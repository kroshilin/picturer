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
class Vote
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
     * @var int
     */
    public $vote;

    /**
     * @var Poll
     *
     * @ORM\ManyToOne(targetEntity="Poll", inversedBy="votes")
     */
    public $poll;

    /**
     * @var Teleuser
     *
     * @ORM\ManyToOne(targetEntity="teleuser", inversedBy="votes")
     */
    public $teleUser;

    /**
     * @var \DateTimeInterface The date of vote.
     *
     * @ORM\Column(type="datetime")
     */
    public $dateTime;

    public function getId(): ?int
    {
        return $this->id;
    }
}
