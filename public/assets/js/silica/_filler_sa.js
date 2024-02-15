

function fillSelectAccountBank(selectId, startFrom = - 1) {

    const selAccountBK = document.getElementById(selectId);
    if (!validateUserControl(selAccountBK)) {
        return false;
    }

    selAccountBK.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selAccountBK.appendChild(optionStart);

    }

    var companyId = SYSTEM_USER_STATUS.companyId;
    if (companyId == -1) {
        companyId = 5000;
    }
    var http = new XMLHttpRequest();
    var params = "action=getBankAccountList&companyId=" + companyId + "&accountId=-1";
    var url = URL_SA_BANK_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var accountBanks = JSON.parse(this.responseText);
            accountBanks.forEach(element => createTbl(element));
            var n = 0;
            function createTbl(element) {
                n += 1;
                let accountBk = JSON.parse(element);
                var optAccountBk = document.createElement("option");
                optAccountBk.value = accountBk['accountid'];
                optAccountBk.innerHTML = accountBk['initials'] + " - " + accountBk['accountnumber'];
                selAccountBK.appendChild(optAccountBk);
                /*   selAccountBK.name = n;
                 if (selAccountBK.name == 1){
                 fillSelectTpaTerminal('selRecPagTpaTerminal', optAccountBk.value);
                 }*/
            }
            selAccountBK.dispatchEvent(new Event('input'));
        }
    };
    http.send(params);
}

function fillSelectComplaintStatus(selectId, startFrom = 1, initialValue = - 1) {

    setComplaintStatus();
    const selStatus = document.getElementById(selectId);
    if (!validateUserControl(selStatus)) {
        return false;
    }
    selStatus.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selStatus.appendChild(optionStart);
    }


    for (var i = 1; i < COMPLAINT_STATUS.length; i++) {
        let optType = document.createElement("option");
        optType.value = i;
        optType.innerHTML = COMPLAINT_STATUS[i];
        selStatus.appendChild(optType);
    }
    if (initialValue != -1) {
        selStatus.value = initialValue;
    }
    selStatus.dispatchEvent(new Event('input'));
}




function fillSelectProductInstallmentIndicator(selectId, startFrom = 1) {

    const selInd = document.getElementById(selectId);
    if (!validateUserControl(selInd)) {
        return false;
    }
    selInd.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selInd.appendChild(optionStart);
    }


    for (var i = 1; i < PRODUCT_INSTALLMENT_INDICATORS.length; i++) {
        let optType = document.createElement("option");
        optType.value = i;
        optType.innerHTML = PRODUCT_INSTALLMENT_INDICATORS[i];
        selInd.appendChild(optType);
    }
    selInd.dispatchEvent(new Event('input'));
}


function fillSelectDays(selectId, startFrom = 1) {
    const selDays = document.getElementById(selectId);
    if (!validateUserControl(selDays)) {
        return false;
    }
    selDays.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selDays.appendChild(optionStart);
    }

    for (var i = 1; i <= 31; i++) {
        let optDay = document.createElement("option");
        optDay.value = i;
        optDay.innerHTML = i;
        selDays.appendChild(optDay);
    }
    selDays.dispatchEvent(new Event('input'));
}



function fillSelectMonth(selectId, startFrom = 1, initialValue = - 1) {

    const selMonth = document.getElementById(selectId);
    if (!validateUserControl(selMonth)) {
        return false;
    }
    selMonth.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selMonth.appendChild(optionStart);
    }


    for (var i = 0; i <= 11; i++) {
        let optMonth = document.createElement("option");
        optMonth.value = i + 1;
        optMonth.innerHTML = monthName(i);
        selMonth.appendChild(optMonth);
    }
    if (initialValue > 0) {
        selMonth.value = initialValue;
    }
    selMonth.dispatchEvent(new Event('input'));
}




function fillSelectPrintOptionSignature(selectId, startFrom = 1) {
    const selSign = document.getElementById(selectId);
    if (!validateUserControl(selSign)) {
        return false;
    }
    selSign.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar um assinante";
        selSign.appendChild(optionStart);
    }

    var companyId = SYSTEM_USER_STATUS.companyId;
    var dependencyId = SYSTEM_USER_STATUS.workplaceId;
    var http = new XMLHttpRequest();
    var params = "action=getPrintOptionSignature&companyId=" + companyId +
            "&dependencyId=" + dependencyId;
    var url = URL_SA_CRUD_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var signatures = JSON.parse(this.responseText);
            signatures.forEach(element => createTbl(element));
            function createTbl(element) {
                let signature = JSON.parse(element);
                var optValue = document.createElement("option");
                optValue.value = signature['person'] + "-/-" + signature['quality'];
                optValue.innerHTML = signature['person'] + " [" + signature['quality'] + "]";
                selSign.appendChild(optValue);
            }
            selSign.dispatchEvent(new Event('input'));
        }
    };
    http.send(params);


}



function fillSelectConsumerTypeAqua(selectId, startFrom = 1, initialValue = - 1) {

    setConsumerTypeAqua();
    const selType = document.getElementById(selectId);
    if (!validateUserControl(selType)) {
        return false;
    }
    selType.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selType.appendChild(optionStart);
    }


    for (var i = 1; i < CONSUMER_TYPE_AQUA.length; i++) {
        let optType = document.createElement("option");
        optType.value = i;
        optType.innerHTML = CONSUMER_TYPE_AQUA[i];
        selType.appendChild(optType);
    }
    if (initialValue != -1) {
        selType.value = initialValue;
    }
    selType.dispatchEvent(new Event('input'));
}


function fillSelectZoneBlock(selectId, startFrom = 1, initialValue = - 1) {

    const selBlock = document.getElementById(selectId);
    if (!validateUserControl(selBlock)) {
        return false;
    }
    selBlock.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selBlock.appendChild(optionStart);
    }


    for (var i = 1; i < 8; i++) {
        let optType = document.createElement("option");
        var block = "ZONA " + i;
        optType.value = block;
        optType.innerHTML = block;
        selBlock.appendChild(optType);
    }
    if (initialValue != -1) {
        selBlock.value = initialValue;
    }
    selBlock.dispatchEvent(new Event('input'));
}



function fillSelectHydrometerFunctionalStatus(selectId, startFrom = 1, initialValue = - 1) {

    const selStatus = document.getElementById(selectId);
    if (!validateUserControl(selStatus)) {
        return false;
    }
    selStatus.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selStatus.appendChild(optionStart);
    }

    var HYDROMETER_STATUS = ['Funciona', 'Bloqueado', 'Desinstalado', 'Roubado', 'Parado', 'Fechado',
        'Danificado', 'Ilegível', 'Inacessível', 'Invertido'];

    HYDROMETER_STATUS.forEach(element => {
        var optType = document.createElement("option");
        var status = element.toString().toUpperCase();
        optType.value = status;
        optType.innerHTML = status;
        selStatus.appendChild(optType);
    });
    if (initialValue != -1) {
        selStatus.value = initialValue;
    }
    selStatus.dispatchEvent(new Event('input'));
}


function fillSelectHydrometerDiameter(selectId, startFrom = 1, initalValue = - 1) {

    const selDiameter = document.getElementById(selectId);
    if (!validateUserControl(selDiameter)) {
        return false;
    }
    selDiameter.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selDiameter.appendChild(optionStart);
    }

    var HYDROMETER_DIAMETER = ['15', '20', '25', '32', '40',
        '50', '65'];

    HYDROMETER_DIAMETER.forEach(element => {
        var optType = document.createElement("option");
        var status = element.toString().toUpperCase();
        optType.value = status;
        optType.innerHTML = status;
        selDiameter.appendChild(optType);
    });
    if (initalValue != -1) {
        selDiameter.value = initalValue;
    }
    selDiameter.dispatchEvent(new Event('input'));
}

function fillSelectHydrometerRecordStatus(selectId, startFrom = 1) {

    setHydrometerRecordStatus()
    const selStatus = document.getElementById(selectId);
    if (!validateUserControl(selStatus)) {
        return false;
    }
    selStatus.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selStatus.appendChild(optionStart);
    }


    for (var i = 1; i < HYDROMETER_RECORD_STATUS.length; i++) {
        let optType = document.createElement("option");
        optType.value = i;
        optType.innerHTML = HYDROMETER_RECORD_STATUS[i];
        selStatus.appendChild(optType);
    }
    selStatus.dispatchEvent(new Event('input'));
}


function fillSelectCountry(selectId, startFrom = 1) {
    const selCountry = document.getElementById(selectId);
    if (!validateUserControl(selCountry)) {
        return false;
    }
    selCountry.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selCountry.appendChild(optionStart);
    }


    var http = new XMLHttpRequest();
    var params = "action=getCountry";
    var url = URL_SA_CRUD_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var countries = JSON.parse(this.responseText);
            countries.forEach(element => createTbl(element));
            function createTbl(element) {
                let country = JSON.parse(element);
                var optionCountry = document.createElement("option");
                optionCountry.value = country['id'];
                optionCountry.innerHTML = country['country'];
                selCountry.appendChild(optionCountry);
            }
            selCountry.dispatchEvent(new Event('input'));
        }
    };
    http.send(params);


}


function fillSelectProvince(selectId, startFrom = 1, initialValue = - 1, withWP = 0) {
    const selProvince = document.getElementById(selectId);
    selProvince.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selProvince.appendChild(optionStart);
    }

    try {
        var provinces = JSON.parse(sessionStorage.getItem("fill-province-list"));
        realFillProvince(provinces);
    } catch (e) {
        var companyId = SYSTEM_USER_STATUS.companyId;
        var http = new XMLHttpRequest();
        var params = "action=getProvince&companyId=" + companyId;
        var url = URL_SA_CRUD_X;
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                try {
                    var provinces = JSON.parse(this.responseText);
                    sessionStorage.setItem("fill-province-list", http.responseText);
                    realFillProvince(provinces);
                } catch (e) {
                    showHttpResponseText(http);
                }
            }
        };
        http.send(params);
    }




    function realFillProvince(provinces) {
        provinces.forEach(element => createTbl(element));
        function createTbl(element) {
            let province = JSON.parse(element);
            var optProvince = document.createElement("option");
            optProvince.value = province['id'];
            var strProv = province['province'];
            if (withWP == 1) {
                strProv += " (" + province['NumberOfWP'] + ")";
            }
            optProvince.innerHTML = strProv;
            selProvince.appendChild(optProvince);
        }
        if (initialValue != -1) {
            selProvince.value = initialValue;
        }
        selProvince.dispatchEvent(new Event('input'));
}

}


function fillSelectMunicipality(selectId, provinceId, startFrom = 1, initValue = - 1, withWP = 0) {
    const selMunicipality = document.getElementById(selectId);
    if (!validateUserControl(selMunicipality)) {
        return false;
    }
    selMunicipality.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selMunicipality.appendChild(optionStart);
    }
    if ((isNaN(provinceId)) || (Number(provinceId) <= 0)) {
        return false;
    }
    try {
        var municipalities = JSON.parse(sessionStorage.getItem("fill-municipality-list"));
        realFillMunicipality(municipalities);
    } catch (e) {
        var companyId = SYSTEM_USER_STATUS.companyId;
        var http = new XMLHttpRequest();
        var params = "action=getMunicipality&companyId=" + companyId + "&provinceId=-1";
        var url = URL_SA_CRUD_X;
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                try {
                    sessionStorage.setItem("fill-municipality-list", http.responseText);
                    var municipalities = JSON.parse(this.responseText);
                    realFillMunicipality(municipalities);
                } catch (e) {
                    alert(e.message);
                    showHttpResponseText(http);
                }
            }
        };
        http.send(params);
    }

    function realFillMunicipality(municipalities) {
        municipalities.forEach(element => createTbl(element));
        function createTbl(element) {
            let municipality = JSON.parse(element);
            if (municipality['provinceid'] == provinceId) {
                var optMunicipality = document.createElement("option");
                optMunicipality.value = municipality['id'];
                var strMun = municipality['municipality'];
                if (withWP == 1) {
                    strMun += " (" + municipality['NumberOfWP'] + ")";
                }
                optMunicipality.innerHTML = strMun;
                selMunicipality.appendChild(optMunicipality);
            }
        }
        if (initValue != -1) {
            selMunicipality.value = initValue;
        }
        selMunicipality.dispatchEvent(new Event('input'));
}

}



function fillSelectProductSection(selectId, startFrom = 1, chargeType = - 1) {
    const selProductSection = document.getElementById(selectId);
    if (!validateUserControl(selProductSection)) {
        return false;
    }
    PERIODIC_SERVICE_LIST = [];
    setProductChargeType('');
    selProductSection.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selProductSection.appendChild(optionStart);
    }
    var companyId = SYSTEM_USER_STATUS.companyId;
    var http = new XMLHttpRequest();
    var params = "action=getProductSection&companyId=" + companyId + "&chargeType=" + chargeType;
    var url = URL_SA_PRODUCT_SALE_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var productSections = [];
            productSections = JSON.parse(this.responseText);
            productSections.forEach(element => createTbl(element));
            function createTbl(element) {
                let productSection = JSON.parse(element);
                var optionProductSection = document.createElement("option");
                optionProductSection.value = productSection['id'];
                var strSection = productSection['section'];
                if (chargeType == 1) {
                    strSection += " (" + PRODUCT_CHARGE_TYPES[productSection['chargetype']] + ")";
                }
                optionProductSection.innerHTML = strSection;
                selProductSection.appendChild(optionProductSection);
                PERIODIC_SERVICE_LIST[productSection['id']] = productSection;
            }
            selProductSection.dispatchEvent(new Event('input'));
        }
    };
    http.send(params);

}



function fillSelectProductSectionPOS(mainDivId, startFrom = 1, chargeType = - 1) {

    const mainDiv = document.getElementById(mainDivId);
    if (!validateUserControl(mainDiv)) {
        return false;
    }
    PERIODIC_SERVICE_LIST = [];
    setProductChargeType('');
    mainDiv.innerHTML = "";
    if (startFrom == -1) {
        //productSearchProductPOS(divToClose, sender);
        var divAll = document.createElement("div");
        divAll.title = "Ver todos produtos e serviços";
        divAll.setAttribute('class', 'div-section-pos');
        mainDiv.appendChild(divAll);
        divAll.onclick = function () {
            setElementValue('txtProdProcSectionPOS', -1);
            document.getElementById('btProdProcSearchProductPOS').click();
        };

        var divAllCont = document.createElement('div');
        divAllCont.setAttribute('class', 'div-section-content-pos');
        divAll.appendChild(divAllCont);

        var lbText = document.createElement('span');
        lbText.innerHTML = "TODOS PRODUTOS E SERVIÇOS";
        divAllCont.appendChild(lbText);
    }
    var companyId = SYSTEM_USER_STATUS.companyId;
    var http = new XMLHttpRequest();
    var params = "action=getProductSection&companyId=" + companyId + "&chargeType=" + chargeType;
    var url = URL_SA_PRODUCT_SALE_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var productSections = [];
            productSections = JSON.parse(this.responseText);
            productSections.forEach(element => createTbl(element));
            function createTbl(element) {
                let productSection = JSON.parse(element);
                var strSection = productSection['section'];
                if (chargeType == 1) {
                    strSection += " (" + PRODUCT_CHARGE_TYPES[productSection['chargetype']] + ")";
                }

                var divSection = document.createElement("div");
                divSection.title = strSection;
                divSection.setAttribute('class', 'div-section-pos');
                mainDiv.appendChild(divSection);
                divSection.onclick = function () {
                    setElementValue('txtProdProcSectionPOS', productSection['id']);
                    document.getElementById('btProdProcSearchProductPOS').click();
                };
                if (productSection['photo'] != "") {
                    divSection.style.backgroundImage = "url(" + productSection['photo'] + ")";//../_svg/user-circle_white_00.svg);
                }


                var divSectCont = document.createElement('div');
                divSectCont.setAttribute('class', 'div-section-content-pos');
                divSection.appendChild(divSectCont);

                var lbSection = document.createElement('span');
                lbSection.innerHTML = strSection;
                divSectCont.appendChild(lbSection);



                // var optionProductSection = document.createElement("option");
                //  optionProductSection.value = productSection['id'];

                // optionProductSection.innerHTML = strSection;
                //   selProductSection.appendChild(optionProductSection);
                PERIODIC_SERVICE_LIST[productSection['id']] = productSection;
            }
            //  selProductSection.dispatchEvent(new Event('input'));
        }
    };
    http.send(params);

}



function fillSelectProductIvaCategory(selectId, startFrom = 1, initValue = 0) {

    const selProductIvaCategory = document.getElementById(selectId);
    if (!validateUserControl(selProductIvaCategory)) {
        return false;
    }
    selProductIvaCategory.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selProductIvaCategory.appendChild(optionStart);
    }

    var companyId = SYSTEM_USER_STATUS.companyId;
    var http = new XMLHttpRequest();
    var params = "action=getProductIvaCategory&companyId=" + companyId;
    var url = URL_SA_PRODUCT_SALE_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var productCategories = [];
            productCategories = JSON.parse(this.responseText);
            productCategories.forEach(element => createTbl(element));
            function createTbl(element) {
                let productIvaCategory = JSON.parse(element);
                var optionProductIvaCategory = document.createElement("option");
                optionProductIvaCategory.value = productIvaCategory['id'];
                optionProductIvaCategory.innerHTML = productIvaCategory['category'];// + (": " + productIvaCategory['exemptionreason']);
                selProductIvaCategory.appendChild(optionProductIvaCategory);
            }
            if (startFrom != -1) {
                selProductIvaCategory.value = "17";
            }
            if (initValue != 0) {
                selProductIvaCategory.value = initValue;
            }
            selProductIvaCategory.dispatchEvent(new Event('input'));
        }
    };
    http.send(params);

}


function fillSelectProductCategory(selectId, startFrom = 1, initValue = "") {
    const selCategory = document.getElementById(selectId);
    if (!validateUserControl(selCategory)) {
        return false;
    }
    selCategory.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selCategory.appendChild(optionStart);
    }

    var optCat = document.createElement("option");
    optCat.value = "DIVERSOS";
    optCat.innerHTML = "diversos";
    optCat.style.textTransform = "capitalize";
    selCategory.appendChild(optCat);

    var http = new XMLHttpRequest();
    var params = "action=getProductCategory";
    var url = URL_SA_PRODUCT_SALE_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            try {
                var categories = JSON.parse(this.responseText);
                var oldGroup = "-/-";
                var nC = 0;
                var optGroup = document.createElement('optgroup');

                categories.forEach(element => createTbl(element));
                function createTbl(element) {
                    let category = JSON.parse(element);
                    let group = category['cetegorygroup'];
                    let strCategory = category['category'].toUpperCase();
                    if (group != oldGroup) {
                        if (nC != 0) {
                            selCategory.appendChild(optGroup);
                            nC = 0;
                        }
                        optGroup = document.createElement('optgroup');
                        optGroup.label = group;
                        oldGroup = group;
                        nC += 1;
                    }
                    var optCat = document.createElement("option");
                    optCat.value = strCategory;
                    optCat.innerHTML = strCategory.toLowerCase();
                    optCat.style.textTransform = "capitalize";
                    if (optGroup !== null) {
                        optGroup.appendChild(optCat);
                    }
                }
                if (optGroup !== null) {
                    selCategory.appendChild(optGroup);
                }
                var optCat = document.createElement("option");
                optCat.value = "OUTRA";
                optCat.innerHTML = "outra";
                optCat.style.textTransform = "capitalize";
                selCategory.appendChild(optCat);
                selCategory.style.textTransform = "capitalize";
                if (startFrom != -1) {
                    selCategory.value = optCat.value;
                }
                if (initValue != "") {
                    selCategory.value = initValue;
                }
                selCategory.dispatchEvent(new Event('input'));
            } catch (ex) {
                alert(ex.message);
            }
        }
    };
    http.send(params);

}





function fillSelectProductFamilyBrandModel(selectId, familyBrandModel, startFrom = 1) {

    const selFamily = document.getElementById(selectId);
    if (!validateUserControl(selFamily)) {
        return false;
    }
    selFamily.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selFamily.appendChild(optionStart);
    }
    var companyId = SYSTEM_USER_STATUS.companyId;
    var http = new XMLHttpRequest();
    var params = "action=getProductFamlily&companyId=" + companyId + "&familyBrandModel=" + familyBrandModel;
    var url = URL_SA_PRODUCT_SALE_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var families = JSON.parse(this.responseText);
            families.forEach(element => createTbl(element));
            function createTbl(element) {
                let family = JSON.parse(element);
                var optFamily = document.createElement("option");
                optFamily.value = family[familyBrandModel];
                optFamily.innerHTML = family[familyBrandModel];
                selFamily.appendChild(optFamily);
            }
            selFamily.dispatchEvent(new Event('input'));
        }
    };
    http.send(params);

}



function fillSelectSupplier(selectId, startFrom = 1, initialValue = - 1) {

    const selSupplier = document.getElementById(selectId);
    if (!validateUserControl(selSupplier)) {
        return false;
    }
    selSupplier.innerHTML = "";
    if (startFrom == -1) {
        var optStart = document.createElement("option");
        optStart.value = "-1";
        optStart.setAttribute('class', 'selectPlaceHolder');
        optStart.innerHTML = "Seleccionar um fornecedor";
        selSupplier.appendChild(optStart);
    }

    var companyId = SYSTEM_USER_STATUS.companyId;
    var http = new XMLHttpRequest();
    var params = "action=getSupplierList&companyId=" + companyId + "&supplierId=-1";
    var url = URL_SA_SUPPLIER_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var suppliers = JSON.parse(this.responseText);
            suppliers.forEach(element => createTbl(element));
            function createTbl(element) {
                let supplier = JSON.parse(element);
                var optSupplier = document.createElement("option");
                optSupplier.value = supplier['id'];
                optSupplier.innerHTML = supplier['companyname'];
                selSupplier.appendChild(optSupplier);
            }
            if (initialValue != -1) {
                selSupplier.value = initialValue;
            }
            selSupplier.dispatchEvent(new Event('input'));
        }
    };
    http.send(params);

}



function fillSelectRubric(selectId, startFrom = 1, initialValue = - 1) {

    const selRubric = document.getElementById(selectId);
    if (!validateUserControl(selRubric)) {
        return false;
    }
    selRubric.innerHTML = "";
    if (startFrom == -1) {
        var optStart = document.createElement("option");
        optStart.value = "-1";
        optStart.setAttribute('class', 'selectPlaceHolder');
        optStart.innerHTML = "Seleccionar uma rúbrica";
        selRubric.appendChild(optStart);
    }

    var companyId = SYSTEM_USER_STATUS.companyId;
    var http = new XMLHttpRequest();
    var params = "action=getTreasuryRubricList&companyId=" + companyId + "&dependencyId=-1" +
            "&costType=-1";
    var url = URL_SA_TREASURY_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var rubrics = JSON.parse(this.responseText);
            rubrics.forEach(element => createTbl(element));
            function createTbl(element) {
                let rubric = JSON.parse(element);
                var optRubric = document.createElement("option");
                optRubric.value = rubric['id'];
                optRubric.innerHTML = rubric['description'];
                selRubric.appendChild(optRubric);
            }
            if (initialValue != -1) {
                selRubric.value = initialValue;
            }
            selRubric.dispatchEvent(new Event('input'));
        }
    };
    http.send(params);

}




function fillSelectCustomerType(selectId, startFrom = 1, initialValue = - 1) {
    setCustomerType('');
    const selType = document.getElementById(selectId);
    if (!validateUserControl(selType)) {
        return false;
    }
    selType.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selType.appendChild(optionStart);
    }


    for (var i = 1; i < CUSTOMER_TYPE.length; i++) {
        let optType = document.createElement("option");
        optType.value = i;
        optType.innerHTML = CUSTOMER_TYPE[i];
        selType.appendChild(optType);
    }
    if (initialValue != -1) {
        selType.value = initialValue;
    }
    selType.dispatchEvent(new Event('input'));
}


function fillSelectUserType(selectId, userType, companyId, sender) {

    const selUserType = document.getElementById(selectId);
    selUserType.innerHTML = "";
    var optionStart = document.createElement("option");
    optionStart.value = "-1";
    optionStart.setAttribute('class', 'selectPlaceHolder');
    optionStart.innerHTML = "Seleccionar";
    selUserType.appendChild(optionStart);

    var http = new XMLHttpRequest();
    var params = "action=getSaleReportFillSelect&userType=" + userType + "&companyId=" + companyId + "&sender=" + sender;
    var url = URL_SA_SALE_REPORT_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {//Call a function when the state changes.
        if (http.readyState == 4 && http.status == 200) {
            var users = JSON.parse(this.responseText);
            users.forEach(element => createTbl(element));
            function createTbl(element) {
                let user = JSON.parse(element);
                var optUser = document.createElement("option");
                optUser.value = user['userid'];
                optUser.innerHTML = getFirtLastName(user['userfullname']) + " (" + user['userid'] + ")";
                selUserType.appendChild(optUser);
            }
            selUserType.dispatchEvent(new Event('input'));

        }
    };
    http.send(params);
}


function fillSelectSystemUserBillingUser(selectId, userProfile, startFrom = 1) {
    const selProfile = document.getElementById(selectId);
    if (selProfile !== null) {
        selProfile.innerHTML = "";
        if (startFrom == -1) {
            var optionStart = document.createElement("option");
            optionStart.value = "-1";
            optionStart.setAttribute('class', 'selectPlaceHolder');
            optionStart.innerHTML = "Seleccionar";
            selProfile.appendChild(optionStart);
        }
        var companyId = SYSTEM_USER_STATUS.companyId;
        var http = new XMLHttpRequest();
        var params = "action=getSystemUserBillingProfile&companyId=" + companyId + "&userProfile=" + userProfile + "&userId=-1";
        var url = URL_SA_SYSTEM_USER_X;
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function () {//Call a function when the state changes.
            if (http.readyState == 4 && http.status == 200) {
                var users = JSON.parse(this.responseText);
                users.forEach(element => createTbl(element));
                function createTbl(element) {
                    let user = JSON.parse(element);
                    var optUser = document.createElement("option");
                    optUser.value = user['userid'];
                    optUser.innerHTML = user['userfullname'];
                    selProfile.appendChild(optUser);
                }
                selProfile.dispatchEvent(new Event('input'));
            }
        };
        http.send(params);
}
}

function fillSelectPatrimonyType(selectId, startFrom = 1) {
    const selType = document.getElementById(selectId);
    selType.innerHTML = "";
    if (startFrom == -1) {
        var optStart = document.createElement("option");
        optStart.value = "-1";
        optStart.setAttribute('class', 'selectPlaceHolder');
        optStart.innerHTML = "Seleccionar tipo de activo";
        selType.appendChild(optStart);
    }
    var TYPES = ["Imóvel", "Móvel"];
    TYPES.forEach(element => {
        var optType = document.createElement("option");
        optType.value = element.toString().toUpperCase();
        optType.innerHTML = element;
        selType.appendChild(optType);
    });
    selType.dispatchEvent(new Event('input'));
}



function fillSelectPatrimonySubType(selectId, startFrom = 1) {
    const selSubType = document.getElementById(selectId);
    selSubType.innerHTML = "";
    if (startFrom == -1) {
        var optStart = document.createElement("option");
        optStart.value = "-1";
        optStart.setAttribute('class', 'selectPlaceHolder');
        optStart.innerHTML = "Seleccionar subtipo de activo";
        selSubType.appendChild(optStart);
    }
    var SUBTYPES = ['Outro', "Terreno", "Vivenda", 'Apartamento', 'Prédio', 'Automóvel ligeiro',
        'Automóvel pesado', 'Motocíclo', 'Mobiliário', 'Equipamento'];
    SUBTYPES.forEach(element => {
        var optSType = document.createElement("option");
        optSType.value = element.toString().toUpperCase();
        optSType.innerHTML = element;
        selSubType.appendChild(optSType);
    });
    selSubType.dispatchEvent(new Event('input'));
}

function fillSelectPatrimonyTaxSubject(selectId, startFrom = 1) {
    const selTax = document.getElementById(selectId);
    selTax.innerHTML = "";
    if (startFrom == -1) {
        var optStart = document.createElement("option");
        optStart.value = "";
        optStart.setAttribute('class', 'selectPlaceHolder');
        optStart.innerHTML = "Seleccionar tipo de imposto";
        selTax.appendChild(optStart);
    }
    var SUBTYPES = [['IP', 'Imposto Predial'], ['IVM', 'Imposto sobre Veículos Motorizados']];
    SUBTYPES.forEach(element => {
        var optTax = document.createElement("option");
        optTax.value = element[0];
        optTax.innerHTML = element[1];
        selTax.appendChild(optTax);
    });
    selTax.dispatchEvent(new Event('input'));
}


function fillSelectPatrimonyAquisitionStatus(selectId, startFrom = 1) {
    const selStatus = document.getElementById(selectId);
    selStatus.innerHTML = "";
    if (startFrom == -1) {
        var optStart = document.createElement("option");
        optStart.value = "-1";
        optStart.setAttribute('class', 'selectPlaceHolder');
        optStart.innerHTML = "Seleccionar estado do activo";
        selStatus.appendChild(optStart);
    }
    var STATUS = ['Novo', "Usado funcional", "Usado com defeito", 'Não funcional'];
    STATUS.forEach(element => {
        var optStatus = document.createElement("option");
        optStatus.value = element.toString().toUpperCase();
        optStatus.innerHTML = element;
        selStatus.appendChild(optStatus);
    });
    selStatus.dispatchEvent(new Event('input'));
}


function fillSelectDependency(selectId, municipalityId, startFrom = 1, withUser = 0) {
    const selDependency = document.getElementById(selectId);
    selDependency.innerHTML = "";
    if (startFrom == -1) {
        var optStart = document.createElement("option");
        optStart.value = "-1";
        optStart.setAttribute('class', 'selectPlaceHolder');
        optStart.innerHTML = "Seleccionar";
        selDependency.appendChild(optStart);
    }

    var companyId = SYSTEM_USER_STATUS.companyId;
    var http = new XMLHttpRequest();
    var params = "action=getDependeces&companyId=" + companyId + "&municipalityId=" + municipalityId;
    var url = URL_SA_CRUD_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {//Call a function when the state changes.
        if (http.readyState == 4 && http.status == 200) {
            var dependences = JSON.parse(this.responseText);
            dependences.forEach(element => createTbl(element));
            function createTbl(element) {
                let dependence = JSON.parse(element);
                var optDep = document.createElement("option");
                optDep.value = dependence['id'];
                var strDesig = dependence['designation'];
                if (withUser == 1) {
                    strDesig += " (" + dependence['NumberOfU'] + ")";
                }
                optDep.innerHTML = strDesig;
                selDependency.appendChild(optDep);
            }
            selDependency.dispatchEvent(new Event('input'));
        }
    };
    http.send(params);

}


function fillSelectDependencyFull(selectId, startFrom = 1, onlyMyDepependency = 0) {
    const selDependency = document.getElementById(selectId);
    selDependency.innerHTML = "";
    if (startFrom == -1) {
        var optStart = document.createElement("option");
        optStart.value = "-1";
        optStart.setAttribute('class', 'selectPlaceHolder');
        optStart.innerHTML = "Seleccionar";
        selDependency.appendChild(optStart);
    }

    var dependencyId = -1;
    if (onlyMyDepependency == 1) {
        if (Number(SYSTEM_USER_STATUS.billingprofile) < Number(BILLING_PROFILE_PARTNER_INDEX)) {
            dependencyId = Number(SYSTEM_USER_STATUS.workplaceId);
        }
    }

    var companyId = SYSTEM_USER_STATUS.companyId;
    var http = new XMLHttpRequest();
    var params = "action=getCompanyDependencyList&companyId=" + companyId + "&dependencyId=" + dependencyId;
    var url = URL_SA_COMPANY_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {//Call a function when the state changes.
        if (http.readyState == 4 && http.status == 200) {
            var dependences = JSON.parse(this.responseText);
            dependences.forEach(element => createTbl(element));
            function createTbl(element) {
                let dependence = JSON.parse(element);
                var optDep = document.createElement("option");
                optDep.value = dependence['id'];
                optDep.innerHTML = dependence['province'] + " - " + dependence['municipality'] +
                        " - " + dependence['designation'];
                selDependency.appendChild(optDep);
            }
            if (onlyMyDepependency != 0) {
                if (dependencyId > 0) {
                    selDependency.value = dependencyId;
                    selDependency.dispatchEvent(new Event('change'));
                }
            }
            selDependency.dispatchEvent(new Event('input'));
        }
    };
    http.send(params);

}


function fillSelectFund(selectId, startFrom = 1) {

    const selFund = document.getElementById(selectId);
    if (!validateUserControl(selFund)) {
        return false;
    }

    selFund.innerHTML = "";
    if (startFrom == -1) {
        var optStart = document.createElement("option");
        optStart.value = "-1";
        optStart.setAttribute('class', 'selectPlaceHolder');
        optStart.innerHTML = "Seleccionar";
        selFund.appendChild(optStart);
    }

    var http = new XMLHttpRequest();
    var params = "action=getCompanyFund";
    var url = URL_SA_COMPANY_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (http.readyState == 4 && http.status == 200) {
            var funds = JSON.parse(this.responseText);
            funds.forEach(element => createTbl(element));
            function createTbl(element) {
                let fund = JSON.parse(element);
                var optFund = document.createElement("option");
                optFund.value = fund['fundcode'];
                optFund.innerHTML = fund['designation'];
                selFund.appendChild(optFund);
            }
            selFund.dispatchEvent(new Event('input'));
        }
    };
    http.send(params);

}


function fillSelectDefaultValues(selectId, fieldType, startFrom = 1) {
    const selValues = document.getElementById(selectId);
    if (!validateUserControl(selValues)) {
        return false;
    }
    selValues.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selValues.appendChild(optionStart);
    }


    var systemType = SYSTEM_USER_STATUS.systemtype;
    var http = new XMLHttpRequest();
    var params = "action=getDefaultValues&systemType=" + systemType + "&fieldType=" + fieldType;
    var url = URL_SA_CRUD_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var defaults = JSON.parse(this.responseText);
            defaults.forEach(element => createTbl(element));
            function createTbl(element) {
                let itemvalue = JSON.parse(element);
                var optValue = document.createElement("option");
                optValue.value = itemvalue['itemvalue'];
                optValue.innerHTML = itemvalue['itemvalue'];
                selValues.appendChild(optValue);
            }
            selValues.dispatchEvent(new Event('input'));
        }
    };
    http.send(params);
}


function fillSelectCustomerAdditional(selectId, startFrom = 1) {
    const selAdds = document.getElementById(selectId);
    if (!validateUserControl(selAdds)) {
        return false;
    }
    selAdds.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selAdds.appendChild(optionStart);
    }

    var companyId = SYSTEM_USER_STATUS.companyId;
    var http = new XMLHttpRequest();
    var params = "action=getCustomerAdditionalReference&companyId=" + companyId;
    var url = URL_SA_CUSTOMER_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var defaults = JSON.parse(this.responseText);
            defaults.forEach(element => createTbl(element));
            function createTbl(element) {
                let itemvalue = JSON.parse(element);
                var optValue = document.createElement("option");
                optValue.value = itemvalue['customeradditionalreference'];
                optValue.innerHTML = itemvalue['customeradditionalreference'];
                selAdds.appendChild(optValue);
            }
            selAdds.dispatchEvent(new Event('input'));
        }
    };
    http.send(params);


}


function fillSelectNeiborhood(selectId, type = 1, startFrom = 1, initialValue = - 1) {
    let selNeibor = document.getElementById(selectId);
    if (!validateUserControl(selNeibor)) {
        return false;
    }
    selNeibor.innerHTML = "";
    if (startFrom == -1) {
        let optionStart = document.createElement("option");
        optionStart.value = "";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selNeibor.appendChild(optionStart);
    }
    NEIBORHOOD_CIL_SERIE = [];
    var companyId = SYSTEM_USER_STATUS.companyId;
    var dependencyId = SYSTEM_USER_STATUS.workplaceId;

    try {
        var neiborhoods = JSON.parse(sessionStorage.getItem("fill-neiborhood-list_" + dependencyId + "_" + type));
        realFillNeiborhood(neiborhoods);
    } catch (e) {
        var http = new XMLHttpRequest();
        var params = "action=getNeiborhoodList&companyId=" + companyId + "&dependencyId=" + dependencyId + "&type=" + type;
        var url = URL_SA_CRUD_X;
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                try {
                    var neiborhoods = JSON.parse(this.responseText);
                    sessionStorage.setItem("fill-neiborhood-list_" + dependencyId + "_" + type, http.responseText);
                    realFillNeiborhood(neiborhoods);
                } catch (e) {
                    showHttpResponseText(http);
                }
            }
        };
        http.send(params);
    }

    function realFillNeiborhood(neiborhoods) {
        neiborhoods.forEach(element => createTbl(element));
        function createTbl(element) {
            let itemvalue = JSON.parse(element);
            var optValue = document.createElement("option");
            var neibor = itemvalue['neiborhood'].toUpperCase();
            NEIBORHOOD_CIL_SERIE[neibor] = itemvalue['serie'].toUpperCase();
            optValue.value = neibor;
            optValue.innerHTML = neibor;
            selNeibor.appendChild(optValue);
        }
        if (initialValue != -1) {
            selNeibor.value = initialValue;
        }
        selNeibor.dispatchEvent(new Event('input'));
}
}


function fillSelecthydrometrBrand(selectId, startFrom = 1, initialValue = - 1) {
    const selBrand = document.getElementById(selectId);
    if (!validateUserControl(selBrand)) {
        return false;
    }
    selBrand.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selBrand.appendChild(optionStart);
    }

    var FILTER = {};
    FILTER.companyId = SYSTEM_USER_STATUS.companyId;

    var http = new XMLHttpRequest();
    var params = "action=getHydrometerBrandList" + "&filterInfo=" + JSON.stringify(FILTER);
    var url = URL_SA_CRUD_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var defaults = JSON.parse(this.responseText);
            defaults.forEach(element => createTbl(element));
            function createTbl(element) {
                let itemvalue = JSON.parse(element);
                var optValue = document.createElement("option");
                optValue.value = itemvalue['brand'].toUpperCase();
                optValue.innerHTML = itemvalue['brand'].toUpperCase();
                selBrand.appendChild(optValue);
            }
            if (initialValue != -1) {
                selBrand.value = initialValue;
            }
            selBrand.dispatchEvent(new Event('input'));
        }
    };
    http.send(params);


}


function fillSelectProductChargeType(selectId, startFrom = 1) {

    const selType = document.getElementById(selectId);
    if (!validateUserControl(selType)) {
        return false;
    }
    selType.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selType.appendChild(optionStart);
    }


    for (var i = 1; i < PRODUCT_CHARGE_TYPES.length; i++) {
        let optType = document.createElement("option");
        optType.value = i;
        optType.innerHTML = PRODUCT_CHARGE_TYPES[i];
        selType.appendChild(optType);
    }
    selType.dispatchEvent(new Event('input'));
}


function fillSelectProductChargeSequence(selectId, startFrom = 1) {

    const selSeq = document.getElementById(selectId);
    if (!validateUserControl(selSeq)) {
        return false;
    }
    selSeq.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selSeq.appendChild(optionStart);
    }


    for (var i = 1; i < PRODUCT_CHARGE_SEQUENCES.length; i++) {
        let optType = document.createElement("option");
        optType.value = i;
        optType.innerHTML = PRODUCT_CHARGE_SEQUENCES[i];
        selSeq.appendChild(optType);
    }
    selSeq.dispatchEvent(new Event('input'));
}



function fillSelectLinkRequestStatus(selectId, startFrom = 1, initialValue = - 1) {

    setLinkRequestStatus();
    const selStatus = document.getElementById(selectId);
    if (!validateUserControl(selStatus)) {
        return false;
    }
    selStatus.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selStatus.appendChild(optionStart);
    }


    for (var i = 1; i < LINK_REQUEST_STATUS.length; i++) {
        let optType = document.createElement("option");
        optType.value = i;
        optType.innerHTML = LINK_REQUEST_STATUS[i];
        selStatus.appendChild(optType);
    }
    if (initialValue != -1) {
        selStatus.value = initialValue;
    }
    selStatus.dispatchEvent(new Event('input'));
}


function fillSelectWaterLinkStatus(selectId, startFrom = 1) {

    setWaterLinkStatus();
    const selStatus = document.getElementById(selectId);
    if (!validateUserControl(selStatus)) {
        return false;
    }
    selStatus.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selStatus.appendChild(optionStart);
    }


    for (var i = 0; i < WATER_LINK_STATUS.length; i++) {
        if (i in WATER_LINK_STATUS) {
            let optType = document.createElement("option");
            optType.value = i;
            optType.innerHTML = WATER_LINK_STATUS[i];
            selStatus.appendChild(optType);
        }
    }
    selStatus.dispatchEvent(new Event('input'));
}


function fillSelectNationalBank(selectId, startFrom = 1) {
    const selBank = document.getElementById(selectId);
    if (!validateUserControl(selBank)) {
        return false;
    }
    selBank.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selBank.appendChild(optionStart);
    }


    var http = new XMLHttpRequest();
    var params = "action=getNationalBank";
    var url = URL_SA_BANK_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var banks = JSON.parse(this.responseText);
            banks.forEach(element => createTbl(element));
            function createTbl(element) {
                let bank = JSON.parse(element);
                var optBank = document.createElement("option");
                optBank.value = bank['id'];
                optBank.innerHTML = bank['initials'] + " - " + bank['bankname'];
                selBank.appendChild(optBank);
            }
            selBank.dispatchEvent(new Event('input'));
        }
    };
    http.send(params);

}



function fillSelectWaterTax(selectId, startFrom = 1) {
    const selWaterTax = document.getElementById(selectId);
    if (!validateUserControl(selWaterTax)) {
        return false;
    }
    selWaterTax.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selWaterTax.appendChild(optionStart);
    }


    var http = new XMLHttpRequest();
    var params = "action=getWaterTax";
    var url = URL_SA_CRUD_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var banks = JSON.parse(this.responseText);
            banks.forEach(element => createTbl(element));
            function createTbl(element) {
                let tax = JSON.parse(element);
                var optTax = document.createElement("option");
                optTax.value = tax['taxlevel'];
                optTax.innerHTML = tax['taxdesignation'];
                selWaterTax.appendChild(optTax);
            }
            selWaterTax.dispatchEvent(new Event('input'));
        }
    };
    http.send(params);

}


function fillSelectPaymentMechanism(selectId, startFrom = 1) {
    const selPaymentMechanism = document.getElementById(selectId);
    if (!validateUserControl(selPaymentMechanism)) {
        return false;
    }
    selPaymentMechanism.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selPaymentMechanism.appendChild(optionStart);
    }
    setPaymentMechanism('');
    PAYMENT_MECHANISM.forEach(element => createSel(element));
    function createSel(element) {
        var optionPayMechanism = document.createElement("option");
        if (Number(element['id']) < 100) {
            optionPayMechanism.value = element['id'];
            optionPayMechanism.innerHTML = element['mechanism'];
            selPaymentMechanism.appendChild(optionPayMechanism);
        }
    }
    selPaymentMechanism.dispatchEvent(new Event('input'));
}



function fillSelectProductStock(selectId, startFrom = 1, initValue = - 1) {
    const selStock = document.getElementById(selectId);
    if (!validateUserControl(selStock)) {
        return false;
    }
    selStock.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selStock.appendChild(optionStart);
    }

    var companyId = SYSTEM_USER_STATUS.companyId;
    var dependecyId = (Number(SYSTEM_USER_STATUS.billingprofile) >= BILLING_PROFILE_PARTNER_INDEX) ? -1 : SYSTEM_USER_STATUS.workplaceId;
    var http = new XMLHttpRequest();
    var params = "action=getProductStock&companyId=" + companyId + "&productStockId=-1&dependecyId=" + dependecyId;
    var url = URL_SA_PRODUCT_SALE_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var stocks = JSON.parse(this.responseText);
            stocks.forEach(element => createTbl(element));
            var billingStock = -1;
            function createTbl(element) {
                let stock = JSON.parse(element);
                var designation = stock['desigination'];
                if (stock['billingstock'] == 1) {
                    billingStock = stock['id'];
                    designation += " (*)";
                }
                let optStock = document.createElement("option");
                optStock.value = stock['id'];
                optStock.innerHTML = designation;
                selStock.appendChild(optStock);
            }

            if (initValue != -1) {
                selStock.value = billingStock;
            }
            selStock.dispatchEvent(new Event('input'));
        }
    };
    http.send(params);

}



function fillSelectSystemType(selectId, startFrom = 1) {

    const selSystemType = document.getElementById(selectId);
    if (!validateUserControl(selSystemType)) {
        return false;
    }
    selSystemType.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selSystemType.appendChild(optionStart);
    }
    setSystemType('');
    for (var i = 1; i <= SYSTEM_TYPES.length; i++) {
        var optTypes = document.createElement("option");
        optTypes.value = i;
        optTypes.innerHTML = SYSTEM_TYPES[i];
        selSystemType.appendChild(optTypes);
    }
    selSystemType.dispatchEvent(new Event('input'));

}


function fillSelectTpaTerminal(selectId, accountId, startFrom = 1) {
    const selTpaTerminal = document.getElementById(selectId);
    if (!validateUserControl(selTpaTerminal)) {
        return false;
    }
    selTpaTerminal.innerHTML = "";
    if (startFrom == -1) {
        var optionStart = document.createElement("option");
        optionStart.value = "-1";
        optionStart.setAttribute('class', 'selectPlaceHolder');
        optionStart.innerHTML = "Seleccionar";
        selTpaTerminal.appendChild(optionStart);
    }

    if (PAYMENT_ALL_TPA_TERMINAL.length <= 0) {
        paymentGetTpaTerminal();
    }

    PAYMENT_ALL_TPA_TERMINAL.forEach(element => createTbl(element));
    function createTbl(element) {
        let tpaTerminal = JSON.parse(element);
        var optTerminal = document.createElement("option");
        if (tpaTerminal['accountbankid'] == accountId) {
            optTerminal.value = tpaTerminal['id'];
            optTerminal.innerHTML = tpaTerminal['designation'];
            selTpaTerminal.appendChild(optTerminal);
        }
    }
    selTpaTerminal.dispatchEvent(new Event('input'));

}



