<?php

namespace Docs\AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AuthBundle:Default:index.html.twig');
    }
}
