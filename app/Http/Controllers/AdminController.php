<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Colocation;
use App\Models\Expense;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $stats = [
            'total_users'           => User::count(),
            'banned_users'          => User::where('is_banned', true)->count(),
            'total_colocations'     => Colocation::count(),
            'active_colocations'    => Colocation::where('status', 'active')->count(),
            'cancelled_colocations' => Colocation::where('status', 'cancelled')->count(),
            'total_expenses'        => Expense::sum('amount'),
            'expense_count'         => Expense::count(),
        ];

        $colocations = Colocation::with(['memberships' => function ($q) {
            $q->whereNull('left_at');
        }, 'expenses'])->latest()->get();

        $users = User::with('reputationLogs')->latest()->get();

        foreach ($users as $user) {
            $user->reputation = $user->getReputationScore();
        }

        return view('admin.dashboard', compact('stats', 'colocations', 'users'));
    }

    public function ban(User $user)
    {
        if ($user->is_admin) {
            return back()->with('error', 'Impossible de bannir un administrateur.');
        }
        $user->update(['is_banned' => true]);
        return back()->with('success', "L'utilisateur {$user->name} a été banni.");
    }

    public function unban(User $user)
    {
        $user->update(['is_banned' => false]);
        return back()->with('success', "L'utilisateur {$user->name} a été débanni.");
    }
}