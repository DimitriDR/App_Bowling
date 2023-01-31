<?php
require_once dirname(__DIR__) . "/controllers/header.start_session.php"; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"  content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bowlin' Time</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
</head>
<body>
<main class="container mx-auto border rounded-md px-10 py-5 mt-6">
    <?php require_once "display_errors.php"; ?>
    <h1 class="text-4xl text-center font-semibold">Création d'une partie</h1>
        <form method="POST" action="/controllers/index.php" class="grid grid-cols-1 gap-4 content-center mx-auto">
            <input class="mt-4 mx-auto w-80 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" name="player_number" id="player_number" placeholder="Saisir le nombre de joueurs" min="1" max="20" required autofocus>
            <input class="mt-4 mx-auto w-80 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" name="pin_number" id="pin_number" placeholder="Saisir le nombre de quilles" min="1"  required autofocus>
            <input class="mt-2 mx-auto w-80 block rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" name="rounds" id="rounds" placeholder="Saisir le nombre de tours" min="1" required>
            <button class="text-white w-80 ml-auto mr-auto bg-gradient-to-br from-purple-600 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center mb-2"  name="submit" id="submit" type="submit">
                Passer à la saisie du nom des joueurs
            </button>
        </form>
</main>
</body>
</html>
