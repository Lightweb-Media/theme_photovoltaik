var radioButtons = document.querySelectorAll('.frm_image_option');
    radioButtons?.forEach(function(radioButton) {
        radioButton.addEventListener('click', function() {
        document.querySelector('.frm_button_submit').click();
    });
});