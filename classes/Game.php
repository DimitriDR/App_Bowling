<?php
require_once "classes/Player.php";

class Game
{
    /**
     * @var array Liste des joueurs présents dans la partie
    **/
    private array $players = [];

    /**
     * Constructeur permettant d'intégrer directement une liste de joueurs
     * @param array $players Liste initiale des joueurs
    **/
    public function __construct(array $players)
    {
        $this->players = $players;
    }

    /**
     * Fonction pour ajouter un joueur après que l'objet ait été créé
     * @param Player $player
     * @return void
    **/
    public function add_player(Player $player): void
    {
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
}