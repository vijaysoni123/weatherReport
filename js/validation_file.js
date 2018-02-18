history.pushState(null, document.title, location.href);
window.addEventListener('popstate', function (event)
{
	history.pushState(null, document.title, location.href);
});

var isNS = (navigator.appName == "Netscape") ? 1 : 0;
if (navigator.appName == "Netscape") document.captureEvents(Event.MOUSEDOWN || Event.MOUSEUP);

function mischandler() {
    return false;
}
function mousehandler(e) {
    var myevent = (isNS) ? e : event;
    var eventbutton = (isNS) ? myevent.which : myevent.button;
    if ((eventbutton == 2) || (eventbutton == 3)) return false;
}
document.oncontextmenu = mischandler;
document.onmousedown = mousehandler;
document.onmouseup = mousehandler;

function disableCtrlKeyCombination(e) {
    var forbiddenKeys = new Array("a", "s", "c", "x");
    var key;
    var isCtrl;

    if (window.event) {
        key = window.event.keyCode;     //IE
        if (window.event.ctrlKey)
            isCtrl = true;
        else
            isCtrl = false;
    }
    else {
        key = e.which;     //firefox
        if (e.ctrlKey)
            isCtrl = true;
        else
            isCtrl = false;
    }

    if (isCtrl) {
        for (i = 0; i < forbiddenKeys.length; i++) {
            //case-insensitive comparation
            if (forbiddenKeys[i].toLowerCase() == String.fromCharCode(key).toLowerCase()) {
                return false;
            }
        }
    }
    return true;
}


function AllowAlphabet(e) {
    isIE = document.all ? 1 : 0
    keyEntry = !isIE ? e.which : event.keyCode;
    if (((keyEntry >= 65) && (keyEntry <= 90)) || ((keyEntry >= 97) && (keyEntry <= 122)) || (keyEntry == 46) || (keyEntry == 32) || keyEntry == 45 || keyEntry == 0 || keyEntry == 8) {
        return true;
    }
    else {
        return false;
    }
}

//Code Starts
var isIE = navigator.userAgent.toLowerCase().indexOf("msie");

function SetWidthToAuto(drpLst) {
    if (isIE > -1) {
        drpLst.style.width = 'auto';
    }
}

function ResetWidth(drpLst) {
    if (isIE > -1) {
        drpLst.style.width = '150px';
    }
}
