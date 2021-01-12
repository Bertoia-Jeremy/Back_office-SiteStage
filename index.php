<!-- Page contenant : - La structure nav et footer.
                      - Les includes
    Le système de connexion à été retiré pour plus de sécurité.-->
<?php 
    date_default_timezone_set('Europe/Paris');
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="pragma" content="no-cache" />
    <?php header("Cache-Control:no-cache"); ?>
	<link rel="stylesheet" type="text/css" href="./Ressources/style.css">
	<link rel="stylesheet" type="text/css" href="./Ressources/bootstrap.min.css">
    <title>Back-office</title>
  </head>
    <?php
        include('./Ressources/connect.php');
    ?>
    <body class="d-flex flex-column justify-content-between align-items-center bg-white">
        <div class="row mx-0 w-100">
            <header class="sticky-top w-100">
                <nav class="navbar navbar-expand-sm navbar-light bg-primary">
                    <a class="navbar-brand text-white font-weight-bold" href="index.php">Guillermain</a>

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <div class="d-sm-none pt-3"></div>
                        <div class="w-100 d-sm-flex justify-content-end">
                            <ul class="navbar-nav text-center">
                                <li class="nav-item active">
                                    <a class="nav-link text-white" href="index.php">Accueil</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Pages
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right bg-info" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item text-white" href="index.php?page=2">Qui sommes nous</a>
                                        <a class="dropdown-item text-white" href="index.php?page=3">Nos savoirs faire</a>
                                        <a class="dropdown-item text-white" href="index.php?page=4">Nos réalisations</a>
                                        <a class="dropdown-item text-white" href="index.php?page=5">Contact</a>
                                    </div>
                                </li>
                                <div class="dropdown-divider d-sm-none"></div>
                                <li class="nav-item active">
                                    <a class="nav-link text-white" href="index.php?page=8">Calendrier</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>
            
            <section class="col p-0 pb-5">            
                <div id="corps_index">
                    <?php
                        if(isset($_GET['page'])){
                            if(is_numeric($_GET['page'])){
                                $page = $_GET['page'];
                            }
                        }
                        
                        
                        if(isset($_GET['page'])){
                            if($page == 1){
                                include("./Ressources/accueil.php");
                            }elseif($page == 2){
                                include("./Ressources/qui_sommes_nous.php");
                            }elseif($page == 3){
                                include("./Ressources/nos_savoirs_faire.php");
                            }elseif($page == 4){
                                include("./Ressources/nos_realisations.php");        
                            }elseif($page == 5){
                                include("./Ressources/contact.php");
                            }elseif($page == 6){
                                include("./Ressources/nouveau_produit.php");        
                            }elseif($page == 7){
                                include("./Ressources/tableau_produit.php");
                            }elseif($page == 8){
                                include("./Ressources/calendrier.php");        
                            }else{
                                include("./Ressources/accueil.php");
                            }
                        }else{
                        include("./Ressources/accueil.php");
                        } 
                    ?>
                </div>
            </section>
        </div>

        <footer class="bg-primary text-white py-4 text-center w-100">
            Administrateur : 
            <a class="text-white" href="mailto:bertoia.jeremy@hotmail.fr">Bertoïa Jérémy</a>
        </footer>


        <script rel="stylesheet" type="text/javascript" src="./Ressources/jQuery.js"></script>
	    <script rel="stylesheet" type="text/javascript" src="./Ressources/bootstrap.min.js"></script>
    </body>
</html>