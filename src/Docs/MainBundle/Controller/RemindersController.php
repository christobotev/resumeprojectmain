<?php
namespace Docs\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller takes care of reminder
 * listing and creation from modal windows
 * @author hbotev
 *
 */
class RemindersController extends Controller
{
    /**
     * List all reminders
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        $remindersProvider = $this->get('data_provider.reminders');
        /* @var $remindersProvider \Docs\MainBundle\DataProvider\RemindersProvider */

        $userID = $this->getUser()
                            ->getUserID();

        $remindersData = $remindersProvider->getOpenReminder($request, $userID);

        // set it as an attribute for the modal creation option
        $request->attributes->set('userID', $userID);
        $reminderFormHelper = $this->get("form.reminder_form_helper");
        /* @var $reminderFormHelper \Docs\MainBundle\Reminder\ReminderFormHelper */

        $reminderForm = $reminderFormHelper->handleFormSubmission($request);

        return $this->render('MainBundle:DocsGrids:listReminders.html.twig', [
            "reminders"         => $remindersData['reminders'],
            "pagination"        => $remindersData['pagination'],
            "reminderForm"      => $reminderForm->createView()
        ]);
    }

    /**
     * @param Request $request
     * @return \Docs\MainBundle\Controller\Response
     */
    public function addReminderModalAction(Request $request)
    {
        $reminderFormHelper = $this->get("form.reminder_form_helper");
        /* @var $reminderFormHelper \Docs\MainBundle\Reminder\ReminderFormHelper */

        try {
            $userID = $this->getUser()
                                ->getUserID();

            $request->attributes->set('userID', $userID);
            $result = $reminderFormHelper->handleFormSubmission($request);
            if ($result instanceof Form) {
                $errors = $result->getErrors(true,true)[0];
                /* @var $errors \Symfony\Component\Form\FormError */

                return $this->FaildJSONResponce($errors->getMessage());
            }
        } catch (\Exception $e) {
            return $this->FaildJSONResponce();
        }

        return new Response(
            json_encode(['message' => "Success!"]),
            200,
            ["Content-Type" => "application/json"]
        );
    }
}
