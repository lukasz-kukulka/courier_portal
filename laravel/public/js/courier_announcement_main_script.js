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

function subscribeContactButtons() {
    var fillButton = document.getElementById(
        "courier_announcement_fill_data_contact_button"
    );
    var clearButton = document.getElementById(
        "courier_announcement_clear_data_contact_button"
    );
    fillButton.addEventListener("click", function () {
        fillContactData();
    });
    clearButton.addEventListener("click", function () {
        clearContactData();
    });
}

function fillContactData() {
    for (var key in contactArray) {
        var input = document.getElementById("contact_detail_" + key);
        if (input.value == "") {
            input.value = contactArray[key];
        }
    }
}

function clearContactData() {
    for (var key in contactArray) {
        var input = document.getElementById("contact_detail_" + key);
        input.value = "";
    }
}

function subscribeCloseInfo() {
    // window.addEventListener('beforeunload', function(event) {
    //     event.preventDefault();
    //     event.returnValue = '';
    //     var decision = confirm("Czy na pewno chcesz opuścić tę stronę?");
    //     if (decision === false) {
    //         event.preventDefault();
    //     }
    // });
}

document.addEventListener("DOMContentLoaded", function () {
    subscribeContactButtons();
    setActionForDateExperience();
    setExperienceDateCheckboxFromPrevData();
    subscribeCloseInfo();
});
