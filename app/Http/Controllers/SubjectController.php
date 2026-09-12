<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate(['name' => ['required', 'string', 'max:80', 'unique:subjects,name,NULL,id,user_id,' . Auth::id()]]);
        Subject::create(['user_id' => Auth::id(), 'name' => $validated['name']]);

        return back()->with('success', 'Subject added successfully.');
    }
}
