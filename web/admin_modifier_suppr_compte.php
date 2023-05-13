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
                        <a class="nav-link" href="admin_ajouter_ann_promo_mat.php">Ajouter année-universitaire, promo, classe,semestre,  matière</a>
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
        <h2 style="text-align: center; padding-top: 5px;">Modifier ou supprimer un compte :</h2>
    </header>

    <div id="tableau">
        <style> table, td, th{border:1px solid}; </style>

        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            Choisir un statut :
            <select id="case" name="statut">
                <option value="eleve">Eleve</option>
                <option value="prof">Professeur</option>
            </select>
            <br>
            Si compte élève :
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
           
            if(isset($_POST['rechercher']) && isset($_POST['statut'])){
                $statut = $_POST['statut'];
                $_SESSION['statut'] = $statut; 
                if($statut == 'eleve'){
                    $classe = $_POST['classe'];
                    $compteInfosEleve = dbGetCompteInfoEleve($db,$classe);

                    echo "<table class='table table-striped'>";
                    echo "<thead> <tr> <th>Nom</th><th>Prénom</th><th>Email</th><th>Téléphone</th><th>Classe</th><th>Promo</th><th>Modifier/Supprimer</th> </tr> </thead>";
                    echo "<tbody>";
                    foreach($compteInfosEleve as $key => $values){
                        echo "<tr> 
                        <td>".$values['eleve_name']."</td><td>".$values['eleve_surname']."</td><td>".$values['eleve_email']."</td><td>".$values['eleve_phone']."</td><td>".$values['classe']."</td><td>".$values['promo']."</td>
                        <td><input type='radio' name='modifier_suppr' value=".$values['eleve_id']."></td>
                        </tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    echo "<input class='btn btn-secondary' type='submit' name='modifier_eleve' value='Modifier le compte sélectionné'/>";
                    echo "<br>";
                    echo "<br>";
                    echo "<input class='btn btn-secondary' type='submit' name='supprimer_eleve' value='Supprimer le compte sélectionné'/>";
                }
                elseif($statut == 'prof'){
                    $compteInfosProf = dbGetCompteInfoProf($db);
                    echo "<table class='table table-striped'>";
                    echo "<thead> <tr> <th>Nom</th><th>Prénom</th><th>Email</th><th>Téléphone</th><th>Matière</th><th>Modifier/Supprimer</th> </tr> </thead>";
                    echo "<tbody>";
                    foreach($compteInfosProf as $key => $values){
                        echo "<tr> 
                        <td>".$values['prof_name']."</td><td>".$values['prof_surname']."</td><td>".$values['prof_email']."</td><td>".$values['prof_phone']."</td><td>".$values['matiere']."</td>
                        <td><input type='radio' name='modifier_suppr' value=".$values['prof_id']."></td>
                        </tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";
                    echo "<input class='btn btn-secondary' type='submit' name='modifier_prof' value='Modifier le compte sélectionné'/>";
                    echo "<br>";
                    echo "<br>";
                    echo "<input class='btn btn-secondary' type='submit' name='supprimer_prof' value='Supprimer le compte sélectionné'/>";
                }
            }
                
            if(isset($_POST['modifier_eleve']) && isset($_SESSION['statut'])){
                $id_compte_eleve = $_POST['modifier_suppr']; 
                $_SESSION['id_compte_eleve'] = $id_compte_eleve;
                echo "<br>";
                echo "Que voulez-vous modifier ?";
                echo "<br>";
                echo "<select id='case' name='selectAttEleve'>";
                $attributs_compte_eleve = dbGetCompteEleve($db);
                foreach($attributs_compte_eleve as $key => $values){
                    echo "<option value=".$key.">".$key."</option>";
                }
                echo "</select>";
                echo "<br>";
                echo "Entrer la nouvelle donnée pour le compte :";
                echo "<br>";
                echo "<input id='case' type='text' name='newInfoEleve'/>";
                echo "<input class='btn btn-secondary' type='submit' name='choix_eleve' value='Modifier'/>";
            }
            
            if(isset($_POST['modifier_prof']) && isset($_SESSION['statut'])){
                $id_compte_prof = $_POST['modifier_suppr']; 
                $_SESSION['id_compte_prof'] = $id_compte_prof;
                echo "<br>";
                echo "Que voulez-vous modifier ?";
                echo "<br>";
                echo "<select id='case' name='selectAttProf'>";
                $attributs_compte_prof = dbGetCompteProf($db);                    
                foreach($attributs_compte_prof as $key => $values){
                    echo "<option value=".$key.">".$key."</option>";
                }
                echo "</select>";
                echo "<br>";
                echo "Entrer la nouvelle donnée pour le compte :";
                echo "<br>";
                echo "<input id='case' type='text' name='newInfoProf'/>";
                echo "<input class='btn btn-secondary' type='submit' name='choix_prof' value='Modifier'/>";
            }
            
            if(isset($_POST['choix_eleve']) && isset($_SESSION['id_compte_eleve'])){
                $attribut = $_POST['selectAttEleve'];
                $new_val = $_POST['newInfoEleve'];
                if($attribut == 'eleve_name'){
                    dbModifierCompteEleveName($db,$new_val,$_SESSION['id_compte_eleve']);
                }
                elseif($attribut == 'eleve_surname'){
                    dbModifierCompteEleveSurname($db,$new_val,$_SESSION['id_compte_eleve']);
                }
                elseif($attribut == 'eleve_email'){
                    dbModifierCompteEleveEmail($db,$new_val,$_SESSION['id_compte_eleve']);
                }
                elseif($attribut == 'eleve_phone'){
                    dbModifierCompteElevePhone($db,$new_val,$_SESSION['id_compte_eleve']);
                }
                elseif($attribut == 'classe'){
                    dbModifierCompteEleveClasse($db,$new_val,$_SESSION['id_compte_eleve']);
                }
                elseif($attribut == 'promo'){
                    dbModifierCompteElevePromo($db,$new_val,$_SESSION['id_compte_eleve']);
                }
            }

            if(isset($_POST['choix_prof']) && isset($_SESSION['id_compte_prof'])){
                $attribut = $_POST['selectAttProf'];
                $new_val = $_POST['newInfoProf'];
                if($attribut == 'prof_name'){
                    dbModifierCompteProfName($db,$new_val,$_SESSION['id_compte_prof']);
                }
                elseif($attribut == 'prof_surname'){
                    dbModifierCompteProfSurname($db,$new_val,$_SESSION['id_compte_prof']);
                }
                elseif($attribut == 'prof_email'){
                    dbModifierCompteProfEmail($db,$new_val,$_SESSION['id_compte_prof']);
                }
                elseif($attribut == 'prof_phone'){
                    dbModifierCompteProfPhone($db,$new_val,$_SESSION['id_compte_prof']);
                }
                elseif($attribut == 'matiere'){
                    dbModifierCompteProfSMatiere($db,$new_val,$_SESSION['id_compte_prof']);
                }
            }

            if(isset($_POST['supprimer_eleve'])){
                $id_compte_eleve = $_POST['modifier_suppr']; 
                dbSupprCompteEleve($db,$id_compte_eleve);
                echo "<br>";
                echo "Le compte élève numéro ".$id_compte_eleve." (id) a été supprimé !";
            }

            if(isset($_POST['supprimer_prof'])){
                $id_compte_prof = $_POST['modifier_suppr']; 
                dbSupprCompteProf($db,$id_compte_prof);
                echo "<br>";
                echo "Le compte prof numéro ".$id_compte_prof." (id) a été supprimé !";
            }
        ?>
        </form>

    </div>

    <footer>
    </footer>

</div>
</body>
</html>