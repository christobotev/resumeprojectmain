<?php
namespace Docs\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class AppointmentsController extends Controller
{
    public function listAction(Request $request)
    {
        $appointmentsProvider = $this->get('data_provider.appointments');
        /* @var $appointmentsProvider \Docs\MainBundle\DataProvider\AppointmentsProvider */

        $user = $this->getUser();
        $appointmentsData = $appointmentsProvider->getAppointments($request, $user);

        $appStatuses = $this->get("repository.appointmentStatuses")
                                                            ->findAll();

        // get appointment statuses for dropdown
        return $this->render('MainBundle:DocsGrids:listAppointments.html.twig', [
            "appointments"  => $appointmentsData['appointments'],
            "pagination"    => $appointmentsData['pagination'],
            "appointmentStatuses" => $appStatuses
        ]);
    }

    public function listClosedAction()
    {
        return new Response('list closed action');
    }

    public function addAppointmentModalAction(Request $request)
    {
        $appFormHelper = $this->get("form.appointment_form_helper");
        /* @var $appFormHelper \Docs\MainBundle\Appointment\AppointmentFormHelper */

        try {
            $result = $appFormHelper->handleFormSubmission($request);

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

    protected function FaildJSONResponce($error = 'Failed!')
    {
        return new Response(
            json_encode(['message' => $error]),
            404,
            ["Content-Type" => "application/json"]
        );
    }
}
