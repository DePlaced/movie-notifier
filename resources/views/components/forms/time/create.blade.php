@vite(['resources/css/app.css', 'resources/js/app.js'])

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
