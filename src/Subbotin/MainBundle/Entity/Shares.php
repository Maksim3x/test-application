<?php

namespace Subbotin\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Subbotin\MainBundle\Entity\User;

/**
 * Акции
 *
 * @ORM\Table(name="shares")
 * @ORM\Entity(repositoryClass="Subbotin\MainBundle\Repository\SharesRepository")
 */
class Shares
{
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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="1", max="255")
     */
    public $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30)
     * @Assert\NotBlank()
     * @Assert\Length(min="1", max="30")
     */
	public $code;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Subbotin\MainBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Assert\NotNull()
     */
    protected $user;

    /**
     * Вывод строки
     */
    public function __toString()
    {
        return $this->title;
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
     * Set title
     *
     * @param string $title
     * @return Shares
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
     * Set code
     *
     * @param string $code
     * @return Shares
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return Shares
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
