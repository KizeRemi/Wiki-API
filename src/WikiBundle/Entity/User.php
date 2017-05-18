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
 * @ORM\Entity()
 * @ORM\Table(name="user")
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

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}