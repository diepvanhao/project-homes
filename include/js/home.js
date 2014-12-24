

function datepicker() {
    j("#datepicker").datepicker();
}
function birthday(val) {
    j("#" + val).datepicker({
        changeYear: true,
        showButtonPanel: true,
        yearRange: '-90:+50',
        dateFormat:'yy/mm/dd',
        onSelect:function(selectedDate){
            if(this.id=='contract_period_from'){
                 var str = $("#contract_period_from").val();
                 var parts = str.split("/");
                 var year = parts[0] && parseInt( parts[0], 10 );
                 var day = parts[2] && parseInt( parts[2], 10 );
                 var month = parts[1] && parseInt( parts[1], 10 );
                 var yearEnd=year+2;
                $('#contract_period_to').val( yearEnd + "/" + month + "/" + day );
            }
            if(this.id=='date_from'){
                selectDate();
            }
            if(this.id=='date_to'){
                selectDate();
            }
            if(this.id=='expire_from'){
                selectDate();
            }
            if(this.id=='expire_to'){
                selectDate();
            }
        }
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