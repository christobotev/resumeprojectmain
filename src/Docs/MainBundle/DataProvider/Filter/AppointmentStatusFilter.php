<?php
namespace Docs\MainBundle\DataProvider\Filter;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * Filter appointments by status
 * @author h.botev
 */
class AppointmentStatusFilter implements FilterInterface
{

    /* (non-PHPdoc)
     * @see \Docs\MainBundle\DataProvider\Filter\FilterInterface::filter()
     */
    public function filter(QueryBuilder $queryBuilder, Request $request)
    {
        $status = $request->query->get($this->getFieldName());

        if ($request->query->get($this->getFieldName())) {
            $queryBuilder->andWhere($queryBuilder->expr()->in("Appointment.status", ":statusID"))
                         ->setParameter(":statusID", $status);
        }
    }

    /**
     * (non-PHPdoc)
     * @see \Docs\MainBundle\DataProvider\Filter\FilterInterface::getFieldName()
     */
    public function getFieldName()
    {
        return "appointmentStatus";
    }
}
