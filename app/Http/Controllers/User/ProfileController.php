<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $orders = $user->orders()->with('items.product')->orderBy('created_at', 'desc')->get(); // Fetch user orders
        return view('profile.edit', compact('user', 'orders'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'nullable|string|max:15',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'addresses.*' => 'nullable|string|max:255',
            'default_address' => 'nullable|integer|exists:addresses,id', // Validate default address
        ]);

        $data = $request->only(['name', 'email', 'phone']);
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = '/storage/' . $avatarPath;
        }

        $user->update($data);

        // Update addresses
        $user->addresses()->delete();
        if ($request->addresses) {
            foreach ($request->addresses as $address) {
                if (!empty($address)) {
                    $user->addresses()->create(['address' => $address]);
                }
            }
        }

        // Set default address
        if ($request->default_address) {
            $user->addresses()->update(['default' => false]); // Reset all to false
            $user->addresses()->where('id', $request->default_address)->update(['default' => true]);
        }

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }
}
