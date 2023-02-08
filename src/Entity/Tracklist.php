<?php

namespace App\Entity;

use App\Repository\TracklistRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TracklistRepository::class)]
class Tracklist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $track_name = null;

    #[ORM\ManyToOne(inversedBy: 'tracklist')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Album $album = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrackName(): ?string
    {
        return $this->track_name;
    }

    public function setTrackName(string $track_name): self
    {
        $this->track_name = $track_name;

        return $this;
    }

    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    public function setAlbum(?Album $album): self
    {
        $this->album = $album;

        return $this;
    }
}
