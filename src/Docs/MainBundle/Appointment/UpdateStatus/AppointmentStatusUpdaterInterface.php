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
     * Manage anything additional before
     * calling the abstract changeStatus
     * @param string $appointmentID
     */
    public function updateAppointment($appointmentID);
}