<?php
require_once dirname(__DIR__) . "/controllers/header.start_session.php";
require_once dirname(__DIR__) . "/models/Game.php";
require_once dirname(__DIR__) . "/models/Player.php";

/** Pour que l'IDE sache le type de la variable */
/** @var Game $game */
$game = unserialize($_SESSION["game"]);

$current_round = $game->get_current_round();
$current_player = $game->get_current_player();

if ($game->get_current_round() > $game->get_rounds())
{
    header("Location: /end.php");
    exit(0);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bowlin' Time</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
</head>
<body>
<main class="container mx-auto border rounded-md px-10 py-5 mt-6">
    <?php require_once "display_errors.php"; ?>
    <h1 class="text-4xl text-center font-semibold">Jouons !</h1>
    <h2 class="text-4xl text-center font-light">Tour <?= $current_round ?> – <?= htmlspecialchars($current_player->name) ?></h2>
    <form method="POST" action="/controllers/play.php" class="grid grid-cols-1 gap-4 content-center mx-auto">
        <label for="throw_value" class="text-xl mt-5 text-center font-semibold">Saisir le score du lancer n°<?= $game->get_current_throw() ?></label>
        <input class="mx-auto w-80 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" name="throw_value" id="throw_value" min="0" max="<?= $game->get_pins() ?>" required autofocus>
        <button class="text-white w-80 ml-auto mr-auto bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-2" name="submit" id="submit" type="submit">
            Valider
        </button>
    </form>
    <?php
    foreach($game->get_players() as $player)
    {
        echo "<h3 class=\"text-2xl text-center font-semibold\">" . htmlspecialchars($player->name) . "</h3>";
        echo "<table class=\"table-auto w-full\">";
        echo "<thead>";
        echo "<tr>";
        for($i = 1; $i <= $game->get_rounds(); $i++)
        {
            if($i == $game->get_rounds()){
                echo "<th colspan=\"3\" class=\"border px-4 py-2\"> Tour " . $i . "</th>";
            }
            else{
                echo "<th colspan=\"2\" class=\"border px-4 py-2\"> Tour " . $i . "</th>";
            }
        }
        if ($game->point_calculation($player)>=0){
            echo "<th class=\"border px-4 py-2\"> Score Total <br>" . $game->point_calculation($player) . "</th>";
        }else{
            echo "<th class=\"border px-4 py-2\"> Score Total <br>0</th>";
        }
        echo "</tr>";
        echo "<tr>";
        if($current_round == $game->get_rounds()){
            foreach($player->get_scoreboard() as $round_number => $round_object){
                if ($round_object->get_first_throw() !== -1): echo "<td class=\"border px-4 py-2 text-center\">" . $round_object->get_first_throw() . "</td>"; else: echo "<td class=\"border px-4 py-2 text-center\">-</td>"; endif;
                if ($round_object->get_second_throw() !== -1): echo "<td class=\"border px-4 py-2 text-center\">" . $round_object->get_second_throw() . "</td>"; else: echo "<td class=\"border px-4 py-2 text-center\">-</td>"; endif;
                if ($round_object->get_third_throw() !== -1): echo "<td class=\"border px-4 py-2 text-center\">" . $round_object->get_third_throw() . "</td>";endif;
            }
        }else{
            foreach($player->get_scoreboard() as $round_number => $round_object){
                if ($round_object->get_first_throw() !== -1): echo "<td class=\"border px-4 py-2 text-center\">" . $round_object->get_first_throw() . "</td>"; else: echo "<td class=\"border px-4 py-2 text-center\">-</td>"; endif;
                if ($round_object->get_second_throw() !== -1): echo "<td class=\"border px-4 py-2 text-center\">" . $round_object->get_second_throw() . "</td>"; else: echo "<td class=\"border px-4 py-2 text-center\">-</td>"; endif;
            }
        }
        echo "</tr>";
        echo "</thead>";
        echo "</table>";
    }
    ?>
</main>
</body>
</html>
