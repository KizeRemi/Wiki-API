<?php

namespace WikiBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="WikiBundle\Repository\UserRepository")
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Cet email est déjà utilisé.",
 * )
 * @UniqueEntity(
 *     fields={"username"},
 *     message="Un utilisateur possède déjà ce pseudo.",
 * )
 */
class User extends BaseUser
{
    const ROLE_USER = "ROLE_USER";
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="name",type="string", length=25, nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(name="lastname",type="string", length=25, nullable=true)
     */
    protected $lastname;

    /**
     * @ORM\Column(name="birth_date",type="date", length=25, nullable=true)
     */
    protected $birthDate;

    public function __construct()
    {
        parent::__construct();
        $this->roles = array(static::ROLE_USER);
        $this->enabled = 1;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
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

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     *
     * @return User
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }
}
