<?php
namespace Docs\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use HWI\Bundle\OAuthBundle\Security\Core\Authentication\Token\OAuthToken;

class AppointmentUpdateController extends Controller
{
    /**
     * Approve appointment
     * @param Request $request
     */
    public function approveAction(Request $request)
    {
        $approvementManager = $this->get('appointment.toApprove');
        /* @var $approvementManager \Docs\MainBundle\Appointment\UpdateStatus\ToApprove */

        $token = $this->get('security.token_storage')->getToken();
        if (!$token instanceof OAuthToken) {
            // set flashbag error and redirect to apps list
        }

        $approvementManager->setToken($token);
        try {
            $flashBag = $this->get("session")->getFlashBag();
            $approvementManager->updateAppointment($request->get('appointmentID'));

            $flashBag->add(
                'success',
                'Appointment saved successfully!'
            );
        } catch (\Exception $e) {
            $flashBag->add(
                'error',
                'Appointment could not be saved at this time, please try again!'
            );
        }

        return $this->redirect($this->generateUrl('manageAppointments'));

    }
}
