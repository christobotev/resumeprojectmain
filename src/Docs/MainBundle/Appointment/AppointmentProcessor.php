<?php
namespace Docs\MainBundle\Appointment;

use Docs\CommonBundle\Entity\Appointment;
use Symfony\Component\Form\FormInterface;
use Docs\CommonBundle\Entity\User;
use Docs\MainBundle\Appointment\AppointmentException;
use Docs\MainBundle\Persistence\Persister;
use Docs\MainBundle\Reference\ReferenceFactory;
use Docs\MainBundle\Note\NoteProcessorLocal;
use Docs\CommonBundle\Entity\Note;
use Docs\CommonBundle\Repository\AppointmentRepository;
use Docs\MainBundle\EventListener\Entity\Exception\ValidationException;

/**
 * Class that takes care of appointment processing
 * @author h.botev
 */
class AppointmentProcessor
{
    /**
     * @var Persister
     */
    protected $persister;

    /**
     * @var ReferenceFactory
     */
    protected $referenceFactory;

    /**
     * @var NoteProcessorLocal
     */
    protected $noteProcessor;

    /**
     * @var AppointmentSymptomsProcessor
     */
    protected $symptomsProcessor;

    /**
     * @var AppointmentRepository
     */
    protected $appointmentRepository;

    /**
     * @param Persister $persister
     * @param ReferenceFactory $refFactory
     * @param NoteProcessorLocal $noteProcessor
     * @param AppointmentSymptomsProcessor $symptomsProcessor
     */
    public function __construct(
        Persister $persister,
        ReferenceFactory $refFactory,
        NoteProcessorLocal $noteProcessor,
        AppointmentSymptomsProcessor $symptomsProcessor,
        AppointmentRepository $appRepo
    ) {
        $this->persister = $persister;
        $this->referenceFactory = $refFactory;
        $this->symptomsProcessor = $symptomsProcessor;
        $this->appointmentRepository = $appRepo;

        // lets say the appointment won't use the rest api
        // for note processing
        $this->noteProcessor = $noteProcessor;
    }

    public function process(FormInterface $form, $loggedUserID)
    {
        $data = $form->getData();
        $this->persister->beginTransaction();

        try {
            $data['user'] = $this->getUserRef($loggedUserID);
            $data['withUser'] = $this->getUserRef($data['withUser']);
            $data['status'] =  $this->referenceFactory
                                            ->getReference(
                                                'Docs\CommonBundle\Entity\AppointmentStatus',
                                                Appointment::STATUS_PENDING
                                            );

            if (!empty($data['content'])) {
                $data['note'] = $this->noteProcessor->createNote($data['content'], $data['user']);
            }

            $appData = $data;
            unset($appData['symptoms']);
            $appointmentPersisted = $this->appointmentRepository
                                                            ->save(
                                                                $appData,
                                                                AppointmentRepository::PERSIST_ENTITY
                                                            );

            // process dependent data
            $this->symptomsProcessor->process($appointmentPersisted, $data['symptoms']);

            // flush all persited entities
            $this->persister->finishTransaction();
        } catch (ValidationException $e) {
            $this->persister->rollback();
            throw $e;
        } catch (\Exception $e) {
            $this->persister->rollBack();
            throw new AppointmentException(
                'Doctrine exception: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * @param string $userID
     */
    protected function getUserRef($userID)
    {
        return $this->referenceFactory
                                ->getReference(
                                    'Docs\CommonBundle\Entity\User',
                                    $userID
                                );
    }
}
