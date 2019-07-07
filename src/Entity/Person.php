<?php

namespace App\Entity;

use App\ValueObject\Id;
use App\ValueObject\Name;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 */
class Person
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", unique=true, length=255, nullable=true)
     */
    private $tmdbId;

    public function __construct(Name $name, ?Id $tmdbId)
    {
        $this->name   = (string)$name;
        $this->tmdbId = (string)$tmdbId;
    }

    public function getId() : ?Id
    {
        return ($this->id !== null) ? Id::createFromInt($this->id) : null;
    }

    public function getName() : Name
    {
        return Name::createFromString($this->name);
    }

    public function getTmdbId() : ?Id
    {
        return ($this->id !== null) ? Id::createFromInt($this->id) : null;
    }
}
