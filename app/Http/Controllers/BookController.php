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
        $validatedRequest = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $book = new Book();
        $book = $book->setUser(Auth::id())->setData($validatedRequest)->saveOrUpdateBook();

        return response()->json([
            'message' => 'Book Created Successfully',
            'book' => new BookResource($book['data']),
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = new Book();
        $book = $book->setUser(Auth::id())->setBookId($id)->getBook('fetch');

        if(!$book){
            $response['message'] = 'Book not found';
            $statusCode = 404;
        } else {
            $response['book'] = new BookResource($book);
            $statusCode = 200;
        }

        return response()->json($response,$statusCode);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedRequest = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $book = new Book();
        $book = $book->setUser(Auth::id())->setBookId($id)->getBook();

        if($book) {
            $book = $book->setData($validatedRequest)->saveOrUpdateBook();
            $response['message'] = "Book updated successfully";
            $response['book'] = new BookResource($book['data']);
            $statusCode = 200;
        } else {
            $response['message'] = "Book not found";
            $statusCode = 404;
        }
        return response()->json($response,$statusCode);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = new Book();
        $book = $book->setUser(Auth::id())->setBookId($id)->getBook('fetch');

        if(!$book){
            $response['message'] = 'Book not found';
            $statusCode = 404;
        } else {
            $book->delete();
            $response['message'] = 'Book deleted successfully';
            $statusCode = 200;
        }


        return response()->json($response, $statusCode);
    }
}
