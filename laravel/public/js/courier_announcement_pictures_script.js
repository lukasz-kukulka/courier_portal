function addListenerForAddPicturesFile() {
    const pictureInputs = document.querySelectorAll(
        '[id^="courier_announcement_picture_input_"]'
    );
    pictureInputs.forEach(function (pictureInput) {
        pictureInput.addEventListener("change", function () {});
    });
}

function updateInfoVisible() {
    let pictureInfo = document.querySelector(".picture_limit_info");
    let inputPictures = document.querySelector(".input_pictures");
    if (picturesQuantity > maxPictureNumber) {
        pictureInfo.style.display = "block";
        inputPictures.style.display = "none";
    } else {
        pictureInfo.style.display = "none";
        inputPictures.style.display = "inline-block";
    }
}

function displayThumbnails(event) {
    var thumbnailsContainer = document.querySelector(".thumbnailsContainer");

    var files = event.target.files;

    updateInfoVisible(files.length);

    for (let i = 0; i < files.length; i++) {
        let file = files[i];
        let reader = new FileReader();

        reader.onload = function () {
            var thumbnail = generateThumbnailSection();
            var img = generateImgTag(reader);
            var fileNameOverlay = generateFileNameOverlay(file);
            var deleteButton = createDeleteButton(file);

            thumbnail.appendChild(img);
            thumbnail.appendChild(fileNameOverlay);
            thumbnail.appendChild(deleteButton);

            thumbnailsContainer.appendChild(thumbnail);

            uploadedFiles.push(file);
            picturesQuantity++;
            updateInfoVisible();
        };

        reader.readAsDataURL(file);
    }
}

function removeFile(file) {
    var index = uploadedFiles.indexOf(file);

    if (index > -1) {
        uploadedFiles.splice(index, 1);
        refreshThumbnails();
        picturesQuantity--;
        updateInfoVisible();
    }
}

function refreshThumbnails() {
    var thumbnailsContainer = document.querySelector(".thumbnailsContainer");
    thumbnailsContainer.innerHTML = "";

    uploadedFiles.forEach(function (file) {
        var thumbnail = generateThumbnailSection(file);
        var deleteButton = createDeleteButton(file);
        var fileNameOverlay = generateFileNameOverlay(file);

        thumbnail.appendChild(fileNameOverlay);
        thumbnail.appendChild(deleteButton);
        thumbnailsContainer.appendChild(thumbnail);
    });
}

function generateImgTag(reader) {
    var img = document.createElement("img");
    img.src = reader.result;
    img.alt = "Thumbnail";

    return img;
}

function generateThumbnailSection(file = null) {
    var thumbnail = document.createElement("div");
    thumbnail.classList.add("thumbnail");
    if (file != null) {
        thumbnail.innerHTML =
            '<img src="' + URL.createObjectURL(file) + '" alt="Thumbnail">';
    }

    return thumbnail;
}

function createDeleteButton(file) {
    var deleteButton = document.createElement("button");
    deleteButton.textContent = deletePictureTextButton;
    deleteButton.classList.add("btn", "btn-sm", "btn-danger");
    deleteButton.addEventListener("click", function () {
        removeFile(file);
    });

    return deleteButton;
}

function generateFileNameOverlay(file) {
    var fileNameOverlay = document.createElement("div");
    fileNameOverlay.classList.add("fileNameOverlay");
    fileNameOverlay.textContent = file.name;

    return fileNameOverlay;
}

function addListenerForSubmitButtonAndAddFilesArray() {
    document
        .getElementById("courier_announcement_submit_button")
        .addEventListener("click", function (event) {
            event.preventDefault();

            var form = document.getElementById("courier_announcement_form");

            var input = document.getElementById(
                "courier_announcement_picture_input"
            );
            if (input) {
                input.remove();
            }

            generateVariableIfAddedFiles(form);

            uploadedFiles.forEach(function (file, index) {
                let list = new DataTransfer();
                list.items.add(file);
                let myFileList = list.files;

                var input = document.createElement("input");
                input.type = "file";
                input.name = "files[]";
                input.files = myFileList;

                form.appendChild(input);
            });

            form.submit();
        });
}

function generateVariableIfAddedFiles(form) {
    const errorPictureInfo = document.createElement("input");

    if (uploadedFiles.length > 0) {
        errorPictureInfo.type = "hidden";
        errorPictureInfo.name = "is_error_picture_info";
        errorPictureInfo.id = "is_error_picture_info";
        errorPictureInfo.value = true;
    }

    form.appendChild(errorPictureInfo);
}

function registerListenerForPrevPicturesButtons() {
    let delButtons = document.querySelectorAll( '[class*="delete_prev_image_"]' );
    let resButtons = document.querySelectorAll( '[class*="restore_prev_image_"]' );

    delButtons.forEach(function ( button ) {
        button.addEventListener( "click", function () {
            var elementNumber = extractElementNumber( button, 'delete_prev_image_' );
            var delButton = document.getElementById( 'restore_prev_image_' + elementNumber );
            exchangeButtonsProperties( button, delButton );
            switchIfImagesIsForDelete( true, elementNumber );
            changeValueForDeleteOldImages( true, elementNumber );
        });
    });

    resButtons.forEach(function ( button ) {
        button.addEventListener( "click", function () {
            var elementNumber = extractElementNumber( button, 'restore_prev_image_' );
            var resButton = document.getElementById( 'delete_prev_image_' + elementNumber );
            exchangeButtonsProperties( button, resButton );
            switchIfImagesIsForDelete( false, elementNumber );
            changeValueForDeleteOldImages( false, elementNumber );
        });
    });
}

function extractElementNumber( element, extractName ) {
    var name = element.className;
    var match = name.match( new RegExp( extractName + '(\\d+)') );
    return match[ 1 ];
}

function changeSingleImageButtons( button, clickButtonName, visibleButtonName ) {
    var buttonClassName = button.className;
    var match = buttonClassName.match( new RegExp( clickButtonName + '(\\d+)') );

    var buttonNumber = parseInt( match[ 1 ] );
    var resButton = document.getElementById( visibleButtonName + buttonNumber.toString() );
}

function exchangeButtonsProperties( firstButton, secondButton ) {
    firstButton.classList.remove('btn-primary');
    firstButton.classList.add('btn-secondary');
    firstButton.disabled = true;

    secondButton.classList.remove('btn-secondary');
    secondButton.classList.add('btn-primary');
    secondButton.disabled = false;
}

// function switchIfImagesIsForDelete( isForDelete, number ) {
//     let note = document.querySelector( '.image_will_be_delete_note_' + number );
//     let rectangle = document.querySelector( '.delete_image_background_' + number );

//     if( isForDelete == true ) {
//         rectangle.classList.add('delete_image_background_show_after');
//         note.style.display = 'block';
//     } else {
//         rectangle.classList.remove('delete_image_background_show_after');
//         note.style.display = 'none';
//     }
// }

// function changeValueForDeleteOldImages( isForDelete, number ) {
//     let image = document.querySelector( '.old_image_link_' + number );
//     if ( isForDelete == true ) {
//         image.value = 'isForDelete';
//     } else {
//         image.value = 'noDelete';
//     }
// }

function switchIfImagesIsForDelete( isForDelete, number ) {
    let note = document.querySelector( '.image_will_be_delete_note_' + number );
    let rectangle = document.querySelector( '.delete_image_background_' + number );
    switchVisibleRectangleForDeletePicture( isForDelete, rectangle, note );
}

function switchVisibleRectangleForDeletePicture( isForDelete, rectangle, note ) {
    if( isForDelete == true ) {
        rectangle.classList.add('delete_image_background_show_after');
        note.style.display = 'block';
    } else {
        rectangle.classList.remove('delete_image_background_show_after');
        note.style.display = 'none';
    }
}

function checkDefaultDeleteImageSettings() {
    for( i = 1; ; i++) {
        let image = document.querySelector( '.old_image_info_' + i );
        let note = document.querySelector( '.image_will_be_delete_note_' + i );
        let rectangle = document.querySelector( '.delete_image_background_' + i );
        if ( rectangle == null ) {
            break;
        }
        isForDelete = image.value == 'isForDelete' ? true : false;
        if( isForDelete ) {
            let delButtons = document.querySelector( '.delete_prev_image_' + i );
            let resButtons = document.querySelector( '.restore_prev_image_' + i );
            exchangeButtonsProperties( delButtons, resButtons );
        }
        switchVisibleRectangleForDeletePicture(isForDelete, rectangle, note );
    }
}

function changeValueForDeleteOldImages( isForDelete, number ) {
    let image = document.querySelector( '.old_image_info_' + number );
    if ( isForDelete == true ) {
        image.value = 'isForDelete';
    } else {
        image.value = 'noDelete';
    }
}

document.addEventListener("DOMContentLoaded", function () {
    addListenerForAddPicturesFile();
    addListenerForSubmitButtonAndAddFilesArray();
    registerListenerForPrevPicturesButtons();
    checkDefaultDeleteImageSettings();
});
