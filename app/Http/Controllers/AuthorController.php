<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $sortBy = $request->query('sortBy', 'id');
        $sortDir = $request->query('sortDir', 'asc');
        $perPage = $request->query('perPage', 10);

        $query = Author::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }

        $allowedSorts = ['id', 'name', 'birth_date'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDir);
        }

        $authors = $query->paginate($perPage);

        $data = [
            'meta' => [
                'current_page' => $authors->currentPage(),
                'per_page' => $authors->perPage(),
                'total' => $authors->total(),
                'last_page' => $authors->lastPage(),
            ],
            'data' => $authors->items(),
        ];

        return $this->success($data, 'Authors retrieved successfully');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'nullable|integer',
            'birth_date' => 'nullable|date',
        ]);

        $author = Author::create($validated);
        return $this->success($author, 'Author created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author)
    {
        return $this->success($author, 'Author retrieved successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Author $author)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'age' => 'nullable|integer',
            'birth_date' => 'nullable|date',
        ]);

        $author->update($validated);
        return $this->success($author, 'Author updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
        $author->delete();
        return $this->success(null, 'Author deleted successfully');
    }
}
