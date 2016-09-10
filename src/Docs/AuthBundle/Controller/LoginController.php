<?php
namespace Docs\AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Docs\AuthBundle\Form\LoginType as LoginForm;

/**
 * Login controller
 * @author h.botev
 */
class LoginController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction()
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

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function loginCheckAction()
    {
        return $this->redirect($this->container->getParameter('mainURL'));
    }
}
