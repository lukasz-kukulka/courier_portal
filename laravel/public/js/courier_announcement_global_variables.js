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
    directionFromIsSet: false,
    directionToIsSet: false,
    dateIsSet: false,
    currentDirectionFrom: '',
    currentDirectionTo: '',
    allPostCodesIsContainerIsVisible: false,
};

var allDirectionsCall = [];

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

var contactArray = JSON.parse( document.currentScript.getAttribute("contactData") );

function setAllDirectionsData() {
    let directionsObject = JSON.parse( document.currentScript.getAttribute("directions") );
    for (let key in directionsObject) {
        if ( directionsObject.hasOwnProperty( key ) ) {
            allDirectionsCall[ key ] = 0;
        }
    }
}

function setDefaultAllDirectionsData() {
    for ( let i = 1; i < date.maxDateIndex; i++ ) {
        var dateDirectionFrom = document.querySelector( "#from_date_directions_select_" + i.toString() );
        var dateDirectionTo = document.querySelector( "#to_date_directions_select_" + i.toString() );

        var directionFromIsSet = ( dateDirectionFrom.value != "default_direction" && dateDirectionFrom.value != "" && dateDirectionFrom.value != null ) ? true : false;
        var directionToIsSet = ( dateDirectionTo.value != "default_direction" && dateDirectionTo.value != "" && dateDirectionTo.value != null ) ? true : false;

        if ( directionFromIsSet == false && directionToIsSet == false ) {
            for (let key in allDirectionsCall) {
                if ( allDirectionsCall[ key ] > 0 ) {
                    var singlePostCodeContainerTitle = document.querySelector( "." + key + "_post_codes_single_container_title" );
                    var singlePostCodeContainerBody = document.querySelector( "." + key + "_post_codes_single_container_body" );
                    singlePostCodeContainerTitle.style.display = 'table-row';
                    singlePostCodeContainerBody.style.display = 'table-row';
                }
            }
            break;
        }

        if ( directionFromIsSet == true ) {
            allDirectionsCall[ dateDirectionFrom.value ]++;
        }

        if ( directionToIsSet == true ) {
            allDirectionsCall[ dateDirectionTo.value ]++;
        }
        if ( i == 1 ) {
            var allDirectionDivSelector = document.querySelector( ".all_post_codes_container" );
            allDirectionDivSelector.style.display = 'block';
            date.allPostCodesIsContainerIsVisible = true;
        }
    }
}

function setDefaultSettingsVariables() {
    setAllDirectionsData();
    setDefaultAllDirectionsData();
}

setDefaultSettingsVariables();
