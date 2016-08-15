<?php
namespace Docs\MainBundle\DataProvider\RestFilter;

use Symfony\Component\HttpFoundation\Request;

class UserRoleFilter implements RestFilterInterface
{
    /**
     * {@inheritDoc}
     * @see \Docs\MainBundle\DataProvider\RestFilter\RestFilterInterface::filter()
     */
    public function filter($restClient, Request $request)
    {
        $restClient->getQueryBuilder()
                        ->addCondition(
                            ["field" => "name",
                            "operator" => "EQ",
                            "value" => 'ROLE_DOC',
                            "entity" => 'Role']
                        );
    }

    /**
     * (non-PHPdoc)
     * @see \Docs\MainBundle\DataProvider\RestFilter\RestFilterInterface::getFieldName()
     */
    public function getFieldName()
    {
        return "userRole";
    }
}
