<?php
namespace Docs\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;

class ProfilePageController extends Controller
{
    public function indexAction(Request $request, $userID)
    {
        $mdProfileSnippet = $this->get("docs_snippet.md_profile");
        /* @var $mdProfileSnippet \Docs\MainBundle\DataSnippets\MdProfileSnippet */
        $mdProfileSnippet->buildSnippetData($userID);

        $ratingSnippet = $this->get("docs_snippet.md_ratings");
        /* @var $ratingSnippet \Docs\MainBundle\DataSnippets\MdRatingSnippet */
        $ratingSnippet->buildSnippetData($userID);

        $appFormHelper = $this->get("form.appointment_form_helper");
        /* @var $appFormHelper \Docs\MainBundle\Appointment\AppointmentFormHelper */

        $appointmentForm = $appFormHelper->handleFormSubmission($request);

        if (!$appointmentForm instanceof Form) {
            return $this->redirectToRoute($appointmentForm, ['userID' => $userID]);
        }

        $reminderFormHelper = $this->get("form.reminder_form_helper");
        /* @var $reminderFormHelper \Docs\MainBundle\Reminder\ReminderFormHelper */

        $reminderForm = $reminderFormHelper->handleFormSubmission($request);

        if (!$reminderForm instanceof Form) {
            return $this->redirectToRoute($reminderForm);
        }

        return $this->render('MainBundle::contactPage.html.twig', [
            'userID' => $userID,
            'mdProfileSnippet'    => $mdProfileSnippet,
            'mdRatingSnippet'     => $ratingSnippet,
            'appointmentForm'     => $appointmentForm->createView(),
            'reminderForm'        => $reminderForm->createView()
        ]);
    }
}