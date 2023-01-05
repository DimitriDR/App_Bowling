<?php
require_once "classes/Round.php";

class Player
{
    /**
     * @var string Nom du joueur
     */
    public string $name;
    /**
     * @var int Score du joueur
     */
    private int $score;
    /**
     * @var array Tableau de 10 objets Round : points remportés par le joueur
     */
    private array $marked_points;

    /**
     * Constructeur de la classe Player
     * @param string $name Nom du joueur
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->score = 0;
        $this->marked_points = [new Round(), new Round(), new Round(), new Round(), new Round(),
                                new Round(), new Round(), new Round(), new Round(), new Round()
                               ];
    }

    /**
     * @param int $score Points à rajouter au joueur
     * @return void
     */
    public function add_score(int $score): void
    {
        if ($score < 0) {
            throw new InvalidArgumentException("Le score ne peut pas être négatif");
        }

        $this->score += $score;
    }

    /**
     * @return int Score du joueur
     */
    public function get_score(): int
    {
        return $this->score;
    }
}