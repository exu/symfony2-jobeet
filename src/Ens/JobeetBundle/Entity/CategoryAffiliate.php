<?php

namespace Ens\JobeetBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoryAffiliate
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ens\JobeetBundle\Entity\CategoryAffiliateRepository")
 */
class CategoryAffiliate
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
     * @ORM\ManyToOne(targetEntity="Affiliate", inversedBy="category_affiliates")
     */
    private $affiliate;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="category_affiliates")
     */
    private $category;



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
     * Set affiliate
     *
     * @param \Ens\JobeetBundle\Entity\Affiliate $affiliate
     * @return CategoryAffiliate
     */
    public function setAffiliate(\Ens\JobeetBundle\Entity\Affiliate $affiliate = null)
    {
        $this->affiliate = $affiliate;
    
        return $this;
    }

    /**
     * Get affiliate
     *
     * @return \Ens\JobeetBundle\Entity\Affiliate 
     */
    public function getAffiliate()
    {
        return $this->affiliate;
    }

    /**
     * Set category
     *
     * @param \Ens\JobeetBundle\Entity\Category $category
     * @return CategoryAffiliate
     */
    public function setCategory(\Ens\JobeetBundle\Entity\Category $category = null)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return \Ens\JobeetBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }
}