<?php
require_once "Round.php";

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
        $this->marked_points = [ new Round([0, 0], 1),
                                 new Round([0, 0], 2),
                                 new Round([0, 0], 3),
                                 new Round([0, 0], 4),
                                 new Round([0, 0], 5),
                                 new Round([0, 0], 6),
                                 new Round([0, 0], 7),
                                 new Round([0, 0], 8),
                                 new Round([0, 0], 9),
                                 new Round([0, 0, 0], 10)
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

    /**
     * @return array Tableau contenant les points marqués par le joueur
     */
    public function get_marked_points(): array
    {
        return $this->marked_points;
    }

    /**
     * @param array $round_data Tableau contenant les valeurs des lancers
     * @return void
     */
    public function set_marked_points(array $round_data): void
    {
        array_push($this->marked_points, $round_data);
    }

    /**
     * @return int Nombre de points marqués par le  joueur
     */
    public function count_marked_points(): int
    {
        $count = 0;

        for ($i = 0; $i < count($this->marked_points); $i++) {
            
            if($this->marked_points[$i]->is_Strike() && $this->marked_points[$i]->get_turn() != 10){
                $count += $this->marked_points[$i]->get_first_throw();
                $count += $this->marked_points[$i]->get_second_throw();
                $count += $this->marked_points[$i+1]->get_first_throw();
                $count += $this->marked_points[$i+1]->get_second_throw();
            }
            elseif($this->marked_points[$i]->is_Spare() && $this->marked_points[$i]->get_turn() != 10){
                $count += $this->marked_points[$i]->get_first_throw();
                $count += $this->marked_points[$i]->get_second_throw();
                $count += $this->marked_points[$i+1]->get_first_throw();

            }
            elseif($this->marked_points[$i]->is_Strike() && $this->marked_points[$i]->get_turn() == 10){
                $count += $this->marked_points[$i]->get_first_throw();
                $count += $this->marked_points[$i]->get_second_throw();
                $count += $this->marked_points[$i]->get_third_throw();
            }
            elseif($this->marked_points[$i]->is_Spare() && $this->marked_points[$i]->get_turn() == 10){
                $count += $this->marked_points[$i]->get_first_throw();
                $count += $this->marked_points[$i]->get_second_throw();
                $count += $this->marked_points[$i]->get_third_throw();
            }else{
                $count += $this->marked_points[$i]->get_first_throw();
                $count += $this->marked_points[$i]->get_second_throw();
            }
        }
    }
}