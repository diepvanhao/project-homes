

function datepicker() {
    j("#datepicker").datepicker();
}
function birthday(val) {
    j("#" + val).datepicker({
        changeYear: true,
        showButtonPanel: true,
        yearRange: '-90:+50',
        dateFormat:'mm/dd/yy'
    });
}
function timepicker(val){
    j('#'+val).timepicker();
}

// Hide function by changing <div> style display attribute back to none
function hideloadgif() {
    j('#loadgif').css('display','none');
}

// Show function by changing <div> style display attribute from none to block.
function showloadgif() {
    j('#loadgif').css('display','block');
}

// Making sure that any other event running in the background isn't affected
/*if (window.addEventListener) { // Mozilla, Netscape, Firefox
 window.addEventListener('load', WindowLoad, false);
 } else if (window.attachEvent) { // IE
 window.attachEvent('onload', WindowLoad);
 }*/

// Call the hideloadgif() function on click event,
// with interval time set to 3 seconds to hide the <div>
function WindowLoad(click) {
    setInterval("hideloadgif()", 3000)
}