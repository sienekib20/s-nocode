class _linkRequest{constructor(e="Nova",t=5e3,n=-1,i="2021-07-01",s=1,a="",l=1,o="",r="",u="2021-01-01",c="Solteiro(a)",d="2021-02-01",m=-1,p="",S="",R=-1,I=18,L=-1,h="",E="",g="",q="",v="",D="",y="",b="",T="",k="",f=0){this.requestid=e,this.companyid=t,this.dependencyid=n,this.requestdate=i,this.requesttype=s,this.cil=a,this.requeststatus=l,this.requestername=o,this.requesterdocnumber=r,this.requesterdocemission=u,this.requestermaritalstatus=c,this.requesterbirthdate=d,this.requesterbirthprovince=m,this.requesterbirthmunicipality=R,this.requesterfather=p,this.requestermother=S,this.billingprovince=I,this.billingmunicipality=L,this.billingcomuna=h,this.billingneiborhood=E,this.billingstreetname=g,this.billingblock=q,this.billingbuildingnumber=v,this.billingpostalcode=D,this.telephone1=y,this.telephone2=b,this.telephone3=T,this.email=k,this.statususer=f}}function linkRequestRegisterOnLoad(){checkUserStatusAfterExecute(function(){fillSelectConsumerTypeAqua("selLkReqDetRequestType"),fillSelectLinkRequestStatus("selLkReqDetRequestStatus"),fillSelectMunicipality("selLkReqDetMunicipality",SYSTEM_USER_STATUS.provinceId,-1),fillSelectNeiborhood("selLkReqDetNeiborhood",1,-1),fillSelectProvince("selLkReqDetProvince",1,SYSTEM_USER_STATUS.provinceId),fillSelectProvince("selLkReqDetBirthProvince",-1,SYSTEM_USER_STATUS.provinceId),fillSelectZoneBlock("selLkReqDetBlock",-1),maskInputByPlaceHold("txtLkReqDetPostalCode"),maskInputByPlaceHold("txtLkReqDetPhone1"),maskInputByPlaceHold("txtLkReqDetPhone2"),maskInputByPlaceHold("txtLkReqDetPhone3")})}function linkRequestListOnLoad(){try{showNotification(),checkUserStatusAfterExecute(function(){fillSelectDependencyFull("selLkReqDependency",-1,1),fillSelectConsumerTypeAqua("selLkReqRequestType",-1),fillSelectLinkRequestStatus("selLkReqRequestStatus",-1);const e=document.getElementById("selLkReqProvince");e.onchange=function(){fillSelectMunicipality("selLkReMunicipality",e.value,-1,-1)},fillSelectProvince("selLkReqProvince",-1,18),fillSelectNeiborhood("selLkReqNeiborhood",1,-1),setTriggerButtonByText("txtLkReqInitialDate","btLkReqViewAll"),setTriggerButtonByText("txtLkReqEndDate","btLkReqViewAll"),document.getElementById("chbLkReqLisShowDate").onclick=function(){setEnableDisableByCheckBox(this.id,"txtLkReqInitialDate","txtLkReqEndDate")},checkPermission("3001")?showParts("btLkReqNewRequest",!0):hideParts("btLkReqNewRequest"),linkRequestListDetails(),hideNotification()})}catch(e){showNotification(e.message,0)}}function linkRequestNewRequest(){showParts("divLkReqDetail"),linkRequestGetRequestDetails(-1)}function linkRequestGetRequestDetails(e){const t=document.getElementById("lbLkReqHeader"),n=document.getElementById("txtLkReqDetRequestId"),i=document.getElementById("txtLkReqDetRequestDate"),s=document.getElementById("selLkReqDetRequestType"),a=document.getElementById("selLkReqDetRequestStatus"),l=document.getElementById("txtLkReqDetCIL"),o=document.getElementById("txtLkReqDetRequesterName"),r=document.getElementById("txtLkReqDetDocNumber"),u=document.getElementById("txtLkReqDetDocEmissionDate"),c=document.getElementById("selLkReqDetMaritalStatus"),d=document.getElementById("txtLkReqDetBirthDate"),m=document.getElementById("selLkReqDetBirthProvince"),p=document.getElementById("txtLkReqDetFather"),S=document.getElementById("txtLkReqDetMother"),R=document.getElementById("selLkReqDetMunicipality"),I=document.getElementById("txtLkReqDetComuna"),L=document.getElementById("selLkReqDetNeiborhood"),h=document.getElementById("txtLkReqDetStreet"),E=document.getElementById("selLkReqDetBlock"),g=document.getElementById("txtLkReqDetBuidNumber"),q=document.getElementById("txtLkReqDetPostalCode"),v=document.getElementById("txtLkReqDetPhone1"),D=document.getElementById("txtLkReqDetPhone2"),y=document.getElementById("txtLkReqDetPhone3"),b=document.getElementById("txtLkReqDetEmail"),T=document.getElementById("lbLkReqUpdateBy");if(showParts("btLhReqDetSave",!0),hideParts("btLkReqDetCancel"),hideParts("btLkReqAprove"),-1==e){t.innerHTML="Nova solicitação de ligação",n.value="Nova solicitação";var k=new Date;i.value=setLocalDate(k,10),s.value=1,a.value=1,l.value="",o.value="",r.value="",u.value=setLocalDate(new Date(k.getFullYear()-2,k.getMonth(),k.getDate()),10),c.value="Solteiro(a)",d.value=setLocalDate(new Date(k.getFullYear()-18,k.getMonth(),k.getDate()),10),m.value=SYSTEM_USER_STATUS.provinceId,m.dispatchEvent(new Event("change")),p.value="",S.value="",R.value=SYSTEM_USER_STATUS.municipalityId,I.value="",h.value="",E.value="",g.value="",q.value="",v.value="",D.value="",y.value="",b.value="",T.innerHTML="Data de actualizaçao: "+setLocalDate(k,16),dispatchOnInputChange()}else{t.innerHTML="Detalhes da solicitação de ligação";var f=new XMLHttpRequest,_="action=getLinkRequestDetails&requestId="+e,N=URL_SA_LINK_REQUEST_X;f.open("POST",N,!0),f.setRequestHeader("Content-type","application/x-www-form-urlencoded"),f.onreadystatechange=function(){if(4==f.readyState&&200==f.status){var e=JSON.parse(this.responseText);n.value=e.id,i.value=setLocalDate(e.requestdate,10),s.value=e.requesttype,a.value=e.requeststatus,l.value=e.cil,o.value=e.requestername,r.value=e.requesterdocnumber,u.value=setLocalDate(e.requesterdocemission,10),c.value=e.requestermaritalstatus,d.value=setLocalDate(e.requesterbirthdate,10),m.value=e.requesterbirthprovince,fillSelectMunicipality("selLkReqDetBirthMunicipality",m.value,-1,e.requesterbirthmunicipality),p.value=e.requesterfather,S.value=e.requestermother,R.value=e.billingmunicipality,I.value=e.billingcomuna,L.value=e.billingneiborhood,h.value=e.billingstreetname,E.value=e.billingblock,g.value=e.billingbuildingnumber,q.value=e.billingpostalcode,v.value=e.telephone1,D.value=e.telephone2,y.value=e.telephone3,b.value=e.email,T.innerHTML="Cadastrado por "+getOperatorName(e.operatorEntry,e.entryuser)+" em "+setLocalDate(e.entrydate,16)+". <br>Actualizado por "+getOperatorName(e.operatorName,e.statususer)+" em "+setLocalDate(e.statusdate,16),dispatchOnInputChange();var t=e.requeststatus;1!=t&&hideParts("btLhReqDetSave"),checkPermission("3002")&&3!=t&&showParts("btLkReqDetCancel",!0),checkPermission("3003")&&1==t&&showParts("btLkReqAprove",!0)}},f.send(_)}setAutoCompleteToFiel("txtLkReqDetCIL",38,1),setAutoCompleteToFiel("txtLkReqDetRequesterName",39,1),setAutoCompleteToFiel("txtLkReqDetComuna",40,1),setAutoCompleteToFiel("txtLkReqDetStreet",42,1),setAutoCompleteToFiel("txtLkReqDetBuidNumber",44,1),setAutoCompleteToFiel("txtLkReqDetPostalCode",45,1)}function linkRequestSave(e){var t=!1,n="",i=new _linkRequest;if(i.requestid=document.getElementById("txtLkReqDetRequestId").value,i.companyid=SYSTEM_USER_STATUS.companyId,i.dependencyid=SYSTEM_USER_STATUS.workplaceId,i.requestdate=document.getElementById("txtLkReqDetRequestDate").value,i.requesttype=document.getElementById("selLkReqDetRequestType").value,i.cil=reviewString(document.getElementById("txtLkReqDetCIL")).toUpperCase(),i.requeststatus=document.getElementById("selLkReqDetRequestStatus").value,i.requestername=reviewString(document.getElementById("txtLkReqDetRequesterName")).toUpperCase(),i.requesterdocnumber=reviewString(document.getElementById("txtLkReqDetDocNumber")).toUpperCase(),i.requesterdocemission=getElementValue("txtLkReqDetDocEmissionDate"),i.requestermaritalstatus=getElementValue("selLkReqDetMaritalStatus"),i.requesterbirthdate=getElementValue("txtLkReqDetBirthDate"),i.requesterbirthprovince=getElementValue("selLkReqDetBirthProvince"),i.requesterbirthmunicipality=getElementValue("selLkReqDetBirthMunicipality"),i.requesterfather=reviewString(document.getElementById("txtLkReqDetFather")).toUpperCase(),i.requestermother=reviewString(document.getElementById("txtLkReqDetMother")).toUpperCase(),i.billingprovince=document.getElementById("selLkReqDetProvince").value,i.billingmunicipality=document.getElementById("selLkReqDetMunicipality").value,i.billingcomuna=reviewString(document.getElementById("txtLkReqDetComuna")).toUpperCase(),i.billingneiborhood=reviewString(document.getElementById("selLkReqDetNeiborhood")).toUpperCase(),i.billingstreetname=reviewString(document.getElementById("txtLkReqDetStreet")).toUpperCase(),i.billingblock=reviewString(document.getElementById("selLkReqDetBlock")).toUpperCase(),i.billingbuildingnumber=reviewString(document.getElementById("txtLkReqDetBuidNumber")).toUpperCase(),i.billingpostalcode=reviewString(document.getElementById("txtLkReqDetPostalCode")).replace(/[^0-9\-]/g,""),i.telephone1=reviewString(document.getElementById("txtLkReqDetPhone1")).replace(/[^0-9]/g,""),i.telephone2=reviewString(document.getElementById("txtLkReqDetPhone2")).replace(/[^0-9]/g,""),i.telephone3=reviewString(document.getElementById("txtLkReqDetPhone3")).replace(/[^0-9]/g,""),i.email=reviewEmail(document.getElementById("txtLkReqDetEmail").value),i.statususer=SYSTEM_USER_STATUS.userId,""==i.requestername&&(t=!0,n+="Deve indicar o nome do solicitante. <br>"),validateDate(new Date(i.requestdate))||(t=!0,n+="Data da solicitação fora do formato. <br>"),validateDate(new Date(i.requesterdocemission))||(t=!0,n+="Data de emissão fora de formato. <br>"),validateDate(new Date(i.requesterbirthdate))||(t=!0,n+="Data de nascimento fora fo formato. <br>"),i.billingmunicipality<=0&&(t=!0,n+="Deve indicar o município. <br>"),""==i.billingneiborhood&&(t=!0,n+="Deve indicar o bairro. <br>"),""==i.telephone1&&""==i.telephone2&&""==i.telephone3&&(t=!0,n+="Deve indicar pelo menos um contacto telefónico. <br>"),""!=i.email&&(validateEmail(i.email)||(t=!0,n+="E-mail fora do formato. <br>")),1==ST_SA_WAITING_SERVER&&(t=!0,n+="Aguardando resposta do servidor. <br>"),t)return alert(n),!1;showNotification(),ST_SA_WAITING_SERVER=1;var s=new XMLHttpRequest,a="action=saveLinkRequest&linkRequestInfo="+JSON.stringify(i),l=URL_SA_LINK_REQUEST_X;s.open("POST",l,!0),s.setRequestHeader("Content-type","application/x-www-form-urlencoded"),s.onreadystatechange=function(){if(4==this.readyState&&200==this.status){ST_SA_WAITING_SERVER=0;var t=JSON.parse(s.responseText);1==t.status?(showNotification(t.msg,1),"LKREQLIS"==e&&linkRequestListDetails(),hideParts("divLkReqDetail")):showNotification(t.msg,0)}},s.send(a)}function linkRequestListDetails(e=-1,t=-1){try{var n={};if(n.companyId=SYSTEM_USER_STATUS.companyId,n.dependencyId=getElementValue("selLkReqDependency"),n.dependencyId<=0&&Number(SYSTEM_USER_STATUS.billingprofile)<BILLING_PROFILE_PARTNER_INDEX)return showNotification("Seleccionar uma filial.",0),!1;n.filterByDate=getElementChecked("chbLkReqLisShowDate"),n.initialDate=getElementValue("txtLkReqInitialDate"),n.endDate=getElementValue("txtLkReqEndDate"),n.requestType=getElementValue("selLkReqRequestType"),n.requestStatus=getElementValue("selLkReqRequestStatus"),n.municipalityId=getElementValue("selLkReMunicipality"),n.neiborhood=getElementValue("selLkReqNeiborhood"),n.requesterId=t,n.userId=e,showNotification();var i=new XMLHttpRequest,s="action=getLinkRequestListDetails&filterInfo="+JSON.stringify(n),a=URL_SA_LINK_REQUEST_X;i.open("POST",a,!0),i.setRequestHeader("Content-type","application/x-www-form-urlencoded"),i.onreadystatechange=function(){if(4==i.readyState&&200==i.status){try{var e=JSON.parse(this.responseText)}catch(e){showHttpResponseText(i)}e.length<=0?showNotification("Não encontrado.",0):hideDivFilter();var t=e.length,n=document.getElementById("numResultPageNumber"),s=document.getElementById("numResultItems");function a(){var i=Number(n.value),a=Number(s.value),l=(i-1)*a,o=l+a;o>t&&(o=t);var r={},u={},c={};setElementInnerHtml("lbResultOnList",o+" / "+t);let d=document.getElementById("tblLkReqListTable");d.innerHTML="";for(var m=l,p=l;p<o;p++){var S=e[p];let t=JSON.parse(S);var R=CONSUMER_TYPE_AQUA[Number(t.requesttype)],I=LINK_REQUEST_STATUS[Number(t.requeststatus)],L=t.billingneiborhood,h=getOperatorName(t.operatorName,t.statususer);addAmountResume(r,R,1),addAmountResume(u,I,1),addAmountResume(c,L,1),m+=1;let n=d.insertRow(-1);n.id="trLhReqList"+t.id,n.setAttribute("class","borderGray"+setSelectedRowBackground("SELECTED_ROW_LINK_REQUEST_LIST",n.id));let i=n.insertCell(-1);i.setAttribute("class","colRowNumber"),i.innerHTML=m;let s=n.insertCell(-1);s.setAttribute("class","colProductId"),s.innerHTML=t.id;let a=n.insertCell(-1);a.setAttribute("class","colProductId"),a.innerHTML=setLocalDate(t.requestdate,10),n.insertCell(-1).innerHTML=R,n.insertCell(-1).innerHTML=t.requestername,n.insertCell(-1).innerHTML=t.billingMunicipality,n.insertCell(-1).innerHTML=L,n.insertCell(-1).innerHTML=t.telephone1+" / "+t.telephone2+" / "+t.telephone3;var E="black",g="c ",q=t.requeststatus;2==q?g="textUpperUcase textBold":3==q&&(E="red");let l=n.insertCell(-1);l.setAttribute("class",g),l.innerHTML=I,l.style.color=E;let o=n.insertCell(-1);o.setAttribute("class","colProductId"),o.innerHTML=setLocalDate(t.statusdate,16);let v=n.insertCell(-1);v.setAttribute("class","colProductId"),v.innerHTML=h;let D=n.insertCell(-1);D.setAttribute("class","noPrint");let y=document.createElement("button");y.type="button",y.setAttribute("class","labelDetail");const b=t.id;y.onclick=function(){linkRequestEditRequest(b),setSelectedRowOnClick(n,"SELECTED_ROW_LINK_REQUEST_LIST")},D.appendChild(y);let T=document.createElement("button");if(T.setAttribute("class","labelPrint"),T.type="button",T.onclick=function(){setSelectedRowOnClick(n,"SELECTED_ROW_LINK_REQUEST_LIST"),sessionStorage.setItem("linkrequest_to_print",JSON.stringify(t)),window.open("printLinkRequestLayoutA4.php")},D.appendChild(T),2==q){if(checkPermission("3010")&&0==t.inspection){let e=document.createElement("button");e.type="button",e.innerHTML="Vistoria",e.onclick=function(){setSelectedRowOnClick(n,"SELECTED_ROW_LINK_REQUEST_LIST"),sessionStorage.setItem("linkrequest_to_inspect",JSON.stringify(t)),window.location.assign("InspectionList.php")},D.appendChild(e)}if(checkPermission("0101")&&0==t.customerid){let e=document.createElement("button");e.setAttribute("class","labelCustomer"),e.type="button",e.innerHTML="Cliente",e.onclick=function(){setSelectedRowOnClick(n,"SELECTED_ROW_LINK_REQUEST_LIST"),sessionStorage.setItem("linkrequest_to_customer",JSON.stringify(t)),window.location.assign("CustomerList.php")},D.appendChild(e)}}}var v=document.getElementById("tblLkReqResumeTable");for(var S in v.innerHTML="",m=0,r)m+=1,resume(v,m,"Tipo de solicitação",r,S,"Solicitações");for(var S in m=0,u)m+=1,resume(v,m,"Estado da solicitação",u,S,"Solicitações");for(var S in m=0,c)m+=1,resume(v,m,"Bairros",c,S,"Solicitações");hideNotification()}n.value=1,setResultTotalPages(n.id,s.id,t),n.onchange=function(){a()},s.onchange=function(){setResultTotalPages(n.id,s.id,t),a()},a()}},i.send(s)}catch(e){showNotification(e.message,0)}}function linkRequestEditRequest(e){showParts("divLkReqDetail"),linkRequestGetRequestDetails(e)}function linkRequestCancelRequest(){showConfirm("Tem a certeza que deseja anular está solicitação de ligação?",function(){showNotification();var e=getElementValue("txtLkReqDetRequestId"),t=SYSTEM_USER_STATUS.userId,n=new XMLHttpRequest,i="action=changeStatusLinkRequest&requestId="+e+"&status=3&userId="+t,s=URL_SA_LINK_REQUEST_X;n.open("POST",s,!0),n.setRequestHeader("Content-type","application/x-www-form-urlencoded"),n.onreadystatechange=function(){if(4==this.readyState&&200==this.status){var e=JSON.parse(n.responseText);1==e.status?(showNotification(e.msg,1),linkRequestListDetails(),hideParts("divLkReqDetail")):showNotification(e.msg,0)}},n.send(i)})}function linkRequestApproveRequest(){showConfirm("Tem a certeza que deseja APROVAR está solicitação de ligação?",function(){showNotification();var e=getElementValue("txtLkReqDetRequestId"),t=SYSTEM_USER_STATUS.userId,n=new XMLHttpRequest,i="action=changeStatusLinkRequest&requestId="+e+"&status=2&userId="+t,s=URL_SA_LINK_REQUEST_X;n.open("POST",s,!0),n.setRequestHeader("Content-type","application/x-www-form-urlencoded"),n.onreadystatechange=function(){if(4==this.readyState&&200==this.status){var e=JSON.parse(n.responseText);1==e.status?(showNotification(e.msg,1),linkRequestListDetails(),hideParts("divLkReqDetail")):showNotification(e.msg,0)}},n.send(i)})}function inspectionNewInspection(e){showParts("divInspectionDetail"),inspectionGetInspectionDetails(-1,e)}function inspectionGetInspectionDetails(e,t=null){const n=document.getElementById("lbInspecHeader"),i=document.getElementById("txtInspecDetInspectionId"),s=document.getElementById("txtInspecDetInspectionDate"),a=document.getElementById("selInspecDetSatus"),l=document.getElementById("txtInspecDetLinkRequestNumber"),o=document.getElementById("txtInspecDetRequesterName"),r=document.getElementById("selInspecDetMunicipality"),u=document.getElementById("txtInspecDetComuna"),c=document.getElementById("selInspecDetNeiborhood"),d=document.getElementById("txtInspecDetStreet"),m=document.getElementById("selInspecDetBlock"),p=document.getElementById("txtInspecDetBuidNumber"),S=document.getElementById("txtInspecDetPostalCode"),R=document.getElementById("txtInspecDetPhone1"),I=document.getElementById("txtInspecDetPhone2"),L=document.getElementById("txtInspecDetPhone3"),h=document.getElementById("txtInspecDetEmail"),E=document.getElementById("lbInspecUpdateBy");if(showParts("btInspecDetSave",!0),hideParts("btInspecDetCancel"),-1==e){n.innerHTML="Nova vistoria",i.value="Nova vistoria";var g=new Date;s.value=setLocalDate(g,10),a.value=1,l.value=t.id,o.value=t.requestername,r.value=t.billingmunicipality,u.value=t.billingcomuna,c.value=t.billingneiborhood,d.value=t.billingstreetname,m.value=t.billingblock,p.value=t.billingbuildingnumber,S.value=t.billingpostalcode,R.value=t.telephone1,I.value=t.telephone2,L.value=t.telephone3,h.value=t.email,E.innerHTML="Data de actualizaçao: "+setLocalDate(g,16),dispatchOnInputChange()}else{n.innerHTML="Detalhes da vistoria";var q=new XMLHttpRequest,v="action=getInspectionDetails&inspectionId="+e,D=URL_SA_LINK_REQUEST_X;q.open("POST",D,!0),q.setRequestHeader("Content-type","application/x-www-form-urlencoded"),q.onreadystatechange=function(){if(4==q.readyState&&200==q.status){var e=JSON.parse(this.responseText);i.value=e.id,s.value=setLocalDate(e.inspectiondate,10),a.value=e.inspectionstatus,l.value=e.linkrequestid,o.value=e.requestername,r.value=e.billingmunicipality,u.value=e.billingcomuna,c.value=e.billingneiborhood,d.value=e.billingstreetname,m.value=e.billingblock,p.value=e.billingbuildingnumber,S.value=e.billingpostalcode,R.value=e.telephone1,I.value=e.telephone2,L.value=e.telephone3,h.value=e.email,E.innerHTML="Cadastrado por "+getOperatorName(e.operatorEntry,e.entryuser)+" em "+setLocalDate(e.entrydate,16)+". <br>Actualizado por "+getOperatorName(e.operatorName,e.statususer)+" em "+setLocalDate(e.statusdate,16),dispatchOnInputChange();var t=e.inspectionstatus;1!=t&&hideParts("btInspecDetSave"),checkPermission("3011")&&3!=t&&showParts("btInspecDetCancel",!0)}},q.send(v)}}function inspectionRegisterOnLoad(){checkUserStatusAfterExecute(function(){fillSelectMunicipality("selInspecDetMunicipality",SYSTEM_USER_STATUS.provinceId,-1),fillSelectNeiborhood("selInspecDetNeiborhood",1,-1),fillSelectZoneBlock("selInspecDetBlock",-1)})}function inspectionSave(e,t){var n=!1,i="",s={};if(s.inspectionId=document.getElementById("txtInspecDetInspectionId").value,s.companyid=SYSTEM_USER_STATUS.companyId,s.dependencyid=SYSTEM_USER_STATUS.workplaceId,s.inspectiondate=document.getElementById("txtInspecDetInspectionDate").value,s.linkrequestid=document.getElementById("txtInspecDetLinkRequestNumber").value,s.nextStatus=e,s.statususer=SYSTEM_USER_STATUS.userId,validateDate(new Date(s.inspectiondate))||(n=!0,i+="Data da vistoria fora fo formato. <br>"),1==ST_SA_WAITING_SERVER&&(n=!0,i+="Aguardando resposta do servidor. <br>"),n)return alert(i),!1;showNotification(),ST_SA_WAITING_SERVER=1;var a=new XMLHttpRequest,l="action=saveInspection&inspectionInfo="+JSON.stringify(s),o=URL_SA_LINK_REQUEST_X;a.open("POST",o,!0),a.setRequestHeader("Content-type","application/x-www-form-urlencoded"),a.onreadystatechange=function(){if(4==this.readyState&&200==this.status){ST_SA_WAITING_SERVER=0;var e=JSON.parse(a.responseText);1==e.status?(showNotification(e.msg,1),"INSPECLIS"==t&&inspectionListDetails(),hideParts("divInspectionDetail")):showNotification(e.msg,0)}},a.send(l)}function inspectionListOnLoad(){try{showNotification(),checkUserStatusAfterExecute(function(){inspectionListGetCheckList(),fillSelectDependencyFull("selInspecListDependency",-1,1),fillSelectMunicipality("selInspecListMunicipality",SYSTEM_USER_STATUS.provinceId,-1,-1),fillSelectNeiborhood("selInspecListNeiborhood",1,-1),setTriggerButtonByText("txtInspecListInitialDate","btInspectListViewAll"),setTriggerButtonByText("txtInspecListEndDate","btInspectListViewAll"),document.getElementById("chbInspecListLisShowDate").onclick=function(){setEnableDisableByCheckBox(this.id,"txtInspecListInitialDate","txtInspecListEndDate")},checkPermission("3010")?showParts("btInspecLisNewInspection",!0):hideParts("btInspecLisNewInspection");var e=sessionStorage.getItem("linkrequest_to_inspect");if(null!=e&&""!=e){var t=document.getElementById("btInspectListPending");showParts(t.id,!0),t.onclick=function(){inspectionNewInspection(JSON.parse(e)),hideParts(t.id)},sessionStorage.setItem("linkrequest_to_inspect","")}inspectionListDetails(),hideNotification()})}catch(e){showNotification(e.message,0)}}function inspectionListDetails(e=-1){if(1==ST_SA_LISTING_SERVER)return showNotification("Procurando...",1),!1;var t={};if(t.companyId=SYSTEM_USER_STATUS.companyId,t.dependencyId=getElementValue("selInspecListDependency"),t.dependencyId<=0&&Number(SYSTEM_USER_STATUS.billingprofile)<BILLING_PROFILE_PARTNER_INDEX)return showNotification("Seleccionar uma filial.",0),!1;t.filterByDate=getElementChecked("chbInspecListLisShowDate"),t.initialDate=getElementValue("txtInspecListInitialDate"),t.endDate=getElementValue("txtInspecListEndDate"),t.inspectionStatus=getElementValue("selInspecListInspectStatus"),t.municipalityId=getElementValue("selInspecListMunicipality"),t.neiborhood=getElementValue("selInspecListNeiborhood"),t.userId=e,t.onlynumber=1,t.startIdx=0,t.endIdx=0,showNotification(),ST_SA_LISTING_SERVER=1;var n=new XMLHttpRequest,i="action=getInspectionListDetails&filterInfo="+JSON.stringify(t),s=URL_SA_LINK_REQUEST_X;n.open("POST",s,!0),n.setRequestHeader("Content-type","application/x-www-form-urlencoded"),n.onreadystatechange=function(){if(4==n.readyState&&200==n.status){ST_SA_LISTING_SERVER=0;var e=JSON.parse(this.responseText),i=Number(e.n);i<=0?showNotification("Não encontrado.",0):hideDivFilter();var s=document.getElementById("numResultPageNumber"),a=document.getElementById("numResultItems");function l(){var e=Number(s.value),n=Number(a.value),l=(e-1)*n,o=l+n;o>i&&(o=i),t.totalItems=i,t.startIdx=l,t.endIdx=o,t.onlynumber=0,inspectionListFilter(t)}s.value=1,setResultTotalPages(s.id,a.id,i),s.onchange=function(){l()},a.onchange=function(){setResultTotalPages(s.id,a.id,i),l()},setElementInnerHtml("lbResultOnList",i),l()}},n.send(i)}function inspectionListFilter(e){showNotification();var t=document.getElementById("tblInspecListTable");t.innerHTML="";var n=new XMLHttpRequest,i="action=getInspectionListDetails&filterInfo="+JSON.stringify(e),s=URL_SA_LINK_REQUEST_X;n.open("POST",s,!0),n.setRequestHeader("Content-type","application/x-www-form-urlencoded"),n.onreadystatechange=function(){if(4==n.readyState&&200==n.status){ST_SA_LISTING_SERVER=0;try{var i=JSON.parse(this.responseText)}catch(e){showHttpResponseText(n)}var s={},a={};setElementInnerHtml("lbResultOnList",e.endIdx+" / "+e.totalItems);for(var l=e.startIdx,o=0;o<i.length;o++){var r=i[o];let e=JSON.parse(r);var u=1==Number(e.inspectionstatus)?"Normal":"Anulado",c=e.billingneiborhood,d=getOperatorName(e.operatorName,e.statususer);addAmountResume(s,u,1),addAmountResume(a,c,1),l+=1;let n=t.insertRow(-1);n.id="trInspecList"+e.id,n.setAttribute("class","borderGray"+setSelectedRowBackground("SELECTED_ROW_INSPECTION_LIST",n.id));let R=n.insertCell(-1);R.setAttribute("class","colRowNumber"),R.innerHTML=l;let I=n.insertCell(-1);I.setAttribute("class","colProductId"),I.innerHTML=e.id;let L=n.insertCell(-1);L.setAttribute("class","colProductId"),L.innerHTML=setLocalDate(e.inspectiondate,10),n.insertCell(-1).innerHTML=e.requestername,n.insertCell(-1).innerHTML=e.billingMunicipality,n.insertCell(-1).innerHTML=c;var m="black",p="c ",S=e.inspectionstatus;2==S?p="textUpperUcase textBold":3==S&&(m="red");let h=n.insertCell(-1);h.setAttribute("class",p),h.innerHTML=u,h.style.color=m;let E=n.insertCell(-1);E.setAttribute("class","colProductId"),E.innerHTML=setLocalDate(e.statusdate,16);let g=n.insertCell(-1);g.setAttribute("class","colProductId"),g.innerHTML=d;let q=n.insertCell(-1);q.setAttribute("class","noPrint");let v=document.createElement("button");v.type="button",v.setAttribute("class","labelDetail");const D=e.id;v.onclick=function(){inspectionEditInspection(D),setSelectedRowOnClick(n,"SELECTED_ROW_INSPECTION_LIST")},q.appendChild(v);let y=document.createElement("button");y.setAttribute("class","labelPrint"),y.type="button",y.onclick=function(){setSelectedRowOnClick(n,"SELECTED_ROW_INSPECTION_LIST"),sessionStorage.setItem("inspection_to_print",JSON.stringify(e)),window.open("printInspectionLayoutA4.php")},q.appendChild(y)}var R=document.getElementById("tblInspecListResumeTable");for(var r in R.innerHTML="",l=0,s)l+=1,resume(R,l,"Estado da solicitação",s,r,"Solicitações");for(var r in l=0,a)l+=1,resume(R,l,"Bairros",a,r,"Solicitações");hideNotification()}},n.send(i)}function inspectionEditInspection(e){showParts("divInspectionDetail"),inspectionGetInspectionDetails(e)}function inspectionListGetCheckList(){var e=new XMLHttpRequest,t=URL_SA_LINK_REQUEST_X;e.open("POST",t,!0),e.setRequestHeader("Content-type","application/x-www-form-urlencoded"),e.onreadystatechange=function(){if(4==e.readyState&&200==e.status)try{setCookie("inspectionCheckList",this.responseText,500)}catch(t){showHttpResponseText(e)}},e.send("action=getInspectionCheckList")}