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
                    NOM Prénom
                </span>
            </div>
        </nav>
        <br>
        <h2 style="text-align: center; padding-top: 5px;">Formulaire de création de compte :</h2>
    </header>
    
    <?php
        require('../lib/fonction.php');  
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        $db = dbConnect();
    ?>

    <div class="box" id="box1">
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <input id="case" type="text" placeholder="Nom" name="nom"/>
            <select id="case" name="statut">
                <option value="eleve">Eleve</option>
                <option value="prof">Professeur</option>
            </select>
            <input id="case" type="text" placeholder="Email" name="email"/>
            <input id="case" type="password" placeholder="Mot de passe" name="mp"/>
            <p>Si ajout d'un compte élève :</p>
            <select id="case" name="classe">
                <option selected>Classe :</option>
                <?php
                    $classes = dbGetClasse($db);
                    foreach($classes as $key => $values){
                        echo "<option value='".$values['classe']."'>".$values['classe']."</option>";
                    }
                ?>
            </select>
            <select id="case" name="promo">
                <option selected>Promo :</option>
                <?php
                    $promos = dbGetPromo($db);
                    foreach($promos as $key => $values){
                        echo "<option value='".$values['promo']."'>".$values['promo']."</option>";
                    }
                ?>
            </select>
    </div>

    <div class="box" id="box2">
            <input id="case" type="text" placeholder="Prénom" name="prenom"/>
            <input id="case" type="text" placeholder="Téléphone" name="tel"/>
            <input id="case" type="text" placeholder="Confirmation email" name="conf_email"/>
            <input id="case" type="password" placeholder="Confirmation mot de passe" name="conf_mp"/>
            <p>Si ajout d'un compte professeur :</p>
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
            <br>
            <input class="btn btn-secondary" type="submit" value="Enregistrer le compte"/>
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
                    if(!empty($_POST['classe'])&&!empty($_POST['promo'])){
                        $classe = $_POST["classe"];
                        $promo = $_POST["promo"];
                        dbInsertCompteEleve($db,$nom,$prenom,$email,$tel,$mp_crypt,$classe,$promo);
                    }
                    else{
                        echo "Il manque des informations pour inscrire un élève";
                    }
                }
                elseif($statut == 'prof'){
                    if(!empty($_POST['matiere'])){
                        $matiere = $_POST['matiere'];
                        $request = 'INSERT INTO prof (prof_id, prof_name, prof_surname, prof_email, prof_phone, prof_password, matiere) VALUES (DEFAULT, :name, :surname, :email, :phone, :password, :matiere)';
                        $statement = $db->prepare($request);
                        $statement->bindParam(':name',$nom);
                        $statement->bindParam(':surname',$prenom);
                        $statement->bindParam(':email',$email);
                        $statement->bindParam(':phone',$tel);
                        $statement->bindParam(':password',$mp_crypt);
                        $statement->bindParam(':matiere',$matiere);
                        $statement->execute();
                        dbInsertCompteProf($db,$nom,$prenom,$email,$tel,$mp_crypt,$matiere);                    
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