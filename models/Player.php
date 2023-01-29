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
     * @return array Tableau contenant les points marqués par le joueur
     */
    public function get_scoreboard(): array
    {
        return $this->scoreboard;
    }

    /**
     * Fonction permettant d'inscrire la valeur d'un lancer pour la mettre dans le numéro et le tour appropriés
     * @param int $value        Valeur du lancer à sauvegarder
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
                "Le numéro du lancer doit être compris entre 1 et 3 (inclus)"
            );
        }

        // Récupération de l'objet Round à l'emplacement désiré
        /** @var Round $working_round */
        $working_round = $this->scoreboard[$round_number];

        if ($throw_number === 1)
        {
            $working_round->set_first_throw($value);
        } elseif ($throw_number === 2)
        {
            $working_round->set_second_throw($value);
        } elseif ($throw_number === 3)
        {
            $working_round->set_third_throw($value);
        }
    }

    /**
     * Fonction permettant de savoir si le joueur a fait un spare dans n'importe quel round
     * @param int $round Numéro du round dans lequel on veut savoir si le joueur a fait un spare (valeur entre 1 et 10)
     * @return bool Vrai si le joueur a fait un spare, faux sinon
     */
    public function did_spare_in_round(int $round): bool
    {
        if ($round < 1 || $round > 10)
        {
            throw new OutOfBoundsException("Le numéro du round doit être compris entre 1 et 10");
        }

        return $this->scoreboard[$round]->get_first_throw() + $this->scoreboard[$round]->get_second_throw() === 10;
    }

    public function did_strike_in_round(int $round): bool
    {
        if ($round < 1 || $round > 10)
        {
            throw new OutOfBoundsException("Le numéro du round doit être compris entre 1 et 10");
        }

        return $this->scoreboard[$round]->get_first_throw() == 10;
    }

    /**
     * Fonction permettant d'ajouter un nouveau round au tableau des scores
     * @return void
     **/
    public function new_round(): void
    {
        $this->scoreboard[] = new Round();
    }

    /**
     * Fonction permettant de calculer le score final du joueur
     * @return int Nombre de points marqués par le joueur
     **/
    public function point_calculation(): int
    {
        $total = 0;

        for ($i = 1 ; $i <= sizeof($this->scoreboard) ; $i++)
        {
            if ($this->did_spare_in_round($i))
            {
                $total += 10;

                // Si ce n'est pas le dernier round, on va regarder le premier lancer du round suivant
                if ($i < 10)
                {
                    $total += $this->scoreboard[$i + 1]->get_first_throw();
                } else // Sinon, on regarde dans le troisième lancer
                {
                    $total += $this->scoreboard[$i]->get_third_throw();
                }
            } elseif ($this->did_strike_in_round($i))
            {
                $total += 10;

                if ($i < 10) {
                    $total += $this->scoreboard[$i + 1]->get_first_throw();
                    $total += $this->scoreboard[$i + 1]->get_second_throw();
                } else {
                    $total += $this->scoreboard[$i]->get_second_throw();
                    $total += $this->scoreboard[$i]->get_third_throw();
                }
            } else // Si le joueur n'a pas fait de spare, alors on ne fait qu'additionner les points
            {
                $total += $this->scoreboard[$i]->get_first_throw();
                $total += $this->scoreboard[$i]->get_second_throw();
            }
        }

        return $total;
    }
}
