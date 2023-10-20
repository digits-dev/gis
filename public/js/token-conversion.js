function formatDateToYYYYMMDD(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");
    return `${year}-${month}-${day}`;
}
$("#start_date, #end_date").attr("type", "date");
const startDateInput = $("#start_date");
const endDateInput = $("#end_date");
const currentDate = new Date();
startDateInput.attr("min", formatDateToYYYYMMDD(currentDate));

startDateInput.on("change", function () {
    endDateInput.attr("readonly", false);
    endDateInput.val("");
    const minForEndDate = $("#start_date").val();
    endDateInput.attr("min", minForEndDate);
});
