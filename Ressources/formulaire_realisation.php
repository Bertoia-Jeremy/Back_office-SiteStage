<!-- Page contenant le formulaire d'ajout et de modifiation
    L'ajout est un formulaire simple - Choix savoir faire
                                     - Lieu de la réalisation
                                     - Input file pour l'image principale
                                     - Input files pour toutes les autres

    La modification permet de :  - Changer le savoir faire
                                 - Changer le lieu de la réalisation
                                 - Ajouter une (ou des) image(s)
                                 - Voir les différentes images de la réalisations
                                 - Modifier une image
                                 - Supprimer une image
                                 - Changer l'ordre des images
    Afin de changer l'ordre des images il m'a fallu "tricher" en utilisant javascript
        Un modal est affiché lorsque l'on clique sur une image afin de la modifier, le modal comprend un formulaire.
        Ne pouvant faire une formulaire dans un formulaire j'ai utilisé un évènement javascript afin de récupérer la valeur de l'input
        number en dessous de chaque photo.
        Le script ordre_image est donc inclut à la fin de la page pour appliquer l'explication ci dessus.
-->
<section class="container">
    <div class="row">
        <form method='POST' action="#" enctype="multipart/form-data" class="px-3 my-4 mx-auto text-secondary" multiple>

            <h4 class="text-dark mb-3"><u>
                <?php if(isset($_GET['modification'])){ echo "Modification"; }else{ echo "Ajout"; } ?>
            d'une réalisation :</u></h4>

            <div class="form-group">
                <label for="choix_savoirs_faire">Choisir parmi les savoirs-faire : </label>
                <select name="choix_savoirs_faire" id="choix_savoirs_faire" class="form-control">
                    <?php
                        
                        $query = "  SELECT Nom, Identifiant 
                                    FROM Guillermain_sous_pages" ;

                        $req = $bdd->prepare($query);
                        $req->execute() or die(print_r($bdd->errorInfo()));

                        while($donnees = $req->fetch()){

                            if(isset($_GET['modification']) AND $donnees['Nom'] == $nom_savoir_faire){
                                echo '<option value="'.$donnees['Nom'].'" selected>'.$donnees['Nom'].'</option>';
                            }else{
                                echo '<option value="'.$donnees['Nom'].'">'.$donnees['Nom'].'</option>';
                            }

                        } 
                        
                        $req = NULL;
                    ?>              
                </select>
            </div>  
            
            <div class="form-group">
                <label for="nom_produit">Lieu de la réalisation :</label><br/>
                <input type="text" name="nom_produit" id="nom_produit" required class="form-control"
                value="<?php 
                            if(isset($_GET['modification'])){
                                echo $lieu;
                            }else{ 
                                echo ""; }
                        ?>">
            </div>  

            <?php
                /* Si on modifie une réalisation on va chercher toutes les images de celle-ci pour les afficher */
                if(isset($_GET['modification'])){
                    echo '      <button type="submit" class="btn btn-primary d-block mx-auto my-2">Valider les informations</button>  
                            </form>
                            </div>
                            <h4 class="text-dark"><u>Modification des images du carrousel :</u></h4>
                            <button class="btn btn-secondary my-2" type="button" data-toggle="collapse" data-target="#ajouter_realisation" 
                                                aria-expanded="false" aria-controls="ajouter_realisation"><i class="fas fa-plus-circle">
                                </i> Ajouter une (ou plusieurs) image(s)
                            </button>
                            <div class="collapse" id="ajouter_realisation">
                            <div class="card card-body">
                                <form method="POST" action="#" enctype="multipart/form-data" class="w-100">

                                    <div class="form-group my-2 text-left">
                                        <label for="fichier" class="mb-0">
                                            Image(s) : (extensions autorisées : png, jpeg, jpg, tiff, tif)
                                        </label></br>
                                        <input type="file" name="fichiers[]" multiple="multiple">
                                        <input type="hidden" name="ajout_image">
                                    </div>

                                    <button type="submit" href="#" class="btn btn-primary btn-block mt-4">Ajouter</button>
                                </form>
                            </div>
                            </div>
                            <p class="text-center text-danger mt-4"> L\'image ayant l\'ordre 0 est l\'image principale (la 1ère image affichée) du carrousel.</p>';

                    $query = "  SELECT Identifiant, Identifiant_produit, Chemin, Ordre 
                                FROM   Guillermain_carrousel 
                                WHERE  Identifiant_produit = :identifiant_produit
                                ORDER BY Ordre ASC";

                    $req = $bdd->prepare($query);
                    $req->bindValue("identifiant_produit", $modification, PDO::PARAM_INT);
                    $req->execute() or die(print_r($bdd->errorInfo()));

                    echo '<div class="row mx-0">';
                    while($donnees = $req->fetch()){
                                    //Image Cliquable
                        echo ' <div class="card p-0 my-3 col-md-5 col-sm-11 mx-auto shadow">
                                    <a type="button" data-toggle="modal" data-target="#image_'.$donnees['Identifiant'].'" class="rounded-top text-center"> 
                                        <img src=".'.$donnees['Chemin'].'" alt="" class="img-fluid rounded-top" style="max-height: 350px;">
                                    </a>';
                                        //Modal pour modification d\'image
                        echo '      <div class="modal fade" id="image_'.$donnees['Identifiant'].'" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="modal-title text-white">Modification de l\'image</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span class="text-white" aria-hidden="true">x</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mx-auto text-center d-flex flex-column justify-content-center align-items-center">
                                                        <p class="mb-0 font-weight-bold"> Réalisation : "'.$lieu.'"</p>
                                                        <img src=".'.$donnees['Chemin'].'" class="img-fluid" style="max-height: 450px;">
                                                        <a class="btn btn-danger mt-3 text-white" "button" data-toggle="modal" data-target="#suppr_'.$donnees['Identifiant'].'"> 
                                                            Supprimer l\'image
                                                        </a>
                                                        <!-- Modal de confirmation pour la suppression -->
                                                        <div class="modal fade" id="suppr_'.$donnees['Identifiant'].'" tabindex="-1" role="dialog" aria-hidden="true">
                                                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header bg-danger">
                                                                        <h5 class="modal-title text-white text-uppercase">Confirmer la suppression</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p class="mx-auto py-2">Souhaitez vous supprimer cette image ?</p>
                                                                    </div>
                                                                    <div class="modal-footer bg-light">
                                                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Annuler</button>
                                                                        <a type="button" href="index.php?page=4&modification='.$modification.'&supprimer='.$donnees['Identifiant'].'" class="btn btn-danger">
                                                                            Supprimer
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light d-flex justify-content-around">
                                                    <form method="POST" action="#" enctype="multipart/form-data" class="w-100">
                                                        <div class="form-group my-2 text-left">
                                                            <label for="fichier" class="mb-0">
                                                                Image : (extensions autorisées : png, jpeg, jpg, tiff, tif)
                                                            </label></br>
                                                            <input type="file" class="form-control-file" name="fichier" required>
                                                            <input type="hidden" name="identifiant" value="'.$donnees['Identifiant'].'">
                                                        </div>
                                                        <div class="d-flex justify-content-end">
                                                            <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Annuler</button>
                                                            <button type="submit" href="#" class="btn btn-primary">Modifier l\'image</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                        echo '      <div class="card-body d-flex justify-content-center align-items-end pb-1">
                                        <div class="my-2">
                                            <label class="mr-2">Ordre de l\'image :</label>
                                            <input type="number" name="'.$donnees['Identifiant'].'" min="0" value="'.$donnees['Ordre'].'" class="text-center input_ordre" style="width: 50px;">
                                        </div>
                                    </div>
                                </div>';
                    }
                    echo '</div>
                            <div class="text-center my-4">
                                <form method="POST" action="index.php?page=4&modification='.$modification.'" id="form_javascript">
                                    <button class="btn btn-primary btn-lg" id="bouton_submit">Valider l\'ordre des images</button>
                                </form>
                            </div>';
                    $req = NULL;

?>
                    <!-- On inclut le script pour faire fonctionner le formulaire pour l'ordre des images -->
                    <script rel="stylesheet" type="text/javascript" src="./Ressources/ordre_image.js"></script>
<?php
                /* Sinon un simple formulaire d'ajout est affiché */    
                }else{

                    echo '<div class="form-group my-3">
                            <label for="fichier" class="mb-0">Image <u>principale</u> de la réalisation : (extensions autorisées : png, jpeg, jpg, tiff, tif) </label></br>
                            <input type="file" name="fichiers[]" required>
                        </div>

                        <div class="form-group my-3">
                            <label for="fichier" class="mb-0">Autres images de la réalisation (optionnel) : (extensions autorisées : png, jpeg, jpg, tiff, tif) </label></br>
                            <input type="file" name="fichiers[]" multiple="multiple">
                        </div>
                        
                        <button type="submit" class="btn btn-primary d-block mx-auto my-2">Valider</button>  
                    </form>
                  </div>';

                }
?>

</section>

