<?php

namespace Docs\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RemindersController extends Controller
{
    public function listAction(Request $request)
    {
        $remindersProvider = $this->get('data_provider.reminders');
        /* @var $remindersProvider \Docs\MainBundle\DataProvider\RemindersProvider */

        $userID = $this->getUser()
                            ->getUserID();

        $remindersData = $remindersProvider->getOpenReminder($request, $userID);

        return $this->render('MainBundle:DocsGrids:listReminders.html.twig', [
            "reminders"         => $remindersData['reminders'],
            "pagination"        => $remindersData['pagination']
        ]);
    }
}
