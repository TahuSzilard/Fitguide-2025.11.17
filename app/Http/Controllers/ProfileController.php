<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\RewardShopItem;
use App\Models\RedeemedReward;
use Illuminate\Support\Facades\Hash;


class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return view('profile.index', [
            'user' => $user,
            'ordersCount' => $user->orders()->count(),
            'points' => $user->points ?? 0,
        ]);
    }
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function rewards(Request $request): View
    {
        $user = $request->user();

        return view('profile.rewards', [
            'user' => $user,
            'totalPoints' => $user->points ?? 0,
            'redeemed' => $user->redeemedRewards()->with('item')->latest()->get(),
            'shopItems' => RewardShopItem::all()
        ]);
    }


    public function discounts(Request $request): View
    {
        $user = $request->user();
        $discounts = $user->discounts()->orderBy('created_at', 'desc')->get();

        return view('profile.discounts', [
            'user' => $user,
            'discounts' => $discounts
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validatedData = $request->validated();

        // Profilkép
        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::delete('public/' . $user->profile_picture);
            }
            $validatedData['profile_picture'] = 
                $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        $previousCompleted = ($user->phone && $user->dob && $user->gender);

        $user->fill($validatedData);
        $user->save();

        // Pontok profil kitöltésért
        if (!$previousCompleted && $user->phone && $user->dob && $user->gender) {
            $user->increment('total_points', 20);
            return Redirect::route('profile.edit')
                ->with('success', 'Profile completed! +20 points earned!');
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function redeem(Request $request, RewardShopItem $item)
    {
        $user = $request->user();

        if ($user->points < $item->price_points) {
            return back()->with('error', 'Not enough points!');
        }

        $user->points -= $item->price_points;
        $user->save();

        RedeemedReward::create([
            'user_id' => $user->id,
            'reward_shop_item_id' => $item->id,
            'points_spent' => $item->price_points,
        ]);

        return back()->with('success', 'Redeemed successfully!');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();

        if ($user->profile_picture) {
            Storage::delete('public/' . $user->profile_picture);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function security(Request $request)
    {
        return view('profile.security', ['user' => $request->user()]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = $request->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password updated successfully!');
    }
   

}
