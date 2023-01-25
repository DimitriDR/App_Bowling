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

// Vérification du type de la valeur saisie
if (!is_numeric($_POST["throw_value"]))
{
    $_SESSION["error_message"] = "Le champ saisi n'est pas un nombre entier.";
    header("Location: /play.php");
}



$game->register_throw($_POST["throw_value"]);

header("Location: /play.php");
exit();
