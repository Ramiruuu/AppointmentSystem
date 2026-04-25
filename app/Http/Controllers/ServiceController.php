<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('name')->get();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'price'            => ['nullable', 'numeric', 'min:0'],
            'is_active'        => ['boolean'],
        ]);

        Service::create([
            'name'             => $request->name,
            'description'      => $request->description,
            'duration_minutes' => $request->duration_minutes,
            'price'            => $request->price,
            'is_active'        => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'price'            => ['nullable', 'numeric', 'min:0'],
            'is_active'        => ['boolean'],
        ]);

        $service->update([
            'name'             => $request->name,
            'description'      => $request->description,
            'duration_minutes' => $request->duration_minutes,
            'price'            => $request->price,
            'is_active'        => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return back()->with('success', 'Service deleted.');
    }

    public function toggleActive(Service $service)
    {
        $service->update(['is_active' => !$service->is_active]);
        return back()->with('success', 'Service status toggled.');
    }
}