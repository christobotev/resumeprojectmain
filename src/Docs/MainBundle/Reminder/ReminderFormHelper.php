<?php
namespace Docs\MainBundle\Reminder;

use Docs\MainBundle\Form\ReminderForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class that helps process Reminder
 * @author hbotev
 */
class ReminderFormHelper
{
    use ContainerAwareTrait;

    /**
     * @param Request $request
     * @return Form|string
     */
    public function handleFormSubmission(Request $request)
    {
        $mdID = $request->get('userID');

        $md = $this->getUserReference($mdID);
        $reminderForm = $this->get('form.factory')->create(
            ReminderForm::class,
            [],
            ['md' => $md]
        );

        if ($request->request->has('reminder_form')) {
            return $this->executeProcessor(
                $request,
                $reminderForm,
                $this->get('reminder.processor')
            );
        }

        return $reminderForm;
    }

    /**
     * @param Request $request
     * @param Form $form
     * @param ReminderProcessor $reminderProcessor
     */
    protected function executeProcessor(
        Request $request,
        Form $form,
        ReminderProcessor $reminderProcessor
    ) {
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return $form;
        }

        $loggedUser = $this->get('security.token_storage')
                            ->getToken()
                                ->getUser()
                                    ->getUser()->getUserID();

        $flashBag = $this->get("session")->getFlashBag();

        try {
            $reminderProcessor->process($form, $loggedUser);

            $flashBag->add(
                'success',
                'Reminder has been created successfully!'
            );

            return 'listDoctors';
        } catch (\Exception $e) {
            throw $e;
            $flashBag->add(
                'error',
                $this->get("translator")->trans('Reminder could not be saved!')
            );
        }

        return $form;
    }

    /**
     * Get user ref
     * @return object \Docs\CommonBundle\Entity\User
     */
    protected function getUserReference($userID)
    {
        $entityManager = $this->get('doctrine.orm.entity_manager');
        $userEntity = $entityManager->getReference(
            "Docs\CommonBundle\Entity\User",
            $userID
        );

        return $userEntity;
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
