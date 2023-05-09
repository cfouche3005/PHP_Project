<DOCTYPE hmtl>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="connexion.css"/>
</head>

<body>

    <div id="layout">
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="accueil.html">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="connexion_eleve.php">Elève</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="connexion_prof.php">Professeur</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="connexion_admin.php">Administration</a>
                        </li>
                    </ul>
                </div>
        </nav>
        <br>
        <h2 style="text-align: center; padding-top: 25px;">Connexion professeur : </h2>
    </header>

    <?php
        //require('../lib/fonction.php');  
        //ini_set('display_errors', 1);
        //error_reporting(E_ALL);
        //$db = dbConnect();
    ?>

    <div class="box" id="box1">
    </div>

    <div class="box" id="box2">
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <p> Mail : </p>
            <input type="text" name="mail"/>
            <br>
            <p> Mot de passe : </p>
            <input type="password" name="mp"/>
            <br>
            <input type="submit" value="Se connecter"/>
        </form>
    </div>

    <div class="box" id="box3">
    </div>

    <?php
        //Récupération connexion
        if(!empty($_POST['mail']) && !empty($_POST['mp'])){

            $mail = ($_POST["mail"]);
            $mp_n_crypt = ($_POST["mp"]);
            
            if(dbCheckMailMpProf($db,$mail,$mp_n_crypt)){
                //Envoie vers la page prof
                //echo '<meta http-equiv="refresh" content="0;url=?">';
                echo 'Connexion réussie';
            }
            else{
                echo 'Erreur de connexion';
            }
        }
    ?>

</div>

</body>

</html>