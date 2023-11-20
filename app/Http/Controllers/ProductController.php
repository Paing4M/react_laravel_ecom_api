<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller {

  protected $repository;

  public function __construct(ProductRepository $repository) {
    $this->repository = $repository;
  }

  /**
   * Display a listing of the resource.
   */
  public function index() {
    $products = Product::all();
    return new ProductCollection($products);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request) {
    $payload = $request->only([
      'category_id',
      'meta_title',
      'meta_keyword',
      'meta_description',
      'name',
      'slug',
      'brand',
      'description',
      'selling_price',
      'original_price',
      'qty',
      'featured',
      'popular',
      'status',
    ]);


    $validator = Validator::validate($payload, [
      'name' => 'required|unique:products,name',
      'slug' => 'required|unique:products,slug',
      'original_price' => 'required',
      'qty' => 'required',
      'category_id' => 'required',
    ], [
      'category_id.required' => 'Please select a category.',
    ]);

    if ($request->hasFile('image')) {
      $file = $request->file('image');
      $ext = $file->getClientOriginalExtension();
      $filename = time() . '.' . $ext;
      $file->move('uploads/products', $filename);
      $payload['image'] = $filename;
    }


    $product = $this->repository->store($payload);
    return new ProductResource($product);
  }

  /**
   * Display the specified resource.
   */
  public function show(Product $product) {
    return new ProductResource($product);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Product $product) {


    $payload = $request->only([
      'category_id',
      'meta_title',
      'meta_keyword',
      'meta_description',
      'name',
      'slug',
      'brand',
      'description',
      'selling_price',
      'original_price',
      'qty',
      'featured',
      'popular',
      'status',
    ]);

    $validator = Validator::validate($payload, [
      'name' => 'sometimes|required|unique:products,name,' . $product->id,
      'slug' => 'sometimes|required|unique:products,slug,' . $product->id,
      'original_price' => 'sometimes|required',
      'qty' => 'sometimes|required',
      'category_id' => 'sometimes|required',
    ], [
      'category_id.required' => 'Please select a category.',
    ]);

    if ($request->hasFile('image')) {
      // deleting existing image
      if (File::exists('uploads/products/' . $product->image)) {
        File::delete('uploads/products/' . $product->image);
      }

      $file = $request->file('image');
      $ext = $file->getClientOriginalExtension();
      $filename = time() . '.' . $ext;

      $file->move('uploads/products', $filename);
      $payload['image'] = $filename;
    } else {
      $payload['image'] = $product->image;
    }

    $updated = $this->repository->update($product, $payload);
    return new ProductResource($updated);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Product $product) {
    // delete image
    if (File::exists('uploads/products/' . $product->image)) {
      File::delete('uploads/products/' . $product->image);
    }

    $deleted = $this->repository->delete($product);

    if ($deleted)
      return response()->json([
        'message' => 'Product deleted successfully.',
      ]);
  }
}
