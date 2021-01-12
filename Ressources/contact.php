<!-- Cette page contient un formulaire désactivé, une fois appuyé sur le bouton Modifier les inputs de celui ci sont activés pour pouvoir
    être modifié. Les coordonnées peuvent seulement être modifiées pas supprimées ni ajoutées.
    (Elles sont enregistrées dans Guillermain_produits) -->

<div class="text-center my-5">
    <h2 class="text-primary m-0 p-0">Contact</h2>
</div>

<section class="container">
    <?php
        if(isset($_POST['Téléphone']) && isset($_POST['E-mail']) && isset($_POST['Adresse']) && isset($_POST['Horaires'])){
            $contact = ["Téléphone", "E-mail", "Adresse", "Horaires"];

            foreach ($contact as $value) {
                $post_value = htmlspecialchars(trim($_POST[$value]));

                $query = "  UPDATE Guillermain_produits 
                            SET    Valeur = :valeur 
                            WHERE  Nom    = :nom";

                $req = $bdd->prepare($query);
                $req->bindValue("valeur", $post_value, PDO::PARAM_STR);
                $req->bindValue("nom",    $value,      PDO::PARAM_STR);
                $req->execute() or die(print_r($bdd->errorInfo()));
            }
            echo '<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                    Contact modifié !
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        }

        $query = "  SELECT Nom, Valeur, Identifiant 
                    FROM   Guillermain_produits 
                    WHERE  Identifiant_page = 1598255450" ;
                    
        $req = $bdd->prepare($query);
        $req->execute() or die(print_r($bdd->errorInfo()));

        if(isset($_GET['modification'])){
            echo '  <div class="text-left m-4">
                        <a href="index.php?page=5" class="btn btn-outline-primary"><i class="far fa-arrow-alt-circle-left"></i> Retour</a>
                    </div>';
            $disabled = "";
        }else{
            $disabled = "disabled = disabled";
        }

        echo '<form method="POST" action="index.php?page=5" class="mx-auto mb-5">';

        while($donnees = $req->fetch()){
            echo '<div class="form-group w-75 mx-auto">
                    <label for="'.$donnees['Nom'].'" class="text-secondary">'.$donnees['Nom'].'</u></label>
                    <input type="text" name="'.$donnees['Nom'].'" id="'.$donnees['Nom'].'" value="'.$donnees['Valeur'].'" class="form-control" '.$disabled.'>
                  </div>';
        }

        echo '<div class="text-center">';
                
                if(isset($_GET['modification'])){
                    echo ' <a href="index.php?page=5" class="btn btn-secondary mr-2">Annuler</a>
                           <button type="submit" class="btn btn-primary">Valider le changement</button>';
                }else{
                    echo '<a href="index.php?page=5&modification" class="btn btn-secondary">Modifier</a>';
                }

        echo '</div>
        </form>';
    ?>       
</section>
