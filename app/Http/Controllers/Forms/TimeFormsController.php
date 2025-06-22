<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;

class TimeFormsController extends Controller
{
    /**
     * Store a newly created time entry in storage.
     */
    public function store()
    {
        // Logic to store the new time entry
        // Validate and save the data
        return redirect()->route('dashboard')->with('success', 'Time entry created successfully.');
    }

}
