<?php
    include "classes/Game.php";
    include "classes/Player.php";
    session_start();


    echo '<h1>Tour n° 1</h1>'; //a changer en fonction du tour

    echo unserialize($_SESSION['game'])->get_current_player_name();

    //Récupération du score de la page page_score.php et le stocker en int dans la variable $score
    $score = htmlspecialchars($_POST['score']);

    //Vérification que le score est bien un nombre
    if(ctype_digit($score) == false)
    {
        header('Location: page_score.php?error=scoreentier');
    }else{
        $score = (int)$score;
        if($score < 0)
        {
            header('Location: page_score.php?error=scoreinf');
        }
        //Vérification que le score est bien inférieur à 10
        elseif($score > 10)
        {
            header('Location: page_score.php?error=scoreeleve');
        }
        //Vérification que la somme des deux lancers ne dépasse pas 10
        elseif($score + $score > 10)
        {
            header('Location: page_score.php?error=scoremax');
        }
        else{
            echo 'ok';
            
        }
    }
?>

