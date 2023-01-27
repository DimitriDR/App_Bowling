<?php
require_once "Game.php";
require_once "Round.php";

class Player
{
    /**
     * @var string Nom du joueur
     **/
    public string $name;

    /**
     * @var int Score final du joueur
     **/
    private int $score = 0;

    /**
     * @var array Tableau des points marqués par le joueur
     **/
    private array $scoreboard;

    /**
     * Constructeur de la classe Player
     * @param string $name Nom du joueur
     **/
    public function __construct(string $name)
    {
        $this->name = $name;
        // Création d'un premier round, que l'on met à l'indice 1 du tableau des scores
        $this->scoreboard[1] = new Round();
    }

    /**
     * Fonction permettant d'inscrire la valeur d'un lancer pour la mettre dans le numéro et le tour appropriés
     * @param int $value Valeur du lancer à sauvegarder
     * @param int $round_number Numéro du round (premier, deuxième, troisième, etc.) dans lequel on écrit la valeur
     * @param int $throw_number Numéro du lancer (premier, deuxième, troisième) dans lequel on écrit la valeur
     * @return void
     * @throws OutOfBoundsException Exception levée si le numéro du round n'est pas compris entre 1 et Game::MAX_ROUNDS
     **/
    public function save_throw_value(int $value, int $round_number, int $throw_number): void
    {
        // Vérification que le numéro du tour soit cohérent
        if ($round_number <= 0 || $round_number > Game::MAX_ROUNDS)
        {
            throw new OutOfBoundsException(
                "Le numéro du tour doit être compris entre 1 et " . Game::MAX_ROUNDS . "(inclus)"
            );
        }

        // Vérification que le numéro du lancer soit cohérent
        if ($throw_number <= 0 || $throw_number > 3)
        {
            throw new OutOfBoundsException(
                "Le numéro du lancer doit être compris entre 1 et " . Game::MAX_PIN . "(inclus)"
            );
        }

        // Récupération de l'objet Round à l'emplacement désiré
        /** @var Round $working_round */
        $working_round = $this->scoreboard[$round_number];

        if          ($throw_number === 1) { $working_round->set_first_throw   ($value);
        } elseif    ($throw_number === 2) { $working_round->set_second_throw  ($value);
        } elseif    ($throw_number === 3) { $working_round->set_third_throw   ($value);
        }
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
    public function get_scoreboard(): array
    {
        return $this->scoreboard;
    }

    /**
     * @param array $round_data Tableau contenant les valeurs des lancers
     * @return void
     */
    public function set_scoreboard(array $round_data): void
    {
        $this->scoreboard[] = $round_data;
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
        return $this->scoreboard[$round]->get_first_throw() + $this->scoreboard[$round]->get_second_throw() === 10;
    }

    public function did_strike_in_round(int $round): bool
    {
        return $this->scoreboard[$round]->get_first_throw() == 10;
    }

    /**
     * @return int Nombre de points marqués par le joueur
     */
    public function count_marked_points(): int
    {
        $count = 0;

        for ($i = 0; $i < count($this->scoreboard); $i++)
        {

            if ($this->scoreboard[$i]->is_Strike() && $this->scoreboard[$i]->get_turn() != 10)
            {
                $count += $this->scoreboard[$i]->get_first_throw();
                $count += $this->scoreboard[$i]->get_second_throw();
                $count += $this->scoreboard[$i + 1]->get_first_throw();
                $count += $this->scoreboard[$i + 1]->get_second_throw();
            } elseif ($this->scoreboard[$i]->is_Spare() && $this->scoreboard[$i]->get_turn() != 10)
            {
                $count += $this->scoreboard[$i]->get_first_throw();
                $count += $this->scoreboard[$i]->get_second_throw();
                $count += $this->scoreboard[$i + 1]->get_first_throw();

            } elseif ($this->scoreboard[$i]->is_Strike() && $this->scoreboard[$i]->get_turn() == 10)
            {
                $count += $this->scoreboard[$i]->get_first_throw();
                $count += $this->scoreboard[$i]->get_second_throw();
                $count += $this->scoreboard[$i]->get_third_throw();
            } elseif ($this->scoreboard[$i]->is_Spare() && $this->scoreboard[$i]->get_turn() == 10)
            {
                $count += $this->scoreboard[$i]->get_first_throw();
                $count += $this->scoreboard[$i]->get_second_throw();
                $count += $this->scoreboard[$i]->get_third_throw();
            } else
            {
                $count += $this->scoreboard[$i]->get_first_throw();
                $count += $this->scoreboard[$i]->get_second_throw();
            }
        }

        return $count;
    }

    public function new_round()
    {
        $this->scoreboard[] = new Round([0, 0]);
    }
}
