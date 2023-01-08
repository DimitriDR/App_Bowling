<!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet" />
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
            <title>Bowling Points</title>
        </head>
        <body>
        
        <div class="login-form">
            <?php
            include "classes/Player.php"; 
            include "classes/Game.php";
            include "indextraitement.php";
            require_once ("indextraitement.php");
            session_start();
            
            $game = unserialize($_SESSION['game']);

                if(isset($_GET['error']))
                {
                    $error = htmlspecialchars($_GET['error']);

                    switch($error)
                    {
                        case 'scoreeleve':
                        ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> Le score ne peut pas être supérieur à 10 !
                            </div>
                        <?php
                        break;
                        case 'scoreentier':
                        ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> Le score doit être un nombre entier positif !
                            </div>
                        <?php
                        break;
                        case 'scoreinf':
                        ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> Le score ne peut pas être inférieur à 0 !
                            </div>
                        <?php
                        break;
                        case 'scoremax':
                        ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> La somme de vos deux lancers ne peut pas excéder 10 !
                            </div>
                        <?php
                        break;
                    }
                }
                elseif(isset($_GET['success']))
                {
                    ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check"></i> Votre score a bien été enregistré !
                        </div>
                    <?php
                    $game->set_current_throw($game->get_current_throw()+1);
                    if($game->get_current_throw() > 2)
                    {
                        $game->set_current_throw(1);
                        if($game->get_current_player() == count($game->get_players()))
                        {
                            $game->set_current_player(1);
                            $game->set_current_round($game->get_current_round()+1);
                        }
                        else
                        {
                            $game->set_current_player($game->get_current_player()+1);
                        }
                    }
                }
                //echo '<h2 class="text-center">Tour n° 1</h2>'; //a changer en fonction du tour

                //ajout des joueurs dans une partie si il n'y a pas d'erreur
                // serialize et lina va unserialize
                // print_r($game); pour afficher le tableau

            ?>
             
            <form action="enregistrerscore.php" method="post">
                <h2 class="text-center">Enregistrez Votre Score</h2>
                <?php
                $joueur = $game->get_current_player_object()->name;
                echo '<div> <label for="nom">Joueur : '.$joueur.'</label></div>';
                    $num_tour=$game->get_current_round();
                    $num_lancer=$game->get_current_throw();
                echo'<label for="tour">Tour n° '.$num_tour.' / Lancer n° '.$num_lancer.'</label>';
                $_SESSION['game'] = serialize($game);
                ?> 
                <div class="form-group">
                    <input type="text" name="score" class="form-control" placeholder="Score" required="required" autocomplete="off">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-warning btn-block" name="ChoiceName">Enregistrer</button>
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
        </style>
        </body>
</html>