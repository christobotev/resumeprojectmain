<?php
namespace Docs\MainBundle\DataProvider\RestFilter;

use Symfony\Component\HttpFoundation\Request;

/**
 * Filter chain. Used for chaining filters
 * @author h.botev
 */
class RestFilterChain
{
    /**
     * @var RestFilterManager
     */
    protected $restFilterManager;

    /**
     * @var Rest Client
     */
    protected $restClient;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param FilterManager $filterManager
     * @param QueryBuilder $queryBuilder
     * @param Request $request
     */
    public function __construct(RestFilterManager $restFilterManager, $restClient, Request $request)
    {
        $this->restFilterManager = $restFilterManager;
        $this->restClient = $restClient;
        $this->request = $request;
    }

    /**
     * Applies filter to the current rest client
     * @param string $filterName
     * @return \Docs\MainBundle\DataProvider\RestFilter\RestFilterChain
     */
    public function filter($filterName)
    {
        $this->restFilterManager->filter(
            $filterName,
            $this->restClient,
            $this->request
        );

        return $this;
    }
}
