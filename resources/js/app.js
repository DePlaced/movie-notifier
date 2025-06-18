import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import "flatpickr/dist/themes/material_blue.css";

document.addEventListener("DOMContentLoaded", () => {
  flatpickr(".datepicker", {
    dateFormat: "Y/m/d H:i",
    altInput: true,
    altFormat: "d/m/Y H:i",
    enableTime: true,
    time_24hr: true,

    onClose: (selectedDates, dateStr, instance) => {
      const form = instance._input.closest("form");
      if (form) form.submit();
    },

    onReady: function (selectedDates, dateStr, instance) {
      const calendar = instance.calendarContainer;
      const timeContainer = calendar.querySelector(".flatpickr-time");

      if (timeContainer && !calendar.querySelector(".flatpickr-today-inline")) {

        // Clear button
        const clearButton = document.createElement("button");
        clearButton.type = "button";
        clearButton.textContent = "Clear";
        clearButton.onclick = () => {
          instance.clear();
        };

        // Today button
        const todayButton = document.createElement("button");
        todayButton.type = "button";
        todayButton.textContent = "Today";
        todayButton.onclick = () => {
          instance.setDate(new Date(), true);
        };

        todayButton.className =
        "ml-2 px-3 py-1 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700 transition";

        clearButton.className =
        "ml-2 px-3 py-1 text-sm font-medium text-red-600 bg-red-100 rounded hover:bg-red-200 transition";


        timeContainer.appendChild(clearButton);
        timeContainer.appendChild(todayButton);

      }
    }
  });
});
