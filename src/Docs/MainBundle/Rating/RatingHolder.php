<?php
namespace Docs\MainBundle\Rating;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class that holds Rating info
 *
 * This way we get rid of the huge $request
 * @author h.botev
 *
 */
class RatingHolder
{
    protected $comment;
    protected $rating;
    protected $userID;
    protected $createdBy;

    public function __construct(Request $request)
    {
        $rating = $request->get('rating');
        $this->comment = $rating['comment'];
        $this->rating = $rating['rating'];
        $this->userID = $rating['userID'];
        $this->createdBy = $rating['fromUser'];
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function getRating()
    {
        return $this->rating;
    }

    public function getUserID()
    {
        return $this->userID;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
    }
}
