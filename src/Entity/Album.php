<?php

namespace App\Entity;

use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

#[ORM\Entity(repositoryClass: AlbumRepository::class)]
class Album
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $artist = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $album_name = null;

    #[ORM\Column(length: 255)]
    private ?string $genre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $album_picture = null;

    #[ORM\ManyToOne(inversedBy: 'albums')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'album', targetEntity: Review::class, orphanRemoval: true)]
    private Collection $reviews;

    #[ORM\OneToMany(mappedBy: 'album', targetEntity: Tracklist::class, orphanRemoval: true)]
    private Collection $tracklist;



    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->tracklist = new ArrayCollection();
    }


//    private ?int $userId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArtist(): ?string
    {
        return $this->artist;
    }

    public function setArtist(string $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    public function getAlbumName(): ?string
    {
        return $this->album_name;
    }

    public function setAlbumName(string $album_name): self
    {
        $this->album_name = $album_name;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getAlbumPicture(): ?string
    {
        return $this->album_picture;
    }

    public function setAlbumPicture(?string $album_picture): self
    {
        $this->album_picture = $album_picture;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

//    public function getUserId(): ?int
//    {
//        return $this->userId;
//    }

//    public function setUserId(int $userId): self
//    {
//        $this->userId = $userId;
//
//        return $this;
//    }

/**
 * @return Collection<int, Review>
 */
public function getReviews(): Collection
{
    return $this->reviews;
}

public function addReview(Review $review): self
{
    if (!$this->reviews->contains($review)) {
        $this->reviews->add($review);
        $review->setAlbum($this);
    }

    return $this;
}

public function removeReview(Review $review): self
{
    if ($this->reviews->removeElement($review)) {
        // set the owning side to null (unless already changed)
        if ($review->getAlbum() === $this) {
            $review->setAlbum(null);
        }
    }

    return $this;
}

/**
 * @return Collection<int, Tracklist>
 */
public function getTracklist(): Collection
{
    return $this->tracklist;
}

public function addTracklist(Tracklist $tracklist): self
{
    if (!$this->tracklist->contains($tracklist)) {
        $this->tracklist->add($tracklist);
        $tracklist->setAlbum($this);
    }

    return $this;
}

public function removeTracklist(Tracklist $tracklist): self
{
    if ($this->tracklist->removeElement($tracklist)) {
        // set the owning side to null (unless already changed)
        if ($tracklist->getAlbum() === $this) {
            $tracklist->setAlbum(null);
        }
    }

    return $this;
}


}
