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

// Enregistrement du score
$game->save_throw($throw_value);

// La fonction `save_throw` ayant déjà incrémenté "current_throw", celle que l'on va récupérer le coup suivant
$next_current_throw = $game->get_current_throw();

// Avant le dernier round, toujours le même schéma
if ($game->get_current_round() < $game->get_rounds())
{
    if ($next_current_throw >= 3 || $throw_value == 10)
    {
        $game->next();
    }
} else {
    // Dans le dixième et dernier round
    // On a un cas particulier si le joueur a fait un spare
   if ($next_current_throw === 3) { // Si le prochain lancer est le troisième, on le passe si le joueur n'a fait ni un spare ni un strike
       if (!($game->current_player_did_spare()) && !($game->current_player_did_strike()))
       {
           $game->next();
       }
   } elseif ($next_current_throw > 3) // Par contre, au-delà du troisième lancer, on passe forcément au joueur suivant
   {
       $game->next();
   }
}

$_SESSION["game"] = serialize($game);

header("Location: /play.php");
exit();

