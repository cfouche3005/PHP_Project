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

//Récupération eleve 
    //Recuperer Mail eleve
    function dbGetMailEleve($pdo){
        $mails = $pdo->query('SELECT eleve_email FROM  eleve');
        $result = $mails->fetchALL(PDO::FETCH_ASSOC);
        return $result;
    }
    //Recuperer MP eleve
    function dbGetMpEleve($pdo){
        $mps = $pdo->query('SELECT eleve_password from eleve');
        $result = $mps->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    //Recup id eleve by email
    function dbGetIdEleveByEmail($pdo,$email){
        $request = 'SELECT eleve_id FROM eleve WHERE eleve_email=:email';
        $statement = $pdo->prepare($request);
        $statement->bindParam(':email',$email);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    //Recup nom prénom eleve by id
    function dbGetNameSurnameEleveById($pdo,$id_eleve){
        $request = 'SELECT eleve_name, eleve_surname FROM eleve WHERE eleve_id=:id_eleve';
        $statement = $pdo->prepare($request);
        $statement->bindParam(':id_eleve',$id_eleve['eleve_id']);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    //Recup classe eleve by id
    function dbGetClasseEleveById($pdo,$id_eleve){
        $request = 'SELECT classe_id FROM eleve WHERE eleve_id=:id_eleve';
        $statement = $pdo->prepare($request);
        $statement->bindParam(':id_eleve',$id_eleve);
        $statement->execute();
        $id_classe = $statement->fetch(PDO::FETCH_ASSOC);
        $result = dbGetClasseByIdClasse($pdo,$id_classe['classe_id']);
        return $result;
    }
    //Recup id_eleve by classe
    function dbGetIdEleveByClasse($pdo,$classe){
        $idClasse = dbGetIdClasseByClasse($pdo,$classe);
        $request = 'SELECT eleve_id FROM eleve WHERE classe_id=:id_classe';
        $statement = $pdo->prepare($request);
        $statement->bindParam(':id_classe',$idClasse['classe_id']);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    //Recup id_eleve by nom prenom
    function dbGetIdEleveByNomPrenom($pdo,$nom,$prenom){
        $request = 'SELECT eleve_id FROM eleve WHERE eleve_name=:eleve_name AND eleve_surname=:eleve_surname';
        $statement = $pdo->prepare($request);
        $statement->bindParam(':eleve_name',$nom);
        $statement->bindParam(':eleve_surname',$prenom);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    //Recup nom prenom eleve by classe
    function dbGetNameSurnameEleveByClasse($pdo,$classe){
        $idClasse = dbGetIdClasseByClasse($pdo,$classe);
        $request = 'SELECT eleve_id, eleve_name, eleve_surname FROM eleve WHERE classe_id=:id_classe ORDER BY eleve_name';
        $statement = $pdo->prepare($request);
        $statement->bindParam(':id_classe',$idClasse['classe_id']);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    //Recup toutes les infos élèves by classe
    function dbGetEtudiantByClasse($pdo,$classe, $matiere, $id_semestre){
        $idClasse = dbGetIdClasseByClasse($pdo,$classe);
        $request = 'SELECT e.eleve_id, e.eleve_name, e.eleve_surname, e.eleve_email, n.note, n.ds_id, c.classe, a.appreciation FROM eleve e 
                    JOIN classe c ON e.classe_id = c.classe_id 
                    JOIN notes n ON e.eleve_id = n.eleve_id 
                    JOIN ds d ON n.ds_id = d.ds_id
                    JOIN appreciation a ON a.matiere = d.matiere AND a.eleve_id = e.eleve_id
                    WHERE c.classe_id = :id_classe AND e.eleve_id = n.eleve_id AND n.ds_id = d.ds_id AND d.matiere = :matiere AND d.semestre_id = :id_semestre ORDER BY e.eleve_name ASC;';
        $statement = $pdo->prepare($request);
        $statement->bindParam(':id_classe',$idClasse['classe_id']);
        $statement->bindParam(':matiere',$matiere);
        $statement->bindParam(':id_semestre',$id_semestre);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


//Récupération prof
    //Recuperer Mail prof
    function dbGetMailProf($pdo){
        $mails = $pdo->query('SELECT prof_email FROM prof');
        $result = $mails->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    //Recuperer MP prof
    function dbGetMpProf($pdo){
        //$mps = $pdo->query('SELECT '/*mp*/' from '/*table connexion prof*/ );
        $result = $mps->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    //Recup id prod by email
    function dbGetIdProfByEmail($pdo,$email){
        $request = 'SELECT prof_id FROM prof WHERE prof_email=:email';
        $statement = $pdo->prepare($request);
        $statement->bindParam(':email',$email);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    //Recup id prof selon la matiere
    function dbGetIdProfByMatiere($pdo,$matiere){
        $request = 'SELECT prof_id FROM prof WHERE matiere=:matiere';
        $statement = $pdo->prepare($request);
        $statement->bindParam(':matiere',$matiere);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    //Recup nom prénom prof by id
    function dbGetNameSurnameProfById($pdo,$id_prof){
        $request = 'SELECT prof_name, prof_surname FROM prof WHERE prof_id=:id_prof';
        $statement = $pdo->prepare($request);
        $statement->bindParam(':id_prof',$id_prof['prof_id']);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    

//Récupération admin
    //Recuperer MP admin
    function dbGetMpAdmin($pdo){
        //$mps = $pdo->query('SELECT '/*mp*/' from '/*table connexion admin*/ );
        $result = $mps->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    //Recuperer Mail admin
    function dbGetMailAdmin($pdo){
        $mails = $pdo->query('SELECT admin_email FROM admin');
        $result = $mails->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    function dbGetIdAdminByEmail($pdo,$email){
        $request = 'SELECT admin_id FROM admin WHERE admin_email=:email';
        $statement = $pdo->prepare($request);
        $statement->bindParam(':email',$email);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    //Recup nom prénom admin by id
    function dbGetNameSurnameAdminById($pdo,$id_admin){
        $request = 'SELECT admin_name, admin_surname FROM admin WHERE admin_id=:id_admin';
        $statement = $pdo->prepare($request);
        $statement->bindParam(':id_admin',$id_admin['admin_id']);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
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
    function dbGetIdClasseByClasse($pdo,$classe){
        $statement = $pdo->prepare('SELECT classe_id FROM classe WHERE classe=:classe');
        $statement->bindParam(':classe',$classe);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    function dbGetClasseByIdClasse($pdo,$id_classe){
        $statement = $pdo->prepare('SELECT classe FROM classe WHERE classe_id=:id_classe');
        $statement->bindParam(':id_classe',$id_classe);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    function dbInsertClasse($pdo,$classe,$annee_uni){
        $statement = $pdo->prepare('INSERT INTO classe (classe_id,classe,annee_uni) VALUES (DEFAULT,:classe,:annee_uni)');
        $statement->bindParam(':classe',$classe);
        $statement->bindParam(':annee_uni',$annee_uni);
        $statement->execute();
    }

//Année universitaire
    function dbGetAnneeUni($pdo){
        $annees = $pdo->query('SELECT annee_uni from annee_uni');
        $result = $annees->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    function dbGetAnneUniByClasse($pdo,$classe){
        $statement = $pdo->prepare('SELECT annee_uni FROM classe WHERE classe=:classe');
        $statement->bindParam(':classe',$classe);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    function dbInsertAnneeUni($pdo,$annee_uni){
        $statement = $pdo->prepare('INSERT INTO annee_uni (annee_uni) VALUES (:annee_uni)');
        $statement->bindParam(':annee_uni',$annee_uni);
        $statement->execute();
    }

//Semestre
    function dbGetSemestre($pdo){
        $semestres = $pdo->query('SELECT DISTINCT semestre from semestre ORDER BY semestre');
        $result = $semestres->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    function dbGetSemestreByAnneUni($pdo,$annee_uni){
        $statement = $pdo->prepare('SELECT DISTINCT semestre FROM semestre WHERE annee_uni=:annee_uni ORDER BY semestre');
        $statement->bindParam(':annee_uni',$annee_uni);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    function dbGetIdSemestreBySemetre($pdo,$semestre,$annee_uni){
        $statement = $pdo->prepare('SELECT semestre_id FROM semestre WHERE semestre=:semestre AND annee_uni=:annee_uni');
        $statement->bindParam(':semestre',$semestre);
        $statement->bindParam(':annee_uni',$annee_uni);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    function dbGetSemestreByIdSemetre($pdo,$id_semestre){
        $statement = $pdo->prepare('SELECT semestre FROM semestre WHERE semestre_id=:semestre_id');
        $statement->bindParam(':semestre_id',$id_semestre);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    function dbInsertSemestre($pdo,$semestre,$annee_uni){
        $statement = $pdo->prepare('INSERT INTO semestre (semestre_id,semestre,annee_uni) VALUES (DEFAULT,:semestre,:annee_uni)');
        $statement->bindParam(':semestre',$semestre);
        $statement->bindParam(':annee_uni',$annee_uni);
        $statement->execute();
    }

//Recup appréciation
    function dbGetAppreciation($pdo,$semestre,$matiere,$eleve_id,$classe){
        $annee_uni = dbGetAnneUniByClasse($pdo,$classe);
        $semestre_id = dbGetIdSemestreBySemetre($pdo,$semestre,$annee_uni['annee_uni']);
        //return $semestre_id;
        $request = 'SELECT DISTINCT appreciation FROM appreciation WHERE semestre_id=:semestre_id AND matiere=:matiere AND eleve_id=:eleve_id';
        $statement = $pdo->prepare($request);
        $statement->bindParam(':semestre_id',$semestre_id['semestre_id']);
        $statement->bindParam(':matiere',$matiere);
        $statement->bindParam(':eleve_id',$eleve_id);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

//Récupération nom prof
    function dbGetNomProf($pdo){
        $profs = $pdo->query('SELECT prof_name from prof');
        $result = $profs->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

//Récuperer une ligne de DS
    function dbGetDs($pdo){
        $statement = $pdo->prepare('SELECT d.date, d.heure, d.name, d.matiere, s.semestre FROM ds d, semestre s WHERE d.semestre_id=s.semestre_id');
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

//Récupérer la note de DS selon l'ID du DS
    function dbGetNoteDs($pdo,$ds_id){
        $statement = $pdo->prepare('SELECT note FROM note WHERE ds_id=:ds_id');
        $statement->bindParam(':ds_id',$ds_id);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

//Recuperer une ligne de compte eleve
    function dbGetCompteEleve2($pdo){
        $statement = $pdo->prepare('SELECT e.eleve_name, e.eleve_surname, e.eleve_email, e.eleve_phone, c.classe FROM eleve e, classe c WHERE c.classe_id=e.classe_id');
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    function dbGetCompteEleve($pdo){
        $statement = $pdo->query('SELECT eleve_name, eleve_surname, eleve_email, eleve_phone FROM eleve');
        $result = $statement->fetch(PDO::FETCH_ASSOC);
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
    function dbInsertDS($pdo,$class,$DSname,$matiere,$semestre,$heure,$date){
        $prof_id = dbGetIdProfByMatiere($pdo,$matiere);
        $annee_uni = dbGetAnneUniByClasse($pdo,$class);
        $semestre_id = dbGetIdSemestreBySemetre($pdo,$semestre,$annee_uni['annee_uni']);
        $classe_id = dbGetIdClasseByClasse($pdo,$class);
        $request = 'INSERT INTO ds (ds_id,date,heure,name,matiere,semestre_id,classe_id,prof_id) VALUES (DEFAULT,:date,:heure,:name,:matiere,:semestre_id,:classe_id,:prof_id)';
        $statement = $pdo->prepare($request);
        $statement->bindParam(':date',$date);
        $statement->bindParam(':heure',$heure);
        $statement->bindParam(':name',$DSname);
        $statement->bindParam(':matiere',$matiere);
        $statement->bindParam(':semestre_id',$semestre_id['semestre_id']);
        $statement->bindParam(':classe_id',$classe_id['classe_id']);
        $statement->bindParam(':prof_id',$prof_id['prof_id']);
        $statement->execute();
    }


//fonction ajouter compte ds bdd 
    function dbInsertCompteEleve($pdo,$nom,$prenom,$email,$tel,$mp_crypt,$classe){
        $classe_id = dbGetIdClasseByClasse($pdo,$classe);
        $request = 'INSERT INTO eleve (eleve_id,eleve_name,eleve_surname,eleve_email,eleve_phone,eleve_password,classe_id) VALUES (DEFAULT,:nom,:prenom,:email,:tel,:mp,:classe_id)';
        $ajout = $pdo->prepare($request);
        $ajout->bindParam(':nom', $nom);
        $ajout->bindParam(':prenom', $prenom);
        $ajout->bindParam(':email', $email);
        $ajout->bindParam(':tel', $tel);
        $ajout->bindParam(':mp', $mp_crypt);
        $ajout->bindParam(':classe_id', $classe_id['classe_id']);
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
        $classe_id = dbGetIdClasseByClasse($pdo,$classe);
        $request = 'SELECT d.ds_id, d.name, d.matiere, s.semestre, d.date, d.heure FROM ds d, semestre s WHERE d.semestre_id=s.semestre_id AND d.classe_id=:classe_id ORDER BY d.matiere, d.date';
        $statement = $pdo->prepare($request);
        $statement->bindParam(':classe_id',$classe_id['classe_id']);
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
        $prof_id = dbGetIdProfByMatiere($pdo,$matiere);
        $request = 'UPDATE ds SET matiere=:matiere, prof_id=:prof_id WHERE ds_id=:id_ds';
        $statement = $pdo->prepare($request); 
        $statement->bindParam(':matiere',$matiere);
        $statement->bindParam(':prof_id',$prof_id['prof_id']);
        $statement->bindParam(':id_ds',$id_ds);
        $statement->execute();
    }
    function dbModifierDsSemestre($pdo,$semestre,$id_ds,$classe){
        $annee_uni = dbGetAnneUniByClasse($pdo,$classe);
        $semestre_id = dbGetIdSemestreBySemetre($pdo, $semestre,$annee_uni['annee_uni']);
        $request = 'UPDATE ds SET semestre_id=:new_val WHERE ds_id=:id_ds';
        $statement = $pdo->prepare($request); 
        $statement->bindParam(':new_val',$semestre_id['semestre_id']);
        $statement->bindParam(':id_ds',$id_ds);
        $statement->execute();
    }
    function dbModifierDsClasse($pdo,$classe,$id_ds){
        $classe_id = dbGetIdClasseByClasse($pdo,$classe);
        $request = 'UPDATE ds SET classe_id=:new_val WHERE ds_id=:id_ds';
        $statement = $pdo->prepare($request); 
        $statement->bindParam(':new_val',$classe_id);
        $statement->bindParam(':id_ds',$id_ds);
        $statement->execute();
    }


//Tableau avec chaque compte et ces informations selon le statut
    function dbGetCompteInfoEleve($pdo,$classe){
        $request = 'SELECT e.eleve_id, e.eleve_name, e.eleve_surname, e.eleve_email, e.eleve_phone, c.classe FROM eleve e, classe c WHERE c.classe=:classe AND e.classe_id=c.classe_id ORDER BY eleve_name';
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
        $classe_id = dbGetIdClasseByClasse($pdo,$classe);
        $request = 'UPDATE eleve SET classe_id=:new_val WHERE eleve_id=:id_compte';
        $statement = $pdo->prepare($request); 
        $statement->bindParam(':new_val',$classe_id['classe_id']);
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

//Recup liste des matieres selon classe & semestre
    function dbGetMatiereByClasseSemestre($pdo, $classe, $semestre_id){
        $classe_id = dbGetIdClasseByClasse($pdo,$classe);
        $statement = $pdo->prepare('SELECT DISTINCT matiere FROM ds WHERE classe_id=:classe_id AND semestre_id=:semestre_id ORDER BY matiere');
        $statement->bindParam(':classe_id',$classe_id['classe_id']);
        $statement->bindParam(':semestre_id',$semestre_id);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    function dbGetMatiereByProfId($pdo, $id_prof){
        $statement = $pdo->prepare('SELECT matiere FROM prof WHERE prof_id=:id_prof');
        $statement->bindParam(':id_prof',$id_prof['prof_id']);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

//Recup liste id_ds selon classe semestre matiere
    function dbGetIdDsByClasseSemestreMatiere($pdo,$classe,$semestre,$matiere){
        $classe_id = dbGetIdClasseByClasse($pdo,$classe);
        $annee_uni = dbGetAnneUniByClasse($pdo,$classe);
        $semestre_id = dbGetIdSemestreBySemetre($pdo,$semestre,$annee_uni['annee_uni']);
        $request = 'SELECT ds_id FROM ds WHERE classe_id=:classe_id AND semestre_id=:semestre_id AND matiere=:matiere ORDER BY date';
        $statement = $pdo->prepare($request);
        $statement->bindParam(':classe_id',$classe_id['classe_id']);
        $statement->bindParam(':semestre_id',$semestre_id['semestre_id']);
        $statement->bindParam(':matiere',$matiere);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

//Recup info pour tableau notes eleves
    function dbGetInfoNotesEleve($pdo,$id_ds,$classe,$semestre,$matiere,$id_eleve){
        $classe_id = dbGetIdClasseByClasse($pdo,$classe);
        $annee_uni = dbGetAnneUniByClasse($pdo,$classe);
        $semestre_id = dbGetIdSemestreBySemetre($pdo,$semestre,$annee_uni['annee_uni']);
        $request = 'SELECT d.ds_id, d.name, n.note, n.coeff FROM ds d, notes n WHERE d.ds_id=:id_ds AND d.ds_id=n.ds_id AND d.classe_id=:classe_id AND d.semestre_id=:semestre_id AND d.matiere=:matiere AND n.eleve_id=:id_eleve';
        $statement = $pdo->prepare($request);
        $statement->bindParam(':id_ds',$id_ds);
        $statement->bindParam(':classe_id',$classe_id['classe_id']);
        $statement->bindParam(':semestre_id',$semestre_id['semestre_id']);
        $statement->bindParam(':matiere',$matiere);
        $statement->bindParam(':id_eleve',$id_eleve);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

//Nombre d'élève par classe
    function dbCountEleveByClasse($pdo, $classe){
        $classe_id = dbGetIdClasseByClasse($pdo,$classe);
        $statement = $pdo->prepare('SELECT COUNT(eleve_id) FROM eleve WHERE classe_id=:classe_id');
        $statement->bindParam(':classe_id',$classe_id['classe_id']);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;     //attention result tableau ['count']
    }

//Recup note d'un DS
    function dbGetNoteByDsIdEleveId($pdo,$id_ds,$id_eleve){
        $statement = $pdo->prepare('SELECT note FROM notes WHERE ds_id=:id_ds AND eleve_id=:id_eleve');
        $statement->bindParam(':id_ds',$id_ds);
        $statement->bindParam(':id_eleve',$id_eleve);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

//Recup note coef par eleve matiere semestre
    function dbGetNoteCoefByEleveMatSem($pdo,$id_eleve,$matiere,$semestre,$classe){
        $annee_uni = dbGetAnneUniByClasse($pdo,$classe);
        $semestre_id = dbGetIdSemestreBySemetre($pdo,$semestre,$annee_uni['annee_uni']);
        $request = 'SELECT n.note, n.coeff FROM notes n, ds d WHERE d.ds_id=n.ds_id AND d.semestre_id=:semestre_id AND d.matiere=:matiere AND n.eleve_id=:id_eleve';
        $statement = $pdo->prepare($request);
        $statement->bindParam(':semestre_id',$semestre_id['semestre_id']);
        $statement->bindParam(':matiere',$matiere);
        $statement->bindParam(':id_eleve',$id_eleve);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    function dbCountNoteByEleveMatSem($pdo,$id_eleve,$matiere,$semestre,$classe){
        $annee_uni = dbGetAnneUniByClasse($pdo,$classe);
        $semestre_id = dbGetIdSemestreBySemetre($pdo,$semestre,$annee_uni['annee_uni']);
        $request = 'SELECT COUNT(n.note) FROM notes n, ds d WHERE d.ds_id=n.ds_id AND d.semestre_id=:semestre_id AND d.matiere=:matiere AND n.eleve_id=:id_eleve';
        $statement = $pdo->prepare($request);
        $statement->bindParam(':semestre_id',$semestre_id['semestre_id']);
        $statement->bindParam(':matiere',$matiere);
        $statement->bindParam(':id_eleve',$id_eleve);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

//Calculer moyenne eleve par matiere
    function dbCalculerMoyMatiere($pdo,$id_eleve,$matiere,$semestre,$nbrNotes,$classe){
        $listeNotesCoef = dbGetNoteCoefByEleveMatSem($pdo,$id_eleve,$matiere,$semestre,$classe);
        $somme = 0;
        $totalCoef = 0;
        foreach($listeNotesCoef as $key =>$values){
            $somme += $values['note']*$values['coeff'];
            $totalCoef += $values['coeff'];
        }
        $moy = $somme / $totalCoef;
        return $moy;
    }

//Calculer la moyenne de classe
    function dbCalculerMoyenneClasse($pdo,$classe,$matiere,$semestre,$nbrNotes,$nbrEleve){
        $listeIdEleve = dbGetIdEleveByClasse($pdo,$classe);
        $sommeClasse = 0;
        foreach($listeIdEleve as $key =>$values){
            $sommeClasse += dbCalculerMoyMatiere($pdo,$values['eleve_id'],$matiere,$semestre,$nbrNotes,$classe);
        }
        $moyClasse = $sommeClasse / $nbrEleve;
        return $moyClasse;
    }

//Calculer moyenne générale élève
    function dbGetMoyGenerale($pdo,$id_eleve,$semestre,$nbrNotes,$listeMatieres,$classe){
        $somme = 0;
        $nbMoy = 0;
        foreach($listeMatieres as $key =>$values){
            $somme += dbCalculerMoyMatiere($pdo,$id_eleve,$values['matiere'],$semestre,$nbrNotes,$classe);
            $nbMoy += 1;
        }
        $moyGenerale = $somme / $nbMoy;
        return $moyGenerale;
    }

//Rang eleve ds la classe
    function dbGetRang($pdo,$classe,$matiere,$semestre,$nbrNotes,$moy){
        $listeIdEleve = dbGetIdEleveByClasse($pdo,$classe);
        $tabMoy = array();
        foreach($listeIdEleve as $key =>$values){
            array_push($tabMoy, dbCalculerMoyMatiere($pdo,$values['eleve_id'],$matiere,$semestre,$nbrNotes,$classe));
        }
        rsort($tabMoy);
        for($i=0; $i<count($tabMoy); $i++){
            if($tabMoy[$i] == $moy){
                return $i+1;
            }
        }
    }

//Rang général de l'eleve ds la classe
    function dbGetRangGeneral($pdo,$id_eleve,$classe,$semestre,$nbrNotes,$moyGenerale,$listeMatieres){
        $listeIdEleve = dbGetIdEleveByClasse($pdo,$classe);
        $tabMoy = array();
        foreach($listeIdEleve as $key =>$values){
            array_push($tabMoy, dbGetMoyGenerale($pdo,$values['eleve_id'],$semestre,$nbrNotes,$listeMatieres,$classe));
        }
        rsort($tabMoy);
        for($i=0; $i<count($tabMoy); $i++){
            if($tabMoy[$i] == $moyGenerale){
                return $i+1;
            }
        }
    }

//Récupérer toutes les infos des élèves par matière
    function dbGetList($pdo, $classe, $matiere, $semestre){
        $listeIdEleve = dbGetIdEleveByClasse($pdo,$classe);
        $listeEleve = array();
        foreach($listeIdEleve as $key =>$values){
            $eleve = array();
            $eleve['eleve_id'] = $values['eleve_id'];
            $eleve['nom'] = dbGetNomEleveById($pdo,$values['eleve_id']);
            $eleve['prenom'] = dbGetPrenomEleveById($pdo,$values['eleve_id']);
            $eleve['mail'] = dbGetMailEleveById($pdo,$values['eleve_id']);
            $eleve['moyenne'] = dbCalculerMoyMatiere($pdo,$values['eleve_id'],$matiere,$semestre,$nbrNotes,$classe);
            $eleve['rang'] = dbGetRang($pdo,$classe,$matiere,$semestre,$nbrNotes,$eleve['moyenne']);
            array_push($listeEleve, $eleve);
        }
        return $listeEleve;
    }

//Recuperer info DS pour prof 
    function dbGetDsByClasseSemMat($pdo,$classe,$semestre_id,$matiere){
        $classe_id = dbGetIdClasseByClasse($pdo,$classe);
        $statement = $pdo->prepare('SELECT d.ds_id, d.name, d.date FROM ds d WHERE matiere=:matiere AND semestre_id=:semestre_id AND classe_id=:classe_id');
        $statement->bindParam(':matiere',$matiere);
        $statement->bindParam(':semestre_id',$semestre_id);
        $statement->bindParam(':classe_id',$classe_id['classe_id']);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


//Insert info notes
    function dbInsertNotes($pdo,$note,$coef,$eleve_id,$ds_id){
        $query = 'SELECT count(*) FROM notes WHERE eleve_id =:eleve AND ds_id = :ds';
        $statement = $pdo->prepare($query);
        $statement->bindParam(':eleve',$eleve_id);
        $statement->bindParam(':ds',$ds_id);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if($result['count']==0){
            $request = 'INSERT INTO notes (notes_id,note,coeff,eleve_id,ds_id) VALUES (DEFAULT,:note,:coeff,:eleve_id,:ds_id)';
            $statement = $pdo->prepare($request);
            $statement->bindParam(':note',$note);
            $statement->bindParam(':coeff',$coef);
            $statement->bindParam(':eleve_id',$eleve_id);
            $statement->bindParam(':ds_id',$ds_id);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return true;
        }else{
            return false;
        }
    }

//Modify notes
    function dbUpdateNotes($pdo,$note,$coef,$eleve_id,$ds_id){
        $request = 'UPDATE notes SET note=:note, coeff=:coeff WHERE eleve_id=:eleve_id AND ds_id=:ds_id';
        $statement = $pdo->prepare($request);
        $statement->bindParam(':note',$note);
        $statement->bindParam(':coeff',$coef);
        $statement->bindParam(':eleve_id',$eleve_id);
        $statement->bindParam(':ds_id',$ds_id);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

?>