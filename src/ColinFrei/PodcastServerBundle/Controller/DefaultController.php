<?php

namespace ColinFrei\PodcastServerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ColinFreiPodcastServerBundle:Default:index.html.twig', array('name' => $name));
    }
}
