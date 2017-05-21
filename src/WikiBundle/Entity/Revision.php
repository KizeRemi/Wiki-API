<?php

namespace WikiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * PageRevision
 *
 * @ORM\Table(name="revision")
 * @ORM\Entity(repositoryClass="WikiBundle\Repository\RevisionRepository")
 */
class Revision
{
    use TimestampableEntity;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=60)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
    * @Gedmo\Slug(fields={"title"})
    * @ORM\Column(length=255, unique=true)
    */
    private $slug;

    /**
    * @ORM\ManyToOne(targetEntity="WikiBundle\Entity\User")
    * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable = true)
    */
    protected $user;

    /**
    * @ORM\ManyToOne(targetEntity="WikiBundle\Entity\Page")
    * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
    */
    protected $page;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return PageRevision
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return PageRevision
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set user
     *
     * @param \WikiBundle\Entity\User $user
     *
     * @return Revision
     */
    public function setUser(\WikiBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \WikiBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set page
     *
     * @param \WikiBundle\Entity\Page $page
     *
     * @return Revision
     */
    public function setPage(\WikiBundle\Entity\Page $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \WikiBundle\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Revision
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Revision
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}