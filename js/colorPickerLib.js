Function.prototype.bind = function(object)
{
   var __method = this;
   return function() {
      __method.apply(object, arguments);
      }
}
Function.prototype.bindAsEventListener = function(object) {
   var __method = this;
   return function(event) {
      __method.call(object, event || window.event);
      }
   }
function showPatience() {
   waitElt = document.getElementById('waiting');
   if(!waitElt) {
      throw new Error('showPatience : element #waiting introuvable.');
      }
   for(i = 0; i < arguments.length; i++) {
      document.getElementById(arguments[i]).style.display = 'none';
      }
   waitElt.style.display = 'block';
   }
function getElementWidth(elem) {
   if(!elem) {
      return false;
      }
   var result = null;
   result = getCSSProperty(elemRef, "width");
   if(result.indexOf('%') !=- 1 || result == null || isNaN(parseInt(result))) {
      result = (elem.scrollWidth && (elem.offsetWidth != elem.scrollWidth)) ? elem.scrollWidth : elem.offsetWidth;
      }
   return parseInt(result);
   }
function extend(subClass, superClass, overrides) {
   var F = function() {
      };
   F.prototype = superClass.prototype;
   subClass.prototype = new F();
   subClass.prototype.constructor = subClass;
   subClass.superclass = superClass.prototype;
   if(superClass.prototype.constructor == Object.prototype.constructor) {
      superClass.prototype.constructor = superClass;
      }
   if(overrides) {
      for(var i in overrides) {
         subClass.prototype[i] = overrides[i];
         }
      }
   }

function getElementsByAttribute(oElm, strTagName, strAttributeName, strAttributeValue) {
   var arrElements = (strTagName == "*" && oElm.all) ? oElm.all : oElm.getElementsByTagName(strTagName);
   var arrReturnElements = new Array();
   var oAttributeValue = (typeof strAttributeValue != "undefined") ? new RegExp("(^|\\s)" + strAttributeValue + "(\\s|$)") : null;
   var oCurrent;
   var oAttribute;
   for(var i = 0; i < arrElements.length; i++) {
      oCurrent = arrElements[i];
      oAttribute = oCurrent.getAttribute && oCurrent.getAttribute(strAttributeName);
      if(typeof oAttribute == "string" && oAttribute.length > 0) {
         if(typeof strAttributeValue == "undefined" || (oAttributeValue && oAttributeValue.test(oAttribute))) {
            arrReturnElements.push(oCurrent);
            }
         }
      }
   return arrReturnElements;
   }
function delRows(tableID) {
   var myTBody = document.getElementById(tableID).getElementsByTagName('TBODY')[0];
   while(myTBody.getElementsByTagName('TR').length >= 1) {
      myTBody.deleteRow(0);
      }
   }
function ieGetCoords(elt) {
   var coords = elt.getBoundingClientRect();
   var border = getCSSProperty(document.getElementsByTagName('HTML')[0], 'border-width');
   var border = (border == 'medium') ? 2 : parseInt(border);
   coords.left += Math.max(elt.ownerDocument.documentElement.scrollLeft, elt.ownerDocument.body.scrollLeft) - border;
   coords.top += Math.max(elt.ownerDocument.documentElement.scrollTop, elt.ownerDocument.body.scrollTop) - border;
   return coords;
   }
function getElementCoords(element, eltReferant) {
   var coords = {
      left : 0, top : 0};
   if(element.getBoundingClientRect) {
      coords = ieGetCoords(element);
      if(typeof(eltReferant) == 'object') {
         var coords2 = ieGetCoords(eltReferant);
         coords.left -= coords2.left;
         coords.top -= coords2.top;
         coords2 = null;
         }
      }
   else {
      while(element) {
         if(/^table$/i.test(element.tagName)&&element.getElementsByTagName('CAPTION').length==1&&getCSSProperty(element,'position').toLowerCase()=='relative'){coords.top+=element.getElementsByTagName('CAPTION')[0].offsetHeight;
         }
      coords.left += element.offsetLeft;
      coords.top += element.offsetTop;
      element = element.offsetParent;
      if(typeof(eltReferant) == 'object' && element === eltReferant) {
         break;
         }
      }
   }
return coords;
}
function in_array(needle, collection, strict) {
if(strict == null) {
   strict = false;
   }
var i = collection.length - 1;
if(i >= 0) {
   do {
      if(collection[i] == needle) {
         if(strict && typeof(collection[i]) != typeof(needle)) {
            continue;
            }
         return true;
         }
      }
   while(i--);
   }
return false;
}
function setOpacity(node, percent) {
if(typeof(node) == 'string') {
   node = document.getElementById(node);
   }
if(is_ie) {
   opacity = "filter";
   percent = "alpha(opacity=" + percent + ")";
   node.zoom = 1;
   }
else {
   opacity = "MozOpacity";
   percent = percent / 100;
   }
node.style[opacity] = percent;
}
function mulNumbers() {
if(arguments.length < 2) {
   throw new Error('Fonction [multNubmers] : nombre d\'argument insuffisant');
   }
result = 0;
counter = arguments.length - 1;
for(i = 0; i < counter; i++) {
   result = parseFloat(delSpaces(arguments[i])) * parseFloat(delSpaces(arguments[i + 1]));
   arguments[i + 1] = result;
   }
return result;
}
function divNumbers(numerateur, denominateur) {
if(denominateur == 0) {
   return'0.00';
   }
quotient = parseFloat(delSpaces(numerateur)) / parseFloat(delSpaces(denominateur));
return quotient;
}
function addNumbers() {
return doOperation('+', arguments);
}
function subNumbers() {
return doOperation('-', arguments);
}
function doOperation(operande, oNumber) {
result = 0;
for(i = 0; i < oNumber.length; i++) {
   fNumber = parseFloat(delSpaces(oNumber[i]));
   if(operande == '+')result = fNumber + result;
   else if(operande == '-')result = (i == 0) ? fNumber : (result - fNumber);
   }
return result;
}
function loading(msg, divLoadingId) {
if(msg == null)msg = CST_MSG_LOADING;
if(divLoadingId == null)divLoadingId = 'divLoading';
var mode = 2;
var divLoading = window.document.getElementById(divLoadingId);
var reference = document.getElementById('rightColumn');
if(!reference) {
   reference = document.getElementById('content');
   mode = 1;
   }
if(!reference) {
   throw new Error('Fonction [loading] : element de reference introuvable.');
   }
if(divLoading == null) {
   var divLoading = document.createElement('DIV');
   divLoading.id = divLoadingId;
   divLoading.className = 'msgPatience';
   divLoading.style.top = '120px';
   divLoading.appendChild(document.createTextNode(msg));
   document.getElementsByTagName('BODY')[0].appendChild(divLoading);
   divLoading.style.left = ((760 - parseInt(divLoading.offsetWidth)) / 2) + 'px';
   if(mode == 2) {
      divLoading.style.left = parseInt((parseInt(divLoading.style.left) + (157 / 2))) + 'px';
      }
   }
if(mode == 1) {
   for(i = 0; i < reference.childNodes.length; i++) {
      if(reference.childNodes[i].nodeType == 1) {
         reference.childNodes[i].style.display = 'none';
         }
      }
   }
else {
   reference.style.display = 'none';
   }
}
function disconect() {
if(confirmation()) {
   window.document.location.href = WEB_SCRIPT + "connexion/deconnexion.php";
   return true;
   }
return false;
}
function getUrlParam(name) {
name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
var regexS = "[\\?&]" + name + "=([^&#]*)";
var regex = new RegExp(regexS);
var results = regex.exec(window.location.href);
if(results == null)return false;
else return results[1];
}
function write(str) {
myDiv = document.createElement('DIV');
myDiv.innerHTML = str;
debugBlock = document.getElementById('debug');
if(!debugBlock) {
   debugBlock = document.createElement('DIV');
   debugBlock.id = 'debug';
   with(debugBlock.style) {
      position = 'absolute';
      right = '5px';
      top = '5px';
      padding = '3px';
      width = "350px";
      minHeight = "150px";
      border = "1px solid #525D73";
      backgroundColor = "#DEEBF9";
      }
   document.getElementsByTagName('BODY')[0].appendChild(debugBlock);
   }
nbDiv = debugBlock.getElementsByTagName('DIV').length;
if(nbDiv % 2 == 0)myDiv.style.backgroundColor = '#BDD3EF';
if(str == 'clear')debugBlock.innerHTML = '';
else debugBlock.appendChild(myDiv);
}
function isInt(number) {
return(number == Math.round(number));
}
function getNumberFromStr(str) {
return str.replace(/[^-0-9]/g,'');
}
function uniSelectionRange(input, selectionStart, selectionEnd) {
if(input.setSelectionRange) {
   input.focus();
   input.setSelectionRange(selectionStart, selectionEnd);
   }
else if(input.createTextRange) {
   var range = input.createTextRange();
   range.moveEnd('character', selectionEnd);
   range.moveStart('character', selectionStart);
   range.select();
   }
}
function replaceSelection(input, replaceString) {
var selectedText = null;
if(window.getSelection)selectedText = window.getSelection();
else if(document.getSelection)selectedText = document.getSelection();
else if(document.selection)selectedText = document.selection.createRange().text;
if(input.setSelectionRange) {
   var selectionStart = input.selectionStart;
   var selectionEnd = input.selectionEnd;
   if(selectionStart - selectionEnd == 0)return false;
   input.value = input.value.substring(0, selectionStart) + replaceString + input.value.substring(selectionEnd);
   if(selectionStart != selectionEnd)uniSelectionRange(input, selectionStart, selectionStart + replaceString.length);
   return true;
   }
else if(document.selection) {
   var range = document.selection.createRange();
   if(range.parentElement() == input) {
      var isCollapsed = range.text == '';
      range.text = replaceString;
      if(!isCollapsed) {
         range.moveStart('character', - replaceString.length);
         range.select();
         }
      return true;
      }
   }
return false}
function getElementsByClass(node, searchClass, tag) {
var classElements = new Array();
if(node == null)node = document;
if(tag == null)tag = '*';
var els = node.getElementsByTagName(tag);
var elsLen = els.length;
var pattern = new RegExp("(^|\\s)" + searchClass + "(\\s|$)");
for(i = 0, j = 0; i < elsLen; i++) {
   if(pattern.test(els[i].className)) {
      classElements[j] = els[i];
      j++;
      }
   }
return classElements;
}
function getCSSProperty(mixed, sProperty) {
var oNode = (typeof mixed == "object") ? mixed : document.getElementById(mixed);
if(document.defaultView) {
   return document.defaultView.getComputedStyle(oNode, null).getPropertyValue(sProperty);
   }
else if(oNode.currentStyle) {
   sProperty = sProperty.replace(/\-(\w)/g,function(m,c){return c.toUpperCase();});
   return oNode.currentStyle[sProperty];
   }
else {
   return null;
   }
}
function trim(str) {
var res = str.replace(/^\s\s*/,""),ws=/\s / , i = res.length;
while(ws.test(res.charAt(--i)));
return res.slice(0, i + 1);
}
function confirmation() {
if(confirm('Etes-vous sur de vouloir vous deconnecter ?'))return true;
return false;
}
function openPopUp(url, iwidth, iheight) {
if(openPopUp.arguments.length == 4)options = openPopUp.arguments[3] + ',';
else options = '';
if((parseInt(iwidth) == iwidth) || (parseFloat(iwidth) == iwidth))iwidth = new String(iwidth);
if((parseInt(iheight) == iheight) || (parseFloat(iheight) == iheight))iheight = new String(iheight);
if(iwidth.indexOf('%') !=- 1)iwidth = (screen.width * getNumberFromStr(iwidth) / 100);
if(iheight.indexOf('%') !=- 1)iheight = (screen.height * getNumberFromStr(iheight) / 100);
var largeur = (screen.width / 2) - (iwidth / 2);
var hauteur = (screen.height / 2) - (iheight / 2);
var Infowindow = window.open(url, "Infos", options + "resizable=yes,top=" + hauteur + "," + "left=" + largeur + "," + "width=" + iwidth + "," + "height=" + iheight);
Infowindow.focus();
}
function virguleEnPoint(tmp_val) {
return tmp_val.replace(/[,]/,'\.');
}
function arrondir(nbr) {
var precision;
if(arrondir.arguments.length == 2)precision = arrondir.arguments[1];
else precision = 2;
if(isNaN(nbr)) {
   throw("La valeur '" + nbr + "' est incorrecte car non numerique");
   return 0;
   }
else if(isNaN(precision)) {
   throw("Le parametre de precision de la fonction arrondir '" + precision + "' est incorrecte car non numerique");
   return 0;
   }
return new Number(nbr).toFixed(precision);
}
function getWindowHeight() {
var windowHeight = 0;
if(typeof(window.innerHeight) == 'number') {
   windowHeight = window.innerHeight;
   }
else {
   if(document.documentElement && document.documentElement.clientHeight) {
      windowHeight = document.documentElement.clientHeight;
      }
   else {
      if(document.body && document.body.clientHeight) {
         windowHeight = document.body.clientHeight;
         }
      }
   }
return windowHeight;
}
function verticalCentering(dynVar, idElem) {
var heightParent = 0;
if(document.getElementById) {
   if(typeof dynVar == 'string')heightParent = document.getElementById(dynVar).offsetHeight;
   else heightParent = dynVar;
   var elem = document.getElementById(idElem);
   var elemHeight = elem.offsetHeight;
   if(heightParent - elemHeight > 0) {
      elem.style.position = 'relative';
      elem.style.top = ((heightParent / 2) - (elemHeight / 2)) + 'px';
      }
   else elem.style.position = 'static';
   }
}
var clavier_un =- 1;
var clavier_deux =- 1;
var clavier_cds = new Array(146);
clavier_cds[8] = "Retour arriere";
clavier_cds[9] = "Tabulation";
clavier_cds[12] = "Milieu (pave numerique)";
clavier_cds[13] = "Entree";
clavier_cds[16] = "Shift";
clavier_cds[17] = "Ctrl";
clavier_cds[18] = "Alt";
clavier_cds[19] = "Pause";
clavier_cds[20] = "Verr Maj";
clavier_cds[27] = "Echap";
clavier_cds[32] = "Espace";
clavier_cds[33] = "Page precedente";
clavier_cds[34] = "Page suivante";
clavier_cds[35] = "Fin";
clavier_cds[36] = "Debut";
clavier_cds[37] = "Fleche gauche";
clavier_cds[38] = "Fleche haut";
clavier_cds[39] = "Fleche droite";
clavier_cds[40] = "Fleche bas";
clavier_cds[44] = "Impr ecran";
clavier_cds[45] = "Inser";
clavier_cds[46] = "Suppr";
clavier_cds[91] = "Menu Demarrer Windows";
clavier_cds[92] = "Menu Demarrer Windows";
clavier_cds[93] = "Menu contextuel Windows";
clavier_cds[112] = "F1";
clavier_cds[113] = "F2";
clavier_cds[114] = "F3";
clavier_cds[115] = "F4";
clavier_cds[116] = "F5";
clavier_cds[117] = "F6";
clavier_cds[118] = "F7";
clavier_cds[119] = "F8";
clavier_cds[120] = "F9";
clavier_cds[121] = "F10";
clavier_cds[122] = "F11";
clavier_cds[123] = "F12";
clavier_cds[144] = "Verr Num";
clavier_cds[145] = "Arret defil";
var clavier_cor = new Array(103);
clavier_cor[45] = 112;
clavier_cor[46] = 113;
clavier_cor[47] = 114;
clavier_cor[48] = 115;
clavier_cor[49] = 116;
clavier_cor[50] = 117;
clavier_cor[51] = 118;
clavier_cor[52] = 119;
clavier_cor[53] = 120;
clavier_cor[54] = 121;
clavier_cor[55] = 122;
clavier_cor[56] = 123;
clavier_cor[69] = 36;
clavier_cor[70] = 35;
clavier_cor[71] = 33;
clavier_cor[72] = 34;
clavier_cor[73] = 38;
clavier_cor[74] = 40;
clavier_cor[75] = 37;
clavier_cor[76] = 39;
clavier_cor[78] = 47;
clavier_cor[79] = 42;
clavier_cor[80] = 43;
clavier_cor[81] = 45;
clavier_cor[82] = 45;
clavier_cor[83] = 46;
clavier_cor[88] = 18;
clavier_cor[89] = 16;
clavier_cor[90] = 17;
clavier_cor[100] = 144;
clavier_cor[101] = 145;
clavier_cor[102] = 19;
function codeTouche(e) {
var cret;
if(window.event) {
   if(parseInt(clavier_deux) > 0)cret = clavier_deux;
   else cret = window.event.keyCode;
   if(window.event.type == "keypress")clavier_deux = window.event.keyCode;
   if(window.event.type == "keydown")clavier_deux =- 1;
   }
else {
   if(parseInt(clavier_deux) > 0)cret = clavier_deux;
   else if((parseInt(clavier_un) > 0) && (e.which < 1))cret = clavier_un;
   else cret = e.which;
   if(e.type == "keydown") {
      clavier_un = e.which;
      clavier_deux =- 1;
      }
   if(e.type == "keypress")clavier_deux = e.which;
   }
if(parseInt(cret) > 57000) {
   cret = clavier_cor[cret - 57300];
   clavier_deux =- 1;
   }
return(parseInt(cret));
}
function _countdowntimer(cmd, ms) {
this.cmd = cmd;
this.ms = ms;
this.tp = 0;
}
_countdowntimer.prototype.start = function() {
if(this.tp > 0) {
   this.reset();
   }
this.tp = window.setTimeout(this.cmd, this.ms);
}
_countdowntimer.prototype.reset = function() {
if(this.tp > 0) {
   window.clearTimeout(this.tp);
   }
this.tp = 0;
}
var timeDiff = {
setStartTime : function() {
   d = new Date();
   time = d.getTime();
   }
, getDiff : function() {
   d = new Date();
   return(d.getTime() - time);
   }
}
function getTableColor() {
var tableColor = '';
var total = 1657;
var X = Y = j = RG = B = 0;
var aR = new Array(total);
var aG = new Array(total);
var aB = new Array(total);
var hexbase = new Array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F");
for(var i = 0; i < 256; i++) {
   aR[i + 510] = aR[i + 765] = aG[i + 1020] = aG[i + 5 * 255] = aB[i] = aB[i + 255] = 0;
   aR[510 - i] = aR[i + 1020] = aG[i] = aG[1020 - i] = aB[i + 510] = aB[1530 - i] = i;
   aR[i] = aR[1530 - i] = aG[i + 255] = aG[i + 510] = aB[i + 765] = aB[i + 1020] = 255;
   if(i < 255) {
      aR[i / 2 + 1530] = 127;
      aG[i / 2 + 1530] = 127;
      aB[i / 2 + 1530] = 127;
      }
   }
var i = 0;
var jl = new Array();
for(x = 0; x < 16; x++)for(y = 0; y < 16; y++)jl[i++] = hexbase[x] + hexbase[y];
tableColor += '<table id="colorPickerTable" cellspacing="0" cellpadding="0">';
var H = W = 63;
toto = 0;
for(Y = 0; Y <= H; Y++) {
   s = '<' + 'tr>';
   j = Math.round(Y * (510 / (H + 1)) - 255);
   for(X = 0; X <= W; X++) {
      i = Math.round(X * (total / W));
	  R = aR[i] - j;
      if(R < 0)R = 0;
      if(R > 255 || isNaN(R))R = 255;
      G = aG[i] - j;
      if(G < 0)G = 0;
      if(G > 255 || isNaN(G))G = 255;
      B = aB[i] - j;
      if(B < 0)B = 0;
      if(B > 255 || isNaN(B))B = 255;
      cssRGB = 'background-color:#' + jl[R] + jl[G] + jl[B];
      s = s + '<td style="' + cssRGB + '" ' + 'onmouseover="colorPickerLib.setTransitionAreaColor(this)" ' + 'onclick="colorPickerLib.returnColorProperty(this)"></td>';
      toto++;
      }
   tableColor += s + "</tr>";
   }
tableColor += "</table>";
return tableColor;
}
var colorPickerLib = {
colorPanel : null, obj : null, poundSign : true, attachColorPickerBehavior : function() {
   if(!document.getElementById ||!document.getElementsByTagName)return;
   var colorPickerElements = colorPickerLib.getElementsByClass(window.document, "colorPickerElement", "div");
   var current = new Object();
   var j = 0;
   for(j = 0; j < colorPickerElements.length; j++) {
      current = colorPickerElements[j];
      if(current.id == "") {
         throw new Error("Element de classe [colorPickerElement] sans identifiant. L'element a ete ignore.");
         continue;
         }
      addEvent(current, 'click', colorPickerLib.colorPickerShow, false);
      colorPickerLib.attachInputElement(current);
      }
   if(colorPickerLib.colorPanel == null && j > 0)colorPickerLib.createColorPicker();
   delete current;
   }
, attachInputElement : function(elm) {
   colorPickerInput = document.createElement("input");
   colorPickerInput.setAttribute("name", elm.id);
   colorPickerInput.setAttribute("id", "colorPicker:" + elm.id);
   colorPickerInput.setAttribute("type", "hidden");
   colorPickerInput.setAttribute("value", colorPickerLib.setPoundSign(colorPickerLib.decimalToHexa(getCSSProperty(elm.id, "background-color"))));
   elm.appendChild(colorPickerInput);
   delete colorPickerInput;
   }
, colorPickerShow : function(e) {
   colorPickerLib.obj = getEventSrc(e);
   colorPickerLib.colorPickerToggleStatus(true);
   }
, setTransitionAreaColor : function(elm) {
   document.getElementById("colorPickerTransitionArea").style.backgroundColor = elm.style.backgroundColor;
   }
, setPoundSign : function(colorValue) {
   return(colorPickerLib.poundSign) ? colorValue : colorValue.substring(1, colorValue.length);
   }
, returnColorProperty : function(elm) {
   theColor = colorPickerLib.decimalToHexa(elm.style.backgroundColor);
   colorPickerLib.obj.style.backgroundColor = theColor;
   window.document.getElementById("colorPicker:" + colorPickerLib.obj.id).value = colorPickerLib.setPoundSign(theColor);
   colorPickerLib.colorPickerToggleStatus();
   
   fireEvent($("colorPicker:" + colorPickerLib.obj.id), "change");
   }
, decimalToHexa : function(rgbValue) {
   function giveHex(Dec) {
      if(Dec == 10)Value = "A";
      else if(Dec == 11)Value = "B";
      else if(Dec == 12)Value = "C";
      else if(Dec == 13)Value = "D";
      else if(Dec == 14)Value = "E";
      else if(Dec == 15)Value = "F";
      else Value = "" + Dec;
      return Value;
      }
   if(rgbValue.indexOf("#") !=- 1)return rgbValue;
   var pattern = new RegExp("(^[a-z]*$)");
   if(pattern.test(rgbValue))return "";
   Red = rgbValue.substring((rgbValue.indexOf("(") + 1), (rgbValue.indexOf(",")));
   Green = rgbValue.substring((rgbValue.indexOf(",") + 1), (rgbValue.lastIndexOf(",")));
   Blue = rgbValue.substring((rgbValue.lastIndexOf(",") + 1), (rgbValue.indexOf(")")));
   return"#" + giveHex(Math.floor(Red / 16)) + giveHex(Red % 16) + giveHex(Math.floor(Green / 16)) + giveHex(Green % 16) + giveHex(Math.floor(Blue / 16)) + giveHex(Blue % 16);
   }
, colorPickerToggleStatus : function(alwaysVisible) {
   displayProperty = getCSSProperty("colorPickerPanel", "display");
   if(!alwaysVisible && displayProperty != "none")colorPickerLib.colorPanel.style.display = "none";
   else {
      var coords = colorPickerLib.getElementCoords(colorPickerLib.obj);
      var tp = parseInt(coords.top);
      var lt = parseInt(coords.left + colorPickerLib.obj.offsetWidth + 3);
      colorPickerLib.colorPanel.style.top = tp + "px";
      colorPickerLib.colorPanel.style.left = lt + "px";
      colorPickerLib.colorPanel.style.display = "block";
      }
   }
, ieGetCoords : function(elt) {
   var coords = {
      top : 0, left : 0};
   coords.top = elt.getBoundingClientRect().top;
   coords.left = elt.getBoundingClientRect().left;
   var border = getCSSProperty(document.getElementsByTagName('HTML')[0], 'border-width');
   var border = (border == 'medium') ? 2 : parseInt(border);
   if(isNaN(border)) {
      border = 0;
      }
   coords.left += Math.max(elt.ownerDocument.documentElement.scrollLeft, elt.ownerDocument.body.scrollLeft) - border;
   coords.top += Math.max(elt.ownerDocument.documentElement.scrollTop, elt.ownerDocument.body.scrollTop) - border;
   return coords;
   }
, getElementCoords : function(element) {
   var coords = {
      left : 0, top : 0};
   if(element.getBoundingClientRect) {
      coords = this.ieGetCoords(element);
      }
   else {
      while(element) {
         if(/^table$/i.test(element.tagName)&&element.getElementsByTagName('CAPTION').length==1&&getCSSProperty(element,'position').toLowerCase()=='relative'){coords.top+=element.getElementsByTagName('CAPTION')[0].offsetHeight;
         }
      coords.left += element.offsetLeft;
      coords.top += element.offsetTop;
      element = element.offsetParent;
      }
   }
return coords;
}
, getElementsByClass : function(node, searchClass, tag) {
var classElements = new Array();
var els = node.getElementsByTagName(tag);
var elsLen = els.length;
var pattern = new RegExp("(^|\\s)" + searchClass + "(\\s|$)");
for(i = 0, j = 0; i < elsLen; i++) {
   if(pattern.test(els[i].className)) {
      classElements[j] = els[i];
      j++;
      }
   }
return classElements;
}
, createColorPicker : function() {
colorPickerLib.colorPanel = document.createElement('div');
with(colorPickerLib.colorPanel) {
   id = "colorPickerPanel";
   innerHTML = '<div id="colorPickerPanelHeader" onclick="stopEvent(event);">' + ' <a href="javascript:colorPickerLib.colorPickerToggleStatus()" id="colorPickerClose"></a><div id="colorPickerTransitionArea"></div>' + '</div>' + '<div id="tableColor" onclick="stopEvent(event);">' + getTableColor() + '</div>';
   }
document.getElementsByTagName('body')[0].appendChild(colorPickerLib.colorPanel);
}
}