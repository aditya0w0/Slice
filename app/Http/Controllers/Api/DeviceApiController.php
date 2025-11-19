<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class DeviceApiController extends Controller
{
    // GET /api/admin/devices
    public function index(Request $request)
    {
        // authorize
        $this->authorize('viewAny', Device::class);

        // return devices grouped by family for admin listing
        $devices = Device::orderBy('family')->orderBy('name')->get();
        return response()->json(['data' => $devices]);
    }

    // Public search endpoint for frontend filtering (no auth checks)
    public function search(Request $request)
    {
        $query = Device::query();

        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        if ($request->filled('q')) {
            $q = $request->query('q');
            $query->where(function ($q2) use ($q) {
                $q2->where('name', 'like', "%{$q}%")
                    ->orWhere('family', 'like', "%{$q}%")
                    ->orWhere('slug', 'like', "%{$q}%");
            });
        }

        // Default returns devices with only essential fields for the UI
        $devices = $query->orderBy('name')->limit(200)->get(['id','name','slug','family','category','image','price_monthly']);

        return response()->json(['data' => $devices]);
    }

    // GET /api/admin/devices/{id}
    public function show($id)
    {
        $device = Device::findOrFail($id);
        $this->authorize('view', $device);
        return response()->json(['data' => $device]);
    }

    // POST /api/admin/devices
    public function store(Request $request)
    {
        $this->authorize('create', Device::class);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'family' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'price_monthly' => 'nullable|numeric',
            'image' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        if (empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        }

        $device = Device::create($data);
        return response()->json(['data' => $device], Response::HTTP_CREATED);
    }

    // PUT /api/admin/devices/{id}
    public function update(Request $request, $id)
    {
        $device = Device::findOrFail($id);
        $this->authorize('update', $device);
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|nullable|string|max:255',
            'family' => 'sometimes|nullable|string|max:255',
            'category' => 'sometimes|nullable|string|max:100',
            'price_monthly' => 'sometimes|nullable|numeric',
            'image' => 'sometimes|nullable|string',
            'description' => 'sometimes|nullable|string',
        ]);

        if (isset($data['name']) && empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        }

        $device->update($data);
        return response()->json(['data' => $device]);
    }

    // DELETE /api/admin/devices/{id}
    public function destroy($id)
    {
        $device = Device::findOrFail($id);
        $this->authorize('delete', $device);
        $device->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
