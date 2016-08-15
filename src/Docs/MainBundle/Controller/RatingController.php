<?php
namespace Docs\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Docs\MainBundle\Rating\RatingHolder;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller repsponsible for saving and listing
 * MD's rating
 * @author hbotev
 */
class RatingController extends Controller
{
    /**
     * @param Request $request
     * @param string $userID
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request, $userID)
    {
        $ratingSnippet = $this->get("docs_snippet.md_ratings");
        /* @var $ratingSnippet \Docs\MainBundle\DataSnippets\MdRatingSnippet */

        if ($request->query->has("page")) {
            $ratingSnippet->setCurrentPage($request->query->get("page"));
        }

        $ratingSnippet->buildSnippetData($userID);

        $options = ['userID' => $userID, 'mdRatingSnippet' => $ratingSnippet];
        return $this->render(
            $ratingSnippet->getSnippetTemplate(),
            $options
        );
    }

    /**
     * save rating
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function saveAction(Request $request)
    {
        $ratingProcessor = $this->get('form.rating_processor');
        /* @var $ratingProcessor \Docs\MainBundle\Rating\RatingProcessor */

        try {
            $ratingHolder = new RatingHolder($request);
            $ratingProcessor->process($ratingHolder);

            // reloading the grid, so it's good to have some feedback
            $flashBag = $this->get("session")->getFlashBag();
            $flashBag->add(
                'success',
                'Rating has been saved!'
            );
        } catch (\Exception $e) {
            return new Response(
                json_encode(['message' => 'Error']),
                404,
                ["Content-Type" => "application/json"]
            );
        }

        return new Response(
            json_encode(['message' => "Rating saved Successfuly!"]),
            200,
            ["Content-Type" => "application/json"]
        );
    }
}
