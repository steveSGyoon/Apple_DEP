// 숫자 타입에서 쓸 수 있도록 format()
Number.prototype.format = function(){
    if(this==0) return 0;
 
    var reg = /(^[+-]?\d+)(\d{3})/;
    var n = (this + '');
 
    while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');
 
    return n;
};
 
// 문자열 타입에서 쓸 수 있도록 format()
String.prototype.format = function(){
    var num = parseFloat(this);
    if( isNaN(num) ) return "0";
 
    return num.format();
};

function popupOpen(evt, href, name = "popup", width = 1440, height = 960) {
    evt.preventDefault();
    window.open(href, name, 'width=' + width + ', height=' + height +'');
}

function newOpen(evt, href) {
    evt.preventDefault();
    window.open(href, "_blank");
}

/**
 * JAVASCRIPT
 * SET COOKIE
 *
 * @param cname
 * @param cvalue
 * @param exdays
 */
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

/**
 * JAVASCRIPT
 * GET COOKIE
 *
 * @param cname
 * @returns {string}
 */
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
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

/**
 * JAVASCRIPT
 * DELETE COOKIE ('c_value' => '')
 *
 * @param cname
 * @returns {boolean}
 */
function delCookie(cname) {
    var cookieName = getCookie(cname);

    if (cookieName === "") {
        return false;
    } else {
        setCookie(cname.toString(), "", 0);
        return true;
    }
}

/**
 * JAVASCRIPT
 * CHECK COOKIE
 *
 * @param cname
 * @returns {boolean}
 */
function checkCookie(cname) {
    var cookieName = getCookie(cname);

    if (cookieName !== "" && cookieName != null)
        return true;
    else
        return false;
}

/**
* JAVASCRIPT
* CHECK CURRENT LOGIN ID OF COOKIE
*
* @returns {boolean}
*/
function isSetCookieOfLoginID() {
    return checkCookie('SAVEID');
}
/**
* JAVASCRIPT
* GET COOKIE OF LOGIN ID
* @returns {boolean}
*/
function getCookieOfLoginID() {
    return getCookie('SAVEID');
}
/**
* JAVASCRIPT
* SET COOKIE OF LOGIN ID
*/
function setCookieOfLoginID(loginID) {
    delCookie('SAVEID');
    setCookie('SAVEID', loginID, 365);
}
/**
* JAVASCRIPT
* DELETE COOKIE OF LOGIN ID
*/
function deleteCookieOfLoginID() {
    delCookie('SAVEID');
}