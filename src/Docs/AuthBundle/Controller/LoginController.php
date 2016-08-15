<?php
namespace Docs\AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Docs\AuthBundle\Form\LoginType as LoginForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * Login controller
 * @author hbotev
 */
class LoginController extends Controller
{
    public function loginAction(Request $request)
    {
        $form = $this->createForm(
            LoginForm::class,
            []
        );

        return $this->render(
            'AuthBundle:Security:login.html.twig',
            [
                'form' => $form->createView(),
                'error' => '',
                'last_username' => ''
            ]
        );
    }

    public function loginCheckAction(Request $request)
    {
        return $this->redirect($this->container->getParameter('mainURL'));
    }
}
