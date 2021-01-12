/* Cette page est inclut dans formulaire_realisation
    Elle contient l'évènement placé sur la bouton permettant de valider le changement de l'ordre des images.
    Si une image n'a pas le nombre 0 (permettant de la placer en image principale) ou si 2 ordres sont égaux,
    met en rouge les input number situé en dessous des images et affiche un message différent pour chacun des problèmes. */

var inputsOrdre  = document.querySelectorAll('.input_ordre'),
    boutonSubmit = document.getElementById('bouton_submit'),
    formJavascript = document.getElementById('form_javascript');

    if(boutonSubmit != null){

        boutonSubmit.addEventListener("click",function(e){
            var verification = [];

            for(let i = 0, nb = inputsOrdre.length ; i < nb; i++){

                var inputOrdre = document.createElement('INPUT');
                
                if(verification.indexOf(inputsOrdre[i].value) === -1){

                    verification.push(inputsOrdre[i].value);
                    
                    inputOrdre.type  = "hidden";
                    inputOrdre.value = inputsOrdre[i].value;
                    inputOrdre.name  = inputsOrdre[i].name;
                    formJavascript.appendChild(inputOrdre);

                }else{
                    e.preventDefault();
                    
                    for(let i = 0, nb = inputsOrdre.length ; i < nb; i++){

                        inputsOrdre[i].style.background = "red";
                        inputsOrdre[i].style.color      = "white";
                    }
                    
                    var divAlert = document.createElement('div');

                    divAlert.className = "alert alert-danger text-center px-0 mx-auto my-4";
                    divAlert.innerHTML = "Les images n'ont pas toutes un ordre différent, veuillez mettre un ordre différent à chaque image.";
                    formJavascript.parentNode.insertBefore(divAlert, formJavascript);
                    break;
                }
            }

            if(verification.indexOf('0') === -1){
                e.preventDefault();
                    
                for(let i = 0, nb = inputsOrdre.length ; i < nb; i++){

                    inputsOrdre[i].style.background = "red";
                    inputsOrdre[i].style.color      = "white";
                }
                
                var divAlert = document.createElement('div');

                divAlert.className = "alert alert-danger text-center px-0 mx-auto my-4";
                divAlert.innerHTML = "L'ordre 0 n'a pas été défini, veuillez définir votre image principale en lui attribuant l'ordre 0.";
                formJavascript.parentNode.insertBefore(divAlert, formJavascript);

            }else{
                    var inputChangement = document.createElement('INPUT');

                    inputChangement.type  = "hidden";
                    inputChangement.name  = "changement_ordre";
                    formJavascript.appendChild(inputChangement);
            }
            
        }, true);
    }
