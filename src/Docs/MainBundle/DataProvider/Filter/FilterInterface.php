<?php
namespace Docs\MainBundle\DataProvider\Filter;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface that must be implemented by the data filters
 * @author h.botev
 *
 */
interface FilterInterface
{
    /**
     * Add coditions to the query builder
     * @param QueryBuilder $queryBuilder
     * @param Request $request
     */
    public function filter(QueryBuilder $queryBuilder, Request $request);

    /**
     * Return the name of the field in the request
     * that should contain the filter value
     */
    public function getFieldName();
}
