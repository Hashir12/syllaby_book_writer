<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $books = Book::where('user_id',Auth::id());

        if ($request->has('search')) {
            $books = $books->where('title','like','%' . $request->get('search') . '%');
        }
        $books = $books->orderBy('id','desc')->paginate(10);

        return BookResource::collection($books);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $bookData = [
            'title' => $request->get('title'),
            'user_id' => Auth::id(),
        ];

        $book = Book::create($bookData);

        return response()->json([
            'message' => 'Book created successfully.',
            'book' => new BookResource($book),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::where('id', $id)->where('user_id',Auth::id())->first();

        if(!$book){
            return response()->json([
                'message' => 'Book not found',
            ],404);
        }

        return response()->json([
            'book' => new BookResource($book),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedRequest = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $book = Book::where('id', $id)->where('user_id',Auth::id())->first();

        if(!$book){
            return response()->json([
                'message' => 'Book not found',
            ],404);
        }
        $book->update($validatedRequest);
        return response()->json([
            'message' => 'Book updated successfully.',
            'book' => new BookResource($book),
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::where('id', $id)->where('user_id',Auth::id())->first();

        if(!$book){
            return response()->json([
                'message' => 'Book not found',
            ],404);
        }

        $book->delete();

        return response()->json(['message' => 'Book deleted successfully'], 200);
    }
}
