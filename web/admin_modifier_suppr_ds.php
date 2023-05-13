<!DOCTYPE html>
<html lang="fr">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="page_classique.css" rel="stylesheet">
    <meta charset="utf-8">
</head>

<body>
<div>
        
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
        <h2 style="text-align: center; padding-top: 5px;">Liste des DS enregistrés :</h2>
    </header>

    <div id="tableau">
    <style> table, td, th{border:1px solid}; </style>

        <p>Choisir une classe :</p>
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <select id="case" name="classe">
                <?php
                    $classes = dbGetClasse($db);
                    foreach($classes as $key => $values){
                        echo "<option value='".$values['classe']."'>".$values['classe']."</option>";
                    }
                ?>
            </select>
            <input class="btn btn-secondary" type="submit" name="rechercher" value="Rechercher"/>

        <?php

        if(isset($_POST['rechercher'])){
            if(!empty($_POST['classe'])){
                $classe = $_POST['classe'];
                $DsInfo = dbGetDsInfo($db,$classe);

                echo "<table class='table table-striped'>";
                echo "<thead> <tr> <th>Matière</th><th>Nom DS</th><th>Professeur</th><th>Semestre</th><th>Date</th><th>Heure</th><th>Modifier/Supprimer</th> </tr> </thead>";
                echo "<tbody>";
                foreach($DsInfo as $key => $values){
                    echo "<tr> 
                    <td>".$values['matiere']."</td><td>".$values['name']."</td><td>".$values['prof_name']."</td><td>".$values['semestre']."</td><td>".$values['date']."</td><td>".$values['heure']."</td>
                    <td><input type='radio' name='modifier_suppr' value=".$values['ds_id']."></td>
                    </tr>";
                }
                echo "</tbody>";
                echo "</table>";
                echo "<input class='btn btn-secondary' type='submit' name='modifier' value='Modifier le DS sélectionné'/>";
                echo "<br>";
                echo "<br>";
                echo "<input class='btn btn-secondary' type='submit' name='supprimer' value='Supprimer le DS sélectionné'/>";
            }
        }

        if(isset($_POST['modifier'])){
            $id_ds = $_POST['modifier_suppr']; 
            $_SESSION['id_ds'] = $id_ds;
            echo "<br>";
            echo "Que voulez-vous modifier ?";
            echo "<br>";
            echo "<select id='case' name='selectAtt'>";
            $attributsDs = dbGetDs($db);
            foreach($attributsDs as $key => $values){
                echo "<option value=".$key.">".$key."</option>";
            }
            echo "<option value='prof_name'>prof</option>";
            echo "</select>";
            echo "<br>";
            echo "Entrer la nouvelle donnée pour le DS :";
            echo "<br>";
            echo "<input id='case' type='text' name='new_info'/>";
            echo "<input class='btn btn-secondary' type='submit' name='choix' value='Modifier'/>";
        }

        if(isset($_POST['choix']) && isset($_SESSION['id_ds'])){
            $attribut = $_POST['selectAtt'];
            $new_val = $_POST['new_info'];
            if($attribut == 'date'){
                dbModifierDsDate($db,$new_val,$_SESSION['id_ds']);
            }
            elseif($attribut == 'heure'){
                dbModifierDsHeure($db,$new_val,$_SESSION['id_ds']);
            }
            elseif($attribut == 'name'){
                dbModifierDsName($db,$new_val,$_SESSION['id_ds']);
            }
            elseif($attribut == 'matiere'){
                dbModifierDsMatiere($db,$new_val,$_SESSION['id_ds']);
            }
            elseif($attribut == 'semestre'){
                dbModifierDsSemestre($db,$new_val,$_SESSION['id_ds']);
            }
            elseif($attribut == 'prof'){
                dbModifierDsClasse($db,$new_val,$_SESSION['id_ds']);
            }
            echo "<br>";
            echo "Le DS numéro ".$_SESSION['id_ds']." (id) a été modifié !";
        }

        if(isset($_POST['supprimer'])){
            $id_ds = $_POST['modifier_suppr']; 
            $_SESSION['id_ds'] = $id_ds;
            dbSupprDs($db,$_SESSION['id_ds']);
            echo "<br>";
            echo "Le DS numéro ".$id_ds." (id) a été supprimé !";
        }

        ?>
        </form>
    </div>

    <footer>
    </footer>

</div>
</body>
</html>