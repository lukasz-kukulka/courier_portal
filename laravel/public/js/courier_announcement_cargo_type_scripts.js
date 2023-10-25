function addNewCargoTypeButton( ) {
    var button = document.querySelector(".add_cargo_component_btn");
    button.addEventListener("click", function( event ) {

        setAddNewCargoTypeButtonVisible( true, true, true );
        if ( cargo.currentCargoIndex < cargo.maxCargoIndex ) {
            cargo.currentCargoIndex++;
            const cargoComponent = document.querySelector( '.cargo_component_' + cargo.currentCargoIndex );
            cargoComponent.style.display = 'table-row';
        }
        if ( cargo.currentCargoIndex >= cargo.maxCargoIndex ) {
            const addCargoButton = document.querySelector( '.add_new_cargo_type_button' );//////////////////////////
            button.classList.add('disabled');
            button.classList.add('btn-secondary');
            button.innerHTML = cargo.maxCargoButtonText;
        }
        setDeleteActionOnFirstElementOnCargo();
        cargo.nameInfoIsVisible = true;
        cargo.priceInfoIsVisible = true;
        cargo.currencyInfoIsVisible = true;
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
            setDeleteActionOnFirstElementOnCargo();
        });
    });
}

function accessForAddNextElementToCArgoType( ) {
    for ( let index = 1; index <= cargo.maxCargoIndex; index++ ) {
        const inputName = document.querySelector('#cargo_name_' + ( index ).toString() );
        const inputPrice = document.querySelector('#cargo_price_' + ( index ).toString() );
        const inputCurrency = document.querySelector('#select_currency_' + ( index ).toString() );
        const inputNameInfo = document.querySelector('#cargo_name_info_' + ( index ).toString() );

        inputName.addEventListener('input', function() {
            if ( this.value.length >= 3 ) {
                inputNameInfo.style.display = 'none';
                cargo.nameInfoIsVisible = false;
                for ( let visible_item_index = cargo.currentCargoIndex - 1; visible_item_index >= 1; visible_item_index-- ) {
                    const inputNameTemp = document.querySelector('#cargo_name_' + ( visible_item_index ).toString() );
                    if ( inputNameTemp.value.length < 3 ) {
                        nameInfoIsVisible = true;
                        break;
                    }
                }
            } else {
                inputNameInfo.style.display = 'flex';
                cargo.nameInfoIsVisible = true;
            }

            setAddNewCargoTypeButtonVisible( cargo.nameInfoIsVisible, cargo.priceInfoIsVisible, cargo.currencyInfoIsVisible );
        });

        inputPrice.addEventListener('input', function() {
            if ( parseFloat( this.value ) > 0 ) {
                cargo.priceInfoIsVisible = false;
                for ( let visible_item_index = cargo.currentCargoIndex - 1; visible_item_index >= 1; visible_item_index-- ) {
                    const inputPriceTemp = document.querySelector('#cargo_price_' + ( visible_item_index ).toString() );
                    if ( parseFloat( inputPriceTemp.value ) <= 0 ) {
                        nameInfoIsVisible = true;
                        break;
                    }
                }
            } else {
                cargo.priceInfoIsVisible = true;
            }
            setAddNewCargoTypeButtonVisible( cargo.nameInfoIsVisible, cargo.priceInfoIsVisible, cargo.currencyInfoIsVisible );
            setCurrencyAndPriceInfoVisible( cargo.priceInfoIsVisible, cargo.currencyInfoIsVisible );
        });

        inputCurrency.addEventListener( 'change', function() {
            if ( this.value !== "option_default" ) {
                cargo.currencyInfoIsVisible = false;
                for ( let visible_item_index = cargo.currentCargoIndex - 1; visible_item_index >= 1; visible_item_index-- ) {
                    const inputCurrencyTemp = document.querySelector('#select_currency_' + ( visible_item_index ).toString() );
                    if ( inputCurrencyTemp.value === "option_default" ) {
                        nameInfoIsVisible = true;
                        break;
                    }
                }
            } else {
                cargo.currencyInfoIsVisible =  true;
            }
            setAddNewCargoTypeButtonVisible( cargo.nameInfoIsVisible, cargo.priceInfoIsVisible, cargo.currencyInfoIsVisible );
            setCurrencyAndPriceInfoVisible( cargo.priceInfoIsVisible, cargo.currencyInfoIsVisible );
        } );
    }
}

function setCurrencyAndPriceInfoVisible( priceValue, currencyValue, currentIndex = 0 ) {
    let index = currentIndex != 0 ? currentIndex : cargo.currentCargoIndex;
    const inputPriceAndCurrencyInfo = document.querySelector('#cargo_price_info_' + ( index ).toString() );
    if ( priceValue == false && currencyValue == false ) {
        inputPriceAndCurrencyInfo.style.display = 'none';
    } else {
        inputPriceAndCurrencyInfo.style.display = 'flex';
    }
}

function setAddNewCargoTypeButtonVisible( inputName, inputPrice, inputCurrency ) {
    var addButton = document.querySelector(".add_cargo_component_btn");
    if ( inputName == false && inputPrice == false && inputCurrency == false ) {
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
    const inputCurrency = document.querySelector('#select_currency_' + ( cargo.currentCargoIndex ).toString() );
    console.log( cargo.currentCargoIndex );
    if ( inputName.value.length >= 3 && parseFloat( inputPrice.value ) > 0 && inputCurrency.value != "option_default" ) {
        setAddNewCargoTypeButtonVisible( false, false, false );
    } else {
        setAddNewCargoTypeButtonVisible( true, true, true );
    }
}

function setDeleteActionOnFirstElementOnCargo() {
    var deleteButton = document.querySelector(".action_cargo_container_button_1");
    var cargoActionInfo = document.querySelector(".action_cargo_container_info");
    if ( cargo.currentCargoIndex > 1 ) {
        deleteButton.style.display = 'flex';
        cargoActionInfo.style.display = 'none';
    } else {
        deleteButton.style.display = 'none';
        cargoActionInfo.style.display = 'flex';
    }
}

function editCargoVisibleNumberBeforeFormSend() {
    var form = document.getElementById('courier_announcement_form');
    // console.log( form );
    var cargo_visible_number = document.getElementById('cargo_number_visible');
    cargo.currentCargoIndex = parseInt( cargo_visible_number.value );
    console.log( "cargo_visible_number", cargo_visible_number.value );
    document.getElementById('courier_announcement_submit_button').addEventListener('click', function(event) {
        event.preventDefault();
        cargo_visible_number.value = cargo.currentCargoIndex
        form.submit();
    });
}

function setVisibleCargoComponents() {
    for( let i = 1; i <= cargo.currentCargoIndex; i++ ) {
        var element = document.querySelector('.cargo_component_' +  i );
        setCargoDataAfterValidation( i );
        element.style.display = 'table-row';
    }
}

function setCargoDataAfterValidation( index ) {
    const inputName = document.querySelector('#cargo_name_' + ( index ).toString() );
    const inputPrice = document.querySelector('#cargo_price_' + ( index ).toString() );
    const inputCurrency = document.querySelector('#select_currency_' + ( index ).toString() );
    const inputNameInfo = document.querySelector('#cargo_name_info_' + ( index ).toString() );

    if( inputName.value.length >= 3 ) {
        cargo.nameInfoIsVisible = false;
        inputNameInfo.style.display = 'none';
    } else {
        cargo.nameInfoIsVisible = true;
    }

    if( parseFloat( inputPrice.value ) > 0 ) {
        cargo.priceInfoIsVisible = false;
    } else {
        cargo.priceInfoIsVisible = true;
    }

    if( inputCurrency.value != "option_default" && inputCurrency.value != "" && inputCurrency.value != null ) {
        cargo.currencyInfoIsVisible = false;
    } else {
        cargo.currencyInfoIsVisible = true;
    }

    setCurrencyAndPriceInfoVisible( cargo.priceInfoIsVisible, cargo.currencyInfoIsVisible, index );
}

document.addEventListener('DOMContentLoaded', function() {
    var cargo_visible_number = document.getElementById('cargo_number_visible');
    var form = document.getElementById('courier_announcement_form');
    console.log( form );
    console.log( "cargo_cargo: ", cargo_visible_number_xxx.value );
    var addCargoButton = document.querySelector(".add_cargo_component_btn");
    cargo.defaultCargoButtonText = addCargoButton.innerHTML;
    editCargoVisibleNumberBeforeFormSend();
    setAddNewCargoTypeButtonVisible( true, true, true );
    addNewCargoTypeButton();
    deleteAnyCargoButton();
    accessForAddNextElementToCArgoType();
    setDeleteActionOnFirstElementOnCargo();
    setVisibleCargoComponents();
    checkLastCargoItem();
});
