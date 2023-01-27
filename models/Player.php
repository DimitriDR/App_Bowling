<?php
require_once "Round.php";

class Player
{
    /**
     * @var string Nom du joueur
     **/
    public string $name;

    /**
     * @var int Score du joueur
     **/
    private int $score;

    /**
     * @var array Tableau de 10 objets Round : points remportés par le joueur
     **/
    private array $marked_points;

    /**
     * Constructeur de la classe Player
     * @param string $name Nom du joueur
     **/
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->score = 0;
        $this->marked_points[1] = new Round([0, 0]);
    }

    /**
     * @param int $score Points à rajouter au joueur
     * @return void
     */
    public function add_score(int $score): void
    {
        if ($score < 0)
        {
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
        $this->marked_points[] = $round_data;
    }

    /**
     * Fonction permettant
     * @param int $round
     * @return int

    public function get_next_throw_number(int $round): int {
        // Vérification cohérence du numéro du round
        if ($round < 1 || $round > 10)
        {
            throw new OutOfBoundsException("Le numéro du round doit être compris entre 1 et 10");
        }

        return $this->marked_points[$round]->next_throw();
    }*/

    public function did_spare_in_round(int $round): bool
    {
        return $this->marked_points[$round]->get_first_throw() + $this->marked_points[$round]->get_second_throw() == 10;
    }

    /**
     * Fonction permettant d'écrire la valeur d'un lancer pour la mettre dans le tableau
     * @param int $value Valeur du lancer à écrire
     * @param int $round_number Numéro du round (premier, deuxième, troisième, etc.) dans lequel on écrit la valeur
     * @param int $throw_number Numéro du lancer (premier, deuxième, troisième) dans lequel on écrit la valeur
     * @throws OutOfBoundsException Exception levée si le numéro du round n'est pas compris entre 1 et 10
     * @return void
    **/
    public function save_throw_value(int $value, int $round_number, int $throw_number): void
    {
        // Vérification que le numéro du tour soit cohérent
        if ($round_number < 1 || $round_number > 10)
        {
            throw new OutOfBoundsException("Le numéro du tour doit être compris entre 1 et 10");
        }

        // Récupération de l'objet Round sur lequel on travaille pour plus de commodité
        $working_round = $this->marked_points[$round_number];

        if          ($throw_number == 1)   { $working_round->set_first_throw   ($value);
        } elseif    ($throw_number == 2)   { $working_round->set_second_throw  ($value);
        } elseif    ($throw_number == 3)   { $working_round->set_third_throw   ($value);
        }
    }

    /**
     * @return int Nombre de points marqués par le joueur
     */
    public function count_marked_points(): int
    {
        $count = 0;

        for ($i = 0; $i < count($this->marked_points); $i++)
        {

            if ($this->marked_points[$i]->is_Strike() && $this->marked_points[$i]->get_turn() != 10)
            {
                $count += $this->marked_points[$i]->get_first_throw();
                $count += $this->marked_points[$i]->get_second_throw();
                $count += $this->marked_points[$i + 1]->get_first_throw();
                $count += $this->marked_points[$i + 1]->get_second_throw();
            } elseif ($this->marked_points[$i]->is_Spare() && $this->marked_points[$i]->get_turn() != 10)
            {
                $count += $this->marked_points[$i]->get_first_throw();
                $count += $this->marked_points[$i]->get_second_throw();
                $count += $this->marked_points[$i + 1]->get_first_throw();

            } elseif ($this->marked_points[$i]->is_Strike() && $this->marked_points[$i]->get_turn() == 10)
            {
                $count += $this->marked_points[$i]->get_first_throw();
                $count += $this->marked_points[$i]->get_second_throw();
                $count += $this->marked_points[$i]->get_third_throw();
            } elseif ($this->marked_points[$i]->is_Spare() && $this->marked_points[$i]->get_turn() == 10)
            {
                $count += $this->marked_points[$i]->get_first_throw();
                $count += $this->marked_points[$i]->get_second_throw();
                $count += $this->marked_points[$i]->get_third_throw();
            } else
            {
                $count += $this->marked_points[$i]->get_first_throw();
                $count += $this->marked_points[$i]->get_second_throw();
            }
        }

        return $count;
    }

    public function new_round()
    {
        $this->marked_points[] = new Round([0, 0]);
    }
}
