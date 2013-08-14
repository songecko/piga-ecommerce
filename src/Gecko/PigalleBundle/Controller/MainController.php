<?php

namespace Gecko\PigalleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        return $this->render('PigalleBundle:Main:index.html.twig');
    }
    
    public function laMarcaAction()
    {
    	return $this->render('PigalleBundle:Main:laMarca.html.twig');
    }
    
    public function localesAction()
    {
    	return $this->render('PigalleBundle:Main:locales.html.twig');
    }
}
