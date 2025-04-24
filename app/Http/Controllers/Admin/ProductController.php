<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Mews\Purifier\Facades\Purifier;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('slug', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('price_filter')) {
            $query->orderBy('price', $request->price_filter == 'low_to_high' ? 'asc' : 'desc');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->paginate(10)->appends($request->query());

        return view('admin.products.index', compact('products'));
    }

    public function search(Request $request)
    {
        $query = $request->query('query');
        $products = Product::where('title', 'like', '%' . $query . '%')
                           ->orWhere('description', 'like', '%' . $query . '%')
                           ->take(10)
                           ->get(['id', 'title', 'price', 'import_price', 'stock', 'status', 'main_image']);

        $products->transform(function ($product) {
            $product->main_image = $product->main_image ? Storage::url($product->main_image) : null;
            return $product;
        });

        return response()->json($products);
    }

    public function create()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        $brands = Brand::orderBy('name')->get();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0.01|max:9999999.99',
            'import_price' => 'nullable|numeric|min:0|max:9999999.99|lte:price',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:in_stock,out_of_stock',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'variants.*.name' => 'required_with:variants.*.value|string|max:255',
            'variants.*.value' => 'required_with:variants.*.name|string|max:255',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.stock' => 'nullable|integer|min:0',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề sản phẩm.',
            'price.required' => 'Vui lòng nhập giá bán.',
            'price.min' => 'Giá bán phải lớn hơn 0.',
            'price.max' => 'Giá bán không được vượt quá 9,999,999.99.',
            'import_price.min' => 'Giá nhập không được nhỏ hơn 0.',
            'import_price.max' => 'Giá nhập không được vượt quá 9,999,999.99.',
            'import_price.lte' => 'Giá nhập phải nhỏ hơn hoặc bằng giá bán.',
            'stock.required' => 'Vui lòng nhập số lượng tồn kho.',
            'stock.min' => 'Tồn kho không được nhỏ hơn 0.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'brand_id.required' => 'Vui lòng chọn thương hiệu.',
            'main_image.image' => 'Hình ảnh chính phải là file ảnh (jpeg, png, jpg, gif).',
            'main_image.max' => 'Hình ảnh chính không được vượt quá 2MB.',
            'additional_images.*.image' => 'Hình ảnh phụ phải là file ảnh (jpeg, png, jpg, gif).',
            'additional_images.*.max' => 'Hình ảnh phụ không được vượt quá 2MB.',
        ]);

        try {
            DB::transaction(function () use ($request, &$validatedData) {
                $validatedData['slug'] = $validatedData['slug'] ?? Str::slug($validatedData['title']);
                $validatedData['description'] = $validatedData['description'] ? Purifier::clean($validatedData['description']) : null;

                if ($request->hasFile('main_image')) {
                    $validatedData['main_image'] = $request->file('main_image')->store('products/images', 'public');
                }

                if ($request->hasFile('additional_images')) {
                    $additionalImages = [];
                    foreach ($request->file('additional_images') as $image) {
                        $additionalImages[] = $image->store('products/images', 'public');
                    }
                    $validatedData['additional_images'] = $additionalImages;
                }

                $product = Product::create($validatedData);

                if ($request->has('variants')) {
                    foreach ($request->variants as $variant) {
                        $product->variants()->create($variant);
                    }
                    $product->update(['has_variants' => true]);
                }
            });

            return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được tạo thành công.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Lỗi khi tạo sản phẩm: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::with('children')->whereNull('parent_id')->get();
        $brands = Brand::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0.01|max:9999999.99',
            'import_price' => 'nullable|numeric|min:0|max:9999999.99|lte:price',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:in_stock,out_of_stock',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $id,
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'additional_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'variants.*.name' => 'required_with:variants.*.value|string|max:255',
            'variants.*.value' => 'required_with:variants.*.name|string|max:255',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.stock' => 'nullable|integer|min:0',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề sản phẩm.',
            'price.required' => 'Vui lòng nhập giá bán.',
            'price.min' => 'Giá bán phải lớn hơn 0.',
            'price.max' => 'Giá bán không được vượt quá 9,999,999.99.',
            'import_price.min' => 'Giá nhập không được nhỏ hơn 0.',
            'import_price.max' => 'Giá nhập không được vượt quá 9,999,999.99.',
            'import_price.lte' => 'Giá nhập phải nhỏ hơn hoặc bằng giá bán.',
            'stock.required' => 'Vui lòng nhập số lượng tồn kho.',
            'stock.min' => 'Tồn kho không được nhỏ hơn 0.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'brand_id.required' => 'Vui lòng chọn thương hiệu.',
            'main_image.image' => 'Hình ảnh chính phải là file ảnh (jpeg, png, jpg, gif).',
            'main_image.max' => 'Hình ảnh chính không được vượt quá 2MB.',
            'additional_images.*.image' => 'Hình ảnh phụ phải là file ảnh (jpeg, png, jpg, gif).',
            'additional_images.*.max' => 'Hình ảnh phụ không được vượt quá 2MB.',
        ]);

        $product = Product::findOrFail($id);

        try {
            DB::transaction(function () use ($request, $product, &$validatedData) {
                $validatedData['slug'] = $validatedData['slug'] ?? Str::slug($validatedData['title']);
                $validatedData['description'] = $validatedData['description'] ? Purifier::clean($validatedData['description']) : null;

                if ($request->hasFile('main_image')) {
                    if ($product->main_image && Storage::exists('public/' . $product->main_image)) {
                        Storage::delete('public/' . $product->main_image);
                    }
                    $validatedData['main_image'] = $request->file('main_image')->store('products/images', 'public');
                }

                if ($request->hasFile('additional_images')) {
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
                    $validatedData['additional_images'] = $additionalImages;
                }

                $product->update($validatedData);

                // Update or create variants
                if ($request->has('variants')) {
                    $product->variants()->delete(); // Clear existing variants
                    foreach ($request->variants as $variant) {
                        $product->variants()->create($variant);
                    }
                    $product->update(['has_variants' => true]);
                } else {
                    $product->variants()->delete();
                    $product->update(['has_variants' => false]);
                }
            });

            return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật thành công.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Lỗi khi cập nhật sản phẩm: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        try {
            DB::transaction(function () use ($product) {
                if ($product->main_image && Storage::exists('public/' . $product->main_image)) {
                    Storage::delete('public/' . $product->main_image);
                }

                if ($product->additional_images) {
                    foreach ($product->additional_images as $image) {
                        if (Storage::exists('public/' . $image)) {
                            Storage::delete('public/' . $image);
                        }
                    }
                }

                $product->delete();
            });

            return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được xóa thành công.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lỗi khi xóa sản phẩm: ' . $e->getMessage());
        }
    }

    public function import()
    {
        return view('admin.products.import');
    }
}