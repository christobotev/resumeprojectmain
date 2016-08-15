<?php
namespace Docs\MainBundle\Appointment;

use Docs\MainBundle\Persistence\Persister;
use Docs\CommonBundle\Entity\Appointment;
use Docs\MainBundle\Reference\ReferenceFactory;
use Docs\CommonBundle\Repository\AppointmentSymptomsRepository;

/**
 * Class taking care of
 * appointment symptoms creation
 * @author hbotev
 */
class AppointmentSymptomsProcessor
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
     * @var AppointmentSymptomsRepository
     */
    protected $appointmentSymptomsRepo;

    /**
     * @param Persister $persister
     * @param ReferenceFactory $refFactory
     * @param AppointmentSymptomsRepository $asRepo
     */
    public function __construct(
        Persister $persister,
        ReferenceFactory $refFactory,
        AppointmentSymptomsRepository $asRepo
    ) {
        $this->persister = $persister;
        $this->referenceFactory = $refFactory;
        $this->appointmentSymptomsRepo = $asRepo;
    }

    /**
     * @param Appointment $appointment
     * @param array $symptoms
     */
    public function process(Appointment $appointment, array $symptoms)
    {
        foreach ($symptoms as $symptom) {
            $symptomRef = $this->getSymptomReference($symptom);
            $this->appointmentSymptomsRepo->save(
                ['appointment' => $appointment, 'symptom' => $symptomRef],
                AppointmentSymptomsRepository::PERSIST_ENTITY
            );
        }

    }

    /**
     * @param int $symptomID
     */
    protected function getSymptomReference($symptomID)
    {
        return $this->referenceFactory->getReference(
            'Docs\CommonBundle\Entity\Symptom',
            $symptomID
        );
    }
}
