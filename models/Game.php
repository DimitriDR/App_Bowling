<?php
require_once "Player.php";

class Game
{
    /**
     * @var array Liste des joueurs présents dans la partie
     **/
    private array $players = [];

    /**
     * @var int Numéro du round actuel
     *
     * Le premier round est le round 1. Le dernier round est le round 10.
     * Le round 11 est le round bonus : il est utilisé uniquement si le joueur fait un strike ou un spare au round 10
     **/
    private int $current_round = 1;

    /**
     * @var int Numéro du joueur actuel
     *
     * Le premier joueur est le joueur 0 (affiché 1 à l'utilisateur).
     * Le dernier joueur est le joueur sizeof($this->players)
     **/
    private int $current_player = 0;

    /**
     * @var int Numéro du lancer actuel
     *
     * Le premier lancer est le lancer 1. Le dernier lancer est le lancer 2.
     * Le lancer 3 est utilisé uniquement pour le dernier round
     * @note Si le joueur fait un strike, il n'a pas le droit au second lancer
     */
    private int $current_throw = 1;

    /**
     * @var int Nombre de tours de la partie choisie par le joueur
     */
    private int $rounds;

    /**
     * @var int Nombre de quilles dans le jeu
    **/
    private int $pins;

    /**
     * Constructeur permettant d'intégrer directement une liste de joueurs
     *
     * @param array $players    Liste des joueurs présents dans la partie
     * @param int $rounds       Nombre de tours de la partie choisie par le joueur
     * @throws InvalidArgumentException Si un des joueurs n'est pas une instance de la classe Player
     **/
    public function __construct(array $players, int $rounds, int $pin_number)
    {
        // On parcourt tous les joueurs pour vérifier qu'ils sont bien des instances de la classe Player
        foreach ($players as $player)
        {
            if (!is_a($player, Player::class))
            {
                throw new InvalidArgumentException("Un joueur doit être une instance de la classe Player");
            }
        }

        $this->players  = $players;
        $this->rounds   = $rounds;
        $this->pins     = $pin_number;
    }

    /**
     * Accesseur permettant de récupérer le nombre de tours de la partie
     * @return int Nombre de tours de la partie
     **/
    public function get_rounds(): int
    {
        return $this->rounds;
    }

    /**
     * Accesseur pour récupérer le lancer sur lequel on est
     * @return int Numéro du lancer
     **/
    public function get_current_throw(): int
    {
        return $this->current_throw;
    }

    /**
     * Accesseur pour récupérer le numéro du round dans lequel on est
     * @return int Numéro du round
     **/
    public function get_current_round(): int
    {
        return $this->current_round;
    }

    /**
     * Accesseur particulier permettant de récupérer le joueur courant sous sa forme objet
     * @return Player Joueur courant
     **/
    public function get_current_player(): Player
    {
        return $this->players[$this->current_player];
    }

    /**
     * Accesseur pour récupérer le nombre de quilles
     * @return int Nombre de quilles
     */
    public function get_pins(): int
    {
        return $this->pins;
    }

    /**
     * Fonction permettant d'enregistrer la valeur du lancer dans le joueur actuel et le round actuel
     *
     * @param int $value_to_save Valeur du lancer (entre 0 et MAX_PIN)
     * @return void
     * @throws InvalidArgumentException Si la valeur du lancer est strictement inférieure à 0 ou supérieure à MAX_PIN
     **/
    public function save_throw(int $value_to_save): void
    {
        // Vérification que la valeur donnée soit cohérente
        if ($value_to_save < 0 || $value_to_save > $this->pins)
        {
            throw new InvalidArgumentException("La valeur du lancer doit être comprise entre 0 et " . $this->pins);
        }

        // On récupère le joueur actuel pour mettre la valeur du lancer
        // dans le tableau des points marqués grâce à la fonction intégrée
        $player = $this->get_current_player();
        $player->save_throw_value($value_to_save, $this->get_current_round(), $this->current_throw);

        $this->current_throw++;
    }

    /**
     * Fonction permettant de savoir si le joueur courant a fait un spare dans le round courant.
     * @return bool Vrai si le joueur a fait un spare, faux sinon.
     **/
    public function current_player_did_spare(): bool
    {
        $current_player = $this->players[$this->current_player];
        $current_round  = $this->get_current_round();

        return $this->player_did_spare_in_round($current_player, $current_round);
    }

    /**
     * Fonction permettant de savoir si le joueur courant a fait un strike dans le round courant.
     * @return bool Vrai si le joueur a fait un strike, faux sinon.
     **/
    public function current_player_did_strike(): bool
    {
        return $this->current_player_did_strike_in_round($this->get_current_round());
    }

    /**
     * Fonction permettant de savoir si un joueur a fait un strike dans un round donné.
     * @param Player $p Nom du joueur dont on veut savoir s'il a fait un strike
     * @param int $round_number Numéro du round dans lequel on veut savoir si le joueur a fait un strike
     * @return bool Vrai si le joueur a fait un strike, faux sinon.
    **/
    private function player_did_strike_in_round(Player $p, int $round_number): bool
    {
        if ($round_number < 1 || $round_number > $this->rounds)
        {
            throw new OutOfBoundsException("Le numéro du tour doit être compris entre 1 et " . $this->rounds . ".");
        }

        return $p->get_scoreboard()[$round_number]->get_first_throw() === $this->pins;
    }

    /**
     * Fonction permettant de savoir si un joueur a fait un spare dans un round donné.
     * @param Player $p Nom du joueur dont on veut savoir s'il a fait un spare
     * @param int $round_number Numéro du round dans lequel on veut savoir si le joueur a fait un spare
     * @return bool Vrai si le joueur a fait un spare, faux sinon.
    **/
    private function player_did_spare_in_round(Player $p, int $round_number): bool
    {
        if ($round_number < 1 || $round_number > $this->rounds)
        {
            throw new OutOfBoundsException("Le numéro du tour doit être compris entre 1 et " . $this->rounds . ".");
        }

        return $p->get_scoreboard()[$round_number]->get_first_throw() +  $p->get_scoreboard()[$round_number]->get_second_throw() === $this->pins;
    }

    /**
     * Fonction permettant de savoir si le joueur courant a fait un strike dans le round donné.
     * @param int $round_number Numéro du round dans lequel on veut savoir si le joueur a fait un strike
     * @return bool Vrai si le joueur a fait un strike, faux sinon.
    **/
    private function current_player_did_strike_in_round(int $round_number): bool
    {
        $player = $this->get_current_player();
        if ($round_number < 1 || $round_number > $this->rounds)
        {
            throw new OutOfBoundsException("Le numéro du tour doit être compris entre 1 et " . $this->rounds . ".");
        }

        return $player->get_scoreboard()[$round_number]->get_first_throw() === $this->pins;
    }

    /**
     * Fonction permettant de gérer automatiquement le passage, soit au joueur suivant, soit au round suivant
     * selon le cas. Elle incrémente d'un round quand tous les joueurs ont joué le round en question. Sinon,
     * elle donne la main au joueur suivant en incrémentant la variable du joueur courant.
     * @return void
    **/
    public function next(): void
    {
        $this->current_player++;

        // Si le numéro du joueur est supérieur au nombre de joueurs total, on revient au joueur 0
        if ($this->current_player >= sizeof($this->players))
        {
            $this->current_player = 0;
            $this->current_round++;
            $this->current_throw = 1;

            if ($this->current_round > 1 && $this->current_round < 11)
            {
                $this->get_current_player()->new_round();
            }
        } else // Sinon, on passe simplement au round suivant
        {
            $this->current_throw = 1;
            if ($this->current_round > 1)
            {
                $this->get_current_player()->new_round();
            }
        }
    }

    /**
     * Fonction permettant de calculer le score final du joueur
     * @return int Nombre de points marqués par le joueur
     **/
    public function point_calculation(Player $p): int
    {
        $total = 0;

        for ($i = 1 ; $i <= sizeof($p->get_scoreboard()) ; $i++)
        {
            if ($this->player_did_spare_in_round($p, $i))
            {
                $total += $this->pins;

                // Si ce n'est pas le dernier round, on va regarder le premier lancer du round suivant
                if ($i < $this->rounds)
                {
                    $total += $p->get_first_throw_score($i+1);
                } else // Sinon, on regarde dans le troisième lancer
                {
                    $total += $p->get_third_throw_score($i);
                }
            } elseif ($this->player_did_strike_in_round($p, $i))
            {
                $total += $this->pins;

                if ($i < 10) {
                    $total += $p->get_first_throw_score($i+1);
                    $total += $p->get_second_throw_score($i+1);
                } else {
                    $total += $p->get_second_throw_score($i);
                    $total += $p->get_third_throw_score($i);
                }
            } else // Si le joueur n'a pas fait de spare, alors on ne fait qu'additionner les points
            {
                $total += $p->get_first_throw_score($i);
                $total += $p->get_second_throw_score($i);
            }
        }

        return $total;
    }
}
