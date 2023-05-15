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
                      <a class="nav-link" href="accueil.html">Déconnexion</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="prof_liste_etudiants.php">Accéder à la liste des étudiants</a>
                  </li>
                </ul>
                <span class="navbar-text ms-auto mb-2 mb-lg-0">
                    <?php
                      session_start();
                      $id_prof = $_SESSION['id_prof'];
                      $prof_infos = dbGetNameSurnameProfById($db,$id_prof);
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
    <select id = "case" name = "classe">
        <option selected> Classe: </option>
        <?php
          $classes = dbGetClasse($db);
          foreach($classes as $key => $values){
            echo "<option value='".$values['classe']."'>".$values['classe']."</option>";
        }
        ?>
      </select>

        </form>

</div>


</div>
</body>

</html>