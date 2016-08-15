<?php
namespace Docs\MainBundle\Reference;

use Doctrine\ORM\EntityManager;

/**
 * Factory class giving the option
 * to get Entity reference object
 * @author h.botev
 */
class ReferenceFactory
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $entityName
     * @param mixed $id
     * @return object The entity reference.
     *
     * @throws ORMException
     */
    public function getReference($entityName, $id)
    {
        return $this->entityManager
                            ->getReference($entityName, $id);
    }

    /**
     * @param string $entityName The name of the entity type.
     * @param mixed  $identifier The entity identifier.
     *
     * @return object The (partial) entity reference.
     */
    public function getPartialReference($entityName, $identifier)
    {
        return $this->entityManager
                            ->getPartialReference(
                                $entityName,
                                $identifier
                            );
    }
}
