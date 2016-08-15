<?php
namespace Docs\MainBundle\DataProvider;

use Symfony\Component\HttpFoundation\Request;
use Docs\CommonBundle\Entity\User;
use Docs\MainBundle\DataProvider\RestFilter\RestFilterManager;
use Docs\MainBundle\RestClient\DocsUserRoles;

/**
 * Just to show how would it be if we
 * need to get a paginated result out from
 * an API
 * @author h.botev
 *
 */
class DocsRestProvider implements DataProviderInterface
{
    /**
     * Default items per page
     */
    const ITEMS_PER_PAGE = 10;

    /**
     * Unavailable flag
     * not used for now
     * @var string 1|2
     */
    protected $unavailable = false;

    /**
     * The total count of cwc entries
     * @var int
     */
    protected $docsCount = 0;

    /**
     * @var int
     */
    protected $showPage = 1;

    /**
     * @var DocsUserRoles
     */
    protected $restClient;

    /**
     * @var RestFilterManager
     */
    protected $restFilterManager;

    public function __construct(DocsUserRoles $docsRestClient, RestFilterManager $restFilterManager)
    {
        $this->restClient = $docsRestClient;
        $this->restFilterManager = $restFilterManager;
    }

    /**
     * Make a get request to the API
     * to get all doctors
     * @param Request $request
     */
    public function getAllDocs(Request $request)
    {
        // set the filters to the rest client
        $this->restFilterManager->startChain($this->restClient, $request)
                                ->filter('userName')
                                ->filter('userRole');

        $usersRest = $this->restClient->findBy([
            "itemCountPerPage" => static::ITEMS_PER_PAGE,
            "offset" => static::ITEMS_PER_PAGE * ($this->showPage - 1)
        ]);

        $result = $usersRest->getData();

        $this->docsCount = $usersRest->getCount();

        $usersData = [];
        foreach ($result as $user) {
            $user = $user['user'];
            $usersData[$user['userID']] = [
                "username" => $user['username'],
                "roles" => $user['roles'],
                "userID" => $user['userID'],
                "email" => $user['email'],
                "firstName" => $user['firstName'],
                "lastName" => $user['lastName'],
                "created" => new \DateTime($user['created'])
            ];
        }

        return $usersData;
    }

    /**
     * Return the total amount of doctors
     * @return int
     */
    public function getDoctorsCount()
    {
        return $this->docsCount;
    }

    /**
     * Set the number of the current page
     * @param unknown $page
     * @return DocsProvider
     */
    public function setCurrentPage($page)
    {
        $this->showPage = (int) $page;
        return $this;
    }

    public function getUnavailable()
    {
        return $this->unavailable;
    }

    /**
     * Set docs provider as unavailable
     * @param bool $bool
     * @return DocsProvider
     */
    public function setUnavailable($bool)
    {
        $this->unavailable = $bool;
        return $this;
    }

    /**
     * @return number
     */
    public function getCurrentPage()
    {
        return $this->showPage;
    }
}
