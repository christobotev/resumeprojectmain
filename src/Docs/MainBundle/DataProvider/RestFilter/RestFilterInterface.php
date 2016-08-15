<?php
namespace Docs\MainBundle\DataProvider\RestFilter;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface that must be implemented by the Rest filters
 * @author h.botev
 */
interface RestFilterInterface
{
    /**
     * Add conditions to the rest request
     * @param Request $request
     */
    public function filter($restClient, Request $request);

    /**
     * Return the name of the field in the request
     * that should contain the filter value
     */
    public function getFieldName();
}
