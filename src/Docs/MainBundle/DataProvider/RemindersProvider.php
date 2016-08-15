<?php
namespace Docs\MainBundle\DataProvider;

use Docs\CommonBundle\Repository\ReminderRepository;
use Knp\Component\Pager\Paginator;
use Docs\CommonBundle\Entity\Reminder;
use Symfony\Component\HttpFoundation\Request;
use Docs\MainBundle\DataProvider\Filter\FilterManager;

class RemindersProvider implements DataProviderInterface
{
    /**
     * Default items per page
     */
    const ITEMS_PER_PAGE = 10;

    /**
     * @var ReminderRepository
     */
    protected $reminderRepo;

    /**
     * @var Paginator
     */
    protected $paginator;

    /**
     * @param ReminderRepository $reminderRepo
     * @param Paginator $paginator
     * @param FilterManager $filterManager
     */
    public function __construct(
        ReminderRepository $reminderRepo,
        Paginator $paginator,
        FilterManager $filterManager
    ) {
        $this->reminderRepo = $reminderRepo;
        $this->paginator = $paginator;
        $this->filterManager = $filterManager;
    }

    /**
     * @param Request $request
     * @param string $user
     */
    public function getOpenReminder(Request $request, $user = null)
    {
        $queryBuilder = $this->reminderRepo->getQueryBuilder();
        /* @var $queryBuilder \Doctrine\ORM\QueryBuilder */

        $this->filterManager->startChain($queryBuilder, $request)
                                    ->filter('reminderDate');

        $queryBuilder->andWhere($queryBuilder->expr()->eq("Reminder.status", ":open"))
                     ->setParameter(":open", Reminder::STATUS_OPEN);

        if ($user) {
            $queryBuilder->andWhere($queryBuilder->expr()->eq("Reminder.createdBy", ":user"))
                         ->setParameter(":user", $user);
        }

        $pagination = $this->paginator->paginate(
            $queryBuilder,
            $request->get("page", 1),
            $request->get("perPage", self::ITEMS_PER_PAGE)
        );

        $reminders = $pagination->getItems();

        $reminderData = [];
        foreach ($reminders as $key => $reminder) {
            $user = $reminder->getUser();

            // the note is mandatory - but let's keep this just in case
            $note = $reminder->getNote();
            $reminderData[$key]['userID'] = $user->getUserID();
            $reminderData[$key]['user'] = $user->getFirstName() .' ' . $user->getLastName();
            $reminderData[$key]['created'] = $reminder->getCreated();
            $reminderData[$key]['scheduled'] = $reminder->getScheduled();
            $reminderData[$key]['note'] = $note ? $note->getContent() : '';
        }

        return [
         "reminders" => $reminderData,
         "pagination" => $pagination
        ];
    }
}
