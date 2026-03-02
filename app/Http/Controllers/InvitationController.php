<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Membership;
use App\Models\Colocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Requests\StoreInvitationRequest;
use App\Mail\InvitationMail;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{
public function store(StoreInvitationRequest $request, Colocation $colocation)
{
    $invitation = Invitation::create([
        'colocation_id' => $colocation->id,
        'email' => $request->email,
        'token' => Str::uuid(),
        'status' => 'pending',
        'expires_at' => now()->addDays(7),
    ]);

    Mail::to($request->email)->send(new InvitationMail($invitation));

    return back()->with('success', 'Invitation envoyee.');
}

    public function accept($token)
    {
$invitation = Invitation::where('token', $token)->firstOrFail();

        Membership::create([
            'user_id' => Auth::id(),
            'colocation_id' => $invitation->colocation_id,
            'role' => 'member',
            'joined_at' => now(),
        ]);

        if(!Auth::check()){
                    return redirect()->route('login');

        }

        $invitation->update(['status' => 'accepted']);

        return redirect()->route('colocations.show', $invitation->colocation_id,);
    }

    public function refuse($token)
    {
        $invitation = Invitation::where('token', $token)->first();
        $invitation->update(['status' => 'refused']);

        return redirect()->route('colocations.index');
    }
}