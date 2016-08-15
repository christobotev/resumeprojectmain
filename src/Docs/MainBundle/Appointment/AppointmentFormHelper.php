<?php
namespace Docs\MainBundle\Appointment;

use Docs\MainBundle\Form\AppointmentForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Docs\MainBundle\EventListener\Entity\Exception\ValidationException;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class that helps process appointment
 * @author hbotev
 *
 */
class AppointmentFormHelper
{
    use ContainerAwareTrait;

    /**
     * If the form is not being submitted, it will return Form instance
     * @param Request $request
     * @return Form|string
     */
    public function handleFormSubmission(Request $request)
    {
        $userID = $request->get('userID');
        $appForm = $this->get('form.factory')->create(
            AppointmentForm::class,
            [],
            ['entityManager' => $this->get('doctrine.orm.entity_manager'),
             'userID' => $userID
            ]
        );

        if ($request->request->has('appointment_form')) {
            return $this->executeProcessor(
                $request,
                $appForm,
                $this->get("form.appointment_processor")
            );
        }

        return $appForm;
    }

    /**
     * @param Request $request
     * @param Form $form
     * @param AppointmentProcessor $processor
     */
    protected function executeProcessor(
        Request $request,
        Form $form,
        AppointmentProcessor $processor
    ) {
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return $form;
        }

        $loggedUserID = $this->get('security.token_storage')
                            ->getToken()
                                ->getUser()
                                    ->getUser()->getUserID();

        $flashBag = $this->get("session")->getFlashBag();

        try {
            $processor->process($form, $loggedUserID);
            $flashBag->add(
                'success',
                'Appointment request has been send successfully!'
            );

            return 'docMain';
        } catch (ValidationException $e) {
            // Exceptions on flush()
            $errors = $e->getErrorBuffer()
                                ->getAllFieldErrors();

            if ($errors) {
                foreach ($errors as $error) {
                    // set the error to the field
                    if ($form->has(key($error))) {
                        $form->get(key($error))
                                ->addError(new FormError($error[key($error)]));
                    }
                }
            }

            $flashBag->add(
                'error',
                $this->get("translator")->trans('There were errors in the form.')
            );
        } catch (AppointmentException $e) {
            // Any other exception from processing
            $flashBag->add(
                'error',
                $this->get("translator")->trans('Form could not be processed!')
            );
            throw $e;
        }

        return $form;
    }

    /**
     * Get user info
     * @return object \Docs\CommonBundle\Entity\User
     */
    protected function getUser($userID)
    {
        $entityManager = $this->get('doctrine.orm.entity_manager');
        $user = $entityManager->getRepository("\Docs\CommonBundle\Entity\User")
                                    ->findOneBy(['userID' => $userID]);

        return $user;
    }

    /**
     * Return service from the container
     * @param string $service
     * @return object
     */
    protected function get($service)
    {
        return $this->container->get($service);
    }
}
