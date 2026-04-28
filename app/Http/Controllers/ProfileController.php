<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Progress Bar Data
        $totalItems = \App\Models\Item::count();
        $totalItems = $totalItems > 0 ? $totalItems : 1;
        $unlocksCount = \Illuminate\Support\Facades\DB::table('user_unlocks')->where('user_id', $user->id)->count();
        $percentage = round(($unlocksCount / $totalItems) * 100);
        $progreso = $percentage;
        $faltantes = $totalItems - $unlocksCount;

        // User Activity Data
        // Assuming models exist: App\Models\Build and App\Models\TierList
        $builds = \App\Models\Build::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $tierLists = \App\Models\TierList::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        return view('profile', compact('user', 'totalItems', 'unlocksCount', 'progreso', 'faltantes', 'builds', 'tierLists'));
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = time() . '_' . $user->id . '.' . $avatar->getClientOriginalExtension();
            
            // Delete old avatar if it exists and is local
            if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            // Create directory if not exists
            if (!Storage::disk('public')->exists('avatars')) {
                Storage::disk('public')->makeDirectory('avatars');
            }

            // Use Intervention Image to resize to 300x300 and save
            $path = storage_path('app/public/avatars/' . $filename);
            Image::make($avatar)->fit(300, 300)->save($path);

            $user->avatar = $filename;
            $user->save();

            return response()->json([
                'success' => true,
                'avatar_url' => asset('storage/avatars/' . $filename),
                'message' => 'Avatar actualizado correctamente.'
            ]);
        }

        return response()->json(['success' => false, 'message' => 'No se ha subido ninguna imagen.']);
    }
    public function showPublic($id)
    {
        $user = \App\Models\User::findOrFail($id);
        
        if (auth()->check() && auth()->id() == $user->id) {
            return redirect()->route('profile');
        }

        // Progress Bar Data
        $totalItems = \App\Models\Item::count();
        $totalItems = $totalItems > 0 ? $totalItems : 1;
        $unlocksCount = \Illuminate\Support\Facades\DB::table('user_unlocks')->where('user_id', $user->id)->count();
        $percentage = round(($unlocksCount / $totalItems) * 100);
        $progreso = $percentage;
        $faltantes = $totalItems - $unlocksCount;

        // User Activity Data
        $builds = \App\Models\Build::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $tierLists = \App\Models\TierList::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        return view('profile', compact('user', 'totalItems', 'unlocksCount', 'progreso', 'faltantes', 'builds', 'tierLists'));
    }
}
