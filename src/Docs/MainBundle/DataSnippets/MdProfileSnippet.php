<?php
namespace Docs\MainBundle\DataSnippets;

use Docs\MainBundle\DataSnippets\SnippetsInterface;
use Docs\MainBundle\RestClient\DocsUser;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Snippet for md basic profile
 * @author h.botev
 */
class MdProfileSnippet implements SnippetsInterface
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var DocsUser
     */
    protected $docsRestClient;

    public function __construct(DocsUser $docsRC)
    {
        $this->docsRestClient = $docsRC;
    }

    /**
     * Get template name
     * @return string
     */
    public function getSnippetTemplate()
    {
        return "MainBundle:DataSnippets:MdProfileSnippet.html.twig";
    }

    /**
     * Build snippet data
     * @param integer $userID
     */
    public function buildSnippetData($userID)
    {
        $userInfo = $this->docsRestClient->getItemFromCache($userID);

        $mdData = [
            "userID" => $userInfo['userID'],
            "firstName" => $userInfo['firstName'],
            "lastName" => $userInfo['lastName'],
            "email" => $userInfo['email'],
            "created" => new \DateTime($userInfo['created']),
        ];

        $this->data = $mdData;
    }

    /**
     * Return snippet data
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
