<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class BookController extends Controller
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

        $query = Book::with(['author', 'publisher']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('isbn', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            });
        }

        $allowedSorts = ['id', 'title', 'isbn', 'published_year', 'pages'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDir);
        }

        $books = $query->paginate($perPage);

        $data = [
            'meta' => [
                'current_page' => $books->currentPage(),
                'per_page' => $books->perPage(),
                'total' => $books->total(),
                'last_page' => $books->lastPage(),
            ],
            'data' => $books->items(),
        ];

        return $this->success($data, 'Books retrieved successfully');
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
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn',
            'description' => 'nullable|string',
            'pages' => 'nullable|integer',
            'published_year' => 'nullable|digits:4|integer',
            'author_id' => 'required|exists:authors,id',
            'publisher_id' => 'required|exists:publishers,id',
            'cover_image' => 'nullable|string',
        ]);

        $book = Book::create($validated);

        return $this->success($book->load(['author', 'publisher']), 'Book created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return $this->success($book->load(['author', 'publisher']), 'Book retrieved successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'isbn' => 'sometimes|required|string|unique:books,isbn,' . $book->id,
            'description' => 'nullable|string',
            'pages' => 'nullable|integer',
            'published_year' => 'nullable|digits:4|integer',
            'author_id' => 'sometimes|required|exists:authors,id',
            'publisher_id' => 'sometimes|required|exists:publishers,id',
            'cover_image' => 'nullable|string',
        ]);

        $book->update($validated);

        return $this->success($book->load(['author', 'publisher']), 'Book updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return $this->success(null, 'Book deleted successfully');
    }
}
