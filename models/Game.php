<?php

class Game
{
    /**
     * @var array Liste des joueurs présents dans la partie
    **/
    private array $players = [];

    /**
     * @var int Numéro du round actuel
     * @note Le premier round est le round 1. Le dernier round est le round 10. Le round 11 est le round bonus : il est utilisé uniquement si le joueur fait un strike ou un spare au round 10
     */
    private int $current_round = 1;

    /**
     * @var int Numéro du joueur actuel
     * @note Le premier joueur est le joueur 1. Le dernier joueur est le joueur sizeof($this->players)
    **/
    private int $current_player = 1;

    /**
     * Fonction pour récupérer le numéro du round actuel
     * @return int Numéro du round actuel
    **/
    public function get_current_round(): int
    {
        return $this->current_round;
    }

    /**
     * Fonction pour récupérer le numéro du joueur courant
     * @return int Numéro du joueur courant
    **/
    public function get_current_player(): int
    {
        return $this->current_player;
    }

    /**
     * Fonction pour mettre à jour le numéro du round actuel
     * @param int $round Nouveau numéro de round
     * @return void
     * @throws InvalidArgumentException Si le numéro de round est inférieur à 1 ou supérieur à 11
    **/
    public function set_current_round(int $round): void
    {
        if (($round < 1) || ($round > 11)) {
            throw new InvalidArgumentException("Le numéro de round doit être compris entre 1 et 11");
        }

        $this->current_round = $round;
    }

    /**
     * Fonction pour mettre à jour le numéro du joueur actuel
     * @param int $player Nouveau numéro de joueur
     * @return void
     * @throws InvalidArgumentException Si le numéro de joueur est inférieur à 1 ou supérieur à sizeof($this->players)
     */
    public function set_current_player(int $player): void
    {
        if (($player < 1) || ($player > sizeof($this->players))) {
            throw new InvalidArgumentException("Le numéro de joueur doit être compris entre 1 et " . sizeof($this->players));
        }

        $this->current_player = $player;
    }

    /**
     * Constructeur permettant d'intégrer directement une liste de joueurs
     * @param array $players Liste initiale des joueurs
    **/
    public function __construct(array $players)
    {
        foreach ($players as $player)
        {
            if (!is_a($player, Player::class))
                throw new InvalidArgumentException("Un joueur doit être une instance de la classe Player");
        }

        $this->players = $players;
    }

    /**
     * Fonction pour ajouter un joueur après que l'objet ait été créé
     * @param Player $player
     * @return void
     * @throws InvalidArgumentException Exception levée si le joueur est déjà présent dans la partie
    **/
    public function add_player(Player $player): void
    {
        // Check if the parameter $player is the good class
        if (!(isset($player->name)))
            throw new InvalidArgumentException("Le joueur ne peut pas être nul");

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
        } else {
            throw new InvalidArgumentException("L'ID dépasse le nombre de joueurs présents");
        }
    }

    /**
     * Fonction pour démarrer une partie
     * @return void
     * @throws InvalidArgumentException Si la partie a déjà été démarrée
     */
    public function start(): void
    {
        if ($this->current_round != 1)
        {
            throw new InvalidArgumentException("La partie a déjà été démarrée");
        }

        $this->current_round    = 1;
        $this->current_player   = 1;
    }
}