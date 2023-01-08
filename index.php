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

            if(isset($_GET['erreur']))
                    {
                        $err = htmlspecialchars($_GET['erreur']);

                        switch($err)
                        {
                            case 'zero':
                                ?>
                                    <div class="alert alert-danger">
                                        <strong>Erreur</strong> Le nombre de joueurs ne doit pas être égale à 0 !
                                    </div>
                                <?php
                                break;

                            case 'not_number':
                                ?>
                                    <div class="alert alert-danger">
                                        <strong>Erreur</strong> Le nombre de joueurs doit être un nombre entier!
                                    </div>
                                <?php
                                break;
                        }
                    }
        ?>
            <form action="indextraitement.php" method="POST">
                <h2 class="text-center">Créer une partie</h2>       
                <div class="form-group">
                    <input type="text" name="playersnumber" class="form-control" placeholder="Indiquez le nombre de joueurs" required="required" autocomplete="off">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-warning btn-block" name="ChoiceName">Choisir le nom des joueurs</button>
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