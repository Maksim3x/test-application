<?php

namespace Subbotin\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        return $this->render('SubbotinMainBundle:Main:index.html.twig');
    }
}
