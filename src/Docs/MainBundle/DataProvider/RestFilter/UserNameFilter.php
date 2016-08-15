<?php
namespace Docs\MainBundle\DataProvider\RestFilter;

use Symfony\Component\HttpFoundation\Request;

class UserNameFilter implements RestFilterInterface
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function __construct(\Doctrine\ORM\EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * 1st option : have a db field which contains both names
     * 2nd option : build a 'complex addition' feature with logical operators (no time at this point)
     *
     * At this point, will settle for %username% considering google users
     * would have both their names as username
     *
     * {@inheritDoc}
     * @see \Docs\MainBundle\DataProvider\RestFilter\RestFilterInterface::filter()
     */
    public function filter($restClient, Request $request)
    {
        if ($request->query->has('name')) {
            $username = $request->get('name');
            /* @var $restClient \Inkasso\MainBundle\RestClient\CwcCommunications */
            $restClient->getQueryBuilder()
                            ->addCondition(
                                ["field" => "username",
                                "operator" => "like",
                                "value" => "%" . $username . "%",
                                "entity" => "User"]
                            );
        }
    }

    /**
     * (non-PHPdoc)
     * @see \Docs\MainBundle\DataProvider\RestFilter\RestFilterInterface::getFieldName()
     */
    public function getFieldName()
    {
        return "userName";
    }
}
