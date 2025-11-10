<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $devices = Device::orderBy('family')->orderBy('name')->paginate(20);
        return view('admin.devices.index', compact('devices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.devices.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:devices',
            'family' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|string|max:500',
            'description' => 'nullable|string',
        ]);

        Device::create($validated);

        return redirect()->route('admin.devices.index')->with('success', 'Device created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Device $device)
    {
        return view('admin.devices.show', compact('device'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Device $device)
    {
        return view('admin.devices.edit', compact('device'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Device $device)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:devices,slug,' . $device->id,
            'family' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|string|max:500',
            'description' => 'nullable|string',
        ]);

        $device->update($validated);

        return redirect()->route('admin.devices.index')->with('success', 'Device updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
    {
        $device->delete();

        return redirect()->route('admin.devices.index')->with('success', 'Device deleted successfully!');
    }
}
