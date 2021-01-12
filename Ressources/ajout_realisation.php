<?php             
    /* if(isset($_FILES['fichiers']){
            Page inclut dans la page nos_realisations.php 
        }
    
    Traitement des réalisations (envoyées par le formulaire_realisation.php) 
        - On vérifie les extensions (autorisées : "jpg", "jpeg", "png", "tif", "tiff") 
        - L'identifiant de l'image est le timestamp, si il y en a plusieurs on ajoute +1 à chaque nouvelle photo ajoutée.
        - La première image traitée est l'image principale (la 1ere) du carrousel
        - L'identifiant du produit est le même pour toutes les images (du traitement) afin de les liées au produit.

        Toutes les images sont enregistrées dans Guillermain_carrousel.
        La référence pour les appelées est enregistrée dans Guillermain_produits
       */
    
    $nb_fichiers = count($_FILES['fichiers']['name']);
    $timestamp_produit = time();

    for($i= 0; $i < $nb_fichiers; $i++) {

        $name = htmlspecialchars(trim($_FILES['fichiers']['name'][$i]));
        $elementsChemin = pathinfo($name);
        $extensionFichier = strtolower($elementsChemin['extension']);
        $extensionsAutorisees = array("jpg", "jpeg", "png", "tif", "tiff");

        if(!(in_array($extensionFichier, $extensionsAutorisees))){
            exit ("Le fichier ".$name." n'a pas l'extension attendue");
        }else{  
            $timestamp = time() + $i;
            $repertoireDestination = ("../Images/Realisations/");
            $nomDestination = "realisation_".$timestamp.".".$extensionFichier;
            if(move_uploaded_file($_FILES["fichiers"]["tmp_name"][$i], $repertoireDestination.$nomDestination)){
                $file_name = "./Images/Realisations/".$nomDestination;

                $query ="   INSERT INTO Guillermain_carrousel(  Identifiant_produit,
                                                                Identifiant, 
                                                                Chemin,
                                                                Ordre) 
                            VALUES (:identifiant_produit,
                                    :identifiant, 
                                    :chemin,
                                    :ordre)";
                $req = $bdd->prepare($query);
                $req->bindValue("identifiant_produit",   $timestamp_produit,    PDO::PARAM_INT);
                $req->bindValue("identifiant",           $timestamp,            PDO::PARAM_INT);
                $req->bindValue("chemin",                $file_name,            PDO::PARAM_STR);
                $req->bindValue("ordre",                 $i,                    PDO::PARAM_INT);
                $req->execute() or die(print_r($bdd->errorInfo()));
                $req = NULL;
                            
            }else{

                exit ("Le déplacement du fichier temporaire a échoué </br> Vérifiez l'existence du répertoire ".$repertoireDestination);
            }
        }
    }
    
    $query = "  INSERT INTO Guillermain_produits(   Identifiant_page, 
                                                    Identifiant,
                                                    Nom_page, 
                                                    Nom, 
                                                    Valeur) 
                VALUES (:identifiant_page, 
                        :identifiant,
                        :nom_page, 
                        :nom, 
                        :valeur)";

    $req = $bdd->prepare($query);
    $req->bindValue("identifiant_page",      1598255400,            PDO::PARAM_INT);
    $req->bindValue("identifiant",           $timestamp_produit,    PDO::PARAM_INT);
    $req->bindValue("nom_page",              "Nos réalisations",    PDO::PARAM_STR);
    $req->bindValue("nom",                   $nom_produit,          PDO::PARAM_STR);
    $req->bindValue("valeur",                $choix_savoir_faire,   PDO::PARAM_STR);
    $req->execute() or die(print_r($bdd->errorInfo()));
    $req = NULL;
    
    echo '  <div class="alert alert-success text-center px-0 w-50 mx-auto my-4" role="alert">
                <p class="mb-0 font-weight-bold">
                    L\'ajout de la réalisation a été effectué. </br>
                    Souhaitez vous ajouter une réalisation de plus ?</br>
                </p> 
                <div>
                    <a class="btn btn-info m-3" href="index.php?page=4">Retour au tableau des réalisations</a>
                    <a class="btn btn-primary m-3" href="index.php?page=4&ajout">Ajouter une réalisation</a>
                </div>
            </div>';
            