<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ServiceRepository")
 */
class Service implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="integer", unique=true, nullable=false)
     * @Assert\NotBlank()
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\Length(
     *      min = 5,
     *      max = 255,
     *      minMessage = "The name must have at least 5 characters",
     *      maxMessage = "The name must have less than 256 characters"
     * )
     * @Assert\NotBlank()
     */
    private $name;

    public function __construct(int $id = null, String $name = null)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getName(): ?String
    {
        return $this->name;
    }
}
