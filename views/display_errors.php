<?php
require_once dirname(__DIR__) . "/controllers/header.start_session.php";

if (isset($_SESSION["error_message"]))
{
    echo $_SESSION["error_message"];
    unset($_SESSION["error_message"]);
}
