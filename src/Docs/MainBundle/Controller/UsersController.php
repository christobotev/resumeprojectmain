<?php
namespace Docs\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Docs\MainBundle\Form\AppointmentForm;
use Docs\MainBundle\Form\RatingForm;

/**
 * Controller that takes care of user listings
 * @author hbotev
 *
 */
class UsersController extends Controller
{
    /**
     * Action that lists available doctors
     * such that have not more than 3
     * open appointments
     * @param Request $request
     */
    public function listAvailableAction(Request $request)
    {
        $docsProvider = $this->get("data_provider.doctors");
        /* @var $docsProvider \Docs\MainBundle\DataProvider\DocsProvider */

        if ($request->query->has("page")) {
            $docsProvider->setCurrentPage($request->query->get("page"));
        }

        $userData = $docsProvider->getAvailableDocs($request);

        $appForm = $this->createForm(
            AppointmentForm::class,
            [],
            ['action' => $this->get("router")->generate('addAppointment'),
             'entityManager' => $this->get('doctrine.orm.entity_manager')]
        );

        $ratingForm = $this->createForm(
            RatingForm::class,
            [],
            ['action' => $this->get("router")->generate('addRating')]
        );

        return $this->render("MainBundle:DocsGrids:availableDoctors.html.twig", [
            "users"             => $userData['users'],
            "pagination"        => $userData['pagination'],
            "appointmentForm"   => $appForm->createView(),
            "ratingForm"        => $ratingForm->createView()
        ]);
    }

    /**
     * Action that lists all doctors from the database
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAllAction(Request $request)
    {
        $docsProvider = $this->get("data_provider.rest.doctors");
        /* @var $docsProvider \Docs\MainBundle\DataProvider\DocsRestProvider */

        if ($request->query->has("page")) {
            $docsProvider->setCurrentPage($request->query->get("page"));
        }

        $userData = $docsProvider->getAllDocs($request);

        return $this->render("MainBundle:DocsGrids:allDoctors.html.twig", [
            "users" => $userData,
            "usersCount" => $docsProvider->getDoctorsCount(),
            "currentPage" => $docsProvider->getCurrentPage()
        ]);
    }

    /**
     * Grid Pagination Action
     * for the "all doctors" list
     * @param Request $request
     */
    public function listAllGridAction(Request $request)
    {
        $docsProvider = $this->get("data_provider.rest.doctors");
        /* @var $docsProvider \Docs\MainBundle\DataProvider\DocsRestProvider */

        if ($request->query->has("page")) {
            $docsProvider->setCurrentPage($request->query->get("page"));
        }

        $userData = $docsProvider->getAllDocs($request);

        return $this->render("MainBundle:DocsGrids:allDoctorsPaging.html.twig", [
            "users" => $userData,
            "usersCount" => $docsProvider->getDoctorsCount(),
            "currentPage" => $docsProvider->getCurrentPage()
        ]);
    }
}
