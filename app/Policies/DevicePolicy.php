<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Device;

class DevicePolicy
{
    /**
     * Determine whether the user can view any devices (list).
     */
    public function viewAny(?User $user): bool
    {
        // allow only authenticated admins
        if (! $user) return false;
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can view a device.
     */
    public function view(?User $user, Device $device): bool
    {
        if (! $user) return false;
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can create devices.
     */
    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can update the device.
     */
    public function update(User $user, Device $device): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Determine whether the user can delete the device.
     */
    public function delete(User $user, Device $device): bool
    {
        return $this->isAdmin($user);
    }

    protected function isAdmin(User $user): bool
    {
        // Prefer explicit is_admin attribute if present, otherwise consult config list
        if (isset($user->is_admin) && $user->is_admin) return true;
        $admins = config('slice.admins', []);
        return in_array($user->email, $admins, true);
    }
}
