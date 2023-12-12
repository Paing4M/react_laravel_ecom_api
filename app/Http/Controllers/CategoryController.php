<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller {
  protected $repository;

  public function __construct(CategoryRepository $repository) {
    $this->repository = $repository;
  }

  /**
   * Display a listing of the resource.
   */
  public function index(Request $request) {
    $categories = Category::orderBy('created_at' , 'desc')->paginate($request->per_page ?? 10);
    return new CategoryCollection($categories);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request) {

    $payload = $request->only([
      'name',
      'slug',
      'description',
      'status',
      'meta_title',
      'meta_keyword',
      'meta_description',
    ]);

    $validator = Validator::validate($payload, [
      'name' => ['required', 'unique:categories,name'],
      'slug' => ['required', 'unique:categories,slug'],
    ]);

    $category = $this->repository->store($payload);
    return new CategoryResource($category);
  }

  /**
   * Display the specified resource.
   */
  public function show(Category $category) {
    return new CategoryResource($category);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Category $category) {
    $payload = $request->only([
      'name',
      'slug',
      'description',
      'status',
      'meta_title',
      'meta_keyword',
      'meta_description',
    ]);


    $validator = Validator::validate($payload, [
      'name' => ['sometimes', 'required', 'unique:categories,name,' . $category->id],
      'slug' => ['sometimes', 'required', 'unique:categories,slug,' . $category->id],
    ]);

    $updated = $this->repository->update($category, $payload);
    return new CategoryResource($updated);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Category $category) {
    $deleted = $this->repository->delete($category);
    if ($deleted) {
      return response()->json(
        [
          'message' => 'Category deleted successfully.'
        ]
      );
    }
  }


  /* Get all enable categories */
  public function getEnableCategories() {
    $categories = Category::where('status', '0')->orderBy('created_at' , 'desc')->get();
    return new CategoryCollection($categories);
  }
}
