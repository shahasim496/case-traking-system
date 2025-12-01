<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entity;
use Yajra\DataTables\Facades\DataTables;

class EntityController extends Controller
{
    public function index()
    {
        $entities = Entity::orderBy('id', 'DESC')->paginate(10);
        return view('entities.index', compact('entities'));
    }

    public function getEntities(Request $request)
    {
        $entities = Entity::orderBy('id', 'DESC')->get();

        return DataTables::of($entities)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $actionBtn = '<a href="' . route('entities.edit', $row->id) . '" class="btn edit_icon" data-toggle="tooltip" title="Edit">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                              </a>
                              <button class="btn delete_icon" data-id="' . $row->id . '" data-toggle="modal" data-target="#deleteModal" title="Delete">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                              </button>';
                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('entities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:entities,name',
            'description' => 'nullable|string',
        ]);

        Entity::create($request->only('name', 'description'));

        return redirect()->route('entities')->with('success', 'Entity created successfully.');
    }

    public function edit($id)
    {
        $entity = Entity::findOrFail($id);
        return view('entities.edit', compact('entity'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:entities,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $entity = Entity::findOrFail($id);
        $entity->update($request->only('name', 'description'));

        return redirect()->route('entities')->with('success', 'Entity updated successfully.');
    }

    public function delete($id)
    {
        $entity = Entity::findOrFail($id);
        $entity->delete();

        return redirect()->route('entities')->with('success', 'Entity deleted successfully.');
    }
}

