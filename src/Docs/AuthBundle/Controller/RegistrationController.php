<?php
namespace Docs\AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Docs\CommonBundle\Entity\User;
use Docs\AuthBundle\Form\RegisterType as UserForm;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

/**
 * Registration controller
 * @author h.botev
 *
 */
class RegistrationController extends Controller
{
    /**
     * @param Request $request
     */
    public function registerAction(Request $request)
    {
        $registerService = $this->get('register_helper');
        /* @var $registerService \Docs\AuthBundle\Register\RegisterHelper */

        $registered = '';
        $flashBag = $this->get("session")->getFlashBag();
        /* @var $flashBag \Symfony\Component\HttpFoundation\Session\Flash\FlashBag */
        $flashBag->clear();

        try {
            $user = new User();
            $form = $this->createForm(UserForm::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $registered = $registerService->submitForm($user);
            }
        } catch (\Exception $e) {
            $flashBag->add(
                'error',
                'Could not register user successfully'
            );
            return $this->redirectToRoute('main');
        }

        $flashBag->add(
            'success',
            'Please use your new credentials to log in.'
        );

        if ($registered) {
            return $this->redirectToRoute('main');
        }

        return $this->render(
            'AuthBundle:Register:register.html.twig',
            ['form' => $form->createView()]
        );
    }
}
