<?php
namespace Docs\MainBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Event for google calendar
 * @author hbotev
 *
 */
class CalendarWriteEvent extends Event
{
    /**
     * @var \Google_Service_Calendar_Event
     */
    protected $event;

    /**
     * @param array $eventData
     */
    public function __construct(array $eventData)
    {
        $start = new \Google_Service_Calendar_EventDateTime();
        $start->setDateTime($eventData['start']->format(\DateTime::RFC3339));

        $end = new \Google_Service_Calendar_EventDateTime();
        $end->setDateTime($eventData['end']->format(\DateTime::RFC3339));

        $event = new \Google_Service_Calendar_Event([
            'summary' => 'Appointmet with ' . $eventData['appointmentWith'],
            'description' => $eventData['note'],
            'attendees' => [
                ['email' => $eventData['patientEmail']],
            ]
        ]);

        $event->setStart($start);
        $event->setEnd($end);
        $this->event = $event;
    }

    /**
     * Return the raw event data
     * @return \Google_Service_Calendar_Event
     */
    public function getEvent()
    {
        return $this->event;
    }
}
