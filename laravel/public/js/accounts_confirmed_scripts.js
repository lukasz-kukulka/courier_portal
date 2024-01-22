function checkboxCompanyFields() {
    var checkbox = document.getElementById("company_fields_checkbox");
    var div = document.querySelector(".company_fields_container");
    if (checkbox.checked) {
        div.style.display = "block";
    } else {
        div.style.display = "none";
    }
    checkbox.addEventListener("change", function () {
        if (checkbox.checked) {
            div.style.display = "block";
        } else {
            div.style.display = "none";
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    checkboxCompanyFields();
});
