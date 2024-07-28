<?php

namespace App\Entity;

use App\Repository\TournamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TournamentRepository::class)
 */
class Tournament
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $winner;

    /**
     * @ORM\OneToMany(targetEntity=GameInfo::class, mappedBy="tournament", orphanRemoval=true)
     */
    private $infoGames;

    public function __construct()
    {
        $this->infoGames = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getWinner(): ?string
    {
        return $this->winner;
    }

    public function setWinner(string $winner): self
    {
        $this->winner = $winner;

        return $this;
    }

    /**
     * @return Collection<int, GameInfo>
     */
    public function getInfoGames(): Collection
    {
        return $this->infoGames;
    }

    public function addInfoGame(GameInfo $infoGame): self
    {
        if (!$this->infoGames->contains($infoGame)) {
            $this->infoGames[] = $infoGame;
            $infoGame->setTournament($this);
        }

        return $this;
    }

    public function removeInfoGame(GameInfo $infoGame): self
    {
        if ($this->infoGames->removeElement($infoGame)) {
            if ($infoGame->getTournament() === $this) {
                $infoGame->setTournament(null);
            }
        }

        return $this;
    }
}
