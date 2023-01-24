<?php require_once dirname(__DIR__) . "/controllers/header.start_session.php"; ?>
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
        <form method="POST" action="/controllers/player_name.php" class="grid grid-cols-1 gap-4 content-center mx-auto">
            <?php for($i = 0 ; $i < $_SESSION["game_data"]["player_number"] ; $i++): ?>

            <?php endfor; ?>
            echo
        </form>
</main>
</body>
</html>
