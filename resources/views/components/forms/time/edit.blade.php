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
            class="datepicker w-64 rounded-md border border-gray-300 px-3 py-2 shadow-sm text-sm placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
            placeholder="Select date"
            value="{{ Carbon\Carbon::now()->format('Y-m-d H:i') }}"
            />

        </form>

        <button onclick="openSwal()">
            Create time
        </button>

        <script>
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
                    flatpickr("#timePicker", {
                      enableTime: true,                // Enables time selection
                      dateFormat: "Y/m/d H:i",         // Submitted value format (e.g. 2025/06/22 14:30)
                      defaultDate: new Date(),         // Pre-fill with current date & time
                      altInput: true,                  // Shows a prettier version to the user
                      altFormat: "d/m/Y H:i",          // User-friendly format (e.g. 22/06/2025 14:30)
                      time_24hr: true                  // 24-hour time instead of AM/PM
                    });

                  },
                  preConfirm: () => {
                    const time = document.getElementById('timePicker').value;
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
        </script>
    </body>
</html>