function setActionForDateExperience() {
    var checkbox = document.getElementById("experience_for_premium_date");
    checkbox.addEventListener("change", function () {
        var dateField = document.querySelector(
            ".input_experience_date_container"
        );

        if (this.checked) {
            dateField.style.display = "none";
        } else {
            dateField.style.display = "block";
        }
    });
}

function setExperienceDateCheckboxFromPrevData() {
    var checkbox = document.getElementById("experience_for_premium_date");
    var dateField = document.querySelector(".input_experience_date_container");

    if (checkbox.checked) {
        dateField.style.display = "none";
    } else {
        dateField.style.display = "block";
    }
}

document.addEventListener("DOMContentLoaded", function () {
    setActionForDateExperience();
    addListenerForSubmitButtonAndDeleteEmptyFields();
    setExperienceDateCheckboxFromPrevData();
});
