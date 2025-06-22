<!DOCTYPE html>
<html>
    <head>
    <title>SweetAlert2 + Flatpickr</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    </head>
    <body>
        <form method="POST" action="{{ route('time.store') }}" class="flex flex-row gap-4">
            @csrf
            <input type="hidden" name="id" value="{{ $id }}">

            <input
            type="text"
            name="date_at"
            class="dateTimePicker w-64 rounded-md border border-gray-300 px-3 py-2 shadow-sm text-sm placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
            placeholder="Select date"
            value="{{ Carbon\Carbon::now()->format('Y-m-d H:i') }}"
            data-submit-on-close="true"
            />

        </form>

        <button onclick="openSwal()">
            Create time
        </button>

        <script>
            // Ensure the DOM is fully loaded before attaching event listeners
            document.addEventListener('DOMContentLoaded', function () {
                initializeFlatpickr();
            });

          function openSwal() {
            console.log('openSwal called');
            fetch('{{ route('time.create') }}')
              .then(response => {
                if (!response.ok) {
                  throw new Error('Network response was not OK');
                }
                return response.text();
              })
              .then(html => {
                Swal.fire({
                  title: 'Select a time',
                  html: html,
                  showCancelButton: true,
                  confirmButtonText: 'Save',
                  cancelButtonText: 'Cancel',
                  didOpen: () => {
                    initializeFlatpickr();
                  },
                  preConfirm: () => {
                    const time = document.querySelector('.dateTimePicker').value;
                    if (!time) {
                      Swal.showValidationMessage('Please select a time');
                    }
                    return time;
                  }
                }).then(result => {
                  if (result.isConfirmed) {
                    Swal.fire('Time Saved', `You selected: ${result.value}`, 'success');
                  }
                });
              })
              .catch(error => {
                console.error('Failed to load time picker view:', error);
              });
          }

         function initializeFlatpickr() {
          document.querySelectorAll(".dateTimePicker").forEach((input) => {
            // Ensure the input is not already initialized
            if (input._flatpickr) {
              input._flatpickr.destroy();
            }


            const submitOnClose = input.dataset.submitOnClose === "true";
            let originalValue = input.value;

            const config = {
              enableTime: true,
              dateFormat: "Y/m/d H:i",
              altInput: true,
              altFormat: "d/m/Y H:i",
              time_24hr: true,

              onReady: function (selectedDates, dateStr, instance) {
                const calendar = instance.calendarContainer;
                const timeContainer = calendar.querySelector(".flatpickr-time");

                if (timeContainer && !calendar.querySelector(".flatpickr-today-inline")) {
                  const clearButton = document.createElement("button");
                  clearButton.type = "button";
                  clearButton.textContent = "Clear";
                  clearButton.onclick = () => instance.clear();

                  const todayButton = document.createElement("button");
                  todayButton.type = "button";
                  todayButton.textContent = "Today";
                  todayButton.onclick = () => instance.setDate(new Date(), true);

                  clearButton.className =
                    "text-sm text-red-600 bg-red-100 rounded px-3 h-8 hover:bg-red-200 transition";
                  todayButton.className =
                    "text-sm text-white bg-blue-500 rounded px-3 h-8 hover:bg-blue-600 transition";

                  timeContainer.style.display = "flex";
                  timeContainer.style.justifyContent = "center";
                  timeContainer.style.alignItems = "center";
                  timeContainer.style.gap = "0.5rem";

                  timeContainer.appendChild(clearButton);
                  timeContainer.appendChild(todayButton);
                }
              }
            };

            if (submitOnClose) {
              config.onOpen = function (selectedDates, dateStr, instance) {
                originalValue = instance.input.value;
              };
              config.onClose = function (selectedDates, dateStr, instance) {
                if (instance.input.value !== originalValue) {
                  const form = instance.input.closest("form");
                  if (form) form.submit();
                }
              };
            }

            flatpickr(input, config);
          });
        }
        </script>
    </body>
</html>