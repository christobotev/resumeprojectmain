<?php
namespace Docs\MainBundle\DataSnippets;

use Docs\MainBundle\DataSnippets\SnippetsInterface;
use Docs\CommonBundle\Repository\UserRepository;

/**
 * Snippet for md basic profile
 * @author h.botev
 *
 */
class MdProfileSnippet implements SnippetsInterface
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
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
        $userInfo = $this->userRepository->findOneBy(['userID' => $userID]);

        $mdData = [
            "userID" => $userInfo->getUserID(),
            "firstName" => $userInfo->getFirstName(),
            "lastName" => $userInfo->getLastName(),
            "email" => $userInfo->getEmail(),
            "created" => $userInfo->getCreated(),
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
