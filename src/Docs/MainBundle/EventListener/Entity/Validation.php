<?php

namespace Docs\MainBundle\EventListener\Entity;

use Docs\MainBundle\EventListener\Entity\ValidationErrorBuffer;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\Entity;
use Docs\CommonBundle\Doctrine\EntityInterface;

/**
 * Validation listener
 * @author h.botev
 */
class Validation implements EventSubscriber
{
    /**
     *
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    private $validator;

    /**
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator
     */
    public function __construct(\Symfony\Component\Validator\Validator\ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * {@inheritDoc}
     * @see \Doctrine\Common\EventSubscriber::getSubscribedEvents()
     */
    public function getSubscribedEvents()
    {
        $events = [
            Events::onFlush
        ];

        return $events;
    }

    /**
     * @param \Doctrine\ORM\Event\OnFlushEventArgs $args
     * @throws \Docs\MainBundle\EventListener\Entity\Exception\ValidationException
     */
    public function onFlush(\Doctrine\ORM\Event\OnFlushEventArgs $args)
    {
        $insertionEntities = $args->getEntityManager()->getUnitOfWork()->getScheduledEntityInsertions();
        $updateEntities = $args->getEntityManager()->getUnitOfWork()->getScheduledEntityUpdates();
        $all = array_merge($insertionEntities, $updateEntities);

        $errorBuffer = new ValidationErrorBuffer();
        foreach ($all as $entity) {
            $this->validate($entity, $errorBuffer);
        }

        $errors = $errorBuffer->getErrorCount();
        if (!empty($errors)) {
            $exception = new Exception\ValidationException();
            $exception->setErrorBuffer($errorBuffer);
            throw $exception;
        }
    }

    /**
     *
     * @param EntityInterface $entity
     * @param ValidationErrorBuffer $errorBuffer
     */
    private function validate($entity, ValidationErrorBuffer $errorBuffer)
    {
        $violations = $this->validator->validate($entity);
        /* @var $violations \Symfony\Component\Validator\ConstraintViolationList */

        if ($violations->count() > 0) {
            $errorBuffer->setEntity($entity);
            foreach ($violations as $violation) {
                /* @var $violation \Symfony\Component\Validator\ConstraintViolation */
                $propertyPath = $violation->getPropertyPath();
                if ($propertyPath) {
                    $errorBuffer->setFieldError($propertyPath, $violation->getMessage());
                } else {
                    $errorBuffer->setErrorWithoutField($violation->getMessage());
                }
            }
        }
    }
}
