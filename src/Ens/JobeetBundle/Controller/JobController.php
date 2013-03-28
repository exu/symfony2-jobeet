<?php

namespace Ens\JobeetBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ens\JobeetBundle\Entity\Job;
use Ens\JobeetBundle\Form\JobType;

use Doctrine\ORM\Query;

/**
 * Job controller.
 *
 * @Route("/")
 * @Route("/job")
 */
class JobController extends Controller
{
    /**
     * Lists all Job entities.
     *
     * @Route("/", name="job")
     * @Route("/", name="EnsJobeetBundle_homepage")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        /* @example get as Array */
        /* $query = $em->createQuery('SELECT j FROM EnsJobeetBundle:Job j'); */
        /* $entities = $query->execute(array(), Query::HYDRATE_ARRAY); */

        /* @example default data retrieval */
        /* $entities = $em->getRepository('EnsJobeetBundle:Job') */
        /*    ->findAll(); */

        /* @example from day5 */
        /* $query = $em->createQuery( */
        /*     'SELECT j FROM EnsJobeetBundle:Job j WHERE j.createdAt > :date' */
        /* )->setParameter('date', date('Y-m-d H:i:s', time() - 86400 * 30)); */
        /* $entities = $query->getResult(); */

        $em = $this->getDoctrine()->getEntityManager();

        $categories = $em->getRepository('EnsJobeetBundle:Category')->getWithJobs();

        foreach ($categories as $category) {
            $category->setActiveJobs(
                $em->getRepository('EnsJobeetBundle:Job')
                   ->getActiveJobs(
                       $category->getId(),
                       $this->container->getParameter('max_jobs_on_homepage')
                   )
            );

            $category->setMoreJobs(
                $em->getRepository('EnsJobeetBundle:Job')
                   ->countActiveJobs($category->getId()) -  $this->container->getParameter('max_jobs_on_homepage')
            );
        }

        return $this->render(
            'EnsJobeetBundle:Job:index.html.twig',
            array(
                'categories' => $categories
            )
        );
    }

    /**
     * Creates a new Job entity.
     *
     * @Route("/", name="job_create")
     * @Method("POST")
     * @Template("EnsJobeetBundle:Job:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Job();
        $form = $this->createForm(new JobType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect(
                $this->generateUrl(
                    'job_show',
                    array(
                        'company' => $entity->getCompanySlug(),
                        'location' => $entity->getLocationSlug(),
                        'id' => $entity->getId(),
                        'position' => $entity->getPositionSlug()
                    )
                )
            );
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Job entity.
     *
     * @Route("/new", name="job_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Job();
        $entity->setType('full-time');
        $form   = $this->createForm(new JobType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }





    /**
     * Finds and displays a Job entity by token.
     *
     * @Route("/{company}/{location}/{token}/{position}", name="job_preview")
     * @Method("GET")
     * @Template()
     */
    public function previewAction($id)
    {
        $entity = $em->getRepository('EnsJobeetBundle:Job')->findOneByToken($token);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Job entity.');
        }

        return $this->showAction($entity->getId());
    }

    /**
     * Finds and displays a Job entity.
     *
     * @Route("/{id}", name="job_show_short")
     * @Route("/{company}/{location}/{id}/{position}", requirements={"id" = "\d+"}, name="job_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EnsJobeetBundle:Job')
                     ->getActiveJob($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Job entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render(
            'EnsJobeetBundle:Job:show.html.twig',
            array(
                'entity'      => $entity,
                'delete_form' => $deleteForm->createView(),
            )
        );
    }



    /**
     * Displays a form to edit an existing Job entity.
     *
     * @Route("/{token}/edit", name="job_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($token)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EnsJobeetBundle:Job')->findOneByToken($token);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Job entity.');
        }

        $editForm = $this->createForm(new JobType(), $entity);
        $deleteForm = $this->createDeleteForm($token);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Job entity.
     *
     * @Route("/{token}", name="job_update")
     * @Method("PUT")
     * @Template("EnsJobeetBundle:Job:edit.html.twig")
     */
    public function updateAction(Request $request, $token)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EnsJobeetBundle:Job')->findOneByToken($token);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Job entity.');
        }

        $deleteForm = $this->createDeleteForm($token);
        $editForm = $this->createForm(new JobType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('job_edit', array('token' => $token)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Job entity.
     *
     * @Route("/{token}", name="job_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $token)
    {
        $form = $this->createDeleteForm($token);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('EnsJobeetBundle:Job')->findOneByToken($token);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Job entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('job'));
    }

    /**
     * Creates a form to delete a Job entity by id.
     *
     * @param mixed $token The TOKEN :)
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($token)
    {
        return $this->createFormBuilder(array('token' => $token))
            ->add('token', 'hidden')
            ->getForm();
    }
}
