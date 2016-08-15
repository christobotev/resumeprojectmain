<?php
namespace Docs\AuthBundle\Controller;

use Docs\AuthBundle\Form\RegisterType as UserForm;
use Docs\CommonBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Docs\AuthBundle\Security\Authentication\SecurityUser;

class RegistrationController extends Controller
{
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserForm::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $securityData = new SecurityUser($user);
            $password = $this->get('security.password_encoder')
                                    ->encodePassword($securityData, $user->getPassword());
            $user->setPassword($password);
            $user->setSalt($securityData->generateSalt());
            $user->setGoogleID(0);

            $em = $this->get("doctrine.orm.entity_manager");
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('/');
        }

        return $this->render(
            'AuthBundle:Register:register.html.twig',
            ['form' => $form->createView()]
        );
    }
}
