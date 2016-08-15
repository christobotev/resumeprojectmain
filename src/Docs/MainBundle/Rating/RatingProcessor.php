<?php
namespace Docs\MainBundle\Rating;

use Docs\MainBundle\Rating\RatingHolder;
use Docs\MainBundle\EventListener\Entity\Exception\ValidationException;
use Docs\MainBundle\Persistence\Persister;
use Docs\MainBundle\Reference\ReferenceFactory;
use Docs\MainBundle\Note\NoteProcessorLocal;
use Docs\CommonBundle\Repository\RatingRepository;
use Docs\CommonBundle\Doctrine\Repository\AbstractRepository;

/**
 * Class that takes care
 * of rating processing
 * @author hbotev
 */
class RatingProcessor
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
     * @var RatingRepository
     */
    protected $ratingRepository;

    /**
     * @param Persister $persister
     * @param ReferenceFactory $refFactory
     * @param NoteProcessorLocal $noteProcessor
     * @param RatingRepository $ratingRepo
     */
    public function __construct(
        Persister $persister,
        ReferenceFactory $refFactory,
        NoteProcessorLocal $noteProcessor,
        RatingRepository $ratingRepo
    ) {
        $this->persister = $persister;
        $this->referenceFactory = $refFactory;
        $this->noteProcessor = $noteProcessor;
        $this->ratingRepository = $ratingRepo;
    }

    /**
     * @param RatingHolder $ratingHolder
     * @throws ValidationException
     * @throws RatingException
     */
    public function process(RatingHolder $ratingHolder)
    {
        $user = $this->referenceFactory->getReference(
            "Docs\CommonBundle\Entity\User",
            $ratingHolder->getUserID()
        );

        $createdBy = $this->referenceFactory->getReference(
            "Docs\CommonBundle\Entity\User",
            $ratingHolder->getCreatedBy()
        );

        $this->persister->beginTransaction();
        try {
            $notePersisted = '';
            if (!empty($ratingHolder->getComment())) {
                $notePersisted = $this->noteProcessor->createNote($ratingHolder->getComment(), $createdBy);
            }

            // prepare data
            $ratingData = [
                'note' => $notePersisted,
                'rating' => $ratingHolder->getRating(),
                'user' => $user,
                'createdBy' => $createdBy
            ];

            // persist the new rating
            $this->ratingRepository->save(
                $ratingData,
                AbstractRepository::PERSIST_ENTITY
            );

            $this->persister->finishTransaction();
        } catch (ValidationException $e) {
            $this->persister->rollback();
            throw $e;
        } catch (\Exception $e) {
            $this->persister->rollback();
            throw new RatingException(
                'Doctrine exception: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
}
