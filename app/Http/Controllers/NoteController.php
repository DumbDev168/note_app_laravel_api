<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetNotesRequest;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Models\Note;
use Illuminate\Http\JsonResponse;

class NoteController extends Controller
{

    /**
     * get notes
     *
     * @param GetNotesRequest $request
     * @return JsonResponse
     */
    public function index(GetNotesRequest $request): JsonResponse
    {
        $data = $request->validated();
        $currentPage = $data['page'];
        $pageSize = $data['page_size'] ?? 15;

        $notes = Note::orderBy('id','desc')->simplePaginate(
            $pageSize,
            ['*'],
            'page',
            $currentPage
        );

        return $this->success($notes->getCollection());
    }


    /**
     * Create a note
     *
     * @param StoreNoteRequest $request
     * @return JsonResponse
     */
    public function store(StoreNoteRequest $request): JsonResponse
    {
        $note = Note::create($request->validated());

        return $this->success($note, 'Note has been created successfully!');
    }

    /**
     * Get a single note
     *
     * @param Note $note
     * @return JsonResponse
     */
    public function show(Note $note): JsonResponse
    {
        return $this->success($note);
    }

    /**
     * Update a note
     *
     * @param UpdateNoteRequest $request
     * @param Note $note
     * @return JsonResponse
     */
    public function update(UpdateNoteRequest $request, Note $note): JsonResponse
    {
        $note->update($request->validated());

        return $this->success($note->refresh(), "Note has been updated successfully!");
    }

    /**
     * Delete a note
     *
     * @param Note $note
     * @return JsonResponse
     */
    public function destroy(Note $note): JsonResponse
    {
        $note->delete();

        return $this->success($note->id, 'Note has been deleted successfully!');
    }
}
