<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\DocumentRepository;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 */
class Document
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $docuImage;

    /**
     * @ORM\ManyToOne(targetEntity=Figure::class, inversedBy="picture")
     */
    private $figurePicture;

    /**
     * @ORM\ManyToOne(targetEntity=Figure::class, inversedBy="video")
     */
    private $figureVideo;

    /**
     * @ORM\Column(type="smallint")
     */
    private $booleanImageVideo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getDocuImage(): ?string
    {
        return $this->docuImage;
    }

    public function setDocuImage(string $docuImage): self
    {
        $this->docuImage = $docuImage;

        return $this;
    }
    public function getFigurePicture(): ?Figure
    {
        return $this->figurePicture;
    }

    public function setFigurePicture(?Figure $figurePicture): self
    {
        $this->figurePicture = $figurePicture;

        return $this;
    }

    public function getFigureVideo(): ?Figure
    {
        return $this->figureVideo;
    }

    public function setFigureVideo(?Figure $figureVideo): self
    {
        $this->figureVideo = $figureVideo;

        return $this;
    }

    public function getBooleanImageVideo(): ?int
    {
        return $this->booleanImageVideo;
    }

    public function setBooleanImageVideo(int $booleanImageVideo): self
    {
        $this->booleanImageVideo = $booleanImageVideo;

        return $this;
    }
}
