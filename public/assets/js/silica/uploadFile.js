


function sendFile(file, extensionList, maxFileSize, localPathUploadFile,
        lbMsg = "", lbOldFile = "", lbNewFile = "", imgPreview = "") {
    showNotification();
    var uri = "../srepository/_attachs/uploadFile.php";
    var xhr = new XMLHttpRequest();
    var fd = new FormData();
    fd.append('myFile', file);
    fd.append('extensionList', extensionList);
    fd.append('maxFileSize', maxFileSize);
    fd.append('localPathUploadFile', localPathUploadFile);
    
    xhr.open("POST", uri, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = JSON.parse(xhr.responseText);
            var msg = "";
            if (response['status'] == 0) {
                msg = "Tamanho do ficheiro maior do que é suportado.";
            }
            if (response['status'] == -1) {
                msg = "Extensão do ficheiro não é permitida.";
            }
            if (response['status'] == -2) {
                msg = "Não foi possível anexar o ficheiro.";
            }
            if (response['status'] == 1) {
                if (lbOldFile != "") {
                    document.getElementById(lbOldFile).innerHTML ="| " + response['oldFile'];
                }
                if (lbNewFile != "") {
                    document.getElementById(lbNewFile).innerHTML = response['newFile'];
                }
                if (imgPreview != "") {
                    let imgPrev = document.getElementById(imgPreview);
                 //   localPathUploadFile = localPathUploadFile.substring(3);
                    imgPrev.setAttribute('src',  response['prevFile']);
                }
            }
            if (lbMsg != "") {
                document.getElementById(lbMsg).innerHTML = msg;
            }

            hideNotification();
        }
    };
    // Initiate a multipart/form-data upload
    xhr.send(fd);
}