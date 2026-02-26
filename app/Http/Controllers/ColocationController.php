<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Membership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreColocationRequest;
use App\Http\Requests\UpdateColocationRequest;

class ColocationController extends Controller
{
    public function index(){
        $colocations = colocation::all();
        return view('colocations.index', compact('colocations'));
    }
    public function create(){
        return view('colocations.index');
    }
    public function store(StoreColocationRequest $request){
        $colocation = colocation::create([
            'name' => $request->name,
            'statut'=>'Avtive',
            'created_by'=>Auth::id(),
        ]);
        Membership::create([
            'User_id'=> Auth::id(),
            'colocation_id' => $colocation->id,
            'role' => 'owner',
            'joined_at'=>now(),
        ]);
        return redirect()->route('colocation.show', $colocation);
    }
     public function show(Colocation $colocation){
        $membres = $colocation->memberships()->whereNull('left_at')->with('user')->get();
        $expenses = $colocation->expenses()->with('category', 'PaidBy')->get();
         return view('colocations.show', compact('colocation', 'members', 'expenses'));
     }
     public function edit(Colocation $colocation)
    {
        return view('colocations.edit', compact('colocation'));
    }

    public function update(UpdateColocationRequest $request, Colocation $colocation)
    {
        $colocation->update($request->validated());
        return redirect()->route('colocations.show', $colocation);
    }
    public function cancel(Colocation $colocation)
    {
        $colocation->update(['status' => 'cancelled']);
        return redirect()->route('colocations.index');
    }
        public function leave(Colocation $colocation)
    {
        $membership = Membership::where('user_id', Auth::id())
            ->where('colocation_id', $colocation->id)
            ->first();

        $membership->update(['left_at' => now()]);

        return redirect()->route('colocations.index');
    }
}
