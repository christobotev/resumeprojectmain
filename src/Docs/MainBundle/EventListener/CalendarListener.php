<?php
namespace Docs\MainBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Docs\MainBundle\Event\CalendarWriteEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Docs\MainBundle\Calendar\CalendarWriter;

/**
 * Calendar Listener
 * @author h.botev
 *
 */
class CalendarListener
{
    /**
     * @var CalendarWriter
     */
    protected $calendarWriter;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param EntityManager $entityManager
     * @param CalendarWriter $calendarWriter
     *
     */
    public function __construct(
        CalendarWriter $calendarWriter,
        EventDispatcherInterface $dispatcher
    ) {
        $this->calendarWriter = $calendarWriter;
        $this->eventDispatcher = $dispatcher;
    }

    /**
     * @param CalendarWriteEvent $event
     */
    public function onCalendarWriteRequest(CalendarWriteEvent $event)
    {
        $event = $event->getEvent();

        if ($event instanceof \Google_Service_Calendar_Event) {
            $this->calendarWriter->saveEvent($event);
        }
    }
}
