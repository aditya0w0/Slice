<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Order;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $families = Device::distinct()->whereNotNull('family')->pluck('family');
        return view('admin.devices.create', compact('families'));
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:devices',
            'family' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'price_monthly' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048', // Validate as image, max 2MB
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('devices', 'public');
            $validated['image'] = '/storage/' . $path;
        }

        // Smart Family Inference
        if (empty($validated['family'])) {
            $validated['family'] = $this->inferFamily($validated['name']);
        }

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
        $families = Device::distinct()->whereNotNull('family')->pluck('family');
        return view('admin.devices.edit', compact('device', 'families'));
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
            'category' => 'nullable|string|max:255',
            'price_monthly' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048', // Validate as image, max 2MB
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('devices', 'public');
            $validated['image'] = '/storage/' . $path;
        }

        // Smart Family Inference (only if explicitly cleared or not set)
        if (empty($validated['family'])) {
            $validated['family'] = $this->inferFamily($validated['name']);
        }

        $device->update($validated);

        return redirect()->route('admin.devices.index')->with('success', 'Device updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
    {
        Log::info('Device destroy called for: ' . $device->name . ' (' . $device->slug . ')');

        // Check if device is referenced in orders or cart items
        $orderCount = Order::where('variant_slug', $device->slug)->count();
        $cartCount = CartItem::where('variant_slug', $device->slug)->count();

        Log::info('Device references - Orders: ' . $orderCount . ', Cart: ' . $cartCount);

        if ($orderCount > 0 || $cartCount > 0) {
            Log::info('Preventing deletion due to references');
            return redirect()->route('admin.devices.index')->with('error', 'Cannot delete device. It is referenced by ' . $orderCount . ' order(s) and ' . $cartCount . ' cart item(s).');
        }

        try {
            $device->delete();
            Log::info('Device deleted successfully');
            return redirect()->route('admin.devices.index')->with('success', 'Device deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting device: ' . $e->getMessage());
            return redirect()->route('admin.devices.index')->with('error', 'Cannot delete device. It may be linked to existing orders or carts.');
        }
    }

    /**
     * Infer family from device name.
     */
    private function inferFamily(string $name): string
    {
        // iPhone logic: "iPhone 15 Pro Max" -> "iPhone 15"
        if (preg_match('/^(iPhone\s+\d+)/i', $name, $matches)) {
            return $matches[1];
        }

        // iPhone SE logic
        if (stripos($name, 'iPhone SE') !== false) {
            return 'iPhone SE';
        }

        // iPad logic: Use full name (as per granular display requirement)
        if (stripos($name, 'iPad') !== false) {
            return $name;
        }

        // Default: Use the name itself as the family
        return $name;
    }
}
