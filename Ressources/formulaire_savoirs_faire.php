<!-- Cette page est inclut dans nos_savoirs_faire.php
    Cette page contient - Le formulaire d'ajout d'un savoir faire.
                                            (- Choisir parmis les savoirs faire) - select
                                            (- Nom de la prestation) - input type="text"
                        - Le même formulaire pré-rempli pour la modification.
-->

<div class="text-left m-4">
    <a href="index.php?page=3" class="btn btn-outline-primary"><i class="far fa-arrow-alt-circle-left"></i> Retour au tableau des prestations</a>
</div>

<?php
    if(isset($_GET['Modification'])){
        $identifiant_produit = htmlspecialchars(trim($_GET['Modification']));

        $query = "  SELECT  Nom_sous_page, Valeur
                    FROM    Guillermain_produits
                    WHERE   Identifiant = :identifiant";
        
        $req = $bdd->prepare($query);
        $req->bindValue("identifiant", $identifiant_produit, PDO::PARAM_INT);
        $req->execute() or die(print_r($bdd->errorInfo()));
        $donnees = $req->fetch();

        $nom_savoir_faire = $donnees['Nom_sous_page'];
        $prestation       = $donnees['Valeur'];
    }
?>

<section class="container">
    <div class="row">
        <form method='POST' action="index.php?page=3" class="px-3 my-4 mx-auto text-secondary w-100">

            <h4 class="text-dark mb-3"><u>
                <?php if(isset($_GET['Modification'])){ echo "Modification"; }else{ echo "Ajout"; } ?>
            d'une prestation :</u></h4>

            <div class="form-group">
                <label for="choix_savoir_faire">Choisir parmi les savoirs-faire : </label>
                <select name="choix_savoir_faire" id="choix_savoir_faire" class="form-control">

                    <?php
                        $query = "  SELECT Nom, Identifiant
                                    FROM Guillermain_sous_pages" ;

                        $req = $bdd->prepare($query);
                        $req->execute() or die(print_r($bdd->errorInfo()));

                        while($donnees = $req->fetch()){

                            if(isset($_GET['Modification']) AND $donnees['Nom'] == $nom_savoir_faire){
                                echo '<option value="'.$donnees['Identifiant'].'" selected>'.$donnees['Nom'].'</option>';
                            }else{
                                echo '<option value="'.$donnees['Identifiant'].'">'.$donnees['Nom'].'</option>';
                            }

                        } 

                        $req = NULL;
                    ?>              

                </select>
            </div>  
            
            <div class="form-group">
                <label for="nom_prestation">Nom de la prestation :</label><br/>
                <textarea type="text" name="nom_prestation" id="nom_prestation" required class="form-control"
                ><?php 
                    if(isset($_GET['Modification'])){
                        echo $prestation;
                    }else{ 
                        echo ""; }
                ?></textarea>
            </div>  
<?php
                
    if(isset($_GET['Modification'])){
        echo '<input type="hidden" name="modification" value="'.$identifiant_produit.'">';
    }else{
        echo '<input type="hidden" name="ajout">';
    }

?>
            <button type="submit" class="btn btn-primary d-block mx-auto my-2">Valider</button>  
        </form>
    </div>

</section>