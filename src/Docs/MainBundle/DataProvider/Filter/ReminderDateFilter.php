<?php
namespace Docs\MainBundle\DataProvider\Filter;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * Filter reminders by dates
 * @author h.botev
 */
class ReminderDateFilter implements FilterInterface
{
    /* (non-PHPdoc)
     * @see \Docs\MainBundle\DataProvider\Filter\FilterInterface::filter()
     */
    public function filter(QueryBuilder $queryBuilder, Request $request)
    {
        if (!$request->query->has("reminderFrom") && !$request->query->has("reminderTo")) {
            return;
        }

        if ($request->query->has("reminderFrom")) {
            $date = new \DateTime(
                $request->query->get("reminderFrom")
            );

            $date->modify("midnight");

            $queryBuilder->andWhere($queryBuilder->expr()->gte("Reminder.scheduled", ":reminderFrom"))
                         ->setParameter(":reminderFrom", $date);
        }

        if ($request->query->has("reminderTo")) {
            $date = new \DateTime(
                $request->query->get("reminderTo")
            );

            $date->modify("tomorrow midnight -1 second");

            $queryBuilder->andWhere($queryBuilder->expr()->lte("Reminder.scheduled", ":reminderTo"))
                         ->setParameter(":reminderTo", $date);
        }
    }

    /**
     * (non-PHPdoc)
     * @see \Docs\MainBundle\DataProvider\Filter\FilterInterface::getFieldName()
     */
    public function getFieldName()
    {
        return ["reminderFrom", "reminderTo"];
    }
}
