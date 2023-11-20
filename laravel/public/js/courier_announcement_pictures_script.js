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

    //console.log(uploadedFiles.length);
    if (uploadedFiles.length > 0) {
        errorPictureInfo.type = "hidden";
        errorPictureInfo.name = "is_error_picture_info";
        errorPictureInfo.id = "is_error_picture_info";
        errorPictureInfo.value = true;
    }

    form.appendChild(errorPictureInfo);
    //console.log(form);
}

document.addEventListener("DOMContentLoaded", function () {
    addListenerForAddPicturesFile();
    addListenerForSubmitButtonAndAddFilesArray();
});
