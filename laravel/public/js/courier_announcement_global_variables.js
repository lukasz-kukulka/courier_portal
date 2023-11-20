var cargo = {
    currentCargoIndex: 1,
    maxCargoIndex: document.currentScript.getAttribute("maxCargoNumber"),
    maxCargoButtonText: document.currentScript.getAttribute("maxButtonText"),
    defaultCargoButtonText: null,
    nameInfoIsVisible: true,
    priceInfoIsVisible: true,
    currencyInfoIsVisible: true,
};

var date = {
    currentDateIndex: 1,
    maxDateIndex: document.currentScript.getAttribute("maxDateNumber"),
    maxDateButtonText: document.currentScript.getAttribute("maxButtonDateText"),
    defaultDateButtonText: null,
    directionIsSet: false,
    dateIsSet: false,
};

const deletePictureTextButton = document.currentScript.getAttribute(
    "deletePictureButtonText"
);
const maxPictureNumber =
    document.currentScript.getAttribute("maxPictureNumber");
var uploadedFiles = [];
var picturesQuantity = 0;
var cargo_visible_number_xxx = document.getElementById("cargo_number_visible");
var isFirstCheckboxPLMark = false;
var isFirstCheckboxUKMark = false;
