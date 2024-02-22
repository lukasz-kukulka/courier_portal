function subscribeDirectionCountryChangeEvent() {
    var selectElements = document.querySelectorAll(
        '[id*="country_select_post_code_"]'
    );
    selectElements.forEach(function (element) {
        element.addEventListener("change", function (event) {
            var selectedValue = event.target.value;
            changeVisibilityPostCodesElements(element, selectedValue);
        });
    });
}

function changeVisibilityPostCodesElements(element, visibleIndex) {
    for (var i = 0; i < element.options.length; i++) {
        var option = element.options[i];
        var optionValue = option.value;

        if (optionValue != "") {
            var id = element.id;
            var name = id.replace("country_select_", "");

            var postCodes = document.querySelector(
                ".direction_container_" + optionValue + "_" + name
            );

            var elementToClear = document.querySelectorAll(
                `[class*="prefix_select_"][class*="${name}"]:not([class*="${optionValue}"])`
            );

            elementToClear.forEach(function (postCode) {
                postCode.selectedIndex = 0;
            });

            prefix_select_pl_post_code_sending;
            var postfixSelect = document.querySelector(
                ".prefix_select_" + optionValue + "_" + name
            );
            subscribePostCodesChangeEvent(postfixSelect, name);
            if (optionValue == visibleIndex) {
                postCodes.style.display = "block";
            } else {
                postCodes.style.display = "none";
            }
        }
    }
}

function subscribePostCodesChangeEvent(postCodesSelector, postfixSelectorName) {
    postCodesSelector.addEventListener("change", function (event) {
        changeVisibilityPostfix(postCodesSelector, postfixSelectorName);
    });
}

function changeVisibilityPostfix(postCodesSelector, postfixSelectorName) {
    var postfix = document.querySelector(
        "#postfix_select_" + postfixSelectorName
    );

    subscribePostfixChangeEvent(postfix, postfixSelectorName);
    var postfixClassName = "postfix_container_" + postfixSelectorName;
    var postfixContainer = document.querySelector("." + postfixClassName);

    if (postCodesSelector.value != "") {
        postfix.value = "";
        postfixContainer.style.display = "block";
    } else {
        postfixContainer.style.display = "none";
    }
}

function subscribePostfixChangeEvent(postfix, postfixSelectorName) {
    var city = document.querySelector(
        ".direction_city_container_" + postfixSelectorName
    );
    postfix.addEventListener("input", function (event) {
        if (postfix.value != "") {
            city.style.display = "block";
        } else {
            city.style.display = "none";
        }
    });
}

function checkAfterErrorDirectionsFields() {
    var selectElements = document.querySelectorAll(
        '[id*="country_select_post_code_"]'
    );

    selectElements.forEach(function (element) {
        if (element.value != "") {
            var elementValue = element.name;
            var searchName = elementValue.replace("country_select_", "");
            var prefix = document.querySelector(
                `[class*=direction_container_${element.value}_${searchName}]`
            );
            prefix.style.display = "block";
            if (prefix.value != "") {
                var postfix = document.querySelector(
                    `[class*=postfix_container_${searchName}]`
                );
                postfix.style.display = "block";
                if (postfix.value != "") {
                    var city = document.querySelector(
                        `[class*=direction_city_container_${searchName}]`
                    );
                    city.style.display = "block";
                }
            }
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    subscribeDirectionCountryChangeEvent();
    checkAfterErrorDirectionsFields();
});
