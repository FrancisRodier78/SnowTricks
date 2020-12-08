<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\FigureRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=FigureRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 *  fields={"figureName"},
 *  message="Une figure porte déjà ce nom, merci d'en utiliser un autre."
 * )
 */
class Figure
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
    private $figureName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageDefaut;

    /**
     * @ORM\OneToMany(targetEntity=Document::class, mappedBy="figurePicture", orphanRemoval=true)
     */
    private $picture;

    /**
     * @ORM\OneToMany(targetEntity=Document::class, mappedBy="figureVideo", orphanRemoval=true)
     */
    private $video;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="figure", orphanRemoval=true)
     */
    private $commentaires;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="figures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $authorId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modifDate;

    /**
     * @ORM\ManyToOne(targetEntity=Groupe::class, inversedBy="figures")
     */
    private $groupe;

    public function __construct()
    {
        $this->picture = new ArrayCollection();
        $this->video = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

    /**
     * Permet d'initiaiiser le slug !
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return void
     */
    public function initializeSlug() {
        if(empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->figureName);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFigureName(): ?string
    {
        return $this->figureName;
    }

    public function setFigureName(string $figureName): self
    {
        $this->figureName = $figureName;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImageDefaut(): ?string
    {
        return $this->imageDefaut;
    }

    public function setImageDefaut(string $imageDefaut): self
    {
        //new File($this->getParameter('imageDefauts_directory').'/'.$product->getimageDefaut());

        $this->imageDefaut = $imageDefaut;

        return $this;
    }

    /**
     * @return Collection|Document[]
     */
    public function getPicture(): Collection
    {
        return $this->picture;
    }

    public function addPicture(Document $picture): self
    {
        if (!$this->picture->contains($picture)) {
            $this->picture[] = $picture;
            $picture->setFigurePicture($this);
        }

        return $this;
    }

    public function removePicture(Document $picture): self
    {
        if ($this->picture->contains($picture)) {
            $this->picture->removeElement($picture);
            // set the owning side to null (unless already changed)
            if ($picture->getFigurePicture() === $this) {
                $picture->setFigurePicture(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Document[]
     */
    public function getVideo(): Collection
    {
        return $this->video;
    }

    public function addVideo(Document $video): self
    {
        if (!$this->video->contains($video)) {
            $this->video[] = $video;
            $video->setFigureVideo($this);
        }

        return $this;
    }

    public function removeVideo(Document $video): self
    {
        if ($this->video->contains($video)) {
            $this->video->removeElement($video);
            // set the owning side to null (unless already changed)
            if ($video->getFigureVideo() === $this) {
                $video->setFigureVideo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setFigure($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getFigure() === $this) {
                $commentaire->setFigure(null);
            }
        }

        return $this;
    }

    public function getAuthorId(): ?User
    {
        return $this->authorId;
    }

    public function setAuthorId(?User $authorId): self
    {
        $this->authorId = $authorId;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getModifDate(): ?\DateTimeInterface
    {
        return $this->modifDate;
    }

    public function setModifDate(?\DateTimeInterface $modifDate): self
    {
        $this->modifDate = $modifDate;

        return $this;
    }

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupe $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }
}
