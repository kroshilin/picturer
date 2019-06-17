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
class Poll
{
    /**
     * @var int The id of this book.
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string The title of this book.
     *
     * @ORM\Column
     */
    public $title;

    /**
     * @var string The description of this book.
     *
     * @ORM\Column(type="text")
     */
    public $description;

    /**
     * @var string The author of this book.
     *
     * @ORM\Column
     */
    public $author;

    /**
     * @var \DateTimeInterface The publication date of this book.
     *
     * @ORM\Column(type="datetime")
     */
    public $publicationDate;

    /**
     * @var Chat
     *
     * @ORM\ManyToOne(targetEntity="Chat", inversedBy="polls")
     */
    public $chat;

    /**
     * @var PollTemplate
     *
     * @ORM\ManyToOne(targetEntity="PollTemplate", inversedBy="poll")
     */
    public $pollTemplate;

    /**
     * @var Picture|null
     *
     * @ORM\ManyToOne(targetEntity=Picture::class)
     * @ORM\JoinColumn(nullable=true)
     */
    public $image;

    /**
     * @var Vote[] Available reviews for this book.
     *
     * @ORM\OneToMany(targetEntity="Vote", mappedBy="poll")
     */
    public $votes;

    public function __construct()
    {
        $this->votes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}