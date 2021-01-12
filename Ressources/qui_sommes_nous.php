<!-- La page qui_sommes_nous.php contient la possibilité de changer : - Le texte d'accueil qui présente l'entreprise
                                                                      - Le logo du site
    Ces 2 entrées sont intégrées dans la table Guillermain_produits.
 -->

<div class="text-center my-5">
    <h2 class="text-primary m-0 p-0">Qui sommes nous ?</h2>
</div>
<?php 
    /* --- Formulaire de MODIFICATION du texte --- */
    if(isset($_GET['modification'])){
        echo '  <div class="text-left m-4">
                    <a href="index.php?page=2" class="btn btn-outline-primary"><i class="far fa-arrow-alt-circle-left"></i> Retour</a>
                </div>';

        $modification = htmlspecialchars(trim($_GET['modification']));

        $query = "  SELECT Valeur, Identifiant 
                    FROM   Guillermain_produits 
                    WHERE  Identifiant = :identifiant";
        
        $req = $bdd->prepare($query);
        $req->bindValue("identifiant", $modification, PDO::PARAM_INT);
        $req->execute() or die(print_r($bdd->errorInfo()));
        $donnees = $req->fetch();
        
        echo '  <div class="container">
                    <h4 class="text-secondary align-self-start"><u>Modification du texte d\'accueil :</u></h4>
                    <form method="POST" action="index.php?page=2" class="mt-3 mb-5">
                        <textarea name="texte" class="w-100" style="min-height: 250px;">'.$donnees['Valeur'].'</textarea>
                        <input type="hidden" name="identifiant" value="'.$donnees['Identifiant'].'">
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary my-3">Valider le texte</button>
                        </div>
                    </form>
                </div>';
    }else{
        /* --- MODIFICATION du texte --- */
        if(isset($_POST['texte']) && isset($_POST['identifiant'])){
            $texte       = htmlspecialchars(trim($_POST['texte']));

            $identifiant = htmlspecialchars(trim($_POST['identifiant']));

            $query = "  UPDATE Guillermain_produits 
                        SET    Valeur      = :valeur 
                        WHERE  Identifiant = :identifiant";

            $req = $bdd->prepare($query);
            $req->bindValue("valeur",       $texte,      PDO::PARAM_STR);
            $req->bindValue("identifiant", $identifiant, PDO::PARAM_INT);
            $req->execute() or die(print_r($bdd->errorInfo()));

            echo '<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                    Texte modifié !
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';

        /* --- MODIFICATION du logo --- */
        }elseif(isset($_FILES['fichier']) && isset($_POST['identifiant'])){
            $identifiant = htmlspecialchars(trim($_POST['identifiant']));
            $timestamp = time();

            $name                 = $_FILES['fichier']['name'];
            $elementsChemin       = pathinfo($name);
            $extensionFichier     = strtolower($elementsChemin['extension']);
            $extensionsAutorisees = array("jpg", "jpeg", "png", "tif", "tiff");

            if(!(in_array($extensionFichier, $extensionsAutorisees))){
                exit ("Le fichier ".$name." n'a pas l'extension attendue");
            }else{   
                $repertoireDestination = ("../Images/");
                $nomDestination = "logo_".$timestamp.".".$extensionFichier;
                if(move_uploaded_file($_FILES["fichier"]["tmp_name"], $repertoireDestination.$nomDestination)){
                    $file_name = "./Images/".$nomDestination;

                    $query ="   UPDATE Guillermain_produits 
                                SET    Valeur      = :valeur
                                WHERE  Identifiant = :identifiant";

                    $req = $bdd->prepare($query);
                    $req->bindValue("identifiant", $identifiant, PDO::PARAM_INT);
                    $req->bindValue("valeur",      $file_name,   PDO::PARAM_STR);
                    $req->execute() or die(print_r($bdd->errorInfo()));
                    $req = NULL;

                    echo '<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                            Logo modifié !
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
                                
                }else{
                    exit ("Le déplacement du fichier temporaire a échoué </br> Vérifiez l'existence du répertoire ".$repertoireDestination);
                }
            }

        }
?>
        <section class="row align-items-stretch justify-content-center mx-0 my-5">
            <?php
                $query = "  SELECT Nom, Valeur, Identifiant 
                            FROM   Guillermain_produits 
                            WHERE  Identifiant_page = 1598255290 " ;
                            
                $req = $bdd->prepare($query);
                $req->execute() or die(print_r($bdd->errorInfo()));

                // Modal avec le formulaire pour modifier l\'image (logo)
                while($donnees = $req->fetch()){
                    echo '<div class="col-sm-12 col-md-6 col-lg-5 my-4 text-center d-flex flex-column justify-content-between ">';

                    if($donnees['Nom'] == "Logo"){
                        echo '  <h4 class="text-secondary align-self-start"><u>Logo du site :</u></h4>
                                <a type="button" data-toggle="modal" data-target="#image_logo_'.$donnees['Identifiant'].'"> 
                                    <img class="w-75 shadow" src=".'.$donnees['Valeur'].'">
                                </a>
                                
                                <div class="modal fade" id="image_logo_'.$donnees['Identifiant'].'" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary">
                                                <h5 class="modal-title text-white">Modification du logo</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span class="text-white" aria-hidden="true">x</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mx-auto text-center">
                                                    <p class="mb-0 font-weight-bold">Logo Guillermain</p>
                                                    <img src=".'.$donnees['Valeur'].'" class="img-fluid" style="max-height: 450px;">
                                                    <p>Image actuelle</p>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light d-flex justify-content-around">
                                                <form method="POST" action="#" enctype="multipart/form-data" class="w-100">
                                                    <div class="form-group my-2 text-left">
                                                        <label for="fichier" class="mb-0">
                                                            Logo : (extensions autorisées : png, jpeg, jpg, tiff, tif)
                                                        </label></br>
                                                        <input type="file" class="form-control-file" name="fichier" id="fichier" required>
                                                        <input type="hidden" name="identifiant" value="'.$donnees['Identifiant'].'">
                                                    </div>
                                                    <div class="d-flex justify-content-end">
                                                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Annuler</button>
                                                        <button type="submit" href="#" class="btn btn-primary">Valider</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <a type="button" data-toggle="modal" data-target="#image_logo_'.$donnees['Identifiant'].'"> 
                                    <button class="btn btn-secondary mt-4">Changer le logo</button>
                                </a>';
                    }else{
                        echo '  <h4 class="text-secondary align-self-start"><u>Texte accueil :</u></h4>
                                <p class="text-left">'.$donnees['Valeur'].'</p>
                                <a href="index.php?page=2&modification='.$donnees['Identifiant'].'">
                                    <button class="btn btn-secondary">Modifier le texte d\'accueil</button>
                                </a>';
                    }
                    echo ' </div>';
                }
                $req = NULL;
            ?>       
        </section>
<?php
    }



