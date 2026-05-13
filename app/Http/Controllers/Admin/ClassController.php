<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::withCount(['subjects', 'students'])->latest()->get();
        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('admin.classes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:classes',
            'description' => 'nullable|string',
        ]);

        SchoolClass::create($request->only('name', 'description'));
        return redirect()->route('admin.classes.index')->with('success', 'Class created successfully.');
    }

    public function edit(SchoolClass $class)
    {
        return view('admin.classes.edit', compact('class'));
    }

    public function update(Request $request, SchoolClass $class)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:classes,name,' . $class->id,
            'description' => 'nullable|string',
        ]);

        $class->update($request->only('name', 'description'));
        return redirect()->route('admin.classes.index')->with('success', 'Class updated successfully.');
    }

    public function destroy(SchoolClass $class)
    {
        $class->delete();
        return redirect()->route('admin.classes.index')->with('success', 'Class deleted.');
    }
}
