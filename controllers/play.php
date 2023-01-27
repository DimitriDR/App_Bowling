<?php
require_once "header.start_session.php";
require_once dirname(__DIR__) . "/models/Game.php";
require_once dirname(__DIR__) . "/models/Player.php";

if (!isset($_POST["submit"]))
{
    $_SESSION["error_message"] = "Aucun formulaire n'a été soumis.";
    header("Location: /");
}

/** Pour forcer l'IDE à connaître le type de la variable **/
/* @var Game $game */
$game = unserialize($_SESSION["game"]);

$throw_value = $_POST["throw_value"];

// Vérification du type de la valeur saisie
if (!is_numeric($throw_value))
{
    $_SESSION["error_message"] = "Le champ saisi n'est pas un nombre entier.";
    header("Location: /play.php");
}

// Si on est dans le premier ou le deuxième lancer du joueur courant, on l'enregistre


$game->save_throw($throw_value);

// La fonction `save_throw` ayant déjà incrémenté "current_throw", celle que l'on va récupérer le coup suivant
$next_current_throw = $game->get_current_throw();

if ($next_current_throw >= 3 || $throw_value == 10)
{
    $game->next();
}

$_SESSION["game"] = serialize($game);

header("Location: /play.php");
exit();

