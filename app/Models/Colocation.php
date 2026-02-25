<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Colocation extends Model
{
    protected $fillable = ['name', 'status'];

    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}