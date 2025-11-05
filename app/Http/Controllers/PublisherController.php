<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class PublisherController extends Controller
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

        $query = Publisher::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('address', 'like', "%$search%");
            });
        }

        $allowedSorts = ['id', 'address', 'name'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDir);
        }

        $publishers = $query->paginate($perPage);

        $data = [
            'meta' => [
                'current_page' => $publishers->currentPage(),
                'per_page' => $publishers->perPage(),
                'total' => $publishers->total(),
                'last_page' => $publishers->lastPage(),
            ],
            'data' => $publishers->items(),
        ];

        return $this->success($data, 'Publishers retrieved successfully');
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
            'address' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
        ]);

        $publisher = Publisher::create($validated);

        return $this->success($publisher, 'Publisher created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Publisher $publisher)
    {
        return $this->success($publisher, 'Publisher retrieved successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Publisher $publisher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publisher $publisher)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'address' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
        ]);

        $publisher->update($validated);

        return $this->success($publisher, 'Publisher updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publisher $publisher)
    {
        $publisher->delete();
        return $this->success(null, 'Publisher deleted successfully');
    }
}
