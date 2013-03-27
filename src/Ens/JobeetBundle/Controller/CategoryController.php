<?php

namespace Ens\JobeetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ens\JobeetBundle\Entity\Category;
use Ens\JobeetBundle\Form\CategoryType;

/**
 * Category controller.
 *
 * @Route("/category")
 */
class CategoryController extends Controller
{
    /**
     * Lists all Category entities.
     *
     * @Route("/", name="category")
     * @Route("/{slug}/{page}", name="EnsJobeetBundle_category")
     * @Method("GET")
     * @Template()
     */
    public function showAction($slug, $page = 1)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $category = $em->getRepository('EnsJobeetBundle:Category')->findOneBySlug($slug);

        if (!$category) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $category->setActiveJobs($em->getRepository('EnsJobeetBundle:Job')->getActiveJobs($category->getId()));

        return $this->render(
            'EnsJobeetBundle:Category:show.html.twig',
            array(
                'category' => $category,
            )
        );
    }
}
