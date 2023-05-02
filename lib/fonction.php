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

    function redirect($url, $statusCode = 303){
    header('Location: ' . $url, true, $statusCode);
    die();
    }

    //Get sessions and permissions
    function dbGetSessionID($pdo){
        $request = 'SELECT sessions_ID from connexions where sessions_ID = :sessions_ID'; 
        $statement = $pdo->prepare($request);
        $statement->bindParam(':sessions_ID',$_SESSION['sessions_ID']);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    function checkSessionID($pdo){
        $session = dbGetSessionID($pdo);
        if($session['sessions_ID'] == false || $session['sessions_ID'] == null || $session['sessions_ID'] != $_COOKIE['PHPSESSID']){
            redirect('../../unauthorized.php',401);
        }
        else{
            checkPermission($pdo);
        }
            
    }

    function dbGetPermission($pdo){
        $request = 'SELECT sessions_PERM from connexions where sessions_ID = :sessions_ID'; 
        $statement = $pdo->prepare($request);
        $statement->bindParam(':sessions_ID',$_SESSION['sessions_ID']);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    function checkPermission($pdo){
        $permission = dbGetPermission($pdo);
        if($permission['sessions_PERM'] != $_SESSION['sessions_PERM']){
            redirect('../../forbidden.php',403);
        }            
    }

    function dbCreateSessions($pdo,$perm){
        $_SESSION['sessions_ID'] = $_COOKIE['PHPSESSID'];
        $_SESSION['sessions_PERM'] = $perm;
        $request = 'INSERT INTO connexions (sessions_ID,sessions_PERM) VALUES (:sessions_ID,:sessions_PERM)';
        $statement = $pdo->prepare($request);
        $statement->bindParam(':sessions_ID',$_COOKIE['PHPSESSID']);
        $statement->bindParam(':sessions_PERM',$perm);
        $statement->execute();
    }


?>