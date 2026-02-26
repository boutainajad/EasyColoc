<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvitationController extends Controller
{
        public function store(StoreInvitationRequest $request, Colocation $colocation)
        {
            invitation::create([
                'colocation_id' => $colocation->id,
                'email' => $request->email,
                'token' => str::uuid,
                'status' => 'pending',
                'expires_at' => now()->addDays(7),
            ]);
                return back()->with('success', 'Invitation envoyee.');
        }
        
            public function accept($token)
        {
            $invitation = Invitation::where('token', $token)->first();

            Membership::create([
                'user_id' => Auth::id(),
                'colocation_id' => $invitation->colocation_id,
                'role' => 'member',
                'joined_at' => now(),
            ]);

            $invitation->update(['status' => 'accepted']);

            return redirect()->route('colocations.show', $invitation->colocation_id);
        }

    public function refuse($token)
    {
        $invitation = Invitation::where('token', $token)->first();
        $invitation->update(['status' => 'refused']);

        return redirect()->route('colocations.index');
    }
}
