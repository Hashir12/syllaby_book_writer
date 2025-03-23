<?php

namespace App\Http\Controllers;

use App\Http\Resources\SectionResource;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request  $request)
    {
        $section = Section::with('book','parent','children');

        if ($request->has('search')) {
            $section = $section->where('title','like','%' . $request->get('search') . '%');
        }
        $section = $section->orderBy('id','desc')->paginate(10);

        return SectionResource::collection($section);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedRequest = $request->validate([
            'book_id' => 'required',
            'parent_id' => 'nullable',
            'title' => 'required|string',
            'content' => 'required',
        ]);

        $section = new Section();
        $section = $section->setData($validatedRequest)->saveOrUpdate();

        return response()->json([
            'message' => 'Section created successfully.',
            'section' => new SectionResource($section['data']),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $section = new Section();
        $section = $section->setId($id)->getSection('fetch');

        if (!$section) {
            $response['message'] = 'Section not found';
            $statusCode = 404;
        } else {
            $response['data'] = new SectionResource($section);
            $statusCode = 200;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedRequest = $request->validate([
            'title' => 'required|string',
            'content' => 'required',
        ]);

        $section = new Section();
        $section = $section->setId($id)->getSection();

        if ($section) {
            $section = $section->setData($validatedRequest)->saveOrUpdate();
            $response['message'] = "Book updated successfully";
            $response['book'] = new SectionResource($section['data']);
            $statusCode = 200;
        } else {
            $response['message'] = 'Section not found';
            $statusCode = 404;
        }
        return response()->json($response,$statusCode);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $section = new Section();
        $section = $section->setId($id)->getSection('fetch');

        if (!$section) {
            $response['message'] = 'Section not found';
            $statusCode = 404;
        } else {
            $section->delete();
            $response['message'] = 'Section deleted successfully';
            $statusCode = 200;
        }
        return response()->json($response, $statusCode);
    }

    public function createChildSection(Request $request)
    {
        $validatedRequest = $request->validate([
            'book_id' => 'required',
            'parent_id' => 'required',
            'title' => 'required|string',
            'content' => 'required',
        ]);

        $section = new Section();
        $section = $section->setData($validatedRequest)->saveOrUpdate();

        return response()->json([
            'message' => 'Section created successfully.',
            'section' => new SectionResource($section['data']),
        ]);
    }
}
