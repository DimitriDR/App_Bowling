<?php
require_once dirname(__DIR__) . "/controllers/header.start_session.php";
require_once dirname(__DIR__) . "/models/Game.php";
?>
<pre>
    <?php print_r(unserialize($_SESSION["game"])) ?>
</pre>
