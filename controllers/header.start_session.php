<?php
// On démarre une session, sauf si une est déjà ouverte
if (!isset($_SESSION))
{
    session_start();
}