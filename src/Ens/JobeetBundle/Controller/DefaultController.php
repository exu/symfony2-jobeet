<?php

namespace Ens\JobeetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/default")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/{name}")
     * @Template()
     */
    public function indexAction($name = '')
    {
        return array('name' => $name);
    }
}
