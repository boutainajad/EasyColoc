<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'is_admin', 'is_banned'
    ];

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function reputationLogs()
    {
        return $this->hasMany(ReputationLog::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'from_user_id');
    }
     public function colocations()
    {
        return $this->belongsToMany(Colocation::class, 'memberships')
                    ->withPivot('role', 'joined_at', 'left_at');
    }

    public function activeColocation()
    {
        return $this->memberships()->whereNull('left_at')->first();
    }
}