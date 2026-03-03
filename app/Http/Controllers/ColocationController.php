<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Membership;
use App\Models\ReputationLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreColocationRequest;
use App\Http\Requests\UpdateColocationRequest;

class ColocationController extends Controller
{
    public function index()
    {
        if (Auth::user()->is_admin) {
            $colocations = Colocation::all();
        } else {
            $colocations = Colocation::whereHas('memberships', function ($query) {
                $query->where('user_id', Auth::id())
                      ->whereNull('left_at');
            })->get();
        }

        return view('colocations.index', compact('colocations'));
    }

    public function create()
    {
        return view('colocations.create');
    }

    public function store(StoreColocationRequest $request)
    {
        $activeMembership = Membership::where('user_id', Auth::id())
            ->whereNull('left_at')
            ->whereHas('colocation', function ($query) {
                $query->where('status', 'active');
            })
            ->first();

        if ($activeMembership) {
            $activeMembersCount = Membership::where('colocation_id', $activeMembership->colocation_id)
                ->whereNull('left_at')
                ->count();

            if ($activeMembersCount > 1) {
                return back()->withErrors([
                    'error' => 'Vous avez déjà une colocation active.'
                ]);
            }
        }

        $colocation = Colocation::create([
            'name' => $request->name,
            'status' => 'active',
            'created_by' => Auth::id(),
        ]);

        Membership::create([
            'user_id' => Auth::id(),
            'colocation_id' => $colocation->id,
            'role' => 'owner',
            'joined_at' => now(),
        ]);

        return redirect()->route('colocations.show', $colocation);
    }

    public function show(Colocation $colocation)
    {
        $colocation->load('categories');

        $memberships = $colocation->memberships()
            ->whereNull('left_at')
            ->with('user')
            ->get();

        $expenses = $colocation->expenses()
            ->with('category', 'paidBy')
            ->get();

        $settlements = $this->calculateSettlements($colocation);

        $currentUser = auth()->user();
        $currentMembership = $memberships->firstWhere('user_id', $currentUser->id);

        foreach ($memberships as $membership) {
            $membership->reputation = $membership->user->getReputationScore();
            $membership->can_kick = $currentMembership &&
                                    $currentMembership->role === 'owner' &&
                                    $membership->user_id !== $currentUser->id;
        }

        $members = $memberships;

        return view('colocations.show', compact('colocation', 'members', 'expenses', 'settlements'));
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
        $members = $colocation->memberships()->whereNull('left_at')->get();

        foreach ($members as $membership) {
            $balance = $this->calculateBalance($colocation, $membership->user_id);

            ReputationLog::create([
                'user_id' => $membership->user_id,
                'change' => $balance < 0 ? -1 : 1,
                'reason' => $balance < 0 ? 'Cancelled with debt' : 'Cancelled without debt',
            ]);

            $membership->update(['left_at' => now()]);
        }

        $colocation->update(['status' => 'cancelled']);

        return redirect()->route('colocations.index');
    }

    public function leave(Colocation $colocation)
    {
        $membership = Membership::where('user_id', Auth::id())
            ->where('colocation_id', $colocation->id)
            ->whereNull('left_at')
            ->first();

        $balance = $this->calculateBalance($colocation, Auth::id());

        ReputationLog::create([
            'user_id' => Auth::id(),
            'change' => $balance < 0 ? -1 : 1,
            'reason' => $balance < 0 ? 'Left with debt' : 'Left without debt',
        ]);

        $membership->update(['left_at' => now()]);

        return redirect()->route('colocations.index');
    }

    private function calculateBalance(Colocation $colocation, $userId)
    {
        $members = $colocation->memberships()->whereNull('left_at')->count();

        if ($members === 0) return 0;

        $totalExpenses = $colocation->expenses()->sum('amount');
        $share = $totalExpenses / $members;
        $paid = $colocation->expenses()->where('paid_by', $userId)->sum('amount');

        $paymentsMade = $colocation->payments()->where('from_user_id', $userId)->sum('amount');
        $paymentsReceived = $colocation->payments()->where('to_user_id', $userId)->sum('amount');

        return $paid - $share - $paymentsMade + $paymentsReceived;
    }

    private function calculateSettlements(Colocation $colocation)
    {
        $members = $colocation->memberships()->whereNull('left_at')->with('user')->get();
        $expenses = $colocation->expenses;

        if ($members->count() === 0) return [];

        $totalExpenses = $expenses->sum('amount');
        $share = $totalExpenses / $members->count();

        $balances = [];
        foreach ($members as $membership) {
            $paid = $expenses->where('paid_by', $membership->user_id)->sum('amount');

            $paymentsMade = $colocation->payments()
                ->where('from_user_id', $membership->user_id)->sum('amount');
            $paymentsReceived = $colocation->payments()
                ->where('to_user_id', $membership->user_id)->sum('amount');

            $balances[$membership->user_id] = [
                'user' => $membership->user,
                'balance' => $paid - $share - $paymentsMade + $paymentsReceived,
            ];
        }

        $settlements = [];
        $debtors = array_filter($balances, fn($b) => $b['balance'] < -0.01);
        $creditors = array_filter($balances, fn($b) => $b['balance'] > 0.01);

        foreach ($debtors as $debtorId => $debtor) {
            foreach ($creditors as $creditorId => $creditor) {
                $amount = min(abs($debtor['balance']), $creditor['balance']);
                if ($amount > 0.01) {
                    $settlements[] = [
                        'from' => $debtor['user'],
                        'to' => $creditor['user'],
                        'amount' => round($amount, 2),
                    ];
                }
            }
        }

        return $settlements;
    }

    public function kick(Colocation $colocation, User $user)
    {
        $currentUser = auth()->user();
        $currentMembership = $colocation->memberships()
            ->where('user_id', $currentUser->id)
            ->whereNull('left_at')
            ->first();

        if (!$currentMembership || $currentMembership->role !== 'owner') {
            return back()->with('error', 'Seul le propriétaire peut expulser un membre.');
        }

        if ($user->id === $currentUser->id) {
            return back()->with('error', 'Vous ne pouvez pas vous expulser vous-même.');
        }

        $membership = $colocation->memberships()
            ->where('user_id', $user->id)
            ->whereNull('left_at')
            ->first();

        if (!$membership) {
            return back()->with('error', 'Ce membre n\'est pas dans la colocation.');
        }

        $balance = $this->calculateBalance($colocation, $user->id);

        ReputationLog::create([
            'user_id' => $user->id,
            'change'  => $balance < 0 ? -1 : 1,
            'reason'  => $balance < 0 ? 'Expulsé avec dette' : 'Expulsé sans dette',
        ]);

        $membership->update(['left_at' => now()]);

        return back()->with('success', "L'utilisateur {$user->name} a été expulsé de la colocation.");
    }
}