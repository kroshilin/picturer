<?php
// api/src/Entity/Book.php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * A book.
 *
 * @ORM\Entity
 */
class Teleuser
{
    /**
     * @var integer The title of this tag.
     * @ORM\Id
     * @ORM\Column(unique=true)
     */
    public $id;

    /**
     * @var string The title of this tag.
     *
     * @ORM\Column
     */
    public $name;

    /**
     * @var string The title of this tag.
     *
     * @ORM\Column
     */
    public $nickname;

    /**
     * @var Poll[] Available reviews for this book.
     *
     * @ORM\ManyToMany(targetEntity="Poll", mappedBy="teleusers", cascade={"persist", "remove"})
     */
    public $polls;

    /**
     * @var Vote[] Available reviews for this book.
     *
     * @ORM\OneToMany(targetEntity="Vote", mappedBy="teleUser")
     */
    public $votes;

    public function __construct()
    {
        $this->polls = new ArrayCollection();
        $this->votes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}