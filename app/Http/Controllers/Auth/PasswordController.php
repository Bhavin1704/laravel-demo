<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\UpdatePassword;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification as NotificationsNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification as FacadesNotification;
use Illuminate\Validation\Rules\Password;
use Notification;
// use MBarlow\Megaphone\Types\BaseAnnouncement;
// use MBarlow\Megaphone\Types\General;
use MBarlow\Megaphone\Types\Important;
//use MBarlow\Megaphone\Types\NewFeature;


class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $userSchema = User::first();


        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $notification = new \MBarlow\Megaphone\Types\Important(
            'Password-update',
            'Your password update succesfully'
        );
        $user = \App\Models\User::find(auth()->user()->id);
        $user->notify($notification);
        return back()->with('status', 'password-updated');
    }
}
