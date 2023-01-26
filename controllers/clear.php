<?php
require_once "header.start_session.php";

session_destroy();

header("Location: /");
