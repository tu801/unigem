$("#voucher_start_date")
  .datepicker({
    todayHighlight: true,
    format: "dd-mm-yyyy",
    autoclose: true,
  })
  .on("changeDate", function (e) {
    // Cập nhật startDate của voucher_end_date khi voucher_start_date thay đổi
    var selectedDate = e.date;
    $("#voucher_end_date").datepicker("setStartDate", selectedDate);

    // Nếu voucher_end_date hiện tại nhỏ hơn voucher_start_date mới, clear giá trị
    var currentEndDate = $("#voucher_end_date").datepicker("getDate");
    if (currentEndDate && currentEndDate < selectedDate) {
      $("#voucher_end_date").datepicker("clearDates");
    }
  });

$("#voucher_end_date").datepicker({
  todayHighlight: true,
  format: "dd-mm-yyyy",
  autoclose: true,
  startDate: $("#voucher_start_date").val()
    ? new Date($("#voucher_start_date").val().split("-").reverse().join("-"))
    : new Date(),
});
