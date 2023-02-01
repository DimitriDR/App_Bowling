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

    public function get_first_throw_score(int $r): ?int
    {
        return $this->scoreboard[$r]->get_first_throw();
    }

    public function get_second_throw_score(int $r): ?int
    {
        return $this->scoreboard[$r]->get_second_throw();
    }

    public function get_third_throw_score(int $r): ?int
    {
        return $this->scoreboard[$r]->get_third_throw();
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
        if ($round_number < 1)
        {
            throw new OutOfBoundsException("Le numéro du round doit être supérieur ou égal à 1");
        } else if ($throw_number < 1)
        {
            throw new OutOfBoundsException("Le numéro du lancer doit être supérieur ou égal à 1");
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
     * Fonction permettant d'ajouter un nouveau round au tableau des scores
     * @return void
     **/
    public function new_round(): void
    {
        $this->scoreboard[] = new Round();
    }
}
