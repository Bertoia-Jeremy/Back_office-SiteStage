<!-- Cette page contient tout ce qui a un lien avec les réalisations. Elle est divisée en plusieurs pages qui sont nommées explicitement :
        - formulaire_realisation.php + ordre_image.js
        - ajout_realisation.php
        - tableau_realisation.php
    Cette page gère le changement de l'ordre des images, la modification d'une image 
    ainsi que l'affichage des différents messages confirmant que l'action demandée à été réalisée
-->
<div class="text-center my-5">
    <h1 class="text-primary m-0 p-0">Nos réalisations</h1>
</div>

<?php
    if(isset($_POST['choix_savoirs_faire']) AND isset($_POST['nom_produit'])){
        $choix_savoir_faire = htmlspecialchars(trim($_POST['choix_savoirs_faire']));
        $nom_produit        = htmlspecialchars(trim($_POST['nom_produit']));

        if($choix_savoir_faire != NULL && $nom_produit != NULL){

            if(isset($_GET['modification'])){
                $modification = htmlspecialchars(trim($_GET['modification']));

                $query = "  UPDATE  Guillermain_produits 
                            SET     Nom = :nom, Valeur = :valeur 
                            WHERE   Identifiant = :identifiant";

                $req = $bdd->prepare($query);
                $req->bindValue("nom",          $nom_produit,        PDO::PARAM_STR);
                $req->bindValue("valeur",       $choix_savoir_faire, PDO::PARAM_STR);
                $req->bindValue("identifiant",  $modification,       PDO::PARAM_INT);
                $req->execute() or die(print_r($bdd->errorInfo()));

                echo '<script>window.location = "index.php?page=4&modification='.$modification.'&ok";</script>';

            }elseif(isset($_FILES['fichiers'])){
                include ('ajout_realisation.php');

            }
        }

    }else{
        if(isset($_GET['ajout']) || isset($_GET['modification'])){
            echo '  <div class="text-left m-4">
                        <a href="index.php?page=4" class="btn btn-outline-primary"><i class="far fa-arrow-alt-circle-left"></i> Retour au tableau des réalisations</a>
                    </div>';

            if(isset($_GET['modification'])){

                $modification = htmlspecialchars(trim($_GET['modification']));

                /* Vérification pour afficher les différentes alertes */
                if(isset($_GET['ok'])){
                    echo '  <div class="alert alert-success alert-dismissible fade show text-center px-0 w-50 mx-auto my-4" role="alert">
                                Modification du savoir-faire et/ou du lieu, effectuée.
                                <button type="button" class="close" data-dismiss="alert" aria-label=« Close »>
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>';

                }elseif(isset($_GET['ok2'])){
                    echo '  <div class="alert alert-success alert-dismissible fade show text-center px-0 w-50 mx-auto my-4" role="alert">
                                Modification de l\'ordre des images, effectuée.
                                <button type="button" class="close" data-dismiss="alert" aria-label=« Close »>
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>';
                
                }elseif(isset($_GET['ok3'])){
                    echo '  <div class="alert alert-success alert-dismissible fade show text-center px-0 w-50 mx-auto my-4" role="alert">
                                Suppression de l\'image effectuée.
                                <button type="button" class="close" data-dismiss="alert" aria-label=« Close »>
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>';

                }elseif(isset($_GET['ok4'])){
                    echo '  <div class="alert alert-success alert-dismissible fade show text-center px-0 w-50 mx-auto my-4" role="alert">
                                Modification de l\'image effectuée.
                                <button type="button" class="close" data-dismiss="alert" aria-label=« Close »>
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>';

                }elseif(isset($_GET['ok5'])){
                    if(is_numeric($_GET['ok5'])){
                        echo '  <div class="alert alert-success alert-dismissible fade show text-center px-0 w-50 mx-auto my-4" role="alert">
                                    Ajout de '.$_GET['ok5'].' image(s) effectué.
                                    <button type="button" class="close" data-dismiss="alert" aria-label=« Close »>
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>';
                    }
                }elseif(isset($_FILES['fichiers']) && isset($_POST['ajout_image'])){
                    
                    $nb_fichiers = count($_FILES['fichiers']['name']);

                    $query = "  SELECT Ordre
                                FROM   Guillermain_carrousel 
                                WHERE  Identifiant_produit = :identifiant_produit
                                ORDER BY Ordre DESC";

                    $req = $bdd->prepare($query);
                    $req->bindValue("identifiant_produit", $modification, PDO::PARAM_INT);
                    $req->execute() or die(print_r($bdd->errorInfo()));
                    $donnees = $req->fetch();
                    $req = NULL;

                    $ordre = $donnees['Ordre'];
                    $ordre++;

                    for($i= 0; $i < $nb_fichiers; $i++) {
                        $name = $_FILES['fichiers']['name'][$i];
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
                                $req->bindValue("identifiant_produit",   $modification,         PDO::PARAM_INT);
                                $req->bindValue("identifiant",           $timestamp,            PDO::PARAM_INT);
                                $req->bindValue("chemin",                $file_name,            PDO::PARAM_STR);
                                $req->bindValue("ordre",                 ($ordre + $i),         PDO::PARAM_INT);
                                $req->execute() or die(print_r($bdd->errorInfo()));
                                $req = NULL;
                                            
                            }else{

                                exit ("Le déplacement du fichier temporaire a échoué </br> Vérifiez l'existence du répertoire ".$repertoireDestination);
                            }
                        }
                                    
                    }
                    
                    echo '<script>window.location = "index.php?page=4&modification='.$modification.'&ok5='.$nb_fichiers.'";</script>';

                //Fin vérification des alertes vérification. Vérification si il y a un changement d'image, d'ordre ou une suppression
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
                        $nomDestination = "realisation_".$timestamp.".".$extensionFichier;
                        if(move_uploaded_file($_FILES["fichier"]["tmp_name"], $repertoireDestination.$nomDestination)){
                            $file_name = "./Images/".$nomDestination;

                            $query = "  UPDATE  Guillermain_carrousel 
                                        SET     Chemin      = :chemin
                                        WHERE   Identifiant = :identifiant";

                            $req = $bdd->prepare($query);
                            $req->bindValue("identifiant", $identifiant, PDO::PARAM_INT);
                            $req->bindValue("chemin",      $file_name,   PDO::PARAM_STR);
                            $req->execute() or die(print_r($bdd->errorInfo()));
                            $req = NULL;

                            echo '<script>window.location = "index.php?page=4&modification='.$modification.'&ok4";</script>';
                        }

                        exit ("Le déplacement du fichier temporaire a échoué </br> Vérifiez l'existence du répertoire ".$repertoireDestination);  
                    }

                }elseif(isset($_POST['changement_ordre'])){
                    $query = "  SELECT Identifiant
                                FROM   Guillermain_carrousel 
                                WHERE  Identifiant_produit = :identifiant_produit";

                    $req = $bdd->prepare($query);
                    $req->bindValue("identifiant_produit", $modification, PDO::PARAM_INT);
                    $req->execute() or die(print_r($bdd->errorInfo()));
                    $donnees = $req->fetchAll();
                    $req = NULL;
                    
                    $nb_identifiant = count($donnees);
                    
                    for ($i=0; $i < $nb_identifiant; $i++) { 

                        $identifiant = $donnees[$i]['Identifiant'];

                        if(is_numeric($_POST[$identifiant])){

                            $query = "  UPDATE Guillermain_carrousel 
                                        SET    Ordre       = :ordre
                                        WHERE  Identifiant = :identifiant";

                            $req = $bdd->prepare($query);
                            $req->bindValue("identifiant", $identifiant,         PDO::PARAM_INT);
                            $req->bindValue("ordre",       $_POST[$identifiant], PDO::PARAM_INT);
                            $req->execute() or die(print_r($bdd->errorInfo()));
                            $req = NULL;
                        }
                    
                        echo '<script>window.location = "index.php?page=4&modification='.$modification.'&ok2";</script>';

                    }
                    
                }elseif(isset($_GET['supprimer'])){
                    $suppression = htmlspecialchars(trim($_GET['supprimer']));

                    $query = "DELETE FROM Guillermain_carrousel WHERE Identifiant = :identifiant";

                    $req = $bdd->prepare($query);
                    $req->bindValue("identifiant", $suppression, PDO::PARAM_INT);
                    $req->execute() or die(print_r($bdd->errorInfo()));
                    $req = NULL;

                    echo '<script>window.location = "index.php?page=4&modification='.$modification.'&ok3";</script>';
                }//Fin Vérification si il y a un changement d'image, d'ordre ou une suppression
                
                $query = "  SELECT Nom, Valeur, Identifiant 
                            FROM   Guillermain_produits 
                            WHERE  Identifiant = :identifiant";

                $req = $bdd->prepare($query);
                $req->bindValue("identifiant", $modification, PDO::PARAM_INT);
                $req->execute() or die(print_r($bdd->errorInfo()));
                $donnees = $req->fetch();

                $identifiant_produit = $donnees['Identifiant'];
                $nom_savoir_faire    = $donnees['Valeur'];
                $lieu                = $donnees['Nom'];
                $req = NULL;

            }//FIN if(isset($_GET['modification'])){

            include ('formulaire_realisation.php');

        }else{ //if(isset($_GET['ajout']) || isset($_GET['modification'])){
            if(isset($_GET['supprimer'])){

                $suppression = htmlspecialchars(trim($_GET['supprimer']));

                $query = "  DELETE FROM Guillermain_carrousel 
                            WHERE       Identifiant_produit = :identifiant_produit";

                $req = $bdd->prepare($query);
                $req->bindValue("identifiant_produit", $suppression, PDO::PARAM_INT);
                $req->execute() or die(print_r($bdd->errorInfo()));
                    
                $query = "  DELETE FROM Guillermain_produits 
                            WHERE Identifiant = :identifiant";
                
                $req = $bdd->prepare($query);
                $req->bindValue("identifiant", $suppression, PDO::PARAM_INT);
                $req->execute() or die(print_r($bdd->errorInfo()));

                echo '<script>window.location = "index.php?page=4&ok3";</script>';

            }elseif(isset($_GET['ok3'])){
                echo '  <div class="alert alert-success alert-dismissible fade show text-center px-0 w-50 mx-auto my-4" role="alert">
                            Suppression de la réalisation effectuée.
                            <button type="button" class="close" data-dismiss="alert" aria-label=« Close »>
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';   
            }

            include ('tableau_realisations.php');
        }//Fin du else => if(isset($_GET['ajout']) || isset($_GET['modification'])){
    }//Fin du else => if(isset($_POST['choix_savoirs_faire']) AND isset($_POST['nom_produit'])){
