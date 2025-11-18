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
use App\Models\Comment;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Ipakita ang profile management view, kasama ang stats.
     */
    public function edit(Request $request)
    {
        $user = $request->user();
        $selectedYear = $request->query('year', date('Y')); 
        $availableYears = $user->posts()
                              ->select(DB::raw('YEAR(created_at) as year'))
                              ->distinct()
                              ->orderBy('year', 'desc')
                              ->pluck('year')
                              ->all();
        
        if (!in_array(date('Y'), $availableYears)) {
            array_unshift($availableYears, date('Y'));
        }

        $totalPosts = $user->posts()->count();
        $allPostIds = $user->posts()->pluck('id'); 
        $allCommentsQuery = Comment::whereIn('post_id', $allPostIds); 
        $commentStats = [
            'approved' => (clone $allCommentsQuery)->where('is_verified', true)->count(),
            'pending' => (clone $allCommentsQuery)->where('is_verified', false)->count(),
        ];
        $totalComments = $commentStats['approved'] + $commentStats['pending'];
        $postsPerMonth = $user->posts()
            ->select(
                DB::raw('MONTH(created_at) as month'), 
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', $selectedYear) 
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->all();

        $chartData = array_fill(1, 12, 0); 
        foreach ($postsPerMonth as $month => $count) {
            $chartData[$month] = $count;
        }
        $postChartDataJson = json_encode(array_values($chartData));
        $postIdsThisYear = $user->posts()->whereYear('created_at', $selectedYear)->pluck('id');
        $commentsQueryThisYear = Comment::whereIn('post_id', $postIdsThisYear); 
        $commentStatsThisYear = [
            'approved' => (clone $commentsQueryThisYear)->where('is_verified', true)->count(),
            'pending' => (clone $commentsQueryThisYear)->where('is_verified', false)->count(),
        ];
        $commentStatsJson = json_encode(array_values($commentStatsThisYear)); 

        return view('dashboard.profile.edit', [
            'user' => $user,
            'totalPosts' => $totalPosts,
            'totalComments' => $totalComments, 
            'pendingComments' => $commentStats['pending'],
            'commentStatsJson' => $commentStatsJson, 
            'postChartDataJson' => $postChartDataJson, 
            'availableYears' => $availableYears, 
            'selectedYear' => $selectedYear,     
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