<?php

namespace Subbotin\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Subbotin\MainBundle\Entity\Shares;
use Subbotin\MainBundle\Entity\User;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Группы Акций
 *
 * @ORM\Table(name="group_shares")
 * @ORM\Entity(repositoryClass="Subbotin\MainBundle\Repository\GroupSharesRepository")
 */
class GroupShares
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
     */
	public $title;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Subbotin\MainBundle\Entity\Shares")
     * @ORM\JoinTable(name="group_shares_relationship",
     *      joinColumns={@ORM\JoinColumn(name="shares_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
	public $shares;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Subbotin\MainBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Assert\NotNull()
     */
    protected $user;

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
     * Constructor
     */
    public function __construct()
    {
        $this->shares = new ArrayCollection();
    }

    /**
     * Вывод строки
     */
    public function __toString()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return GroupShares
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
     * Add shares
     *
     * @param Shares $shares
     * @return GroupShares
     */
    public function addShare(Shares $shares)
    {
        $this->shares[] = $shares;

        return $this;
    }

    /**
     * Remove shares
     *
     * @param Shares $shares
     */
    public function removeShare(Shares $shares)
    {
        $this->shares->removeElement($shares);
    }

    /**
     * Get shares
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getShares()
    {
        return $this->shares;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return GroupShares
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
