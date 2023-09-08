<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;

/**
 * Controller CategoryController
 *
 * Controlador responsável por todas as interações envolvendo categorias.
 */
class CategoryController extends Controller
{
    /**
     * @var CategoryService
     */
    private CategoryService $categoryService;

    /**
     * Construtor.
     *
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Action que retorna todas as categorias.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $categories = $this->categoryService->all();

        return CategoryResource::collection($categories);
    }

    public function create()
    {

    }

    /**
     * Action que cadastra uma nova categoria.
     *
     * @param CategoryStoreRequest $request
     * @return CategoryResource
     */
    public function store(CategoryStoreRequest $request): CategoryResource
    {
        $category = $this->categoryService->create($request->validated());

        return new CategoryResource($category);
    }

    /**
     * Action que retorna uma categoria especifica por id.
     *
     * @param string $id
     * @return CategoryResource
     */
    public function show(string $id): CategoryResource
    {
        $category = $this->categoryService->find($id);

        return new CategoryResource($category);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Action que atualiza uma categoria.
     *
     * @param CategoryUpdateRequest $request
     * @param string $id
     * @return CategoryResource
     */
    public function update(CategoryUpdateRequest $request, string $id): CategoryResource
    {
        $category = $this->categoryService->update($id, $request->validated());

        return new CategoryResource($category);
    }

    /**
     * Action que deleta uma categoria.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id): \Illuminate\Http\Response
    {
        $this->categoryService->delete($id);

        return response()->noContent();
    }
}
