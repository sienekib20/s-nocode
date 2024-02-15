class _billToPay {
    constructor(billId, companyid, dependencyid = 0, supplierid = - 1,
            invoicenumber = "", amount = 0, invoicedate = "2021-01-01", limitdate = "2021-01-01",
            rubricid = - 1, description = "", sourceid = 0) {
        this.billId = billId;
        this.companyid = companyid;
        this.dependencyid = dependencyid;
        this.supplierid = supplierid;
        this.invoicenumber = invoicenumber;
        this.amount = amount;
        this.invoicedate = invoicedate;
        this.limitdate = limitdate;
        this.rubricid = rubricid;
        this.description = description;
        this.sourceid = sourceid;
    }
}

class _cashOut {
    constructor(cashoutId, companyid, dependencyid = 0, carrier = "",
            cashoutdate = "2021-01-01", amount = 0, paymentmechanism = 1, bankaccountid = - 1,
            description = "", justifiedamount = 0, sourceid = 0) {
        this.cashoutId = cashoutId;
        this.companyid = companyid;
        this.dependencyid = dependencyid;
        this.carrier = carrier;
        this.cashoutdate = cashoutdate;
        this.amount = amount;
        this.paymentmechanism = paymentmechanism;
        this.bankaccountid = bankaccountid;
        this.description = description;
        this.justifiedamount = justifiedamount;
        this.sourceid = sourceid;
    }
}

class _cashOutLine {
    constructor(id = 0, companyid = 0, cashoutid = 0, rubricid = - 1,
            supplierid = - 1, invoicenumber = "", invoicedate = "2021-01-01", invoiceamount = 0,
            taxbase = 0, taxpayable = 0, deductibleamount = 0, status = 1) {
        this.id = id;
        this.companyid = companyid;
        this.cashoutid = cashoutid;
        this.rubricid = rubricid;
        this.supplierid = supplierid;
        this.invoicenumber = invoicenumber;
        this.invoicedate = invoicedate;
        this.invoiceamount = invoiceamount;
        this.taxbase = taxbase;
        this.taxpayable = taxpayable;
        this.deductibleamount = deductibleamount;
        this.status = status;
    }
}

var CASH_OUT_NEW_LINES = [];

function treasuryRubricOnLoad(a) {
    showNotification();
    function ploLoad() {
        fillSelectDependencyFull('selTreRubDependency', -1, 1);
        setAutoCompleteToFiel('txtTreRubRubricDescription', 35, 1);
        treasuryRubricList('');
        hideNotification();
    }
    checkUserStatusAfterExecute(ploLoad);
}


function treasuryRubricDependencyOnChange(a) {
    treasuryRubricList('');
}



var TREASURY_RUBRICS = [];


function treasuryRubricList(a) {
    showNotification();
    TREASURY_RUBRICS = [];
    var thPercent = document.getElementById('thTreRubHeaderPercent');
    var companyId = SYSTEM_USER_STATUS.companyId;
    var dependencyId = Number(document.getElementById('selTreRubDependency').value);
    if (dependencyId <= 0) {
        if (Number(SYSTEM_USER_STATUS.billingprofile) < BILLING_PROFILE_PARTNER_INDEX) {
            showNotification('Deve seleccionar uma filial.', 0);
            return false;
        }
        thPercent.innerHTML = "...";
    } else {
        thPercent.innerHTML = "Percentagem";
    }
    var costType = Number(document.getElementById('selTreRubCostType').value);
    if ((isNaN(costType)) || (costType <= 0)) {
        showNotification('Deve seleccionar um tipo de custo.', 0);
        return false;
    }
    var http = new XMLHttpRequest();
    var params = "action=getTreasuryRubricList&companyId=" + companyId + "&dependencyId=" + dependencyId +
            "&costType=" + costType;
    var url = URL_SA_TREASURY_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var rubrics = JSON.parse(this.responseText);
            if (rubrics.length <= 0) {
                showNotification('Não encontrado.', 0);
            }
            document.getElementById('lbResultOnList').innerHTML = rubrics.length;
            let tableRef = document.getElementById("tblTreRubMainTable");
            tableRef.innerHTML = "";

            rubrics.forEach(element => createTbl(element));
            function createTbl(element) {
                let rubric = JSON.parse(element);
                treasuryRubricAddSingleLine(rubric, dependencyId);
            }
            treasuryRubricRecalculateTotal('');
            hideNotification();

        }
    };
    http.send(params);

}


function treasuryRubricAddSingleLine(rubric, dependencyId) {
    let tableRef = document.getElementById("tblTreRubMainTable");
    var nRows = tableRef.rows.length + 1;
    let newRow = tableRef.insertRow(-1);
    newRow.id = "trTreRucRubricLine" + nRows;
    newRow.setAttribute("class", "borderGray");

    let cellRow = newRow.insertCell(-1);
    cellRow.setAttribute("class", "colRowNumber");
    cellRow.innerHTML = nRows;

    let cellCodigo = newRow.insertCell(-1);
    cellCodigo.setAttribute("class", "colProductId");
    cellCodigo.innerHTML = rubric['id'];

    let cellDescription = newRow.insertCell(-1);
    cellDescription.innerHTML = rubric['description'];

    let cellPerc = newRow.insertCell(-1);
    if (dependencyId <= 0) {
        var btEdit = document.createElement("button");
        btEdit.setAttribute("class", "labelEdit");
        btEdit.type = "button";
        btEdit.name = nRows
        btEdit.onclick = function () {
            treasuryRubricShowRubric(rubric['id'], rubric['description'], rubric['costsubtype']);
        };
        cellPerc.appendChild(btEdit);
    } else {
        let numPerc = document.createElement('input');
        numPerc.type = "number";
        numPerc.id = "numTreRubRibricPercent" + nRows;
        numPerc.min = 0;
        numPerc.value = Number(rubric['percent']).toFixed(2);
        numPerc.max = 100;
        numPerc.step = "any";
        numPerc.onchange = function () {
            treasuryRubricRecaculateLine(nRows);
        };
        cellPerc.appendChild(numPerc);
    }

    TREASURY_RUBRICS[Number(nRows - 1)] = rubric;
}

function treasuryRubricShowRubric(rubricId = - 1, description = "", costSubType = 0) {
    if (Number(SYSTEM_USER_STATUS.billingprofile) < BILLING_PROFILE_PARTNER_INDEX) {
        showNotification('Não tem permissão para executar está operação.', 0);
        return false;
    }
    const btDelete = document.getElementById('btTreRubRubricDelete');
    const txtCodigo = document.getElementById('txtTreRubRucricId');
    const txtDescription = document.getElementById('txtTreRubRubricDescription');
    btDelete.style.display = "none";

    if (rubricId == -1) {
        txtCodigo.value = "Nova rúbrica";
        txtDescription.value = "";
    } else {
        txtCodigo.value = rubricId;
        txtDescription.value = description;
        if (Number(costSubType) < 1) {
            if (checkPermission('1102')) {
                btDelete.style.display = "inline";
                btDelete.onclick = function () {
                    treasuryRubricDeleteSingle(rubricId);
                };
            }
        }
    }

    showParts('divTreRubRubricDetail');

}



function treasuryRubricRecaculateLine(nLine) {
    try {
        var numPerc = document.getElementById("numTreRubRibricPercent" + nLine);
        var perc = numPerc.value;
        if (isNaN(perc)) {
            perc = 0;
        }
        if (perc < 0) {
            perc = 0;
        }
        if (perc > 100) {
            perc = 100;
        }
        TREASURY_RUBRICS[Number(nLine) - 1].percent = perc;
        numPerc.value = perc;

        treasuryRubricRecalculateTotal('');

    } catch (ex) {
        showNotification(ex.message, 0);
    }
}


function treasuryRubricRecalculateTotal(a) {
    var tPercentagem = 0;
    TREASURY_RUBRICS.forEach(element => check(element));
    function check(element) {
        tPercentagem += Number(element.percent);
    }
    var lbTotal = document.getElementById('lbTreRubTotalPercentage');
    lbTotal.innerHTML = formatNumber(tPercentagem);
    if (tPercentagem != 100) {
        lbTotal.style.color = "red";
        showNotification("O total das percentagem de ser igual a 100.", 0);
        return false;
    } else {
        lbTotal.style.color = "black";
        return true;
    }
}


function treasuryRubricSaveSingle(a) {

    var mandatoryField = false;
    var msg = "";
    var companyId = SYSTEM_USER_STATUS.companyId;
    var rubricId = document.getElementById('txtTreRubRucricId').value;
    var description = reviewString(document.getElementById('txtTreRubRubricDescription')).toUpperCase();
    var costType = Number(document.getElementById('selTreRubCostType').value);

    if (description == "") {
        mandatoryField = true;
        msg += "Deve a descrição da rúbrica. <br>";
    }
    if (mandatoryField) {
        alert(msg);
        return false;
    }

    showNotification();
    var http = new XMLHttpRequest();
    var params = "action=saveTreasuryRubricSingle&companyId=" + companyId +
            "&rubricId=" + rubricId + "&description=" + description + "&costType=" + costType;
    var url = URL_SA_TREASURY_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(http.responseText);
            if (response['status'] == 1) {
                showNotification(response['msg'], 1);
                treasuryRubricList('');
                hideParts('divTreRubRubricDetail');
            } else {
                showNotification(response['msg'], 0);
            }
        }
    };
    http.send(params);


}


function treasuryRubricDeleteSingle(rubricId) {

    showNotification();
    var http = new XMLHttpRequest();
    var params = "action=deleteTreasuryRubricSingle&rubricId=" + rubricId;
    var url = URL_SA_TREASURY_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(http.responseText);
            if (response['status'] == 1) {
                showNotification(response['msg'], 1);
                treasuryRubricList('');
                hideParts('divTreRubRubricDetail');
            } else {
                showNotification(response['msg'], 0);
            }
        }
    };
    http.send(params);


}


function treasuryRubricSave(a) {
    try {

        var dependencyId = Number(document.getElementById('selTreRubDependency').value);
        if (dependencyId <= 0) {
            showNotification("Deve indicar uma filial.", 0);
            return false;
        }

        if (treasuryRubricRecalculateTotal('') == true) {
            var url = '&dependencyId=' + dependencyId + '&rubrics={';
            var n = 0;
            TREASURY_RUBRICS.forEach(element => parseLine(element));
            function parseLine(element) {
                if (n != 0) {
                    url += ",";
                }
                url += '"' + n + '":' + JSON.stringify(element);
                n += 1;
            }
            url += "}";

            showNotification();
            var http = new XMLHttpRequest();
            var params = "action=saveTreasuryRubrics" + url;
            url = URL_SA_TREASURY_X;
            http.open('POST', url, true);
            http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            http.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    var response = JSON.parse(http.responseText);
                    if (response['status'] == 1) {
                        showNotification(response['msg'], 1);
                        treasuryRubricList('');
                    } else {
                        showNotification(response['msg'], 0);
                    }
                }
            };
            http.send(params);

        }

    } catch (ex) {
        showNotification(ex.message, 0);
    }
}




function treasuryCashInOnLoad(a) {
    showNotification();
    function tcioLoad() {
        fillSelectDependencyFull('selTreCasInDependency', -1, 1);
        setTriggerButtonByText('txtTreCasInInitialDate', 'btTreCasInViewAll');
        setTriggerButtonByText('txtTreCasInEndDate', 'btTreCasInViewAll');

        hideNotification();
    }
    checkUserStatusAfterExecute(tcioLoad);
}



function treasuryBillToPayOnLoad(a) {
    showNotification();
    function ploLoad() {
        fillSelectDependencyFull('selTreBillTPDependency', -1, 1);
        fillSelectSupplier('selTreBillTPSupplier', -1);
        fillSelectSupplier('selTreBillTPDetSupplier', -1);
        fillSelectRubric('selTreBillTPDetRubric', -1);
        setAutoCompleteToFiel('txtTreBillTPDetDescription', 36, 1);
        setTriggerButtonByText('txtTreBillTPInitialDate', 'btTreBillTPViewAll');
        setTriggerButtonByText('txtTreBillTPInitialDate', 'btTreBillTPViewAll');
        treasuryBillToPayList();

        hideNotification();
    }
    checkUserStatusAfterExecute(ploLoad);
}



function treasuryBillToPayNewSupplier() {
    supplierGetInfo(-1);
    showParts('divTreBillTPSupplierRegister');
}


function treasuryBillToPaySetNewEdit(billId = - 1) {
    const btDelete = document.getElementById('btTreBillTPDetDelete');
    const txtCodigo = document.getElementById('txtTreBillTPDetBillId');
    const selSupplier = document.getElementById('selTreBillTPDetSupplier');
    const txtInvoiceNumber = document.getElementById('txtTreBillTPDetInvoiceNumber');
    const txtAmount = document.getElementById('txtTreBillTPDetAmount');
    const txtInvoiceDate = document.getElementById('txtTreBillTPDetInvoiceDate');
    const txtLimitDate = document.getElementById('txtTreBillTPDetLimitDate');
    const selRubric = document.getElementById('selTreBillTPDetRubric');
    const txtDescription = document.getElementById('txtTreBillTPDetDescription');
    btDelete.style.display = "none";

    if (billId == -1) {
        txtCodigo.value = "Novo activo";
        selSupplier.value = "-1";
        txtInvoiceNumber.value = "";
        txtAmount.value = 0;
        txtInvoiceDate.value = setLocalDate(new Date(), 10);
        txtLimitDate.value = setLocalDate(new Date(), 10);
        selRubric.value = -1;
        txtDescription.value = "";

        dispatchOnInputChange();
    } else {
        var companyId = SYSTEM_USER_STATUS.companyId;
        showNotification();
        var http = new XMLHttpRequest();
        var params = "action=getTreasuryBillToPay&companyId=" + companyId + "&dependencyId=-1" +
                "&billId=" + billId + "&initialDate=-1" + "&endDate=-1" + "&supplierId=-1";
        var url = URL_SA_TREASURY_X;
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var bills = JSON.parse(this.responseText);
                bills.forEach(element => createTbl(element));
                function createTbl(element) {
                    let bill = JSON.parse(element);
                    txtCodigo.value = bill['id'];
                    selSupplier.value = bill['supplierid'];
                    txtInvoiceNumber.value = bill['invoicenumber'];
                    txtAmount.value = Number(bill['amount']);
                    txtInvoiceDate.value = setLocalDate(bill['invoicedate'], 10);
                    txtLimitDate.value = setLocalDate(bill['limitdate'], 10);
                    selRubric.value = bill['rubricid'];
                    txtDescription.value = bill['description'];
                    if (checkPermission('1105')) {
                        btDelete.style.display = "inline";
                        btDelete.onclick = function () {
                            treasuryBillToPayDelete(bill['id']);
                        };
                    }

                    dispatchOnInputChange();
                }
                hideNotification();
            }
        };
        http.send(params);
    }

    showParts('divTreBillTPBillDetails');
}



function treasuryBillToPaySave(a) {

    var mandatoryField = false;
    var msg = "";
    var BILL_NEW = new _billToPay;
    BILL_NEW.billId = document.getElementById('txtTreBillTPDetBillId').value;
    BILL_NEW.companyid = SYSTEM_USER_STATUS.companyId;
    BILL_NEW.dependencyid = SYSTEM_USER_STATUS.workplaceId;
    BILL_NEW.supplierid = Number(document.getElementById('selTreBillTPDetSupplier').value);
    BILL_NEW.invoicenumber = document.getElementById('txtTreBillTPDetInvoiceNumber').value;
    BILL_NEW.amount = Number(document.getElementById('txtTreBillTPDetAmount').value);
    BILL_NEW.invoicedate = document.getElementById('txtTreBillTPDetInvoiceDate').value;
    BILL_NEW.limitdate = document.getElementById('txtTreBillTPDetLimitDate').value;
    BILL_NEW.rubricid = document.getElementById('selTreBillTPDetRubric').value;
    BILL_NEW.description = reviewString(document.getElementById('txtTreBillTPDetDescription')).toUpperCase();
    BILL_NEW.sourceid = SYSTEM_USER_STATUS.userId;


    if (BILL_NEW.supplierid <= 0) {
        mandatoryField = true;
        msg += "Deve indicar o fornecedor. <br>";
    }
    if (BILL_NEW.invoicenumber == "") {
        mandatoryField = true;
        msg += "Deve indicar a factura. <br>";
    }
    if (BILL_NEW.amount <= 0) {
        mandatoryField = true;
        msg += "O valor da factura deve ser superior a zero. <br>";
    }
    if (ST_SA_WAITING_SERVER == 1) {
        mandatoryField = true;
        msg += "Aguardando resposta do servidor. <br>";
    }
    if (mandatoryField) {
        alert(msg);
        return false;
    }

    showNotification();
    ST_SA_WAITING_SERVER = 1;
    var http = new XMLHttpRequest();
    var params = "action=saveTreasuryBillToPay&billInfo=" + JSON.stringify(BILL_NEW);
    var url = URL_SA_TREASURY_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            ST_SA_WAITING_SERVER = 0;
            var response = JSON.parse(http.responseText);
            if (response['status'] == 1) {
                showNotification(response['msg'], 1);
                treasuryBillToPayList();
                hideParts('divTreBillTPBillDetails');
            } else {
                showNotification(response['msg'], 0);
            }
        }
    };
    http.send(params);
}




function treasuryBillToPayList() {

    var companyId = SYSTEM_USER_STATUS.companyId;
    var dependencyId = Number(document.getElementById('selTreBillTPDependency').value);
    if (dependencyId <= 0) {
        if (Number(SYSTEM_USER_STATUS.billingprofile) < BILLING_PROFILE_PARTNER_INDEX) {
            showNotification('Seleccionar uma filial.', 0);
            return false;
        }
    }
    var initialDate = document.getElementById('txtTreBillTPInitialDate').value;
    var endDate = document.getElementById('txtTreBillTPEndDate').value;
    var supplierId = document.getElementById('selTreBillTPSupplier').value;

    showNotification();
    var http = new XMLHttpRequest();
    var params = "action=getTreasuryBillToPay&companyId=" + companyId + "&dependencyId=" + dependencyId +
            "&billId=-1" + "&initialDate=" + initialDate + "&endDate=" + endDate + "&supplierId=" + supplierId;
    var url = URL_SA_TREASURY_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var bills = JSON.parse(this.responseText);
            if (bills.length <= 0) {
                showNotification('Não encontrado.', 0);
            } else {
                hideDivFilter();
            }
            document.getElementById('lbResultOnList').innerHTML = bills.length;
            let tableRef = document.getElementById("tblTreBillTPMainTable");
            tableRef.innerHTML = "";
            var nRows = 0;
            var tDebt = 0;
            bills.forEach(element => createTbl(element));
            function createTbl(element) {
                nRows += 1;
                let bill = JSON.parse(element);
                let newRow = tableRef.insertRow(-1);
                newRow.id = "trTreasuryBillToPay" + bill['id'];
                newRow.setAttribute("class", "borderGray" + setSelectedRowBackground("SELECTED_ROW_TREASURY_BILL_TO_PAY", newRow.id));

                let cellRow = newRow.insertCell(-1);
                cellRow.setAttribute("class", "colRowNumber");
                cellRow.innerHTML = nRows;

                let cellSupp = newRow.insertCell(-1);
                cellSupp.style.minWidth = "200px";
                cellSupp.innerHTML = bill['supplierName'] + " (" + bill['supplierid'] + ")";

                let cellDocNum = newRow.insertCell(-1);
                cellDocNum.style.whiteSpace = "nowrap";
                cellDocNum.innerHTML = bill['invoicenumber'];

                let cellAmount = newRow.insertCell(-1);
                cellAmount.setAttribute("class", "colAmount");
                cellAmount.innerHTML = formatNumber(bill['amount']);

                let cellDebt = newRow.insertCell(-1);
                cellDebt.setAttribute("class", "colAmount");
                var debt = Number(bill['debt']);
                if (debt < 0) {
                    debt = 0;
                }
                tDebt += debt;
                cellDebt.innerHTML = formatNumber(debt);

                let cellPrev = newRow.insertCell(-1);
                cellPrev.style.minWidth = "70px";
                cellPrev.setAttribute("class", "colProductId");
                cellPrev.innerHTML = setLocalDate(bill['limitdate'], 10);

                var status = bill['status'];
                var color = "";
                var showDetails = false;
                if (status == "N") {
                    var days = Number(bill['delayDays']);
                    status = "Faltam ";
                    if (days < 0) {
                        color = "rgb(255,143,143)";
                        status = "Atrasado " + Math.abs(days) + " dias";
                    } else if (days == 0) {
                        color = "rgb(143,143,255)";
                        status = ("HOJE").bold();
                    } else if (days <= 3) {
                        color = "rgb(255,222,117)";
                        status += days + " dias";
                    } else {
                        status += days + " dias";
                    }
                    showDetails = true;
                } else {
                    status = "Pago";
                }

                let cellDelay = newRow.insertCell(-1);
                cellDelay.setAttribute("class", "colProductId");
                cellDelay.innerHTML = getDocumentStatus(status);
                cellDelay.style.backgroundColor = color;

                let cellLastUpdt = newRow.insertCell(-1);
                cellLastUpdt.setAttribute("class", "colProductId");
                cellLastUpdt.innerHTML = setLocalDate(bill['statusdate'], 16);

                let cellOperator = newRow.insertCell(-1);
                cellOperator.setAttribute("class", "colProductId");
                cellOperator.innerHTML = getFirtLastName(bill['operatorName']) + " (" + bill['sourceidstatus'] + ")";

                let cellBill = newRow.insertCell(-1);
                cellBill.setAttribute("class", "noPrint");
                let btBill = document.createElement("button");
                btBill.innerHTML = "Pagar";
                btBill.type = "button";
                btBill.setAttribute("class", "labelSale");
                btBill.onclick = function () {
                    setSelectedRowOnClick(newRow, "SELECTED_ROW_TREASURY_BILL_TO_PAY");
                    /* sessionStorage.setItem('invoiceWorkingDocumentInvoiceNumber', invoiceNumber);
                     sessionStorage.setItem('invoiceWorkingDocumentPrintNumber', printNumber);
                     sessionStorage.setItem('invoiceWorkingDocumentCustomerId', customerId);
                     window.location.assign('SaleInvoice.php?invoicetype=1');*/
                };
                if (showDetails) {
                    cellBill.appendChild(btBill);
                }
                cellBill.style.textAlign = "center";

                let btEdit = document.createElement("button");
                btEdit.type = "button";
                btEdit.name = bill['id'];
                btEdit.setAttribute("class", "labelEdit");
                btEdit.onclick = function () {
                    treasuryBillToPaySetNewEdit(btEdit.name);
                    setSelectedRowOnClick(newRow, "SELECTED_ROW_TREASURY_BILL_TO_PAY");
                };
                if (showDetails) {
                    cellBill.appendChild(btEdit);
                }

            }


            let newRow = tableRef.insertRow(-1);
            newRow.setAttribute("class", "borderGray");
            newRow.style.fontSize = "large";

            let cellTotal = newRow.insertCell(-1);
            cellTotal.setAttribute("colSpan", "4");
            cellTotal.style.textAlign = "center";
            cellTotal.innerHTML = "TOTAL A PAGAR";

            let cellTDebt = newRow.insertCell(-1);
            cellTDebt.setAttribute("class", "colAmount");
            cellTDebt.innerHTML = formatNumber(tDebt).bold();


            hideNotification();
        }
    };
    http.send(params);
}




function treasuryBillToPayDelete(billId) {

    showNotification();
    var http = new XMLHttpRequest();
    var params = "action=deleteTreasuryBillToPay&billId=" + billId;
    var url = URL_SA_TREASURY_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(http.responseText);
            if (response['status'] == 1) {
                showNotification(response['msg'], 1);
                treasuryBillToPayList();
                hideParts('divTreBillTPBillDetails');
            } else {
                showNotification(response['msg'], 0);
            }
        }
    };
    http.send(params);
}




function treasuryCashOutOnLoad(a) {
    showNotification();
    function ploLoad() {
        fillSelectDependencyFull('selTreCasOutDependency', -1, 1);
        fillSelectPaymentMechanism('selTreCasOutDetMechanism');
        fillSelectAccountBank('selTreCasOutDetBankAccount', -1);
        setAutoCompleteToFiel('txtTreCasOutDetDescription', 37, 1);
        setTriggerButtonByText('txtTreCasOutInitialDate', 'btTreCasOutViewAll');
        setTriggerButtonByText('txtTreCasOutEndDate', 'btTreCasOutViewAll');
        treasuryCashOutList();

        hideNotification();
    }
    checkUserStatusAfterExecute(ploLoad);
}


function treasuryCashOutSetNewEdit(cashoutId = - 1) {
    const btDelete = document.getElementById('btTreCasOutDetDelete');
    const txtCodigo = document.getElementById('txtTreCasOutDetCashOutId');
    const txtCarrier = document.getElementById('txtTreCasOutDetCarrier');
    const txtEmissionDate = document.getElementById('txtTreCasOutDetEmissionDate');
    const txtAmount = document.getElementById('txtTreCasOutDetAmount');
    const selMechanism = document.getElementById('selTreCasOutDetMechanism');
    const selBankAccount = document.getElementById('selTreCasOutDetBankAccount');
    const txtDescription = document.getElementById('txtTreCasOutDetDescription');
    const lbMissingAmount = document.getElementById('lbTreCasOutMissingAmount');

    btDelete.style.display = "none";
    lbMissingAmount.innerHTML = "";
    cleanElement('tblTreCasOutDetDetail');
    CASH_OUT_NEW_LINES = [];

    if (cashoutId == -1) {
        txtCodigo.value = "Nova saída";
        txtCarrier.value = "";
        txtEmissionDate.value = setLocalDate(new Date(), 10);
        txtAmount.value = 0;
        selMechanism.value = 1;
        selBankAccount.value = -1;
        txtDescription.value = "";
        treasuryCashOutAddLineEmpty();

        dispatchOnInputChange();
    } else {
        var companyId = SYSTEM_USER_STATUS.companyId;
        showNotification();
        var http = new XMLHttpRequest();
        var params = "action=getTreasuryCashOut&companyId=" + companyId + "&dependencyId=-1" +
                "&cashoutId=" + cashoutId + "&initialDate=-1" + "&endDate=-1";
        var url = URL_SA_TREASURY_X;
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                var cashouts = JSON.parse(this.responseText);
                cashouts.forEach(element => createTbl(element));
                function createTbl(element) {
                    let cashout = JSON.parse(element);
                    txtCodigo.value = cashout['id'];
                    txtCarrier.value = cashout['carrier'];
                    txtEmissionDate.value = setLocalDate(cashout['cashoutdate'], 10);
                    txtAmount.value = Number(cashout['amount']).toFixed(2);
                    selMechanism.value = cashout['paymentmechanism'];
                    selBankAccount.value = cashout['bankaccountid'];
                    txtDescription.value = cashout['description'];
                    treasuryCashOutLines(cashout['id']);
                    if (checkPermission('1107')) {
                        btDelete.style.display = "inline";
                        btDelete.onclick = function () {
                            treasuryCashOutDelete(cashout['id']);
                        };
                    }

                    dispatchOnInputChange();
                }

                hideNotification();
            }
        };
        http.send(params);
    }

    showParts('divTreCasOutDetails');
}


function treasuryCashOutAddLineEmpty() {
    var cashLine = new _cashOutLine();
    cashLine.invoicedate = setLocalDate(new Date(), 10);
    treasuryCashOutAddLine(cashLine);
}


function treasuryCashOutAddLine(cashoutLine) {

    let tableRef = document.getElementById('tblTreCasOutDetDetail');
    var nRows = tableRef.rows.length;
    CASH_OUT_NEW_LINES[nRows] = cashoutLine;

    nRows += 1;
    let newRow = tableRef.insertRow(-1);
    newRow.setAttribute("class", "borderGray");
    newRow.setAttribute("id", "trTreCasOutLine" + nRows);

    let cellRow = newRow.insertCell(-1);
    cellRow.setAttribute("class", "colRowNumber");
    cellRow.innerHTML = nRows;

    let cellRubric = newRow.insertCell(-1);
    cellRubric.style.minWidth = "110px";
    let selRubric = document.createElement('select');
    cellRubric.appendChild(selRubric);
    selRubric.id = "selTreCasOutDetRubricLine" + nRows;
    fillSelectRubric(selRubric.id, -1, cashoutLine['rubricid']);
    selRubric.onchange = function () {
        treasuryCashOutRecalculateLite(this, 'rubricid', nRows);
    };

    let cellSupplier = newRow.insertCell(-1);
    cellSupplier.style.minWidth = "110px";
    let selSupplier = document.createElement('select');
    cellSupplier.appendChild(selSupplier);
    selSupplier.id = "selTreCasOutDetSupplierLine" + nRows;
    fillSelectSupplier(selSupplier.id, -1, cashoutLine['supplierid']);
    selSupplier.onchange = function () {
        treasuryCashOutRecalculateLite(this, 'supplierid', nRows);
    };

    let cellIvNumber = newRow.insertCell(-1);
    cellIvNumber.style.minWidth = "70px";
    let txtIvNumber = document.createElement('input');
    cellIvNumber.appendChild(txtIvNumber);
    txtIvNumber.type = "text";
    txtIvNumber.value = cashoutLine['invoicenumber'];
    txtIvNumber.onchange = function () {
        treasuryCashOutRecalculateLite(this, 'invoicenumber', nRows);
    };

    let cellIvDate = newRow.insertCell(-1);
    let txtIvDate = document.createElement('input');
    txtIvDate.style.width = "120px";
    cellIvDate.appendChild(txtIvDate);
    txtIvDate.type = "date";
    txtIvDate.value = setLocalDate(cashoutLine['invoicedate'], 10);
    txtIvDate.onchange = function () {
        treasuryCashOutRecalculateLite(this, 'invoicedate', nRows);
    };

    let cellAmount = newRow.insertCell(-1);
    cellAmount.style.minWidth = "80px";
    let txtAmount = document.createElement('input');
    cellAmount.appendChild(txtAmount);
    txtAmount.type = "number";
    txtAmount.min = 0;
    txtAmount.step = "any";
    txtAmount.value = Number(cashoutLine['invoiceamount']).toFixed(2);
    txtAmount.onchange = function () {
        treasuryCashOutRecalculateLite(this, 'invoiceamount', nRows);
    };

    let cellTaxBase = newRow.insertCell(-1);
    cellTaxBase.style.minWidth = "80px";
    let txtTaxBase = document.createElement('input');
    cellTaxBase.appendChild(txtTaxBase);
    txtTaxBase.id = "numTreCasOutTaxBaseLine" + nRows;
    txtTaxBase.type = "number";
    txtTaxBase.min = 0;
    txtTaxBase.step = "any";
    txtTaxBase.value = Number(cashoutLine['taxbase']).toFixed(2);
    txtTaxBase.onchange = function () {
        treasuryCashOutRecalculateLite(this, 'taxbase', nRows);
    };

    let cellTaxPay = newRow.insertCell(-1);
    cellTaxPay.style.minWidth = "80px";
    let txtTaxPay = document.createElement('input');
    cellTaxPay.appendChild(txtTaxPay);
    txtTaxPay.id = "numTreCasOutTaxPayLine" + nRows;
    txtTaxPay.type = "number";
    txtTaxPay.min = 0;
    txtTaxPay.step = "any";
    txtTaxPay.value = Number(cashoutLine['taxpayable']).toFixed(2);
    txtTaxPay.onchange = function () {
        treasuryCashOutRecalculateLite(this, 'taxpayable', nRows);
    };

    let cellDeduct = newRow.insertCell(-1);
    cellDeduct.setAttribute("id", "tdTreCasOutDeductLine" + nRows);
    cellDeduct.setAttribute("class", "colAmount");
    cellDeduct.innerHTML = formatNumber(cashoutLine['deductibleamount']);

    let cellRemove = newRow.insertCell(-1);
    let btRemove = document.createElement('button');
    cellRemove.appendChild(btRemove);
    btRemove.setAttribute("class", "labelRemove");
    btRemove.type = "button";
    btRemove.onclick = function () {
        treasuryCashOutRemoveLine(nRows);
    };

    treasuryCashOutRecalculateTotal('');
}


function treasuryCashOutRecalculateTotal(a) {
    var total = 0;
    CASH_OUT_NEW_LINES.forEach(element => check(element));
    function check(element) {
        var line = (_cashOutLine);
        line = element;
        if (line.status == 1) {
            total += Number(line.invoiceamount);
        }
    }
    var cashOutAmount = Number(document.getElementById('txtTreCasOutDetAmount').value);
    var dif = cashOutAmount - total;
    var lbTotal = document.getElementById('lbTreCasOutMissingAmount');
    if (dif > 0) {
        lbTotal.innerHTML = "Detalhe inferior ao valor global (" + formatNumber(dif) + " AOA).";
    } else if (dif < 0) {
        lbTotal.innerHTML = "Detalhe superior ao valor global (" + formatNumber(dif) + " AOA).";
    } else {
        lbTotal.innerHTML = "";
    }

}


function treasuryCashOutRecalculateLite(sender, field, nLine) {
    var newValue = sender.value;
    if (sender.type == "number") {
        if ((Number(newValue) < 0) || (isNaN(newValue))) {
            newValue = 0;
            sender.value = 0;
        }
    }

    var cashLine = (_cashOutLine);
    cashLine = CASH_OUT_NEW_LINES[Number(nLine) - 1];
    cashLine[field] = newValue;
    if (Number(cashLine.taxbase) > Number(cashLine.invoiceamount)) {
        showNotification("O valor tributável não deve ser superior ao valor da factura.", 0);
        cashLine.taxbase = cashLine.invoiceamount;
    }
    if (Number(cashLine.taxpayable) > (Number(cashLine.taxbase) * 0.14)) {
        showNotification("O valor do IVA suportado não deve ser superior ao permetido por lei.", 0);
        cashLine.taxpayable = (Number(cashLine.taxbase) * 0.14);
    }
    var percDeduct = 0;
    if (SYSTEM_USER_STATUS.taxGroup == 2) {
        percDeduct = 0.07;
    } else if (SYSTEM_USER_STATUS.taxGroup == 3) {
        percDeduct = 0.14;
    }
    cashLine.deductibleamount = Number(cashLine.taxpayable) * percDeduct;

    document.getElementById("numTreCasOutTaxBaseLine" + nLine).value = Number(cashLine.taxbase).toFixed(2);
    document.getElementById("numTreCasOutTaxPayLine" + nLine).value = Number(cashLine.taxpayable).toFixed(2);
    document.getElementById("tdTreCasOutDeductLine" + nLine).innerHTML = formatNumber(cashLine.deductibleamount);
    treasuryCashOutRecalculateTotal('');
}



function treasuryCashOutNewSupplier() {
    supplierGetInfo(-1);
    showParts('divTreCasOutSupplierRegister');
}


function treasuryCashOutRemoveLine(nLine) {
    document.getElementById("trTreCasOutLine" + nLine).innerHTML = "";
    CASH_OUT_NEW_LINES[Number(nLine) - 1].status = 0;
    treasuryCashOutRecalculateTotal('');
}




function treasuryCashOutSave(a) {

    var mandatoryField = false;
    var msg = "";
    var CASH_OUT_NEW = new _cashOut();
    CASH_OUT_NEW.cashoutId = document.getElementById('txtTreCasOutDetCashOutId').value;
    CASH_OUT_NEW.companyid = SYSTEM_USER_STATUS.companyId;
    CASH_OUT_NEW.dependencyid = SYSTEM_USER_STATUS.workplaceId;
    CASH_OUT_NEW.carrier = reviewString(document.getElementById('txtTreCasOutDetCarrier')).toUpperCase();
    CASH_OUT_NEW.cashoutdate = document.getElementById('txtTreCasOutDetEmissionDate').value;
    CASH_OUT_NEW.amount = Number(document.getElementById('txtTreCasOutDetAmount').value);
    CASH_OUT_NEW.paymentmechanism = Number(document.getElementById('selTreCasOutDetMechanism').value);
    CASH_OUT_NEW.bankaccountid = Number(document.getElementById('selTreCasOutDetBankAccount').value);
    CASH_OUT_NEW.description = reviewString(document.getElementById('txtTreCasOutDetDescription')).toUpperCase();
    CASH_OUT_NEW.sourceid = SYSTEM_USER_STATUS.userId;

    if (CASH_OUT_NEW.amount <= 0) {
        mandatoryField = true;
        msg += "Deve o valor global deve ser superior a zero. <br>";
    }
    if (CASH_OUT_NEW.paymentmechanism <= 1) {
        CASH_OUT_NEW.bankaccountid = -1;
    } else {
        if (CASH_OUT_NEW.bankaccountid <= 0) {
            mandatoryField = true;
            msg += "Deve a conta bancária de origem. <br>";
        }
    }

    if (mandatoryField) {
        alert(msg);
        return false;
    }
    /* LINES */
    var url = '&&cashoutlines={';
    var n = 0;
    CASH_OUT_NEW_LINES.forEach(element => parseLine(element));
    function parseLine(element) {
        if (n != 0) {
            url += ",";
        }
        url += '"' + n + '":' + JSON.stringify(element);
        n += 1;
    }
    url += "}";

    showNotification();
    var http = new XMLHttpRequest();
    var params = "action=saveTreasuryCashOut&cashOutInfo=" + JSON.stringify(CASH_OUT_NEW) + url;
    var url = URL_SA_TREASURY_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(http.responseText);
            if (response['status'] == 1) {
                showNotification(response['msg'], 1);
                treasuryCashOutList();
                hideParts('divTreCasOutDetails');
            } else {
                showNotification(response['msg'], 0);
            }
        }
    };
    http.send(params);
}




function treasuryCashOutList() {

    var companyId = SYSTEM_USER_STATUS.companyId;
    var dependencyId = Number(document.getElementById('selTreCasOutDependency').value);
    if (dependencyId <= 0) {
        if (Number(SYSTEM_USER_STATUS.billingprofile) < BILLING_PROFILE_PARTNER_INDEX) {
            showNotification('Seleccionar uma filial.', 0);
            return false;
        }
    }
    var initialDate = document.getElementById('txtTreCasOutInitialDate').value;
    var endDate = document.getElementById('txtTreCasOutEndDate').value;

    showNotification();
    var http = new XMLHttpRequest();
    var params = "action=getTreasuryCashOut&companyId=" + companyId + "&dependencyId=" + dependencyId +
            "&cashoutId=-1" + "&initialDate=" + initialDate + "&endDate=" + endDate;
    var url = URL_SA_TREASURY_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var cashouts = JSON.parse(this.responseText);
            if (cashouts.length <= 0) {
                showNotification('Não encontrado.', 0);
            } else {
                hideDivFilter();
            }
            document.getElementById('lbResultOnList').innerHTML = cashouts.length;
            let tableRef = document.getElementById("tblTreCasOutMainTable");
            tableRef.innerHTML = "";
            var nRows = 0;
            var total = 0;
            cashouts.forEach(element => createTbl(element));
            function createTbl(element) {
                nRows += 1;
                let cashout = JSON.parse(element);
                var amount = Number(cashout['amount']);
                total += amount;

                let newRow = tableRef.insertRow(-1);
                newRow.id = "trTreasuryCashOutLet" + cashout['id'];
                newRow.setAttribute("class", "borderGray" + setSelectedRowBackground("SELECTED_ROW_TREASURY_CASHOUT", newRow.id));

                let cellRow = newRow.insertCell(-1);
                cellRow.setAttribute("class", "colRowNumber");
                cellRow.innerHTML = nRows;

                let cellCode = newRow.insertCell(-1);
                cellCode.setAttribute("class", "colProductId");
                cellCode.innerHTML = cashout['id'];

                let cellCarrier = newRow.insertCell(-1);
                cellCarrier.style.minWidth = "200px";
                cellCarrier.innerHTML = cashout['carrier'];

                let cellDate = newRow.insertCell(-1);
                cellDate.style.minWidth = "70px";
                cellDate.setAttribute("class", "colProductId");
                cellDate.innerHTML = setLocalDate(cashout['cashoutdate'], 10);

                let cellAmount = newRow.insertCell(-1);
                cellAmount.setAttribute("class", "colAmount");
                cellAmount.innerHTML = formatNumber(amount);

                let cellMecha = newRow.insertCell(-1);
                cellMecha.innerHTML = cashout['mechanism'];

                let cellBank = newRow.insertCell(-1);
                cellBank.innerHTML = cashout['bankaccount'];

                let cellLastUpdt = newRow.insertCell(-1);
                cellLastUpdt.setAttribute("class", "colProductId");
                cellLastUpdt.innerHTML = setLocalDate(cashout['statusdate'], 16);

                let cellOperator = newRow.insertCell(-1);
                cellOperator.setAttribute("class", "colProductId");
                cellOperator.innerHTML = getFirtLastName(cashout['operatorName']) + " (" + cashout['sourceidstatus'] + ")";

                let cellEdit = newRow.insertCell(-1);
                cellEdit.setAttribute("class", "noPrint");
                let btEdit = document.createElement("button");
                btEdit.type = "button";
                btEdit.name = cashout['id'];
                btEdit.setAttribute("class", "labelDetail");
                btEdit.onclick = function () {
                    treasuryCashOutSetNewEdit(btEdit.name);
                    setSelectedRowOnClick(newRow, "SELECTED_ROW_TREASURY_CASHOUT");
                };
                cellEdit.appendChild(btEdit);
                cellEdit.style.textAlign = "center";

            }


            let newRow = tableRef.insertRow(-1);
            newRow.setAttribute("class", "borderGray");
            newRow.style.fontSize = "large";

            let cellTotal = newRow.insertCell(-1);
            cellTotal.setAttribute("colSpan", "4");
            cellTotal.style.textAlign = "center";
            cellTotal.innerHTML = "TOTAL";

            let cellTDebt = newRow.insertCell(-1);
            cellTDebt.setAttribute("class", "colAmount");
            cellTDebt.innerHTML = formatNumber(total).bold();

            hideNotification();
        }
    };
    http.send(params);
}



function treasuryCashOutLines(cashoutId) {

    showNotification();
    var http = new XMLHttpRequest();
    var params = "action=getTreasuryCashOutLines" + "&cashoutId=" + cashoutId;
    var url = URL_SA_TREASURY_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var cashoutlines = JSON.parse(this.responseText);
            cashoutlines.forEach(element => createTbl(element));
            function createTbl(element) {
                let cashLine = (_cashOutLine);
                cashLine = JSON.parse(element);
                treasuryCashOutAddLine(cashLine);
            }
        }
    };
    http.send(params);
}



function treasuryCashOutDelete(cashoutId) {

    showNotification();
    var http = new XMLHttpRequest();
    var params = "action=deleteTreasuryCashOut&cashoutId=" + cashoutId;
    var url = URL_SA_TREASURY_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(http.responseText);
            if (response['status'] == 1) {
                showNotification(response['msg'], 1);
                treasuryCashOutList();
                hideParts('divTreCasOutDetails');
            } else {
                showNotification(response['msg'], 0);
            }
        }
    };
    http.send(params);
}



function treasuryCashFlowOnLoad(a) {
    showNotification();
    function ploLoad() {
        fillSelectDependencyFull('selTreCasFloDependency', -1, 1);
        setTriggerButtonByText('txtTreCasFloInitialDate', 'btTreCasFloViewAll');
        setTriggerButtonByText('txtTreCasFloInitialDate', 'btTreCasFloViewAll');
        treasuryCashFlowList();

        hideNotification();
    }
    checkUserStatusAfterExecute(ploLoad);
}



function treasuryCashFlowList() {

    var companyId = SYSTEM_USER_STATUS.companyId;
    var dependencyId = Number(document.getElementById('selTreCasFloDependency').value);
    if (dependencyId <= 0) {
        if (Number(SYSTEM_USER_STATUS.billingprofile) < BILLING_PROFILE_PARTNER_INDEX) {
            showNotification('Seleccionar uma filial.', 0);
            return false;
        }
    }
    var initialDate = document.getElementById('txtTreCasFloInitialDate').value;
    var endDate = document.getElementById('txtTreCasFloEndDate').value;

    showNotification();
    var http = new XMLHttpRequest();
    var params = "action=getTreasuryCashFlow&companyId=" + companyId + "&dependencyId=" + dependencyId +
            "&initialDate=" + initialDate + "&endDate=" + endDate;
    var url = URL_SA_TREASURY_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var cashflows = JSON.parse(this.responseText);
            if (cashflows.length <= 0) {
                showNotification('Não encontrado.', 0);
            } else {
                hideDivFilter();
            }
            document.getElementById('lbResultOnList').innerHTML = cashflows.length;
            let tableRef = document.getElementById("tblTreCasFloMainTable");
            tableRef.innerHTML = "";
            var nRows = 0;
            /* var tReceipts = 0;*/
            var tBalance = 0;
            var subt = 0;
            var oldForOder = 0;
            var flowType = "";
            cashflows.forEach(element => createTbl(element));
            function createTbl(element) {
                nRows += 1;
                let cashflow = JSON.parse(element);
                var forOrder = Number(cashflow['forOrder']);
                var amount = Number(cashflow['amount']);
                if (forOrder <= 1) {
                    tBalance += amount;
                } else {
                    tBalance -= amount;
                }
                if (oldForOder != forOrder) {
                    if (oldForOder > 0) {
                        let newRow = tableRef.insertRow(-1);
                        newRow.setAttribute("class", "borderGray");
                        newRow.style.fontSize = "large";

                        let cellTotal = newRow.insertCell(-1);
                        cellTotal.setAttribute("colSpan", "2");
                        cellTotal.style.textAlign = "center";
                        cellTotal.innerHTML = "Sub-total";

                        let cellTDebt = newRow.insertCell(-1);
                        cellTDebt.setAttribute("class", "colAmount");
                        cellTDebt.innerHTML = formatNumber(subt).bold();
                    }
                    subt = amount;
                    var title = "1. RECEBIMENTOS DE CLIENTES (+)";
                    if (forOrder == 4) {
                        title = "2. PAGAMENTOS (-)";
                    } else if (forOrder == 5) {
                        title = "3. SALÁRIOS E ENCARGOS (-)";
                    }
                    if (forOrder == 6) {
                        title = "4. IMPOSTOS (-)";
                    }
                    if (forOrder == 7) {
                        title = "5. FUNDOS (-)";
                    }
                    let newRow = tableRef.insertRow(-1);
                    newRow.setAttribute("class", "borderGray");
                    newRow.style.fontSize = "large";

                    let cellTotal = newRow.insertCell(-1);
                    cellTotal.setAttribute("colSpan", "3");
                    cellTotal.innerHTML = (title).bold();

                    oldForOder = forOrder;
                } else {
                    subt += amount;
                }

                let newRow = tableRef.insertRow(-1);
                newRow.setAttribute("class", "borderGray");

                let cellRow = newRow.insertCell(-1);
                cellRow.setAttribute("class", "colRowNumber");
                cellRow.innerHTML = nRows;

                let cellCarrier = newRow.insertCell(-1);
                cellCarrier.style.minWidth = "200px";
                cellCarrier.innerHTML = cashflow['rubric'];

                let cellAmount = newRow.insertCell(-1);
                cellAmount.setAttribute("class", "colAmount");
                cellAmount.innerHTML = formatNumber(amount);

            }

            let newRow = tableRef.insertRow(-1);
            newRow.setAttribute("class", "borderGray");
            newRow.style.fontSize = "large";

            let cellTotal = newRow.insertCell(-1);
            cellTotal.setAttribute("colSpan", "2");
            cellTotal.style.textAlign = "center";
            cellTotal.innerHTML = "Sub-total";

            let cellSubT = newRow.insertCell(-1);
            cellSubT.setAttribute("class", "colAmount");
            cellSubT.innerHTML = formatNumber(subt).bold();

            let newBalance = tableRef.insertRow(-1);
            newBalance.setAttribute("class", "borderGray");
            newBalance.style.fontSize = "large";

            let cellBal = newBalance.insertCell(-1);
            cellBal.setAttribute("colSpan", "2");
            cellBal.style.textAlign = "center";
            cellBal.innerHTML = ("BALANÇO (1-2-3-4-5)").bold();

            let cellTBal = newBalance.insertCell(-1);
            cellTBal.setAttribute("class", "colAmount");
            cellTBal.innerHTML = formatNumber(tBalance).bold();

            hideNotification();
        }
    };
    http.send(params);
}



function treasuryBillToReceiveOnLoad() {
    showNotification();
    function ploLoad() {
        fillSelectDependencyFull('selTreBilRecDependency', -1, 1);
        fillSelectConsumerTypeAqua("selTreBilRecConsumerType", -1);
        setTriggerButtonByText('txtTreCasFloInitialDate', 'btTreBillRecViewAll');
        setTriggerButtonByText('txtTreCasFloInitialDate', 'btTreBillRecViewAll');
        treasuryBillToReceiveDetails();

        hideNotification();
    }
    checkUserStatusAfterExecute(ploLoad);
}




function treasuryBillToReceiveDetails() {
    try {
        if (ST_SA_LISTING_SERVER == 1) {
            showNotification("Procurando...", 1);
            return false;
        }
        var FILTER = {};
        FILTER.companyId = SYSTEM_USER_STATUS.companyId;
        FILTER.dependencyId = Number(document.getElementById('selTreBilRecDependency').value);
        if (FILTER.dependencyId <= 0) {
            if (Number(SYSTEM_USER_STATUS.billingprofile) < BILLING_PROFILE_PARTNER_INDEX) {
                showNotification('Seleccionar uma filial.', 0);
                return false;
            }
        }
        FILTER.cunsumerType = getElementValue("selTreBilRecConsumerType");
        FILTER.initialDate = document.getElementById('txtTreBilRecInitialDate').value;
        FILTER.endDate = document.getElementById('txtTreBilRecEndDate').value;
        FILTER.onlynumber = 1;
        FILTER.startIdx = 0;
        FILTER.endIdx = 0;

        showNotification();
        ST_SA_LISTING_SERVER = 1;
        var http = new XMLHttpRequest();
        var params = "action=getTreasuryBillToReceiveDetails" + "&filterInfo=" + JSON.stringify(FILTER);
        var url = URL_SA_TREASURY_X;
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function () {
            if (http.readyState == 4 && http.status == 200) {
                ST_SA_LISTING_SERVER = 0;
                try {
                    var customers = JSON.parse(this.responseText);
                } catch (e) {
                    showHttpResponseText(http);
                }

                var totalItems = Number(customers['n']);
                if (totalItems <= 0) {
                    showNotification('Não encontrado.', 0);
                } else {
                    hideDivFilter();
                }

                var numPagNumber = document.getElementById("numResultPageNumber");
                var numItems = document.getElementById("numResultItems");

                numPagNumber.value = 1;
                setResultTotalPages(numPagNumber.id, numItems.id, totalItems);
                numPagNumber.onchange = function () {
                    result();
                };
                numItems.onchange = function () {
                    setResultTotalPages(numPagNumber.id, numItems.id, totalItems);
                    result();
                };

                setElementInnerHtml('lbResultOnList', totalItems);

                function result() {
                    var pageNumber = Number(numPagNumber.value);
                    var items = Number(numItems.value);
                    var startIdx = (pageNumber - 1) * items;
                    var endIdx = startIdx + items;
                    if (endIdx > totalItems) {
                        endIdx = totalItems;
                    }

                    FILTER.totalItems = totalItems;
                    FILTER.startIdx = startIdx;
                    FILTER.endIdx = endIdx;
                    FILTER.onlynumber = 0;

                    treasuryBillToReceiveFilter(FILTER);
                }
                result();
            }
        };
        http.send(params);
    } catch (ex) {
        showNotification(ex.message, 0);
    }
}



function treasuryBillToReceiveFilter(FILTER) {
    try {
        showNotification();
        var tableRef = document.getElementById("tblTreBilRecDetailsTable");
        tableRef.innerHTML = "";

        var http = new XMLHttpRequest();
        var params = "action=getTreasuryBillToReceiveDetails" + "&filterInfo=" + JSON.stringify(FILTER);
        var url = URL_SA_TREASURY_X;
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function () {
            if (http.readyState == 4 && http.status == 200) {
                ST_SA_LISTING_SERVER = 0;
                var customers = JSON.parse(this.responseText);

                setElementInnerHtml('lbResultOnList', FILTER.endIdx + " / " + FILTER.totalItems);
                var nRows = FILTER.startIdx;
                var total = 0;
                var consumerTypes = {};
                for (var i = 0; i < customers.length; i++) {
                    var element = customers[i];
                    let customer = JSON.parse(element);
                    var ctype = Number(customer['watertaxlevel']);
                    if (ctype > 0) {
                        ctype = CONSUMER_TYPE_AQUA[ctype];
                    } else {
                        ctype = "";
                    }
                    total += Number(customer['totalamount']);
                    nRows += 1;

                    addAmountResume(consumerTypes, ctype, customer['totalamount']);

                    let newRow = tableRef.insertRow(-1);
                    newRow.setAttribute("class", "borderGray");

                    let cellRow = newRow.insertCell(-1);
                    cellRow.setAttribute("class", "colRowNumber");
                    cellRow.innerHTML = nRows;

                    let cellId = newRow.insertCell(-1);
                    cellId.setAttribute("class", "colProductId");
                    cellId.innerHTML = customer['customerid'];

                    let cellCustomer = newRow.insertCell(-1);
                    cellCustomer.innerHTML = customer['companyname'];

                    let cellTaxId = newRow.insertCell(-1);
                    cellTaxId.innerHTML = customer['customertaxid'];

                    let cellConsumer = newRow.insertCell(-1);
                    cellConsumer.innerHTML = ctype;

                    let cellTAmount = newRow.insertCell(-1);
                    cellTAmount.setAttribute("class", "colAmount");
                    cellTAmount.innerHTML = formatNumber(customer['totalamount']);
                }
                // TOTAL
                let newRow = tableRef.insertRow(-1);
                newRow.setAttribute("class", "borderGray");
                newRow.style.fontSize = "large";

                let cellRow = newRow.insertCell(-1);
                cellRow.setAttribute("colspan", "5");
                cellRow.style.textAlign = "center";
                cellRow.innerHTML = "TOTAL".bold();

                let cellTotal = newRow.insertCell(1);
                cellTotal.setAttribute("class", "colAmount");
                cellTotal.innerHTML = formatNumber(total).bold();
                hideNotification();

                //RESUMO AND CHARTS
                var tblResume = document.getElementById("tblTreBillRecResumeTable");
                tblResume.innerHTML = "";
                nRows = 0;
                for (var element in consumerTypes) {
                    nRows += 1;
                    resume(tblResume, nRows, "Tipo de consumidor", consumerTypes, element, "Dívida (AOA)");
                }
            }
        };
        http.send(params);
    } catch (ex) {
        showNotification(ex.message, 0);
    }
}


function treasuryBillToReceiveConsumptionOnLoad() {
    try {
        showNotification();
        function lrloLoad() {
            setDocumentType('');
            setPaymentMechanism('');
            setLinkRequestStatus();
            setElementValue("numTreBilRecConsYear", new Date().getFullYear());
            fillSelectDependencyFull('selTreBilRecConsDependency', -1, 1);
            fillSelectMonth('selTreBilRecConsMonth', 1, new Date().getMonth() + 1);
            fillSelectConsumerTypeAqua("selTreBilRecConsConsumerType", -1);
            fillSelectMunicipality("selTreBilRecConsMunicipality", SYSTEM_USER_STATUS.provinceId, -1, -1);
            fillSelectNeiborhood("selTreBilRecConsNeiborhood", 3, -1);
            setTriggerButtonByText('txtTreBilRecConsInitialDate', 'btTreBilRecConsViewAll');
            setTriggerButtonByText('txtTreBilRecConsEndDate', 'btTreBilRecConsViewAll');

            hideNotification();
        }

        checkUserStatusAfterExecute(lrloLoad);
    } catch (ex) {
        showNotification(ex.message, 0);
    }
}




function treasuryBillToReceiveConsumptionDetails() {
    try {
        if (ST_SA_LISTING_SERVER == 1) {
            showNotification("Procurando...", 1);
            return false;
        }
        var FILTER = {};
        FILTER.companyId = SYSTEM_USER_STATUS.companyId;
        FILTER.dependencyId = getElementValue('selTreBilRecConsDependency');
        if (FILTER.dependencyId <= 0) {
            if (Number(SYSTEM_USER_STATUS.billingprofile) < BILLING_PROFILE_PARTNER_INDEX) {
                showNotification('Seleccionar uma filial.', 0);
                return false;
            }
        }

        var year = Number(getElementValue("numTreBilRecConsYear"));
        var month = Number(getElementValue("selTreBilRecConsMonth")) - 1;
        FILTER.initialDate = setLocalDate(new Date(year, month, 1), 10);
        FILTER.endDate = setLocalDate(new Date(year, month, daysInMonth(month, year)), 10);
        FILTER.consumertype = getElementValue('selTreBilRecConsConsumerType');
        FILTER.municipalityId = getElementValue('selTreBilRecConsMunicipality');
        FILTER.neiborhood = getElementValue('selTreBilRecConsNeiborhood');
        FILTER.fullReport = 0;

        var tableRef = document.getElementById("tblTreBilRecConsListTable");
        tableRef.innerHTML = "";

        showNotification();
        ST_SA_LISTING_SERVER = 1;
        var http = new XMLHttpRequest();
        var params = "action=getSaleConsumptionReportDetails" + "&filterInfo=" + JSON.stringify(FILTER);
        var url = URL_SA_SALE_REPORT_X;
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function () {
            if (http.readyState == 4 && http.status == 200) {
                ST_SA_LISTING_SERVER = 0;
                try {
                    var invoices = JSON.parse(this.responseText);
                } catch (e) {
                    showHttpResponseText(http);
                    return false;
                }
                if (invoices.length <= 0) {
                    showNotification('Não encontrado.', 0);
                } else {
                    hideDivFilter();
                }

                var nRow = 0;
                var tNInvoice = 0;
                var tConsuption = 0;
                var tAmount = 0;
                var tPayAmount = 0;
                var tInFault = 0;
                for (var i = 0; i < invoices.length; i++) {
                    var element = invoices[i];
                    let invoice = JSON.parse(element);
                    nRow += 1;
                    tNInvoice += Number(invoice['nInvoices']);
                    tConsuption += Number(invoice['consumptionamount']);
                    var amount = Number(invoice['totalInvoice']);
                    tAmount += amount;
                    var payAmount = Number(invoice['paymentamount']);
                    if (payAmount > amount) {
                        payAmount = amount;
                    }
                    tPayAmount += payAmount;
                    var inFault = (amount - payAmount);
                    if (inFault < 0) {
                        inFault = 0;
                    }
                    tInFault += inFault;

                    let newRow = tableRef.insertRow(-1);
                    newRow.setAttribute("class", "borderGray");

                    let cellRow = newRow.insertCell(-1);
                    cellRow.setAttribute("class", "colRowNumber");
                    cellRow.innerHTML = nRow;

                    let cellConsuRecord = newRow.insertCell(-1);
                    cellConsuRecord.innerHTML = dateYearMonthExtension(invoice['billingstartdate']).toUpperCase();

                    let cellNInv = newRow.insertCell(-1);
                    cellNInv.setAttribute("class", "colAmount");
                    cellNInv.innerHTML = invoice['nInvoices'];

                    let cellInFault = newRow.insertCell(-1);
                    cellInFault.setAttribute("class", "colAmount");
                    cellInFault.innerHTML = formatNumber(inFault);

                }

                let newRow = tableRef.insertRow(-1);
                newRow.setAttribute("class", "borderGray");
                newRow.style.fontSize = "large";

                let cellRow = newRow.insertCell(-1);
                cellRow.setAttribute("colspan", "2");
                cellRow.style.textAlign = "center";
                cellRow.innerHTML = "TOTAL".bold();

                let cellNInv = newRow.insertCell(-1);
                cellNInv.setAttribute("class", "colAmount");
                cellNInv.innerHTML = tNInvoice;

                let cellInFault = newRow.insertCell(-1);
                cellInFault.setAttribute("class", "colAmount");
                cellInFault.innerHTML = formatNumber(tInFault);

                hideNotification();
            }
        };
        http.send(params);
    } catch (ex) {
        showNotification(ex.message, 0);
    }
}



function treasuryBillToReceiveServiceOnLoad() {
    showNotification();
    function lrloLoad() {
        setDocumentType('');
        setPaymentMechanism('');
        setLinkRequestStatus();
        setElementValue("numTreBilRecServYear", new Date().getFullYear());
        fillSelectDependencyFull('selTreBilRecServDependency', -1, 1);
        fillSelectMonth('selTreBilRecServMonth', 1, new Date().getMonth() + 1);
        fillSelectConsumerTypeAqua("selTreBilRecServConsumerType", -1);
        fillSelectMunicipality("selTreBilRecServMunicipality", SYSTEM_USER_STATUS.provinceId, -1, -1);
        fillSelectNeiborhood("selTreBilRecServNeiborhood", 3, -1);
        setTriggerButtonByText('txtTreBilRecServInitialDate', 'btTreBilRecServViewAll');
        setTriggerButtonByText('txtTreBilRecServEndDate', 'btTreBilRecServViewAll');

        hideNotification();
    }

    checkUserStatusAfterExecute(lrloLoad);
}




function treasuryBillToReceiveServicesDetails() {
    if (ST_SA_LISTING_SERVER == 1) {
        showNotification("Procurando...", 1);
        return false;
    }
    var FILTER = {};
    FILTER.companyId = SYSTEM_USER_STATUS.companyId;
    FILTER.dependencyId = getElementValue('selTreBilRecServDependency');
    if (FILTER.dependencyId <= 0) {
        if (Number(SYSTEM_USER_STATUS.billingprofile) < BILLING_PROFILE_PARTNER_INDEX) {
            showNotification('Seleccionar uma filial.', 0);
            return false;
        }
    }

    var year = Number(getElementValue("numTreBilRecServYear"));
    var month = Number(getElementValue("selTreBilRecServMonth")) - 1;
    FILTER.initialDate = setLocalDate(new Date(year, month, 1), 10);
    FILTER.endDate = setLocalDate(new Date(year, month, daysInMonth(month, year)), 10);
    FILTER.consumertype = getElementValue('selTreBilRecServConsumerType');
    FILTER.municipalityId = getElementValue('selTreBilRecServMunicipality');
    FILTER.neiborhood = getElementValue('selTreBilRecServNeiborhood');

    var tableRef = document.getElementById("tblTreBilRecServListTable");
    tableRef.innerHTML = "";

    showNotification();
    ST_SA_LISTING_SERVER = 1;
    var http = new XMLHttpRequest();
    var params = "action=getTreasuryBillToReceiveService" + "&filterInfo=" + JSON.stringify(FILTER);
    var url = URL_SA_TREASURY_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
        if (http.readyState == 4 && http.status == 200) {
            ST_SA_LISTING_SERVER = 0;
            try {
                var invoices = JSON.parse(this.responseText);
            } catch (e) {
                showHttpResponseText(http);
                return false;
            }
            if (invoices.length <= 0) {
                showNotification('Não encontrado.', 0);
            } else {
                hideDivFilter();
            }

            var nRow = 0;
            var tNInvoice = 0;
            var tQuant = 0;
            var tInFault = 0;
            for (var i = 0; i < invoices.length; i++) {
                var element = invoices[i];
                let invoice = JSON.parse(element);
                nRow += 1;
                tNInvoice += Number(invoice['nInvoices']);
                var quant = Number(invoice['quantity']);
                tQuant += quant;
                var inFault = Number(invoice['subtotalLine']);
                if (inFault < 0) {
                    inFault = 0;
                }
                tInFault += inFault;

                let newRow = tableRef.insertRow(-1);
                newRow.setAttribute("class", "borderGray");

                let cellRow = newRow.insertCell(-1);
                cellRow.setAttribute("class", "colRowNumber");
                cellRow.innerHTML = nRow;

                let cellProduct = newRow.insertCell(-1);
                cellProduct.innerHTML = invoice['productdescription'];

                let cellQuant = newRow.insertCell(-1);
                cellQuant.setAttribute("class", "colAmount");
                cellQuant.innerHTML = formatNumber(quant);

                let cellNInv = newRow.insertCell(-1);
                cellNInv.setAttribute("class", "colAmount");
                cellNInv.innerHTML = invoice['nInvoices'];

                let cellInFault = newRow.insertCell(-1);
                cellInFault.setAttribute("class", "colAmount");
                cellInFault.innerHTML = formatNumber(inFault);

            }

            let newRow = tableRef.insertRow(-1);
            newRow.setAttribute("class", "borderGray");
            newRow.style.fontSize = "large";

            let cellRow = newRow.insertCell(-1);
            cellRow.setAttribute("colspan", "2");
            cellRow.style.textAlign = "center";
            cellRow.innerHTML = "TOTAL".bold();

            let cellQuant = newRow.insertCell(-1);
            cellQuant.setAttribute("class", "colAmount");
            cellQuant.innerHTML = formatNumber(tQuant);

            let cellNInv = newRow.insertCell(-1);
            cellNInv.setAttribute("class", "colAmount");
            cellNInv.innerHTML = tNInvoice;

            let cellInFault = newRow.insertCell(-1);
            cellInFault.setAttribute("class", "colAmount");
            cellInFault.innerHTML = formatNumber(tInFault);

            hideNotification();
        }
    };
    http.send(params);
}


function treasuryTaxReportOnLoad() {
    showNotification();

    function srproLoad() {
        setDocumentRequestSource();
        fillSelectDependencyFull('selTreTaxRepDependency', -1, 1);
        fillSelectProductSection('selTreTaxRepProdSection', -1);
        fillSelectProductCategory('selTreTaxRepProdCategory', -1);
        fillSelectProductFamilyBrandModel('selTreTaxRepProdFamily', 'productfamily', -1);
        fillSelectProductFamilyBrandModel('selTreTaxRepProdBrand', 'productbrand', -1);
        var today = new Date();
        setElementValue("txtTreTaxRepProdInitialDate", setLocalDate(new Date(today.getFullYear(), today.getMonth(), 1), 10));
        setElementValue("txtTreTaxRepProdEndDate", setLocalDate(today, 10));
        setTriggerButtonByText('txtTreTaxRepProdInitialDate', 'btVDRelProdViewAll');
        setTriggerButtonByText('txtTreTaxRepProdEndDate', 'btVDRelProdViewAll');
        setSessionResultSearch('txtTreTaxRepISPercentage');
        hideNotification();
    }
    checkUserStatusAfterExecute(srproLoad);
}

function treasuryTaxReportDetails() {
    var FILTER = {};
    FILTER.companyId = SYSTEM_USER_STATUS.companyId;
    FILTER.dependencyId = getElementValue('selTreTaxRepDependency');
    if (FILTER.dependencyId <= 0) {
        if (Number(SYSTEM_USER_STATUS.billingprofile) < BILLING_PROFILE_PARTNER_INDEX) {
            showNotification('Seleccionar uma filial.', 0);
            return false;
        }
    }
    FILTER.is = 0;
    FILTER.initialDate = document.getElementById('txtTreTaxRepProdInitialDate').value;
    FILTER.endDate = document.getElementById('txtTreTaxRepProdEndDate').value;
    FILTER.sectionId = document.getElementById('selTreTaxRepProdSection').value;
    FILTER.category = document.getElementById('selTreTaxRepProdCategory').value;
    FILTER.family = document.getElementById('selTreTaxRepProdFamily').value;
    FILTER.brand = document.getElementById('selTreTaxRepProdBrand').value;

    if (ST_SA_WAITING_SERVER == 1) {
        showNotification("Aguardando resposta do servidor. <br>", 0);
        return false;
    }

    let tableRef = document.getElementById("tblTreTaxRepProdDetailsTable");
    tableRef.innerHTML = "";

    showNotification();
    ST_SA_WAITING_SERVER = 1;
    var http = new XMLHttpRequest();
    var params = "action=getTreasuryTaxReportDetails" + "&filterInfo=" + JSON.stringify(FILTER);
    var url = URL_SA_TREASURY_X;
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {//Call a function when the state changes.
        if (http.readyState == 4 && http.status == 200) {
            ST_SA_WAITING_SERVER = 0;
            var products = JSON.parse(this.responseText);
            if (products.length <= 0) {
                showNotification('Não encontrado.', 0);
            } else {
                hideDivFilter();
            }

            var totalItems = products.length;
            var numPagNumber = document.getElementById("numResultPageNumber");
            var numItems = document.getElementById("numResultItems");

            numPagNumber.value = 1;
            setResultTotalPages(numPagNumber.id, numItems.id, totalItems);
            numPagNumber.onchange = function () {
                result();
            };
            numItems.onchange = function () {
                setResultTotalPages(numPagNumber.id, numItems.id, totalItems);
                result();
            };

            function result() {
                var pageNumber = Number(numPagNumber.value);
                var items = Number(numItems.value);
                var startIdx = (pageNumber - 1) * items;
                var endIdx = startIdx + items;
                if (endIdx > totalItems) {
                    endIdx = totalItems;
                }

                var ivas = {};
                var iss = {};
                setElementInnerHtml('lbResultOnList', endIdx + " / " + totalItems);

                var nRows = startIdx;
                var tIVA = 0;
                var tTax = 0;
                var total = 0;
                for (var i = startIdx; i < endIdx; i++) {
                    var element = products[i];
                    let payment = JSON.parse(element);
                    var strIva = "IVA (" + payment['taxPercentage'] + "%)";
                    var ivaPerc = Number(payment['taxPercentage']);
                    var ivaAmount = 0;
                    if (ivaPerc > 0) {
                        var sto = Number(payment['subtotalLine']);
                        ivaAmount = sto - round2Dec(sto / (1 + (ivaPerc / 100)));
                    }
                    var strIS = "IS (" + FILTER.is + "%)";
                    tIVA += ivaAmount;
                    total += Number(payment['subtotalLine']);

                    addAmountResume(ivas, strIva, ivaAmount);

                    nRows += 1;
                    let newRow = tableRef.insertRow(-1);
                    newRow.setAttribute("class", "borderGray");

                    let cellRow = createTableCell(newRow, nRows, "colRowNumber", "#");

                    let cellInvNumber = createTableCell(newRow, payment['invoicenumber'], "text-no-wrap", "Venda");

                    let cellProduct = createTableCell(newRow, payment['productDescription'], "", "Produto / Serviço");

                    let cellPvp = createTableCell(newRow, formatNumber(payment['subtotalLine']).bold(), "colAmount", "PVP (AOA)");

                    let cellIvaPerc = createTableCell(newRow, formatNumber(ivaPerc), "text-align-center", "IVA (%)");

                    let cellIva = createTableCell(newRow, formatNumber(ivaAmount), "colAmount", "IVA (AOA)");

                }
                // TOTAL
                let newRow = tableRef.insertRow(-1);
                newRow.setAttribute("class", "borderGray");
                newRow.style.fontSize = "large";

                createTableCell(newRow, "", "");
                createTableCell(newRow, "TOTAL".bold(), "text-align-center");
                createTableCell(newRow, "", "");

                let cellTPvp = createTableCell(newRow, formatNumber(total).bold(), "colAmount", "PVP (AOA)");

                let cellTCom = newRow.insertCell(-1);
                cellTCom.setAttribute("class", "colAmount");

                let cellTIva = createTableCell(newRow, formatNumber(tIVA).bold(), "colAmount", "IVA (AOA)");


                //RESUMO
                var tblResume = document.getElementById("tblTreTaxRepProdResumeTable");
                tblResume.innerHTML = "";
                nRows = 0;
                for (var element in ivas) {
                    nRows += 1;
                    resume(tblResume, nRows, "IVA", ivas, element);
                }
                hideNotification();
            }
            result();
        }
    };
    http.send(params);
}
