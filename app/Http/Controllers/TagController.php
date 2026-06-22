<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $tags = Tag::query()->latest()->paginate(10);
        return view('tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        //
        try {
            $tag = Tag::create($request->validated());

            return redirect()
                ->route('tags.index')
                ->with('success', 'Tag created successfully.');
        } catch (\Exception $exception) {
            return redirect()
                ->back()
                ->withErrors([ $exception->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        //
        return view('tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        //
        try {
            $tag->update($request->validated());

            return redirect()
                ->route('tags.index')
                ->with('success', 'Tag updated successfully.');
        } catch (\Exception $exception) {
            return redirect()
                ->back()
                ->withErrors([ $exception->getMessage()]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        //
        try {
            $tag->delete();

            return redirect()
                ->route('tags.index')
                ->with('success', 'Tag deleted successfully.');
        } catch (\Exception $exception) {
            return redirect()
                ->back()
                ->withErrors([$exception->getMessage()]);
        }
    }
}
