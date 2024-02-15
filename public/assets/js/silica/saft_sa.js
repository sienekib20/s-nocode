

function saftOnLoad() {
    try {
        showNotification();
        function lrloLoad() {
            fillSelectDependencyFull('selSaftDependency', -1, 1);
            hideNotification();
        }

        checkUserStatusAfterExecute(lrloLoad);
    } catch (ex) {
        showNotification(ex.message, 0);
    }
}


function saftProcessSourceDocuments() {

    if (ST_SA_LISTING_SERVER == 1) {
        showNotification("Processando...", 1);
        return false;
    }
    var FILTER = {};
    FILTER.companyId = SYSTEM_USER_STATUS.companyId;
    FILTER.dependencyId = getElementValue('selSaftDependency');
    if (FILTER.dependencyId <= 0) {
        if (Number(SYSTEM_USER_STATUS.billingprofile) < BILLING_PROFILE_PARTNER_INDEX) {
            showNotification('Seleccionar uma filial.', 0);
            return false;
        }
    }
    FILTER.initialDate = getElementValue('txtSaftInitialDate');
    FILTER.endDate = getElementValue('txtSaftEndDate');

    showNotification();

    var divButtons = document.getElementById("divSaftFinishButtons");
    divButtons.innerHTML = "";

    var SAFT_NS = document.createElement("div");
    SAFT_NS.innerHTML = '<?xml version="1.0" encoding="utf-8"?>';

    var fileVersion = "1.01_01";
    var fileSaftType = "F";

    var START_TIME = setLocalDate(new Date(), 19);
    var BILLING_PROVINCE = 1;
    var TOTAL_CUSTOMERS = -1;
    var CURRENT_CUSTOMER = 0;
    var TOTAL_PRODUCT = -1;
    var CURRENT_PRODUCT = 0;

    var INVOICE_LIST = [];
    var TOTAL_INVOICES = -1;
    var CURRENT_INVOICE = 0;
    var ATTEMPTS_INVOICE = 0;
    var CURRENT_INVOICE_NUMBER = "";
    var INVOICE_ERROR = false;

    var WORKING_DOCS_LIST = [];
    var TOTAL_WORKING_DOCS = -1;
    var CURRENT_WORKING_DOC = 0;
    var CURRENT_WORKING_DOC_NUMBER = "";
    var WORKING_DOCS_ERROR = false;
    var ATTEMPTS_WORKING_DOC = 0;

    var PAYMENTS_LIST = [];
    var TOTAL_PAYMENTS = -1;
    var CURRENT_PAYMENT = 0;
    var CURRENT_PAYMENT_NUMBER = "";
    var PAYMENT_ERROR = false;
    var ATTEMPTS_PAYMENT = 0;

    var STOP_PROCESS = false;

    downLoadXML();

    var AUDIT_FILE = document.createElementNS(SAFT_NS, "AuditFile");
    AUDIT_FILE.setAttribute("xmlns", 'urn:OECD:StandardAuditFile-Tax:AO_' + fileVersion);
    SAFT_NS.appendChild(AUDIT_FILE);

    var HEADER = createSaftElement(AUDIT_FILE, "Header");
    var MASTER_FILE = createSaftElement(AUDIT_FILE, "MasterFiles");
    var SOURCE_DOCUMENTS = createSaftElement(AUDIT_FILE, "SourceDocuments");
    //Header
    saftHeaderCompose();


    function saftHeaderCompose() {
        ST_SA_LISTING_SERVER = 1;
        var http = new XMLHttpRequest();
        var params = "action=getSaftCompanyInfo" + "&filterInfo=" + JSON.stringify(FILTER);
        var url = URL_SA_SAFT_X;
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function () {
            if (http.readyState == 4 && http.status == 200) {
                try {
                    var response = JSON.parse(this.responseText);
                } catch (e) {
                    showHttpResponseText(http);
                }

                var companyInfo = response;
                BILLING_PROVINCE = companyInfo['billingprovince'];
                createSaftElement(HEADER, "AuditFileVersion", fileVersion);
                createSaftElement(HEADER, "CompanyID", companyInfo['companyid']);
                createSaftElement(HEADER, "TaxRegistrationNumber", companyInfo['companytaxid']);
                createSaftElement(HEADER, "TaxAccountingBasis", fileSaftType);
                createSaftElement(HEADER, "CompanyName", companyInfo['companyname']);
                createSaftElement(HEADER, "BusinessName", companyInfo['businessname']);

                var companyAddress = createSaftElement(HEADER, "CompanyAddress");
                if (companyInfo['billingbuildingnumber'] != "") {
                    createSaftElement(companyAddress, "BuildingNumber", companyInfo['billingbuildingnumber'].toString().slice(0, 7));
                }
                if (companyInfo['billingstreetname'] != "") {
                    createSaftElement(companyAddress, "StreetName", companyInfo['billingstreetname']);
                }
                var coFullAddress = fullAddress("Angola", companyInfo['provinceName'], companyInfo['municipalityName'], "",
                        companyInfo['billingdistrict'], companyInfo['billingcomuna'], companyInfo['billingneiborhood'],
                        companyInfo['billingstreetname'], companyInfo['billingbuildingnumber'], companyInfo['billingpostalcode']);
                if (coFullAddress != "") {
                    createSaftElement(companyAddress, "AddressDetail", coFullAddress);
                }

                if (companyInfo['municipalityName'] != "") {
                    createSaftElement(companyAddress, "City", companyInfo['municipalityName']);
                }
                if (companyInfo['billingpostalcode'] != "") {
                    createSaftElement(companyAddress, "PostalCode", companyInfo['billingpostalcode']);
                }
                if (companyInfo['provinceName'] != "") {
                    createSaftElement(companyAddress, "Province", companyInfo['provinceName']);
                }
                createSaftElement(companyAddress, "Country", "AO");

                createSaftElement(HEADER, "FiscalYear", new Date(FILTER.initialDate).getFullYear());
                createSaftElement(HEADER, "StartDate", setLocalDate(FILTER.initialDate, 10));
                createSaftElement(HEADER, "EndDate", setLocalDate(FILTER.endDate, 10));
                createSaftElement(HEADER, "CurrencyCode", "AOA");
                createSaftElement(HEADER, "DateCreated", setLocalDate(new Date(), 10));
                createSaftElement(HEADER, "TaxEntity", "Global");
                createSaftElement(HEADER, "ProductCompanyTaxID", "5417362956");
                createSaftElement(HEADER, "SoftwareValidationNumber", "271/AGT/2020");
                createSaftElement(HEADER, "ProductID", "GME Sílica/GOMES MUCANZA-EMPREENDIMENTOS ,LDA");
                createSaftElement(HEADER, "ProductVersion", "2.1");
                if (companyInfo['billingtelephone1'] != "") {
                    createSaftElement(HEADER, "Telephone", companyInfo['billingtelephone1']);
                }
                if (companyInfo['billingemail'] != "") {
                    createSaftElement(HEADER, "Email", companyInfo['billingemail']);
                }
                if (companyInfo['billingwebsite'] != "") {
                    createSaftElement(HEADER, "Website", companyInfo['billingwebsite']);
                }

                saftMarterFileCustomerCompose();

            }
        };
        http.send(params);

    }

    function saftMarterFileCustomerCompose() {

        ST_SA_LISTING_SERVER = 1;
        var http = new XMLHttpRequest();
        var params = "action=getSaftCustomerList" + "&filterInfo=" + JSON.stringify(FILTER);
        var url = URL_SA_SAFT_X;
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function () {
            if (http.readyState == 4 && http.status == 200) {
                try {
                    var customers = JSON.parse(this.responseText);
                } catch (e) {
                    showHttpResponseText(http);
                }
                var n = customers.length;
                TOTAL_CUSTOMERS = n;
                report();
                if (n > 0) {
                    for (var i = 0; i < n; i++) {
                        var arCustomer = customers[i];
                        var CUSTOMER = createSaftElement(MASTER_FILE, "Customer");
                        //   CUSTOMER.setAttribute("numero", Number(1+i).toFixed());
                        createSaftElement(CUSTOMER, "CustomerID", arCustomer['customerid']);
                        var accountid = arCustomer['accountid'];
                        if (accountid == "") {
                            accountid = "Desconhecido";
                        }
                        createSaftElement(CUSTOMER, "AccountID", accountid);
                        createSaftElement(CUSTOMER, "CustomerTaxID", taxIdForSaft(arCustomer['customertaxid']));
                        createSaftElement(CUSTOMER, "CompanyName", arCustomer['companyname']);

                        var BILL_ADDRESS = createSaftElement(CUSTOMER, "BillingAddress");
                        if (arCustomer['billingbuildingnumber'] != "") {
                            createSaftElement(BILL_ADDRESS, "BuildingNumber", arCustomer['billingbuildingnumber'].toString().slice(0, 7));
                        }
                        if (arCustomer['billingstreetname'] != "") {
                            createSaftElement(BILL_ADDRESS, "StreetName", arCustomer['billingstreetname']);
                        }
                        var custFullAddress = fullAddress(arCustomer['billingCountry'], arCustomer['billingProvince'], arCustomer['billingMunicipality'], "",
                                arCustomer['billingdistrict'], arCustomer['billingcomuna'], arCustomer['billingneiborhood'],
                                arCustomer['billingstreetname'], arCustomer['billingbuildingnumber'], arCustomer['billingpostalcode']);
                        if (custFullAddress != "") {
                            createSaftElement(BILL_ADDRESS, "AddressDetail", custFullAddress);
                        }
                        if (arCustomer['billingMunicipality'] != "") {
                            createSaftElement(BILL_ADDRESS, "City", arCustomer['billingMunicipality']);
                        }
                        if (arCustomer['billingpostalcode'] != "") {
                            createSaftElement(BILL_ADDRESS, "PostalCode", arCustomer['billingpostalcode']);
                        }
                        if (arCustomer['billingProvince'] != "") {
                            createSaftElement(BILL_ADDRESS, "Province", arCustomer['billingProvince']);
                        }
                        createSaftElement(BILL_ADDRESS, "Country", arCustomer['billingISO3166']);


                        custFullAddress = fullAddress(arCustomer['shipCountry'], arCustomer['shipProvince'], arCustomer['shipMunicipality'], "",
                                arCustomer['shipdistrict'], arCustomer['shipcomuna'], arCustomer['shipneiborhood'],
                                arCustomer['shipstreetname'], arCustomer['shipbuildingnumber'], arCustomer['shippostalcode']);
                        if (custFullAddress != "") {
                            var SHIPP_ADDRESS = createSaftElement(CUSTOMER, "ShipToAddress");
                            if (arCustomer['shipbuildingnumber'] != "") {
                                createSaftElement(SHIPP_ADDRESS, "BuildingNumber", arCustomer['shipbuildingnumber'].toString().slice(0, 7));
                            }
                            if (arCustomer['shipstreetname'] != "") {
                                createSaftElement(SHIPP_ADDRESS, "StreetName", arCustomer['shipstreetname']);
                            }
                            createSaftElement(SHIPP_ADDRESS, "AddressDetail", custFullAddress);
                            if (arCustomer['shipMunicipality'] != "") {
                                createSaftElement(SHIPP_ADDRESS, "City", arCustomer['shipMunicipality']);
                            }
                            if (arCustomer['shippostalcode'] != "") {
                                createSaftElement(SHIPP_ADDRESS, "PostalCode", arCustomer['shippostalcode']);
                            }
                            if (arCustomer['shipProvince'] != "") {
                                createSaftElement(SHIPP_ADDRESS, "Province", arCustomer['shipProvince']);
                            }
                            createSaftElement(SHIPP_ADDRESS, "Country", arCustomer['shipISO3166']);
                        }

                        if (arCustomer['telephone1'] != "") {
                            createSaftElement(CUSTOMER, "Telephone", arCustomer['telephone1']);
                        }
                        if (arCustomer['email'] != "") {
                            createSaftElement(CUSTOMER, "Email", arCustomer['email']);
                        }
                        if (arCustomer['website'] != "") {
                            createSaftElement(CUSTOMER, "Website", arCustomer['website']);
                        }
                        createSaftElement(CUSTOMER, "SelfBillingIndicator", arCustomer['selfbillingindicator']);
                        CURRENT_CUSTOMER++;
                    }
                }

                saftMarterFileProductCompose();
            }
        };
        http.send(params);

    }

    function saftMarterFileProductCompose() {

        ST_SA_LISTING_SERVER = 1;
        var http = new XMLHttpRequest();
        var params = "action=getSaftProductList" + "&filterInfo=" + JSON.stringify(FILTER);
        var url = URL_SA_SAFT_X;
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function () {
            if (http.readyState == 4 && http.status == 200) {
                try {
                    var products = JSON.parse(this.responseText);
                } catch (e) {
                    showHttpResponseText(http);
                }
                var n = products.length;
                TOTAL_PRODUCT = n;
                report();
                if (n > 0) {
                    var oldProduct = "";
                    for (var i = 0; i < n; i++) {
                        var arProduct = products[i];
                        if (oldProduct != arProduct['productId']) {
                            var PRODUCT = createSaftElement(MASTER_FILE, "Product");
                            var prodType = arProduct['productType'].toString().toUpperCase();
                            if (prodType != "P") {
                                if (prodType != "S") {
                                    if (prodType != "O") {
                                        if (prodType != "E") {
                                            if (prodType != "I") {
                                                prodType = "O";
                                            }
                                        }
                                    }
                                }
                            }
                            createSaftElement(PRODUCT, "ProductType", prodType);
                            createSaftElement(PRODUCT, "ProductCode", arProduct['productId']);
                            createSaftElement(PRODUCT, "ProductDescription", arProduct['productDescription']);
                            var productBarCode = arProduct['productBarCode'];
                            if (productBarCode == "") {
                                productBarCode = arProduct['productId'];
                            }
                            createSaftElement(PRODUCT, "ProductNumberCode", productBarCode);
                        }
                        oldProduct = arProduct['productId'];
                        CURRENT_PRODUCT++;
                    }
                }

                saftMarterFileTaxTableCompose();
            }
        };
        http.send(params);

    }

    function saftMarterFileTaxTableCompose() {

        var TAX_TABLE = createSaftElement(MASTER_FILE, "TaxTable");

        ST_SA_LISTING_SERVER = 1;
        var http = new XMLHttpRequest();
        var params = "action=getSaftTaxTable" + "&filterInfo=" + JSON.stringify(FILTER);
        var url = URL_SA_SAFT_X;
        http.open('POST', url, true);
        http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        http.onreadystatechange = function () {
            if (http.readyState == 4 && http.status == 200) {
                try {
                    var taxs = JSON.parse(this.responseText);
                } catch (e) {
                    showHttpResponseText(http);
                }
                var n = taxs.length;
                if (n > 0) {
                    var oldTax = "";
                    var oldIVA = "";
                    for (var i = 0; i < n; i++) {
                        var arTax = taxs[i];
                        if ((oldTax != arTax['taxGroup']) || (oldIVA != arTax['iva'])) {
                            var cTaxCode = taxCode(arTax['taxGroup'], arTax['iva']);
                            var TABLE_ENTRY = createSaftElement(TAX_TABLE, "TaxTableEntry");
                            createSaftElement(TABLE_ENTRY, "TaxType", cTaxCode['type']);
                            var taxReg = "AO";
                            if (BILLING_PROVINCE == 4) {
                                taxReg = "AO-CAB";
                            }
                            createSaftElement(TABLE_ENTRY, "TaxCountryRegion", taxReg);
                            createSaftElement(TABLE_ENTRY, "TaxCode", cTaxCode['code']);
                            createSaftElement(TABLE_ENTRY, "Description", cTaxCode['code']);
                            createSaftElement(TABLE_ENTRY, "TaxPercentage", arTax['iva']);
                        }
                        oldTax = arTax['taxGroup'];
                        oldIVA = arTax['iva'];
                    }
                }

                saftSorceDocumentsInvoiceCompose();
            }
        };
        http.send(params);

    }


    function saftSorceDocumentsInvoiceCompose() {
        try {
            if (STOP_PROCESS) {
                return false;
            }
            INVOICE_ERROR = false;
            var SALE_INVOICES = createSaftElement(SOURCE_DOCUMENTS, "SalesInvoices");
            CURRENT_INVOICE_NUMBER = "Procurando...";

            ST_SA_LISTING_SERVER = 1;
            var http = new XMLHttpRequest();
            var params = "action=getSaftSaleInvoicesSimpleList" + "&filterInfo=" + JSON.stringify(FILTER);
            var url = URL_SA_SAFT_X;
            http.open('POST', url, true);
            http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            http.onreadystatechange = function () {
                if (http.readyState == 4 && http.status == 200) {
                    try {
                        var response = JSON.parse(this.responseText);

                        var arDebitCredit = response['debitCredit'];
                        INVOICE_LIST = response['invoices'];
                        var n = INVOICE_LIST.length;
                        TOTAL_INVOICES = n;
                        CURRENT_INVOICE = 0;
                        report();
                        createSaftElement(SALE_INVOICES, "NumberOfEntries", Number(n).toFixed());
                        createSaftElement(SALE_INVOICES, "TotalDebit", Number(arDebitCredit['debitAmount']).toFixed(2));
                        createSaftElement(SALE_INVOICES, "TotalCredit", Number(arDebitCredit['creditAmount']).toFixed(2));

                        if (n > 0) {
                            saftSourceDocumentsInvoiceDetais(SALE_INVOICES);
                        } else {
                            CURRENT_INVOICE_NUMBER = "Não foram encontradas...";
                            saftSorceDocumentsWorkingDocumentCompose();
                        }
                    } catch (e) {
                        INVOICE_ERROR = true;
                        console.log(e);
                        showHttpResponseText(http);
                    }
                }
            };
            http.send(params);

        } catch (e) {
            INVOICE_ERROR = true;
            console.log(e);
        }
    }

    function saftSourceDocumentsInvoiceDetais(SALE_INVOICES) {
        try {
            if (STOP_PROCESS) {
                return false;
            }
            INVOICE_ERROR = false;
            let arInvoice = INVOICE_LIST[CURRENT_INVOICE];
            let status = arInvoice['invoicestatus'].toString().toUpperCase();
            if (status == "C") {
                status = "N";
            }

            CURRENT_INVOICE_NUMBER = arInvoice['invoicenumber'];

            FILTER.invoiceType = arInvoice['invoicetype'];
            FILTER.invoiceNumber = arInvoice['invoicenumber'];
            FILTER.status = status;

            ST_SA_LISTING_SERVER = 1;
            var http = new XMLHttpRequest();
            var params = "action=getSaftSaleInvoicesFullDetails" + "&filterInfo=" + JSON.stringify(FILTER);
            var url = URL_SA_SAFT_X;
            http.open('POST', url, true);
            http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            http.onreadystatechange = function () {
                if (http.readyState == 4 && http.status == 200) {
                    try {
                        ATTEMPTS_INVOICE = 0;
                        var response = JSON.parse(this.responseText);

                        arInvoice = response['invoiceDetaisl'];

                        let INVOICE = createSaftElement(SALE_INVOICES, "Invoice");
                        createSaftElement(INVOICE, "InvoiceNo", arInvoice['invoicenumber']);
                        let DOCUMENT_STATUS = createSaftElement(INVOICE, "DocumentStatus");
                        //status
                        createSaftElement(DOCUMENT_STATUS, "InvoiceStatus", status);
                        createSaftElement(DOCUMENT_STATUS, "InvoiceStatusDate", setLocalDate(arInvoice['invoiceStatusDate'], 19, false, "T"));
                        if (status != "N") {
                            createSaftElement(DOCUMENT_STATUS, "Reason", arInvoice['invoicestatusreason'].toString().slice(0, 45));
                        }
                        createSaftElement(DOCUMENT_STATUS, "SourceID", arInvoice['Sourceidstatus']);
                        createSaftElement(DOCUMENT_STATUS, "SourceBilling", arInvoice['Sourcebilling']);

                        createSaftElement(INVOICE, "Hash", arInvoice['hash']);
                        createSaftElement(INVOICE, "HashControl", arInvoice['hashcontrol']);
                        createSaftElement(INVOICE, "Period", new Date(arInvoice['invoicedate']).getMonth() + 1);
                        createSaftElement(INVOICE, "InvoiceDate", setLocalDate(arInvoice['invoicedate'], 10));
                        createSaftElement(INVOICE, "InvoiceType", arInvoice['invoicetype']);

                        let SPECIAL_REGIME = createSaftElement(INVOICE, "SpecialRegimes");
                        createSaftElement(SPECIAL_REGIME, "SelfBillingIndicator", arInvoice['SelfBillingIndicator']);
                        createSaftElement(SPECIAL_REGIME, "CashVATSchemeIndicator", arInvoice['cashvatschemeindicator']);
                        createSaftElement(SPECIAL_REGIME, "ThirdPartiesBillingIndicator", arInvoice['thirdpartiesbillingindicator']);

                        createSaftElement(INVOICE, "SourceID", arInvoice['sourceid']);
                        createSaftElement(INVOICE, "SystemEntryDate", setLocalDate(arInvoice['systementrydate'], 19, false, "T"));
                        createSaftElement(INVOICE, "CustomerID", arInvoice['customerid']);

                        let invFullAddress = fullAddress(arInvoice['shipCountry'], arInvoice['shipProvince'], arInvoice['shipMunicipality'], "",
                                arInvoice['districtshipto'], arInvoice['comunashipto'], arInvoice['neiborhoodshipto'],
                                arInvoice['streetnameshipto'], arInvoice['buildingnumbershipto'], arInvoice['postalcodeshipto']);
                        if (invFullAddress != "") {
                            let SHIP_ADDRESS = createSaftElement(INVOICE, "ShipTo");
                            createSaftElement(SHIP_ADDRESS, "DeliveryID", arInvoice['deliveryIdShipto']);
                            createSaftElement(SHIP_ADDRESS, "DeliveryDate", setLocalDate(arInvoice['deliverydateshipto'], 10));
                            let SADDRESS = createSaftElement(SHIP_ADDRESS, "Address");
                            if (arInvoice['buildingnumbershipto'] != "") {
                                createSaftElement(SADDRESS, "BuildingNumber", arInvoice['buildingnumbershipto'].toString().slice(0, 7));
                            }
                            if (arInvoice['streetnameshipto'] != "") {
                                createSaftElement(SADDRESS, "StreetName", arInvoice['streetnameshipto']);
                            }
                            createSaftElement(SADDRESS, "AddressDetail", invFullAddress);
                            if (arInvoice['shipMunicipality'] != "") {
                                createSaftElement(SADDRESS, "City", arInvoice['shipMunicipality']);
                            }
                            if (arInvoice['postalcodeshipto'] != "") {
                                createSaftElement(SADDRESS, "PostalCode", arInvoice['postalcodeshipto']);
                            }
                            if (arInvoice['shipProvince'] != "") {
                                createSaftElement(SADDRESS, "Province", arInvoice['shipProvince']);
                            }
                            createSaftElement(SADDRESS, "Country", arInvoice['shipISO3166']);
                        }

                        invFullAddress = fullAddress(arInvoice['billingCountry'], arInvoice['billingProvince'], arInvoice['billingMunicipality'], "",
                                arInvoice['districtshipfrom'], arInvoice['comunashipfrom'], arInvoice['neiborhoodshipfrom'],
                                arInvoice['streetNameshipfrom'], arInvoice['buildingnumbeshipfrom'], arInvoice['postalCodeshipfrom']);
                        if (invFullAddress != "") {
                            let SHIP_FROM = createSaftElement(INVOICE, "ShipFrom");
                            createSaftElement(SHIP_FROM, "DeliveryID", arInvoice['deliveryidshipfrom']);
                            createSaftElement(SHIP_FROM, "DeliveryDate", setLocalDate(arInvoice['deliverydateshipfrom'], 10));
                            let FADDRESS = createSaftElement(SHIP_FROM, "Address");
                            if (arInvoice['buildingnumbeshipfrom'] != "") {
                                createSaftElement(FADDRESS, "BuildingNumber", arInvoice['buildingnumbeshipfrom'].toString().slice(0, 7));
                            }
                            if (arInvoice['streetNameshipfrom'] != "") {
                                createSaftElement(FADDRESS, "StreetName", arInvoice['streetNameshipfrom']);
                            }
                            createSaftElement(FADDRESS, "AddressDetail", invFullAddress);
                            if (arInvoice['billingMunicipality'] == "") {
                                arInvoice['billingMunicipality'] = arInvoice['shipMunicipality'];
                            }
                            createSaftElement(FADDRESS, "City", arInvoice['billingMunicipality']);
                            if (arInvoice['postalCodeshipfrom'] != "") {
                                createSaftElement(FADDRESS, "PostalCode", arInvoice['postalCodeshipfrom']);
                            }
                            if (arInvoice['billingProvince'] != "") {
                                createSaftElement(FADDRESS, "Province", arInvoice['billingProvince']);
                            }
                            createSaftElement(FADDRESS, "Country", arInvoice['billingISO3166']);
                        }
                        //Lines
                        saftSourceDocumentsLines(INVOICE, arInvoice, response['invoiceLines'], response['invoicePayments']);

                        CURRENT_INVOICE++;
                        if (CURRENT_INVOICE < (INVOICE_LIST.length)) {
                            saftSourceDocumentsInvoiceDetais(SALE_INVOICES);
                        } else {
                            saftSorceDocumentsWorkingDocumentCompose();
                        }
                    } catch (e) {
                        INVOICE_ERROR = true;
                        console.log(e);
                        showHttpResponseText(http);
                    }
                }
            };
            http.send(params);

        } catch (e) {
            INVOICE_ERROR = true;
            console.log(e);
            ATTEMPTS_INVOICE++;
            if (ATTEMPTS_INVOICE <= 5) {
                saftSourceDocumentsInvoiceDetais(SALE_INVOICES);
            } else if (ATTEMPTS_INVOICE == 6) {
                try {
                    SOURCE_DOCUMENTS.removeChild(SALE_INVOICES);
                } catch (e) {

                }
                saftSorceDocumentsInvoiceCompose();
            }
        }
    }

    function saftSourceDocumentsLines(INVOICE, arInvoice, lines, invoicePayments) {
        var invoiceType = arInvoice['invoicetype'];
        var n = 0;
        for (var i = 0; i < lines.length; i++) {
            n++;
            let arLine = lines[i];
            let LINE = createSaftElement(INVOICE, "Line");
            createSaftElement(LINE, "LineNumber", n);

            createSaftElement(LINE, "ProductCode", arLine['productCode']);
            createSaftElement(LINE, "ProductDescription", arLine['productDescription']);
            createSaftElement(LINE, "Quantity", arLine['quantity']);
            let unit = arLine['unitOfMeasure'];
            if (unit == "") {
                unit = "UN";
            }
            createSaftElement(LINE, "UnitOfMeasure", unit);
            createSaftElement(LINE, "UnitPrice", Number(arLine['priceWithComission']).toFixed(2));
            createSaftElement(LINE, "TaxPointDate", setLocalDate(arInvoice['invoicedate'], 10));
            if (invoiceType == "NC") {
                if (arInvoice['reference'] != "") {
                    let REF = createSaftElement(LINE, "References");
                    createSaftElement(REF, "Reference", arInvoice['reference']);
                    createSaftElement(REF, "Reason", arInvoice['rectificationReason'].toString().slice(0, 45));
                }
            }
            createSaftElement(LINE, "Description", arLine['productDescription']);
            let PROD_SERIE_NUMB = createSaftElement(LINE, "ProductSerialNumber");
            createSaftElement(PROD_SERIE_NUMB, "SerialNumber", arLine['productCode']);
            if (invoiceType == "NC") {
                createSaftElement(LINE, "DebitAmount", Number(arLine['debitAmount']).toFixed(2));
            } else {
                createSaftElement(LINE, "CreditAmount", Number(arLine['creditAmount']).toFixed(2));
            }
            let lnTaxCode = taxCode(arInvoice['taxGroup'], arLine['taxPercentage']);
            let TAX = createSaftElement(LINE, "Tax");
            createSaftElement(TAX, "TaxType", lnTaxCode['type']);
            let taxReg = "AO";
            if (BILLING_PROVINCE == 4) {
                taxReg = "AO-CAB";
            }
            createSaftElement(TAX, "TaxCountryRegion", taxReg);
            createSaftElement(TAX, "TaxCode", lnTaxCode['code']);
            createSaftElement(TAX, "TaxPercentage", arLine['taxPercentage']);
            if (arLine['taxPercentage'] == 0) {
                createSaftElement(LINE, "TaxExemptionReason", arLine['taxExemptionReason']);
                createSaftElement(LINE, "TaxExemptionCode", arLine['taxExemptionCode']);
            }
            let settlementAmount = Number(arLine['priceWithComission']) * Number(arLine['settlementAmount']) / 100;
            createSaftElement(LINE, "SettlementAmount", settlementAmount.toFixed(2));
        }
        //Total
        let DOC_TOTAL = createSaftElement(INVOICE, "DocumentTotals");
        createSaftElement(DOC_TOTAL, "TaxPayable", Number(arInvoice['subtotalIvaWithoutDescount']).toFixed(2));
        let net = Number(arInvoice['grosstotal']) - Number(arInvoice['subtotalIvaWithoutDescount']);
        createSaftElement(DOC_TOTAL, "NetTotal", net.toFixed(2));
        createSaftElement(DOC_TOTAL, "GrossTotal", Number(arInvoice['grosstotal']).toFixed(2));

        if (invoiceType != "NC") {
            saftSourceDocumentsSalePay(INVOICE, DOC_TOTAL, arInvoice, invoicePayments);
        }

    }


    function saftSourceDocumentsSalePay(INVOICE, DOC_TOTAL, arInvoice, pays) {

        var n = pays.length;
        if (n > 0) {
            for (var i = 0; i < n; i++) {
                let arSPay = pays[i];
                let PAYMENT = createSaftElement(DOC_TOTAL, "Payment");
                createSaftElement(PAYMENT, "PaymentMechanism", arSPay['initials']);
                createSaftElement(PAYMENT, "PaymentAmount", Number(arSPay['PaymentAmount']).toFixed(2));
                createSaftElement(PAYMENT, "PaymentDate", setLocalDate(arSPay['PaymentDate'], 10));
            }

        }
        if (arInvoice['WithholdingTaxAmount'] > 0) {
            let HOLDING_TAX = createSaftElement(INVOICE, "WithholdingTax");
            createSaftElement(HOLDING_TAX, "WithholdingTaxType", arInvoice['WithholdingTaxType']);
            createSaftElement(HOLDING_TAX, "WithholdingTaxAmount", Number(arInvoice['WithholdingTaxAmount']));
        }

    }




    function saftSorceDocumentsWorkingDocumentCompose() {
        try {

            if (STOP_PROCESS) {
                return false;
            }

            CURRENT_WORKING_DOC_NUMBER = "Procurando...";

            ST_SA_LISTING_SERVER = 1;
            var http = new XMLHttpRequest();
            var params = "action=getSaftWorkingDocumentsSimpleList" + "&filterInfo=" + JSON.stringify(FILTER);
            var url = URL_SA_SAFT_X;
            http.open('POST', url, true);
            http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            http.onreadystatechange = function () {
                if (http.readyState == 4 && http.status == 200) {
                    try {
                        var response = JSON.parse(this.responseText);

                        let arDebitCredit = response['debitCredit'];
                        WORKING_DOCS_LIST = response['invoices'];
                        var n = WORKING_DOCS_LIST.length;
                        TOTAL_WORKING_DOCS = n;
                        CURRENT_WORKING_DOC = 0;
                        report();

                        if (n > 0) {
                            var WORKING_DOCUMENTS = createSaftElement(SOURCE_DOCUMENTS, "WorkingDocuments");
                            createSaftElement(WORKING_DOCUMENTS, "NumberOfEntries", n);
                            createSaftElement(WORKING_DOCUMENTS, "TotalDebit", Number(arDebitCredit['debitAmount']).toFixed(2));
                            createSaftElement(WORKING_DOCUMENTS, "TotalCredit", Number(arDebitCredit['creditAmount']).toFixed(2));

                            saftSourceDocumentsWDFullDetails(WORKING_DOCUMENTS);
                        } else {
                            CURRENT_WORKING_DOC_NUMBER = "Não foram encontradas..";
                            saftSorceDocumentsPaymentCompose();
                        }

                    } catch (e) {
                        WORKING_DOCS_ERROR = true;
                        console.log(e);
                        showHttpResponseText(http);
                    }

                }
            };
            http.send(params);

        } catch (e) {
            WORKING_DOCS_ERROR = true;
            console.log(e);
        }

    }

    function saftSourceDocumentsWDFullDetails(WORKING_DOCUMENTS) {

        try {

            if (STOP_PROCESS) {
                return false;
            }

            var arWD = WORKING_DOCS_LIST[CURRENT_WORKING_DOC];

            CURRENT_WORKING_DOC_NUMBER = arWD['invoicenumber'];
            var status = arWD['invoicestatus'];
            FILTER.invoiceType = arWD['invoicetype'];
            FILTER.invoiceNumber = arWD['invoicenumber'];
            FILTER.status = status;

            ST_SA_LISTING_SERVER = 1;
            var http = new XMLHttpRequest();
            var params = "action=getSaftWorkingDocumentsFullDetails" + "&filterInfo=" + JSON.stringify(FILTER);
            var url = URL_SA_SAFT_X;
            http.open('POST', url, true);
            http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            http.onreadystatechange = function () {
                if (http.readyState == 4 && http.status == 200) {
                    try {
                        ATTEMPTS_WORKING_DOC = 0;
                        var response = JSON.parse(this.responseText);

                        arWD = response['invoiceDetaisl'];

                        let WD = createSaftElement(WORKING_DOCUMENTS, "WorkDocument");
                        createSaftElement(WD, "DocumentNumber", arWD['invoicenumber']);
                        let DOC_STATUS = createSaftElement(WD, "DocumentStatus");
                        createSaftElement(DOC_STATUS, "WorkStatus", status);
                        createSaftElement(DOC_STATUS, "WorkStatusDate", setLocalDate(arWD['invoiceStatusDate'], 19, false, "T"));
                        if (status != "N") {
                            createSaftElement(DOC_STATUS, "Reason", arWD['invoicestatusreason'].toString().slice(0, 45));
                        }
                        createSaftElement(DOC_STATUS, "SourceID", arWD['Sourceidstatus']);
                        createSaftElement(DOC_STATUS, "SourceBilling", arWD['Sourcebilling']);

                        createSaftElement(WD, "Hash", arWD['hash']);
                        createSaftElement(WD, "HashControl", arWD['hashcontrol']);
                        createSaftElement(WD, "Period", new Date(arWD['invoicedate']).getMonth() + 1);
                        createSaftElement(WD, "WorkDate", setLocalDate(arWD['invoicedate'], 10));
                        createSaftElement(WD, "WorkType", arWD['invoicetype']);
                        createSaftElement(WD, "SourceID", arWD['sourceid']);
                        createSaftElement(WD, "SystemEntryDate", setLocalDate(arWD['systementrydate'], 19, false, "T"));
                        createSaftElement(WD, "CustomerID", arWD['customerid']);
                        //Lines
                        saftSourceDocumentsWDLines(WD, arWD, response['invoiceLines']);

                        CURRENT_WORKING_DOC++;
                        if (CURRENT_WORKING_DOC < WORKING_DOCS_LIST.length) {
                            saftSourceDocumentsWDFullDetails(WORKING_DOCUMENTS);
                        } else {
                            saftSorceDocumentsPaymentCompose();
                        }
                    } catch (e) {
                        WORKING_DOCS_ERROR = true;
                        console.log(e);
                        showHttpResponseText(http);
                    }
                }
            };
            http.send(params);

        } catch (e) {
            WORKING_DOCS_ERROR = true;
            console.log(e);
            ATTEMPTS_WORKING_DOC++;
            if (ATTEMPTS_WORKING_DOC <= 5) {
                saftSourceDocumentsWDFullDetails(WORKING_DOCUMENTS);
            } else if (ATTEMPTS_WORKING_DOC == 6) {
                try {
                    SOURCE_DOCUMENTS.removeChild(WORKING_DOCUMENTS);
                } catch (e) {

                }
                saftSorceDocumentsWorkingDocumentCompose();
            }
        }
    }


    function saftSourceDocumentsWDLines(WD, arWD, lines) {

        var n = 0;
        for (var i = 0; i < lines.length; i++) {
            n++;
            let arLine = lines[i];
            let LINE = createSaftElement(WD, "Line");
            createSaftElement(LINE, "LineNumber", n);

            createSaftElement(LINE, "ProductCode", arLine['productCode']);
            createSaftElement(LINE, "ProductDescription", arLine['productDescription']);
            createSaftElement(LINE, "Quantity", arLine['quantity']);
            let unit = arLine['unitOfMeasure'];
            if (unit == "") {
                unit = "UN";
            }
            createSaftElement(LINE, "UnitOfMeasure", unit);
            createSaftElement(LINE, "UnitPrice", Number(arLine['priceWithComission']).toFixed(2));
            createSaftElement(LINE, "TaxPointDate", setLocalDate(arWD['invoicedate'], 10));
            createSaftElement(LINE, "Description", arLine['productDescription']);
            let PROD_SERIE_NUMB = createSaftElement(LINE, "ProductSerialNumber");
            createSaftElement(PROD_SERIE_NUMB, "SerialNumber", arLine['productCode']);
            createSaftElement(LINE, "CreditAmount", Number(arLine['creditAmount']).toFixed(2));
            let lnTaxCode = taxCode(arWD['taxGroup'], arLine['taxPercentage']);
            let TAX = createSaftElement(LINE, "Tax");
            createSaftElement(TAX, "TaxType", lnTaxCode['type']);
            let taxReg = "AO";
            if (BILLING_PROVINCE == 4) {
                taxReg = "AO-CAB";
            }
            createSaftElement(TAX, "TaxCountryRegion", taxReg);
            createSaftElement(TAX, "TaxCode", lnTaxCode['code']);
            createSaftElement(TAX, "TaxPercentage", arLine['taxPercentage']);
            if (arLine['taxPercentage'] == 0) {
                createSaftElement(LINE, "TaxExemptionReason", arLine['taxExemptionReason']);
                createSaftElement(LINE, "TaxExemptionCode", arLine['taxExemptionCode']);
            }
            let settlementAmount = Number(arLine['priceWithComission']) * Number(arLine['settlementAmount']) / 100;
            createSaftElement(LINE, "SettlementAmount", settlementAmount.toFixed(2));
        }
        //Total
        let DOC_TOTAL = createSaftElement(WD, "DocumentTotals");
        createSaftElement(DOC_TOTAL, "TaxPayable", Number(arWD['subtotalIvaWithoutDescount']).toFixed(2));
        let net = Number(arWD['grosstotal']) - Number(arWD['subtotalIvaWithoutDescount']);
        createSaftElement(DOC_TOTAL, "NetTotal", net.toFixed(2));
        createSaftElement(DOC_TOTAL, "GrossTotal", Number(arWD['grosstotal']).toFixed(2));

    }


    function saftSorceDocumentsPaymentCompose() {
        try {

            if (STOP_PROCESS) {
                return false;
            }

            CURRENT_PAYMENT_NUMBER = "Procurando...";

            ST_SA_LISTING_SERVER = 1;
            var http = new XMLHttpRequest();
            var params = "action=getSaftPaymentListSimpleList" + "&filterInfo=" + JSON.stringify(FILTER);
            var url = URL_SA_SAFT_X;
            http.open('POST', url, true);
            http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            http.onreadystatechange = function () {
                if (http.readyState == 4 && http.status == 200) {
                    try {
                        var response = JSON.parse(this.responseText);

                        let arDebitCredit = response['debitCredit'];
                        PAYMENTS_LIST = response['payments'];
                        let n = PAYMENTS_LIST.length;
                        TOTAL_PAYMENTS = n;
                        CURRENT_PAYMENT = 0;
                        report();

                        if (n > 0) {
                            let PAYMENTS = createSaftElement(SOURCE_DOCUMENTS, "Payments");
                            createSaftElement(PAYMENTS, "NumberOfEntries", n);
                            createSaftElement(PAYMENTS, "TotalDebit", Number(arDebitCredit['debitAmount']).toFixed(2));
                            createSaftElement(PAYMENTS, "TotalCredit", Number(arDebitCredit['creditAmount']).toFixed(2));

                            saftSourceDocumentsPaymentFullDetails(PAYMENTS);
                        } else {
                            CURRENT_PAYMENT_NUMBER = "Não foram encontrados.";
                        }

                    } catch (e) {
                        PAYMENT_ERROR = true;
                        console.log(e);
                        showHttpResponseText(http);
                    }
                }
            };
            http.send(params);

        } catch (e) {
            PAYMENT_ERROR = true;
            console.log(e);
        }
    }


    function saftSourceDocumentsPaymentFullDetails(PAYMENTS) {
        try {

            if (STOP_PROCESS) {
                return false;
            }
            var arPay = PAYMENTS_LIST[CURRENT_PAYMENT];

            CURRENT_PAYMENT_NUMBER = arPay['invoicenumber'];
            FILTER.invoiceType = arPay['invoicetype'];
            FILTER.invoiceNumber = arPay['invoicenumber'];
            FILTER.status = arPay['paymentstatus'];

            ST_SA_LISTING_SERVER = 1;
            var http = new XMLHttpRequest();
            var params = "action=getSaftPaymentListFullDetails" + "&filterInfo=" + JSON.stringify(FILTER);
            var url = URL_SA_SAFT_X;
            http.open('POST', url, true);
            http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            http.onreadystatechange = function () {
                if (http.readyState == 4 && http.status == 200) {
                    try {
                        ATTEMPTS_PAYMENT = 0;
                        var response = JSON.parse(this.responseText);

                        arPay = response['invoiceDetaisl'];

                        let PAY = createSaftElement(PAYMENTS, "Payment");
                        createSaftElement(PAY, "PaymentRefNo", arPay['invoicenumber']);
                        createSaftElement(PAY, "Period", new Date(arPay['transactiondate']).getMonth() + 1);
                        createSaftElement(PAY, "TransactionDate", setLocalDate(arPay['transactiondate'], 10));
                        createSaftElement(PAY, "PaymentType", arPay['invoicetype']);
                        createSaftElement(PAY, "Description", arPay['description']);

                        let DOC_STATUS = createSaftElement(PAY, "DocumentStatus");
                        let status = arPay['paymentstatus'];
                        createSaftElement(DOC_STATUS, "PaymentStatus", status);
                        createSaftElement(DOC_STATUS, "PaymentStatusDate", setLocalDate(arPay['paymentstatusdate'], 19, false, "T"));
                        if (status != "N") {
                            createSaftElement(DOC_STATUS, "Reason", arPay['reason'].toString().slice(0, 45));
                        }
                        createSaftElement(DOC_STATUS, "SourceID", arPay['sourceidstatus']);
                        createSaftElement(DOC_STATUS, "SourcePayment", arPay['sourcepayment']);
                        //Pay
                        saftSourceDocumentsPayPay(PAY, arPay, response['invoicePayments'], response['invoiceLines']);

                        CURRENT_PAYMENT++;
                        if (CURRENT_PAYMENT < PAYMENTS_LIST.length) {
                            saftSourceDocumentsPaymentFullDetails(PAYMENTS);
                        }

                    } catch (e) {
                        PAYMENT_ERROR = true;
                        console.log(e);
                        showHttpResponseText(http);
                    }


                }
            };
            http.send(params);

        } catch (e) {
            PAYMENT_ERROR = true;
            console.log(e);
            ATTEMPTS_PAYMENT++;
            if (ATTEMPTS_PAYMENT <= 5) {
                saftSourceDocumentsPaymentFullDetails(PAYMENTS);
            } else if (ATTEMPTS_PAYMENT == 6) {
                try {
                    SOURCE_DOCUMENTS.removeChild(PAYMENTS);
                } catch (e) {

                }
                saftSorceDocumentsPaymentCompose();
            }
        }

    }


    function saftSourceDocumentsPayPay(PAY, arPay, pays, lines) {

        var n = pays.length;
        if (n > 0) {
            for (var i = 0; i < n; i++) {
                let arSPay = pays[i];
                let PAYMENT = createSaftElement(PAY, "PaymentMethod");
                createSaftElement(PAYMENT, "PaymentMechanism", arSPay['initials']);
                createSaftElement(PAYMENT, "PaymentAmount", Number(arSPay['PaymentAmount']).toFixed(2));
                createSaftElement(PAYMENT, "PaymentDate", setLocalDate(arPay['transactiondate'], 10));
            }

        }
        //
        createSaftElement(PAY, "SourceID", arPay['sourceid']);
        createSaftElement(PAY, "SystemEntryDate", setLocalDate(arPay['systementrydate'], 19, false, "T"));
        createSaftElement(PAY, "CustomerID", arPay['customerid']);
        //Lines
        saftSourceDocumentsPaymentLines(PAY, arPay, lines);

    }



    function saftSourceDocumentsPaymentLines(PAY, arPay, lines) {
        var n = 0;
        for (var i = 0; i < lines.length; i++) {
            n++;
            let arLine = lines[i];
            let LINE = createSaftElement(PAY, "Line");
            createSaftElement(LINE, "LineNumber", n);

            let SOURCE_DOC = createSaftElement(LINE, "SourceDocumentID");
            createSaftElement(SOURCE_DOC, "OriginatingON", arLine['originatingon']);
            createSaftElement(SOURCE_DOC, "InvoiceDate", setLocalDate(arLine['invoicedate'], 10));
            createSaftElement(SOURCE_DOC, "Description", arLine['description']);

            createSaftElement(LINE, "CreditAmount", Number(arLine['creditamount']).toFixed(2));
            let lnTaxCode = taxCode(arPay['taxGroup'], arLine['taxpercentage']);
            let TAX = createSaftElement(LINE, "Tax");
            createSaftElement(TAX, "TaxType", lnTaxCode['type']);
            let taxReg = "AO";
            if (BILLING_PROVINCE == 4) {
                taxReg = "AO-CAB";
            }
            createSaftElement(TAX, "TaxCountryRegion", taxReg);
            createSaftElement(TAX, "TaxCode", lnTaxCode['code']);
            createSaftElement(TAX, "TaxPercentage", arLine['taxpercentage']);
            if (arLine['taxpercentage'] == 0) {
                createSaftElement(LINE, "TaxExemptionReason", "Transmissão de bens e serviço não sujeita");
                createSaftElement(LINE, "TaxExemptionCode", "M02");
            }
        }
        //Total
        let DOC_TOTAL = createSaftElement(PAY, "DocumentTotals");
        createSaftElement(DOC_TOTAL, "TaxPayable", Number(0).toFixed(2));
        createSaftElement(DOC_TOTAL, "NetTotal", Number(arPay['grosstotal']).toFixed(2));
        createSaftElement(DOC_TOTAL, "GrossTotal", Number(arPay['grosstotal']).toFixed(2));

    }



    function createSaftElement(father, tag, inner = "") {
        let elem = document.createElementNS(SAFT_NS, tag);
        if (inner != "") {
            elem.innerHTML = inner;
        }
        father.appendChild(elem);
        return elem;
    }


    function taxIdForSaft(taxId) {
        taxId = (taxId).toString().trim();
        if (taxId == "") {
            return "999999999";
        }
        if (isNaN(taxId)) {
            return "999999999";
        }
        return taxId;
    }


    function taxCode(taxGroup, iva) {
        var type = "";
        if (isNaN(taxGroup)) {
            type = "NS";
        } else if (taxGroup <= 1) {
            type = "NS";
        } else {
            type = "IVA";
        }
        var code = "NS";
        if (type == "IVA") {
            if (iva == 0) {
                code = "ISE";
            } else {
                code = "NOR";
            }
        }
        return {"type": type, "code": code};
    }


    function fullAddress(country, province, municipality, city, district, comuna, neiborhood, street, buildnumber, postalCode) {
        var address = "";
        if (street != null) {
            if (street != "") {
                address += street + ", ";
            }
        }
        if (buildnumber != null) {
            if (buildnumber != "") {
                address += "nº " + buildnumber + ", ";
            }
        }
        if (neiborhood != null) {
            if (neiborhood != "") {
                address += neiborhood + ", ";
            }
        }
        if (comuna != null) {
            if (comuna != "") {
                address += comuna + ", ";
            }
        }
        if (district != null) {
            if (district != "") {
                address += district + ", ";
            }
        }
        if (city != null) {
            if (city != "") {
                address += city + ", ";
            }
        }
        if (municipality != null) {
            if (municipality != "") {
                address += municipality + ", ";
            }
        }
        if (province != null) {
            if (province != "") {
                address += province + ", ";
            }
        }
        if (country != null) {
            if (country != "") {
                address += country + ", ";
            }
        }
        if (postalCode != null) {
            if (postalCode != "") {
                address += "CP " + postalCode + ", ";
            }
        }

        return address;
    }

    function downLoadXML() {
        var goback = true;
        report();
        if ((INVOICE_ERROR) || (WORKING_DOCS_ERROR) || (PAYMENT_ERROR)) {
            goback = false;
            STOP_PROCESS = true;
        }
        if (CURRENT_INVOICE == TOTAL_INVOICES) {
            if (CURRENT_WORKING_DOC == TOTAL_WORKING_DOCS) {
                if (CURRENT_PAYMENT == TOTAL_PAYMENTS) {
                    goback = false;
                }
            }
        }
        if (goback) {
            setTimeout(function () {
                downLoadXML();
            }, 1000);
            return false;
        }

        ST_SA_LISTING_SERVER = 0;

        var xmltext = SAFT_NS.innerHTML.replace("!--", "").replace("--", "");

        var dep = (FILTER.dependencyId != -1) ? "_" + FILTER.dependencyId : "";

        var filename = FILTER.companyId + dep + "_v" + new Date().getTime() + ".xml";
        var pom = document.createElement('a');
        var bb = new Blob([xmltext], {type: 'text/plain'});

        pom.setAttribute('href', window.URL.createObjectURL(bb));
        pom.setAttribute('download', filename);

        pom.dataset.downloadurl = ['text/plain', pom.download, pom.href].join(':');
        pom.draggable = true;
        pom.classList.add('dragout');

        pom.click();

        hideNotification();
    }

    function report() {

        var divReport = document.getElementById("divSaftReport");
        divReport.innerHTML = "<p><b>Iniciando criação do SAFT.</b> <br> Este processo pode demorar alguns minutos.</p>";
        divReport.innerHTML += "<p><b>Clientes: </b> " + CURRENT_CUSTOMER + " / " + TOTAL_CUSTOMERS + ".</p>";
        divReport.innerHTML += "<p><b>Produtos e serviços: </b> " + CURRENT_PRODUCT + " / " + TOTAL_PRODUCT + ".</p>";
        divReport.innerHTML += "<p><b>Facturas: </b> <i>" + CURRENT_INVOICE_NUMBER + "</i> ** <b>" + CURRENT_INVOICE + "</b> / " + TOTAL_INVOICES + ".</p>";
        if (INVOICE_ERROR) {
            divReport.innerHTML += "<p><b><i>Erro nas facturas.</i></b></p>";
        }
        divReport.innerHTML += "<p><b>Consultas de preço: </b><i>" + CURRENT_WORKING_DOC_NUMBER + "</i> ** <b>" + CURRENT_WORKING_DOC + "</b> / " + TOTAL_WORKING_DOCS + ".</p>";
        if (WORKING_DOCS_ERROR) {
            divReport.innerHTML += "<p><b><i>Erro nas consultas de preço.</i></b></p>";
        }
        divReport.innerHTML += "<p><b>Recibos: </b><i>" + CURRENT_PAYMENT_NUMBER + "</i> ** <b>" + CURRENT_PAYMENT + "</b> / " + TOTAL_PAYMENTS + ".</p>";
        if (PAYMENT_ERROR) {
            divReport.innerHTML += "<p><b><i>Erro nos recibos.</i></b></p>";
        }

        divReport.innerHTML += "<p><i>Iniciado em: " + START_TIME + " / Última verificação: " + setLocalDate(new Date(), 19) + "</i></p>";
    }
}


