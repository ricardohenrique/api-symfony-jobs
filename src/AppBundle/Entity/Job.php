<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Datetime;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\JobRepository")
 */
class Job implements EntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Service")
     * @ORM\JoinColumn(nullable=false, name="service_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $service_id;

    /**
     * @ORM\Column(type="string", length=5, options={"fixed" = true})
     * @ORM\ManyToOne(targetEntity="App\Entity\Zipdcode")
     * @ORM\JoinColumn(nullable=false, name="category_id", referencedColumnName="id")
     * @Assert\Length(
     *      min = 5,
     *      max = 5,
     *      minMessage = "The zipcode_id must have exactly 5 characters",
     *      maxMessage = "The zipcode_id must have exactly 5 characters"
     * )
     * @Assert\NotBlank()
     */
    private $zipcode_id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "The title must more than 4 characters",
     *      maxMessage = "The title must have less than 51 characters"
     * )
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     * @Assert\Date()
     */
    private $date_to_be_done;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    public function __construct(
        int $serviceId = null,
        String $zipcodeId = null,
        String $title = null,
        String $description = null,
        \DateTimeInterface $dateToBeDone = null,
        String $id = null
    ) {
        $this->service_id = $serviceId;
        $this->zipcode_id = $zipcodeId;
        $this->title = $title;
        $this->description = $description;
        $this->date_to_be_done = $dateToBeDone;
        $this->created_at = new DateTime();
        $this->id = $id ?? $this->id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getServiceId(): ?int
    {
        return $this->service_id;
    }

    /**
     * @return null|String
     */
    public function getZipcodeId(): ?String
    {
        return $this->zipcode_id;
    }

    /**
     * @return null|String
     */
    public function getTitle(): ?String
    {
        return $this->title;
    }

    /**
     * @return null|String
     */
    public function getDescription(): ?String
    {
        return $this->description;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateToBeDone(): ?\DateTimeInterface
    {
        return $this->date_to_be_done;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }
}
