var date = {
    currentDateIndex: 1,
    maxDateIndex: document.currentScript.getAttribute('maxDateNumber'),
    maxDateButtonText: document.currentScript.getAttribute('maxButtonDateText'),
    defaultDateButtonText: null,
    directionIsSet: false,
    dateIsSet: false
};

function addNewDateButton( ) {
    var button = document.querySelector(".add_date_component_btn");
    button.addEventListener("click", function( event ) {
        setAddNewDateButtonVisible( true, true );
        if ( date.currentDateIndex < date.maxDateIndex ) {
            date.currentDateIndex++;
            const dateComponent = document.querySelector( '.date_component_' + date.currentDateIndex );
            dateComponent.style.display = 'table-row';
            this.style.opacity = 0.5;
            this.style.pointerEvents = 'none';
        }
        if ( date.currentDateIndex >= date.maxDateIndex ) {
            const addDateButton = document.querySelector( '.add_new_date_button' );
            button.classList.add('disabled');
            button.classList.add('btn-secondary');
            button.innerHTML = date.maxDateButtonText;
        }
        setDeleteActionOnFirstElementOnDate();
  } );
}

function deleteAnyDateButton( ) {
    var deleteButtons = document.querySelectorAll('[class^="date_delete_btn_"]');
    deleteButtons.forEach( function( button ) {
        button.addEventListener("click", function( event ) {
            var buttonClass = button.className;
            var match = buttonClass.match(/date_delete_btn_(\d+)/);
            if ( date.maxDateIndex > parseInt( match[ 1 ] )  ) {
                for ( var i = parseInt( match[ 1 ] ); i <= date.currentDateIndex; i++ ) {
                    var dateDirection = document.querySelector('#date_directions_select_' + ( i + 1 ).toString() ).value;
                    var dateInput = document.querySelector('#date_input_' + ( i + 1 ).toString() ).value;
                    var dateDescription = document.querySelector('#date_description_' + ( i + 1 ).toString() ).value;

                    document.querySelector('#date_directions_select_' + ( i ).toString() ).value = dateDirection;
                    document.querySelector('#date_input_' + ( i ).toString() ).value = dateInput;
                    document.querySelector('#date_description_' + ( i ).toString() ).value = dateDescription;
                }
            }
            const dateComponent = document.querySelector( '.date_component_' + date.currentDateIndex );
            dateComponent.style.display = 'none';
            date.currentDateIndex--;
            checkLastDateItem();
            setDeleteActionOnFirstElementOnDate();
        });
    });
}

function accessForAddNextElementToDate( ) {
    for ( let index = 1; index <= date.maxDateIndex; index++ ) {
        var dateDirection = document.querySelector('#date_directions_select_' + ( index ).toString() );
        var dateInput = document.querySelector('#date_input_' + ( index ).toString() );

        dateDirection.addEventListener('input', function() {
            if ( this.value == 'default_direction' ) {
                date.directionIsSet = false;
                for ( let visible_item_index = date.currentDateIndex - 1; visible_item_index >= 1; visible_item_index-- ) {
                    const dateDirectionTemp = document.querySelector('#date_directions_select_' + ( visible_item_index ).toString() );
                    if ( dateDirectionTemp.value != 'default_direction' ) {
                        nameInfoIsVisible = true;
                        break;
                    }
                }
            } else {
                date.directionIsSet = true;
            }
            setAddNewDateButtonVisible( date.directionIsSet, date.dateIsSet );
        });

        dateInput.addEventListener('input', function() {
            if ( this.value == "" ) {
                date.dateIsSet = false;
                for ( let visible_item_index = date.currentDateIndex - 1; visible_item_index >= 1; visible_item_index-- ) {
                    const dateInputTemp = document.querySelector('#date_input_' + ( visible_item_index ).toString() );
                    if ( dateInputTemp.value != "" ) {
                        nameInfoIsVisible = true;
                        break;
                    }
                }
            } else {
                date.dateIsSet = true;
            }
            setAddNewDateButtonVisible( date.directionIsSet, date.dateIsSet );
        });
    }
}

function setAddNewDateButtonVisible( inputDirection, inputDate ) {
    var addButton = document.querySelector(".add_date_component_btn");
    if ( inputDirection == true && inputDate == true ) {
        addButton.style.opacity = 1.0;
        addButton.style.pointerEvents = 'auto';
    } else {
        addButton.style.opacity = 0.5;
        addButton.style.pointerEvents = 'none';
    }
}

function checkLastDateItem() {
    var dateDirection = document.querySelector('#date_directions_select_' + ( date.currentDateIndex ).toString() );
    var dateInput = document.querySelector('#date_input_' + ( date.currentDateIndex ).toString() );

    if ( dateInput.value == "" && dateDirection.value == 'default_direction' ) {
        setAddNewDateButtonVisible( false, false );
    } else {
        setAddNewDateButtonVisible( true, true );
    }
}

function setDeleteActionOnFirstElementOnDate() {
    var deleteButton = document.querySelector(".action_date_container_button_1");
    var dateActionInfo = document.querySelector(".action_date_container_info");
    console.log( date.currentDateIndex );
    if ( date.currentDateIndex > 1 ) {
        deleteButton.style.display = 'flex';
        dateActionInfo.style.display = 'none';
    } else {
        deleteButton.style.display = 'none';
        dateActionInfo.style.display = 'flex';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var addDateButton = document.querySelector(".add_date_component_btn");
    date.defaultDateButtonText = addDateButton.innerHTML;
    setAddNewDateButtonVisible( false, false );
    addNewDateButton();
    deleteAnyDateButton();
    accessForAddNextElementToDate();
    setDeleteActionOnFirstElementOnDate();
});
