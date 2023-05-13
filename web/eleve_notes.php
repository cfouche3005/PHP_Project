<DOCTYPE hmtl>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="page_classique.css"/>
    <meta charset="utf-8">
</head>

<body>
<div id="layout">
    <?php
        require('../lib/fonction.php');  
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        $db = dbConnect();
    ?>

    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul  class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="accueil.html">Déconnexion</a>
                    </li>
                </ul>
                <span class="navbar-text">
                    Connecté sous :
                </span>
                <span class="navbar-text">
                    <?php
                        session_start();
                        $id_eleve = $_SESSION['id_eleve'];
                        $eleve_infos = dbGetNameSurnameEleveById($db,$id_eleve);
                        $eleve_nom = $eleve_infos['eleve_name'];
                        echo $eleve_nom;
                        echo "<br>";
                        $eleve_prenom = $eleve_infos['eleve_surname'];
                        echo $eleve_prenom;
                    ?>
                </span>
            </div>
        </nav>
        <br>
        <h2 style="text-align: center; padding-top: 5px;">Vos notes :</h2>
    </header>

    <div>
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <select id="case" name="semestre">
                <?php
                    $semestres = dbGetSemestre($db);
                    foreach($semestres as $key => $values){
                        echo "<option value='".$values['semestre']."'>".$values['semestre']."</option>";
                    }
                ?>
            </select>
            <input class="btn btn-secondary" type="submit" name="rechercher" value="Rechercher"/>
        
        <?php
            echo $id_eleve['eleve_id'];
            echo "<br>";
            
        ?>

        
        
        </form>
    </div>

    <footer>
    </footer>

</div>
</body>