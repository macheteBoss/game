<?php

namespace App\Entity;

use App\Repository\GameInfoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameInfoRepository::class)
 */
class GameInfo
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
    private $couple;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $score;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $winner;

    /**
     * @ORM\ManyToOne(targetEntity=Tournament::class, inversedBy="infoGames")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tournament;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCouple(): ?string
    {
        return $this->couple;
    }

    public function setCouple(string $couple): self
    {
        $this->couple = $couple;

        return $this;
    }

    public function getScore(): ?string
    {
        return $this->score;
    }

    public function setScore(string $score): self
    {
        $this->score = $score;

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

    public function getTournament(): ?Tournament
    {
        return $this->tournament;
    }

    public function setTournament(?Tournament $tournament): self
    {
        $this->tournament = $tournament;

        return $this;
    }
}
