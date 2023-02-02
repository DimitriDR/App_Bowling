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
     * @param array $players  Liste des joueurs présents dans la partie
     * @param int $rounds     Nombre de tours de la partie choisie par le joueur
     * @param int $pin_number Nombre de quilles dans le jeu
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

        $this->players = $players;
        $this->rounds = $rounds;
        $this->pins = $pin_number;
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
     * Accesseur tableau des joueurs
     * @return array Tableau des joueurs
     **/
    public function get_players(): array
    {
        return $this->players;
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

        // On récupère le joueur courant
        $player = $this->get_current_player();

        // Au premier et deuxième lancer, on ne peut pas mettre un nombre de quilles tombées que ce qu'il reste
        if ($this->current_throw < 3)
        {
            if ($this->current_round != $this->rounds)
            {
                if ($player->get_first_throw_score($this->get_current_round()) != $this->pins)
                {
                    if ($value_to_save > $this->pins - $player->get_first_throw_score($this->get_current_round()))
                    {
                        throw new LogicException("La valeur du lancer doit être inférieure ou égale à " . ($this->pins - $player->get_first_throw_score($this->get_current_round())));
                    }
                }

            } else
            {
                if (!($this->player_did_strike_in_round($player, $this->get_current_round())))
                {
                    if ($value_to_save > $this->pins - $player->get_first_throw_score($this->get_current_round()))
                    {
                        throw new LogicException("La valeur du lancer doit être inférieure ou égale à " . ($this->pins - $player->get_first_throw_score($this->get_current_round())));
                    }
                }
            }
        } else // Au dernier round, on ne peut pas mettre le nombre que l'on veut sauf si l'on a fait un spare ou un strike
        {
            if (!($this->player_did_strike_in_round($player, $this->get_current_round())) && !($this->player_did_spare_in_round($player, $this->get_current_round())))
            {
                throw new LogicException("La valeur du lancer doit être inférieure ou égale à " . ($this->pins - $player->get_first_throw_score($this->get_current_round())));
            }

        }

        // dans le tableau des points marqués grâce à la fonction intégrée
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
        $current_round = $this->get_current_round();

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
     * @param Player $p         Nom du joueur dont on veut savoir s'il a fait un strike
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
     * @param Player $p         Nom du joueur dont on veut savoir s'il a fait un spare
     * @param int $round_number Numéro du round dans lequel on veut savoir si le joueur a fait un spare
     * @return bool Vrai si le joueur a fait un spare, faux sinon.
     **/
    private function player_did_spare_in_round(Player $p, int $round_number): bool
    {
        if ($round_number < 1 || $round_number > $this->rounds)
        {
            throw new OutOfBoundsException("Le numéro du tour doit être compris entre 1 et " . $this->rounds . ".");
        }

        $first_throw = $p->get_scoreboard()[$round_number]->get_first_throw();
        $second_throw = $p->get_scoreboard()[$round_number]->get_second_throw();

        return $first_throw + $second_throw === $this->pins;
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

            foreach ($this->players as $player)
            {
                $player->new_round();
            }

        } else // Sinon, on passe simplement au round suivant
        {
            $this->current_throw = 1;
        }
    }

    /**
     * Fonction pour calculer le score d'un joueur dans un round donné.
     *
     * @param Player $p  Joueur dont on veut calculer le score
     * @param int $round Round dans lequel on veut calculer le score
     * @return int|null     Score du joueur dans le round donné, ou null si le joueur n'a pas encore joué ce round
     **/
    public function calculate_score_in_round_for_player(Player $p, int $round): ?int
    {
        $first_throw = $p->get_first_throw_score($round);
        $second_throw = $p->get_second_throw_score($round);

        // Si le premier lancer n'a pas été fait, on ne peut assurément pas calculer le score d'un round
        if ($first_throw === null)
        {
            return null;
        }

        // Dans le cas où l'on n'a fait ni strike, ni spare
        // Cette condition implique qu'au moins un lancer a été fait
        if (!$this->player_did_strike_in_round($p, $round) && !$this->player_did_spare_in_round($p, $round))
        {
            return $first_throw + $second_throw;
        }

        // Si on est au dernier round
        if ($round === $this->rounds)
        {
            // Si le joueur a fait un spare, on attend le troisième lancer pour calculer le score
            // Sous-entend que l'on a déjà fait le premier et le deuxième lancer
            if ($this->player_did_spare_in_round($p, $round))
            {
                if ($p->get_third_throw_score($round) === null)
                {
                    return null;
                } else
                {
                    return $this->pins + $p->get_third_throw_score($round);
                }
            } elseif ($this->player_did_strike_in_round($p, $round))
            {
                // Si le joueur a fait un strike, on attend le deuxième et le troisième lancer pour calculer le score
                // Sous-entend que l'on a déjà fait le premier lancer
                if ($p->get_second_throw_score($round) === null || $p->get_third_throw_score($round) === null)
                {
                    return null;
                } else
                {
                    return $this->pins + $p->get_second_throw_score($round) + $p->get_third_throw_score($round);
                }
            }
        } else // Sinon, on est dans un round intermédiaire
        {
            // Si le joueur a fait un spare, on attend le premier lancer du round suivant pour calculer le score
            if ($this->player_did_strike_in_round($p, $round))
            {
                // Si le joueur a fait un strike, on attend le premier et le deuxième lancer du round suivant pour calculer le score
                if ($p->get_first_throw_score($round + 1) === null || $p->get_second_throw_score($round + 1) === null)
                {
                    return null;
                } else
                {
                    return $this->pins + $p->get_first_throw_score($round + 1) + $p->get_second_throw_score($round + 1);
                }
            } elseif ($this->player_did_spare_in_round($p, $round))
            {
                if ($p->get_first_throw_score($round + 1) === null) { return null; }
                else
                {
                    return $this->pins + $p->get_scoreboard()[$round + 1]->get_first_throw();
                }
            } else
            {
                if ($second_throw === null)
                {
                    return null;
                } else
                {
                    return $first_throw + $second_throw;
                }
            }
        }

        return null;
    }

    /**
     * Fonction permettant de calculer le score total d'un joueur
     * @param Player $p Joueur dont on veut calculer le score
     * @return int|null Score total du joueur, ou null si l'on n'est pas en capacité de calculer le score
     *                  (par exemple, si le joueur n'a pas fait le deuxième lancer, en attente du suivant, etc.).
     */
    public function total_score_for_player(Player $p): ?int
    {
        $total = 0;

        for ($i = 1 ; $i <= sizeof($p->get_scoreboard()) ; $i++)
        {
            $round_score = $this->calculate_score_in_round_for_player($p, $i);

            if ($round_score === null)
            {
                break;
            }

            $total += $round_score;
        }

        return $total;
    }
}
