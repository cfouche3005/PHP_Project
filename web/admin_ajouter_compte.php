<!DOCTYPE html>
<html lang="fr">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="page_classique.css" rel="stylesheet">
    <meta charset="utf-8">
</head>

<body>
<div id="layout">

    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                  <li class="nav-item">
                      <a class="nav-link" href="accueil.html">Déconnexion</a>
                  </li>
                </ul>
            </div>
      </nav>
        <br>
        <h2 style="text-align: center; padding-top: 5px;">Formulaire de création de compte :</h2>
    </header>
    
    <?php
        //require('../lib/fonction.php');  
        //ini_set('display_errors', 1);
        //error_reporting(E_ALL);
        //$db = dbConnect();
    ?>


    <div class="box" id="box1">
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <input id="case" type="text" placeholder="Nom" name="nom"/>
            <br>
            <select id="case" name="statut">
                <option value="eleve">Eleve</option>
                <option value="prof">Professeur</option>
            </select>
            <br>
            <input id="case" type="text" placeholder="Email" name="email"/>
            <br>
            <input id="case" type="password" placeholder="Mot de passe" name="mp"/>
            <br> 
            <p>Si ajout d'un compte élève :</p>
            <input id="case" type="text" placeholder="Classe (ex:CIR2,CSI1...)" name="classe">
            <input id="case" type="text" placeholder="Promo (ex:2021-2026)" name="promo">

    </div>

    <div class="box" id="box2">
            <input id="case" type="text" placeholder="Prénom" name="prenom"/>
            <br>
            <input id="case" type="text" placeholder="Téléphone" name="tel"/>
            <br>
            <input id="case" type="text" placeholder="Confirmation email" name="conf_email"/>
            <br>
            <input id="case" type="password" placeholder="Confirmation mot de passe" name="conf_mp"/>
            <br> 
            <p>Si ajout d'un compte professeur :</p>
            <input id="case" type="text" placeholder="Matière" name="matiere">
            <br>
            <br>
            <input type="submit" value="Enregistrer le compte"/>
        </form>
    </div>

    <footer>
    </footer>

    <?php
        //Vérification entrées de toutes les infos
        if(!empty($_POST['nom'])&&!empty($_POST['prenom'])&&!empty($_POST['statut'])&&!empty($_POST['tel'])&&!empty($_POST['email'])&&!empty($_POST['conf_email'])&&!empty($_POST['mp'])&&!empty($_POST['conf_mp'])){
            if($_POST['email']==$_POST['conf_email'] && $_POST['mp']==$_POST['conf_mp']){
                
                //Récupération des données
                $nom = $_POST['nom'];
                $prenom = $_POST['prenom'];
                $statut = $_POST['statut'];
                $tel = $_POST['tel'];
                $email = $_POST["email"];
                $mp_n_crypt = $_POST["mp"];
                $mp_crypt = password_hash($mp_n_crypt,PASSWORD_BCRYPT);
                
                if($statut == 'eleve'){
                    echo "eleve";
                    if(!empty($_POST['classe'])&&!empty($_POST['promo'])){
                        $classe = $_POST["classe"];
                        $promo = $_POST["promo"];
                        //voir id
                        $ajout = $db->prepare("INSERT INTO eleve (eleve_id,eleve_name,eleve_surname,eleve_email,eleve_phone,eleve_password,classe,promo) VALUES (DEFAULT,:nom,:prenom,:email,:tel,:mp,:classe,:promo)");
                        $ajout->bindParam(':nom', $nom);
                        $ajout->bindParam(':prenom', $prenom);
                        $ajout->bindParam(':email', $email);
                        $ajout->bindParam(':tel', $tel);
                        $ajout->bindParam(':mp', $mp_crypt);
                        $ajout->bindParam(':classe', $classe);
                        $ajout->bindParam(':promo', $promo);
                        $ajout->execute();
                    }
                    else{
                        echo "Il manque des informations pour inscrire un élève";
                    }
                }
                else{
                    echo "prof";
                    if(!empty($_POST['matiere'])){
                        $matiere = $_POST['matiere'];
                        $ajout = $db->prepare("INSERT INTO prof (prof_id,prof_name,prof_surname,prof_email,prof_phone,prof_password,matiere) VALUES (DEFAULT,:nom,:prenom,:email,:tel,:mp,:matiere)");
                        $ajout->bindParam(':nom', $nom);
                        $ajout->bindParam(':prenom', $prenom);
                        $ajout->bindParam(':email', $email);
                        $ajout->bindParam(':tel', $tel);
                        $ajout->bindParam(':mp', $mp_crypt);
                        $ajout->bindParam(':matiere', $matiere);
                        $ajout->execute();
                    }
                    else{
                        echo "Il manque des informations pour inscrire un professeur";
                    }
                }
            }
            else{
                echo "Erreur dans la confirmation du mot de passe ou de l'email";
            }
        }
    ?>

</div>


</body>
</html>