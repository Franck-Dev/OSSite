import 'bootstrap/bootstrap.index.js';
import 'bootstrap/dist/css/bootstrap.min.css';

/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

$(document).ready(function () {

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });

});

const input = document.querySelector("#contact_honeyPot");
const label_input = document.getElementById("label_honeyPot");
const btn_save = document.getElementById("contact_save");
input.addEventListener("input", (event) => {
    const text = 'Je suis un ';
    if (event.target.value == 4) {
        const nature = "HUMAIN";
        label_input.innerText = (text + nature);
        label_input.style.backgroundColor = 'green';
        btn_save.className='btn-primary btn';
    } else {
        const nature = "ROBOT";
        label_input.innerText = (text + nature);
        label_input.style.backgroundColor = 'red';
        btn_save.className='btn-primary btn d-none disabled';
    }
    
});

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
