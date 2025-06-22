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
            class="timePicker w-64 rounded-md border border-gray-300 px-3 py-2 shadow-sm text-sm placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
            placeholder="Select date"
            value="{{ Carbon\Carbon::now()->format('Y-m-d H:i') }}"
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
                    const time = document.querySelector('.timePicker').value;
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
              flatpickr(".timePicker", {
                enableTime: true,                // Enables time selection
                dateFormat: "Y/m/d H:i",         // Submitted value format (e.g. 2025/06/22 14:30
                altInput: true,                  // Shows a prettier version to the user
                altFormat: "d/m/Y H:i",          // User-friendly format (e.g. 22/06/2025 14:30)
                time_24hr: true,                  // 24-hour time instead of AM/PM
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

                    // Clear button style (with Tailwind colors)
                    clearButton.className =
                      "text-sm text-red-600 bg-red-100 rounded px-3 h-8 hover:bg-red-200 transition";  // h-8 adjusts height, px and py adjust spacing

                    // Today button style (with Tailwind colors)
                    todayButton.className =
                      "text-sm text-white bg-blue-500 rounded px-3 h-8 hover:bg-blue-600 transition";  // h-8 adjusts height, px and py adjust spacing

                    // Apply flexbox styling to the time container to center the buttons vertically
                    timeContainer.style.display = "flex"; // Enable flexbox
                    timeContainer.style.justifyContent = "center"; // Center buttons horizontally
                    timeContainer.style.alignItems = "center"; // Center buttons vertically
                    timeContainer.style.gap = "0.5rem"; // Add some space between buttons

                    
                    timeContainer.appendChild(clearButton);
                    timeContainer.appendChild(todayButton);

                  }
                }
              });
          }
        </script>
    </body>
</html>