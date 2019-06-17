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
class PollTemplate
{
    /**
     * @var int The id of this book.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string The title of this book.
     *
     * @ORM\Column
     */
    public $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    public $template;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    public $usePicture;

    /**
     * @var Poll[]
     *
     * @ORM\OneToMany(targetEntity="Poll", mappedBy="pollTemplate")
     */
    public $polls;

    /**
     * @var Tag[]
     *
     * @ORM\ManyToMany(targetEntity="Tag", mappedBy="tags", cascade={"persist", "remove"})
     */
    public $tags;

    public function __construct()
    {
        $this->polls = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}