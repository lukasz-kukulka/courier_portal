function setActionForDateExperience() {
    var checkbox = document.getElementById( 'experience_for_premium_date' );
    console.log( checkbox );
    checkbox.addEventListener( 'change', function() {
        var dateField = document.querySelector( '.input_experience_date_container' );

        if( this.checked ) {
            dateField.style.display = 'none'
        } else {
            dateField.style.display = 'block'
        }
    } );
}

document.addEventListener('DOMContentLoaded', function() {
    setActionForDateExperience();
} );
