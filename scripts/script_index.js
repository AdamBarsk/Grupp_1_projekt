$(function() {
  $("#check-in-date, #check-out-date").datepicker({
    inline: true,
    showOtherMonths: true,
    dateFormat: "yy-mm-dd",
    dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
  });
});

var date = new Date();

var day = date.getDate();
var tomorrow = date.getDate() + 1;
var month = date.getMonth() + 1;
var year = date.getFullYear();

if (month < 10) month = "0" + month;
if (day < 10) day = "0" + day;

var today = year + "-" + month + "-" + day;
var tomorrow = year + "-" + month + "-" + tomorrow;

document.getElementById("check-in-date").value = today;
document.getElementById("check-out-date").value = tomorrow;
