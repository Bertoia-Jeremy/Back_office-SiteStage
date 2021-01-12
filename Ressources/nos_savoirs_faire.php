<!-- Les savoirs faire (ici cela concerne les types de prestations pas les savoirs faire comme le carrelage, peinture...) 
    peuvent être modifiés, supprimés ou ajoutés. 
    Ils sont visibles dans la présentation de chacun sur le site, dans le texte en bas à gauche. Ils sont la liste des types de prestations
    proposées par l'entreprise. 
    Cette page contient :
                        -formulaire_savoirs_faire.php
                        - tableau_savoirs_faire.php
                            
    Cette page gère :
                    - L'ajout des prestations
                    - La modification des prestations
                    - La suppression des prestations
                    - L'affichage des différents messages confirmant que l'action demandée à été réalisée
-->

<div class="text-center my-5">
    <h1 class="text-primary m-0 p-0">Nos savoirs faire</h1>
</div>

<?php
    if(isset($_GET['Ajout']) OR isset($_GET['Modification'])){

        include ('formulaire_savoirs_faire.php');

    }elseif(isset($_GET['Suppression'])){

        $suppression = htmlspecialchars(trim($_GET['Suppression']));

        $query = 'DELETE FROM Guillermain_produits WHERE Identifiant = :identifiant';

        $req = $bdd->prepare($query);
        $req->bindValue("identifiant", $suppression, PDO::PARAM_INT);
        $req->execute() or die(print_r($bdd->errorInfo()));

        echo '<script>window.location = "index.php?page=3&ok3";</script>';

    }elseif(isset($_POST['ajout']) AND isset($_POST['nom_prestation']) AND isset($_POST['choix_savoir_faire'])){
        
        $nom_prestation             = htmlspecialchars(trim($_POST['nom_prestation']));
        $identifiant_savoir_faire   = htmlspecialchars(trim($_POST['choix_savoir_faire']));

        $query = "  SELECT  Nom
                    FROM    Guillermain_sous_pages
                    WHERE   Identifiant = :identifiant";
        
        $req = $bdd->prepare($query);
        $req->bindValue("identifiant", $identifiant_savoir_faire, PDO::PARAM_INT);
        $req->execute() or die(print_r($bdd->errorInfo()));

        $donnees = $req->fetch();
        $savoir_faire = $donnees['Nom'];

        $query = "  INSERT INTO Guillermain_produits(   Identifiant_page, 
                                                        Identifiant_sous_page, 
                                                        Identifiant, 
                                                        Nom_page, 
                                                        Nom_sous_page, 
                                                        Nom, 
                                                        Valeur) 
                    VALUES (:identifiant_page, 
                            :identifiant_sous_page, 
                            :identifiant, 
                            :nom_page, 
                            :nom_sous_page, 
                            :nom, 
                            :valeur)";

        $req = $bdd->prepare($query);
        $req->bindValue("identifiant_page",      1598255300,                PDO::PARAM_INT);
        $req->bindValue("identifiant_sous_page", $identifiant_savoir_faire, PDO::PARAM_INT);
        $req->bindValue("identifiant",           time(),                    PDO::PARAM_INT);
        $req->bindValue("nom_page",              "Nos savoirs faire",       PDO::PARAM_STR);
        $req->bindValue("nom_sous_page",         $savoir_faire,             PDO::PARAM_STR);
        $req->bindValue("nom",                   "Prestation",              PDO::PARAM_STR);
        $req->bindValue("valeur",                $nom_prestation,           PDO::PARAM_STR);
        $req->execute() or die(print_r($bdd->errorInfo()));

        echo '<script>window.location = "index.php?page=3&ok2";</script>';

    }elseif(isset($_POST['modification']) AND isset($_POST['nom_prestation']) AND isset($_POST['choix_savoir_faire'])){
        
        $nom_prestation           = htmlspecialchars(trim($_POST['nom_prestation']));
        $identifiant_savoir_faire = htmlspecialchars(trim($_POST['choix_savoir_faire']));
        $identifiant              = htmlspecialchars(trim($_POST['modification']));

        $query = "  SELECT  Nom
                    FROM    Guillermain_sous_pages
                    WHERE   Identifiant = :identifiant";
        
        $req = $bdd->prepare($query);
        $req->bindValue("identifiant", $identifiant_savoir_faire, PDO::PARAM_INT);
        $req->execute() or die(print_r($bdd->errorInfo()));

        $donnees = $req->fetch();
        $savoir_faire = $donnees['Nom'];

        $query = "  UPDATE  Guillermain_produits  
                    SET     Identifiant_sous_page = :identifiant_sous_page, 
                            Nom_sous_page         = :nom_sous_page, 
                            Valeur                = :valeur 
                    WHERE   Identifiant = :identifiant";

        $req = $bdd->prepare($query);
        $req->bindValue("identifiant_sous_page", $identifiant_savoir_faire, PDO::PARAM_INT);
        $req->bindValue("nom_sous_page",         $savoir_faire,             PDO::PARAM_STR);
        $req->bindValue("valeur",                $nom_prestation,           PDO::PARAM_STR);
        $req->bindValue("identifiant",           $identifiant,              PDO::PARAM_INT);
        $req->execute() or die(print_r($bdd->errorInfo()));

        echo '<script>window.location = "index.php?page=3&ok";</script>';

    }else{

        if(isset($_GET['ok'])){
            echo '  <div class="alert alert-success alert-dismissible fade show text-center px-0 w-50 mx-auto my-4" role="alert">
                        Modification de la prestation, effectuée.
                        <button type="button" class="close" data-dismiss="alert" aria-label=« Close »>
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';

        }elseif(isset($_GET['ok2'])){
            echo '  <div class="alert alert-success alert-dismissible fade show text-center px-0 w-50 mx-auto my-4" role="alert">
                        Ajout de la prestation, effectué.
                        <button type="button" class="close" data-dismiss="alert" aria-label=« Close »>
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
        }elseif(isset($_GET['ok3'])){
            echo '  <div class="alert alert-success alert-dismissible fade show text-center px-0 w-50 mx-auto my-4" role="alert">
                        Suppression de la prestation, effectuée.
                        <button type="button" class="close" data-dismiss="alert" aria-label=« Close »>
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
        }

        include ('tableau_savoirs_faire.php');
    }  