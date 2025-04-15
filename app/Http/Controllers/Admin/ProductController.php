<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
 
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand']);

        // Search filter
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Price sorting
        if ($request->filled('price_filter')) {
            if ($request->price_filter == 'low_to_high') {
                $query->orderBy('price', 'asc');
            } elseif ($request->price_filter == 'high_to_low') {
                $query->orderBy('price', 'desc');
            }
        }

        $products = $query->paginate(6)->appends($request->query());

        return view('admin.products.index', compact('products'));
    }

    public function search(Request $request)
    {
        $query = $request->query('query');
        $products = Product::where('title', 'like', '%' . $query . '%')
                           ->orWhere('description', 'like', '%' . $query . '%')
                           ->take(10)
                           ->get(['id', 'title', 'price', 'stock', 'main_image']);

        // Transform main_image to full URL
        $products->transform(function ($product) {
            $product->main_image = $product->main_image ? Storage::url($product->main_image) : null;
            return $product;
        });

        return response()->json($products);
    }

    public function create()
    {
        $categories = Category::with('children')->get(); // Include child categories
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:in_stock,out_of_stock',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Generate slug if not provided
        $validatedData['slug'] = $validatedData['slug'] ?? Str::slug($validatedData['title']);

        // Handle main image upload
        if ($request->hasFile('main_image')) {
            $validatedData['main_image'] = $request->file('main_image')->store('products/images', 'public');
        }

        // Handle additional images upload
        if ($request->hasFile('additional_images')) {
            $additionalImages = [];
            foreach ($request->file('additional_images') as $image) {
                $additionalImages[] = $image->store('products/images', 'public');
            }
            $validatedData['additional_images'] = $additionalImages; // Array is cast to JSON by model
        }

        Product::create($validatedData);

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được tạo thành công.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::with('children')->get();
        $brands = Brand::all();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:in_stock,out_of_stock',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $id,
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::findOrFail($id);

        // Generate slug if not provided
        $validatedData['slug'] = $validatedData['slug'] ?? Str::slug($validatedData['title']);

        // Handle main image upload
        if ($request->hasFile('main_image')) {
            // Delete old main image if it exists
            if ($product->main_image && Storage::exists('public/' . $product->main_image)) {
                Storage::delete('public/' . $product->main_image);
            }
            $validatedData['main_image'] = $request->file('main_image')->store('products/images', 'public');
        }

        // Handle additional images upload
        if ($request->hasFile('additional_images')) {
            // Delete old additional images if they exist
            if ($product->additional_images) {
                foreach ($product->additional_images as $oldImage) {
                    if (Storage::exists('public/' . $oldImage)) {
                        Storage::delete('public/' . $oldImage);
                    }
                }
            }
            $additionalImages = [];
            foreach ($request->file('additional_images') as $image) {
                $additionalImages[] = $image->store('products/images', 'public');
            }
            $validatedData['additional_images'] = $additionalImages; // Array is cast to JSON by model
        }

        $product->update($validatedData);

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật thành công.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Delete main image
        if ($product->main_image && Storage::exists('public/' . $product->main_image)) {
            Storage::delete('public/' . $product->main_image);
        }

        // Delete additional images
        if ($product->additional_images) {
            foreach ($product->additional_images as $image) {
                if (Storage::exists('public/' . $image)) {
                    Storage::delete('public/' . $image);
                }
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được xóa thành công.');
    }
    public function import()
    {
        return view('admin.products.import');
    }

    /**
     * Xử lý file CSV/Excel để nhập hàng loạt sản phẩm
     */
    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls|max:2048',
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('file'));

            return redirect()->route('admin.products.index')
                           ->with('success', 'Nhập hàng loạt sản phẩm thành công.');
        } catch (\Exception $e) {
            return redirect()->route('admin.products.import')
                           ->with('error', 'Có lỗi xảy ra khi nhập sản phẩm: ' . $e->getMessage());
        }
    }
}
