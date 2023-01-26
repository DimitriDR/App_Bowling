<?php
require_once "header.start_session.php";
require_once dirname(__DIR__) . "/models/Game.php";
require_once dirname(__DIR__) . "/models/Player.php";

if (!isset($_POST["submit"]))
{
    $_SESSION["error_message"] = "Aucun formulaire n'a été soumis.";
    header("Location: /");
}

$game = unserialize($_SESSION["game"]);

$throw_value = $_POST["throw_value"];

// Vérification du type de la valeur saisie
if (!is_numeric($throw_value))
{
    $_SESSION["error_message"] = "Le champ saisi n'est pas un nombre entier.";
    header("Location: /play.php");
}

// Si on est dans le premier et le second lancer, on enregistre le score et on passe au lancer suivant
if ($game->get_current_player()->get_next_throw_number($game->get_current_round()) <= 2)
{
    $game->register_throw($throw_value);
} else {
    if ($game->get_current_player()->get_marked_points()[$game->get_current_round()]->is_spare()) {
        $game->register_throw($throw_value);
    }

    $game->next_player();
}


$_SESSION["game"] = serialize($game);

header("Location: /play.php");
exit();
