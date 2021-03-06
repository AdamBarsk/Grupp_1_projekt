$(function() {
  $("#check-in-date").datepicker({
      inline: true,
      showOtherMonths: true,
      firstDay: 1,
      dateFormat: "yy-mm-dd",
      dayNamesMin: ["Sön", "Mån", "Tis", "Ons", "Tor", "Fre", "Lör"],
      minDate: today,
      onSelect: function (date) {
          var date2 = $("#check-in-date").datepicker("getDate");
          date2.setDate(date2.getDate() + 1);
          $("#check-out-date").datepicker("setDate", date2);
          //sets minDate to checkin date + 1
          $("#check-out-date").datepicker("option", "minDate", date2);
      }

  });
  $("#check-out-date").datepicker({
      inline: true,
      showOtherMonths: true,
      firstDay: 1,
      dateFormat: "yy-mm-dd",
      dayNamesMin: ["Sön", "Mån", "Tis", "Ons", "Tor", "Fre", "Lör"],
      minDate: tomorrow,
      onClose: function () {
          var checkin = $("#check-in-date").datepicker("getDate");
          var checkout = $("#check-out-date").datepicker("getDate");
          //check to prevent a user from entering a date below date of checkin
          if (checkout <= checkin) {
              var minDate = $("#check-out-date").datepicker("option", "minDate");
              $("#check-out-date").datepicker("setDate", minDate);
          }
      }
  });
});

var date = new Date();

var day = date.getDate();
var tomorrow = date.getDate() + 1;
var month = date.getMonth() + 1;
var tomorrowMonth = date.getMonth() + 1;
var year = date.getFullYear();

  if (month < 10) month = "0" + month;
  if (day < 10) day = "0" + day;
  if (tomorrowMonth < 10) tomorrowMonth = "0" + tomorrowMonth;
  if (tomorrow < 10) tomorrow = "0" + tomorrow;


var today = year + "-" + month + "-" + day;
var tomorrow = year + "-" + tomorrowMonth + "-" + tomorrow;

document.getElementById("check-in-date").value = today;
document.getElementById("check-out-date").value = tomorrow;

var selects = document.getElementsByTagName("select");

$('#search').click(function bookRooms(){
 localStorage.setItem("checkin", document.getElementById("check-in-date").value);
 localStorage.setItem("checkout", document.getElementById("check-out-date").value);
 localStorage.setItem("doublerooms", selects[0].value);
 localStorage.setItem("singlerooms", selects[1].value);
 localStorage.setItem("familyrooms", selects[2].value);
});
