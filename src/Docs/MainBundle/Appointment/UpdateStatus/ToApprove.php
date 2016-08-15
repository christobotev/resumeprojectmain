<?php
namespace Docs\MainBundle\Appointment\UpdateStatus;

use Docs\CommonBundle\Entity\Appointment;
use Docs\MainBundle\Google\Service\CalendarService;
use Docs\CommonBundle\Repository\AppointmentRepository;
use Docs\MainBundle\Reference\ReferenceFactory;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;
use Docs\MainBundle\Google\GoogleClient;
use Docs\MainBundle\Calendar\CalendarWriter;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Docs\MainBundle\Event\CalendarWriteEvent;

class ToApprove extends AbstractAppointmentStatusUpdater implements AppointmentStatusUpdaterInterface
{
    /**
     * @var CalendarService
     */
    protected $calendar;

    /**
     * @var OAuthToken
     */
    protected $authToken;

    /**
     * @var GoogleClient
     */
    protected $client;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var CalendarWriter
     */
    protected $calendarWriter;

    /**
     * @param CalendarService $calendar
     * @param GoogleClient $client
     * @param AppointmentRepository $appRepo
     * @param ReferenceFactory $refFactory
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        CalendarService $calendar,
        GoogleClient $client,
        AppointmentRepository $appRepo,
        ReferenceFactory $refFactory,
        EventDispatcherInterface $eventDispatcher,
        CalendarWriter $calendarWriter
    ) {
        $this->calendar = $calendar;
        $this->client = $client;
        $this->eventDispatcher = $eventDispatcher;
        $this->calendarWriter = $calendarWriter;

        parent::__construct($appRepo, $refFactory);
    }

    /**
     * Update appointment status to open /Approved/
     * and save the event in google calendar
     * @param string $appointmentID
     */
    public function updateAppointment($appointmentID)
    {
        // Save the appointment into google calendar
        // happens only on approval
        $this->saveInGoogle($appointmentID);

        // Change it's status in our system
        $this->changeStatus($appointmentID, Appointment::STATUS_OPEN);
    }

    /**
     * @param string $appointmentID
     */
    protected function saveInGoogle($appointmentID)
    {
        $appointment = $this->getAppInfo($appointmentID);

        $appWith = $appointment->getWithUser();

        $calendarService = $this->calendar
                                    ->getCalendar(
                                        $this->authToken,
                                        $this->client
                                    );

        $securityUser = $this->authToken->getUser();
        $this->calendarWriter->setService($calendarService);
        $this->calendarWriter->setCalendarID(
                                    $securityUser->getUser()
                                                    ->getEmail()
                                );

        // Raise calendar.write event
        $end = clone $appointment->getScheduled();
        $endDateTime = $end->add(new \DateInterval("PT1H"));
        $this->eventDispatcher->dispatch(
            "calendar.write",
            new CalendarWriteEvent([
                'start' => $appointment->getScheduled(),
                'end' => $endDateTime,
                'note' => $appointment->getNote()->getContent(),
                'appointmentWith' => $appWith->getFirstName() . ' ' . $appWith->getLastName(),
                'patientEmail' => $appWith->getEmail()
            ])
        );
    }

    /**
     * Auth token for google calendar
     * @param OAuthToken $token
     */
    public function setToken(OAuthToken $token)
    {
        $this->authToken = $token;
    }
}