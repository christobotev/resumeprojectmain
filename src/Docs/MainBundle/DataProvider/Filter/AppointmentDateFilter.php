<?php
namespace Docs\MainBundle\DataProvider\Filter;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * Filter appointments by date
 * @author h.botev
 */
class AppointmentDateFilter implements FilterInterface
{
    /* (non-PHPdoc)
     * @see \Docs\MainBundle\DataProvider\Filter\FilterInterface::filter()
     */
    public function filter(QueryBuilder $queryBuilder, Request $request)
    {
        if (!$request->query->has("appointmentFrom") && !$request->query->has("appointmentTo")) {
            return;
        }

        if ($request->query->has("appointmentFrom")) {
            $date = new \DateTime(
                $request->query->get("appointmentFrom")
            );

            $date->modify("midnight");

            $queryBuilder->andWhere($queryBuilder->expr()->gte("Appointment.scheduled", ":appointmentFrom"))
                         ->setParameter(":appointmentFrom", $date);
        }

        if ($request->query->has("appointmentTo")) {
            $date = new \DateTime(
                $request->query->get("appointmentTo")
            );

            $date->modify("tomorrow midnight -1 second");

            $queryBuilder->andWhere($queryBuilder->expr()->lte("Appointment.scheduled", ":appointmentTo"))
                         ->setParameter(":appointmentTo", $date);
        }
    }

    /**
     * (non-PHPdoc)
     * @see \Docs\MainBundle\DataProvider\Filter\FilterInterface::getFieldName()
     */
    public function getFieldName()
    {
        return ["appointmentFrom", "appointmentTo"];
    }
}
