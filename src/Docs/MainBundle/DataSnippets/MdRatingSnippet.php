<?php
namespace Docs\MainBundle\DataSnippets;

use Docs\MainBundle\DataSnippets\SnippetsInterface;
use Docs\CommonBundle\Repository\RatingRepository;

/**
 * Snippet for MD rating
 * @author h.botev
 *
 */
class MdRatingSnippet implements SnippetsInterface
{
    /**
     * Default items per page
     */
    const ITEMS_PER_PAGE = 10;

    /**
     * @var integer
     */
    protected $showPage = 1;

    /**
     * The total count of rating entries
     * @var int
     */
    protected $ratingsCount = 0;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var RatingRepository
     */
    protected $userRatingsRepository;

    public function __construct(RatingRepository $urRepo)
    {
        $this->userRatingsRepository = $urRepo;
    }

    /**
     * Get template name
     * @return string
     */
    public function getSnippetTemplate()
    {
        return "MainBundle:DataSnippets:MdRatingSnippet.html.twig";
    }

    /**
     * Build snippet data
     * @param integer $userID
     */
    public function buildSnippetData($userID)
    {
        $ratings = $this->userRatingsRepository->findBy(
            ['user' => $userID],
            [],
            static::ITEMS_PER_PAGE,
            static::ITEMS_PER_PAGE * ($this->showPage - 1)
        );

        if (empty($this->ratingsCount)) {
            $qb = $this->userRatingsRepository->cloneQueryBuilder();
            $qb->select("count(Rating)")
                ->where($qb->expr()->eq("Rating.user", ":userID"))
                ->setParameter(":userID", $userID);

            $result = $qb->getQuery()->getOneOrNullResult();
            $this->ratingsCount = $result[1];
        }

        $ratingsArray = [];
        foreach ($ratings as $rating) {
            $createdBy = $rating->getCreatedBy();

            $ratingsArray[] = [
                'rating' => $rating->getRating(),
                'note' => $rating->getNote()->getContent(),
                'createdBy' => isset($createdBy) ? $createdBy->getFirstName(). " " . $createdBy->getLastName() : '',
                'created' => $rating->getCreated()
            ];
        }

        $this->data = $ratingsArray;
    }

    /**
     * Return snippet data
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the current page number
     * @param int $page
     * @return \Docs\MainBundle\DataSnippets\MdRatingSnippet
     */
    public function setCurrentPage($page)
    {
        $this->showPage = (int) $page;
        return $this;
    }

    /**
     * Return the total number of ratings available
     * for this user
     * @return int
     */
    public function getRatingsCount()
    {
        return $this->ratingsCount;
    }
}
