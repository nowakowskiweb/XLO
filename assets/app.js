/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';
import 'bootstrap';

// Example starter JavaScript for disabling form submissions if there are invalid fields
// (() => {
//     'use strict'
//
//     // Fetch all the forms we want to apply custom Bootstrap validation styles to
//     const forms = document.querySelectorAll('.needs-validation')
//
//     // Loop over them and prevent submission
//     Array.from(forms).forEach(form => {
//         form.addEventListener('submit', event => {
//             console.log('dupa')
//
//             if (!form.checkValidity()) {
//                 event.preventDefault()
//                 event.stopPropagation()
//             }
//             form.classList.add('was-validated')
//         }, false)
//     })
// })()