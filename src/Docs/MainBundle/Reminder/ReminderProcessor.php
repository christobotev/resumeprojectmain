<?php
namespace Docs\MainBundle\Reminder;

use Docs\CommonBundle\Entity\Reminder;
use Symfony\Component\Form\FormInterface;
use Docs\CommonBundle\Entity\User;
use Docs\MainBundle\Processor\Exception\ReminderException;
use Docs\MainBundle\RestClient\DocsReminder;

/**
 * Class that processes reminder
 * given reminder form
 * @author hbotev
 */
class ReminderProcessor
{
    /**
     * Reminder rest client
     * @var DocsReminder
     */
    protected $reminderClient;

    /**
     * @param DocsReminder $reminderClient
     */
    public function __construct(DocsReminder $reminderClient)
    {
        $this->reminderClient = $reminderClient;
    }

    /**
     * Send request to the docs api
     * @param FormInterface $form
     * @param string $user
     * @throws ReminderException
     * @return \Docs\RestClientBundle\Client\ResultInterface
     */
    public function process(FormInterface $form, $user)
    {
        $data = $form->getData();

        try {
            $data['user'] = $user;
            $reminderData = $this->prepareReminderData($data);

            return $this->reminderClient->create($reminderData);
        } catch (\Exception $e) {
            throw new ReminderException(
                'Could not created reminder: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * prepare reminder array
     * @param array $data
     */
    public function prepareReminderData($data)
    {
        $rmData = [
            'user' => $data['md'],
            'note' => $data['note'],
            'scheduled' => $data['datetime']->format('Y-m-d'),
            'status' => Reminder::STATUS_OPEN,
            'createdBy' => $data['user'],
        ];

        return ['Reminder' => $rmData];
    }
}
