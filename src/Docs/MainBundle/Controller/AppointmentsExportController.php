<?php
namespace Docs\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Controller that takes care of appointment
 * export
 *
 * @author hbotev
 */
class AppointmentsExportController extends Controller
{
    /**
     * Appointments Export
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportAction(Request $request)
    {
        $exportMngr = $this->get("export.appointments");
        /* @var $exportMngr \Docs\MainBundle\Export\AppointmentsExport */

        $user = $this->getUser();
        $exportFile = $exportMngr->generate($request, $user);

        // prepare BinaryFileResponse
        $response = new BinaryFileResponse($exportFile);
        $response->headers->set('Content-Type', 'text/csv');
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'AppointmentsExport ' . date('Ymd-hiA') . '.csv',
            'Appointments-' . time() . '.csv'
        );

        $response->deleteFileAfterSend(true);

        return $response;
    }
}
