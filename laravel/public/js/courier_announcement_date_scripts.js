function addNewDateButton() {
    var button = document.querySelector(".add_date_component_btn");
    button.addEventListener("click", function (event) {
        setAddNewDateButtonVisible(true, true, true);
        if (date.currentDateIndex < date.maxDateIndex) {
            date.currentDateIndex++;
            const dateComponent = document.querySelector(
                ".date_component_" + date.currentDateIndex
            );
            dateComponent.style.display = "table-row";
            this.style.opacity = 0.5;
            this.style.pointerEvents = "none";
        }
        if (date.currentDateIndex >= date.maxDateIndex) {
            const addDateButton = document.querySelector(
                ".add_new_date_button"
            );
            button.classList.add("disabled");
            button.classList.add("btn-secondary");
            button.innerHTML = date.maxDateButtonText;
        }
        setDeleteActionOnFirstElementOnDate();
        date.dateIsSet = false;
        date.directionIsSet = false;
    });
}

function deleteAnyDateButton() {
    var deleteButtons = document.querySelectorAll(
        '[class^="date_delete_btn_"]'
    );
    deleteButtons.forEach(function (button) {
        button.addEventListener("click", function (event) {
            var buttonClass = button.className;
            var match = buttonClass.match(/date_delete_btn_(\d+)/);
            if (date.maxDateIndex > parseInt(match[1])) {
                for ( var i = parseInt( match[ 1 ] ); i <= date.currentDateIndex; i++ ) {
                    var dateDirectionFrom = document.querySelector(
                        "#from_date_directions_select_" + (i + 1).toString()
                    ).value;
                    var dateDirectionTo = document.querySelector(
                        "#to_date_directions_select_" + (i + 1).toString()
                    ).value;
                    var dateInput = document.querySelector(
                        "#date_input_" + (i + 1).toString()
                    ).value;
                    var dateDescription = document.querySelector(
                        "#date_description_" + (i + 1).toString()
                    ).value;

                    document.querySelector(
                        "#from_date_directions_select_" + i.toString()
                    ).value = dateDirectionFrom;
                    document.querySelector(
                        "#to_date_directions_select_" + i.toString()
                    ).value = dateDirectionTo;
                    document.querySelector(
                        "#date_input_" + i.toString()
                    ).value = dateInput;
                    document.querySelector(
                        "#date_description_" + i.toString()
                    ).value = dateDescription;
                }
            }
            const dateComponent = document.querySelector(
                ".date_component_" + date.currentDateIndex
            );
            dateComponent.style.display = "none";
            date.currentDateIndex--;
            checkLastDateItem();
            setDeleteActionOnFirstElementOnDate();
        });
    });
}

function accessForAddNextElementToDate() {
    for (let index = 1; index <= date.maxDateIndex; index++) {
        var dateDirectionFrom = document.querySelector(
            "#from_date_directions_select_" + index.toString()
        );
        var dateDirectionTo = document.querySelector(
            "#to_date_directions_select_" + index.toString()
        );
        var dateInput = document.querySelector(
            "#date_input_" + index.toString()
        );

        dateDirectionFrom.addEventListener("input", function () {
            if (this.value == "default_direction") {
                date.directionIsSet = false;
                for (
                    let visible_item_index = date.currentDateIndex - 1;
                    visible_item_index >= 1;
                    visible_item_index--
                ) {
                    const dateDirectionTemp = document.querySelector(
                        "#from_date_directions_select_" +
                            visible_item_index.toString()
                    );
                    if (dateDirectionTemp.value != "default_direction") {
                        nameInfoIsVisible = true;
                        break;
                    }
                }
            } else {
                date.directionFromIsSet = true;
            }
            setAddNewDateButtonVisible( date.directionFromIsSet, date.directionToIsSet, date.dateIsSet );
        });

        dateDirectionTo.addEventListener("input", function () {
            if (this.value == "default_direction") {
                date.directionIsSet = false;
                for (
                    let visible_item_index = date.currentDateIndex - 1;
                    visible_item_index >= 1;
                    visible_item_index--
                ) {
                    const dateDirectionTemp = document.querySelector(
                        "#to_date_directions_select_" +
                            visible_item_index.toString()
                    );
                    if (dateDirectionTemp.value != "default_direction") {
                        nameInfoIsVisible = true;
                        break;
                    }
                }
            } else {
                date.directionToIsSet = true;
            }
            setAddNewDateButtonVisible( date.directionFromIsSet, date.directionToIsSet, date.dateIsSet );
        });

        dateInput.addEventListener("input", function () {
            if (this.value == "") {
                date.dateIsSet = false;
                for (
                    let visible_item_index = date.currentDateIndex - 1;
                    visible_item_index >= 1;
                    visible_item_index--
                ) {
                    const dateInputTemp = document.querySelector(
                        "#date_input_" + visible_item_index.toString()
                    );
                    if (
                        dateInputTemp.value != "" &&
                        validateConditionsDate(dateInputTemp.value)
                    ) {
                        nameInfoIsVisible = true;
                        break;
                    }
                }
            } else {
                if (validateConditionsDate(this.value)) {
                    date.dateIsSet = true;
                } else {
                    date.dateIsSet = false;
                }
            }
            setAddNewDateButtonVisible( date.directionFromIsSet, date.directionToIsSet, date.dateIsSet );
        });
    }
}

function validateConditionsDate(formDate) {
    const currentDate = new Date();
    currentDate.setDate(currentDate.getDate() + 1);
    const userDate = new Date(formDate);
    const oneDayMillisecond = 1000 * 60 * 60 * 24;

    var diffDays = parseInt((userDate - currentDate) / oneDayMillisecond);
    if (diffDays < 0) {
        return false;
    } else {
        return true;
    }
}

function setAddNewDateButtonVisible( inputDirectionFrom, inputDirectionTo, inputDate ) {
    var addButton = document.querySelector(".add_date_component_btn");
    if ( inputDirectionFrom == true && inputDirectionTo == true && inputDate == true) {
        addButton.style.opacity = 1.0;
        addButton.style.pointerEvents = "auto";
    } else {
        addButton.style.opacity = 0.5;
        addButton.style.pointerEvents = "none";
    }
}

function checkLastDateItem() {
    var dateDirectionFrom = document.querySelector(
        "#from_date_directions_select_" + date.currentDateIndex.toString()
    );
    var dateDirectionTo = document.querySelector(
        "#to_date_directions_select_" + date.currentDateIndex.toString()
    );
    var dateInput = document.querySelector(
        "#date_input_" + date.currentDateIndex.toString()
    );
    if ( dateInput.value == "" &&
        (dateDirectionFrom.value == "default_direction" || dateDirectionFrom.value == "" || dateDirectionFrom.value == null ) &&
        (dateDirectionTo.value == "default_direction" || dateDirectionTo.value == "" || dateDirectionTo.value == null ) ) {
        setAddNewDateButtonVisible(false, false, false);
    } else {
        setAddNewDateButtonVisible(true, true, true);
    }
}

function setDeleteActionOnFirstElementOnDate() {
    var deleteButton = document.querySelector(
        ".action_date_container_button_1"
    );
    var dateActionInfo = document.querySelector(".action_date_container_info");
    if (date.currentDateIndex > 1) {
        deleteButton.style.display = "flex";
        dateActionInfo.style.display = "none";
    } else {
        deleteButton.style.display = "none";
        dateActionInfo.style.display = "flex";
    }
}

function editDateVisibleNumberBeforeFormSend() {
    var form = document.getElementById("courier_announcement_form");
    var date_visible_number = document.getElementById("date_number_visible");
    date.currentDateIndex = parseInt(date_visible_number.value);

    document
        .getElementById("courier_announcement_submit_button")
        .addEventListener("click", function (event) {
            event.preventDefault();
            date_visible_number.value = date.currentDateIndex;
            form.submit();
        });
}

function setVisibleDateComponents() {
    for (let i = 1; i <= date.currentDateIndex; i++) {
        var element = document.querySelector(".date_component_" + i);
        setDateDataAfterValidation(i);
        element.style.display = "table-row";
    }
}

function setDateDataAfterValidation(index) {
    var dateDirectionFrom = document.querySelector(
        "#from_date_directions_select_" + index.toString()
    );
    var dateDirectionTo = document.querySelector(
        "#to_date_directions_select_" + index.toString()
    );
    var dateInput = document.querySelector("#date_input_" + index.toString());

    if ( dateInput.value != null && dateInput.value != "" && validateConditionsDate(dateInput.value) ) {
        date.dateIsSet = true;
    } else {
        date.dateIsSet = false;
    }

    if ( dateDirectionFrom.value != "default_direction" && dateDirectionFrom.value != "" && dateDirectionFrom.value != null ) {
        date.directionFromIsSet = true;
    } else {
        date.directionFromIsSet = false;
    }

    if ( dateDirectionTo.value != "default_direction" && dateDirectionTo.value != "" && dateDirectionTo.value != null ) {
        date.directionToIsSet = true;
    } else {
        date.directionToIsSet = false;
    }

    setAddNewDateButtonVisible( date.directionFromIsSet, date.directionToIsSet, date.dateIsSet);
}

document.addEventListener("DOMContentLoaded", function () {
    var addDateButton = document.querySelector(".add_date_component_btn");
    date.defaultDateButtonText = addDateButton.innerHTML;
    editDateVisibleNumberBeforeFormSend();
    setAddNewDateButtonVisible(false, false, false);
    addNewDateButton();
    deleteAnyDateButton();
    accessForAddNextElementToDate();
    setDeleteActionOnFirstElementOnDate();
    setVisibleDateComponents();
    checkLastDateItem();
});
