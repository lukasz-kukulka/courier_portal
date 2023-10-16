function changeCheckboxesStatus( buttonName, selectorCheckboxName, isSelect ) {
    const selectButton = document.querySelector( buttonName );
    selectButton.addEventListener( 'click', function() {
        var allPostCodeCheckboxes = document.querySelectorAll( selectorCheckboxName );
        allPostCodeCheckboxes.forEach( function( checkbox ) {
            checkbox.checked = isSelect;
            changeSingleCheckboxButtonClass( checkbox );
        } );
    } );
}

function changeColorButtonIfIsMarkedCheckboxListener() {
    var allPostCodeCheckboxes = document.querySelectorAll( '[id^="post_code_checkbox_"]' );
    allPostCodeCheckboxes.forEach( function( checkbox ) {
        checkbox.addEventListener( 'change', function() {
            changeSingleCheckboxButtonClass( checkbox );
        } );
    } );
}

function changeSingleCheckboxButtonClass( checkbox ) {
    var name = checkbox.id;
    var matchPostfix = name.match(/post_code_checkbox_(.+)/);
    const button = document.querySelector( '.post_code_button_' + matchPostfix[ 1 ] );
    if ( checkbox.checked === true ) {
        button.classList.remove('btn-secondary');
        button.classList.add('btn-info');
    } else {
        button.classList.remove('btn-info');
        button.classList.add('btn-secondary');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    changeCheckboxesStatus( '.select_all_post_code_pl', '[id^="post_code_checkbox_pl_"]', true );
    changeCheckboxesStatus( '.select_all_post_code_uk', '[id^="post_code_checkbox_uk_"]', true );
    changeCheckboxesStatus( '.clear_all_post_code_pl', '[id^="post_code_checkbox_pl_"]', false );
    changeCheckboxesStatus( '.clear_all_post_code_uk', '[id^="post_code_checkbox_uk_"]', false );
    changeColorButtonIfIsMarkedCheckboxListener();
} );