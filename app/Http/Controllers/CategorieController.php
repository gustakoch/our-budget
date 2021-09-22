<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategorieController extends Controller
{
    private $category;

    public function __construct(CategoryModel $category)
    {
        $this->category = $category;
    }

    public function index()
    {
        $categories = $this->category->getAll();

        return view('categories.index', [
            'categories' => $categories
        ]);
    }

    public function store()
    {
        $data = request()->all();

        CategoryModel::create([
            'description' => $data['description_category'],
            'belongs_to' => $data['belongs_to'],
            'color' => $data['color']
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Categoria cadastrada com sucesso!'
        ]);
    }

    public function show($id)
    {
        $category = CategoryModel::find($id);

        return response()->json($category);
    }

    public function update()
    {
        $data = request()->all();
        $data['color'] = isset($data['color']) ? $data['color'] : null;

        CategoryModel::where('id', $data['id_category'])
            ->update([
                'description' => $data['description_category_edit'],
                'belongs_to' => $data['belongs_to_edit'],
                'color' => $data['color']
            ]);

        return response()->json([
            'ok' => true,
            'message' => 'Categoria atualizada com sucesso'
        ]);
    }

    public function destroy($id)
    {
        CategoryModel::where('id', $id)
            ->update([
                'active' => 0,
            ]);

        return response()->json([
            'ok' => true,
            'msg' => 'Categoria removida com sucesso!'
        ]);
    }
}
