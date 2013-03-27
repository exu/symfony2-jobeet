<?php

namespace Ens\JobeetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ens\JobeetBundle\Utils\Jobeet;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ens\JobeetBundle\Entity\CategoryRepository")
 */
class Category
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

     /**
     * @ORM\OneToMany(targetEntity="Job", mappedBy="category")
     */
    private $jobs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->jobs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Add jobs
     *
     * @param \Ens\JobeetBundle\Entity\Job $jobs
     * @return Category
     */
    public function addJob(\Ens\JobeetBundle\Entity\Job $jobs)
    {
        $this->jobs[] = $jobs;

        return $this;
    }

    /**
     * Remove jobs
     *
     * @param \Ens\JobeetBundle\Entity\Job $jobs
     */
    public function removeJob(\Ens\JobeetBundle\Entity\Job $jobs)
    {
        $this->jobs->removeElement($jobs);
    }

    /**
     * Get jobs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getJobs()
    {
        return $this->jobs;
    }

    public function __toString()
    {
        return '(' . $this->getId() . ') ' . $this->getName();
    }


    private $activeJobs;

    public function setActiveJobs($jobs)
    {
        $this->activeJobs = $jobs;
    }

    public function getActiveJobs()
    {
        return $this->activeJobs;
    }

    private $moreJobs;

    public function setMoreJobs($jobs)
    {
        $this->moreJobs = $jobs >=  0 ? $jobs : 0;
    }

    public function getMoreJobs()
    {
        return $this->moreJobs;
    }

    /**
     * @ORM\prePersist
     */
    public function setSlugValue()
    {
        $this->slug = Jobeet::slugify($this->getName());
    }
}
