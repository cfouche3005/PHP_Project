<!-- fichier pour les fonctions récurrentes -->

<?php

    include("../db/constants.php");

    //Connection database
    function dbConnect(){
        $dsn = 'pgsql:dbname='.DB_NAME.';host='.DB_SERVER.';port='.DB_PORT;

        try {
            $conn = new PDO($dsn,DB_USER,DB_PASSWORD);
        }
        catch(PDOException $e){
            echo 'Connexion échouée : '.$e->getMessage();
        }
        return $conn;
    }

//Récupération email
    //Recuperer Mail eleve
    function dbGetMailEleve($pdo){
        $mails = $pdo->query('SELECT eleve_email FROM  eleve');
        $result = $mails->fetchALL(PDO::FETCH_ASSOC);
        return $result;
    }
    //Recuperer Mail prof
    function dbGetMailProf($pdo){
        $mails = $pdo->query('SELECT prof_email FROM prof');
        $result = $mails->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    //Recuperer Mail admin
    function dbGetMailAdmin($pdo){
        $mails = $pdo->query('SELECT admin_email FROM admin');
        $result = $mails->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

//Récupération mot passe
    //Recuperer MP eleve
    function dbGetMpEleve($pdo){
        $mps = $pdo->query('SELECT eleve_password from eleve');
        $result = $mps->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    //Recuperer MP prof
    function dbGetMpProf($pdo){
        //$mps = $pdo->query('SELECT '/*mp*/' from '/*table connexion prof*/ );
        $result = $mps->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    //Recuperer MP admin
    function dbGetMpAdmin($pdo){
        //$mps = $pdo->query('SELECT '/*mp*/' from '/*table connexion admin*/ );
        $result = $mps->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

//Matières
    function dbGetMatiere($pdo){
        $matieres = $pdo->query('SELECT matiere from matiere');
        $result = $matieres->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    function dbInsertMatiere($pdo,$matiere){
        $statement = $pdo->prepare('INSERT INTO matiere (matiere) VALUES (:matiere)');
        $statement->bindParam(':matiere',$matiere);
        $statement->execute();
    }

//Classe
    function dbGetClasse($pdo){
        $classes = $pdo->query('SELECT classe from classe');
        $result = $classes->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    function dbInsertClasse($pdo,$classe){
        $statement = $pdo->prepare('INSERT INTO classe (classe) VALUES (:classe)');
        $statement->bindParam(':classe',$classe);
        $statement->execute();
    }

//Année universitaire
    function dbGetAnneeUni($pdo){
        $annees = $pdo->query('SELECT annee_uni from annee_uni');
        $result = $annees->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    function dbInsertAnneeUni($pdo,$annee_uni){
        $statement = $pdo->prepare('INSERT INTO annee_uni (annee_uni) VALUES (:annee_uni)');
        $statement->bindParam(':annee_uni',$annee_uni);
        $statement->execute();
    }

//Promo
    function dbGetPromo($pdo){
        $promos = $pdo->query('SELECT promo from promo');
        $result = $promos->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    function dbInsertPromo($pdo,$promo){
        $statement = $pdo->prepare('INSERT INTO promo (promo) VALUES (:promo)');
        $statement->bindParam(':promo',$promo);
        $statement->execute();
    }

//Semestre
    function dbGetSemestre($pdo){
        $semestres = $pdo->query('SELECT semestre from semestre');
        $result = $semestres->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    function dbInsertSemestre($pdo,$semestre){
        $statement = $pdo->prepare('INSERT INTO semestre (semestre) VALUES (:semestre)');
        $statement->bindParam(':semestre',$emestre);
        $statement->execute();
    }

//Récupération nom prof
    function dbGetNomProf($pdo){
        $profs = $pdo->query('SELECT prof_name from prof');
        $result = $profs->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

//Récuperer une ligne de DS
    function dbGetDs($pdo){
        $ds = $pdo->query('SELECT date, heure, name, matiere, semestre FROM ds');
        $result = $ds->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

//Recuperer une ligne de compte eleve
    function dbGetCompteEleve($pdo){
        $ds = $pdo->query('SELECT eleve_name,eleve_surname,eleve_email,eleve_phone,classe,promo FROM eleve');
        $result = $ds->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

//Récuperer une ligne de compte prof
    function dbGetCompteProf($pdo){
        $ds = $pdo->query('SELECT prof_name,prof_surname,prof_email,prof_phone,matiere FROM prof');
        $result = $ds->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

//Connexion eleve 
    //Vérifier si mail entrer est dans la bd
    function dbEmailInBdEleve($pdo,$mail){
        $mails = dbGetMailEleve($pdo);
        /*foreach($mails as $key => $values){
            echo $values['eleve_email'].'<br>';
        }*/
        foreach($mails as $key => $values){
            if($mail == $values['eleve_email']){
                return true;
            }
            else{
                $check = false;
            }
        }
        return $check;
    }
    //Vérifier mail correspond au mp crypté
    function dbCheckMailMpEleve($pdo,$mail,$mp){
        //vérifie si email est present ds la bd
        $checkMail = dbEmailInBdEleve($pdo,$mail);
        
        if($checkMail == true){
            //récupère le mp crypté present ds la base de donnée selon l'email 
            $request = 'SELECT eleve_password from eleve where eleve_email = :eleve_email'; 
            $statement = $pdo->prepare($request);
            $statement->bindParam(':eleve_email',$mail);
            $statement->execute();
            $mp_crypt_bd = $statement->fetch(PDO::FETCH_ASSOC);

            //verifie si mp entrer est mp crypt de la bd
            $checkMp = password_verify($mp,$mp_crypt_bd['eleve_password']);   //attention verify prend que string
            if($checkMp){
                return true;
            }
        }
    }


//Connexion prof 
    //Vérifier si mail entrer est dans la bd
    function dbEmailInBdProf($pdo,$mail){
        $mails = dbGetMailProf($pdo);
        foreach($mails as $key => $values){
            if($mail == $values['prof_email']){
                return true;
            }
            else{
                $check = false;
            }
        }
        return $check;
    }
    //Vérifier mail correspond au mp crypté
    function dbCheckMailMpProf($pdo,$mail,$mp){
        //vérifie si email est present ds la bd
        $checkMail = dbEmailInBdProf($pdo,$mail);
        
        if($checkMail == true){
            //récupère le mp crypté present ds la base de donnée selon l'email 
            $request = 'SELECT prof_password from prof where prof_email = :prof_email'; 
            $statement = $pdo->prepare($request);
            $statement->bindParam(':prof_email',$mail);
            $statement->execute();
            $mp_crypt_bd = $statement->fetch(PDO::FETCH_ASSOC);

            //verifie si mp entrer est mp crypt de la bd
            $checkMp = password_verify($mp,$mp_crypt_bd['prof_password']);   //attention verify prend que string
            if($checkMp){
                return true;
            }
        }
    }
    

//Connexion admin 
    //Vérifier si mail entrer est dans la bd
    function dbEmailInBdAdmin($pdo,$mail){
        $mails = dbGetMailAdmin($pdo);
        foreach($mails as $key => $values){
            if($mail == $values['admin_email']){
                return true;
            }
            else{
                $check = false;
            }
        }
        return $check;
    }
    //Vérifier mail correspond au mp crypté
    function dbCheckMailMpAdmin($pdo,$mail,$mp){
        //vérifie si email est present ds la bd
        $checkMail = dbEmailInBdAdmin($pdo,$mail);
        
        if($checkMail == true){
            //récupère le mp crypté present ds la base de donnée selon l'email 
            $request = 'SELECT admin_password from admin where admin_email = :admin_email'; 
            $statement = $pdo->prepare($request);
            $statement->bindParam(':admin_email',$mail);
            $statement->execute();
            $mp_crypt_bd = $statement->fetch(PDO::FETCH_ASSOC);

            //verifie si mp entrer est mp crypt de la bd
            $checkMp = password_verify($mp,$mp_crypt_bd['admin_password']);   //attention verify prend que string
            if($checkMp){
                return true;
            }
        }
    }


//fonction ajouter DS ds bdd
    function dbInsertDS($pdo,$class,$DSname,$matiere,$semester,$heure,$date){
        $prof_id = dbGetIdProfByMatiere($pdo,$matiere);
        $request = 'INSERT INTO ds (ds_id,date,heure,name,matiere,semestre,classe,prof_id) VALUES (DEFAULT,:date,:heure,:name,:matiere,:semestre,:classe,:prof_id)';
        $statement = $pdo->prepare($request);
        $statement->bindParam(':date',$date);
        $statement->bindParam(':heure',$heure);
        $statement->bindParam(':name',$DSname);
        $statement->bindParam(':matiere',$matiere);
        $statement->bindParam(':semestre',$semester);
        $statement->bindParam(':classe',$class);
        $statement->bindParam(':prof_id',$prof_id['prof_id']);
        $statement->execute();
    }

    function dbGetIdProfByMatiere($pdo,$matiere){
        $request = 'SELECT prof_id FROM prof WHERE matiere=:matiere';
        $statement = $pdo->prepare($request);
        $statement->bindParam(':matiere',$matiere);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    //plus utile
    function dbGetEleveID($pdo,$class){
        $request = 'SELECT eleve_id from eleve where eleve_class = :eleve_class';
        $statement = $pdo->prepare($request);
        $statement->bindParam(':eleve_class',$class);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $resultlist = array();
        foreach($result as $key => $values){
            array_push($resultlist,$values);
        }
        return $resultlist;
    }


//fonction ajouter compte ds bdd 
    function dbInsertCompteEleve($pdo,$nom,$prenom,$email,$tel,$mp_crypt,$classe,$promo){
        $request = 'INSERT INTO eleve (eleve_id,eleve_name,eleve_surname,eleve_email,eleve_phone,eleve_password,classe,promo) VALUES (DEFAULT,:nom,:prenom,:email,:tel,:mp,:classe,:promo)';
        $ajout = $pdo->prepare($request);
        $ajout->bindParam(':nom', $nom);
        $ajout->bindParam(':prenom', $prenom);
        $ajout->bindParam(':email', $email);
        $ajout->bindParam(':tel', $tel);
        $ajout->bindParam(':mp', $mp_crypt);
        $ajout->bindParam(':classe', $classe);
        $ajout->bindParam(':promo', $promo);
        $ajout->execute();
    }
    function dbInsertCompteProf($pdo,$nom,$prenom,$email,$tel,$mp_crypt,$matiere){
        $request = 'INSERT INTO prof (prof_id,prof_name,prof_surname,prof_email,prof_phone,prof_password,matiere) VALUES (DEFAULT,:nom,:prenom,:email,:tel,:mp,:matiere)';
        $ajout = $pdo->prepare($request);
        $ajout->bindParam(':nom', $nom);
        $ajout->bindParam(':prenom', $prenom);
        $ajout->bindParam(':email', $email);
        $ajout->bindParam(':tel', $tel);
        $ajout->bindParam(':mp', $mp_crypt);
        $ajout->bindParam(':matiere', $matiere);
        $ajout->execute();
    }


//Tableau avec chaque ds et ces informations selon la classe
    function dbGetDsInfo($pdo,$classe){
        $request = 'SELECT d.ds_id, d.name, d.matiere, p.prof_name, d.semestre, d.date, d.heure FROM ds d, prof p WHERE d.prof_id=p.prof_id AND d.classe=:classe ORDER BY d.matiere, d.date';
        $statement = $pdo->prepare($request);
        $statement->bindParam(':classe',$classe);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


//Supprimer un DS
    function dbSupprDs($pdo,$id_ds){
        $statement = $pdo->prepare('DELETE from ds WHERE ds_id=:id_ds');
        $statement->bindParam(':id_ds',$id_ds);
        $statement->execute();
    }


//Modifier un DS
    function dbModifierDsDate($pdo,$date,$id_ds){
        $request = 'UPDATE ds SET date=:new_val WHERE ds_id=:id_ds';
        $statement = $pdo->prepare($request); 
        $statement->bindParam(':new_val',$date);
        $statement->bindParam(':id_ds',$id_ds);
        $statement->execute();
    }
    function dbModifierDsHeure($pdo,$heure,$id_ds){
        $request = 'UPDATE ds SET heure=:new_val WHERE ds_id=:id_ds';
        $statement = $pdo->prepare($request); 
        $statement->bindParam(':new_val',$heure);
        $statement->bindParam(':id_ds',$id_ds);
        $statement->execute();
    }
    function dbModifierDsName($pdo,$name,$id_ds){
        $request = 'UPDATE ds SET name=:new_val WHERE ds_id=:id_ds';
        $statement = $pdo->prepare($request); 
        $statement->bindParam(':new_val',$name);
        $statement->bindParam(':id_ds',$id_ds);
        $statement->execute();
    }
    function dbModifierDsMatiere($pdo,$matiere,$id_ds){
        $request = 'UPDATE ds SET matiere=:new_val WHERE ds_id=:id_ds';
        $statement = $pdo->prepare($request); 
        $statement->bindParam(':new_val',$matiere);
        $statement->bindParam(':id_ds',$id_ds);
        $statement->execute();
    }
    function dbModifierDsSemestre($pdo,$semestre,$id_ds){
        $request = 'UPDATE ds SET semestre=:new_val WHERE ds_id=:id_ds';
        $statement = $pdo->prepare($request); 
        $statement->bindParam(':new_val',$semestre);
        $statement->bindParam(':id_ds',$id_ds);
        $statement->execute();
    }
    function dbModifierDsClasse($pdo,$classe,$id_ds){
        $request = 'UPDATE ds SET classe=:new_val WHERE ds_id=:id_ds';
        $statement = $pdo->prepare($request); 
        $statement->bindParam(':new_val',$classe);
        $statement->bindParam(':id_ds',$id_ds);
        $statement->execute();
    }
    function dbModifierDsIdProf($pdo,$prof_name,$id_ds){
        $id_prof = dbGetIdProfByName($pdo,$prof_name);
        $request = 'UPDATE ds SET prof_id=:new_val WHERE ds_id=:id_ds';
        $statement = $pdo->prepare($request); 
        $statement->bindParam(':new_val',$id_prof);
        $statement->bindParam(':id_ds',$id_ds);
        $statement->execute();
    }


//Tableau avec chaque compte et ces informations selon le statut
    function dbGetCompteInfoEleve($pdo,$classe){
        $request = 'SELECT eleve_id, eleve_name, eleve_surname, eleve_email, eleve_phone, classe, promo FROM eleve WHERE classe=:classe ORDER BY eleve_name';
        $statement = $pdo->prepare($request);
        $statement->bindParam(':classe',$classe);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    function dbGetCompteInfoProf($pdo){
        $statement = $pdo->query('SELECT prof_id, prof_name, prof_surname, prof_email, prof_phone, matiere FROM prof ORDER BY prof_name');
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


//Modifier un compte eleve
    function dbModifierCompteEleveName($pdo,$eleve_name,$id_compte){
        $request = 'UPDATE eleve SET eleve_name=:new_val WHERE eleve_id=:id_compte';
        $statement = $pdo->prepare($request); 
        $statement->bindParam(':new_val',$eleve_name);
        $statement->bindParam(':id_compte',$id_compte);
        $statement->execute();
    }
    function dbModifierCompteEleveSurname($pdo,$eleve_surname,$id_compte){
        $request = 'UPDATE eleve SET eleve_surname=:new_val WHERE eleve_id=:id_compte';
        $statement = $pdo->prepare($request); 
        $statement->bindParam(':new_val',$eleve_surname);
        $statement->bindParam(':id_compte',$id_compte);
        $statement->execute();
    }
    function dbModifierCompteEleveEmail($pdo,$eleve_email,$id_compte){
        $request = 'UPDATE eleve SET eleve_email=:new_val WHERE eleve_id=:id_compte';
        $statement = $pdo->prepare($request); 
        $statement->bindParam(':new_val',$eleve_email);
        $statement->bindParam(':id_compte',$id_compte);
        $statement->execute();
    }
    function dbModifierCompteElevePhone($pdo,$eleve_phone,$id_compte){
        $request = 'UPDATE eleve SET eleve_phone=:new_val WHERE eleve_id=:id_compte';
        $statement = $pdo->prepare($request); 
        $statement->bindParam(':new_val',$eleve_phone);
        $statement->bindParam(':id_compte',$id_compte);
        $statement->execute();
    }
    function dbModifierCompteEleveClasse($pdo,$classe,$id_compte){
        $request = 'UPDATE eleve SET classe=:new_val WHERE eleve_id=:id_compte';
        $statement = $pdo->prepare($request); 
        $statement->bindParam(':new_val',$classe);
        $statement->bindParam(':id_compte',$id_compte);
        $statement->execute();
    }
    function dbModifierCompteElevePromo($pdo,$promo,$id_compte){
        $request = 'UPDATE eleve SET promo=:new_val WHERE eleve_id=:id_compte';
        $statement = $pdo->prepare($request); 
        $statement->bindParam(':new_val',$promo);
        $statement->bindParam(':id_compte',$id_compte);
        $statement->execute();
    }


//Supprimer un compte eleve
    function dbSupprCompteEleve($pdo,$id_compte_eleve){
        $statement = $pdo->prepare('DELETE from eleve WHERE eleve_id=:id_eleve');
        $statement->bindParam(':id_eleve',$id_compte_eleve);
        $statement->execute();
    }


//Modifier un compte prof
    function dbModifierCompteProfName($pdo,$prof_name,$id_compte){
        $request = 'UPDATE prof SET prof_name=:new_val WHERE prof_id=:id_compte';
        $statement = $pdo->prepare($request); 
        $statement->bindParam(':new_val',$prof_name);
        $statement->bindParam(':id_compte',$id_compte);
        $statement->execute();
    }
    function dbModifierCompteProfSurname($pdo,$prof_surname,$id_compte){
        $request = 'UPDATE prof SET prof_surname=:new_val WHERE prof_id=:id_compte';
        $statement = $pdo->prepare($request); 
        $statement->bindParam(':new_val',$prof_surname);
        $statement->bindParam(':id_compte',$id_compte);
        $statement->execute();
    }
    function dbModifierCompteProfEmail($pdo,$prof_email,$id_compte){
        $request = 'UPDATE prof SET prof_email=:new_val WHERE prof_id=:id_compte';
        $statement = $pdo->prepare($request); 
        $statement->bindParam(':new_val',$prof_email);
        $statement->bindParam(':id_compte',$id_compte);
        $statement->execute();
    }
    function dbModifierCompteProfPhone($pdo,$prof_phone,$id_compte){
        $request = 'UPDATE prof SET prof_phone=:new_val WHERE prof_id=:id_compte';
        $statement = $pdo->prepare($request); 
        $statement->bindParam(':new_val',$prof_phone);
        $statement->bindParam(':id_compte',$id_compte);
        $statement->execute();
    }
    function dbModifierCompteProfSMatiere($pdo,$matiere,$id_compte){
        $request = 'UPDATE prof SET matiere=:new_val WHERE prof_id=:id_compte';
        $statement = $pdo->prepare($request); 
        $statement->bindParam(':new_val',$matiere);
        $statement->bindParam(':id_compte',$id_compte);
        $statement->execute();
    }

    
//Supprimer un compte eleve
    function dbSupprCompteProf($pdo,$id_compte_prof){
        $statement = $pdo->prepare('DELETE from prof WHERE prof_id=:id_prof');
        $statement->bindParam(':id_prof',$id_compte_prof);
        $statement->execute();
    }

?>