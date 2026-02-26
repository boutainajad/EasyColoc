<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Colocation;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request, Colocation $colocation)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create([
            'name' => $request->name,
            'colocation_id' => $colocation->id,
        ]);

        return back()->with('success', 'Categorie ajoutee.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Categorie supprimee.');
    }
}