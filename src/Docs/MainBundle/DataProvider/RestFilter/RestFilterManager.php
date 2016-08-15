<?php
namespace Docs\MainBundle\DataProvider\RestFilter;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class that manages the data filters
 * @author h.botev
 */
class RestFilterManager
{
    /**
     * @var [RestFilterInterface]
     */
    protected $restFilters = [];

    /**
     * Add filter
     * @param string $name
     * @param RestFilterInterface $restFilter
     * @return \Docs\MainBundle\DataProvider\RestFilter\RestFilterManager
     */
    public function addFilter($name, RestFilterInterface $restFilter)
    {
        $this->restFilters[$name] = $restFilter;

        return $this;
    }

    /**
     * Get filter
     * @param string $name
     * @throws \InvalidArgumentException
     * @return RestFilterInterface
     */
    public function getFilter($name)
    {
        if (!isset($this->restFilters[$name])) {
            throw new \InvalidArgumentException("No data filter {$name}");
        }

        return $this->restFilters[$name];
    }

    /**
     * Apply filter
     * @param string $name
     * @param QueryBuilder $queryBuilder
     * @param Request $request
     * @return Docs\MainBundle\DataProvider\RestFilter\RestFilterManager
     */
    public function filter($name, $restClient, Request $request)
    {
        $this->getFilter($name)->filter($restClient, $request);

        return $this;
    }

    /**
     * Start a chain of filters
     * @param Request $request
     * @return \Docs\MainBundle\DataProvider\RestFilter\RestFilterChain
     */
    public function startChain($restClient, Request $request)
    {
        return new RestFilterChain($this, $restClient, $request);
    }
}
