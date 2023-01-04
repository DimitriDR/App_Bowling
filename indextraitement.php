
<?php


    if(isset($_GET['playersnumber'])){

        $playersnumber = $_GET['playersnumber'];

        if(ctype_digit($playersnumber) == false)
        {
            header('Location: index.php?erreur=not_number');
        }else{
            $playersnumber = (int)$playersnumber;
        
            if($playersnumber == 0)
            {
                header('Location: index.php?erreur=zero');
            }
            elseif(!is_numeric($playersnumber))
            {
                header('Location: index.php?erreur=not_number');
            }
            else
            {

                for($i = 1; $i <= $playersnumber; $i++)
                {
                    echo '<div class="login-form">';
                    echo '<input type="text" name="player'.$i.'" class="form-control" placeholder="Nom du joueur '.$i.'" required="required" autocomplete="off">';
                    echo '</div>';

                    echo '<style>';
                    echo'.login-form {
                        width: 341px;
                        margin: 51px auto;
                    }
                    .login-form form {
                        margin-bottom: 16px;
                        background: #f7f7f7;
                        box-shadow: 0px 3px 3px rgba(0, 0, 0, 0.3);
                        padding: 31px;
                    }
                    .login-form h2 {
                        margin: 0 0 16px;
                        font-family: Poppins;
        
                }';
                    echo '</style>';
                }
        }
    }
}

?>