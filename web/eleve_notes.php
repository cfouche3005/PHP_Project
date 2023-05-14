<DOCTYPE hmtl>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="page_classique.css"/>
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
            Quel semestre ?    
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
            if(isset($_POST['rechercher']) && isset($_POST['semestre'])){
                $semestre = $_POST['semestre'];
                $classe = dbGetClasseEleveById($db,$id_eleve['eleve_id']);
                $annee_uni = dbGetAnneUniByClasse($db,$classe['classe']);
                $semestre_id = dbGetIdSemestreBySemetre($db,$semestre,$annee_uni['annee_uni']);
                $listeMatieres = dbGetMatiereByClasseSemestre($db,$classe['classe'],$semestre_id['semestre_id']);
                $nbrNotesTotal = 0;
                foreach($listeMatieres as $key => $values){
                    $nbrNotes = dbCountNoteByEleveMatSem($db,$id_eleve['eleve_id'],$values['matiere'],$semestre,$classe['classe']);
                    $nbrNotesTotal += $nbrNotes['count'];
                }
                
                if($nbrNotesTotal != 0){
                    $moyGenerale = dbGetMoyGenerale($db,$id_eleve['eleve_id'],$semestre,$nbrNotesTotal,$listeMatieres,$classe['classe']);
                    $nbrEleves = dbCountEleveByClasse($db, $classe['classe']);
                    $rangGeneral = dbGetRangGeneral($db,$id_eleve['eleve_id'],$classe['classe'],$semestre,$nbrNotes['count'],$moyGenerale,$listeMatieres);

                    echo "<table style='text-align: center' class='table table-striped table-bordered'>";
                    echo "<br>";
                    echo "<thead> <tr> <th>Moyenne générale éleve</th><th>Rang général</th></tr> </thead>";
                    echo "<tbody>";
                    echo "<tr> <td>".round($moyGenerale,1)."</td><td>".$rangGeneral."/".$nbrEleves['count']."</td> </tr>";
                    echo "</tbody>";
                    echo "</table>";
                    echo "<br>";
    
                    foreach($listeMatieres as $key => $values){
                        $listeIdDs = dbGetIdDsByClasseSemestreMatiere($db,$classe['classe'],$semestre,$values['matiere']);
                        $nbrNotes = dbCountNoteByEleveMatSem($db,$id_eleve['eleve_id'],$values['matiere'],$semestre,$classe['classe']);
                        $moy = dbCalculerMoyMatiere($db,$id_eleve['eleve_id'],$values['matiere'],$semestre,$nbrNotes['count'],$classe['classe']);
                        $nbrEleves = dbCountEleveByClasse($db, $classe['classe']);
                        $moyClasse = dbCalculerMoyenneClasse($db,$classe['classe'],$values['matiere'],$semestre,$nbrNotes['count'],$nbrEleves['count']);
                        if($moy < 10){
                            $rattrapage = 'OUI';
                        }
                        else{
                            $rattrapage = 'NON';
                        }
                        $rang = dbGetRang($db,$classe['classe'],$values['matiere'],$semestre,$nbrNotes['count'],round($moy,1));
                        $appreciation = dbGetAppreciation($db,$semestre,$values['matiere'],$id_eleve['eleve_id'],$classe['classe']);

                        echo "<table style='text-align: center' class='table table-striped table-bordered'>";
                        echo "<br>";
                        echo "<h4> ".$values['matiere']." :</h4>";
                        echo "<thead> <tr> <th>Libellé</th><th>Note</th><th>Moyenne éleve</th><th>Moyenne classe</th><th>Rang</th><th>Rattrapage</th><th>Appréciation</th> </tr> </thead>";
                        echo "<tbody>";
                        echo "<tr> <td></td><td></td><td>".round($moy,1)."</td><td>".round($moyClasse,1)."</td><td>".$rang."/".$nbrEleves['count']."</td><td>".$rattrapage."</td><td>".$appreciation['appreciation']."</td> </tr>";
    
                        foreach($listeIdDs as $cle => $valIdDs){
                            $infosDsEleve = dbGetInfoNotesEleve($db,$valIdDs['ds_id'],$classe['classe'],$semestre,$values['matiere'],$id_eleve['eleve_id']);
    
                            foreach($infosDsEleve as $cle =>$valDs){
                                 
                                echo "<tr> 
                                <td>".$valDs['name']."</td><td>".$valDs['note']."</td><td></td><td></td><td></td><td></td><td></td>
                                </tr>";
                            }
                        }
                        echo "</tbody>";
                        echo "</table>";
                    }
                }
                else{
                    echo "<h4 style='text-align:center'>Vous n'avez pas de notes pour ce semestre !</h4>";
                }
            }
        ?>
        </form>
    </div>

    <footer>
    </footer>

</div>
</body>