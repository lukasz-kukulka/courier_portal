document.addEventListener('DOMContentLoaded', function() {
    var rectangles = document.getElementsByClassName('rectangle');
    var mark_color = 'red';
    for (var i = 0; i < rectangles.length; i++) {
        rectangles[i].addEventListener('click', function() {
            if ( this.style.backgroundColor === mark_color ) {
                for (var j = 0; j < rectangles.length; j++) {
                    rectangles[j].style.backgroundColor = '';
                }
            } else {
                for (var j = 0; j < rectangles.length; j++) {
                    rectangles[j].style.backgroundColor = '';
                }
                this.style.backgroundColor = mark_color;
            }
        });
    }
});

