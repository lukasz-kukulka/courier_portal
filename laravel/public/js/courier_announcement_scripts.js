var cargo = {
    currentCargoIndex: 1,
    maxCargoIndex: document.currentScript.getAttribute('maxCargoNumber'),
    maxCargoButtonText: document.currentScript.getAttribute('maxButtonText'),
    defaultCargoButtonText: null
};

function addNewCargoTypeButton( ) {
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

function deleteAnyCargoButton( ) {
    var deleteButtons = document.querySelectorAll('[class^="cargo_type_delete_btn_"]');

    deleteButtons.forEach( function( button ) {
        button.addEventListener("click", function( event ) {
            var buttonClass = button.className;
            var match = buttonClass.match(/cargo_type_delete_btn_(\d+)/);
            console.log( cargo.maxCargoIndex, match[ 1 ] );
            if ( cargo.maxCargoIndex > parseInt( match[ 1 ] )  ) {
                for ( var i = parseInt( match[ 1 ] ); i <= cargo.currentCargoIndex; i++ ) {
                    var cargoCurrentNameInput = document.querySelector('#cargo_name_' + ( i + 1 ).toString() ).value;
                    var cargoCurrentDescriptionInput = document.querySelector('#cargo_description_' + ( i + 1 ).toString() ).value;
                    var cargoCurrentPriceInput = document.querySelector('#cargo_price_' + ( i + 1 ).toString() ).value;
                    document.querySelector('#cargo_name_' + ( i ).toString() ).value = cargoCurrentNameInput;
                    document.querySelector('#cargo_description_' + ( i ).toString() ).value = cargoCurrentDescriptionInput;
                    document.querySelector('#cargo_price_' + ( i ).toString() ).value = cargoCurrentPriceInput;
                }
            }
            const cargoComponent = document.querySelector( '.cargo_component_' + cargo.currentCargoIndex );
            cargoComponent.style.display = 'none';
            cargo.currentCargoIndex--;
        });
    });
}

function accessForAddNextElementToCArgoType( ) {

}


document.addEventListener('DOMContentLoaded', function() {
    var addCargoButton = document.querySelector(".add_cargo_component_btn");
    cargo.defaultCargoButtonText = addCargoButton.innerHTML;
    addNewCargoTypeButton();
    deleteAnyCargoButton();
    accessForAddNextElementToCArgoType();
});
