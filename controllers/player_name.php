<?php
require_once "header.start_session.php";
require_once dirname(__DIR__) . "/models/Player.php";
require_once dirname(__DIR__) . "/models/Game.php";

// Gestion des manipulations de l'utilisateur
if (!isset($_POST["submit"]))
{
    $_SESSION["error_message"] = "Aucun formulaire n'a été soumis.";
    header("Location: /");
}

// Vérification que tous les champs soient passés un par un
for ($i = 0 ; $i < $_SESSION["player_number"] ; $i++)
{
    if (!(isset($_POST["player_name_" . $i])))
    {
        $_SESSION["error_message"] = "Le nom d'un joueur est manquant. Veuillez réessayer.";
        header("Location: /player_name.php");
        break;
    }
}

$players = array();

// Si tous les champs sont OK, on crée un objet pour chacun des joueurs renseignés
for ($j = 0 ; $j < $_SESSION["player_number"] ; $j++)
{
    // Création du nouveau joueur que l'on va rajouter dans le tableau des joueurs
    $new_player = new Player($_POST["player_name_" . $j]);
    $players[] = $new_player;
}

// Création du jeu auquel on y ajoute directement les joueurs
$game = new Game($players, $_SESSION["rounds"], $_SESSION["pin_number"]);

// Suppression de la variable comportant le nombre de joueurs, ainsi que le nombre de rounds
// dans la session maintenant que l'instance Game va prendre le relai
unset($_SESSION["player_number"]);
unset($_SESSION["rounds"]);
unset($_SESSION["pin_number"]);

$_SESSION["game"] = serialize($game);

header("Location: /play.php");
exit(0);
