<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link href="prof_liste_etudiants.css" rel="stylesheet">
        <title>Liste des étudiants</title>
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
                      <a class="nav-link" href="accueil.html">Déconnexion</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="prof_ajouter_modifier.php">Ajouter les notes d'un DS ou les modifier</a>
                </ul>
                <span class="navbar-text ms-auto mb-2 mb-lg-0">
                <?php
                      session_start();
                      $id_prof = $_SESSION['id_prof'];
                      $prof_infos = dbGetNameSurnameProfById($db,$id_prof);
                      $prof_matiere = dbGetMatiereByProfId($db,$id_prof);
                      $_SESSION['prof_matiere'] = $prof_matiere['matiere'];
                      $prof_nom = $prof_infos['prof_name'];
                      echo $prof_nom;
                      echo " ";
                      $prof_prenom = $prof_infos['prof_surname'];
                      echo $prof_prenom;
                    ?>
                </span>
            </div>
      </nav>
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
            <button type="submit" name="valider" class="btn btn-primary" style="width:130px">Valider</button>
              <?php
                if(isset($_POST['valider']) && isset($_POST['classe'])) { 
                    $_SESSION['classe'] = $_POST['classe'];
                    echo"<select id='case' name='semestre'>";
                    echo"<option selected>Semestre :</option>";
                    $annee_uni = dbGetAnneUniByClasse($db,$_POST['classe']);
                    $semestres = dbGetSemestreByAnneUni($db,$annee_uni['annee_uni']);
                    foreach($semestres as $key => $values){
                        echo "<option value=".$values['semestre'].">".$values['semestre']."</option>";
                    }
                    echo "</select>";
                    echo "<button type='submit' name='afficher' class='btn btn-primary' style='width:130px'>Afficher</button>";
                  } 
                ?>                            
</div>

</div>

<table class="table">
              <thead>
                <tr>
                  <th scope="col">ID</th>
                  <th scope="col">Nom</th>
                  <th scope="col">Prénom</th>
                  <th scope="col">Email</th>
                  <th scope="col">Cycle</th>
                  <th scope="col">Notes</th>
                  <th scope="col">Appréciation</th>
                  <th scope="col">Classement</th>
                  <th scope="col">Moyenne</th>
                  <th scope="col">Modifier</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if (isset($_POST['afficher']) && isset($_SESSION['classe']) && isset($_POST['semestre'])){
                  $classe = $_SESSION['classe'];
                  $matiere = $_SESSION['prof_matiere'];
                  $annee_uni = dbGetAnneUniByClasse($db,$classe);
                  $id_semestre = dbGetIdSemestreBySemetre($db,$_POST['semestre'], $annee_uni['annee_uni']);
                  //print_r($id_semestre);
                  $etudiants = dbGetEtudiantByClasse($db,$classe, $matiere, $id_semestre['semestre_id']);
                  foreach($etudiants as $key => $values){
                    echo "<tr>";
                    echo "<td>".$values['eleve_id']."</td>";
                    echo "<td>".$values['eleve_name']."</td>";
                    echo "<td>".$values['eleve_surname']."</td>";
                    echo "<td>".$values['eleve_email']."</td>";
                    echo "<td>".$values['classe']."</td>";
                    echo "<td>".$values['note']."</td>";
                    echo "</tr>";
                  }
                }
                  
                 
                ?>
              </tbody>
        </table>
              </form>
</body>

</html>