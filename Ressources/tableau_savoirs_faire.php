<!-- Cette page est inclut dans nos_savoirs_faire.php
    Cette page contient :
                        - Un tableau avec toutes les prestations avec bouton modifier ou supprimer
                        - Possibilité de tri en cliquant sur un savoir faire
                        - Un bouton pour créer une nouvelle prestation 
-->
<div class="text-left m-4">
    <a href="index.php?page=3&Ajout" class="btn btn-outline-primary"><i class="fas fa-plus-circle"></i> Ajouter une prestation</a>
</div>

<?php
    $query = "  SELECT  Identifiant, Identifiant_sous_page, Nom_sous_page, Valeur
                FROM    Guillermain_produits
                WHERE   Nom = 'Prestation'";

    if(isset($_GET['savoir'])){

        $savoir = htmlspecialchars(trim($_GET['savoir']));
        $query  .= ' AND Identifiant_sous_page = '.$savoir.' ';
        echo '<div class="text-center my-2">
                <a href="index.php?page=3" class="btn btn-success"> Voir toutes les prestations </a>
              </div>';
    }

    $req = $bdd->prepare($query);
    $req->execute() or die(print_r($bdd->errorInfo()));

    $nom_sous_page         = [];
    $identifiant           = [];
    $prestation            = [];
    $identifiant_sous_page = [];

    while($donnees = $req->fetch()){
        array_push($nom_sous_page,         $donnees['Nom_sous_page']);
        array_push($identifiant,           $donnees['Identifiant']);
        array_push($identifiant_sous_page, $donnees['Identifiant_sous_page']);
        array_push($prestation,            $donnees['Valeur']);
    }
    
    echo '<div class="table-responsive">
            <table class="table table-striped text-center">
            <thead>
                <tr>
                    <th scope="col">Savoir faire</th>
                    <th scope="col">Prestation</th>
                    <th scope="col">Modifier</th>
                    <th scope="col">Supprimer</th>
                </tr>
            </thead>
            <tbody>';

    $nb_sous_page = count($identifiant);

    if($nb_sous_page < 1){
        echo '<tr>
                <td colspan="5">Aucune prestation n\'a été inséré ici.  </br><a class="btn btn-secondary" href="index.php?page=3&Ajout">Ajouter une prestation ?</a></td>
                </tr>'; 
    }else{

        for ($i=0; $i < $nb_sous_page; $i++) {
            echo '<tr scope="row">';    
            
            echo '  <td class="pt-4"><a class="text-dark" href="index.php?page=3&savoir='.$identifiant_sous_page[$i].'">'.$nom_sous_page[$i].'</a></td>';

            echo '  <td class="pt-4"><a class="text-dark" href="index.php?page=3&Modification='.$identifiant[$i].'">'.$prestation[$i].'</a></td>';

            echo   '<td>
                        <a href="index.php?page=3&Modification='.$identifiant[$i].'">
                            <img style="width: 40px; height:40px;" src="../Images/parametres.png" alt="Modifier" title="Modifier"/>
                        </a>
                    </td>';

            echo   '<td>
                        <a type="button" data-toggle="modal" data-target="#suppr'.$identifiant[$i].'"> 
                            <img style="width: 40px; height:40px;" src="../Images/supprimer.png" alt="Supprimer" title="Supprimer" />
                        </a>
                        <!-- Modal de confirmation pour la suppression -->
                        <div class="modal fade" id="suppr'.$identifiant[$i].'" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger">
                                        <h5 class="modal-title text-white text-uppercase">Confirmer la suppression</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mx-auto py-2">
                                            Souhaitez vous supprimer la prestation suivante :</br> 
                                            <span class="font-weight-bold">"'.$prestation[$i].'"</span> ?
                                        </p>
                                    </div>
                                    <div class="modal-footer bg-light">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Annuler</button>
                                        <a type="button" href="index.php?page=3&Suppression='.$identifiant[$i].'" class="btn btn-danger">Supprimer</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>';
        }
    }
    echo '  </tbody>
        </table>
        </div>'; 
