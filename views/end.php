<?php
require_once dirname(__DIR__) . "/controllers/header.start_session.php";
require_once dirname(__DIR__) . "/models/Game.php"; ?>
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

    <?php

    $game = unserialize($_SESSION["game"]);

    // pour chaque joueur
    foreach ($game->get_players() as $player) : ?>
        <h3 class="text-2xl text-center font-semibold"><?= htmlspecialchars($player->name) ?></h3>
        <table class="table-auto w-full">
            <thead>
            <tr>
                <?php for ($i = 1 ; $i <= sizeof($player->get_scoreboard()) - 1 ; $i++): ?>
                    <?php if ($i !== $game->get_rounds()): ?>
                        <th scope="col" colspan="2" class="border px-4 py-1">Tour <?= $i ?></th>
                    <?php else: ?>
                        <th scope="col" colspan="3" class="border px-4 py-1">Tour <?= $i ?></th>
                    <?php endif; ?>
                <?php endfor ; ?>
                <th scope="col" class="border px-4 py-1">Score total</th>
            </tr>
            </thead>
            <tbody>
            <tr>
            <?php for ($i = 1 ; $i <= $game->get_current_round()-1 ; $i++): ?>


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
                <td rowspan="2" colspan="2"
                    class="text-center text-xl border px-4 py-1"><?= ($total_score = $game->total_score_for_player($player)) === null ? "-" : $total_score; ?></td>
            </tr>
            <tr>
                <?php for ($i = 1 ; $i <= $game->get_current_round()-1 ; $i++): ?>
                    <!-- Affichage du score du round -->
                    <?php if ($i !== $game->get_rounds()): ?>
                        <td colspan="2"
                            class="text-center border px-4 py-1"><?= ($v = $game->calculate_score_in_round_for_player($player, $i)) === null ? "-" : $v; ?></td>
                    <?php else: ?>
                        <td colspan="3"
                            class="text-center border px-4 py-1"><?= ($v = $game->calculate_score_in_round_for_player($player, $i)) === null ? "-" : $v; ?></td>
                    <?php endif; ?>
                <?php endfor ; ?>
            </tr>
            </tbody>
        </table>
    <?php endforeach; ?>
    <div class="flex justify-center mt-6">
        <?php
        if (sizeof($game->calculate_winner()) === 1) : ?>
            <h2 class="text-2xl text-center font-semibold">Le gagnant est <?= htmlspecialchars($game->calculate_winner()[0]->name) ?></h2>
        <?php else : ?>
            <h2 class="text-2xl text-center font-semibold">Les gagnants sont :</h2>
            <?php foreach ($game->calculate_winner() as $winner) : ?>
                <h3 class="text-2xl text-center font-semibold"><?= htmlspecialchars($winner->name) ?></h3>
                <h3 class="text-2xl text-center font-semibold">,</h3>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Add a button as link with tailwind css -->
    </div>
    <div class="flex justify-center mt-6">
        <a href="/controllers/clear.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">Rejouer</a>
    </div>
</main>
</body>
</html>
