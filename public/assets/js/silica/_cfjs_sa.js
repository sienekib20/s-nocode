
const leadingZero = (num, places) => String(num).padStart(places, '0');
const DOMAIN = 'sepasza.ao';

Number.prototype.countDecimals = function () {
    if (Math.floor(this.valueOf()) === this.valueOf())
        return 0;
    var str = this.toString();
    if (str.indexOf(".") !== -1 && str.indexOf("-") !== -1) {
        return str.split("-")[1] || 0;
    } else if (str.indexOf(".") !== -1) {
        return str.split(".")[1].length || 0;
    }
    return str.split("-")[1] || 0;
};

function round2Dec(num) {
    num = Number(num);
    var m = Number((Math.abs(num) * 100).toPrecision(15));
    return Math.round(m) / 100 * Math.sign(num);
}


ST_SA_WAITING_SERVER = 0;
ST_SA_SEARCHING_SERVER = 0;
ST_SA_LISTING_SERVER = 0;

function validateUserControl(control) {
    if (control === null) {
        return false;
    } else {
        return true;
    }
}


function validateDate(d) {
    return d instanceof Date && !isNaN(d);
}

function daysInMonth(month, year) {
    return new Date(year, month, 0).getDate();
}


function setElementValue(elementId, elementValue = "") {
    var element = document.getElementById(elementId);
    if (validateUserControl(element)) {
        element.value = elementValue;
}
}


function getElementValue(elementId) {
    var element = document.getElementById(elementId);
    if (validateUserControl(element)) {
        return      element.value;
    }
}


function setElementInnerHtml(elementId, elementValue = "") {
    var element = document.getElementById(elementId);
    if (validateUserControl(element)) {
        element.innerHTML = elementValue;
}
}

function getElementInnerHtml(elementId) {
    var element = document.getElementById(elementId);
    if (validateUserControl(element)) {
        return   element.innerHTML;
    }
}


function getElementChecked(elementId) {
    var element = document.getElementById(elementId);
    if (validateUserControl(element)) {
        if (element.checked == true) {
            return 1;
        }
    }
    return 0;
}

function dateDiffDays(date1, date2) {
    var date1d = new Date(date1);
    var date2d = new Date(date2);
    var time = date1d.getTime() - date2d.getTime();
    return (time / (1000 * 60 * 60 * 24));
}

function dateExtension(d) {
    var dt = new Date(d);
    return dt.getDate() + " de " + monthName(dt.getMonth()) + " de " + dt.getFullYear();
}

function dateYearMonthExtension(d) {
    var dt = new Date(d);
    return dt.getFullYear() + "/" + monthName(dt.getMonth());
}

function showParts(id, inline = false) {
    var part = document.getElementById(id);
    if (validateUserControl(part)) {
        if (inline) {
            part.style.display = 'inline';
        } else {
            part.style.display = 'block';
        }
}
}

function showDivSearcher(divId, btnId = "") {
    var div = document.getElementById(divId);
    if (validateUserControl(div)) {
        div.style.display = 'block';
    }
    var btSearch = document.getElementById(btnId);
    if (validateUserControl(btSearch)) {
        btSearch.click();
}
}



function showDivPayment(divId, whoCall) {
    var div = document.getElementById(divId);
    if (validateUserControl(div)) {
        div.style.display = 'block';
    }
    paymentSelectDetails(whoCall);
}



function hideParts(id) {
    var part = document.getElementById(id);
    if (validateUserControl(part)) {
        part.style.display = 'none';
    }
}

function cleanElement(id) {
    var part = document.getElementById(id);
    if (validateUserControl(part)) {
        part.innerHTML = "";
    }
}

function showNotification(msg = "Processando...", sucess = - 1) {
    if ((sucess == 0) || (sucess == 1)) {
        let divBox = document.getElementById("divNotificationBox");
        let divIcon = document.getElementById("divNotificationBoxIcon");

        setElementInnerHtml("pNotificationBoxText", msg);
        if (sucess == 1) {
            divIcon.classList.add("notification-bcg-check");
            divIcon.classList.remove("notification-bcg-not-ckeck");
        } else {
            divIcon.classList.add("notification-bcg-not-ckeck");
            divIcon.classList.remove("notification-bcg-check");
        }
        showParts(divBox.id);
        var duration = msg.length * 100;
        if (duration < 3000) {
            duration = 3000;
        } else if (duration > 10000) {
            duration = 10000;
        }
        setTimeout(function () {
            hideParts(divBox.id);
        }, duration);

        hideNotification();
        return true;
    }
    const div = document.getElementById('divNotification');
    const lb = document.getElementById('lbNotification');
    const divProg = document.getElementById('divNotificationProgress');
    divProg.style.width = "0%";

    if (!validateUserControl(div)) {
        return false;
    }
    div.classList.remove('labelValidate');
    div.classList.remove('labelError');

    if (sucess == 1) {
        div.style.backgroundColor = "rgba(0,176,80)";
        div.classList.add('labelValidate');
    }
    if (sucess == 0) {
        div.style.backgroundColor = "rgba(196,0,0)";
        div.classList.add('labelError');
    }
    div.style.height = "70px";
    lb.innerHTML = msg;

    if (sucess == -1) {
        div.style.backgroundColor = "#2c69cc";
        var pStatus = 0;
        function progress() {
            pStatus += 5;
            divProg.style.width = pStatus + "%";
            if (pStatus < 85) {
                setTimeout(function () {
                    progress();
                }, 500);
            } else if (pStatus == 85) {
                setTimeout(function () {
                    pStatus = 20;
                    progress();
                }, 500);
            }
        }
        progress();
        /*   setTimeout(function () {
         lb.innerHTML = "Parece que a conexão não está boa...";
         div.style.backgroundColor = "rgba(196,0,0)";
         setTimeout(function () {
         div.style.height = "0px";
         }, 2000);
         }, 180000);*/
    } else {
        setTimeout(function () {
            div.style.height = "0px";
        }, 3000);
}
}

function hideNotification() {
    setTimeout(function () {
        document.getElementById('divNotificationProgress').style.width = "100%";
        document.getElementById('divNotification').style.height = "0px";
    }, 1000);
}

function hideDivFilter() {
    const divFilter = document.getElementById('divFilter');
    if (validateUserControl(divFilter)) {
        var span = divFilter.querySelector(".div-fieldset-legend");
        var status = span.getAttribute("status");
        if ((status != 0)) {
            span.dispatchEvent(new Event('click'));
        }
    }
}

function showDivFilter() {
    const divFilter = document.getElementById('divFilter');
    if (validateUserControl(divFilter)) {
        var span = divFilter.querySelector(".div-fieldset-legend");
        var status = span.getAttribute("status");
        if ((status == 0)) {
            span.dispatchEvent(new Event('click'));
        }
    }
}


function setSpanHideFieldSet(buttonId, fieldsetId) {
    const bt = document.getElementById(buttonId);
    const flds = document.getElementById(fieldsetId);
    if (!validateUserControl(bt)) {
        return false;
    }
    if (!validateUserControl(flds)) {
        return false;
    }
    flds.style.display = "none";
    bt.onclick = function () {
        if (window.getComputedStyle(flds).display == "none") {
            flds.style.display = "flex";
            bt.innerHTML = "-";
        } else {
            flds.style.display = "none";
            bt.innerHTML = "+";
        }
    };
}


function showConfirm(question, callBack) {
    const div = document.getElementById('divConfirmOverlay');
    const lbQuest = document.getElementById('lbConfirmQuestion');
    const btConfirm = document.getElementById('btConfirmYes');

    lbQuest.innerHTML = question;
    div.style.display = "block";
    btConfirm.onclick = function () {
        div.style.display = "none";
        callBack();
    };
}

function showPrintOptions(printReceipt, printA4, sendByEmail, sendByWhatsApp,
        customerEmail = "", customerWhatsApp = "", onlyPrint = false) {
    const div = document.getElementById('divPreviewAndPrintAndSend');
    const btReceipt = document.getElementById('btPriPrevPrintReceipt');
    const btA4 = document.getElementById('btPriPrevPrintA4');
    const btEmail = document.getElementById('btPriPrevSendMail');
    const btWhatsApp = document.getElementById('btPriPrevSendWhatsApp');
    const txtEmail = document.getElementById('txtPriPrevCustomerEmail');
    const txtWhatsApp = document.getElementById('txtPriPrevCustomerPhone');

    div.style.display = "block";
    if (onlyPrint) {
        hideParts('divPriPrevSendMail');
        hideParts('divPriPrevSendWhatsApp');
    } else {
        showParts('divPriPrevSendMail');
        showParts('divPriPrevSendWhatsApp');
    }
    txtEmail.value = customerEmail;
    if (customerWhatsApp.length == 9) {
        customerWhatsApp = "244" + customerWhatsApp;
    }
    txtWhatsApp.value = customerWhatsApp;
    btReceipt.onclick = function () {
        div.style.display = "none";
        printReceipt();
    };
    btA4.onclick = function () {
        div.style.display = "none";
        printA4();
    };
    btEmail.onclick = function () {
        customerEmail = txtEmail.value.toLowerCase();
        customerEmail = customerEmail.trim().replace(/[^a-z0-9\_\.\@\;]/g, '').replace(/\s/g, '');
        txtEmail.value = customerEmail;
        if (customerEmail.length <= 0) {
            showNotification("E-mail fora do formato.", 0);
            return false;
        }
        var mails = customerEmail.split(";");
        if (mails.length <= 1) {
            if (!validateEmail(customerEmail)) {
                showNotification("E-mail fora do formato.", 0);
                return false;
            }
        }
        var isOk = true;
        mails.forEach(element => {
            var email = element.toString();
            if (!validateEmail(email)) {
                showNotification("E-mail fora do formato.", 0);
                isOk = false;
                return false;
            }
        });
        if (!isOk) {
            showNotification("E-mail fora do formato.", 0);
            return false;
        }
        div.style.display = "none";
        sendByEmail();
    };
    btWhatsApp.onclick = function () {
        customerWhatsApp = txtWhatsApp.value;
        customerWhatsApp = customerWhatsApp.trim().replace(/[^0-9]/g, "");
        if (customerWhatsApp.slice(0, 2) == "00") {
            customerWhatsApp = customerWhatsApp.slice(2);
        }
        txtWhatsApp.value = customerWhatsApp;
        if (customerWhatsApp.length < 12) {
            showNotification("Número do WhatsApp fora do formato.", 0);
            return false;
        }
        div.style.display = "none";
        sendByWhatsApp();
    };
}


function showHideDivUserInfo(a) {
    var x = document.getElementById("divUserInfo");
    if (window.getComputedStyle(x).display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}

function dateToProductCode(dateX) {
    var date1 = new Date("2000-01-01");
    var Difference_In_Time = dateX.getTime() - date1.getTime();
    return Number(Difference_In_Time / (1000 * 3600 * 24)).toFixed();
}


function subMenuShow(itemId, aId, subItemId) {
    var menuItem = document.getElementById(itemId);
    var aItem = document.getElementById(aId);
    var subMenuItem = document.getElementById(subItemId);
    var status = menuItem.value;
    if (status == 1) {
        subMenuItem.style.display = "none";
        // menuItem.style.background = "none";
        menuItem.classList.remove('menuItemSelect');
        menuItem.value = 0;
        aItem.style.color = "white";
    } else {
        subMenuItem.style.display = "block";
        //  menuItem.style.backgroundColor = "White";

        //  menuItem.style.borderRadius = "9px";
        menuItem.classList.add('menuItemSelect');
        menuItem.value = 1;
        aItem.style.color = "#00007D";
    }
}


function reviewString(control) {
    if (!validateUserControl(control)) {
        return "";
    }
    var strValue = "";
    if (typeof control === 'string' || control instanceof String) {
        strValue = control;
    } else {
        strValue = control.value;
    }

    strValue = strValue.trim().replace(/[^A-zÀ-ú0-9\"\-\.\s\,\!\%\@\(\)]/g, "").replace(/\s{2,}/g, ' ');

    if (typeof control !== 'string') {
        var size = Number(control.maxlength);
        if (!isNaN(size)) {
            if (size > 0) {
                strValue = strValue.slice(0, size);
            }
        }
    }

    return strValue;
    /*  if (required){
     if (strValue.trim() == ""){
     alert("Campo obrigatório vazio.");
     return false;
     }
     }
     if (/[^a-zA-Z0-9\-\.]/.test(strValue)) {
     alert('Não deve utilizar caracteres especiais.');
     return false;
     }
     return true;*/
}

function reviewEmail(strValue) {
    strValue = strValue.toString().toLowerCase();
    strValue = strValue.trim().replace(/[^a-z0-9\_\-\.\@]/g, '').replace(/\s/g, '');
    return strValue;
}

function validateEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function getFirtLastName(fullName) {
    if (fullName) {
        fullName = reviewString(fullName);
        var names = fullName.toString().split(" ");
        if (names.length <= 1) {
            return fullName;
        }
        return names[0] + " " + names[names.length - 1];
    } else {
        return "";
    }

}

function getOperatorName(operatorName, operatorId) {
    return getFirtLastName(operatorName) + " [" + operatorId + "]";
}

function validateTaxId(taxId) {
    if (taxId.length < 10) {
        return false;
    }
    var numericPart = taxId.substring(0, 9);
    if (isNaN(numericPart)) {
        return false;
    }
    return true;
}


function formatNumber(num) {
    num = Number(num);
    return (
            num
            .toFixed(2) // always two decimal digits
            .replace('.', ',') // replace decimal point character with ,
            .replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1 ')
            ); // use . as a separator
}


function selectYesNoChangeColor(sel) {
    if (validateUserControl(sel)) {
        sel.title = sel.value;
    }
}

function selectCountryChange(country, province, municipality, city) {
    const selCountry = document.getElementById(country);
    const selProvince = document.getElementById(province);
    const selMunicipality = document.getElementById(municipality);
    const lbCity = document.getElementById(city);
    if (validateUserControl(selCountry)) {
        selCountry.onchange = function () {
            if (selCountry.value == 1) {
                selProvince.disabled = false;
                selMunicipality.disabled = false;
                if (validateUserControl(lbCity)) {
                    lbCity.innerHTML = "Cidade";
                }

            } else {
                selProvince.disabled = true;
                selProvince.value = -1;
                selMunicipality.disabled = true;
                selMunicipality.value = -1;
                if (validateUserControl(lbCity)) {
                    lbCity.innerHTML = "Cidade / Estado";
                }
            }
        };
    }
}


function setCookie(cname, cvalue, exmin) {
    var d = new Date();
    d.setTime(d.getTime() + (exmin * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}


function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function showHttpResponseText(http) {
    const url = window.location.href;
    if ((url.includes("localhost")) || (url.includes("gme.ao"))) {
        alert(http.responseText);
    } else {
        showNotification("Não foi possível executar esta operação", 0);
    }
}


function printPageA4(title = "", subtitle = "", printForm = false) {
    showParts('divPrintOptions');
    document.getElementById('txtPrintOptionTitle').value = title;
    document.getElementById('txtPrintOptionSubtitle').value = subtitle;

    var companyId = SYSTEM_USER_STATUS.companyId;
    var dependencyId = SYSTEM_USER_STATUS.workplaceId;
    var cookiename = companyId + dependencyId + "printOptionSignature";

    fillSelectPrintOptionSignature("selPrintOptionVisaBy", -1);
    fillSelectPrintOptionSignature("selPrintOptionSignature1", -1);
    fillSelectPrintOptionSignature("selPrintOptionSignature2", -1);
    fillSelectPrintOptionSignature("selPrintOptionSignature3", -1);

    var visa = getCookie(cookiename + 0);
    if ((visa != null) && (visa != "")) {
        var signature = JSON.parse(visa);
        document.getElementById('txtPrintOptionVisaQuality').value = signature['quality'];
        document.getElementById('txtPrintOptionVisaPerson').value = signature['person'];
    }

    var sign1 = getCookie(cookiename + 1);
    if ((sign1 != null) && (sign1 != "")) {
        var signature = JSON.parse(sign1);
        document.getElementById('txtPrintOptionSignatureQuality1').value = signature['quality'];
        document.getElementById('txtPrintOptionSignaturePerson1').value = signature['person'];
    }

    var sign2 = getCookie(cookiename + 2);
    if ((sign2 != null) && (sign2 != "")) {
        var signature = JSON.parse(sign2);
        document.getElementById('txtPrintOptionSignatureQuality2').value = signature['quality'];
        document.getElementById('txtPrintOptionSignaturePerson2').value = signature['person'];
    }

    var sign3 = getCookie(cookiename + 3);
    if ((sign3 != null) && (sign3 != "")) {
        var signature = JSON.parse(sign3);
        document.getElementById('txtPrintOptionSignatureQuality3').value = signature['quality'];
        document.getElementById('txtPrintOptionSignaturePerson3').value = signature['person'];
    }

    var divs = document.getElementsByClassName("no-print-on-form");
    Array.from(divs).forEach(div => {
        if (printForm) {
            div.classList.add("noPrint");
        } else {
            div.classList.remove("noPrint");
        }
    });
}

function printOptionPrint() {
    hideParts('divPrintOptions');

    const lbTitle = document.getElementById('lbPrintHeaderDocumentTitle');
    const lbSubTitle = document.getElementById('lbPrintHeaderDocumentSubTitle');
    const divVisa = document.getElementById('divPrintHeaderVisaBox');
    const divSignature1 = document.getElementById('divPrintFooterSignatureBox1');
    const divSignature2 = document.getElementById('divPrintFooterSignatureBox2');
    const divSignature3 = document.getElementById('divPrintFooterSignatureBox3');

    var title = document.getElementById('txtPrintOptionTitle').value.trim();
    var subTitle = document.getElementById('txtPrintOptionSubtitle').value.trim();
    var visaQuality = document.getElementById('txtPrintOptionVisaQuality').value.trim();
    var visaPerson = document.getElementById('txtPrintOptionVisaPerson').value.trim();
    var signatureQuality1 = document.getElementById('txtPrintOptionSignatureQuality1').value.trim();
    var signaturePerson1 = document.getElementById('txtPrintOptionSignaturePerson1').value.trim();
    var signatureQuality2 = document.getElementById('txtPrintOptionSignatureQuality2').value.trim();
    var signaturePerson2 = document.getElementById('txtPrintOptionSignaturePerson2').value.trim();
    var signatureQuality3 = document.getElementById('txtPrintOptionSignatureQuality3').value.trim();
    var signaturePerson3 = document.getElementById('txtPrintOptionSignaturePerson3').value.trim();


    if (title == "") {
        hideParts(lbTitle.id);
    } else {
        showParts(lbTitle.id);
        lbTitle.innerHTML = title;
    }

    if (subTitle == "") {
        hideParts(lbSubTitle.id);
    } else {
        showParts(lbSubTitle.id);
        lbSubTitle.innerHTML = subTitle;
    }

    if ((visaQuality != "") && (visaPerson != "")) {
        showParts(divVisa.id);
        document.getElementById('lbPrintHeaderVisaQuality').innerHTML = visaQuality;
        document.getElementById('lbPrintHeaderVisaPerson').innerHTML = visaPerson;
    } else {
        hideParts(divVisa.id);
    }

    if ((signatureQuality1 != "") && (signaturePerson1 != "")) {
        showParts(divSignature1.id);
        document.getElementById('lbPrintFooterSignatureQuality1').innerHTML = signatureQuality1;
        document.getElementById('lbPrintFooterSignaturePerson1').innerHTML = signaturePerson1;
    } else {
        hideParts(divSignature1.id);
    }

    if ((signatureQuality2 != "") && (signaturePerson2 != "")) {
        showParts(divSignature2.id);
        document.getElementById('lbPrintFooterSignatureQuality2').innerHTML = signatureQuality2;
        document.getElementById('lbPrintFooterSignaturePerson2').innerHTML = signaturePerson2;
    } else {
        hideParts(divSignature2.id);
    }

    if ((signatureQuality3 != "") && (signaturePerson3 != "")) {
        showParts(divSignature3.id);
        document.getElementById('lbPrintFooterSignatureQuality3').innerHTML = signatureQuality3;
        document.getElementById('lbPrintFooterSignaturePerson3').innerHTML = signaturePerson3;
    } else {
        hideParts(divSignature3.id);
    }

    printOptionSaveSignature();

    // var curURL = window.location.href;
    // history.replaceState(history.state, '', '/');///

    var pageTitle = document.title;
    document.title = "Processado por programa validado nº 271/AGT/2020 - GME Sílica.";

    window.print();
    //  history.replaceState(history.state, '', curURL);
    document.title = pageTitle;

}



function invoiceDucumentSerie(docType, request = 10) {
    var year = new Date().getFullYear();
    var companyId = SYSTEM_USER_STATUS.companyId;
    var dependencyId = SYSTEM_USER_STATUS.workplaceId;
    var userId = SYSTEM_USER_STATUS.userId
    if ((isNaN(dependencyId)) || Number(dependencyId) <= 0) {
        dependencyId = "";
    }
    return wordToNumberPitagoras(docType) + userId + year.toString();
}


var SYSTEM_USER_STATUS = {sessionId: "", systemtype: 1, companyId: -1, taxGroup: 1, userId: -1, email: "", userFullName: "", billingprofile: 1,
    coordinatorUser: -1, managerUser: -1, directorGeneralUser: -1, administratorUser: -1, partnershipUser: 1,
    udaoxuser: 0, permissionlevel: -1, workplaceId: -1, billingStockId: -1, dependecyName: "", municipalityId: -1,
    municipalityName: "", provinceId: -1, provinceName: ""};

var SYSTEM_USER_PERMISSION = {};

var LICENSE_STATUS = {status: 0, type: "", duration: 0, startdate: "", enddate: "", selfdomain: 0,
    maxusers: 0, maxproducts: 0, maxinvoices: 0, saleamount: 0, salepercentage: 0, licenselevel: 1,
    currentUsers: 0, currentInvoices: 0, currentAmount: 0};

function setSystemUserStatus(a) {

    var phttp = new XMLHttpRequest();
    var params = "action=getPermissionInfo";
    var url = '_aqconections/_userPermission.php';
    phttp.open('POST', url, true);
    phttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    phttp.onreadystatechange = function () {
        if (phttp.readyState == 4 && phttp.status == 200) {
            var PAGE_PERMISSION = phttp.responseText;
            if (PAGE_PERMISSION == 1) {
                var uhttp = new XMLHttpRequest();
                var params = "action=getSystemUserInfo";
                var url = '_aqconections/_userPermission.php';
                uhttp.open('POST', url, true);
                uhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                uhttp.onreadystatechange = function () {
                    if (uhttp.readyState == 4 && uhttp.status == 200) {
                        var infoData = JSON.parse(uhttp.responseText);
                        SYSTEM_USER_STATUS.sessionId = infoData['sessionid'];
                        SYSTEM_USER_STATUS.companyId = infoData['companyid'];
                        SYSTEM_USER_STATUS.userId = infoData['userid'];
                        SYSTEM_USER_STATUS.email = infoData['email'];
                        SYSTEM_USER_STATUS.userFullName = infoData['userfullname'];
                        SYSTEM_USER_STATUS.billingprofile = infoData['billingprofile'];
                        SYSTEM_USER_STATUS.coordinatorUser = (infoData['coordinatoruser'] != -1) ? infoData['coordinatoruser'] : SYSTEM_USER_STATUS.userId;
                        SYSTEM_USER_STATUS.managerUser = (infoData['manageruser'] != -1) ? infoData['manageruser'] : SYSTEM_USER_STATUS.userId;
                        SYSTEM_USER_STATUS.directorGeneralUser = (infoData['directorgeneraluser'] != -1) ? infoData['directorgeneraluser'] : SYSTEM_USER_STATUS.userId;
                        SYSTEM_USER_STATUS.administratorUser = (infoData['administratoruser'] != -1) ? infoData['administratoruser'] : SYSTEM_USER_STATUS.userId;
                        SYSTEM_USER_STATUS.partnershipUser = infoData['partnershipuser'];
                        SYSTEM_USER_STATUS.udaoxuser = infoData['udaoxuser'];
                        SYSTEM_USER_STATUS.permissionlevel = infoData['permissionlevel'];
                        SYSTEM_USER_STATUS.workplaceId = infoData['workplaceid'];
                        SYSTEM_USER_STATUS.billingStockId = infoData['billingStockId'];
                        SYSTEM_USER_STATUS.dependecyName = infoData['dependency'];
                        SYSTEM_USER_STATUS.municipalityId = infoData['municipalityid'];
                        SYSTEM_USER_STATUS.municipalityName = infoData['municipalityname'];
                        SYSTEM_USER_STATUS.provinceId = infoData['provinceid'];
                        SYSTEM_USER_STATUS.provinceName = infoData['provinceName'];

                        if (infoData['accountstatus'] != 1) {
                            alert("Conta de utilizador desactivada. <br>" +
                                    "Por favor contactar o administrador do sistema.");
                            setTimeout(function () {
                                window.location.href = "index.php";
                            }, 3000);
                        }

                        document.getElementById('lbHeaderUserFullname').innerHTML = SYSTEM_USER_STATUS.userFullName;
                        document.getElementById('lbHeaderUserFullname2').innerHTML = SYSTEM_USER_STATUS.userFullName;
                        document.getElementById('imgHeaderUserLogedPhoto').src = infoData['photo'];
                        if (infoData['needconfirmemail'] == 1) {
                            document.getElementById('btHeaderConfirmEmail').style.display = "block";
                        }
                        if (SYSTEM_USER_STATUS.companyId == 5000) {
                            showParts('btHeaderPromotionSilica');
                        }

                        setSystemUserPermissions();

                        licenseValidation();

                        var http = new XMLHttpRequest();
                        var params = "action=getCompanyInfo";
                        var url = '_aqconections/_userPermission.php';
                        http.open('POST', url, true);
                        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                        http.onreadystatechange = function () {
                            if (http.readyState == 4 && http.status == 200) {
                                var infoData = JSON.parse(http.responseText);
                                SYSTEM_USER_STATUS.taxGroup = infoData['ivagroup'];
                                SYSTEM_USER_STATUS.systemtype = infoData['systemtype'];
                                document.getElementById('lbHearderCompanyName').innerHTML = infoData['companyname'];
                                document.getElementById('lbHeaderCompanyTaxId').innerHTML = "NIF: " + infoData['companytaxid'].bold();
                                document.getElementById('lbHeaderCompanyNus').innerHTML = "N.U.S: " + SYSTEM_USER_STATUS.companyId.bold();
                                document.getElementById('lbHeaderCompanyIVA').innerHTML = "IVA: " + regimeIva(SYSTEM_USER_STATUS.taxGroup).bold();
                                document.getElementById('lbHeaderDependency').innerHTML = "Filial: " + SYSTEM_USER_STATUS.dependecyName;
                            }
                        };
                        http.send(params);
                    }
                };
                uhttp.send(params);

            } else if (PAGE_PERMISSION == -1) {
                setTimeout(function () {
                    var path = window.location.href;
                    window.location.href = "index.php?wc=" + path.substring(path.lastIndexOf('/') + 1);
                }, 2000);
                showNotification("Precisar fazer login.");
            } else if (PAGE_PERMISSION == 0) {
                var path = window.location.href;
                if (!path.includes("/home.php")) {
                    setTimeout(function () {
                        window.location.href = "home.php?frc=1";
                    }, 2000);
                }
                showNotification("Não tem permissão para acessar esta página.");
            } else if (PAGE_PERMISSION == -2) {
                hideNotification();
                showParts("divAlertOutOfWorkSchedule");
            }
        }
    };
    phttp.send(params);

}

function licenseValidation() {
    var http = new XMLHttpRequest();
    var params = "action=getCompanyLicenseStatus";
    var url = '_aqconections/_userPermission.php';
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (http.readyState == 4 && http.status == 200) {
            var lic = JSON.parse(http.responseText);
            if (lic['status'] == 1) {
                LICENSE_STATUS.status = lic['status'];
                LICENSE_STATUS.type = lic['designation'];
                LICENSE_STATUS.duration = lic['duration'];
                LICENSE_STATUS.startdate = lic['startdate'];
                LICENSE_STATUS.enddate = lic['enddate'];
                LICENSE_STATUS.selfdomain = lic['selfdomain'];
                LICENSE_STATUS.maxusers = lic['maxusers'];
                LICENSE_STATUS.maxproducts = lic['maxproducts'];
                LICENSE_STATUS.maxinvoices = lic['maxinvoices'];
                LICENSE_STATUS.saleamount = lic['saleamount'];
                LICENSE_STATUS.salepercentage = lic['salepercentage'];
                LICENSE_STATUS.licenselevel = lic['licenselevel'];
                LICENSE_STATUS.currentUsers = lic['currentUsers'];
                LICENSE_STATUS.currentInvoices = lic['currentInvoices'];
                LICENSE_STATUS.currentAmount = lic['currentAmount'];
            }
        }

        setElementInnerHtml('lbHeaderLicenseStatus', "Licença: " + LICENSE_STATUS.type + ". <br>" +
                "Data de activação: " + LICENSE_STATUS.startdate.bold() + " <br>" +
                "Data de validade: " + LICENSE_STATUS.enddate.bold());
    };
    http.send(params);
}

function setSystemUserPermissions() {
    //  SYSTEM_USER_PERMISSION = {};
    var http = new XMLHttpRequest();
    var params = "action=getSystemUserPermissions";
    var url = '_aqconections/_userPermission.php';
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (http.readyState == 4 && http.status == 200) {
            SYSTEM_USER_PERMISSION = JSON.parse(http.responseText);
        }
    };
    http.send(params);
}


function setProcessedByON() {
    setElementInnerHtml("pPrintFooterProcessedBy", "Processado por " + getFirtLastName(SYSTEM_USER_STATUS.userFullName) +
            ", em " + setLocalDate(new Date, 10))
}





//var ATTEMPTS = 0;
//var ATTEMPTS_LICENSE = 0;
setCookie("ATTEMPTS", 0, 3000);
setCookie("ATTEMPTS_LICENSE", 0, 3000);
function checkUserStatusAfterExecute(funcao) {
    var ATTEMPTS = Number(getCookie("ATTEMPTS"));
    ATTEMPTS += 1;
    setCookie("ATTEMPTS", ATTEMPTS, 3000);
    var maxAttempts = 120;
    var maxAttemptsLicense = 40;
    if (SYSTEM_USER_STATUS.companyId <= 0) {
        document.getElementById('lbNotification').innerHTML = "Verificando estado do utilizador. " + Number(maxAttempts - 1 - ATTEMPTS) + "''";
        setTimeout(function () {
            checkUserStatusAfterExecute(funcao);
        }, 1000);
        if (ATTEMPTS == maxAttempts) {
            setCookie("ATTEMPTS", 0, 3000);
            alert("Problema de comunicação. Precisar fazer login.");
            setTimeout(function () {
                window.location.href = "index.php";
            }, 2000);
        }
    } else {
        var ATTEMPTS_LICENSE = Number(getCookie("ATTEMPTS_LICENSE"));
        document.getElementById('lbNotification').innerHTML = "Procurando licença. Por favor não sair da página. " + Number(maxAttemptsLicense - ATTEMPTS_LICENSE) + "''";

        ATTEMPTS_LICENSE += 1;
        setCookie("ATTEMPTS_LICENSE", ATTEMPTS_LICENSE, 3000);
        if (LICENSE_STATUS.status != 1) {
            setTimeout(function () {
                checkUserStatusAfterExecute(funcao);
            }, 1000);
            if (ATTEMPTS_LICENSE == maxAttemptsLicense) {
                setCookie("ATTEMPTS_LICENSE", 0, 3000);
                showParts('divAlertNoLicense');
                hideNotification();
            }
        } else {
            setCookie("ATTEMPTS", 0, 3000);
            setCookie("ATTEMPTS_LICENSE", 0, 3000);
            //  setGlobalDictionary(SYSTEM_USER_STATUS.systemtype);
            getAllSelects();
            hideShortcuts();
            funcao();
            setSessionTimeOut();
            setProcessedByON();
            setDivFieldsetLegend();
            setVirtualKeyboardOnInput();
            if (getCookie("sms_lockwindows") == 1) {
                lockWindowShow();
            }
        }
    }
}


function checkPermission(permission) {
    if (SYSTEM_USER_PERMISSION.length <= 0) {
        return false;
    } else {
        permission = "p" + permission;
        if (permission in SYSTEM_USER_PERMISSION) {
            //alert(SYSTEM_USER_PERMISSION['licenselevel']);
            return !!+SYSTEM_USER_PERMISSION[permission];
        } else {
            return false;
        }
    }
}


function setSessionTimeOut() {
    var totalSecond = 1201;
    var timeToClose = totalSecond;
    var lbSessionTime = document.getElementById('lbHeaderSessionTime');
    lbSessionTime.style.animation = "";
    lbSessionTime.style.fontSize = "small";
    lbSessionTime.style.fontWeight = "normal";
    function displayTime() {
        totalSecond -= 1;
        if (validateUserControl(lbSessionTime)) {
            lbSessionTime.innerHTML = sec2time(totalSecond);
            if (totalSecond == 60) {
                lbSessionTime.style.fontSize = "large";
                lbSessionTime.style.fontWeight = "bold";
            }
            if (totalSecond == 30) {
                lbSessionTime.style.animation = "blink-text 1.0s step-end infinite";
            }
            if (totalSecond <= 0) {
                return false;
            }
        }
        setTimeout(function () {
            displayTime();
        }, 1000);
    }
    displayTime();
    setTimeout(function () {
        lockWindowShow();
    }, timeToClose * 1000);
}



function setSystemType(a) {
    SYSTEM_TYPES = [];
    SYSTEM_TYPES[1] = "Genérico";
    SYSTEM_TYPES[2] = "Uso doméstico";
    SYSTEM_TYPES[3] = "Gestor de mercado informal";
    SYSTEM_TYPES[4] = "Gestor de condomínio";
}


function setDocumentType(a) {
    DOCUMENT_TYPE = [];
    DOCUMENT_TYPE['FT'] = "FACTURA";
    DOCUMENT_TYPE['FR'] = "FACTURA RECIBO";
    DOCUMENT_TYPE['VD'] = "VENDA A DINHEIRO";
    DOCUMENT_TYPE['RC'] = "RECIBO";
    DOCUMENT_TYPE['RE'] = "RECIBO";
    DOCUMENT_TYPE['RG'] = "RECIBO";
    DOCUMENT_TYPE['PP'] = "PROFORMA";
    DOCUMENT_TYPE['ND'] = "NOTA DE DÉBITO";
    DOCUMENT_TYPE['NC'] = "NOTA DE CRÉDITO";
    DOCUMENT_TYPE['DC'] = "DOC. CONFERÊNCIA";
    DOCUMENT_TYPE['GR'] = "GUIA DE REMESSA";
    DOCUMENT_TYPE['GA'] = "MOVIMENTAÇÃO";
    DOCUMENT_TYPE['NA'] = "ADIANTAMENTO";
    DOCUMENT_TYPE['GE'] = "GUIA DE ENTRADA";
    DOCUMENT_TYPE['GF'] = "FACTURA GENÉRICA";
    DOCUMENT_TYPE['FG'] = "FACTURA GLOBAL";
    DOCUMENT_TYPE['AC'] = "AVISO DE COBRANÇA";
    DOCUMENT_TYPE['AR'] = "AVISO DE COBRANÇA/RECIBO";
    DOCUMENT_TYPE['AF'] = "FACTURA / RECIBO (autofacturação)";
    DOCUMENT_TYPE['TV'] = "TALÃO DE VENDA";
    DOCUMENT_TYPE['TS'] = "TALÃO DE SERVIÇO PRESTADO";
    DOCUMENT_TYPE['TD'] = "TALÃO DE DEVOLUÇÃO";
    DOCUMENT_TYPE['AA'] = "ALIENAÇÃO DE ACTIVOS";
    DOCUMENT_TYPE['DA'] = "DEVOLUÇÃO DE ACTIVOS";
    DOCUMENT_TYPE['GT'] = "GUIA DE TRANSPORTE";
    DOCUMENT_TYPE['GC'] = "GUIA DE CONCIGNAÇÃO";
    DOCUMENT_TYPE['GD'] = "GUIA DE DEVOLUÇÃO";
    DOCUMENT_TYPE['CM'] = "CONSULTA DE MESA";
    DOCUMENT_TYPE['CC'] = "CRÉDITO DE CONSIGNAÇÃO";
    DOCUMENT_TYPE['NR'] = "NOTA DE REMESSA";
    DOCUMENT_TYPE['FO'] = "FOLHA DE OBRA";
    DOCUMENT_TYPE['NE'] = "NOTA DE ENCOMENDA";
    DOCUMENT_TYPE['OR'] = "ORÇAMENTO";

    DOCUMENT_TYPE['CI'] = "CONSUMO INTERNO";
    DOCUMENT_TYPE['AE'] = "ABATE DE ESTOQUE";
}

function setMovimentTargetType(a) {
    MOVIMENT_TARGET_TYPE = [];
    MOVIMENT_TARGET_TYPE[1] = "Cliente com factura";
    MOVIMENT_TARGET_TYPE[2] = "Cliente sem factura";
    MOVIMENT_TARGET_TYPE[3] = "Movimento entre depedências (posto)";
    MOVIMENT_TARGET_TYPE[4] = "Movimento Interno";
    MOVIMENT_TARGET_TYPE[5] = "Consumo Interno";
    MOVIMENT_TARGET_TYPE[6] = "Abate de estoque";
    MOVIMENT_TARGET_TYPE[7] = "Devolução ao fornecedor";
}


class _paymentMechanism {
    constructor(id, mechanism = "", short = "", shortplus = "", initials = "") {
        this.id = id;
        this.mechanism = mechanism;
        this.short = short;
        this.shortplus = shortplus;
        this.initials = initials;
    }
}


function setPaymentMechanism(a) {
    PAYMENT_MECHANISM = [];
    PAYMENT_MECHANISM[1] = new _paymentMechanism(1, 'Numerário', 'Numerário', '', 'NU');
    PAYMENT_MECHANISM[2] = new _paymentMechanism(2, 'Terminal de Pagamento Automático', 'TPA', 'Per:Tr:M:', 'CD');
    PAYMENT_MECHANISM[3] = new _paymentMechanism(3, 'Depósito Bancário', 'Depósito', 'Bordx', 'TB');
    PAYMENT_MECHANISM[4] = new _paymentMechanism(4, 'Transferência Bancária', 'Transferência', '', 'TB');
    PAYMENT_MECHANISM[5] = new _paymentMechanism(5, 'Compensação de Saldo em Conta Corrente', 'Conta Corrente', '', 'CS');
    PAYMENT_MECHANISM[6] = new _paymentMechanism(6, 'Referencia de Pagamento para Multicaixa', 'Ref.Multicaixa', 'Ref', 'MB');
    PAYMENT_MECHANISM[7] = new _paymentMechanism(7, 'Cartão de Crédito', 'C. Crédito', 'Ref', 'CC');
    PAYMENT_MECHANISM[8] = new _paymentMechanism(8, 'Cheque Bancário', 'Cheque', 'Nº', 'CH');
    PAYMENT_MECHANISM[9] = new _paymentMechanism(9, 'Dinheiro Electrónico', 'DE', '', 'DE');
    PAYMENT_MECHANISM[10] = new _paymentMechanism(10, 'Outro', 'Outro', '', 'OU');
    PAYMENT_MECHANISM[11] = new _paymentMechanism(11, 'Código QR', 'QR', '', 'TB');
    PAYMENT_MECHANISM[12] = new _paymentMechanism(12, 'PIX', 'PIX', '', 'TB');
    PAYMENT_MECHANISM[100] = new _paymentMechanism(100, 'Troco', 'Troco', '', 'TR');
}

function setInstallmentStatus(a) {
    INSTALLMENT_STATUS = [];
    INSTALLMENT_STATUS['AP'] = 'Aguarda pagamento';
    INSTALLMENT_STATUS['PP'] = 'Pagamento parcial';
    INSTALLMENT_STATUS['PT'] = 'Pagamento total';
}

function setDocumentRequestSource(a) {
    DOCUMENT_REQUEST_SOURCE = [];
    DOCUMENT_REQUEST_SOURCE[10] = "Presencial";
    DOCUMENT_REQUEST_SOURCE[15] = "Cartão digital";
    DOCUMENT_REQUEST_SOURCE[20] = "Online";
}

function setCustomerType(a) {
    CUSTOMER_TYPE = [];
    //CUSTOMER_TYPE[0] = "Por indicar.";
    CUSTOMER_TYPE[1] = "Pessoa Singular";
    CUSTOMER_TYPE[2] = "Empresa";//"Profissional Autónomo";
    CUSTOMER_TYPE[3] = "Grupo";//"Sociedade Unipessoal (SU)";
    /*  CUSTOMER_TYPE[4] = "Empresa em nome individual";
     CUSTOMER_TYPE[5] = "Sociedade por quota (Lda)";
     CUSTOMER_TYPE[6] = "Sociedade Anónima (S.A.)";
     CUSTOMER_TYPE[7] = "Empresa Pública (EP)";
     CUSTOMER_TYPE[8] = "Instituto Público";
     CUSTOMER_TYPE[9] = "Instituto Pmpresarial";
     CUSTOMER_TYPE[10] = "Cooperativa";
     CUSTOMER_TYPE[11] = "Organização Sindical";
     CUSTOMER_TYPE[12] = "Organização Não Governamental (ONG)";
     CUSTOMER_TYPE[13] = "Organização Religiosa";
     CUSTOMER_TYPE[14] = "Organização da Sociedade Civil";
     CUSTOMER_TYPE[15] = "Associação";
     CUSTOMER_TYPE[16] = "Fundação";*/
}

var BILLING_PROFILE_SELLER_INDEX = 1;
var BILLING_PROFILE_MANAGER_INDEX = 10;
var BILLING_PROFILE_PARTNER_INDEX = 20;
var LICENSE_STATUS = [];
var LINK_REQUEST_STATUS = [];
var CONSUMER_TYPE_AQUA = [];
var HYDROMETER_RECORD_STATUS = [];
var WATER_LINK_STATUS = [];
var NEIBORHOOD_CIL_SERIE = [];
var COMPLAINT_STATUS = [];

function setLicenseStatus(a) {
    LICENSE_STATUS = [];
    LICENSE_STATUS[-1] = "Suspensa";
    LICENSE_STATUS[0] = "Expirada";
    LICENSE_STATUS[1] = "Activa";
    LICENSE_STATUS[2] = "Em espera";
}


function setSystemUserBillingProfile(a) {
    SYSTEM_USER_BILLING_PROFILE = [];
    SYSTEM_USER_BILLING_PROFILE[0] = "Agente";
    SYSTEM_USER_BILLING_PROFILE[BILLING_PROFILE_SELLER_INDEX] = "Assistente";
    SYSTEM_USER_BILLING_PROFILE[BILLING_PROFILE_MANAGER_INDEX] = "Responsável de loja";
    // SYSTEM_USER_BILLING_PROFILE['3'] = "Gerente provincial / Regional";
    //  SYSTEM_USER_BILLING_PROFILE['4'] = "Gerente regional";
    //   SYSTEM_USER_BILLING_PROFILE['4'] = "Gerente nacional";
    //  SYSTEM_USER_BILLING_PROFILE['5'] = "Director nacional";
    //   SYSTEM_USER_BILLING_PROFILE['6'] = "Director Geral";
    //  SYSTEM_USER_BILLING_PROFILE['7'] = "Administrador";
    SYSTEM_USER_BILLING_PROFILE[BILLING_PROFILE_PARTNER_INDEX] = "Chefe de departamento";

    SYSTEM_USER_TITLE = [];
    SYSTEM_USER_TITLE['1'] = 'Sr.';
    SYSTEM_USER_TITLE['2'] = 'Sra.';
    SYSTEM_USER_TITLE['3'] = 'Lic.';
    SYSTEM_USER_TITLE['4'] = 'Dr.';
    SYSTEM_USER_TITLE['5'] = 'Dra.';
    SYSTEM_USER_TITLE['6'] = 'Msc.';
    SYSTEM_USER_TITLE['7'] = 'PhD.';

}


function setProductChargeType(a) {
    PRODUCT_CHARGE_TYPES = [];
    PRODUCT_CHARGE_TYPES[1] = "Padrão";
    PRODUCT_CHARGE_TYPES[2] = "Diária";
    PRODUCT_CHARGE_TYPES[3] = "Mensal";

    PRODUCT_CHARGE_SEQUENCES = [];
    PRODUCT_CHARGE_SEQUENCES[1] = "Padrão";
    PRODUCT_CHARGE_SEQUENCES[2] = "Sem sequência";
    PRODUCT_CHARGE_SEQUENCES[3] = "Sequência obrigatória";

    PRODUCT_INSTALLMENT_INDICATORS = [];
    PRODUCT_INSTALLMENT_INDICATORS[1] = "Padrão";
    PRODUCT_INSTALLMENT_INDICATORS[2] = "Automático";
    PRODUCT_INSTALLMENT_INDICATORS[3] = "Manual";
}


function setLinkRequestStatus() {
    LINK_REQUEST_STATUS = [];
    LINK_REQUEST_STATUS[1] = "Aguarda aprovação";
    LINK_REQUEST_STATUS[2] = "Aprovado";
    LINK_REQUEST_STATUS[3] = "Anulado";
}

function setWaterLinkStatus() {
    WATER_LINK_STATUS = [];
    WATER_LINK_STATUS[0] = "Aguarda activação";
    WATER_LINK_STATUS[1] = "Activa";
    WATER_LINK_STATUS[3] = "Suspensa";
}



function setConsumerTypeAqua() {
    CONSUMER_TYPE_AQUA = [];
    CONSUMER_TYPE_AQUA[1] = "Doméstico";
    CONSUMER_TYPE_AQUA[2] = "Comércio";
    CONSUMER_TYPE_AQUA[3] = "Serviços";
    CONSUMER_TYPE_AQUA[4] = "Indústria";
    CONSUMER_TYPE_AQUA[5] = "Chafariz";
    CONSUMER_TYPE_AQUA[6] = "Girafa";
}

function setHydrometerRecordStatus() {
    HYDROMETER_RECORD_STATUS = [];
    HYDROMETER_RECORD_STATUS[1] = "Aguarda factura";
    HYDROMETER_RECORD_STATUS[2] = "Facturado";
}

function setComplaintStatus() {
    COMPLAINT_STATUS = [];
    COMPLAINT_STATUS[1] = "Em aberto";
    COMPLAINT_STATUS[2] = "Resolvido";
    COMPLAINT_STATUS[3] = "Anulada";
}


function monthName(month) {
    var mName = [];
    mName[0] = "Janeiro";
    mName[1] = "Fevereiro";
    mName[2] = "Março";
    mName[3] = "Abril";
    mName[4] = "Maio";
    mName[5] = "Junho";
    mName[6] = "Julho";
    mName[7] = "Agosto";
    mName[8] = "Setembro";
    mName[9] = "Outubro";
    mName[10] = "Novembro";
    mName[11] = "Dezembro";

    return mName[month];
}

function weekDay(day, short = 0) {
    var wday = new Array(7);
    wday[0] = ["Domingo", "Dom"];
    wday[1] = ["Segunda-feira", "Seg"];
    wday[2] = ["Terça-feira", "Ter"];
    wday[3] = ["Quarta-feira", "Qua"];
    wday[4] = ["Quinta-feira", "Qui"];
    wday[5] = ["Sexta-feira", "Sex"];
    wday[6] = ["Sábado", "Sáb"];
    return wday[day][short];
}


var SYSTEM_TYPES = [];
var DOCUMENT_TYPE = [];
var MOVIMENT_TARGET_TYPE = [];
var PAYMENT_MECHANISM = [];
var INSTALLMENT_STATUS = [];
var DOCUMENT_REQUEST_SOURCE = [];
var CUSTOMER_TYPE = [];
var SYSTEM_USER_BILLING_PROFILE = [];
var PRODUCT_CHARGE_TYPES = [];
var PRODUCT_CHARGE_SEQUENCES = [];
var PRODUCT_INSTALLMENT_INDICATORS = [];
var PERIODIC_SERVICE_LIST = [];
var CUSTOMER_PERIODIC_SERVICE_LIST = [];

var LABEL_EDIT = reviewString(getComputedStyle(document.documentElement).getPropertyValue('--edit'));
var LABEL_DETAIL = reviewString(getComputedStyle(document.documentElement).getPropertyValue('--detail'));
var LABEL_PRINT = reviewString(getComputedStyle(document.documentElement).getPropertyValue('--print'));
var LABEL_DELETE = reviewString(getComputedStyle(document.documentElement).getPropertyValue('--delete'));
var LABEL_REMOVE = reviewString(getComputedStyle(document.documentElement).getPropertyValue('--remove'));


function systemUserSendConfirmationEmail(a) {
    var userId = SYSTEM_USER_STATUS.userId;
    var http = new XMLHttpRequest();
    var params = "action=systemUserRequestEmailConfirmation&userId=" + userId;
    var url = URL_SA_SYSTEM_USER_X;
    showNotification();
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (http.readyState == 4 && http.status == 200) {
            var response = JSON.parse(http.responseText);
            alert(response['msg']);
            hideNotification();
        }
    };
    http.send(params);
}


function postSaleSetAutoComplete(companyId, txtName, documentType, shelfLife = - 1) {
    try {
        const txtDocument = document.getElementById(txtName);
        if (!validateUserControl(txtDocument)) {
            return false;
        }
        var http = new XMLHttpRequest();
        var params = "action=getPostSaleAutocomplete" + "&companyId=" + companyId +
                "&documentType=" + documentType + "&shelfLife=" + shelfLife;
        var url = URL_SA_POST_SALE_X;
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function () {
            if (http.readyState == 4 && http.status == 200) {
                var documents = this.responseText.split("-/-");
                autocomplete(txtDocument, documents);
            }
        };
        http.send(params);

    } catch (ex) {
        showNotification(ex.message, 0);
}
}

function postSaleSearchOnLoad(documentType) {
    function psoLoad() {
        setCustomerType('');
        // customerSetAutoComplete('txtDocProSearchCustomer');
        contractAutocompleteList("txtDocProSearchCustomer", "");
        postSaleSetAutoComplete(SYSTEM_USER_STATUS.companyId, 'txtDocProDocumentNumber', documentType);
        setTriggerButtonByText('txtDocProSearchCustomer', 'btDocProSearch');
        setSessionResultSearch('txtDocProSearchCustomer');
        setSessionResultSearch('numDocProSearchLimit');
    }

    checkUserStatusAfterExecute(psoLoad);
}

function setSelectedRowBackground(CookeiName, name) {
    var fromCook = getCookie(CookeiName);
    if (fromCook == name) {
        return " selected-row";
    }
    return "";
}

function setSelectedRowOnClick(newRow, cookieName) {
    newRow.classList.add("selected-row");
    setCookie(cookieName, newRow.id, 1400);
}

function postSaleSearchDocument(divToClose, whoCall, documentType, shelfLife = - 1) {
    try {

        var FILTER = {};
        FILTER.companyId = SYSTEM_USER_STATUS.companyId;
        FILTER.searchLimit = getElementValue("numDocProSearchLimit");
        FILTER.documentNumber = reviewString(document.getElementById("txtDocProDocumentNumber")).toUpperCase();
        FILTER.customerName = reviewString(document.getElementById("txtDocProSearchCustomer")).toUpperCase();
        FILTER.documentType = documentType;
        FILTER.shelfLife = shelfLife;

        if (isNaN(FILTER.searchLimit)) {
            FILTER.searchLimit = 5;
        }

        FILTER.field = getElementValue("selDocSearField");
        if (FILTER.field == -1) {
            if (!isNaN(FILTER.customerName)) {
                FILTER.field = 3;
            } else if (FILTER.customerName.slice(0, 2) == "CN") {
                FILTER.field = 2;
            } else if (FILTER.customerName.includes("-")) {
                FILTER.field = 1;
            } else {
                FILTER.field = 4;
            }
        }
        if (ST_SA_SEARCHING_SERVER == 1) {
            return false;
        }
        setElementInnerHtml("lbDocSeaResult", "Procurando...");

        ST_SA_SEARCHING_SERVER = 1;

        var http = new XMLHttpRequest();
        var params = "action=getPostSaleListSearch" + "&filterInfo=" + JSON.stringify(FILTER);
        var url = URL_SA_POST_SALE_X;
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function () {
            if (http.readyState == 4 && http.status == 200) {
                ST_SA_SEARCHING_SERVER = 0;
                var documents = JSON.parse(this.responseText);
                if (documents.length <= 0) {
                    showNotification("Não encontrado", 0);
                }
                var founded = documents.length;
                if (documents.length == FILTER.searchLimit) {
                    founded += " ou mais";
                }
                setElementInnerHtml("lbDocSeaResult", "Encontrados: " + founded);

                let tableRef = document.getElementById("tblDocProMainTable");
                tableRef.innerHTML = "";
                var nRows = 0;
                documents.forEach(element => createTbl(element));
                function createTbl(element) {
                    nRows += 1;
                    let doc = JSON.parse(element);

                    let newRow = tableRef.insertRow(-1);
                    newRow.id = "trDocSearRow" + doc['invoicenumber'];
                    newRow.setAttribute("class", "borderGray" + setSelectedRowBackground("SELECTED_ROW_DOCUMENT_SEARCH", newRow.id));

                    let cellRow = newRow.insertCell(newRow.cells.length);
                    cellRow.setAttribute("class", "colRowNumber");
                    cellRow.innerHTML = nRows;
                    cellRow.style.width = "10px";

                    let cellID = newRow.insertCell(-1);
                    cellID.setAttribute("class", "colProductId");
                    cellID.innerHTML = doc['contractid'];

                    let cellCil = newRow.insertCell(-1);
                    cellCil.setAttribute("class", "colProductId");
                    cellCil.innerHTML = doc['cil'];

                    let cellDesignation = newRow.insertCell(newRow.cells.length);
                    cellDesignation.innerHTML = doc['customername'];

                    let cellDocNumber = newRow.insertCell(newRow.cells.length);
                    cellDocNumber.innerHTML = doc['invoicenumber'];

                    let cellDate = newRow.insertCell(newRow.cells.length);
                    cellDate.innerHTML = doc['transactiondate'];

                    let cellAmount = newRow.insertCell(newRow.cells.length);
                    cellAmount.style.textAlign = "right";
                    cellAmount.style.paddingRight = "10px";
                    cellAmount.innerHTML = formatNumber(doc['totalInvoice']);

                    let cellView = newRow.insertCell(newRow.cells.length);
                    let btView = document.createElement("div");
                    btView.id = "btDocSearView" + nRows;
                    /*   btView.setAttribute("class", "labelView");
                     btView.type = "button";*/
                    const docNumber = doc['invoicenumber'];
                    const customerId = doc['customerid'];
                    btView.onclick = function () {
                        if (whoCall == "VDREC") {
                            paymentSetNew(doc);
                        } else if (whoCall == "VD") {
                            invoiceSetNewFromProform(docNumber, doc['invoicestatus'], doc['printnumber'], customerId);
                        }
                        hideParts(divToClose);
                        tableRef.innerHTML = "";
                    };
                    cellView.appendChild(btView);
                    cellView.style.textAlign = "center";
                    cellView.style.height = "30px";
                    setTriggerButtonByRowDobleClick(newRow.id, btView.id, "SELECTED_ROW_DOCUMENT_SEARCH");
                }
            }
        };
        http.send(params);

    } catch (ex) {
        showNotification(ex.message);
}
}


function postSaleSearchInvoicePendengOnLoad(a) {
    function psipoLoad() {
        setCustomerType('');
        customerSetAutoComplete('txtDocProSearchCustomer');
        setTriggerButtonByText('txtDocProSearchCustomer', 'btDocProDocumentSearch');
        setSessionResultSearch('txtDocProSearchCustomer');
        setSessionResultSearch('numUdaConProSearchLimit');
    }

    checkUserStatusAfterExecute(psipoLoad);
}



function postSaleSearchInvoicePendent(divToClose) {
    try {
        var companyId = SYSTEM_USER_STATUS.companyId;
        var searchLimit = document.getElementById("numDocProSearchLimit").value;
        var documentNumber = document.getElementById("txtDocProDocumentNumber").value;
        var customerName = reviewString(document.getElementById("txtDocProSearchCustomer"));

        if (isNaN(searchLimit)) {
            searchLimit = 5;
        }
        if (ST_SA_SEARCHING_SERVER == 1) {
            return false;
        }
        setElementInnerHtml("lbDocPendSeaResult", "Procurando...");

        ST_SA_SEARCHING_SERVER = 1;

        var http = new XMLHttpRequest();
        var params = "action=getPostSaleInvoicePendengSearch&companyId=" + companyId + "&searchLimit=" + searchLimit +
                "&documentNumber=" + documentNumber + "&customerName=" + customerName;
        var url = URL_SA_POST_SALE_X;
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function () {
            if (http.readyState == 4 && http.status == 200) {
                ST_SA_SEARCHING_SERVER = 0;
                var documents = JSON.parse(this.responseText);
                if (documents.length <= 0) {
                    showNotification("Não encontrado", 0);
                }
                var founded = documents.length;
                if (documents.length == searchLimit) {
                    founded += " ou mais";
                }
                setElementInnerHtml("lbDocPendSeaResult", "Encontrados: " + founded);

                let tableRef = document.getElementById("tblDocProMainTable");
                tableRef.innerHTML = "";
                var nRows = 0;
                documents.forEach(element => createTbl(element));
                function createTbl(element) {
                    nRows += 1;
                    let doc = JSON.parse(element);

                    let newRow = tableRef.insertRow(-1);
                    newRow.id = "trInvPendSearRow" + doc['invoicenumber'];
                    newRow.setAttribute("class", "borderGray" + setSelectedRowBackground("SELECTED_ROW_INVOICE_PENDING", newRow.id));

                    let cellRow = newRow.insertCell(newRow.cells.length);
                    cellRow.setAttribute("class", "colRowNumber");
                    cellRow.innerHTML = nRows;
                    cellRow.style.width = "10px";

                    let cellDesignation = newRow.insertCell(newRow.cells.length);
                    cellDesignation.innerHTML = doc['customerName'];

                    let cellDocNumber = newRow.insertCell(newRow.cells.length);
                    cellDocNumber.innerHTML = doc['invoicenumber'];

                    let cellDate = newRow.insertCell(newRow.cells.length);
                    cellDate.innerHTML = doc['entrydate'];

                    let cellView = newRow.insertCell(newRow.cells.length);
                    let btView = document.createElement("div");
                    btView.id = "btInvPendSearRow" + nRows;
                    /*  btView.setAttribute("class", "labelView");
                     btView.type = "button";*/
                    const docNumber = doc['invoicenumber'];
                    const customerId = doc['customerid'];
                    btView.onclick = function () {
                        movimentSetNew(doc['invoicenumber'], doc['customerid']);
                        hideParts(divToClose);
                        tableRef.innerHTML = "";
                    };
                    cellView.appendChild(btView);
                    cellView.style.textAlign = "center";
                    cellView.style.height = "30px";
                    setTriggerButtonByRowDobleClick(newRow.id, btView.id, "SELECTED_ROW_INVOICE_PENDING");
                }
            }
        };
        http.send(params);

    } catch (ex) {
        showNotification(ex.message);
    }
}


function systemUserSearchOnLoad(a) {
    showNotification();
    function susoLoad() {
        systemUserAutocomplete('txtUtiProSearchUser');
        setTriggerButtonByText('txtUtiProSearchUser', 'btUtiProSearchUser');
        setSessionResultSearch('txtUtiProSearchUser');
        setSessionResultSearch('numUtiProSearchLimit');
        setSystemUserBillingProfile('');
        hideNotification();
    }

    checkUserStatusAfterExecute(susoLoad);
}



function systemUserAutocomplete(txtAutocomplete) {
    var companyId = SYSTEM_USER_STATUS.companyId;
    const txtUsers = document.getElementById(txtAutocomplete);
    if (!validateUserControl(txtUsers)) {
        return false;
    }

    var http = new XMLHttpRequest();
    var params = "action=getSystemUserAutocompleteList&companyId=" + companyId;
    var url = URL_SA_SYSTEM_USER_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (http.readyState == 4 && http.status == 200) {
            var users = this.responseText.split("-/-");
            autocomplete(txtUsers, users);
        }

    };
    http.send(params);
}




function systemUserSearchUser(divToClose, sender, target = "") {
    var companyId = SYSTEM_USER_STATUS.companyId;
    var searchLimit = document.getElementById("numUtiProSearchLimit").value;
    var searchTag = reviewString(document.getElementById("txtUtiProSearchUser"));
    if (isNaN(searchLimit)) {
        searchLimit = 5;
    }
    if (ST_SA_SEARCHING_SERVER == 1) {
        return false;
    }
    setElementInnerHtml("lbUserSeaResult", "Procurando...");

    ST_SA_SEARCHING_SERVER = 1;

    var url = "&companyId=" + companyId + "&searchLimit=" + searchLimit + "&searchTag=" + searchTag + "&target=" + target;
    var http = new XMLHttpRequest();
    var params = "action=getSystemUserListSearch" + url;
    var url = URL_SA_SYSTEM_USER_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {//Call a function when the state changes.
        if (http.readyState == 4 && http.status == 200) {
            ST_SA_SEARCHING_SERVER = 0;
            var users = JSON.parse(this.responseText);
            if (users.length <= 0) {
                showNotification("Não encontrado", 0);
            }
            var founded = users.length;
            if (users.length == searchLimit) {
                founded += " ou mais";
            }
            setElementInnerHtml("lbUserSeaResult", "Encontrados: " + founded);

            let tableRef = document.getElementById("tblUtiProMainTable");
            tableRef.innerHTML = "";
            var nRows = 0;
            users.forEach(element => createTbl(element));
            function createTbl(element) {
                nRows += 1;
                let user = JSON.parse(element);
                let newRow = tableRef.insertRow(-1);
                newRow.id = "trUserSearRow" + user['id'];
                newRow.setAttribute("class", "borderGray" + setSelectedRowBackground("SELECTED_ROW_USER_SEARCH", newRow.id));

                let cellRow = newRow.insertCell(newRow.cells.length);
                cellRow.setAttribute("class", "colRowNumber");
                cellRow.innerHTML = nRows;
                cellRow.style.width = "10px";

                let cellPhoto = newRow.insertCell(newRow.cells.length);
                if (user['photo'] != "") {
                    let imgPhoto = document.createElement('img');
                    imgPhoto.setAttribute("class", "imgOnSearch");
                    imgPhoto.src = user['photo'];
                    cellPhoto.appendChild(imgPhoto);
                }

                let cellID = newRow.insertCell(newRow.cells.length);
                cellID.setAttribute("class", "colProductId");
                cellID.innerHTML = user['userid'];

                let cellName = newRow.insertCell(newRow.cells.length);
                cellName.innerHTML = user['userfullname'];

                let cellProfile = newRow.insertCell(newRow.cells.length);
                cellProfile.setAttribute("class", "colProductId");
                cellProfile.innerHTML = SYSTEM_USER_BILLING_PROFILE[user['billingprofile']];

                let cellView = newRow.insertCell(newRow.cells.length);
                let btView = document.createElement("div");
                btView.id = "btUserSearView" + nRows;
                /*  btView.setAttribute("class", "labelView");
                 btView.type = "button";*/
                btView.name = user['userid'];
                btView.onclick = function () {
                    if ((sender == "UTICAD") || (sender == "UDAUTICAD")) {
                        systemUserGetUserInfo(btView.name);
                    } else if (sender == "VDPF") {
                        postsellGetDocuments(-1, -1, -1, btView.name);
                    } else if (sender == "UTIREINPASS") {
                        systemUserRestartPassListDetails(btView.name);
                    } else if (sender == "RHLOCTRA") {
                        document.getElementById("txtRHLocTraEmployeeId").value = btView.name;
                        hrWorkplaceAddUser('');
                    } else if (sender == "PRODESTSAILIS") {
                        movimentListGet(-1, -1, -1, -1, btView.name);
                    } else if (sender == "PRODESTENTRLIS") {
                        entranceListGet(-1, -1, -1, btView.name);
                    } else if (sender == "PRODSTCMOVPEND") {
                        movimentPendingList(-1, -1, -1, btView.name);
                    } else if (sender == "SCHLIS") {
                        document.getElementById('txtSchLisUser').value = getFirtLastName(user['userfullname']);
                        hideParts('divSearchUser');
                    } else if (sender == "VDORDERLIST") {
                        postsaleOrderList(-1, -1, -1, -1, btView.name);
                    } else if (sender == "USERPROMO") {
                        systemUserPromotionSilicaList(btView.name, true);
                    } else if (sender == "AGENTPAYPROC") {
                        agentPaymentProcessedList(btView.name);
                    } else if (sender == "LKREQLIS") {
                        linkRequestListDetails(btView.name);
                    } else if (sender == "CONTRLIS") {
                        contractListDetails(-1, btView.name);
                    } else if (sender == "HYDROLIS") {
                        hydrometerListDetails(-1, btView.name);
                    } else if (sender == "HYDRORECLIS") {
                        hydrometerRecordListDetails(-1, btView.name);
                    } else if (sender == "CONSUESTLIS") {
                        consumptionEstimatedListDetails(-1, btView.name);
                    } else if (sender == "SALECONSULIS") {
                        saleConsumptionListDetails(-1, btView.name);
                    } else if (sender == "WTLINK") {
                        contractWaterLinkList(-1, btView.name);
                    } else if (sender == "INSPECLIS") {
                        inspectionListDetails(btView.name);
                    } else if (sender == "COMPLLIS") {
                        complaintListDetails(btView.name);
                    } else if (sender == "CONTRWITHYDLIS") {
                        contractWithouHydrometerListDetails(-1, btView.name);
                    }

                    hideParts(divToClose);
                    tableRef.innerHTML = "";
                };
                cellView.appendChild(btView);
                cellView.style.textAlign = "center";
                cellView.style.height = "30px";
                setTriggerButtonByRowDobleClick(newRow.id, btView.id, "SELECTED_ROW_USER_SEARCH");
            }
        }

    };
    http.send(params);
}


function productAutocompleteList(txtAutocomplete) {
    const txtProduct = document.getElementById(txtAutocomplete);
    if (!validateUserControl(txtProduct)) {
        return false;
    }

    var companyId = SYSTEM_USER_STATUS.companyId;
    var http = new XMLHttpRequest();
    var params = "action=getProductAutocompleteList&companyId=" + companyId;
    var url = URL_SA_PRODUCT_SALE_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var products = this.responseText.split("-/-");
            products.sort();
            autocomplete(txtProduct, products);
        }
    };
    http.send(params);
}



function productSearchOnLoad(whoCall) {
    showNotification();
    function psoLoad() {
        fillSelectProductSection('selProdProcProductSection', -1);
        fillSelectProductCategory('selProdProcProductCategory', -1);
        productAutocompleteList('txtProdProcSearchProduct');
        if (whoCall == "VD") {
            setElementInnerHtml('tdProdProcBarcode', "P.V.P");
        }
        setTriggerButtonByText('txtProdProcSearchProduct', 'btProdProcSearchProduct');
        setSessionResultSearch('txtProdProcSearchProduct');
        setSessionResultSearch('numProdProcSearchLimit');
        hideNotification();
    }

    checkUserStatusAfterExecute(psoLoad);
}




function productSearchProduct(divToClose, sender) {

    var companyId = SYSTEM_USER_STATUS.companyId;
    var workplaceId = SYSTEM_USER_STATUS.workplaceId;
    var productSection = document.getElementById("selProdProcProductSection").value;
    var productCategory = document.getElementById("selProdProcProductCategory").value;
    var searchLimit = document.getElementById("numProdProcSearchLimit").value;
    var searchTag = reviewString(document.getElementById("txtProdProcSearchProduct"));

    if (isNaN(searchLimit)) {
        searchLimit = 5;
    }
    if (ST_SA_SEARCHING_SERVER == 1) {
        return false;
    }
    setElementInnerHtml("lbProdSeaResult", "Procurando...");

    ST_SA_SEARCHING_SERVER = 1;
    var http = new XMLHttpRequest();
    var params = "action=getProductListSearch&companyId=" + companyId + "&productSection=" + productSection +
            "&productCategory=" + productCategory + "&&searchLimit=" + searchLimit + "&&searchTag=" + searchTag +
            "&workplaceId=" + workplaceId;
    var url = URL_SA_PRODUCT_SALE_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (http.readyState == 4 && http.status == 200) {
            ST_SA_SEARCHING_SERVER = 0;
            var products = JSON.parse(this.responseText);
            if (products.length <= 0) {
                showNotification("Não encontrado", 0);
            }
            var founded = products.length;
            if (products.length == searchLimit) {
                founded += " ou mais";
            }
            setElementInnerHtml("lbProdSeaResult", "Encontrados: " + founded);

            let tableRef = document.getElementById("tblProdProcMainTable");
            tableRef.innerHTML = "";
            var nRows = 0;
            products.forEach(element => createTbl(element));
            function createTbl(element) {
                nRows += 1;
                let product = JSON.parse(element);
                let newRow = tableRef.insertRow(-1);
                newRow.id = "trProdSearRow" + product['id'];
                newRow.setAttribute("class", "borderGray" + setSelectedRowBackground("SELECTED_ROW_PRODUCT_SEARCH", newRow.id));

                let cellRow = newRow.insertCell(-1);
                cellRow.setAttribute("class", "colRowNumber");
                cellRow.innerHTML = nRows;
                cellRow.style.width = "10px";

                let cellPhoto = newRow.insertCell(-1);
                if (product['photo'] != "") {
                    let imgPhoto = document.createElement('img');
                    imgPhoto.setAttribute("class", "imgOnSearch");
                    imgPhoto.src = product['photo'];
                    cellPhoto.appendChild(imgPhoto);
                }

                let cellID = newRow.insertCell(-1);
                cellID.setAttribute("class", "colProductId");
                cellID.innerHTML = product['id'];

                let cellDescription = newRow.insertCell(-1);
                cellDescription.innerHTML = product['productdescription'];

                let cellBarCode = newRow.insertCell(-1);
                var barCode = product['productnumbercode']
                if (sender == "VD") {
                    barCode = formatNumber(product['pvp']).bold();
                    cellBarCode.style.textAlign = "right";
                    cellBarCode.style.whiteSpace = "nowrap";
                    cellBarCode.style.paddingRight = "5px";
                }
                cellBarCode.innerHTML = barCode;

                let cellStock = newRow.insertCell(-1);
                cellStock.style.textAlign = "center";
                cellStock.innerHTML = (product['productstock'] == 0) ? "NE" : formatNumber(product['quantity']);

                let cellView = newRow.insertCell(-1);
                let btView = document.createElement("div");
                btView.id = "btProdSearView" + nRows;
                /*  var viewClass = "labelView";
                 if (sender == "VD") {
                 viewClass = "labelAddToCar";
                 } else if ((sender == "PROESTENTR") || (sender == "PRODESTSAI")) {
                 viewClass = "labelAdd";
                 }
                 btView.setAttribute("class", viewClass);
                 btView.type = "button";*/
                const productCode = product['id'];
                btView.onclick = function () {
                    if (sender == "productPrice") {
                        document.getElementById("txtProdPricoProduct").value = productCode;
                        productPriceAddProduct('');
                    } else if (sender == "PRODCAD") {
                        productProdutOpen(productCode);
                    } else if (sender == "PROESTENTR") {
                        document.getElementById("txtProdEstEntrProduct").value = productCode;
                        entranceAddProduct();
                    } else if (sender == "PRODESTSAI") {
                        document.getElementById("txtProdEstSaiProduct").value = productCode;
                        movimentAddProduct(0);
                    } else if (sender == "PRODEXPD") {
                        productExpirationGetList(productCode);
                    } else if (sender == "PRODESTSAILIS") {
                        movimentListGet(-1, -1, -1, -1, -1, -1, -1, productCode);
                    } else if (sender == "PRODESTENTRLIS") {
                        entranceListGet(-1, -1, -1, -1, -1, -1, productCode);
                    } else if (sender == "PRODSTCKMANAJU") {
                        document.getElementById("txtProdStckManAjuProduct").value = productCode;
                        productStockManualAjustAddProduct('');
                    } else if (sender == "VDORDERLIST") {
                        postsaleOrderList(-1, -1, productCode);
                    } else if (sender == "VDPF") {
                        postsellGetDocuments(-1, -1, -1, -1, productCode);
                    } else {
                        document.getElementById("txtVDFactProduct").value = productCode;
                        invoiceAddProduct('tblVDFactMainTable');
                    }

                    hideParts(divToClose);
                    tableRef.innerHTML = "";
                };
                cellView.appendChild(btView);
                cellView.style.textAlign = "center";
                cellView.style.height = "30px";
                setTriggerButtonByRowDobleClick(newRow.id, btView.id, "SELECTED_ROW_PRODUCT_SEARCH");
            }
        }
    };
    http.send(params);

}



function customerSearchOnLoad(a) {
    function load() {
        setCustomerType('');
        fillSelectCustomerType('selCliProCustomerType', -1);
        fillSelectNeiborhood("selCliProNeiborhood", 2, -1);
        setTriggerButtonByText('txtCliProSearchCustomer', 'btCliProSearch');
        setSessionResultSearch('txtCliProSearchCustomer');
        setSessionResultSearch('numCliProSearchLimit');
        customerSetAutoComplete('txtCliProSearchCustomer');
    }

    checkUserStatusAfterExecute(load);
}

var CALL_BY_NEW_CONTRACT = 0;
var CALL_BY_ACTIVATION_CODE = 0;

function customerSearchCustomer(divToClose, sender, contractorId = - 1) {
    try {
        var FILTER = {};
        FILTER.companyId = SYSTEM_USER_STATUS.companyId;
        FILTER.customerType = document.getElementById("selCliProCustomerType").value;
        FILTER.neiborhood = document.getElementById('selCliProNeiborhood').value;
        FILTER.portalStatus = (CALL_BY_ACTIVATION_CODE == 1) ? 1 : -1;
        FILTER.searchLimit = document.getElementById("numCliProSearchLimit").value;
        FILTER.searchTag = reviewString(document.getElementById("txtCliProSearchCustomer"));
        if (isNaN(FILTER.searchLimit)) {
            FILTER.searchLimit = 5;
        }
        if (sender == "UDACONCAD") {
            contractorId = 1;
        }
        if (ST_SA_SEARCHING_SERVER == 1) {
            return false;
        }
        FILTER.contractorId = contractorId;
        setElementInnerHtml("lbCustSeaResult", "Procurando...");

        ST_SA_SEARCHING_SERVER = 1;
        var http = new XMLHttpRequest();
        var params = "action=getCustomerListSearch" + "&filterInfo=" + JSON.stringify(FILTER);
        var url = URL_SA_CUSTOMER_X;
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function () {
            if (http.readyState == 4 && http.status == 200) {
                ST_SA_SEARCHING_SERVER = 0;
                try {
                    var customers = JSON.parse(this.responseText);
                } catch (e) {
                    showHttpResponseText(http.responseText);
                }

                if (customers.length <= 0) {
                    showNotification("Não encontrado", 0);
                }
                var founded = customers.length;
                if (customers.length == FILTER.searchLimit) {
                    founded += " ou mais";
                }
                setElementInnerHtml("lbCustSeaResult", "Encontrados: " + founded);
                let tableRef = document.getElementById("tblCliProMainTable");
                tableRef.innerHTML = "";
                var nRows = 0;
                customers.forEach(element => createTbl(element));
                function createTbl(element) {
                    nRows += 1;
                    let customer = JSON.parse(element);
                    let newRow = tableRef.insertRow(-1);
                    newRow.id = "trCusSeaRow" + customer['customerid'];
                    newRow.setAttribute("class", "borderGray" + setSelectedRowBackground("SELECTED_ROW_CUSTOMER_SEARCH", newRow.id));

                    let cellRow = newRow.insertCell(-1);
                    cellRow.setAttribute("class", "colRowNumber");
                    cellRow.innerHTML = nRows;
                    cellRow.style.width = "10px";

                    let cellID = newRow.insertCell(-1);
                    cellID.setAttribute("class", "colProductId");
                    cellID.innerHTML = customer['customerid'];

                    let cellDesignation = newRow.insertCell(-1);
                    cellDesignation.innerHTML = customer['companyname'];

                    let cellNIF = newRow.insertCell(-1);
                    cellNIF.innerHTML = customer['customertaxid'];

                    let cellContracts = newRow.insertCell(-1);
                    cellContracts.style.textAlign = "center";
                    cellContracts.innerHTML = customer['contracts'];

                    let cellView = newRow.insertCell(-1);
                    let btView = document.createElement("div");
                    btView.id = "btCusSeaView" + nRows;
                    /*  btView.setAttribute("class", "labelView");
                     btView.type = "button";*/
                    btView.name = customer['customerid'];
                    btView.onclick = function () {
                        if ((sender == "CLICAD")) {
                            customerGetInfo(btView.name, sender);
                        } else if ((sender == "VD")) {
                            customer['contractid'] = "";
                            invoiceSelectContract(customer);
                        } else if (sender == "CLIHIS") {
                            customerHistoricGetInfo(FILTER.companyId, btView.name);
                        } else if (sender == "CLIVINSERV") {
                            customerAssignService(btView.name);
                        } else if (sender == "VDPF") {
                            postsellGetDocuments(-1, -1, btView.name);
                        } else if (sender == "UDACONCAD") {
                            companyContractorNew(btView.name, -1);
                        } else if (sender == "PRODESTSAI") {
                            movimentSetNew("", btView.name);
                        } else if (sender == "PRODESTSAILIS") {
                            movimentListGet(-1, -1, btView.name);
                        } else if (sender == "VDADIAN") {
                            const cName = customer['companyname'];
                            const cTaxId = customer['customertaxid'];
                            paymentAdvanceSetNew(btView.name, cName, cTaxId, customer['email'], customer['telephone1']);
                        } else if (sender == "PRODSTCMOVPEND") {
                            movimentPendingList(-1, -1, btView.name);
                        } else if (sender == "SCHLIS") {
                            document.getElementById('txtSchLisCustomer').value = customer['companyname'];
                            document.getElementById('txtSchLisContacts').value = customer['telephone1'] + " / " + customer['telephone2'] + " / " + customer['telephone3'];
                            document.getElementById('txtSchLisEmail').value = customer['email'];
                            hideParts('divSchLisCustomerSearch');
                        } else if (sender == "VDORDERLIST") {
                            postsaleOrderList(-1, -1, -1, btView.name);
                        } else if (sender == "SALINVMISPAY") {
                            saleReportInvoiceMissingPaymentList(customer);
                        } else if (sender == "CLILIST") {
                            customerCustomerListDetails(btView.name);
                        } else if (sender == "CONTRLIS") {
                            if (CALL_BY_NEW_CONTRACT == 1) {
                                contractNewContract(customer);
                            } else {
                                contractListDetails(btView.name);
                            }
                        } else if (sender == "HYDROLIS") {
                            hydrometerListDetails(btView.name);
                        } else if (sender == "HYDRORECLIS") {
                            hydrometerRecordListDetails(btView.name);
                        } else if (sender == "CONSUESTLIS") {
                            consumptionEstimatedListDetails(btView.name);
                        } else if (sender == "SALECONSULIS") {
                            saleConsumptionListDetails(btView.name);
                        } else if (sender == "WTLINK") {
                            contractWaterLinkList(btView.name);
                        } else if (sender == "COMPLLIS") {
                            if (CALL_BY_NEW_COMPLAINT == 1) {
                                complaintNewComplaint(customer);
                            } else {
                                complaintListDetails(btView.name);
                            }
                        } else if (sender == "CLICONCORR") {
                            customerAccountListDetails(btView.name);
                        } else if (sender == "CONTRWITHYDLIS") {
                            contractWithouHydrometerListDetails(btView.name);
                        } else if (sender == "CUSTPORLIS") {
                            if (CALL_BY_ACTIVATION_CODE == 1) {
                                customerPortalShowActivationCode(customer);
                            } else {
                                customerPortalList(btView.name);
                            }
                        }
                        hideParts(divToClose);
                        tableRef.innerHTML = "";
                    };
                    cellView.appendChild(btView);
                    cellView.style.textAlign = "center";
                    cellView.style.height = "30px";
                    setTriggerButtonByRowDobleClick(newRow.id, btView.id, "SELECTED_ROW_CUSTOMER_SEARCH");
                }
            }
        };
        http.send(params);
    } catch (ex) {
        showNotification(ex.message, 0);
}


}




function contractSearchOnLoad(whoCall) {
    function load() {
        setWaterLinkStatus();
        fillSelectConsumerTypeAqua("selContrSearConsumerType", -1);
        fillSelectNeiborhood("selContrSearNeiborhood", 3, -1);
        setTriggerButtonByText('txtContrSearSearchContract', 'btContrSearSearch');
        setSessionResultSearch('txtContrSearSearchContract');
        setSessionResultSearch('numContrSearSearchLimit');
        contractAutocompleteList("txtContrSearSearchContract", whoCall);
    }

    checkUserStatusAfterExecute(load);
}


function contractSearchCustomer(divToClose, sender) {
    try {
        var companyId = SYSTEM_USER_STATUS.companyId;
        var dependencyId = -1;
        var forHydrometer = 0;
        if (sender == "HYDROLIS") {
            dependencyId = SYSTEM_USER_STATUS.workplaceId;
            forHydrometer = 1;
        }
        var consumerType = getElementValue("selContrSearConsumerType");
        var neiborhood = getElementValue('selContrSearNeiborhood');
        var searchLimit = getElementValue("numContrSearSearchLimit");
        var searchTag = reviewString(document.getElementById("txtContrSearSearchContract")).toUpperCase();
        if (isNaN(searchLimit)) {
            searchLimit = 5;
        }

        var field = getElementValue("selContrSearField");
        if (field == -1) {
            if (!isNaN(searchTag)) {
                field = 3;
            } else if (searchTag.slice(0, 2) == "CN") {
                field = 2;
            } else if (searchTag.includes("-")) {
                field = 1;
            } else {
                field = 4;
            }
        }
        if (ST_SA_SEARCHING_SERVER == 1) {
            return false;
        }
        setElementInnerHtml("lbContrSeaResult", "Procurando...");

        ST_SA_SEARCHING_SERVER = 1;
        var http = new XMLHttpRequest();
        var params = "action=getContractListSearch" + "&companyId=" + companyId + "&dependencyId=" + dependencyId +
                "&consumerType=" + consumerType + "&neiborhood=" + neiborhood +
                "&searchLimit=" + searchLimit + "&searchTag=" + searchTag + "&field=" + field + "&forHydrometer=" + forHydrometer;
        var url = URL_SA_CONTRACT_X;
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function () {
            if (http.readyState == 4 && http.status == 200) {
                ST_SA_SEARCHING_SERVER = 0;
                var contracts = JSON.parse(this.responseText);
                if (contracts.length <= 0) {
                    showNotification("Não encontrado", 0);
                }
                var founded = contracts.length;
                if (contracts.length == searchLimit) {
                    founded += " ou mais";
                }
                setElementInnerHtml("lbContrSeaResult", "Encontrados: " + founded);

                let tableRef = document.getElementById("tblContrSearMainTable");
                tableRef.innerHTML = "";
                var nRows = 0;
                contracts.forEach(element => createTbl(element));
                function createTbl(element) {
                    nRows += 1;
                    let contract = JSON.parse(element);
                    let newRow = tableRef.insertRow(-1);
                    newRow.id = "trContrSeaRow" + contract['contractid'];
                    newRow.setAttribute("class", "borderGray" + setSelectedRowBackground("SELECTED_ROW_CONTRACT_SEARCH", newRow.id));

                    let cellRow = newRow.insertCell(-1);
                    cellRow.setAttribute("class", "colRowNumber");
                    cellRow.innerHTML = nRows;
                    cellRow.style.width = "10px";

                    let cellID = newRow.insertCell(-1);
                    cellID.setAttribute("class", "colProductId");
                    cellID.innerHTML = contract['contractid'];

                    let cellCil = newRow.insertCell(-1);
                    cellCil.setAttribute("class", "colProductId");
                    cellCil.innerHTML = contract['cil'];

                    let cellDesignation = newRow.insertCell(-1);
                    cellDesignation.innerHTML = contract['companyname'] + " [" + contract['customerid'] + "]";

                    let cellNIF = newRow.insertCell(-1);
                    cellNIF.innerHTML = contract['customertaxid'];

                    let cellWtLink = newRow.insertCell(-1);
                    cellWtLink.innerHTML = WATER_LINK_STATUS[Number(contract['waterlinkstatus'])] + " / " + contract['hydrometerid'];

                    let cellView = newRow.insertCell(-1);
                    let btView = document.createElement("div");
                    btView.id = "btContrSeaView" + nRows;
                    btView.name = contract['contractid'];
                    btView.onclick = function () {
                        if ((sender == "HYDROLIS")) {
                            hydrometerNewHydrometer(contract);
                        } else if ((sender == "CONSUESTLIS")) {
                            consumptionEstimatedNewEstimated(contract);
                        } else if (sender == "SALECONSULIS") {
                            saleConsumptionGroupShow(contract);
                        } else if (sender == "SALINVMISPAY") {
                            saleReportInvoiceMissingPaymentList(-1, contract);
                        } else if (sender == "VDADIAN") {
                            paymentAdvanceSetNew(contract['contractid'], contract['cil'], contract['customerid'],
                                    contract['companyname'], contract['customertaxid'], contract['email'], contract['telephone1']);
                        } else if (sender == "VD") {
                            invoiceSelectContract(contract);
                        }
                        hideParts(divToClose);
                        tableRef.innerHTML = "";
                    };
                    cellView.appendChild(btView);
                    cellView.style.textAlign = "center";
                    cellView.style.height = "30px";
                    setTriggerButtonByRowDobleClick(newRow.id, btView.id, "SELECTED_ROW_CONTRACT_SEARCH");
                }
            }
        };
        http.send(params);
    } catch (ex) {
        showNotification(ex.message, 0);
    }


}



function customerSetAutoComplete(txtName) {
    try {
        const txtCustomer = document.getElementById(txtName);
        if (!validateUserControl(txtCustomer)) {
            return false;
        }
        var companyId = SYSTEM_USER_STATUS.companyId;
        var http = new XMLHttpRequest();
        var params = "action=getCustomerAutocompleteList" + "&companyId=" + companyId;
        var url = URL_SA_CUSTOMER_X;
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function () {
            if (http.readyState == 4 && http.status == 200) {
                var customers = this.responseText.split("-/-");
                autocomplete(txtCustomer, customers);
            }
        };
        http.send(params);
    } catch (ex) {
        showNotification(ex.message, 0);
    }
}



function contractAutocompleteList(txtAutocomplete, whoCall) {
    const txtContract = document.getElementById(txtAutocomplete);
    if (!validateUserControl(txtContract)) {
        return false;
    }

    var companyId = SYSTEM_USER_STATUS.companyId;
    var dependencyId = -1;
    if ((whoCall == "HYDROLIS") || (whoCall == "HYDRORECLIS")) {
        dependencyId = SYSTEM_USER_STATUS.workplaceId;
    }
    var http = new XMLHttpRequest();
    var params = "action=getContractAutocompleteList&companyId=" + companyId + "&dependencyId=" + dependencyId;
    var url = URL_SA_CONTRACT_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var contracts = this.responseText.split("-/-");
            contracts.sort();
            autocomplete(txtContract, contracts);
        }
    };
    http.send(params);
}





function linkRequestSearchOnLoad(whoCall) {
    function load() {
        fillSelectWaterTax('selLkReqSearchRequestType', -1);
        fillSelectNeiborhood("selLkReqSearchNeiborhood", 1, -1);
        setTriggerButtonByText('txtLkReqSearchRequester', 'btLkReqSearch');
        setSessionResultSearch('txtLkReqSearchRequester');
        setSessionResultSearch('numLkReqSearchLimit');
        setAutoCompleteToFiel('txtLkReqSearchRequester', 39, 1);
        if (whoCall == "LKREQLIS") {
            hideParts("lbLkReqSearchOBS");
            setElementInnerHtml("lbLkReqSearchHeaderTitle", "Solicitação de ligação");
        }
    }

    checkUserStatusAfterExecute(load);
}



function linkRequestSearchRequest(divToClose, sender) {
    try {
        var FILTER = {};
        FILTER.companyId = SYSTEM_USER_STATUS.companyId;
        FILTER.dependencyId = -1;
        if (Number(SYSTEM_USER_STATUS.billingprofile) < BILLING_PROFILE_PARTNER_INDEX) {
            dependencyId = SYSTEM_USER_STATUS.workplaceId;
        }
        FILTER.requestType = document.getElementById("selLkReqSearchRequestType").value;
        FILTER.neiborhood = document.getElementById('selLkReqSearchNeiborhood').value;
        FILTER.searchLimit = document.getElementById("numLkReqSearchLimit").value;
        FILTER.searchTag = reviewString(document.getElementById("txtLkReqSearchRequester"));
        if (isNaN(FILTER.searchLimit)) {
            FILTER.searchLimit = 5;
        }
        FILTER.requestStatus = 2;
        if (sender == "LKREQLIS") {
            FILTER.requestStatus = -1;
        }
        if (ST_SA_SEARCHING_SERVER == 1) {
            return false;
        }
        setElementInnerHtml("lbLinkReqSeaResult", "Procurando...");

        ST_SA_SEARCHING_SERVER = 1;

        var http = new XMLHttpRequest();
        var params = "action=getLinkRequestListSearch" + "&filterInfo=" + JSON.stringify(FILTER);
        var url = URL_SA_LINK_REQUEST_X;
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function () {
            if (http.readyState == 4 && http.status == 200) {
                ST_SA_SEARCHING_SERVER = 0;
                try {
                    var requests = JSON.parse(this.responseText);
                } catch (e) {
                    showHttpResponseText(http);
                }

                if (requests.length <= 0) {
                    showNotification("Não encontrado", 0);
                }
                var founded = requests.length;
                if (requests.length == FILTER.searchLimit) {
                    founded += " ou mais";
                }
                setElementInnerHtml("lbLinkReqSeaResult", "Encontrados: " + founded);

                let tableRef = document.getElementById("tblLkReqSearchMainTable");
                tableRef.innerHTML = "";
                var nRows = 0;
                requests.forEach(element => createTbl(element));
                function createTbl(element) {
                    nRows += 1;
                    let request = JSON.parse(element);
                    let newRow = tableRef.insertRow(-1);
                    newRow.id = "trLkReqSearchRow" + request['id'];
                    newRow.setAttribute("class", "borderGray" + setSelectedRowBackground("SELECTED_ROW_LINK_REQUEST_SEARCH", newRow.id));

                    let cellRow = newRow.insertCell(-1);
                    cellRow.setAttribute("class", "colRowNumber");
                    cellRow.innerHTML = nRows;
                    cellRow.style.width = "10px";

                    let cellID = newRow.insertCell(-1);
                    cellID.setAttribute("class", "colProductId");
                    cellID.innerHTML = request['id'];

                    let cellDesignation = newRow.insertCell(-1);
                    cellDesignation.innerHTML = request['requestername'];

                    let cellNIF = newRow.insertCell(-1);
                    cellNIF.innerHTML = request['requesterdocnumber'];

                    let cellView = newRow.insertCell(-1);
                    let btView = document.createElement("div");
                    btView.id = "btLkReqSearchView" + nRows;
                    /*  btView.setAttribute("class", "labelView");
                     btView.type = "button";*/
                    btView.name = request['id'];
                    btView.onclick = function () {
                        if ((sender == "CLILIST")) {
                            customerCustomerListShowDetais(-1, "FROMLKREQ", request);
                        } else if ((sender == "INSPECLIS")) {
                            inspectionNewInspection(request);
                        } else if ((sender == "LKREQLIS")) {
                            linkRequestListDetails(-1, btView.name);
                        }
                        hideParts(divToClose);
                        tableRef.innerHTML = "";
                    };
                    cellView.appendChild(btView);
                    cellView.style.textAlign = "center";
                    cellView.style.height = "30px";
                    setTriggerButtonByRowDobleClick(newRow.id, btView.id, "SELECTED_ROW_LINK_REQUEST_SEARCH");
                }
            }
        };
        http.send(params);
    } catch (ex) {
        showNotification(ex.message, 0);
    }
}




function hydrometerSearchhydrometer(divToClose, sender) {
    try {
        var companyId = SYSTEM_USER_STATUS.companyId;
        var dependencyId = -1;
        if (sender == "HYDRORECLIS") {
            dependencyId = SYSTEM_USER_STATUS.workplaceId;
        }
        var consumerType = getElementValue("selHydroSearConsumerType");
        var neiborhood = getElementValue('selHydroSearNeiborhood');
        var searchLimit = getElementValue("numHydroSearSearchLimit");
        var searchTag = reviewString(document.getElementById("txtHydroSearSearchHydrometer")).toUpperCase();
        if (isNaN(searchLimit)) {
            searchLimit = 5;
        }

        var field = getElementValue("selHydroSearField");
        if (field == -1) {
            if (!isNaN(searchTag)) {
                field = 3;
            } else if (searchTag.slice(0, 2) == "CN") {
                field = 2;
            } else if (searchTag.includes("-")) {
                field = 1;
            } else if (searchTag.includes("H0")) {
                field = 5;
            } else {
                field = 4;
            }
        }
        if (ST_SA_SEARCHING_SERVER == 1) {
            return false;
        }
        setElementInnerHtml("lbHydroSeaResult", "Procurando...");

        ST_SA_SEARCHING_SERVER = 1;


        var http = new XMLHttpRequest();
        var params = "action=getHydrometerListSearch" + "&companyId=" + companyId + "&dependencyId=" + dependencyId +
                "&consumerType=" + consumerType + "&neiborhood=" + neiborhood +
                "&searchLimit=" + searchLimit + "&searchTag=" + searchTag + "&field=" + field;
        var url = URL_SA_HYDROMETER_X;
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function () {
            if (http.readyState == 4 && http.status == 200) {
                ST_SA_SEARCHING_SERVER = 0;
                var hydrometers = JSON.parse(this.responseText);
                if (hydrometers.length <= 0) {
                    showNotification("Não encontrado", 0);
                }
                var founded = hydrometers.length;
                if (hydrometers.length == searchLimit) {
                    founded += " ou mais";
                }
                setElementInnerHtml("lbHydroSeaResult", "Encontrados: " + founded);

                let tableRef = document.getElementById("tblHydroSearMainTable");
                tableRef.innerHTML = "";
                var nRows = 0;
                hydrometers.forEach(element => createTbl(element));
                function createTbl(element) {
                    nRows += 1;
                    let hydrometer = JSON.parse(element);
                    let newRow = tableRef.insertRow(-1);
                    newRow.id = "trHydroSeaRow" + hydrometer['hydrometerid'];
                    newRow.setAttribute("class", "borderGray" + setSelectedRowBackground("SELECTED_ROW_HYDROMETER_SEARCH", newRow.id));

                    let cellRow = newRow.insertCell(-1);
                    cellRow.setAttribute("class", "colRowNumber");
                    cellRow.innerHTML = nRows;
                    cellRow.style.width = "10px";

                    let cellID = newRow.insertCell(-1);
                    cellID.setAttribute("class", "colProductId");
                    cellID.innerHTML = hydrometer['hydrometerid'];

                    let cellContract = newRow.insertCell(-1);
                    cellContract.setAttribute("class", "colProductId");
                    cellContract.innerHTML = hydrometer['contractid'];

                    let cellCil = newRow.insertCell(-1);
                    cellCil.setAttribute("class", "colProductId");
                    cellCil.innerHTML = hydrometer['cil'];

                    let cellDesignation = newRow.insertCell(-1);
                    cellDesignation.innerHTML = hydrometer['companyname'] + " [" + hydrometer['customerid'] + "]";

                    let cellNIF = newRow.insertCell(-1);
                    cellNIF.innerHTML = hydrometer['customertaxid'];

                    let cellWtLink = newRow.insertCell(-1);
                    cellWtLink.innerHTML = WATER_LINK_STATUS[Number(hydrometer['waterlinkstatus'])];

                    let cellView = newRow.insertCell(-1);
                    let btView = document.createElement("div");
                    btView.id = "btContrSeaView" + nRows;
                    btView.name = hydrometer['contractid'];
                    btView.onclick = function () {
                        if ((sender == "HYDRORECLIS")) {
                            hydrometerRecordNewRecord(hydrometer);
                        }
                        hideParts(divToClose);
                        tableRef.innerHTML = "";
                    };
                    cellView.appendChild(btView);
                    cellView.style.textAlign = "center";
                    cellView.style.height = "30px";
                    setTriggerButtonByRowDobleClick(newRow.id, btView.id, "SELECTED_ROW_HYDROMETER_SEARCH");
                }
            }
        };
        http.send(params);
    } catch (ex) {
        showNotification(ex.message, 0);
    }


}





var PAYMENT_ALL_TPA_TERMINAL = [];
function paymentGetTpaTerminal() {

    var http = new XMLHttpRequest();
    var params = "action=getTpaTerminal&&accountId=-1";// + accountId;
    var url = URL_SA_CRUD_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            try {
                var tpaTerminals = JSON.parse(this.responseText);
                PAYMENT_ALL_TPA_TERMINAL = tpaTerminals;
            } catch (e) {
                showHttpResponseText(http);
            }
        }
    };
    http.send(params);
}



function saleReportCreateGraphic(arraySourceData, chartId, chartTitle, chartType) {
    try {
        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable(arraySourceData);
            var options = {
                title: chartTitle,
                pieHole: 0.4,

                legend: {position: 'left'
                }
            }
            ;
            var chart = new google.visualization.PieChart(document.getElementById(chartId));
            chart.draw(data, options);
        }
    } catch (ex) {
        showNotification("Gráfico indispunível, provavelmente por falta de internet,", 0)
    }


}








function setAutoCompleteToFiel(txtName, tblFld, needCompanyId = 0) {
    try {
        const txtDocument = document.getElementById(txtName);
        var companyId = -1;
        if (!validateUserControl(txtDocument)) {
            return false;
        }
        if (needCompanyId != 0) {
            companyId = SYSTEM_USER_STATUS.companyId;
        }
        var http = new XMLHttpRequest();
        var params = "action=getAutocompleteField" + "&companyId=" + companyId +
                "&tblFld=" + tblFld;
        var url = URL_SA_CRUD_X;
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function () {
            if (http.readyState == 4 && http.status == 200) {
                var documents = this.responseText.split("-/-");
                autocomplete(txtDocument, documents);
            }
        };
        http.send(params);

    } catch (ex) {
        showNotification(ex.message, 0);
}
}



function printOptionSaveSignature() {

    var companyId = SYSTEM_USER_STATUS.companyId;
    var dependencyId = SYSTEM_USER_STATUS.workplaceId;

    var visaQuality = document.getElementById('txtPrintOptionVisaQuality').value.trim();
    var visaPerson = document.getElementById('txtPrintOptionVisaPerson').value.trim();
    var signatureQuality1 = document.getElementById('txtPrintOptionSignatureQuality1').value.trim();
    var signaturePerson1 = document.getElementById('txtPrintOptionSignaturePerson1').value.trim();
    var signatureQuality2 = document.getElementById('txtPrintOptionSignatureQuality2').value.trim();
    var signaturePerson2 = document.getElementById('txtPrintOptionSignaturePerson2').value.trim();
    var signatureQuality3 = document.getElementById('txtPrintOptionSignatureQuality3').value.trim();
    var signaturePerson3 = document.getElementById('txtPrintOptionSignaturePerson3').value.trim();

    var signatures = [];
    signatures[0] = JSON.stringify({person: visaPerson, quality: visaQuality});
    signatures[1] = JSON.stringify({person: signaturePerson1, quality: signatureQuality1});
    signatures[2] = JSON.stringify({person: signaturePerson2, quality: signatureQuality2});
    signatures[3] = JSON.stringify({person: signaturePerson3, quality: signatureQuality3});

    var cookiename = "printOptionSignature";
    setCookie(companyId + dependencyId + cookiename + 0, signatures[0], 10080);
    setCookie(companyId + dependencyId + cookiename + 1, signatures[1], 10080);
    setCookie(companyId + dependencyId + cookiename + 2, signatures[2], 10080);
    setCookie(companyId + dependencyId + cookiename + 3, signatures[3], 10080);

    var signatureInfo = '&signatureInfo={"0":' + signatures[0] +
            ',"1":' + signatures[1] + ',"2":' + signatures[2] + ',"3":' + signatures[3] + '}';


    var http = new XMLHttpRequest();
    var params = "action=savePrintOptionSignature" + "&companyId=" + companyId +
            "&dependencyId=" + dependencyId + signatureInfo;
    var url = URL_SA_CRUD_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (http.readyState == 4 && http.status == 200) {

        }
    };
    http.send(params);

}

function printOptionSelectSignatureOnChange(selSign, qualityId, personId) {

    //  const selSign = document.getElementById(selectId);
    const txtQuality = document.getElementById(qualityId);
    const txtPerson = document.getElementById(personId);

    var signature = selSign.value.toString().split("-/-");
    if (signature.length >= 2) {
        txtQuality.value = signature[1];
        txtPerson.value = signature[0];
    } else {
        txtQuality.value = "";
        txtPerson.value = "";
    }
}





function wordToNumberPitagoras(word) {
    word = reviewString(word);
    if (word == "") {
        return "";
    }
    var i = 0;
    var result = "";
    for (i = 0; i < word.toString().length; i++) {
        result += letterToNumber(word.substring(i, i + 1)).toString();
    }
    return result;
}

function letterToNumber(letter) {
    letter = letter.toString().toUpperCase();
    if ((letter == "A") || (letter == "J") || (letter == "S")) {
        return 1;
    }
    if ((letter == "B") || (letter == "K") || (letter == "T")) {
        return 2;
    }
    if ((letter == "C") || (letter == "L") || (letter == "U")) {
        return 3;
    }
    if ((letter == "D") || (letter == "M") || (letter == "V")) {
        return 4;
    }
    if ((letter == "E") || (letter == "N") || (letter == "W")) {
        return 5;
    }
    if ((letter == "F") || (letter == "O") || (letter == "X")) {
        return 6;
    }
    if ((letter == "G") || (letter == "P") || (letter == "Y")) {
        return 7;
    }
    if ((letter == "H") || (letter == "Q") || (letter == "Z")) {
        return 8;
    }
    return 9;
}

/**/



var ALERT_TITLE = "SÍLICA Aqua";
var ALERT_BUTTON_TEXT = "Ok";

if (document.getElementById) {
    window.alert = function (txt) {
        createCustomAlert(txt);
    };
}

function createCustomAlert(txt) {
    d = document;

    if (d.getElementById("modalContainer"))
        return;

    mObj = d.getElementsByTagName("body")[0].appendChild(d.createElement("div"));
    mObj.id = "modalContainer";
    mObj.style.height = d.documentElement.scrollHeight + "px";

    alertObj = mObj.appendChild(d.createElement("div"));
    alertObj.id = "alertBox";
    if (d.all && !window.opera)
        alertObj.style.top = document.documentElement.scrollTop + "px";
    alertObj.style.left = (d.documentElement.scrollWidth - alertObj.offsetWidth) / 2 + "px";
    alertObj.style.visiblity = "visible";

    h1 = alertObj.appendChild(d.createElement("h1"));
    h1.appendChild(d.createTextNode(ALERT_TITLE));

    msg = alertObj.appendChild(d.createElement("p"));
    //msg.appendChild(d.createTextNode(txt));
    msg.innerHTML = txt;

    btn = alertObj.appendChild(d.createElement("a"));
    btn.id = "closeBtn";
    btn.appendChild(d.createTextNode(ALERT_BUTTON_TEXT));
    btn.href = "#";
    btn.focus();
    btn.onclick = function () {
        removeCustomAlert();
        return false;
    };

    alertObj.style.display = "block";

}

function removeCustomAlert() {
    document.getElementsByTagName("body")[0].removeChild(document.getElementById("modalContainer"));
}

function animateValue(obj, start, end, duration, format = false, extraLabel = "") {
    if (!validateUserControl(obj)) {
        return false;
    }
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp)
            startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        var value = Math.floor(progress * (end - start) + start);
        if (format) {
            value = formatNumber(value);
        }
        obj.innerHTML = value + extraLabel;// Math.floor(progress * (end - start) + start);
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}


function sec2time(timeInSeconds) {
    var pad = function (num, size) {
        return ('000' + num).slice(size * -1);
    },
            time = parseFloat(timeInSeconds).toFixed(3),
            hours = Math.floor(time / 60 / 60),
            minutes = Math.floor(time / 60) % 60,
            seconds = Math.floor(time - minutes * 60),
            milliseconds = time.slice(-3);

    return  pad(minutes, 2) + ':' + pad(seconds, 2);
}

function maskInputByPlaceHold(inputId) {
    const obj = document.getElementById(inputId);
    if (!validateUserControl(obj)) {
        return false;
    }
    obj.addEventListener('input', () => {
        for (const el of document.querySelectorAll("[placeholder][data-slots]")) {
            const pattern = el.getAttribute("placeholder"),
                    slots = new Set(el.dataset.slots || "_"),
                    prev = (j => Array.from(pattern, (c, i) => slots.has(c) ? j = i + 1 : j))(0),
                    first = [...pattern].findIndex(c => slots.has(c)),
                    accept = new RegExp(el.dataset.accept || "\\d", "g"),
                    clean = input => {
                        input = input.match(accept) || [];
                        return Array.from(pattern, c =>
                            input[0] === c || slots.has(c) ? input.shift() || c : c
                        );
                    },
                    format = () => {
                const [i, j] = [el.selectionStart, el.selectionEnd].map(i => {
                    i = clean(el.value.slice(0, i)).findIndex(c => slots.has(c));
                    return i < 0 ? prev[prev.length - 1] : back ? prev[i - 1] || first : i;
                });
                el.value = clean(el.value).join``;
                el.setSelectionRange(i, j);
                back = false;
            };
            let back = false;
            el.addEventListener("keydown", (e) => back = e.key === "Backspace");
            el.addEventListener("input", format);
            el.addEventListener("focus", format);
            el.addEventListener("blur", () => el.value === pattern && (el.value = ""));
        }
    });
}


function noRequiredShowHide(bt) {
    const demoClasses = document.getElementsByClassName('noRequiredHide');
    var divsTeste = Array.prototype.filter.call(demoClasses, function (elementoTeste) {
        if (window.getComputedStyle(elementoTeste).display == "none") {
            elementoTeste.style.display = "inline";
            bt.innerHTML = "Ocultar campos não obrigatórios";
        } else {
            elementoTeste.style.display = "none";
            bt.innerHTML = "Ver campos não obrigatórios";
        }
    });
}


function getDocumentStatus(status) {
    status = status.toUpperCase();
    if (status == "A") {
        return "Anulado";
    } else if (status == "N") {
        return "Normal";
    } else if (status == "AF") {
        return "Aguarda Facturação";
    } else if (status == "F") {
        return "Facturado";
    } else {
        return status;
    }
}

function hideShortcuts() {
    if (!checkPermission('0201')) {
        document.getElementById('btShortcutSaleInvoice').style.display = "none";
    }
    if (!checkPermission('0204')) {
        document.getElementById('btShortcutSaleWorkdocument').style.display = "none";
    }
    if (!checkPermission('0205')) {
        document.getElementById('btShortcutSalePayment').style.display = "none";
    }
    if (!checkPermission('0207')) {
        document.getElementById('btShortcutOrder').style.display = "none";
    }
    if (!checkPermission('0306')) {
        document.getElementById('btShortcutMoviment').style.display = "none";
    }
    if (!checkPermission('0304')) {
        document.getElementById('btShortcutEntrance').style.display = "none";
    }
}


function regimeIva(iva) {
    if (Number(iva) <= 1) {
        return "Regime de exclusão";
    } else if (iva == 2) {
        return "Regime simplificado.";
    } else {
        return "Regime geral.";
    }
}


function setTriggerButtonByText(txtId, btId) {
    var input = document.getElementById(txtId);
    var btn = document.getElementById(btId);
    if (!validateUserControl(input)) {
        return false;
    }
    if (!validateUserControl(btn)) {
        return false;
    }
// Execute a function when the user releases a key on the keyboard
    input.addEventListener("keyup", function (event) {
        // Number 13 is the "Enter" key on the keyboard
        if (event.keyCode === 13) {
            // Cancel the default action, if needed
            event.preventDefault();
            // Trigger the button element with a click
            btn.click();
        }
    });
}


function setTriggerButtonByRowDobleClick(rowId, btId, coockieName = "") {
    var row = document.getElementById(rowId);
    var btn = document.getElementById(btId);
    if (!validateUserControl(row)) {
        return false;
    }
    if (!validateUserControl(btn)) {
        return false;
    }
    row.style.cursor = "pointer";
// Execute a function when the user releases a key on the keyboard
    row.addEventListener("click", function (event) {
        if (coockieName != "") {
            setCookie(coockieName, row.id, 1400);
        }
        // Cancel the default action, if needed
        event.preventDefault();
        // Trigger the button element with a click
        btn.click();
    });
}



function setEnableDisableByCheckBox(chbId, elem1Id, elem2Id = "", elem3Id = "") {
    var chb = document.getElementById(chbId);
    var element1 = document.getElementById(elem1Id);
    var element2 = document.getElementById(elem2Id);
    var element3 = document.getElementById(elem3Id);
    if (!validateUserControl(chb)) {
        return false;
    }
    if (chb.checked == true) {
        element1.disabled = "";
        element2.disabled = "";
        element3.disabled = "";
    } else {
        element1.disabled = "true";
        element2.disabled = "true";
        element3.disabled = "true";
}
}




function setSessionResultSearch(numResultId) {
    const numResult = document.getElementById(numResultId);
    if (!validateUserControl(numResult)) {
        return false;
    }

    var cookName = SYSTEM_USER_STATUS.userId + numResultId;

    var initVal = getCookie(cookName);
    if (initVal != null) {
        if (initVal != "") {
            numResult.value = initVal;
        }
    }

    numResult.addEventListener("change", function () {
        setCookie(cookName, numResult.value, 10300);
    });
}


function setLocalDate(inDate, formated = false, onLabel = false, sep = " ") {
    if ((inDate == null) || (inDate == "")) {
        return inDate;
    }
    var date = new Date(inDate);
    if (!validateDate(date)) {
        return inDate;
    }
    date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
    if (!formated) {
        return date;
    } else {
        if (onLabel) {
            var day = leadingZero(date.getDate(), 2);
            var month = leadingZero(date.getMonth() + 1, 2);
            return day + "-" + month + "-" + date.getFullYear();
        } else {
            return    date.toISOString().slice(0, formated).replace("T", sep);
        }

}
}



function getTimeOnDate(inDate) {
    if ((inDate == null) || (inDate == "")) {
        return inDate;
    }
    var date = new Date(inDate);
    if (!validateDate(date)) {
        return inDate;
    }
    date.setMinutes(date.getMinutes() - date.getTimezoneOffset());

    return    date.toISOString().slice(11, 16);
}



function addAmountResume(listOf, item, value) {
    if (item != null) {
        if (item != "") {
            if (!(item in listOf)) {
                listOf[item] = {desigination: item, amount: value};
            } else {
                listOf[item].amount = Number(listOf[item].amount) + Number(value);
            }
        }
    }
}


function resume(tblResume, nRows, sType, listOf, element, strValue = "Valor (AOA)") {

    if (nRows == 1) {
        let rwHd = tblResume.insertRow(-1);
        rwHd.setAttribute("class", "borderGray");

        let cellType = rwHd.insertCell(-1);
        cellType.setAttribute("colspan", "2");
        cellType.innerHTML = sType.bold();

        let cellValue = rwHd.insertCell(-1);
        cellValue.innerHTML = strValue.bold();
        cellValue.style.textAlign = "right";
    }
    let newRow = tblResume.insertRow(-1);
    newRow.setAttribute("class", "borderGray");

    let cellRow = newRow.insertCell(-1);
    cellRow.setAttribute("class", "colRowNumber");
    cellRow.innerHTML = nRows;

    let cellDesign = newRow.insertCell(-1);
    cellDesign.innerHTML = listOf[element].desigination;

    let cellAmount = newRow.insertCell(-1);
    cellAmount.style.textAlign = "right";
    cellAmount.style.paddingRight = "5px";
    cellAmount.style.whiteSpace = "nowrap";
    cellAmount.innerHTML = formatNumber(listOf[element].amount);
}


function invoicePrintA4(printNumber, via = 1) {
    window.open("_pdf/SaleDocumentLayoutA4.php?pn=" + printNumber + "&v=" + via + "&a=0");
}

function invoicePrintReceipt(printNumber, via = 1) {
    window.open("_pdf/SaleDocumentReceipt.php?pn=" + printNumber + "&v=" + via + "&a=0");
}

function invoiceSendByEmail(printNumber, companyName = "", documentNumber = "Documento", customerName = "", emails = "") {
    var remetente = "<b>" + companyName + "</b";
    var invoiceUrl = encodeURIComponent("https://www." + DOMAIN + "/_pdf/SaleDocumentLayout.php?pn=" + printNumber);
    var content = "<p>Caro " + customerName + ",</p>" +
            "<p>Segue a baixo o link de acesso ao documento solicitado.</p>" +
            "<p><b>" + documentNumber + "</b></p>" +
            '<a href="' + invoiceUrl + '">Acessar ' + documentNumber + '</a>';
    mailingSend(emails, documentNumber, content, remetente);
}

function invoiceSendByWhatsApp(printNumber, documentNumber = "Documento", customerName = "", phoneNumber = "") {
    var companyName = document.getElementById('lbHearderCompanyName').innerHTML;
    var invoiceUrl = encodeURIComponent("https://www." + DOMAIN + "/_pdf/SaleDocumentLayout.php?pn=" + printNumber);
    invoiceUrl = invoiceUrl.replace(" ", "%20");
    var content = "*" + companyName + "*%0a%0a" +
            "*" + documentNumber + "*%0a%0a" +
            "Caro " + customerName + ",%0a" +
            "Segue a baixo o link de acesso ao documento solicitado. %0a%0a" +
            "*" + invoiceUrl + "*%0a%0a" +
            "Grato pela sua visita %0a%0a" +
            "--------------%0a" +
            "*Sílica Aqua* - _Software de facturação e gestão certificado pela AGT_%0a" +
            "_www.silicaweb.ao_";
    window.open("https://wa.me/" + phoneNumber + "/?text=" + content);
}





function paymentPrintA4(printNumber, via = 1) {
    window.open("_pdf/PaymentDocumentLayoutA4.php?pn=" + printNumber + "&v=" + via + "&a=0");
}

function paymentPrintReceipt(printNumber, via = 1) {
    window.open("_pdf/PaymentDocumentReceipt.php?pn=" + printNumber + "&v=" + via + "&a=0");
}

function paymentSendByEmail(printNumber, companyName = "", documentNumber = "Documento", customerName = "", emails = "") {
    var remetente = "<b>" + companyName + "</b";
    var invoiceUrl = encodeURIComponent("https://www." + DOMAIN + "/_pdf/PaymentDocumentLayout.php?pn=" + printNumber);
    var content = "<p>Caro" + customerName + ",</p>" +
            "<p>Segue a baixo o link de acesso ao documento solicitado.</p>" +
            "<p><b>" + documentNumber + "</b></p>" +
            '<a href="' + invoiceUrl + '">Acessar ' + documentNumber + '</a>';
    mailingSend(emails, documentNumber, content, remetente);
}

function paymentSendByWhatsApp(printNumber, documentNumber = "Documento", customerName = "", phoneNumber = "") {
    var companyName = document.getElementById('lbHearderCompanyName').innerHTML;
    var invoiceUrl = encodeURIComponent("https://www." + DOMAIN + "/_pdf/PaymentDocumentLayout.php?pn=" + printNumber);
    invoiceUrl = invoiceUrl.replace(" ", "%20");
    var content = "*" + companyName + "*%0a%0a" +
            "*" + documentNumber + "*%0a%0a" +
            "Caro " + customerName + ",%0a" +
            "Segue a baixo o link de acesso ao documento solicitado. %0a%0a" +
            "*" + invoiceUrl + "*%0a%0a" +
            "Grato pela sua visita %0a%0a" +
            "--------------%0a" +
            "*Sílica Aqua* - _Software de facturação e gestão certificado pela AGT_%0a" +
            "_www.silicaweb.ao_";
    window.open("https://wa.me/" + phoneNumber + "/?text=" + content);
}





function entrancePrintA4(printNumber, via = 1) {
    window.open("_pdf/EntranceDocumentLayoutA4.php?pn=" + printNumber + "&v=" + via + "&a=0");
}

function entrancePrintReceipt(printNumber, via = 1) {
    window.open("_pdf/EntranceDocumentReceipt.php?pn=" + printNumber + "&v=" + via + "&a=0");
}

function entranceSendByEmail(printNumber, companyName = "", documentNumber = "Documento", customerName = "", emails = "") {
    var remetente = "<b>" + companyName + "</b";
    var invoiceUrl = encodeURIComponent("https://www." + DOMAIN + "/_pdf/EntranceDocumentLayout.php?pn=" + printNumber);
    var content = "<p>Caro " + customerName + ",</p>" +
            "<p>Segue a baixo o link de acesso ao documento solicitado.</p>" +
            "<p><b>" + documentNumber + "</b></p>" +
            '<a href="' + invoiceUrl + '">Acessar ' + documentNumber + '</a>';
    mailingSend(emails, documentNumber, content, remetente);
}

function entranceSendByWhatsApp(printNumber, documentNumber = "Documento", customerName = "", phoneNumber = "") {
    var companyName = document.getElementById('lbHearderCompanyName').innerHTML;
    var invoiceUrl = encodeURIComponent("https://www." + DOMAIN + "/_pdf/EntranceDocumentLayout.php?pn=" + printNumber);
    invoiceUrl = invoiceUrl.replace(" ", "%20");
    var content = "*" + companyName + "*%0a%0a" +
            "*" + documentNumber + "*%0a%0a" +
            "Caro " + customerName + ",%0a" +
            "Segue a baixo o link de acesso ao documento solicitado. %0a%0a" +
            "*" + invoiceUrl + "*%0a%0a" +
            "Grato pela sua visita %0a%0a" +
            "--------------%0a" +
            "*Sílica Aqua* - _Software de facturação e gestão certificado pela AGT_%0a" +
            "_www.silicaweb.ao_";
    window.open("https://wa.me/" + phoneNumber + "/?text=" + content);
}



function movimentPrintA4(printNumber, via = 1) {
    window.open("_pdf/MovimentDocumentLayoutA4.php?pn=" + printNumber + "&v=" + via + "&a=0");
}

function movimentPrintReceipt(printNumber, via = 1) {
    window.open("_pdf/MovimentDocumentReceipt.php?pn=" + printNumber + "&v=" + via + "&a=0");
}

function movimentSendByEmail(printNumber, companyName = "", documentNumber = "Documento", customerName = "", emails = "") {
    var remetente = "<b>" + companyName + "</b";
    var invoiceUrl = encodeURIComponent("https://www." + DOMAIN + "/_pdf/MovimentDocumentLayout.php?pn=" + printNumber);
    var content = "<p>Caro " + customerName + ",</p>" +
            "<p>Segue a baixo o link de acesso ao documento solicitadi.</p>" +
            "<p><b>" + documentNumber + "</b></p>" +
            '<a href="' + invoiceUrl + '">Acessar ' + documentNumber + '</a>';
    mailingSend(emails, documentNumber, content, remetente);
}

function movimentSendByWhatsApp(printNumber, documentNumber = "Documento", customerName = "", phoneNumber = "") {
    var companyName = document.getElementById('lbHearderCompanyName').innerHTML;
    var invoiceUrl = encodeURIComponent("https://www." + DOMAIN + "/_pdf/MovimentDocumentLayout.php?pn=" + printNumber);
    invoiceUrl = invoiceUrl.replace(" ", "%20");
    var content = "*" + companyName + "*%0a%0a" +
            "*" + documentNumber + "*%0a%0a" +
            "Caro " + customerName + ",%0a" +
            "Segue a baixo o link de acesso ao documento solicitado. %0a%0a" +
            "*" + invoiceUrl + "*%0a%0a" +
            "Grato pela sua visita %0a%0a" +
            "--------------%0a" +
            "*Sílica Aqua* - _Software de facturação e gestão certificado pela AGT_%0a" +
            "_www.silicaweb.ao_";
    window.open("https://wa.me/" + phoneNumber + "/?text=" + content);
}

function lockWindowShow() {
    showParts('divLockWindow');
    document.getElementById('lbLockWindowUserFullName').innerHTML = SYSTEM_USER_STATUS.userFullName;
    document.getElementById('txtIndPassword').value = "";
    document.getElementById('txtIndPassword').focus();
    setTriggerButtonByText('txtIndPassword', 'btIndLogin');
    setCookie("sms_lockwindows", 1, 200000);
    systemUserLogOut(true);
}



function lockWindowLogin() {
    var lbMsg = document.getElementById('lbIndMsgError');
    var username = SYSTEM_USER_STATUS.email;
    var pass = document.getElementById('txtIndPassword').value;
    lbMsg.innerHTML = "";

    var http = new XMLHttpRequest();
    var params = "action=getSystemUserInfo&username=" + username + "&pass=" + pass +
            "&whoCall=LOGIN&udaox=0&openUser=0";
    var url = 'indexLogin_crud.php';
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(http.responseText);
            if (response['status'] == 1) {
                hideParts('divLockWindow');
                setCookie("sms_lockwindows", 0, 200000);
                setSessionTimeOut();
            } else {
                lbMsg.innerHTML = "Email ou palavra passe errada.";
            }
        }
    };
    http.send(params);
}


function systemUserLogOut(onlyUpdateTimeOut = false) {

    if (!onlyUpdateTimeOut) {
        showNotification("Saindo do Sílica Aqua...");
    }

    var http = new XMLHttpRequest();
    var params = "action=logoutUser";
    var url = 'indexLogin_crud.php';
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {

        }
    };
    http.send(params);

    if (!onlyUpdateTimeOut) {
        setTimeout(function () {
            var path = window.location.href;
            window.location.href = "index.php?wc=" + path.substring(path.lastIndexOf('/') + 1);
        }, 2000);
}
}



function mailingSend(destinatary, subject, contentHtmlStyle, entityRemetent = "") {
    try {
        var mailInfo = {destinatary: destinatary, subject: subject,
            contentHtmlStyle: contentHtmlStyle, entityRemetent: entityRemetent};
        var http = new XMLHttpRequest();
        var params = "action=sendMailStandard" + "&mailInfo=" + JSON.stringify(mailInfo);
        var url = URL_SA_CRUD_X;
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function () {
            if (http.readyState == 4 && http.status == 200) {
                var response = this.responseText;
                if (response == true) {
                    showNotification("E-mail enviado", 1);
                } else {
                    showNotification("Não foi possível enviar o e-mail.", 0);
                }
            }
        };
        http.send(params);

    } catch (ex) {
        showNotification(ex.message, 0);
}
}


function getAllSelects() {
    var selectors = document.querySelectorAll("select");
    for (var i = 0; i < selectors.length; i++) {
        let sel = selectors[i];
        sel.addEventListener('input', () => {
            sel.title = sel.value;
        });
        sel.dispatchEvent(new Event('input'));
    }
}

function setSelectValue(selectId, valueToSet = "") {
    var select = document.getElementById(selectId);
    if (validateUserControl(select)) {
        select.value = valueToSet;
        select.dispatchEvent(new Event('input'));
}
}


function dispatchOnInputChange() {
    var selectors = document.querySelectorAll("select");
    for (var i = 0; i < selectors.length; i++) {
        let sel = selectors[i];
        sel.dispatchEvent(new Event('input'));
    }
}


function productSearchPOSOnLoad() {
    showNotification();
    function psoLoad() {
        fillSelectProductSectionPOS('divProdProcSection', -1);
        productAutocompleteList('txtProdProcSearchProductPOS');
        setTriggerButtonByText('txtProdProcSearchProductPOS', 'btProdProcSearchProductPOS');
        hideNotification();
    }

    checkUserStatusAfterExecute(psoLoad);
}




function productSearchProductPOS(divToClose, sender) {

    var mainDiv = document.getElementById('divProdProcProductListPOS');
    var companyId = SYSTEM_USER_STATUS.companyId;
    var workplaceId = SYSTEM_USER_STATUS.workplaceId;
    var productSection = document.getElementById("txtProdProcSectionPOS").value;
    var productCategory = "-1";// document.getElementById("selProdProcProductCategory").value;
    var searchLimit = -1;// document.getElementById("numProdProcSearchLimit").value;
    var searchTag = reviewString(document.getElementById("txtProdProcSearchProductPOS"));

    if (isNaN(searchLimit)) {
        searchLimit = 5;
    }
    var http = new XMLHttpRequest();
    var params = "action=getProductListSearch&companyId=" + companyId + "&productSection=" + productSection +
            "&productCategory=" + productCategory + "&&searchLimit=" + searchLimit + "&&searchTag=" + searchTag +
            "&workplaceId=" + workplaceId;
    var url = URL_SA_PRODUCT_SALE_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (http.readyState == 4 && http.status == 200) {
            var products = JSON.parse(this.responseText);
            if (products.length <= 0) {
                showNotification("Não encontrado", 0);
            }
            mainDiv.innerHTML = "";
            var nRows = 0;
            products.forEach(element => createTbl(element));
            function createTbl(element) {
                nRows += 1;
                let product = JSON.parse(element);
                var description = product['productdescription'];

                var divProduct = document.createElement("div");
                divProduct.id = "divProdSearPOS" + product['id'];
                //  divProduct.classList.add(setSelectedRowBackground("SELECTED_ROW_PRODUCT_SEARCH", divProduct.id));
                divProduct.title = description;
                divProduct.setAttribute('class', 'div-product-pos');
                mainDiv.appendChild(divProduct);
                if (product['photo'] != "") {
                    divProduct.style.backgroundImage = "url(" + product['photo'] + ")";//../_svg/user-circle_white_00.svg);
                }

                var divProductCont = document.createElement('div');
                divProductCont.setAttribute('class', 'div-product-content-pos');
                divProduct.appendChild(divProductCont);

                var lbDescrip = document.createElement('span');
                lbDescrip.innerHTML = description;
                divProductCont.appendChild(lbDescrip);

                var divPrice = document.createElement('div');
                divPrice.setAttribute('class', 'div-product-price-pos');
                divProduct.appendChild(divPrice);

                var lbPrice = document.createElement('span');
                lbPrice.setAttribute('class', 'span-product-price-pos');
                var barCode = product['productnumbercode'];
                if (sender == "VD") {
                    barCode = "PVP: " + formatNumber(product['pvp']).bold();
                }
                lbPrice.innerHTML = barCode;
                divPrice.appendChild(lbPrice);

                var lbStock = document.createElement('span');
                if ((product['productstock'] == 0)) {
                    lbStock.innerHTML = "NE";
                } else {
                    lbStock.innerHTML = "qt: " + formatNumber(product['quantity']);
                }

                divPrice.appendChild(lbStock);

                const productCode = product['id'];
                divProduct.onclick = function () {
                    if (sender == "productPrice") {
                        document.getElementById("txtProdPricoProduct").value = productCode;
                        productPriceAddProduct('');
                    } else if (sender == "PRODCAD") {
                        productProdutOpen(productCode);
                    } else if (sender == "PROESTENTR") {
                        document.getElementById("txtProdEstEntrProduct").value = productCode;
                        entranceAddProduct();
                    } else if (sender == "PRODESTSAI") {
                        document.getElementById("txtProdEstSaiProduct").value = productCode;
                        movimentAddProduct(0);
                    } else if (sender == "PRODEXPD") {
                        productExpirationGetList(productCode);
                    } else if (sender == "PRODESTSAILIS") {
                        movimentListGet(-1, -1, -1, -1, -1, -1, -1, productCode);
                    } else if (sender == "PRODESTENTRLIS") {
                        entranceListGet(-1, -1, -1, -1, -1, -1, productCode);
                    } else if (sender == "PRODSTCKMANAJU") {
                        document.getElementById("txtProdStckManAjuProduct").value = productCode;
                        productStockManualAjustAddProduct('');
                    } else if (sender == "VDORDERLIST") {
                        postsaleOrderList(-1, -1, productCode);
                    } else if (sender == "VDPF") {
                        postsellGetDocuments(-1, -1, -1, -1, productCode);
                    } else {
                        document.getElementById("txtVDFactProduct").value = productCode;
                        invoiceAddProduct('tblVDFactMainTable');
                    }

                    hideParts(divToClose);
                };

                // setTriggerButtonByRowDobleClick(newRow.id, btView.id, "SELECTED_ROW_PRODUCT_SEARCH");
            }
        }
    };
    http.send(params);

}



function temporaryBackgroundGray(elementId) {
    var element = document.getElementById(elementId);
    if (validateUserControl(element)) {
        element.style.animation = "blink-background-gray 0.7s step-end infinite";
        setTimeout(function () {
            element.style.animation = "";
        }, 3000);
    }
}

function setResultTotalPages(numPagesId, numItemsId, totalItems) {
    var numPages = document.getElementById(numPagesId);
    var numItems = document.getElementById(numItemsId);

    var pages = Number(totalItems / Number(numItems.value));
    var decPart = Number(pages - Math.floor(pages));
    if (decPart > 0) {
        pages = Math.floor(pages) + 1;
    }
    if (pages < 0) {
        pages = 1;
    }

    numPages.max = pages;
    setElementInnerHtml("lbTesultTotalPages", pages);

    if (Number(numPages.value) > pages) {
        numPages.value = pages;
    }
}

function alterVirtualKeyboardStatus() {
    var cname = "virtual-keyboard";
    var status = getCookie(cname);
    if (status == 1) {
        status = 0;
    } else {
        status = 1;
    }
    setCookie(cname, status, 10000000);
    setVirtualKeyboardOnInput();
}

function setVirtualKeyboardOnInput() {
    var cname = "virtual-keyboard";
    var status = getCookie(cname);
    var selectors = document.querySelectorAll("input");
    for (var i = 0; i < selectors.length; i++) {
        let sel = selectors[i];
        if (status == 1) {
            sel.classList.add('jQKeyboard');
        } else {
            sel.classList.remove('jQKeyboard');
        }
    }
    callKeyBoard();
}

function callKeyBoard() {
    $(function () {
        var keyboard = {
            'layout': [
                // alphanumeric keyboard type
                // text displayed on keyboard button, keyboard value, keycode, column span, new row
                [
                    [
                        ['`', '`', 192, 0, true], ['1', '1', 49, 0, false], ['2', '2', 50, 0, false], ['3', '3', 51, 0, false], ['4', '4', 52, 0, false], ['5', '5', 53, 0, false], ['6', '6', 54, 0, false],
                        ['7', '7', 55, 0, false], ['8', '8', 56, 0, false], ['9', '9', 57, 0, false], ['0', '0', 48, 0, false], ['-', '-', 189, 0, false], ['=', '=', 187, 0, false],
                        ['q', 'q', 81, 0, true], ['w', 'w', 87, 0, false], ['e', 'e', 69, 0, false], ['r', 'r', 82, 0, false], ['t', 't', 84, 0, false], ['y', 'y', 89, 0, false], ['u', 'u', 85, 0, false],
                        ['i', 'i', 73, 0, false], ['o', 'o', 79, 0, false], ['p', 'p', 80, 0, false], ['[', '[', 219, 0, false], [']', ']', 221, 0, false], ['&#92;', '\\', 220, 0, false],
                        ['a', 'a', 65, 0, true], ['s', 's', 83, 0, false], ['d', 'd', 68, 0, false], ['f', 'f', 70, 0, false], ['g', 'g', 71, 0, false], ['h', 'h', 72, 0, false], ['j', 'j', 74, 0, false],
                        ['k', 'k', 75, 0, false], ['l', 'l', 76, 0, false], [';', ';', 186, 0, false], ['&#39;', '\'', 222, 0, false], ['Enter', '13', 13, 3, false],
                        ['Shift', '16', 16, 2, true], ['z', 'z', 90, 0, false], ['x', 'x', 88, 0, false], ['c', 'c', 67, 0, false], ['v', 'v', 86, 0, false], ['b', 'b', 66, 0, false], ['n', 'n', 78, 0, false],
                        ['m', 'm', 77, 0, false], [',', ',', 188, 0, false], ['.', '.', 190, 0, false], ['/', '/', 191, 0, false], ['Shift', '16', 16, 2, false],
                        ['Bksp', '8', 8, 3, true], ['Space', '32', 32, 12, false], ['Clear', '46', 46, 3, false], ['Cancel', '27', 27, 3, false]
                    ]
                ]
            ]
        }
        $('input.jQKeyboard').initKeypad({'keyboardLayout': keyboard});
    });
}


function cilToSerieSequence(cil) {
    var parts = cil.split("-");
    var seq = parts[parts.length - 1];
    var serie = "";
    if (parts.length > 1) {
        for (var i = 0; i < (parts.length - 1); i++) {
            serie += parts[i] + "-";
        }
    } else {
        serie = cil.replace(/[^A-Z]/g, '');
        seq = cil.replace(/^\D+/g, '');
    }
    return {serie: serie, seq: seq};
}


function setDivFieldsetLegend() {
    $(".div-fieldset-legend").each(function () {
        if (this.getAttribute("set-div-fieldset") != 1) {
            const subArrow = document.createElement("span");
            subArrow.setAttribute("class", "sub-arrow rotate-270-deg");
            this.prepend(subArrow);
            this.onclick = function () {
                if (this.getAttribute("status") != 0) {
                    $(this).parent().removeClass("div-fieldset-expand");
                    $(this).parent().addClass("div-fieldset-collapse");
                    this.setAttribute("status", "0");

                    subArrow.classList.remove("rotate-270-deg");
                    subArrow.classList.add("rotate-90-deg");
                } else {
                    $(this).parent().removeClass("div-fieldset-collapse");
                    $(this).parent().addClass("div-fieldset-expand");
                    this.setAttribute("status", "1");
                    subArrow.classList.remove("rotate-90-deg");
                    subArrow.classList.add("rotate-270-deg");
                }
            };
            this.setAttribute("set-div-fieldset", 1);
        }
    });
}



function rootSilicaUniverMenuConstroctor() {

    var FILTER = {};
    FILTER.userId = SYSTEM_USER_STATUS.userId;
    FILTER.companyId = SYSTEM_USER_STATUS.companyId;
    FILTER.officialpartnerid = SYSTEM_USER_STATUS.officialpartnerid;

    var http = new XMLHttpRequest();
    var params = "action=getRootMenu" + "&filterInfo=" + JSON.stringify(FILTER);
    var url = URL_SU_COMMON_ROOT_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (http.readyState == 4 && http.status == 200) {
            var response = http.responseText;
            setMenuHtml(response);
        }
    };
    http.send(params);

    function setMenuHtml(mHtml) {
        setElementInnerHtml("divIndSilContMenu", mHtml);
    }

}

function createTableCell(row, inner, clas = "", dataLabel = "") {
    let cell = row.insertCell(-1);
  //  let div = createElement("div", "", "");
  //  cell.appendChild(div);
    if ((inner != null) && (inner != "")) {
        cell.innerHTML = inner;
    } else {
        if (dataLabel != "...") {
            clas += " pseudo-content";
        }
    }

 /*   var tbody = $(row).parent()[0];
    var colIdx = cell.cellIndex;
    var att = tbody.getAttribute("hide_this_col_" + colIdx);
    var tIdx = tbody.getAttribute("table_index");
    if (att == 1) {
        clas += " display-none";
    }*/
    if (clas != "") {
        cell.setAttribute("class", clas);
    }
   // clas += " div-on-cell-to-print-" + tIdx + "-" + colIdx;
  //  div.setAttribute("class", clas.replace("pseudo-content", ""));

    if (dataLabel != "") {
        cell.setAttribute("data-label", dataLabel);
    }
    if (dataLabel != "") {
        cell.setAttribute("title", dataLabel);
    }

    return cell;
}
