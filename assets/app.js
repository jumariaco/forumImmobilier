/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)

import * as bootstrap from 'bootstrap';
import './styles/app.scss';

// -------------------------EMETTRE UN COMMENTAIRE-------------------------
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

// --------------------------FIN DE L'EMETTEUR DE COMMENTAIRES--------------------------

// -------------------------HOME-------------------------
// function([string1, string2],target id,[color1,color2])    
consoleText(['Bienvenue sur le 1er forum d\'expertise immobilière'], 'text',['primary','secondary']);

function consoleText(words, id, colors) {
  if (colors === undefined) colors = ['#fff'];
  var visible = true;
  var con = document.getElementById('console');
  var letterCount = 1;
  var x = 1;
  var waiting = false;
  var target = document.getElementById(id)
  target.setAttribute('style', 'color:' + colors[0])
  window.setInterval(function() {

    if (letterCount === 0 && waiting === false) {
      waiting = true;
      target.innerHTML = words[0].substring(0, letterCount)
      window.setTimeout(function() {
        var usedColor = colors.shift();
        colors.push(usedColor);
        var usedWord = words.shift();
        words.push(usedWord);
        x = 1;
        target.setAttribute('style', 'color:' + colors[0])
        letterCount += x;
        waiting = false;
      }, 1000)
    } else if (letterCount === words[0].length + 1 && waiting === false) {
      waiting = true;
      window.setTimeout(function() {
        x = -1;
        letterCount += x;
        waiting = false;
      }, 1000)
    } else if (waiting === false) {
      target.innerHTML = words[0].substring(0, letterCount)
      letterCount += x;
    }
  }, 120)
  window.setInterval(function() {
    if (visible === true) {
      con.className = 'console-underscore hidden'
      visible = false;

    } else {
      con.className = 'console-underscore'

      visible = true;
    }
  }, 400)
}


// -------------------------FIN HOME-------------------------