<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $books = Book::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('books.index', compact('books'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        $data['user_id'] = $request->user()->id;

        Book::create($data);

        return redirect()
            ->route('books.index')
            ->with('success', 'Book added successfully.');
    }

    public function edit(Request $request, int $book)
    {
        $bookRecord = $this->ownedBook($request, $book);

        $books = Book::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('books.index', compact('books', 'bookRecord'));
    }

    public function update(Request $request, int $book)
    {
        $record = $this->ownedBook($request, $book);

        $record->update($this->validatedData($request));

        return redirect()
            ->route('books.index')
            ->with('success', 'Book updated successfully.');
    }

    public function destroy(Request $request, int $book)
    {
        $this->ownedBook($request, $book)->delete();

        return redirect()
            ->route('books.index')
            ->with('success', 'Book deleted successfully.');
    }

    private function ownedBook(Request $request, int $book): Book
    {
        return Book::where('user_id', $request->user()->id)
            ->findOrFail($book);
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'author' => ['required', 'string', 'max:100'],
            'pages' => ['required', 'integer', 'min:1', 'max:100000'],
        ]);
    }
}
