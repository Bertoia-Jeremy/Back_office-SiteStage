<!-- if(isset($_GET['ajout']) || isset($_GET['modification'])){

    }else{
        Page inclut dans la page nos_realisations.php 
    } 

    Page affichant un tableau de toutes les réalisations.
    Possibilité d'ajouter, supprimer ou modifier une réalisation.
-->

<?php
    echo '  <div class="text-left m-4">
                <a href="index.php?page=4&ajout" class="btn btn-outline-primary"><i class="fas fa-plus-circle"></i> Ajouter une réalisation</a>
            </div>';

    $query = "  SELECT Guillermain_produits.Nom, Guillermain_produits.Valeur, Guillermain_produits.Identifiant,
                        Guillermain_carrousel.Identifiant, Guillermain_carrousel.Chemin 
                FROM       Guillermain_produits
                INNER JOIN Guillermain_carrousel
                WHERE      Guillermain_produits.Identifiant_page = 1598255400
                AND        Guillermain_carrousel.Ordre = 0
                AND        Guillermain_produits.Identifiant = Guillermain_carrousel.Identifiant_produit";
    
    $req = $bdd->prepare($query);
    $req->execute() or die(print_r($bdd->errorInfo()));

    $nom_produit         = [];
    $valeur_produit      = [];
    $identifiant_produit = [];
    $identifiant_image   = [];
    $chemin_image        = [];

    while($donnees = $req->fetch()){
        array_push($nom_produit,         $donnees['Nom']);
        array_push($valeur_produit,      $donnees['Valeur']);
        array_push($identifiant_produit, $donnees['Identifiant']);
        array_push($identifiant_image,   $donnees['Identifiant']);
        array_push($chemin_image,        $donnees['Chemin']);
    }
    $req = NULL;

?>
    <div class="table-responsive">
        <table class="table table-striped text-center">
            <thead>
                <tr>
                    <th scope="col">Image principale</th>
                    <th scope="col">Lieu</th>
                    <th scope="col">Savoir-faire</th>
                    <th scope="col">Modifier</th>
                    <th scope="col">Supprimer</th>
                </tr>
            </thead>
            <tbody>

<?php
            $nb_produit = count($nom_produit);

            if($nb_produit < 1){
                echo '<tr>
                        <td colspan="5">Aucune réalisation n\'a été insérée ici.  </br><a class="btn btn-primary" href="index.php?page=4&ajout">Ajouter une réalisation ?</a></td>
                        </tr>'; 
            }else{
    
                for($i=0; $i < $nb_produit; $i++){
                    
                    if($chemin_image[$i] == NULL){
                        $image = "../Images/croix.png";
                    }else{
                        $image = ".".$chemin_image[$i];
                    }
?>
                <tr scope="row">
                    <td>
                        <a type="button" data-toggle="modal" data-target="#image_principale_<?= $identifiant_image[$i]?>"> 
                            <img style="width: 50px; height:50px;" src="<?= $image ?>">
                        </a>
                        <div class="modal fade" id="image_principale_<?= $identifiant_image[$i]?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary">
                                            <h5 class="modal-title text-white">Modification de l'image</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span class="text-white" aria-hidden="true">x</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mx-auto text-center">
                                                <p class="mb-0 font-weight-bold"> <?= $nom_produit[$i]." - ".$valeur_produit[$i] ?></p>
                                                <img src="<?= $image; ?>" class="img-fluid" style="max-height: 450px;">
                                                <p>Image actuelle</p>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light d-flex justify-content-around">
                                            <form method="POST" action="#" enctype="multipart/form-data" class="w-100">
                                                <div class="form-group my-2 text-left">
                                                    <label for="fichier" class="mb-0">
                                                        Image de la réalisation : (extensions autorisées : png, jpeg, jpg, tiff, tif)
                                                    </label></br>
                                                    <input type="file" class="form-control-file" name="fichier" id="fichier" required>
                                                    <input type="hidden" name="ID" value="<?= $identifiant_image[$i] ?>">
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
                        </td>
                        <td class="pt-4"><a class="text-dark" href="index.php?page=4&modification=<?= $identifiant_produit[$i] ?>"> <?=$nom_produit[$i]?> </a></td>
                        <td class="pt-4"><a class="text-dark" href="index.php?page=4&modification=<?= $identifiant_produit[$i] ?>"> <?=$valeur_produit[$i]?> </a></td>
                        <td>
                            <a href="index.php?page=4&modification=<?= $identifiant_produit[$i] ?>">
                                <img style="width: 40px; height:40px;" src="../Images/parametres.png" alt="Modifier" title="Modifier"/>
                            </a>
                        </td>
                        <td>
                            <a type="button" data-toggle="modal" data-target="#suppr<?= $identifiant_produit[$i] ?>"> 
                                <img style="width: 40px; height:40px;" src="../Images/supprimer.png" alt="Supprimer" title="Supprimer" />
                            </a>
                            <div class="modal fade" id="suppr<?= $identifiant_produit[$i] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger">
                                            <h5 class="modal-title text-white text-uppercase">Confirmer la suppression</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="mx-auto py-2">Souhaitez vous supprimer <span class="font-weight-bold">"<?= $nom_produit[$i]." - ".$valeur_produit[$i] ?>"</span> ?</p>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-primary" data-dismiss="modal">Annuler</button>
                                            <a type="button" href="index.php?page=4&supprimer=<?= $identifiant_produit[$i] ?>" class="btn btn-danger">Supprimer</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
<?php
                }
            }
?>
                </tbody>
            </table> 
        </div>     