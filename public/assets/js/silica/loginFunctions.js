var INDEX_PERSON_TYPE = 2;
function loginOnLoad() {
  setTriggerButtonByText("txtIndUserName", "btIndLogin"),
    setTriggerButtonByText("txtIndPassword", "btIndLogin");
}
function indexLogin(e = 0, t = "LOGIN") {
  var n = document.getElementById("lbIndMsgError"),
    a = document.getElementById("txtIndUserName").value,
    i = document.getElementById("txtIndPassword").value;
  n.innerHTML = "";
  var s = new XMLHttpRequest(),
    o =
      "action=getSystemUserInfo&username=" +
      a +
      "&pass=" +
      i +
      "&whoCall=" +
      t +
      "&udaox=" +
      e +
      "&openUser=0";
  s.open("POST", "indexLogin_crud.php", !0),
    s.setRequestHeader("Content-type", "application/x-www-form-urlencoded"),
    (s.onreadystatechange = function () {
      if (4 == this.readyState && 200 == this.status) {
        console.log(s.responseText);
        var e = JSON.parse(s.responseText);
        1 == e.status
          ? (t.includes(".php")
              ? window.location.assign(t + "?&frc=1")
              //: window.location.assign("/home?rp=" + e.defaultPass + "?&frc=1"),
              : window.location.assign("/home"),
            setCookie("sms_lockwindows", 0, 2e5))
          : (n.innerHTML = "Utilizador ou palavra passe errada.");
      }
    }),
    s.send(o);
}
function indexSetPersonType(e) {
  (INDEX_PERSON_TYPE = e),
    hideParts("divIndPersonType"),
    showParts("divIndLogin");
  var t =
    1 == e ? "Adquirir SílicaAqua singular" : "Adquirir SílicaAqua colectivo";
  document.getElementById("btIndRegisterMyCompany").value = t;
}
function indexGoBack(e) {
  hideParts("divIndLogin"), showParts("divIndPersonType");
}
function indexRegisterMyCompany(e) {
  window.location.href =
    "PreCadastroCadastro.php?personType=" +
    INDEX_PERSON_TYPE +
    "&userId=-1&request=20&registerId=0&companyId=5000&businessGroupId=-1&businessId=-1";
}
function indexRequestNewPass(e) {
  var t = !1,
    n = "",
    a = reviewString(document.getElementById("txtIndUserName")),
    i = reviewEmail(document.getElementById("txtIndEmail").value);
  if (
    ("" == a && ((t = !0), (n = "Deve indicar o utilizador. <br>")),
    validateEmail(i) || ((t = !0), (n += "Deve indicar um e-mail válido")),
    t)
  )
    return alert(n), !1;
  var s = new XMLHttpRequest(),
    o = "action=validateUsernameEmail&username=" + a + "&email=" + i;
  s.open("POST", "indexLogin_crud.php", !0),
    s.setRequestHeader("Content-type", "application/x-www-form-urlencoded"),
    (s.onreadystatechange = function () {
      if (4 == this.readyState && 200 == this.status) {
        var e = JSON.parse(s.responseText);
        1 == e.status
          ? (alert(e.msg),
            setTimeout(function () {
              window.location.assign("index.php");
            }, 5e3))
          : alert(e.msg);
      }
    }),
    s.send(o);
}
