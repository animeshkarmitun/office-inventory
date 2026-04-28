<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();
        
        $sort = $request->input('sort', 'name');
        $direction = $request->input('direction', 'asc');
        
        $allowedSorts = ['id', 'name'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'name';
        }

        $categories = $query->orderBy($sort, $direction)->paginate(10)->withQueryString();
        return view('pages.category.index', compact('categories'));
    }

    public function showAdd()
    {
        return view('pages.category.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Category::create([
            'name' => $request->name
        ]);

        return redirect()->route('category')->with(['message' => 'Category added', 'alert' => 'alert-success']);
    }

    public function destroy($id)
    {
        $category = Category::find($id)->delete();

        return redirect()->route('category')->with(['message' => 'Category deleted', 'alert' => 'alert-danger']);
    }

    public function showEdit($id)
    {
        $category = Category::find($id);

        return view('pages.category.edit', compact('category'));
    }

    public function update($id, Request $request)
    {
        $category = Category::find($id);

        $request->validate([
            'name' => 'required',
        ]);

        $category->name = $request->name;

        $category->save();

        return redirect()->route('category')->with(['message' => 'Category updated', 'alert' => 'alert-success']);
    }
}
