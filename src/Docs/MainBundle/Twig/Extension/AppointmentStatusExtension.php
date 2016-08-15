<?php
namespace Docs\MainBundle\Twig\Extension;

use Docs\CommonBundle\Entity\Appointment;

/**
 * Get appointment status
 * @author hbotev
 *
 */
class AppointmentStatusExtension extends \Twig_Extension
{
    /**
     * (non-PHPdoc)
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction("isOpen", [$this, "isOpen"]),
            new \Twig_SimpleFunction("isPending", [$this, "isPending"]),
            new \Twig_SimpleFunction("isClosed", [$this, "isClosed"]),
        ];
    }

    /**
     * Check if the appointment status is 'open'
     * @param string $statusID
     * @return boolean
     */
    public function isOpen($statusID)
    {
        return $statusID == Appointment::STATUS_OPEN;
    }

    /**
     * Check if the appointment status is pending or not
     * @param string $statusID
     * @return boolean
     */
    public function isPending($statusID)
    {
        return $statusID == Appointment::STATUS_PENDING;
    }

    /**
     * Checks whether the appointment is closed or not
     * @param string $statusID
     * @return boolean
     */
    public function isClosed($statusID)
    {
        return $statusID == Appointment::STATUS_CLOSED;
    }

    /**
     * (non-PHPdoc)
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return "appointment_status";
    }
}
