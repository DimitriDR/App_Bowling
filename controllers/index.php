<?php
require_once "header.start_session.php";

const REDIRECT_URL = "Location: /";
const MIN_PLAYER_NUMBER = 1;
const MAX_PLAYER_NUMBER = 20;

const MIN_PIN_NUMBER = 1;


if (!isset($_POST["submit"]))
{
    $_SESSION["error_message"] = "Aucun formulaire n'a été soumis.";
    header(REDIRECT_URL);
}

// Vérification du type de la variable donnée
if (!is_numeric($_POST["player_number"]) || !is_numeric($_POST["pin_number"]))
{
    $_SESSION["error_message"] = "Le champ saisi n'est pas un nombre entier.";
    header(REDIRECT_URL);
}

$player_number = (int) $_POST["player_number"];
$pin_number = (int) $_POST["pin_number"];

// Vérification du nombre de joueurs
if ($player_number < MIN_PLAYER_NUMBER && $player_number > MAX_PLAYER_NUMBER)
{
    $_SESSION["error_message"] = "Le nombre de joueurs doit être compris entre " . MIN_PLAYER_NUMBER . " et " . MAX_PLAYER_NUMBER .".";
    header(REDIRECT_URL);
}
// Vérification du nombre de quilles
if ($pin_number < MIN_PIN_NUMBER)
{
    $_SESSION["error_message"] = "Le nombre de quilles doit être supérieur à " . MIN_PIN_NUMBER . ".";
    header(REDIRECT_URL);
}

// On stocke temporairement le nombre de joueurs dans la partie
// L'objet Game prendra le relai une fois que les noms auront été saisis
$_SESSION["player_number"] = $player_number;
$_SESSION["pin_number"] = $pin_number;

header("Location: /player_name.php");
exit(0);
