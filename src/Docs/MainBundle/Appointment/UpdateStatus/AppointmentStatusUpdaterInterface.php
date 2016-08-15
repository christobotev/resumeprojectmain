<?php
namespace Docs\MainBundle\Appointment\UpdateStatus;

/**
 * Interface that must be implemented by
 * classes that update appointment status
 * @author hbotev
 */
interface AppointmentStatusUpdaterInterface
{
    /**
     * @param string $appointmentID
     */
    public function updateAppointment($appointmentID);
}