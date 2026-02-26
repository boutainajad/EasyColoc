<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Colocation;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;

class ExpenseController extends Controller
{
    public function store(StoreExpenseRequest $request, Colocation $colocation)
    {
        Expense::create([
            'title' => $request->title,
            'amount' => $request->amount,
            'date' => $request->date,
            'category_id' => $request->category_id,
            'paid_by' => $request->paid_by,
            'colocation_id' => $colocation->id,
        ]);

        return back()->with('success', 'Depense ajoutee.');
    }

    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        $expense->update($request->validated());
        return back()->with('success', 'Depense modifiee.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return back()->with('success', 'Depense supprimee.');
    }
}