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
     * Constructeur permettant d'intégrer directement une liste de joueurs
     * @param array $players Liste initiale des joueurs
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
     * Fonction permettant d'inscrire la valeur du lancer dans le joueur actuel et le round actuel
     * @param int $value_to_save Valeur du lancer (entre 0 et 10)
     * @return void
     * @throws InvalidArgumentException Si la valeur du lancer est strictement inférieure à 0 ou supérieure à 10
     **/
    public function save_throw(int $value_to_save): void
    {
        // Vérification d'une valeur cohérente
        if (($value_to_save < 0) || ($value_to_save > 10))
        {
            throw new InvalidArgumentException("La valeur du lancer doit être comprise entre 0 et 10");
        }

        // On récupère le joueur actuel pour mettre la valeur du lancer
        // dans le tableau des points marqués grâce à la fonction intégrée
        $player = $this->get_current_player();
        $player->save_throw_value($value_to_save, $this->get_current_round(), $this->current_throw);

        $this->current_throw++;
    }


    public function current_player_did_spare(): bool
    {
        $player = $this->get_current_player();
        return $player->get_marked_points()[$this->get_current_round()][1] === 10;
    }

    /**
     * @return int Numéro du lancer actuel
     **/
    public function get_current_throw(): int
    {
        return $this->current_throw;
    }

    /**
     * @param int $current_throw Numéro du lancer actuel
     * @return void
     **/
    public function set_current_throw(int $current_throw): void
    {
        $this->current_throw = $current_throw;
    }

    /**
     * Fonction pour récupérer le numéro du round actuel
     * @return int Numéro du round actuel
     **/
    public function get_current_round(): int
    {
        return $this->current_round;
    }

    /**
     * Fonction retournant le nombre total de joueurs dans la partie.
     * @return int Nombre de joueurs total
     */
    public function get_player_number(): int
    {
        return sizeof($this->players);
    }

    /**
     * Fonction pour récupérer le joueur courant
     * @return Player Joueur courant
     * @throws OutOfBoundsException Si le numéro du joueur actuel est inférieur à 0 ou supérieur à sizeof($this->players)
     **/
    public function get_current_player(): Player
    {
        if (($this->current_player < 0) || ($this->current_player > sizeof($this->players)))
        {
            throw new OutOfBoundsException(
                "Le numéro de joueur doit être compris entre 1 et " . sizeof($this->players)
            );
        }

        return $this->players[$this->current_player];
    }


    /**
     * Fonction pour mettre à jour le numéro du round actuel
     * @param int $round Nouveau numéro de round
     * @return void
     * @throws InvalidArgumentException Si le numéro de round est inférieur à 1 ou supérieur à 11
     **/
    public function set_current_round(int $round): void
    {
        if (($round < 1) || ($round > 11))
        {
            throw new InvalidArgumentException("Le numéro de round doit être compris entre 1 et 11");
        }

        $this->current_round = $round;
    }

    /**
     * Fonction pour mettre à jour le numéro du joueur actuel
     * @param int $player Nouveau numéro de joueur
     * @return void
     * @note Si le numéro de joueur est supérieur à sizeof($this->players), on revient au joueur 1
     */
    public function set_current_player(int $player): void
    {
        if ($player >= sizeof($this->players))
        {
            $player = 1;
        }

        $this->current_player = $player;
    }

    /**
     * Fonction pour ajouter un joueur après que l'objet a été créé
     * @param Player $player
     * @return void
     * @throws InvalidArgumentException Exception levée si le joueur est déjà présent dans la partie
     **/
    public function add_player(Player $player): void
    {
        // Check if the parameter $player is the good class
        if (!(isset($player->name)))
        {
            throw new InvalidArgumentException("Le joueur ne peut pas être nul");
        }

        $this->players[] = $player;
    }

    /**
     * Fonction pour récupérer un objet Player à un emplacement du tableau via $id
     * @param int $id Joueur à récupérer
     * @return Player Joueur trouvé
     * @throws InvalidArgumentException Si $id dépasse les bornes du tableau
     **/
    public function get_player_at(int $id): Player
    {
        if (($id >= 0) && ($id < sizeof($this->players)))
        {
            return $this->players[$id];
        } else
        {
            throw new InvalidArgumentException("L'ID dépasse le nombre de joueurs présents");
        }
    }

    /**
     * Fonction pour récupérer les joueurs de la partie
     * @return array Liste des joueurs
     */
    public function get_players(): array
    {
        return $this->players;
    }


    public function next(): void
    {
        $this->current_player++;

        // Si le numéro du joueur est supérieur au nombre de joueurs total, on revient au joueur 0
        if ($this->current_player >= sizeof($this->players))
        {
            $this->current_player = 0;
            $this->current_round++;
            $this->current_throw = 1;
            $this->get_current_player()->new_round();
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
