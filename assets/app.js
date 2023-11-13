/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)

import * as bootstrap from 'bootstrap';
import './styles/app.scss';


// on met un écouteur d'évènements sur tous les boutons "répondre" (à un commentaire)
document.querySelectorAll('[data-reply]').forEach((element) => {
    element.addEventListener('click', function() {
        document.querySelector("#commentaire_parent").value = this.dataset.id;

        // Mettez à jour le champ commentaire_enfant avec l'ID du commentaire en réponse
        document.querySelector("#commentaire_enfant").value = this.dataset.id;
    });
});

// on met un écouteur d'évènements sur tous les boutons "répondre" (à un commentaire)
document.querySelectorAll('[data-replies]').forEach((element) => {
    element.addEventListener('click', function() {

        // Mettez à jour le champ commentaire_enfant avec l'ID du commentaire en réponse
        document.querySelector("#commentaire_enfant").value = this.dataset.id;
    });
});


