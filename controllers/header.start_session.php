<?php
// On démarre une session, sauf si une est déjà ouverte
if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}