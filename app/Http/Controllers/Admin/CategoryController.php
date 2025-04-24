<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')->get(); 
        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'slug' => 'required|string|max:255|unique:categories',
            'icon' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $validatedData['icon'] = $validatedData['icon'] ?? null; 

        Category::create($validatedData);

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục được tạo thành công.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::whereNull('parent_id')->where('id', '!=', $id)->get(); 
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'slug' => 'required|string|max:255|unique:categories,slug,' . $id,
            'icon' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $validatedData['icon'] = $validatedData['icon'] ?? null; 

        $category = Category::findOrFail($id);
        $category->update($validatedData);

        return redirect()->route('admin.categories.index')->with('success', 'Đã cập nhật danh mục thành công.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Đã xóa danh mục thành công.');
    }

    public function showHierarchy()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        return view('admin.categories.hierarchy', compact('categories'));
    }
}