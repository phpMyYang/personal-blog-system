<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Validation\Rule; 
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * Ipakita ang profile management view.
     */
    public function edit(Request $request)
    {
        return view('dashboard.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Handle ang pag-update ng Personal Info o Password.
     * Gagamit tayo ng check para malaman kung anong form ang ginagamit.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        // PATH A: UPDATE PASSWORD
        if ($request->has('current_password')) {
            return $this->updatePassword($request, $user);
        }

        // PATH B: UPDATE NAME, EMAIL, AVATAR
        // 1. Validation
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                // unique ang email, pero i-ignore ang kasalukuyang email ng user.
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);
        } catch (ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            return redirect()->back()->withInput()->with('error', $firstError);
        }

        // 2. Handle Image Deletion
        if ($request->has('remove_avatar') && $user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
            $user->avatar_path = null;
        }

        // 3. Handle New Avatar Upload
        if ($request->hasFile('avatar')) {
            // Burahin ang luma kung meron
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            // I-save ang bago
            $imagePath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar_path = $imagePath;
        }

        // 4. Update Name at Email
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    /**
     * Handles password update logic.
     */
    protected function updatePassword(Request $request, User $user)
    {
        // 1. Validation
        try {
            $request->validate([
                'current_password' => 'required|string',
                'password' => ['required', 'confirmed', Password::min(8)],
            ]);
        } catch (ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            return redirect()->back()->withInput()->with('error', $firstError);
        }

        // 2. Tiyakin na TAMA ang kasalukuyang password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'The provided current password does not match our records.');
        }

        // 3. Update Password
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Password changed successfully.');
    }

    /**
     * Handle account deletion.
     */
    public function destroy(Request $request)
    {
        $user = $request->user();

        // 1. Burahin ang lahat ng files sa storage
        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        // 2. Burahin ang user (Awtomatikong buburahin ang posts at tokens dahil sa cascade)
        Auth::logout();
        $user->delete();

        // 3. Redirect sa homepage
        return redirect()->route('home')->with('success', 'Your account has been permanently deleted.');
    }
}