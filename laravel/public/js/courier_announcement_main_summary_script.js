function submitFormAfterSummary(action) {
    var form = document.getElementById("courier_announcement_summary");
    var route = "";

    if (action === "confirm") {
        route = document
            .querySelector('[data-action="confirm"]')
            .getAttribute("data_route");
        deleteEmptyCargoFormFields(form);
        deleteEmptyDateFormFields(form);
    } else if (action === "edit") {
        route = document
            .querySelector('[data-action="edit"]')
            .getAttribute("data_route");
    } else {
        console.log("ERROR: submitFormAfterSummary()");
    }

    form.action = route;
    form.submit();
}

function makeUnvisibleDirectionIfIsEmpty() {
    const directions = document.querySelectorAll(
        '[class^="direction_container_"]'
    );
    directions.forEach(function (dir) {
        const match = dir.className.match(/direction_container_([^]+)/);
        const dirHiddenInput = document.getElementById("is_empty_" + match[1]);
        if (dirHiddenInput.value === "false") {
            dir.style.display = "none";
        }
    });
}

function deleteEmptyCargoFormFields(form) {
    iterator = 1;

    while (true) {
        const inputCargoName = form.querySelector(
            "#cargo_name_" + iterator.toString()
        );
        if (inputCargoName === null) {
            break;
        }
        const inputPrice = form.querySelector(
            "#cargo_price_" + iterator.toString()
        );
        const inputCurrency = form.querySelector(
            "#select_currency_" + iterator.toString()
        );
        const inputDescription = form.querySelector(
            "#cargo_description_" + iterator.toString()
        );

        const cargoNameIsEmpty =
            inputCargoName.value === null || inputCargoName.value === "";
        const priceIsEmpty =
            inputPrice.value === null ||
            inputPrice.value === "" ||
            inputPrice.value === "0";
        const currencyIsEmpty =
            inputCurrency.value === null ||
            inputCurrency.value === "" ||
            inputCurrency.value === "option_default";
        const descriptionIsEmpty =
            inputDescription.value === null || inputDescription.value === "";

        const isElementToDelete =
            cargoNameIsEmpty &&
            priceIsEmpty &&
            currencyIsEmpty &&
            descriptionIsEmpty;

        if (isElementToDelete) {
            inputCargoName.parentNode.removeChild(inputCargoName);
            inputPrice.parentNode.removeChild(inputPrice);
            inputCurrency.parentNode.removeChild(inputCurrency);
            inputDescription.parentNode.removeChild(inputDescription);
        }
        iterator++;
    }
}

function deleteEmptyDateFormFields(form) {
    iterator = 1;

    while (true) {
        const directionsInput = form.querySelector(
            "#date_directions_select_" + iterator.toString()
        );
        if (directionsInput === null) {
            break;
        }
        const dateInput = form.querySelector(
            "#date_input_" + iterator.toString()
        );
        const descriptionInput = form.querySelector(
            "#date_description_" + iterator.toString()
        );
        fullDateComponent = form.querySelector(
            ".date_component_" + iterator.toString()
        );

        const directionIsEmpty =
            directionsInput.value === null ||
            directionsInput.value === "" ||
            directionsInput.value === "default_direction";
        const dateIsEmpty = dateInput.value === null || dateInput.value === "";
        const descriptionIsEmpty =
            descriptionInput.value === null || descriptionInput.value === "";
        isElementToDelete =
            directionIsEmpty && dateIsEmpty && descriptionIsEmpty;

        if (isElementToDelete) {
            directionsInput.parentNode.removeChild(directionsInput);
            dateInput.parentNode.removeChild(dateInput);
            descriptionInput.parentNode.removeChild(descriptionInput);
        }
        iterator++;
    }
}

document.addEventListener("DOMContentLoaded", function () {
    makeUnvisibleDirectionIfIsEmpty();
});
