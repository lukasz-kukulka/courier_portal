function setupCheckbox() {
    var checkbox = document.getElementById("item_checkbox_6_id");
    var div = document.getElementById("text_others_div");
    checkbox.addEventListener('change', function() {
      if (this.checked) {
        var input = document.createElement("input");
        input.type = "text";
        input.id = "text_others_id";
        input.name = "text_others";
        input.placeholder = "Opisz jakie";
        div.appendChild(input);
      } else {
        div.innerHTML = "";
      }
    });
}

function clickAddNewItemButton() {
    var inputs = document.querySelectorAll('[id^="add_new_item_button_"]');

    for (var i = 0; i < inputs.length; i++) {
        (function(index) {
            var input = inputs[index];
            input.addEventListener("click", function() {
                var myElement = document.querySelector(".single_item_in_parcel_div_" + (index + 2) + "_class");
                var add_new_item_button = document.getElementById("add_new_item_button_" + ( index + 1 ) );
                var delete_item_button = document.getElementById("delete_item_button_" + ( index + 1 ) );
                myElement.style.display = "inline";
                add_new_item_button.style.display = "none";
                delete_item_button.style.display = "none";
            });
        })(i);
    } 
}

function deleteItemButton() {
  var inputs = document.querySelectorAll('[id^="delete_item_button_"]');

  for (var i = 0; i < inputs.length; i++) {
      (function(index) {
          var input = inputs[index];
          input.addEventListener("click", function() {
              var myElement = document.querySelector(".single_item_in_parcel_div_" + ( index + 2 ) + "_class" );
              var add_new_item_button_prev = document.getElementById("add_new_item_button_" + ( index + 1 ) );
              var add_new_item_button_current = document.getElementById("add_new_item_button_" + ( index + 2 ) );
              var description_field = document.getElementById("input_description_" + ( index + 2 ) + "_id" );
              var quantity_field = document.getElementById("input_quantity_" + ( index + 2 ) + "_id" );
              var value_field = document.getElementById("input_value_" + ( index + 2 ) + "_id" );
              var delete_item_button = document.getElementById("delete_item_button_" + ( index + 1 ) );
              myElement.style.display = "none";
              add_new_item_button_prev.style.display = "inline";
              add_new_item_button_current.disabled = true;
              description_field.value = "";
              quantity_field.value = "";
              value_field.value = "";
              delete_item_button.style.display = "inline";
          });
      })(i);
  } 
}

function ButtonConditions (is_description, is_quantity, is_value ) {
  this.is_description = is_description;
  this.is_quantity = is_quantity;
  this.is_value = is_value;
}

function checkAllInputsInItems() {
  
  var inputs_description = document.querySelectorAll('[id^="input_description_"]');
  var inputs_quantity = document.querySelectorAll('[id^="input_quantity_"]');
  var inputs_value = document.querySelectorAll('[id^="input_value_"]');
  var buttonConditionsArray = [];

  for (var i = 0; i < inputs_description.length; i++) {
      const newButtonCondition = new ButtonConditions( false, false, false, false);
      buttonConditionsArray.push(newButtonCondition);
  }
  for (var i = 0; i < inputs_description.length; i++) {
      (function(index) {
        checkInputTextSingleItem( inputs_description, index, buttonConditionsArray );
        checkInputNumberSingleItem( inputs_quantity, index, buttonConditionsArray, 'quantity' );
        checkInputNumberSingleItem( inputs_value, index, buttonConditionsArray, 'value' );
        resetAllItemConditionsToFalse( index, buttonConditionsArray );
      })(i);
  }
}

function resetAllItemConditionsToFalse( index, buttonConditionsArray ) {
  (function( i ) {
    var del_button = document.getElementById("delete_item_button_" + ( i + 2 ) );
    del_button.addEventListener('click', function() {
      buttonConditionsArray[ i + 1 ].is_description = false;
      buttonConditionsArray[ i+ 1 ].is_quantity = false;
      buttonConditionsArray[ i + 1 ].is_value = false;
      checkAllConditionsForCurrentAndPrevItems( buttonConditionsArray );
    });
  })( index );
  
}

function checkInputTextSingleItem( inputs, index, buttonConditionsArray ) {
  var input = inputs[ index ];
    input.addEventListener('input', function() {
      
      if (input.value.length >= 3 ) {
        buttonConditionsArray[ index ].is_description = true;
      } else {
        buttonConditionsArray[ index ].is_description = false;
      }
      checkAllConditionsForCurrentAndPrevItems( buttonConditionsArray );
    });
}

function checkInputNumberSingleItem( inputs, index, buttonConditionsArray, conditions ) {
  var input = inputs[ index ];
  input.addEventListener('input', function() {
      if ( input.value > 0 && !isNaN( input.value ) ) {
        setSingleItemConditions( index, buttonConditionsArray, conditions, true );
      } else {
        setSingleItemConditions( index, buttonConditionsArray, conditions, false );
      }
      checkAllConditionsForCurrentAndPrevItems( buttonConditionsArray );
    });
}

function setSingleItemConditions( index, buttonConditionsArray, conditions, is_true ) {
  switch ( conditions ) {
    case 'quantity':
      buttonConditionsArray[ index ].is_quantity = is_true;
      break;
    case 'value':
      buttonConditionsArray[ index ].is_value = is_true;
      break;
    default:
  }
}

function getIndexOfActiveButton() {
  var buttons = document.querySelectorAll('[id^="add_new_item_button_"]');
  for ( let i = 0; i < buttons.length; i++ ) {
    if ( buttons[ i ].style.display != "none") {
      return i;
    }
  }
  return 99;
}

function checkAllConditionsForAllButtons( index, buttonConditionsArray ) {
  for (let i = 0; i < index + 1; i++) {
    if( buttonConditionsArray[ i ].is_description == false ||
      buttonConditionsArray[ i ].is_quantity == false ||
      buttonConditionsArray[ i ].is_value == false ) {
        return false;
    } 
  }

  return true;
}

function checkAllConditionsForCurrentAndPrevItems( buttonConditionsArray ) {
  let active_button_index = getIndexOfActiveButton();
  let is_all_true = checkAllConditionsForAllButtons( active_button_index, buttonConditionsArray );
  var button = document.getElementById("add_new_item_button_" + ( active_button_index + 1 ) );
  var button_msg = document.getElementById("msg_add_new_item_button_" + ( active_button_index + 1 ) );
  if ( is_all_true == true ) {
      button.disabled = false;
      button_msg.style.display = "none";
  }
  else {
    button.disabled = true;
    button_msg.style.display = "inline";
  }
}

function checkSummaryCheckbox() {
  var checkbox = document.getElementById('summary_checkbox_id');
  var div = document.querySelector('.generate_summary');
  checkbox.addEventListener('change', function() {
    if (checkbox.checked ) {
      div.style.display = 'none';
    } else {
      div.style.display = 'block';
    }
  });
}

function generateMessageIfIsEmptyInputSender() {

}

function sendForm() {
  if (confirm("Czy na pewno chcesz wysłać test test\ntest test" ) ) {
    document.getElementById("full_form").submit();
  }
}

function sendFormButton( ) {
  var button = document.getElementById("button_send_form");
  button.addEventListener("click", function(event) {
    // event.preventDefault();
    // sendForm();
  } );
}

function checkBusinessCheckbox() {
  var checkbox = document.getElementById('business_checkbox_id');
  var div = document.querySelector('.business_section');
  checkbox.addEventListener('change', function() {
    if (checkbox.checked ) {
      div.style.display = 'block';
    } else {
      div.style.display = 'none';
    }
  });
}
  
document.addEventListener('DOMContentLoaded', function() {
    sendFormButton();
    checkSummaryCheckbox()
    checkBusinessCheckbox();
    setupCheckbox();
    clickAddNewItemButton();
    deleteItemButton();
    checkAllInputsInItems();
});