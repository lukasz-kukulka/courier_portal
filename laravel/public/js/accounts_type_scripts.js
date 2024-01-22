document.addEventListener("DOMContentLoaded", function () {
    var rectangles = document.getElementsByClassName("rectangle");
    var button = document.getElementsByClassName("account_choice_button")[0];
    var default_button_style = button.style;
    var account_type_id = document.querySelector(
        'input[name="account_type_input_id"]'
    );
    var button_error = document.getElementById("button_errors_container");
    var default_button_error_message = button_error.textContent;
    var mark_color = "#99ff99";
    for (var i = 0; i < rectangles.length; i++) {
        rectangles[i].addEventListener("click", function () {
            if (this.style.backgroundColor != "") {
                for (var j = 0; j < rectangles.length; j++) {
                    rectangles[j].style.backgroundColor = "";
                }
                button_error.textContent = default_button_error_message;
                button.style = default_button_style;
            } else {
                for (var j = 0; j < rectangles.length; j++) {
                    rectangles[j].style.backgroundColor = "";
                }
                this.style.backgroundColor = mark_color;
                account_type_id.value = this.id;
                button_error.textContent = "";
                button.style.pointerEvents = "auto";
                button.style.opacity = 1.0;
            }
        });
    }
});
