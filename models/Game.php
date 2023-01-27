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
     * @var int Nombre de rounds maximum
     **/
    public const MAX_ROUNDS = 10;

    /**
     * @var int Nombre maximum de quilles dans le jeu
     **/
    public const MAX_PIN = 10;

    /**
     * Constructeur permettant d'intégrer directement une liste de joueurs
     *
     * @param array $players Liste initiale des joueurs
     * @throws InvalidArgumentException Si un des joueurs n'est pas une instance de la classe Player
     **/
    public function __construct(array $players)
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
     * Fonction permettant d'enregistrer la valeur du lancer dans le joueur actuel et le round actuel
     *
     * @param int $value_to_save Valeur du lancer (entre 0 et MAX_PIN)
     * @return void
     * @throws InvalidArgumentException Si la valeur du lancer est strictement inférieure à 0 ou supérieure à MAX_PIN
     **/
    public function save_throw(int $value_to_save): void
    {
        // Vérification que la valeur donnée soit cohérente
        if ($value_to_save < 0 || $value_to_save > self::MAX_PIN)
        {
            throw new InvalidArgumentException("La valeur du lancer doit être comprise entre 0 et " . self::MAX_PIN);
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
        $player = $this->get_current_player();
        return $player->get_scoreboard()[$this->get_current_round()]->get_first_throw() + $player->get_scoreboard()[$this->get_current_round()]->get_second_throw() === 10;
    }

    /**
     * Fonction permettant de savoir si le joueur courant a fait un strike dans le round courant.
     * @return bool Vrai si le joueur a fait un strike, faux sinon.
     **/
    public function current_player_did_strike(): bool
    {
        $player = $this->get_current_player();
        return $player->get_scoreboard()[$this->get_current_round()]->get_first_throw() === 10;
    }

    /**
     * Fonction permettant de gérer automatiquement le passage, soit au joueur suivant, soit au round suivant
     * selon le cas. Elle incrémente d'un round quand tous les joueurs ont joué le round en question. Sinon,
     * elle donne la main au joueur suivant en incrémentant la variable du joueur courant.
     * @return void
     */
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

}
