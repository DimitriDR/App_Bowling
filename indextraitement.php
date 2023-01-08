
<?php
    session_start();


if(isset($_POST['playersnumber'])){
    $playersnumber = $_POST['playersnumber'];
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
            echo '<div class="login-form">
                <form action="page_score.php" method="POST">
                    <h2 class="text-center">Indiquer le nom des joueurs</h2>';
                    $number=$playersnumber;
                    $_SESSION['number'] = serialize($number);
                    for($i = 1; $i <= $playersnumber; $i++)
                    {
                        echo'<div class="form-group">
                        <input type="text" name="player'.$i.'" class="form-control" placeholder="Nom du joueur '.$i.'" required="required" autocomplete="off"><br>
                        </div>';
                    }
                    echo '<div class="form-group">
                    <button type="submit" class="btn btn-warning btn-block" name="ChoiceName">Commencer la partie</button>
                    </div>
                </form>
             </div>
             <style>
            .login-form {
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
            }
            .form-control, .btn {
                min-height: 39px;
                border-radius: 3px;
            }
            .btn {        
                font-size: 16px;
                font-weight: bold;
            }
            </style>';
           
            
            
        }
    }
}

?>