<?php
namespace Docs\MainBundle\Appointment\UpdateStatus;

use Docs\CommonBundle\Entity\Appointment;

/**
 * Change the appointment status to closed
 * @author h.botev
 */
class ToClose extends AbstractAppointmentStatusUpdater implements AppointmentStatusUpdaterInterface
{
    /**
     * {@inheritDoc}
     * @see \Docs\MainBundle\Appointment\UpdateStatus\AppointmentStatusUpdaterInterface::updateAppointment()
     */
    public function updateAppointment($appointmentID)
    {
        $this->changeStatus($appointmentID, Appointment::STATUS_CLOSED);
    }
}