<?php
namespace Docs\MainBundle\Twig\Extension;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Doctrine\ORM\EntityManager;

/**
 * Extension for reminders data
 * @author h.botev
 *
 */
class RemindersExtension extends \Twig_Extension
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage
     */
    protected $tokenStorage;

    /**
     * Set entity manager
     * @param EntityManager $entityManager
     * @return \Docs\MainBundle\Twig\Extension\RemindersExtension
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    /**
     * Set token storage
     * @param TokenStorage $tokenStorage
     * @return \Docs\MainBundle\Twig\Extension\RemindersExtension
     */
    public function setTokenStorage(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;

        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction("open_reminders_count", [$this, "openReminders"])
        ];
    }

    /**
     * Return the number of open reminders with date in the past
     * @return number
     */
    public function openReminders()
    {
        $reminderRepo = $this->entityManager->getRepository(
            "Docs\CommonBundle\Entity\Reminder"
        );
        /* @var $reminderRepo \Docs\CommonBundle\Repository\ReminderRepository */

        $user = $this->tokenStorage->getToken()->getUser()->getUser();

        return $reminderRepo->getCurrentlyOpenRemindersCount($user->getUserID());
    }

    /**
     * (non-PHPdoc)
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return "open_reminders_count";
    }
}
