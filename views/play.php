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
    <?php foreach($game->get_players() as $player): ?>
    <h3 class="text-2xl text-center font-semibold"><?= htmlspecialchars($player->name) ?></h3>
    <table class="table-auto w-full">
    <thead>
    <tr>
            <?php for ($i = 1 ; $i <= sizeof($player->get_scoreboard()); $i++): ?>
            <?php if($i !== $game->get_rounds()): ?>
            <th scope="col" colspan="2" class="border px-4 py-1">Tour <?= $i ?></th>
            <?php else: ?>
            <th scope="col" colspan="3" class="border px-4 py-1">Tour <?= $i ?></th>
            <?php endif; ?>
            <?php endfor; ?>
            <th scope="col" class="border px-4 py-1">Score total</th>
        </tr>
    </thead>
    <tbody>
        <tr>
        <?php for ($i = 1 ; $i <= $game->get_current_round() ; $i++): ?>


            <!-- Affichage des lancers -->
            <?php 
                $first = $player->get_first_throw_score($i);
                $second = $player->get_second_throw_score($i);
                $third = $player->get_third_throw_score($i);
            
            if ($i === $game->get_rounds()){ // Si on est au dernier tour
                if ($first === null) { 
                    $first = "-";
                }
                if ($second === null) { 
                    $second = "-";
                }
                if ($third === null) { 
                    $third = "-";
                }
                if ($player->get_first_throw_score($i) === $game->get_pins()){ 
                    $first = "X";
                }
                if ($player->get_second_throw_score($i) === $game->get_pins()){ 
                    $second = "X";
                }
                if ($player->get_first_throw_score($i) + $player->get_second_throw_score($i) === $game->get_pins()){ 
                    if ($player->get_first_throw_score($i) === $game->get_pins()){ 
                        $first = "X";
                    }else{
                        $second = "/";
                    }
                }
                if ($player->get_second_throw_score($i) + $player->get_third_throw_score($i) === $game->get_pins()){
                    if ($player->get_second_throw_score($i) === $game->get_pins()){ 
                        $second = "X";
                    }else{
                        $third = "/";
                    }
                }
                if ($player->get_third_throw_score($i) === $game->get_pins()){ 
                    $third = "X";
                }
            ?>
            <td class="text-center border px-4 py-1"><?= $first ?></td>
            <td class="text-center border px-4 py-1"><?= $second ?></td>
            <td class="text-center border px-4 py-1"><?= $third ?></td>
            <?php } else { // Si on est pas au dernier tour
                if ($first === null) { 
                    $first = "-";
                }
                if ($second === null) { 
                    $second = "-";
                }
                if ($player->get_second_throw_score($i) === $game->get_pins()){ 
                    $second = "X";
                }
                if ($player->get_first_throw_score($i) + $player->get_second_throw_score($i) === $game->get_pins()){ 
                    if ($player->get_first_throw_score($i) === $game->get_pins()){ 
                        $first = "X";
                        $second = "-";
                    } else {
                        $second = "/";
                    }
                }

            ?>
            <td class="text-center border px-4 py-1"><?= $first ?></td>
            <td class="text-center border px-4 py-1"><?= $second ?></td>
            <?php } ?>
        <?php endfor; ?>

            <td rowspan="2" colspan="2" class="text-center text-xl border px-4 py-1"><?= ($total_score = $game->total_score_for_player($player)) === null ? "-" : $total_score; ?></td>
        </tr>
        <tr>
            <?php for ($i = 1 ; $i <= $game->get_current_round() ; $i++): ?>
            <!-- Affichage du score du round -->
            <?php if($i !== $game->get_rounds()): ?>
            <td colspan="2" class="text-center border px-4 py-1"><?= ($v = $game->calculate_score_in_round_for_player($player, $i)) === null ? "-" : $v; ?></td>
            <?php else: ?>
            <td colspan="3" class="text-center border px-4 py-1"><?= ($v = $game->calculate_score_in_round_for_player($player, $i)) === null ? "-" : $v; ?></td>
            <?php endif; ?>
            <?php endfor; ?>
        </tr>
        </tbody>
        </table>
    <?php endforeach; ?>
</main>
</body>
</html>
