<?php
require_once dirname(__DIR__) . "/controllers/header.start_session.php";

if (isset($_SESSION["error_message"])):
?>
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
    <strong class="font-bold">Erreur.</strong>
    <span class="block sm:inline"><?= $_SESSION["error_message"]; ?></span>
    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
    </span>
</div>
<?php
unset($_SESSION["error_message"]);
endif;
