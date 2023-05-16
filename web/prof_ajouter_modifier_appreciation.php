<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link href="prof_ajouter_modifier.css" rel="stylesheet">
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
                      <a class="nav-link" href="prof_liste_etudiants.php">Accéder à la liste des étudiants</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="prof_ajouter_modifier.php">Ajouter les notes d'un DS ou les modifier</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="prof_ajouter_modifier_appreciation.php">Modifier l'appréciation du semestre</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="accueil.html">Déconnexion</a>
                  </li>
                </ul>
                <span class="navbar-text ms-auto mb-2 mb-lg-0">
                    <?php
                        session_start();
                        $id_prof = $_SESSION['id_prof'];
                        $prof_infos = dbGetNameSurnameProfById($db,$id_prof);
                        $prof_matiere = dbGetMatiereByProfId($db,$id_prof);
                        $_SESSION['prof_matiere'] = $prof_matiere;
                        $prof_nom = $prof_infos['prof_name'];
                        echo $prof_nom;
                        $prof_prenom = $prof_infos['prof_surname'];
                        echo $prof_prenom;
                    ?>
                </span>
            </div>
      </nav>
</header>


<div class="box" id="box1">
    
    
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
    <select id = "case" name = "classe">
        Classe:
        <?php
            $classes = dbGetClasse($db);
            foreach($classes as $key => $values){
                echo "<option value=".$values['classe'].">".$values['classe']."</option>";
            }
        ?>
    </select>
    <input class='btn btn-secondary' type='submit' name='rechercher_classe' value='Rechercher'/>

    <?php
        if(isset($_POST['rechercher_classe']) && isset($id_prof) && isset($_POST['classe'])){
            $classe = $_POST['classe'];
            $_SESSION['classe'] = $classe;
            $annee_uni = dbGetAnneUniByClasse($db,$classe);
            $_SESSION['annee_uni'] = $annee_uni['annee_uni'];
            $semestres = dbGetSemestreByAnneUni($db,$annee_uni['annee_uni']); 
            echo "<select id='case' name='semestre'>";
            echo "Semestre :";
            foreach($semestres as $key =>$values){
                echo "<option value=".$values['semestre'].">".$values['semestre']."</option>";
            }
            echo "<input class='btn btn-secondary' type='submit' name='rechercher_sem' value='Rechercher'/>";      
        }

        if(isset($_POST['rechercher_sem']) && isset($_POST['semestre'])){
            $semestre = $_POST['semestre'];
            $_SESSION['semestre'] = $semestre;
            $semestre_id = dbGetIdSemestreBySemetre($db,$semestre,$_SESSION['annee_uni']);
            $prof_matiere = $_SESSION['prof_matiere'];
            $classe = $_SESSION['classe'];
            
            //Recup les DS sans notes modifier dbGetDsByClasseSemMat
            $infosDs = dbGetDsByClasseSemMat($db,$classe,$semestre_id['semestre_id'],$prof_matiere['matiere']);

            echo "<table class='table table-striped'>";
            echo "<thead> <tr> <th>Id Ds</th><th>Nom DS</th><th>Date</th><th>Ajouter</th> </tr> </thead>";
            echo "<tbody>";
            foreach($infosDs as $key => $values){
                echo "<tr> 
                <td>".$values['ds_id']."</td><td>".$values['name']."</td><td>".$values['date']."</td>
                <td><input type='radio' name='selectDs' value=".$values['ds_id']."></td>
                </tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "<input class='btn btn-secondary' type='submit' name='ajouter' value='Ajouter une appréciation'/>";      
        }


        if(isset($_POST['ajouter']) && isset($_POST['selectDs'])){
            $_SESSION['ds_id']= $_POST['selectDs'];
            $listeEleves = dbGetNameSurnameEleveByClasse($db,$_SESSION['classe']);
            //print_r($listeEleves);

            echo "<table class='table table-striped'>";
            echo "<thead> <tr> <th>Id Eleve</th><th>Nom</th><th>Prénom</th><th>Appréciation</th> </tr> </thead>";
            echo "<tbody>";
            foreach($listeEleves as $key => $values){
                $getAppreciation = dbGetAppreciationByEleveIdDsIdAndMatiere($db, $values['eleve_id'], $prof_matiere['matiere']);
                if ($getAppreciation == null){
                    $getAppreciation['appreciation'] = NULL;
                }
                echo "<tr> <td>".$values['eleve_id']."</td><td>".$values['eleve_name']."</td><td>".$values['eleve_surname']."</td>
                <td><input type='text' name='selectEleve[".$values['eleve_id']."]' value=".$getAppreciation['appreciation']."></td> </tr>";
            }
            print_r($getAppreciation);
            echo "</tbody>";
            echo "</table>";
            echo "<input class='btn btn-secondary' type='submit' name='valeurs' value='Rentrer les appréciations'/>";      
        }

        if (isset($_POST['valeurs']) && isset($_POST["selectEleve"])){
            $listeEleves = $_POST["selectEleve"];
        
            foreach ($listeEleves as $eleve_id => $appreciation) {
                if ($appreciation != NULL || $appreciation != "") {
                    $var = dbInsertAppreciation($db,$appreciation,$eleve_id, $prof_matiere['matiere']);
                    if($var==false){
                        dbUpdateAppreciation($db,$appreciation,$eleve_id, $prof_matiere['matiere']);
                    }  
                }
            }
        }

    ?>

    </form>

</div>


</div>
</body>

</html>