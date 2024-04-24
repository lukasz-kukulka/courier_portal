function setSingleFilterSelector( headName, bodyName, expandName, foldName ) {
    const tableBody = document.querySelector( bodyName );
    const expandField = document.querySelector( expandName );
    const foldField = document.querySelector( foldName );
    tableBody.style.display = 'none';
    foldField.style.display = 'none';
    document.querySelector( headName ).addEventListener('click', function() {
        tableBody.style.display = tableBody.style.display === 'none' ? 'block' : 'none';
        expandField.style.display = tableBody.style.display === 'none' ? 'block' : 'none';
        foldField.style.display = tableBody.style.display === 'none' ? 'none' : 'block';
    });

}

function setAllFiltersSelector() {
    setSingleFilterSelector( '.post_codes_from_filter_title',
                             '.post_codes_from_filter_body',
                             '.post_codes_from_filter_title_right_expand',
                             '.post_codes_from_filter_title_right_fold' );
    setSingleFilterSelector( '.post_codes_to_filter_title',
                             '.post_codes_to_filter_body',
                             '.post_codes_to_filter_title_right_expand',
                             '.post_codes_to_filter_title_right_fold' );
    setSingleFilterSelector( '.date_filter_title',
                             '.date_filter_body',
                             '.date_filter_title_right_expand',
                             '.date_filter_title_right_fold' );
    setSingleFilterSelector( '.cargo_type_filter_title',
                             '.cargo_type_filter_body',
                             '.cargo_type_filter_title_right_expand',
                             '.cargo_type_filter_title_right_fold' );
}

function subscribeVisibleButtonsFilters() {
    const showFilterButton = document.querySelector( '#courier_announcement_add_filters_button' );
    const hideFilterButton = document.querySelector( '#courier_announcement_hide_filters_button' );
    const allFiltersField = document.querySelector( '.filters_container_body' );

    // console.log( showFilterButton, hideFilterButton, allFiltersField );
    hideFilterButton.style.display = 'none';
    allFiltersField.style.display = 'none';

    showFilterButton.addEventListener('click', function() {
        showFilterButton.style.display = 'none';
        hideFilterButton.style.display = 'block';
        allFiltersField.style.display = 'block';
    });
    hideFilterButton.addEventListener('click', function() {
        showFilterButton.style.display = 'block';
        hideFilterButton.style.display = 'none';
        allFiltersField.style.display = 'none';
    });
}

function getMatchedNamePostfix( element, textMatching ) {
    var name = element.id;
    var matchPostfix = name.match(new RegExp(textMatching + '(.+)'));
    return matchPostfix ? matchPostfix[ 1 ] : null;
}

function subscribeClickDirectionsButtons( dir ) {
    let directionsButtons = document.querySelectorAll( '[class^="direction_button_' + dir + '"]' );
    console.log( directionsButtons );
    directionsButtons.forEach( function ( button ) {
        button.addEventListener( 'click', function() {
            const postfix = getMatchedNamePostfix( button, 'id_direction_button_' + dir + '_' );
            console.log( postfix );
            button.style.opacity = button.style.opacity == '1' ? '0.5' : '1.0';
            const hideDirection = document.getElementById( 'id_post_codes_' + dir + '_filter_body_direction' );
            if( button.style.opacity == '1' ) {
                hideDirection.value = postfix;
                setVisibleSingleDirectionPostCodes( postfix, dir );
                button.style.opacity = '1.0';
                directionsButtons.forEach( function ( othersButton ) {
                    if( button != othersButton ) {
                        othersButton.style.opacity = '0.5';
                    }
                } );
                console.log( dir );
            } else {
                directionsButtons.forEach( function ( othersButton ) {
                    othersButton.style.opacity = '0.5';
                    setVisibleSingleDirectionPostCodes( null, dir );
                    hideDirection.value = null;
                } );
            }
        } );
    } );
}


function setVisibleSingleDirectionPostCodes( postfix, dir ) {
    let postCodesContainers = document.querySelectorAll( '[class^="post_codes_' + dir + '_container_"]' );
    console.log( postCodesContainers );
    postCodesContainers.forEach( function( container ) {
        console.log( container.classList, postfix );
        if ( container.classList.contains('post_codes_' + dir + '_container_' + postfix ) && postfix != null ) {

            container.style.display = 'inline-block';
        } else {
            container.style.display = 'none';
        }
    } );
}

function subscribeDateCheckbox() {
    const checkbox = document.getElementById( 'id_all_dates_checkbox' );
    checkbox.addEventListener( 'change', function() {
        const dateContainer = document.querySelector( '.custom_date_container' );
        if( checkbox.checked == true ) {
            dateContainer.style.display = 'none';
        } else {
            dateContainer.style.display = 'flex';
        }
    } );
}

document.addEventListener("DOMContentLoaded", function () {
    setAllFiltersSelector();
    subscribeVisibleButtonsFilters();
    subscribeClickDirectionsButtons( 'from' );
    subscribeClickDirectionsButtons( 'to' );
    subscribeDateCheckbox();
});


