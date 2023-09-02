document.addEventListener("DOMContentLoaded", () => {
    let radios = document.querySelectorAll('[name^="add_announcement[images]["][name$="[main]"]');

    radios.forEach(function(radio) {
        radio.addEventListener('change', function() {
            if (this.checked) {
                radios.forEach(function(innerRadio) {
                    if (innerRadio !== radio) {
                        innerRadio.checked = false;
                    }
                });
            }
        });
    });
});
