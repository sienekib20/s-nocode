!function(e){var t={htmlElem:"",initValue:"",keyboardLayout:{layout:[[[["`","`",192,0,!0],["1","1",49,0,!1],["2","2",50,0,!1],["3","3",51,0,!1],["4","4",52,0,!1],["5","5",53,0,!1],["6","6",54,0,!1],["7","7",55,0,!1],["8","8",56,0,!1],["9","9",57,0,!1],["0","0",48,0,!1],["-","-",189,0,!1],["=","=",187,0,!1],["q","q",81,0,!0],["w","w",87,0,!1],["e","e",69,0,!1],["r","r",82,0,!1],["t","t",84,0,!1],["y","y",89,0,!1],["u","u",85,0,!1],["i","i",73,0,!1],["o","o",79,0,!1],["p","p",80,0,!1],["[","[",219,0,!1],["]","]",221,0,!1],["&#92;","\\",220,0,!1],["a","a",65,0,!0],["s","s",83,0,!1],["d","d",68,0,!1],["f","f",70,0,!1],["g","g",71,0,!1],["h","h",72,0,!1],["j","j",74,0,!1],["k","k",75,0,!1],["l","l",76,0,!1],[";",";",186,0,!1],["&#39;","'",222,0,!1],["Enter","13",13,3,!1],["Shift","16",16,2,!0],["z","z",90,0,!1],["x","x",88,0,!1],["c","c",67,0,!1],["v","v",86,0,!1],["b","b",66,0,!1],["n","n",78,0,!1],["m","m",77,0,!1],[",",",",188,0,!1],[".",".",190,0,!1],["/","/",191,0,!1],["Shift","16",16,2,!1],["Bksp","8",8,3,!0],["Space","32",32,12,!1],["Clear","46",46,3,!1],["Cancel","27",27,3,!1]],[["~","~",192,0,!0],["!","!",49,0,!1],["@","@",50,0,!1],["#","#",51,0,!1],["$","$",52,0,!1],["%","%",53,0,!1],["^","^",54,0,!1],["&","&",55,0,!1],["*","*",56,0,!1],["(","(",57,0,!1],[")",")",48,0,!1],["_","_",189,0,!1],["+","+",187,0,!1],["Q","Q",81,0,!0],["W","W",87,0,!1],["E","E",69,0,!1],["R","R",82,0,!1],["T","T",84,0,!1],["Y","Y",89,0,!1],["U","U",85,0,!1],["I","I",73,0,!1],["O","O",79,0,!1],["P","P",80,0,!1],["{","{",219,0,!1],["}","}",221,0,!1],["|","|",220,0,!1],["A","A",65,0,!0],["S","S",83,0,!1],["D","D",68,0,!1],["F","F",70,0,!1],["G","G",71,0,!1],["H","H",72,0,!1],["J","J",74,0,!1],["K","K",75,0,!1],["L","L",76,0,!1],[":",":",186,0,!1],['"','"',222,0,!1],["Enter","13",13,3,!1],["Shift","16",16,2,!0],["Z","Z",90,0,!1],["X","X",88,0,!1],["C","C",67,0,!1],["V","V",86,0,!1],["B","B",66,0,!1],["N","N",78,0,!1],["M","M",77,0,!1],["<","<",188,0,!1],[">",">",190,0,!1],["?","?",191,0,!1],["Shift","16",16,2,!1],["Bksp","8",8,3,!0],["Space","32",32,12,!1],["Clear","46",46,3,!1],["Cancel","27",27,3,!1]]]]},keyboardType:"0",keyboardSet:0,dataType:"string",isMoney:!1,thousandsSep:",",disableKeyboardKey:!1};function a(a){e("#jQKeyboardContainer").empty();for(var r=t.keyboardLayout.layout[t.keyboardType][a],i=0;i<r.length;i++){if(r[i][4]){var o=document.createElement("div");o.setAttribute("class","jQKeyboardRow"),o.setAttribute("name","jQKeyboardRow"),e("#jQKeyboardContainer").append(o)}var l=document.createElement("button");l.setAttribute("type","button"),l.setAttribute("name","key"+r[i][2]),l.setAttribute("id","key"+r[i][2]),l.setAttribute("class","jQKeyboardBtn ui-button-colspan-"+r[i][3]),l.setAttribute("data-text",r[i][0]),l.setAttribute("data-value",r[i][1]),l.innerHTML=r[i][0],e(l).click(function(e){n(e.target)}),e(o).append(l)}}function n(n){var i,o=function(t){var a=e(t).get(0);if("selectionStart"in a)return a.selectionStart;if(document.selection){a.focus();var n=document.selection.createRange(),r=document.selection.createRange().text.length;return n.moveStart("character",-a.value.length),n.text.length-r}}(t.htmlElem),l=parseInt(e(n).attr("name").replace("key","")),c=e(t.htmlElem).val(),s=c,u=!1;switch(l){case 8:s=function(e,t){var a=e.split("");if(a.length>1)return a.splice(t-1,1),a.join("")}(c,o),o--;break;case 13:u=!0;break;case 16:i=0===t.keyboardSet?1:0,t.keyboardSet=i,a(i),1===i?e('button[name="key16"').addClass("shift-active"):e('button[name="key16"').removeClass("shift-active");break;case 27:u=!0,s=t.initValue;break;case 32:s=function(e,t){return r(e," ",t)}(c,o),o++;break;case 46:s="";break;case 190:default:s=function(e,t,a){return r(e,t.attr("data-value"),a)}(c,e(n),o),o++}e(t.htmlElem).val(s),function(t,a){var n=e(t).get(0);if(null!==n)if(n.createTextRange){var r=t.createTextRange();r.move("character",a),r.select()}else n.focus(),n.setSelectionRange(a,a)}(t.htmlElem,o),u&&(e("#jQKeyboardContainer").remove(),e(t.htmlElem).removeClass("focus"),e(t.htmlElem).blur())}function r(e,t,a){var n=e.split("");return n.splice(a,0,t),n.join("")}e.fn.initKeypad=function(n){e(this).click(function(n){var r,i;r=n.target,0===e("div.jQKeyboardContainer").length&&(t.htmlElem=e(r),t.initValue=e(r).val(),e(t.htmlElem).addClass("focus"),(i=document.createElement("div")).setAttribute("class","jQKeyboardContainer"),i.setAttribute("id","jQKeyboardContainer"),i.setAttribute("name","keyboardContainer"+t.keyboardType),e("body").append(i),a(0))})}}(jQuery);