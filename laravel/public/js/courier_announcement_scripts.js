var cargo = {
    currentCargoIndex: 1,
    maxCargoIndex: document.currentScript.getAttribute('maxCargoNumber'),
    maxCargoButtonText: document.currentScript.getAttribute('maxButtonText'),
    defaultCargoButtonText: null
};

function addNewCargoType( ) {

    var button = document.querySelector(".add_cargo_component_btn");
    button.addEventListener("click", function( event ) {
        if ( cargo.currentCargoIndex < cargo.maxCargoIndex ) {
            cargo.currentCargoIndex++;
            const cargoComponent = document.querySelector( '.cargo_component_' + cargo.currentCargoIndex );
            cargoComponent.style.display = 'table-row';
        }
        if ( cargo.currentCargoIndex >= cargo.maxCargoIndex ) {
            const addCargoButton = document.querySelector( '.add_new_cargo_type_button' );
            alert( cargo.maxCargoButtonText );
            button.classList.add('disabled');
            button.classList.add('btn-secondary');
            button.innerHTML = cargo.maxCargoButtonText;
        }
  } );
  }

document.addEventListener('DOMContentLoaded', function() {
    var addCargoButton = document.querySelector(".add_cargo_component_btn");
    cargo.defaultCargoButtonText = addCargoButton.innerHTML;
    addNewCargoType();
});
