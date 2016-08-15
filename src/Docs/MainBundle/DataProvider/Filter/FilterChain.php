<?php
namespace Docs\MainBundle\DataProvider\Filter;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * Filter chain. Used to allow chaining filters
 * from the filter manager
 * @author h.botev
 *
 */
class FilterChain
{
    /**
     * @var FilterManager
     */
    protected $filterManager;

    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param FilterManager $filterManager
     * @param QueryBuilder $queryBuilder
     * @param Request $request
     */
    public function __construct(FilterManager $filterManager, QueryBuilder $queryBuilder, Request $request)
    {
        $this->filterManager = $filterManager;
        $this->queryBuilder = $queryBuilder;
        $this->request = $request;
    }

    /**
     * Applies filter to the current query builder
     * @param string $filterName
     * @return \Docs\MainBundle\DataProvider\Filter\FilterChain
     */
    public function filter($filterName)
    {
        $this->filterManager->filter(
            $filterName,
            $this->queryBuilder,
            $this->request
        );

        return $this;
    }
}
