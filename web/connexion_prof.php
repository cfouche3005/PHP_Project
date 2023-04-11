<DOCTYPE hmtl>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="connexion.css"/>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" >Connexion :</a>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item active">
                            <a class="nav-link" href="connexion_eleve.php">Elève</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="connexion_prof.php">Professeur</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="connexion_admin.php">Administration</a>
                        </li>
                    </ul>
                </div>
        </nav>
</header>

<div id="container">
    <div class="box" id="box1">
    </div>

    <div class="box" id="box2">
        <form>
            <!-- Email input -->
            <div class="form">
                <p>Adresse email :</p>
                <input type="email" class="taille_form" id="email" name="email" class="form-control" />
            </div>

            <!-- Password input -->
            <div class="form">
                <p>Mot de passe :</p>
                <input type="password" class="taille_form" id="pw" name="pw" class="form-control" />
            </div>

            <!-- Submit button -->
            <button type="submit">Se connecter</button>
        </form>
    </div>

    <div class="box" id="box3">
    </div>
</div>

</body>

</html>