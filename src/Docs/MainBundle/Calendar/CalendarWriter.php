<?php
namespace Docs\MainBundle\Calendar;

/**
 * class that writes data to
 * Google Calendar
 *
 * @author hbotev
 */
class CalendarWriter
{
    /**
     * @var \Google_Service_Calendar
     */
    protected $service;

    /**
     * @var string
     */
    protected $calendarID;

    /**
     * Save Google calendar event
     * @param \Google_Service_Calendar_Event $data
     */
    public function saveEvent(\Google_Service_Calendar_Event $event)
    {
        return $this->service
                        ->events
                            ->insert($this->calendarID, $event);
    }

    /**
     * @param \Google_Service_Calendar $service
     * @return \Docs\MainBundle\Calendar\CalendarWriter
     */
    public function setService(\Google_Service_Calendar $service)
    {
        $this->service = $service;
        return $this;
    }

    /**
     * @param string $calendarID
     * @return \Docs\MainBundle\Calendar\CalendarWriter
     */
    public function setCalendarID($calendarID)
    {
        $this->calendarID = $calendarID;
        return $this;
    }
}