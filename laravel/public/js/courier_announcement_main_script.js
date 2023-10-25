function setActionForDateExperience() {
    var checkbox = document.getElementById( 'experience_for_premium_date' );
    checkbox.addEventListener( 'change', function() {
        var dateField = document.querySelector( '.input_experience_date_container' );

        if( this.checked ) {
            dateField.style.display = 'none'
        } else {
            dateField.style.display = 'block'
        }
    } );
}

function addListenerForSubmitButtonAndDeleteEmptyFields() {
    document.getElementById('courier_announcement_submit_button').addEventListener('click', function(event) {
        event.preventDefault();

        var form = document.getElementById('courier_announcement_form');

        deleteEmptyCargoFormFields( form );
        deleteEmptyDateFormFields( form );

        form.submit();
    });
}

function deleteEmptyCargoFormFields( form ) {
    for ( let i = 2; i <= cargo.maxCargoIndex; i++ ) {
        const inputCargoName = form.querySelector( '#cargo_name_' + ( i ).toString() );
        const inputPrice = form.querySelector( '#cargo_price_' + ( i ).toString() );
        const inputCurrency = form.querySelector( '#select_currency_' + ( i ).toString() );
        const inputDescription = form.querySelector( '#cargo_description_' + ( i ).toString() );
        const fullCargoComponent = form.querySelector(  '.cargo_component_' + ( i ).toString() );

        const cargoNameIsEmpty = inputCargoName.value === null || inputCargoName.value === "";
        const priceIsEmpty = inputPrice.value === null || inputPrice.value === "" || inputPrice.value === "0";
        const currencyIsEmpty = inputCurrency.value === null || inputCurrency.value === "" || inputCurrency.value === "option_default";
        const descriptionIsEmpty = inputDescription.value === null || inputDescription.value === "";

        if ( cargoNameIsEmpty && priceIsEmpty && currencyIsEmpty && descriptionIsEmpty ) {
            fullCargoComponent.remove();
        }
    }
}

function deleteEmptyDateFormFields( form ) {
    for ( let i = 2; i <= date.maxDateIndex; i++ ) {
        const directionsInput = form.querySelector('#date_directions_select_' + ( i ).toString() );
        const dateInput = form.querySelector('#date_input_' + ( i ).toString() );
        const descriptionInput = form.querySelector('#date_description_' + ( i ).toString() );
        const fullDateComponent = form.querySelector(  '.date_component_' + ( i ).toString() );

        const directionIsEmpty = directionsInput.value === null || directionsInput.value === "" || directionsInput.value === "default_direction";
        const dateIsEmpty = dateInput.value === null || dateInput.value === "";
        const descriptionIsEmpty = descriptionInput.value === null || descriptionInput.value === "";

        if ( directionIsEmpty && dateIsEmpty && descriptionIsEmpty ) {
            fullDateComponent.remove();
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    setActionForDateExperience();
    addListenerForSubmitButtonAndDeleteEmptyFields();
    console.log( cargo );
} );
