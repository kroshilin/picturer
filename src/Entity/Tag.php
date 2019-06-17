<?php
// api/src/Entity/Book.php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * A book.
 * @ORM\Entity
 */
class Tag
{
    /**
     * @var string The title of this tag.
     * @ORM\Id
     * @ORM\Column(unique=true)
     */
    public $name;

    /**
     * @var Picture[] Available reviews for this book.
     *
     * @ORM\ManyToMany(targetEntity="Picture", mappedBy="tags", cascade={"persist"})
     */
    public $pictures;

    /**
     * @var PollTemplate[]
     *
     * @ORM\ManyToMany(targetEntity="PollTemplate", mappedBy="tags", cascade={"persist", "remove"})
     */
    public $pollTemplates;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
        $this->pollTemplates = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->name;
    }
}