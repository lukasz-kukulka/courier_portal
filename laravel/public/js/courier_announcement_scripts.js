var cargo = {
    currentCargoIndex: 1,
    maxCargoIndex: document.currentScript.getAttribute('maxCargoNumber'),
    maxCargoButtonText: document.currentScript.getAttribute('maxButtonText'),
    defaultCargoButtonText: null
};

function addNewCargoTypeButton( ) {
    var button = document.querySelector(".add_cargo_component_btn");
    button.addEventListener("click", function( event ) {
        setAddNewCargoTypeButtonVisible( true, true );
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
            checkLastCargoItem();
        });
    });
}

function accessForAddNextElementToCArgoType( ) {
    for ( let index = 1; index < cargo.maxCargoIndex; index++ ) {
        const inputName = document.querySelector('#cargo_name_' + ( index ).toString() );
        const inputPrice = document.querySelector('#cargo_price_' + ( index ).toString() );
        const inputNameInfo = document.querySelector('#cargo_name_info_' + ( index ).toString() );
        const inputPriceInfo = document.querySelector('#cargo_price_info_' + ( index ).toString() );
        var nameInfoIsVisible = true;
        var priceInfoIsVisible = true;
        inputName.addEventListener('input', function() {
            if ( inputName.value.length >= 3 ) {
                inputNameInfo.style.display = 'none';
                nameInfoIsVisible = false;
            } else {
                inputNameInfo.style.display = 'flex';
                nameInfoIsVisible = true;
            }
            setAddNewCargoTypeButtonVisible( nameInfoIsVisible, priceInfoIsVisible );
        });

        inputPrice.addEventListener('input', function() {
            if ( parseFloat( inputPrice.value ) > 0 ) {
                inputPriceInfo.style.display = 'none';
                priceInfoIsVisible = false;
            } else {
                inputPriceInfo.style.display = 'flex';
                priceInfoIsVisible = true;
            }
            setAddNewCargoTypeButtonVisible( nameInfoIsVisible, priceInfoIsVisible );
        });
    }

}

function setAddNewCargoTypeButtonVisible( inputName, inputPrice ) {
    var addButton = document.querySelector(".add_cargo_component_btn");
    if ( inputName == false && inputPrice == false ) {
        addButton.style.opacity = 1.0;
        addButton.style.pointerEvents = 'auto';
    } else {
        addButton.style.opacity = 0.5;
        addButton.style.pointerEvents = 'none';
    }
}

function checkLastCargoItem() {
    const inputName = document.querySelector('#cargo_name_' + ( cargo.currentCargoIndex ).toString() );
    const inputPrice = document.querySelector('#cargo_price_' + ( cargo.currentCargoIndex ).toString() );
    if ( inputName.value.length >= 3 && parseFloat( inputPrice.value ) > 0 ) {
        setAddNewCargoTypeButtonVisible( false, false );
    } else {
        setAddNewCargoTypeButtonVisible( true, true );
    }

}

document.addEventListener('DOMContentLoaded', function() {
    var addCargoButton = document.querySelector(".add_cargo_component_btn");
    cargo.defaultCargoButtonText = addCargoButton.innerHTML;
    setAddNewCargoTypeButtonVisible( true, true );
    addNewCargoTypeButton();
    deleteAnyCargoButton();
    accessForAddNextElementToCArgoType();
});
