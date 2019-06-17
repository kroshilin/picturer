<?php
// api/src/Entity/Book.php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * A poll.
 *
 * @ORM\Entity
 */
class Chat
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", unique=true)
     */
    private $id;

    /**
     * @var string The title of this book.
     *
     * @ORM\Column
     */
    public $name;

    /**
     * @var string The description of this book.
     *
     * @ORM\Column(type="text")
     */
    public $description;

    /**
     * @var Poll[] Available reviews for this book.
     *
     * @ORM\OneToMany(targetEntity="Poll", mappedBy="bot")
     */
    public $polls;

    public function __construct()
    {
        $this->polls = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}