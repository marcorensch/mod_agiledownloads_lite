document.addEventListener("DOMContentLoaded", function(event) {
    domLoaded();
});

/**
 * Remove classes from parent elements and make them full width
 * Used for Description texts in backend
 * @param element
 */
function removeClasses(element){
    element.closest('.spacer').classList.remove('spacer');
    element.closest('div').classList.remove('control-label');
}

function hideLabel(element){
    element.closest('div').classList.remove('control-label');
}

function domLoaded(){
    const ids = [
        'jform_params_warning_message_fa-lbl',
        'jform_params_label_overrides-lbl'
    ]

    ids.forEach(id => {
        // Structured Content description
        const el = document.getElementById(id);
        hideLabel(el);
    });
}

