<?php
require_once "header.start_session.php";

const REDIRECT_URL = "Location: /";

if (!isset($_POST["submit"]))
{
    $_SESSION["error_message"] = "Aucun formulaire n'a été soumis.";
    header(REDIRECT_URL);
}

// Vérification du type de la variable donnée
if (!is_numeric($_POST["player_number"]))
{
    $_SESSION["error_message"] = "Le champ saisi n'est pas un nombre entier.";
    header(REDIRECT_URL);
}

$player_number = (int)$_POST["player_number"];

// Vérification du nombre de joueurs
if ($player_number <= 0 || $player_number > 20)
{
    $_SESSION["error_message"] = "Le nombre de joueurs n'est pas compris dans les bornes prévues.";
    header(REDIRECT_URL);
}

// On stocke temporairement le nom nombre de joueurs dans la partie
// L'objet Game prendra le relai une fois que les noms auront été saisis
$_SESSION["player_number"] = $player_number;

header("Location: /player_name.php");
exit(0);
