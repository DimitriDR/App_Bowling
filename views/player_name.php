<?php
require_once dirname(__DIR__) . "/controllers/header.start_session.php";

if(!isset($_SESSION["player_number"])) {
    $_SESSION["error_message"] = "Vous avez déjà commencé une partie.";
    header("Location: /play.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bowlin' Time</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
</head>
<body>
<main class="container mx-auto border rounded-md px-10 py-5 mt-6">
    <?php require_once "display_errors.php"; ?>
    <h1 class="text-4xl text-center font-semibold">Saisir le nom des joueurs d'une partie</h1>
        <form method="POST" action="/controllers/player_name.php" class="grid grid-cols-1 gap-4 content-center mx-auto">
            <input class="mt-4 mx-auto w-80 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" name="player_name_0" placeholder="Saisir le nom du joueur 1" required autofocus>
            <?php
            // Affichage des champs supplémentaires si NB joueurs > 1
            for ($i = 1 ; $i < $_SESSION["player_number"] ; $i++): ?>
            <input class="mt-0 mx-auto w-80 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" name="player_name_<?= $i; ?>" placeholder="Saisir le nom du joueur <?= $i+1; ?>" required>
            <?php endfor; ?>
            <button class="text-white w-80 ml-auto mr-auto bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-2"  name="submit" id="submit" type="submit">
                Commencer !
            </button>
        </form>
</main>
</body>
</html>
