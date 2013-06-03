<?php

namespace Gecko\PigalleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PigalleBundle:Default:index.html.twig');
    }
}
