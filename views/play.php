 <?php
require_once dirname(__DIR__) . "/controllers/header.start_session.php";
require_once dirname(__DIR__) . "/models/Game.php";
require_once dirname(__DIR__) . "/models/Player.php";
$game = unserialize($_SESSION["game"]);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bowlin' Time</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
</head>
<body>
<main class="container mx-auto border rounded-md px-10 py-5 mt-6">
    <?php require_once "display_errors.php"; ?>
    <h1 class="text-4xl text-center font-semibold">Jouons !</h1>
    <h2 class="text-4xl text-center font-light">Tour <?= $game->get_current_round(); ?></h2>
        <form method="POST" action="/controllers/player_name.php" class="grid grid-cols-1 gap-4 content-center mx-auto">
            <table class="border border-slate-400">
                <thead>
                    <tr>
                        <th class="border border-slate-300">Nom du joueur</th>
                        <th class="border border-slate-300">Lancer 1</th>
                        <th class="border border-slate-300">Lancer 2</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0 ; $i < $game->get_player_number() ; $i++): ?>
                    <tr>
                        <td class="border border-slate-300 text-center"><?= $game->get_player_at($i)->name; ?></td>
                        <td class="border border-slate-300">X</td>
                        <td class="border border-slate-300">X</td>
                    </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
            <input class="mt-4 mx-auto w-80 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" name="playersnumber" id="playersnumber" placeholder="Saisie du lancer" min="1" max="10" required>
            <button class="text-white w-80 ml-auto mr-auto bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-2"  name="submit" id="submit" type="submit">
                Valider
            </button>
        </form>
</main>
</body>
</html>
