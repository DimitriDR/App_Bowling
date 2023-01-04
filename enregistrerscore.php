<?php
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
    }
?>

