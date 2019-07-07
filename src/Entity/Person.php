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

    public function __construct(Name $name)
    {
        $this->name = (string)$name;
    }

    public function getId() : ?Id
    {
        return ($this->id !== null) ? Id::createFromInt($this->id) : null;
    }

    public function getName(): Name
    {
        return Name::createFromString($this->name);
    }
}
