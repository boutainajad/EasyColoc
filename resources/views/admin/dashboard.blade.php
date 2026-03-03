<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - EasyColoc</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 30px 20px; }

        .container { max-width: 1000px; margin: 0 auto; }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 12px 20px;
            margin-bottom: 20px;
        }
        nav h1 { font-size: 18px; color: #333; }
        .btn-red { background: #c0392b; border: none; cursor: pointer; color: white; padding: 7px 16px; border-radius: 4px; font-size: 13px; }

        .stats {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }
        .stat {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 14px 16px;
        }
        .stat-value { font-size: 24px; font-weight: bold; color: #222; }
        .stat-label { font-size: 11px; color: #888; margin-top: 3px; text-transform: uppercase; letter-spacing: 0.4px; }

        .section {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-bottom: 20px;
            overflow: hidden;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            padding: 12px 16px;
            border-bottom: 1px solid #eee;
            background: #fafafa;
        }

        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th { text-align: left; padding: 9px 14px; background: #f5f5f5; color: #555; font-weight: 600; border-bottom: 1px solid #e0e0e0; font-size: 12px; }
        td { padding: 9px 14px; border-bottom: 1px solid #f0f0f0; color: #333; }
        tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #fafafa; }

        .badge { display: inline-block; padding: 2px 9px; border-radius: 3px; font-size: 11px; font-weight: 600; }
        .badge-active { background: #e9f7ef; color: #1e8449; }
        .badge-cancelled { background: #fdecea; color: #922b21; }
        .badge-banned { background: #fdecea; color: #922b21; }
        .badge-admin { background: #eaf2ff; color: #1a5276; }

        .btn-sm { padding: 4px 10px; font-size: 12px; border: none; cursor: pointer; color: white; border-radius: 4px; font-weight: 600; }
        .btn-ban { background: #c0392b; }
        .btn-unban { background: #27ae60; }

        .alert { padding: 10px 15px; border-radius: 4px; margin-bottom: 15px; font-size: 13px; }
        .alert-success { background: #e9f7ef; border: 1px solid #a9dfbf; color: #1e8449; }
        .alert-error { background: #fdecea; border: 1px solid #f1948a; color: #922b21; }

        .rep-pos { color: #27ae60; font-weight: bold; }
        .rep-neg { color: #c0392b; font-weight: bold; }
        .text-muted { color: #999; }
    </style>
</head>
<body>
<div class="container">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    {{-- NAV --}}
    <nav>
        <h1>Dashboard Administrateur</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-red">Se déconnecter</button>
        </form>
    </nav>

    {{-- STATS --}}
    <div class="stats">
        <div class="stat">
            <div class="stat-value">{{ $stats['total_users'] }}</div>
            <div class="stat-label">Utilisateurs</div>
        </div>
        <div class="stat">
            <div class="stat-value">{{ $stats['banned_users'] }}</div>
            <div class="stat-label">Bannis</div>
        </div>
        <div class="stat">
            <div class="stat-value">{{ $stats['active_colocations'] }}</div>
            <div class="stat-label">Actives</div>
        </div>
        <div class="stat">
            <div class="stat-value">{{ $stats['total_colocations'] }}</div>
            <div class="stat-label">Colocations</div>
        </div>
        <div class="stat">
            <div class="stat-value">{{ $stats['expense_count'] }}</div>
            <div class="stat-label">Dépenses</div>
        </div>
        <div class="stat">
            <div class="stat-value">{{ number_format($stats['total_expenses'], 0, ',', ' ') }} €</div>
            <div class="stat-label">Total €</div>
        </div>
    </div>

    {{-- COLOCATIONS --}}
    <div class="section">
        <div class="section-title">Colocations</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Statut</th>
                    <th>Membres actifs</th>
                    <th>Nb dépenses</th>
                    <th>Total dépenses</th>
                    <th>Créée le</th>
                </tr>
            </thead>
            <tbody>
                @forelse($colocations as $coloc)
                <tr>
                    <td class="text-muted">{{ $coloc->id }}</td>
                    <td><strong>{{ $coloc->name }}</strong></td>
                    <td>
                        <span class="badge {{ $coloc->status === 'active' ? 'badge-active' : 'badge-cancelled' }}">
                            {{ $coloc->status === 'active' ? 'Active' : 'Annulée' }}
                        </span>
                    </td>
                    <td>{{ $coloc->memberships->count() }}</td>
                    <td>{{ $coloc->expenses->count() }}</td>
                    <td>{{ number_format($coloc->expenses->sum('amount'), 2, ',', ' ') }} €</td>
                    <td class="text-muted">{{ $coloc->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center; color:#999; padding:20px;">Aucune colocation</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- USERS --}}
    <div class="section">
        <div class="section-title">Utilisateurs</div>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Réputation</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="text-muted">{{ $user->id }}</td>
                    <td><strong>{{ $user->name }}</strong></td>
                    <td class="text-muted">{{ $user->email }}</td>
                    <td>
                        @if($user->is_admin)
                            <span class="badge badge-admin">Admin</span>
                        @else
                            <span class="text-muted">User</span>
                        @endif
                    </td>
                    <td>
                        <span class="{{ $user->reputation > 0 ? 'rep-pos' : ($user->reputation < 0 ? 'rep-neg' : 'text-muted') }}">
                            {{ $user->reputation > 0 ? '+' : '' }}{{ $user->reputation }}
                        </span>
                    </td>
                    <td>
                        @if($user->is_banned)
                            <span class="badge badge-banned">Banni</span>
                        @else
                            <span class="badge badge-active">Actif</span>
                        @endif
                    </td>
                    <td>
                        @if(!$user->is_admin)
                            @if($user->is_banned)
                                <form method="POST" action="{{ route('admin.unban', $user) }}" style="display:inline">
                                    @csrf
                                    <button type="submit" class="btn-sm btn-unban">Débannir</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.ban', $user) }}" style="display:inline">
                                    @csrf
                                    <button type="submit" class="btn-sm btn-ban">Bannir</button>
                                </form>
                            @endif
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center; color:#999; padding:20px;">Aucun utilisateur</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
</body>
</html>