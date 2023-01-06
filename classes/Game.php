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
        if (sizeof($players) == 0)
            throw new InvalidArgumentException("Un jeu doit au moins contenir un joueur");

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
}