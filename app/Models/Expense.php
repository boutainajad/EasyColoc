<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
protected $fillable = [
    'title', 'amount', 'date', 'paid_by', 'colocation_id', 'category_id'
];

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function paidBy()
{
    return $this->belongsTo(User::class, 'paid_by');
}
}