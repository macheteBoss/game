<?php

namespace App\Service;

use App\DependencyInjection\Traits\EntityManagerInterfaceInjectionTrait;
use App\Entity\GameInfo;
use App\Entity\Tournament;

class ProcessGameService
{
    use EntityManagerInterfaceInjectionTrait;

    const TEAMS = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

    /** @var array */
    private $resultGames = [];

    /** @var string */
    private $winner;

    public function execute(): array
    {
        $division1 = $this->getDivisionResult();
        $division2 = $this->getDivisionResult();

        $leaders1 = $this->getDivisionLeaders($division1['scores'], 1);
        $leaders2 = $this->getDivisionLeaders($division2['scores'], 2);

        $result = [];
        $i = 0;
        while ($i < 4) {
            $result[] = $leaders1[$i];
            $result[] = $leaders2[$i];

            $i++;
        }

        $this->recursiveProcess($result);

        try {
            $tournament = (new Tournament())
                ->setStartDate(new \DateTime()) // Поле для примера
                ->setEndDate(new \DateTime()) // Поле для примера
                ->setWinner($this->winner)
            ;

            $this->getEntityManager()->persist($tournament);

            foreach ($this->resultGames as $resultGame) {
                foreach ($resultGame as $game) {
                    $gameInfo = (new GameInfo())
                        ->setTournament($tournament)
                        ->setCouple($game['match'][0] . ' -> ' . $game['match'][1])
                        ->setScore($game['score'][0] . ' : ' . $game['score'][1])
                        ->setWinner($game['winner'])
                    ;

                    $this->getEntityManager()->persist($gameInfo);
                }
            }

            $this->getEntityManager()->flush();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return [
            'teams' => self::TEAMS,
            'divisions' => [
                $division1,
                $division2,
            ],
            'playOff' => $this->resultGames,
            'winner' => $this->winner,
        ];
    }

    private function getDivisionResult(): array
    {
        $data = [];
        $scores  = [];

        for ($i = 1; $i <= count(self::TEAMS); $i++) {
            $score = 0;
            for ($g = 1; $g <= count(self::TEAMS); $g++) {
                if ($i == $g) {
                    $data[$i][$g] = [];
                    continue;
                }

                if (isset($data[$g][$i])) {
                    $data[$i][$g] = [$data[$g][$i][1], $data[$g][$i][0]];
                } else {
                    $data[$i][$g] = $this->generateGameResult();
                }

                $score += $data[$i][$g][0];
            }

            $scores[$i] = $score;
        }

        return ['data' => $data, 'scores' => $scores];
    }

    private function generateGameResult(): array {
        return [rand(0, 4), rand(0, 4)];
    }

    private function getDivisionLeaders(array $scores, int $division): array
    {
        arsort($scores);

        $leaders = array_slice($scores, 0, 4, true);

        if ($division == 2) {
            // Если я правильно понял, то в паре должна быть сильнейшая команда из одного дивизиона и слабейшая из второго.
            // Следовательно раз у нас сортировка была по убыванию, то если мы второй дивизион перевернём, то в начале будут идти слабейшие команды.
            ksort($leaders);
        }

        $callback = fn(int $k): string => 'Team ' . self::TEAMS[$k - 1] . ' (Division ' . $division . ')';

        return array_map($callback, array_keys($leaders));
    }

    private function recursiveProcess(array $divisionData): void
    {
        $divisionData = array_chunk($divisionData, 2);

        $winners = [];
        $info = [];
        foreach ($divisionData as $data) {
            [$a, $b] = $this->recursiveGenerateGameResult();

            $winner = ($a > $b) ? $data[0] : $data[1];

            $winners[] = $winner;

            $info[] = [
                'match' => $data,
                'score' => [$a, $b],
                'winner' => $winner
            ];
        }

        $this->resultGames[] = $info;

        if (count($winners) == 1) {
            $this->winner = $winners[0];

            return;
        }

        $this->recursiveProcess($winners);
    }

    private function recursiveGenerateGameResult(): array {
        [$a, $b] = $this->generateGameResult();

        if ($a != $b) {
            return [$a, $b];
        }

        return $this->recursiveGenerateGameResult();
    }
}