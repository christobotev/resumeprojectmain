<?php
namespace Docs\MainBundle\DataProvider\Filter;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class that manages the data filters
 * @author h.botev
 *
 */
class FilterManager
{
    /**
     * @var multiple:FilterInterface
     */
    protected $filters = [];

    /**
     * Add filter
     * @param string $name
     * @param FilterInterface $filter
     * @return \Docs\MainBundle\DataProvider\Filter\FilterManager
     */
    public function addFilter($name, FilterInterface $filter)
    {
        $this->filters[$name] = $filter;

        return $this;
    }

    /**
     * Get filter
     * @param string $name
     * @throws \InvalidArgumentException
     * @return FilterInterface
     */
    public function getFilter($name)
    {
        if (!isset($this->filters[$name])) {
            throw new \InvalidArgumentException("No data filter {$name}");
        }

        return $this->filters[$name];
    }

    /**
     * Apply filter
     * @param string $name
     * @param QueryBuilder $queryBuilder
     * @param Request $request
     * @return Docs\MainBundle\DataProvider\Filter\FilterManager
     */
    public function filter($name, QueryBuilder $queryBuilder, Request $request)
    {
        $this->getFilter($name)->filter($queryBuilder, $request);

        return $this;
    }

    /**
     * Start a chain of filters
     * @param QueryBuilder $queryBuilder
     * @param Request $request
     * @return \Docs\MainBundle\DataProvider\Filter\FilterChain
     */
    public function startChain(QueryBuilder $queryBuilder, Request $request)
    {
        return new FilterChain($this, $queryBuilder, $request);
    }
}
