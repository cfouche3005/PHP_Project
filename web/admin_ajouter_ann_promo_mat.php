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
                        <a class="nav-link" href="admin_ajouter_ann_promo_mat.php">Ajouter année-universitaire, promo, classe, semestre, matière</a>
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
        <h3 style="text-align: center; padding-top: 5px;">Ajouter une année-universitaire, une promo, une classe, un semestre ou une matière :</h3>
    </header>

    <div class="box" id="box1">

        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <p>Ajouter une promo :</p>
            <input id="case" type="text" placeholder="(2020-2026)" name="promo"/>
            <br>
            <p>Ajouter une année universitaire :</p>
            <input id="case" type="text" placeholder="(2020-2021)" name="annee_uni"/>
            <br>
            <p>Ajouter une classe :</p>
            <input id="case" type="text" placeholder="(CIR1)" name="classe"/>
            <br>
    </div>

    <div class="box" id="box2">
            <p>Ajouter un semestre :</p>
            <input id="case" type="text" placeholder="(S1)" name="semestre"/>
            <br>
            <p>Ajouter une matière :</p>
            <input id="case" type="text" placeholder="(Mathématiques)" name="matiere"/>
            <br>
            <br>
            <input class="btn btn-secondary" type="submit" value="Ajouter"/>
        </form>
    </div>

        <?php
            if(!empty($_POST['promo'])){
                $promo = $_POST['promo'];
                $dbpromos = dbGetPromo($db);
                $var = true;
                foreach($dbpromos as $key => $values){
                    if($promo == $values['promo']){
                        $var = false;
                    }
                }
                if($var != false){
                    dbInsertPromo($db,$promo);
                }
                else{
                    echo "La promo existe déjà !";
                }
            }

            if(!empty($_POST['annee_uni'])){
                $annee_uni = $_POST['annee_uni'];
                $dbannees_uni = dbGetAnneeUni($db);
                $var = true;
                foreach($dbannees_uni as $key => $values){
                    if($annee_uni == $values['annee_uni']){
                        $var = false;
                    }
                }
                if($var != false){
                    dbInsertAnneeUni($db,$annee_uni);
                }
                else{
                    echo "L'année universitaire existe déjà !";
                }
            }

            if(!empty($_POST['classe'])){
                $classe = $_POST['classe'];
                $dbclasses = dbGetClasse($db);
                $var = true;
                foreach($dbclasses as $key => $values){
                    if($classe == $values['classe']){
                        $var = false;
                    }
                }
                if($var != false){
                    dbInsertClasse($db,$classe);
                }
                else{
                    echo "La classe existe déjà !";
                }
            }

            if(!empty($_POST['semestre'])){
                $semestre = $_POST['semestre'];

            }

            if(!empty($_POST['matiere'])){
                $matiere = $_POST['matiere'];
                $dbmatieres = dbGetMatiere($db);
                $var = true;
                foreach($dbmatieres as $key => $values){
                    if($matiere == $values['matiere']){
                        $var = false;
                    }
                }
                if($var != false){
                    dbInsertMatiere($db,$matiere);
                }
                else{
                    echo "La matière existe déjà !";
                }
            }            
        ?>

    </div>

    <footer>
    </footer>

</div>
</body>
</html>