<!DOCTYPE html>
<html lang="fr">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="page_classique.css" rel="stylesheet">
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
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="admin_ajouter_compte.php">Ajouter compte</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_ajouter_ds.php">Ajouter DS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_ajouter_ann_promo_mat.php">Ajouter année-universitaire, classe, semestre, matière</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_modifier_suppr_compte.php">Modifier / Supprimer un compte</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_modifier_suppr_ds.php">Modifier / Supprimer DS</a>
                    </li>
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
                        $id_admin = $_SESSION['id_admin'];
                        $admin_infos = dbGetNameSurnameAdminById($db,$id_admin);
                        $admin_nom = $admin_infos['admin_name'];
                        echo $admin_nom;
                        echo "<br>";
                        $admin_prenom = $admin_infos['admin_surname'];
                        echo $admin_prenom;
                    ?>
                </span>
            </div>
        </nav>
        <br>
        <h2 style="text-align: center; padding-top: 5px;">Formulaire de création de DS :</h2>
    </header>

    <div class="box" id="box1">
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <select id="case"  name="classe">
                <option selected>Classe :</option>
                <?php
                    $classes = dbGetClasse($db);
                    foreach($classes as $key => $values){
                        echo "<option value=".$values['classe'].">".$values['classe']."</option>";
                    }
                ?>
            </select>
            <br>
            <input id="case" type="text" placeholder="Nom du DS (ex: DS1)" name="num_ds"/>
            <br>
            <input id="case" type="date" name="date"/>
            <br> 
    </div>

    <div class="box" id="box2">
            <select id="case" name="matiere">
                <option selected>Matière :</option>
                <?php
                    $matieres = dbGetMatiere($db);
                    foreach($matieres as $key => $values){
                        echo "<option value=".$values['matiere'].">".$values['matiere']."</option>";
                    }
                ?>            
            </select>                   
            <br>
            <select id="case" name="semestre">
                <option selected>Semestre :</option>
                <?php
                    $semestres = dbGetSemestre($db);
                    foreach($semestres as $key => $values){
                        echo "<option value=".$values['semestre'].">".$values['semestre']."</option>";
                    }
                ?> 
            </select>
            <br>
            <input id="case" type="time" min="08:00" max="18:00" name="heure"/>
            <br>            
            <input class="btn btn-secondary" type="submit" value="Enregistrer le DS"/>
            <br>

        </form>
    </div>

    <footer>
    </footer>

    <?php
        //Vérification entrées de toutes les infos
        if(!empty($_POST['classe'])&&!empty($_POST['num_ds'])&&!empty($_POST['date'])&&!empty($_POST['matiere'])&&!empty($_POST['semestre'])&&!empty($_POST['heure'])){
                
            //Récupération des données
            $classe = $_POST['classe'];
            $num_ds = $_POST['num_ds'];                
            $matiere = $_POST["matiere"];
            $semestre = $_POST["semestre"];
            $date = $_POST["date"];
            $heure = $_POST["heure"];
            dbInsertDS($db,$classe,$num_ds,$matiere,$semestre,$heure,$date);
        }
    ?>

</div>


</body>
</html>