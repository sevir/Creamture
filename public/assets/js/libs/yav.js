var undef;
var ISFOCUSSET = false;
var internalRules;

function performCheck(J, H, D){
    isFocusSet = ISFOCUSSET;
    var I = makeRules(H);
    internalRules = makeRules(H);
    this.f = document.forms[J];
    if (!this.f) {
        debug("DEBUG: could not find form object " + J);
        return null
    }
    var G = new Array();
    var C = 0;
    if (I.length) {
        for (var E = 0; E < I.length; E++) {
            var F = I[E];
            if (F != null) {
                highlight(getField(f, F.el), inputclassnormal)
            }
        }
    }
    else {
        if (I != null) {
            highlight(getField(f, I.el), inputclassnormal)
        }
    }
    if (I.length) {
        for (var E = 0; E < I.length; E++) {
            var F = I[E];
            var B = null;
            if (F == null) {
            }
            else {
                if (F.ruleType == "pre-condition" || F.ruleType == "post-condition" || F.ruleType == "andor-operator") {
                }
                else {
                    if (F.ruleName == "implies") {
                        pre = F.el;
                        post = F.comparisonValue;
                        var A = getField(f, I[pre].el).className;
                        if (checkRule(f, I[pre]) == null && checkRule(f, I[post]) != null) {
                            B = deleteInline(F.alertMsg) + "__inline__" + I[post].el
                        }
                        else {
                            if (checkRule(f, I[pre]) != null) {
                                getField(f, I[pre].el).className = A
                            }
                        }
                    }
                    else {
                        B = checkRule(f, F)
                    }
                }
            }
            if (B != null) {
                G[C] = B;
                C++
            }
        }
    }
    else {
        var K = I;
        err = checkRule(f, K);
        if (err != null) {
            G[0] = err
        }
    }
    return displayAlert(G, D)
}

function checkKeyPress(E, G, A){
    var F = null;
    if (getBrowser() == "msie") {
        F = window.event.keyCode
    }
    else {
        if (getBrowser() == "netscape" || getBrowser() == "firefox") {
            F = E.which
        }
    }
    var H = makeRules(A);
    var B = true;
    if (H.length) {
        for (var D = 0; D < H.length; D++) {
            var C = H[D];
            if (C.ruleName == "keypress" && C.el == G.name) {
                B = isKeyAllowed(F, C.comparisonValue);
                break
            }
        }
    }
    else {
        var C = H;
        if (C.ruleName == "keypress" && C.el == G.name) {
            B = isKeyAllowed(F, C.comparisonValue)
        }
    }
    if (!B) {
        if (getBrowser() == "msie") {
            window.event.keyCode = 0
        }
        else {
            if (getBrowser() == "netscape" || getBrowser() == "firefox") {
                E.preventDefault();
                E.stopPropagation();
                E.returnValue = false
            }
        }
    }
    return false
}

function displayAlert(C, B){
    var A = null;
    clearAllInlineDivs();
    if (B == "classic") {
        A = displayClassic(C)
    }
    else {
        if (B == "innerHtml") {
            A = displayInnerHtml(C)
        }
        else {
            if (B == "inline") {
                A = displayInline(C)
            }
            else {
                if (B == "jsVar") {
                    A = displayJsVar(C)
                }
                else {
                    debug("DEBUG: alert type " + B + " not supported")
                }
            }
        }
    }
    return A
}

function displayClassic(B){
    var C = "";
    if (B != null && B.length > 0) {
        if (strTrim(HEADER_MSG).length > 0) {
            C += HEADER_MSG + "\n\n"
        }
        for (var A = 0; A < B.length; A++) {
            C += " " + deleteInline(B[A]) + "\n"
        }
        if (strTrim(FOOTER_MSG).length > 0) {
            C += "\n" + FOOTER_MSG
        }
        alert(C);
        return false
    }
    else {
        return true
    }
}

function displayInnerHtml(B){
    if (B != null && B.length > 0) {
        var C = "";
        if (strTrim(HEADER_MSG).length > 0) {
            C += HEADER_MSG
        }
        C += "<ul>";
        for (var A = 0; A < B.length; A++) {
            C += "<li>" + deleteInline(B[A]) + "</li>"
        }
        C += "</ul>";
        if (strTrim(FOOTER_MSG).length > 0) {
            C += FOOTER_MSG
        }
        document.getElementById(errorsdiv).innerHTML = C;
        document.getElementById(errorsdiv).className = innererror;
        document.getElementById(errorsdiv).style.display = "block";
        return false
    }
    else {
        document.getElementById(errorsdiv).innerHTML = "";
        document.getElementById(errorsdiv).className = "";
        document.getElementById(errorsdiv).style.display = "none";
        return true
    }
}

function displayInline(C){
    if (C != null && C.length > 0) {
        var A = new Array();
        var D = 0;
        for (var B = 0; B < C.length; B++) {
            var E = C[B].substring(C[B].indexOf("__inline__") + 10);
            if (document.getElementById(errorsdiv + "_" + E)) {
                document.getElementById(errorsdiv + "_" + E).innerHTML = deleteInline(C[B]);
                document.getElementById(errorsdiv + "_" + E).className = innererror;
                document.getElementById(errorsdiv + "_" + E).style.display = "block"
            }
            else {
                A[D] = C[B];
                D++
            }
        }
        if (D > 0) {
            displayInnerHtml(A)
        }
        return false
    }
    else {
        return true
    }
}

function clearAllInlineDivs(){
    var C = document.getElementsByTagName("div");
    for (var B = 0; B < C.length; B++) {
        var A = C[B].id;
        if (A.indexOf(errorsdiv + "_") == 0) {
            document.getElementById(A).innerHTML = "";
            document.getElementById(A).className = "";
            document.getElementById(A).style.display = "none"
        }
    }
}

function displayJsVar(B){
    document.getElementById(errorsdiv).className = "";
    document.getElementById(errorsdiv).style.display = "none";
    if (B != null && B.length > 0) {
        for (var A = 0; A < B.length; A++) {
            B[A] = deleteInline(B[A])
        }
        var C = "";
        C += "<script>var jsErrors;</script>";
        document.getElementById(errorsdiv).innerHTML = C;
        jsErrors = B;
        return false
    }
    else {
        document.getElementById(errorsdiv).innerHTML = "<script>var jsErrors;</script>";
        return true
    }
}

function rule(B, D, C, A, E){
    if (!checkArguments(arguments)) {
        return false
    }
    tmp = B.split(":");
    nameDisplayed = "";
    if (tmp.length == 2) {
        nameDisplayed = tmp[1];
        B = tmp[0]
    }
    this.el = B;
    this.nameDisplayed = nameDisplayed;
    this.ruleName = D;
    this.comparisonValue = C;
    this.ruleType = E;
    if (A == undef || A == null) {
        this.alertMsg = getDefaultMessage(B, nameDisplayed, D, C) + "__inline__" + this.el
    }
    else {
        this.alertMsg = A + "__inline__" + this.el
    }
}

function checkRule(f, myRule){
    retVal = null;
    if (myRule != null) {
        if (myRule.ruleName == "custom") {
            var customFunction = " retVal = " + myRule.el;
            eval(customFunction)
        }
        else {
            if (myRule.ruleName == "and") {
                var op_1 = myRule.el;
                var op_next = myRule.comparisonValue;
                if (checkRule(f, internalRules[op_1]) != null) {
                    retVal = myRule.alertMsg;
                    if (myRule.ruleType == "pre-condition" || myRule.ruleType == "andor-operator") {
                    }
                }
                else {
                    var op_k = op_next.split("-");
                    for (var k = 0; k < op_k.length; k++) {
                        if (checkRule(f, internalRules[op_k[k]]) != null) {
                            retVal = myRule.alertMsg;
                            if (myRule.ruleType == "pre-condition" || myRule.ruleType == "andor-operator") {
                            }
                            break
                        }
                    }
                }
            }
            else {
                if (myRule.ruleName == "or") {
                    var op_1 = myRule.el;
                    var op_next = myRule.comparisonValue;
                    var success = false;
                    if (checkRule(f, internalRules[op_1]) == null) {
                        success = true
                    }
                    else {
                        if (myRule.ruleType == "pre-condition" || myRule.ruleType == "andor-operator") {
                        }
                        var op_k = op_next.split("-");
                        for (var k = 0; k < op_k.length; k++) {
                            if (checkRule(f, internalRules[op_k[k]]) == null) {
                                success = true;
                                break
                            }
                            else {
                                if (myRule.ruleType == "pre-condition" || myRule.ruleType == "andor-operator") {
                                }
                            }
                        }
                    }
                    if (success) {
                        highlight(getField(f, internalRules[op_1].el), inputclassnormal);
                        var op_k = op_next.split("-");
                        for (var k = 0; k < op_k.length; k++) {
                            highlight(getField(f, internalRules[op_k[k]].el), inputclassnormal)
                        }
                    }
                    else {
                        retVal = myRule.alertMsg
                    }
                }
                else {
                    el = getField(f, myRule.el);
                    if (el == null) {
                        debug("DEBUG: could not find element " + myRule.el);
                        return null
                    }
                    var err = null;
                    if (el.type) {
                        if (el.type == "hidden" || el.type == "text" || el.type == "password" || el.type == "textarea") {
                            err = checkText(el, myRule)
                        }
                        else {
                            if (el.type == "checkbox") {
                                err = checkCheckbox(el, myRule)
                            }
                            else {
                                if (el.type == "select-one") {
                                    err = checkSelOne(el, myRule)
                                }
                                else {
                                    if (el.type == "select-multiple") {
                                        err = checkSelMul(el, myRule)
                                    }
                                    else {
                                        if (el.type == "radio") {
                                            err = checkRadio(el, myRule)
                                        }
                                        else {
                                            debug("DEBUG: type " + el.type + " not supported")
                                        }
                                    }
                                }
                            }
                        }
                    }
                    else {
                        err = checkRadio(el, myRule)
                    }
                    retVal = err
                }
            }
        }
    }
    return retVal
}

function checkArguments(A){
    if (A.length < 4) {
        debug("DEBUG: rule requires four arguments at least");
        return false
    }
    else {
        if (A[0] == null || A[1] == null) {
            debug("DEBUG: el and ruleName are required");
            return false
        }
    }
    return true
}

function checkRadio(B, E){
    var C = null;
    if (E.ruleName == "required") {
        var F = B;
        var D = false;
        if (isNaN(F.length) && F.checked) {
            D = true
        }
        else {
            for (var A = 0; A < F.length; A++) {
                if (F[A].checked) {
                    D = true;
                    break
                }
            }
        }
        if (!D) {
            highlight(B, inputclasserror);
            C = E.alertMsg
        }
    }
    else {
        if (E.ruleName == "equal") {
            var F = B;
            var D = false;
            if (isNaN(F.length) && F.checked) {
                if (F.value == E.comparisonValue) {
                    D = true
                }
            }
            else {
                for (var A = 0; A < F.length; A++) {
                    if (F[A].checked) {
                        if (F[A].value == E.comparisonValue) {
                            D = true;
                            break
                        }
                    }
                }
            }
            if (!D) {
                C = E.alertMsg
            }
        }
        else {
            if (E.ruleName == "notequal") {
                var F = B;
                var D = false;
                if (isNaN(F.length) && F.checked) {
                    if (F.value != E.comparisonValue) {
                        D = true
                    }
                }
                else {
                    for (var A = 0; A < F.length; A++) {
                        if (F[A].checked) {
                            if (F[A].value != E.comparisonValue) {
                                D = true;
                                break
                            }
                        }
                    }
                }
                if (!D) {
                    C = E.alertMsg
                }
            }
            else {
                debug("DEBUG: rule " + E.ruleName + " not supported for radio")
            }
        }
    }
    return C
}

function checkText(el, myRule){
    err = null;
    if (trimenabled) {
        el.value = strTrim(el.value)
    }
    if (myRule.ruleName == "required") {
        if (el.value == null || el.value == "") {
            highlight(el, inputclasserror);
            err = myRule.alertMsg
        }
    }
    else {
        if (myRule.ruleName == "equal") {
            err = checkEqual(el, myRule)
        }
        else {
            if (myRule.ruleName == "notequal") {
                err = checkNotEqual(el, myRule)
            }
            else {
                if (myRule.ruleName == "numeric") {
                    reg = new RegExp("^[0-9]*$");
                    if (!reg.test(el.value)) {
                        highlight(el, inputclasserror);
                        err = myRule.alertMsg
                    }
                }
                else {
                    if (myRule.ruleName == "alphabetic") {
                        reg = new RegExp("^[A-Za-z]*$");
                        if (!reg.test(el.value)) {
                            highlight(el, inputclasserror);
                            err = myRule.alertMsg
                        }
                    }
                    else {
                        if (myRule.ruleName == "alphanumeric") {
                            reg = new RegExp("^[A-Za-z0-9]*$");
                            if (!reg.test(el.value)) {
                                highlight(el, inputclasserror);
                                err = myRule.alertMsg
                            }
                        }
                        else {
                            if (myRule.ruleName == "alnumhyphen") {
                                reg = new RegExp("^[A-Za-z0-9-_]*$");
                                if (!reg.test(el.value)) {
                                    highlight(el, inputclasserror);
                                    err = myRule.alertMsg
                                }
                            }
                            else {
                                if (myRule.ruleName == "alnumhyphenat") {
                                    reg = new RegExp("^[A-Za-z0-9-_@]*$");
                                    if (!reg.test(el.value)) {
                                        highlight(el, inputclasserror);
                                        err = myRule.alertMsg
                                    }
                                }
                                else {
                                    if (myRule.ruleName == "alphaspace") {
                                        reg = new RegExp("^[A-Za-z0-9-_ \n\r\t]*$");
                                        if (!reg.test(el.value)) {
                                            highlight(el, inputclasserror);
                                            err = myRule.alertMsg
                                        }
                                    }
                                    else {
                                        if (myRule.ruleName == "email") {
                                            reg = new RegExp("^(([0-9a-zA-Z]+[-._+&])*[0-9a-zA-Z]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6}){0,1}$");
                                            if (!reg.test(el.value)) {
                                                highlight(el, inputclasserror);
                                                err = myRule.alertMsg
                                            }
                                        }
                                        else {
                                            if (myRule.ruleName == "maxlength") {
                                                if (isNaN(myRule.comparisonValue)) {
                                                    debug("DEBUG: comparisonValue for rule " + myRule.ruleName + " not a number")
                                                }
                                                else {
                                                    if (el.value.length > myRule.comparisonValue) {
                                                        highlight(el, inputclasserror);
                                                        err = myRule.alertMsg
                                                    }
                                                }
                                            }
                                            else {
                                                if (myRule.ruleName == "minlength") {
                                                    if (isNaN(myRule.comparisonValue)) {
                                                        debug("DEBUG: comparisonValue for rule " + myRule.ruleName + " not a number")
                                                    }
                                                    else {
                                                        if (el.value.length < myRule.comparisonValue) {
                                                            highlight(el, inputclasserror);
                                                            err = myRule.alertMsg
                                                        }
                                                    }
                                                }
                                                else {
                                                    if (myRule.ruleName == "numrange") {
                                                        reg = new RegExp("^[-+]{0,1}[0-9]*[.]{0,1}[0-9]*$");
                                                        if (!reg.test(unformatNumber(el.value))) {
                                                            highlight(el, inputclasserror);
                                                            err = myRule.alertMsg
                                                        }
                                                        else {
                                                            regRange = new RegExp("^[0-9]+-[0-9]+$");
                                                            if (!regRange.test(myRule.comparisonValue)) {
                                                                debug("DEBUG: comparisonValue for rule " + myRule.ruleName + " not in format number1-number2")
                                                            }
                                                            else {
                                                                rangeVal = myRule.comparisonValue.split("-");
                                                                if (eval(unformatNumber(el.value)) < eval(rangeVal[0]) || eval(unformatNumber(el.value)) > eval(rangeVal[1])) {
                                                                    highlight(el, inputclasserror);
                                                                    err = myRule.alertMsg
                                                                }
                                                            }
                                                        }
                                                    }
                                                    else {
                                                        if (myRule.ruleName == "regexp") {
                                                            reg = new RegExp(myRule.comparisonValue);
                                                            if (!reg.test(el.value)) {
                                                                highlight(el, inputclasserror);
                                                                err = myRule.alertMsg
                                                            }
                                                        }
                                                        else {
                                                            if (myRule.ruleName == "integer") {
                                                                err = checkInteger(el, myRule)
                                                            }
                                                            else {
                                                                if (myRule.ruleName == "double") {
                                                                    err = checkDouble(el, myRule)
                                                                }
                                                                else {
                                                                    if (myRule.ruleName == "date") {
                                                                        err = checkDate(el, myRule)
                                                                    }
                                                                    else {
                                                                        if (myRule.ruleName == "date_lt") {
                                                                            err = checkDateLessThan(el, myRule, false)
                                                                        }
                                                                        else {
                                                                            if (myRule.ruleName == "date_le") {
                                                                                err = checkDateLessThan(el, myRule, true)
                                                                            }
                                                                            else {
                                                                                if (myRule.ruleName == "keypress") {
                                                                                }
                                                                                else {
                                                                                    if (myRule.ruleName == "empty") {
                                                                                        if (el.value != null && el.value != "") {
                                                                                            highlight(el, inputclasserror);
                                                                                            err = myRule.alertMsg
                                                                                        }
                                                                                    }
                                                                                    else {
                                                                                        debug("DEBUG: rule " + myRule.ruleName + " not supported for " + el.type)
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    return err
}

function checkInteger(A, B){
    reg = new RegExp("^[-+]{0,1}[0-9]*$");
    if (!reg.test(A.value)) {
        highlight(A, inputclasserror);
        return B.alertMsg
    }
}

function checkDouble(B, C){
    var A = DECIMAL_SEP;
    reg = new RegExp("^[-+]{0,1}[0-9]*[" + A + "]{0,1}[0-9]*$");
    if (!reg.test(B.value)) {
        highlight(B, inputclasserror);
        return C.alertMsg
    }
}

function checkDate(B, C){
    error = null;
    if (B.value != "") {
        var A = DATE_FORMAT;
        ddReg = new RegExp("dd");
        MMReg = new RegExp("MM");
        yyyyReg = new RegExp("yyyy");
        if (!ddReg.test(A) || !MMReg.test(A) || !yyyyReg.test(A)) {
            debug("DEBUG: locale format " + A + " not supported")
        }
        else {
            ddStart = A.indexOf("dd");
            MMStart = A.indexOf("MM");
            yyyyStart = A.indexOf("yyyy")
        }
        strReg = A.replace("dd", "[0-9]{2}").replace("MM", "[0-9]{2}").replace("yyyy", "[0-9]{4}");
        reg = new RegExp("^" + strReg + "$");
        if (!reg.test(B.value)) {
            highlight(B, inputclasserror);
            error = C.alertMsg
        }
        else {
            dd = B.value.substring(ddStart, ddStart + 2);
            MM = B.value.substring(MMStart, MMStart + 2);
            yyyy = B.value.substring(yyyyStart, yyyyStart + 4);
            if (!checkddMMyyyy(dd, MM, yyyy)) {
                highlight(B, inputclasserror);
                error = C.alertMsg
            }
        }
    }
    return error
}

function checkDateLessThan(E, F, C){
    error = null;
    var B = checkDate(E, F) == null ? true : false;
    if (B && E.value != "") {
        var A = DATE_FORMAT;
        ddStart = A.indexOf("dd");
        MMStart = A.indexOf("MM");
        yyyyStart = A.indexOf("yyyy");
        dd = E.value.substring(ddStart, ddStart + 2);
        MM = E.value.substring(MMStart, MMStart + 2);
        yyyy = E.value.substring(yyyyStart, yyyyStart + 4);
        myDate = "" + yyyy + MM + dd;
        strReg = A.replace("dd", "[0-9]{2}").replace("MM", "[0-9]{2}").replace("yyyy", "[0-9]{4}");
        reg = new RegExp("^" + strReg + "$");
        var G = F.comparisonValue.indexOf("$") == 0 ? true : false;
        var D = "";
        if (G) {
            toSplit = F.comparisonValue.substr(1);
            tmp = toSplit.split(":");
            if (tmp.length == 2) {
                D = this.getField(f, tmp[0]).value
            }
            else {
                D = this.getField(f, F.comparisonValue.substr(1)).value
            }
        }
        else {
            D = F.comparisonValue
        }
        if (!reg.test(D)) {
            highlight(E, inputclasserror);
            error = F.alertMsg
        }
        else {
            cdd = D.substring(ddStart, ddStart + 2);
            cMM = D.substring(MMStart, MMStart + 2);
            cyyyy = D.substring(yyyyStart, yyyyStart + 4);
            cDate = "" + cyyyy + cMM + cdd;
            if (C) {
                if (!checkddMMyyyy(cdd, cMM, cyyyy) || myDate > cDate) {
                    highlight(E, inputclasserror);
                    error = F.alertMsg
                }
            }
            else {
                if (!checkddMMyyyy(cdd, cMM, cyyyy) || myDate >= cDate) {
                    highlight(E, inputclasserror);
                    error = F.alertMsg
                }
            }
        }
    }
    else {
        if (E.value != "") {
            highlight(E, inputclasserror);
            error = F.alertMsg
        }
    }
    return error
}

function checkEqual(B, C){
    error = null;
    var D = C.comparisonValue.indexOf("$") == 0 ? true : false;
    var A = "";
    if (D) {
        toSplit = C.comparisonValue.substr(1);
        tmp = toSplit.split(":");
        if (tmp.length == 2) {
            A = this.getField(f, tmp[0]).value
        }
        else {
            A = this.getField(f, C.comparisonValue.substr(1)).value
        }
    }
    else {
        A = C.comparisonValue
    }
    if (B.value != A) {
        highlight(B, inputclasserror);
        error = C.alertMsg
    }
    return error
}

function checkNotEqual(B, C){
    error = null;
    var D = C.comparisonValue.indexOf("$") == 0 ? true : false;
    var A = "";
    if (D) {
        toSplit = C.comparisonValue.substr(1);
        tmp = toSplit.split(":");
        if (tmp.length == 2) {
            A = this.getField(f, tmp[0]).value
        }
        else {
            A = this.getField(f, C.comparisonValue.substr(1)).value
        }
    }
    else {
        A = C.comparisonValue
    }
    if (B.value == A) {
        highlight(B, inputclasserror);
        error = C.alertMsg
    }
    return error
}

function checkddMMyyyy(A, C, B){
    retVal = true;
    if ((A < 1) || (A > 31) || (C < 1) || (C > 12) || (A == 31 && (C == 2 || C == 4 || C == 6 || C == 9 || C == 11)) || (A > 29 && C == 2) || (A == 29 && (C == 2) && ((B % 4 > 0) || (B % 4 == 0 && B % 100 == 0 && B % 400 > 0)))) {
        retVal = false
    }
    return retVal
}

function checkCheckbox(A, B){
    if (B.ruleName == "required") {
        if (!A.checked) {
            highlight(A, inputclasserror);
            return B.alertMsg
        }
    }
    else {
        if (B.ruleName == "equal") {
            if (!A.checked || A.value != B.comparisonValue) {
                highlight(A, inputclasserror);
                return B.alertMsg
            }
        }
        else {
            if (B.ruleName == "notequal") {
                if (!A.checked || A.value == B.comparisonValue) {
                    highlight(A, inputclasserror);
                    return B.alertMsg
                }
            }
            else {
                debug("DEBUG: rule " + B.ruleName + " not supported for " + A.type)
            }
        }
    }
}

function checkSelOne(A, C){
    if (C.ruleName == "required") {
        var B = false;
        var D = A.selectedIndex;
        if (D >= 0 && A.options[D].value) {
            B = true
        }
        if (!B) {
            highlight(A, inputclasserror);
            return C.alertMsg
        }
    }
    else {
        if (C.ruleName == "equal") {
            var B = false;
            var D = A.selectedIndex;
            if (D >= 0 && A.options[D].value == C.comparisonValue) {
                B = true
            }
            if (!B) {
                highlight(A, inputclasserror);
                return C.alertMsg
            }
        }
        else {
            if (C.ruleName == "notequal") {
                var B = false;
                var D = A.selectedIndex;
                if (D >= 0 && A.options[D].value != C.comparisonValue) {
                    B = true
                }
                if (!B) {
                    highlight(A, inputclasserror);
                    return C.alertMsg
                }
            }
            else {
                debug("DEBUG: rule " + C.ruleName + " not supported for " + A.type)
            }
        }
    }
}

function checkSelMul(B, D){
    if (D.ruleName == "required") {
        var C = false;
        opts = B.options;
        for (var A = 0; A < opts.length; A++) {
            if (opts[A].selected && opts[A].value) {
                C = true;
                break
            }
        }
        if (!C) {
            highlight(B, inputclasserror);
            return D.alertMsg
        }
    }
    else {
        if (D.ruleName == "equal") {
            var C = false;
            opts = B.options;
            for (var A = 0; A < opts.length; A++) {
                if (opts[A].selected && opts[A].value == D.comparisonValue) {
                    C = true;
                    break
                }
            }
            if (!C) {
                highlight(B, inputclasserror);
                return D.alertMsg
            }
        }
        else {
            if (D.ruleName == "notequal") {
                var C = false;
                opts = B.options;
                for (var A = 0; A < opts.length; A++) {
                    if (opts[A].selected && opts[A].value != D.comparisonValue) {
                        C = true;
                        break
                    }
                }
                if (!C) {
                    highlight(B, inputclasserror);
                    return D.alertMsg
                }
            }
            else {
                debug("DEBUG: rule " + D.ruleName + " not supported for " + B.type)
            }
        }
    }
}

function debug(A){
    if (debugmode) {
        alert(A)
    }
}

function strTrim(A){
    return A.replace(/^\s+/, "").replace(/\s+$/, "")
}

function makeRules(A){
    var C = new Array();
    if (A.length) {
        for (var B = 0; B < A.length; B++) {
            C[B] = splitRule(A[B])
        }
    }
    else {
        C[0] = splitRule(A)
    }
    return C
}

function splitRule(B){
    var A = null;
    if (B != undef) {
        params = B.split(RULE_SEP);
        switch (params.length) {
            case 2:
                A = new rule(params[0], params[1], null, null, null);
                break;
            case 3:
                if (threeParamRule(params[1])) {
                    A = new rule(params[0], params[1], params[2], null, null)
                }
                else {
                    if (params[2] == "pre-condition" || params[2] == "post-condition" || params[2] == "andor-operator") {
                        A = new rule(params[0], params[1], null, "foo", params[2])
                    }
                    else {
                        A = new rule(params[0], params[1], null, params[2], null)
                    }
                }
                break;
            case 4:
                if (threeParamRule(params[1]) && (params[3] == "pre-condition" || params[3] == "post-condition" || params[3] == "andor-operator")) {
                    A = new rule(params[0], params[1], params[2], "foo", params[3])
                }
                else {
                    A = new rule(params[0], params[1], params[2], params[3], null)
                }
                break;
            default:
                debug("DEBUG: wrong definition of rule")
        }
    }
    return A
}

function threeParamRule(A){
    return (A == "equal" || A == "notequal" || A == "minlength" || A == "maxlength" || A == "date_lt" || A == "date_le" || A == "implies" || A == "regexp" || A == "numrange" || A == "keypress" || A == "and" || A == "or") ? true : false
}

function highlight(B, A){
    if (isFocusSet && A == inputclasserror) {
        B.focus();
    }
    if (B != undef && inputhighlight) {
        if (multipleclassname) {
            highlightMultipleClassName(B, A)
        }
        else {
            B.className = A
        }
    }
}

function highlightMultipleClassName(B, A){
    re = new RegExp("(^|\\s)(" + inputclassnormal + "|" + inputclasserror + ")($|\\s)");
    B.className = strTrim(((typeof B.className != "undefined") ? B.className.replace(re, "") : "") + " " + A)
}

function getDefaultMessage(B, A, D, C){
    if (A.length == 0) {
        A = B
    }
    var E = DEFAULT_MSG;
    if (D == "required") {
        E = REQUIRED_MSG.replace("{1}", A)
    }
    else {
        if (D == "minlength") {
            E = MINLENGTH_MSG.replace("{1}", A).replace("{2}", C)
        }
        else {
            if (D == "maxlength") {
                E = MAXLENGTH_MSG.replace("{1}", A).replace("{2}", C)
            }
            else {
                if (D == "numrange") {
                    E = NUMRANGE_MSG.replace("{1}", A).replace("{2}", C)
                }
                else {
                    if (D == "date") {
                        E = DATE_MSG.replace("{1}", A)
                    }
                    else {
                        if (D == "numeric") {
                            E = NUMERIC_MSG.replace("{1}", A)
                        }
                        else {
                            if (D == "integer") {
                                E = INTEGER_MSG.replace("{1}", A)
                            }
                            else {
                                if (D == "double") {
                                    E = DOUBLE_MSG.replace("{1}", A)
                                }
                                else {
                                    if (D == "equal") {
                                        E = EQUAL_MSG.replace("{1}", A).replace("{2}", getComparisonDisplayed(C))
                                    }
                                    else {
                                        if (D == "notequal") {
                                            E = NOTEQUAL_MSG.replace("{1}", A).replace("{2}", getComparisonDisplayed(C))
                                        }
                                        else {
                                            if (D == "alphabetic") {
                                                E = ALPHABETIC_MSG.replace("{1}", A)
                                            }
                                            else {
                                                if (D == "alphanumeric") {
                                                    E = ALPHANUMERIC_MSG.replace("{1}", A)
                                                }
                                                else {
                                                    if (D == "alnumhyphen") {
                                                        E = ALNUMHYPHEN_MSG.replace("{1}", A)
                                                    }
                                                    else {
                                                        if (D == "alnumhyphenat") {
                                                            E = ALNUMHYPHENAT_MSG.replace("{1}", A)
                                                        }
                                                        else {
                                                            if (D == "alphaspace") {
                                                                E = ALPHASPACE_MSG.replace("{1}", A)
                                                            }
                                                            else {
                                                                if (D == "email") {
                                                                    E = EMAIL_MSG.replace("{1}", A)
                                                                }
                                                                else {
                                                                    if (D == "regexp") {
                                                                        E = REGEXP_MSG.replace("{1}", A).replace("{2}", C)
                                                                    }
                                                                    else {
                                                                        if (D == "date_lt") {
                                                                            E = DATE_LT_MSG.replace("{1}", A).replace("{2}", getComparisonDisplayed(C))
                                                                        }
                                                                        else {
                                                                            if (D == "date_le") {
                                                                                E = DATE_LE_MSG.replace("{1}", A).replace("{2}", getComparisonDisplayed(C))
                                                                            }
                                                                            else {
                                                                                if (D == "empty") {
                                                                                    E = EMPTY_MSG.replace("{1}", A)
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    return E
}

function getComparisonDisplayed(A){
    comparisonDisplayed = A;
    if (A.substring(0, 1) == "$") {
        A = A.substring(1, A.length);
        tmp = A.split(":");
        if (tmp.length == 2) {
            comparisonDisplayed = tmp[1]
        }
        else {
            comparisonDisplayed = A
        }
    }
    return comparisonDisplayed
}

function getBrowser(){
    brs = navigator.userAgent.toLowerCase();
    var A;
    if (brs.search(/msie\s(\d+(\.?\d)*)/) != -1) {
        A = "msie"
    }
    else {
        if (brs.search(/netscape[\/\s](\d+([\.-]\d)*)/) != -1) {
            A = "netscape"
        }
        else {
            if (brs.search(/firefox[\/\s](\d+([\.-]\d)*)/) != -1) {
                A = "firefox"
            }
            else {
                A = "unknown"
            }
        }
    }
    return A
}

function isKeyAllowed(D, B){
    retval = false;
    var A;
    if (D == 8) {
        retval = true
    }
    else {
        for (var C = 0; C < B.length; C++) {
            A = B.charCodeAt(C);
            if (A == D) {
                retval = true;
                break
            }
        }
    }
    return retval
}

function getField(B, C){
	var A = null;
	try{
		if (B.elements[C]) {
	        A = B.elements[C]
	    }
	    else {
	        if (document.getElementById(C)) {
	            A = document.getElementById(C)
	        }
	    }
	}catch(e){
		if(document.getElementById(C)){
			A = document.getElementById(C);
		}
	}
    return A
}

function unformatNumber(B){
    var A = B.replace(THOUSAND_SEP, "");
    A = A.replace(DECIMAL_SEP, ".");
    return A
}

function deleteInline(A){
    if (A.indexOf("__inline__") == -1) {
        return A
    }
    else {
        return A.substring(0, A.indexOf("__inline__"))
    }
}
