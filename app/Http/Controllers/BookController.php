<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class BookController extends Controller
{
    /**
     * Show the books index page
     */
    public function index(): Response
    {
        // Render inertia template and provide it with array of books
        return Inertia::render('Books/Index', [
            'books' => Book::query()->orderByDesc('publication_year')->get(),
        ]);
    }

    /**
     * Show a single book
     *
     * @param Book $book
     * @return Response
     */
    public function show(Book $book): Response
    {
        // Render inertia template to show a single book and provide it with book data
        return Inertia::render('Books/Show', [
            'book' => Book::with('loans')->find($book->id),
            'canBeLoaned' => !$book->isLoanedOut(),
        ]);
    }

    /**
     * Show a form to create a book
     *
     * @return Response
     */
    public function create(): Response
    {
        // Render inertia template to show the form to create a book
        return Inertia::render('Books/Create');
    }

    /**
     * handle the incoming data for creating a new book.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Get the current year for validation as books cannot be published in the future
        $current_year = date('Y');

        // Validate the request to make sure that the data provide satisfies the rules below
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publication_year' => "required|integer|max:$current_year",
            'number_of_pages' => 'required|integer|min:1',
        ]);

        // Check to see if validation failed
        if ($validator->fails()) {
            // Redirect to the book create template again
            return to_route('books.create')
                // Provide error details from the validator
                ->withErrors($validator)
                // Return the data submitted to fill in the form again
                ->withInput();
        }

        // Create a new book from the request data
        $book = Book::create($request->all());

        // Redirect to show the newly created book
        return to_route('books.show', $book);
    }

    /**
     * Handle the incoming data to edit a book
     *
     * @param Book $book
     * @return Response
     */
    public function edit( Book $book ): Response
    {
        // Render the inertia template and provide it with the book data
        return Inertia::render('Books/Edit', [
            'book' => $book,
        ]);
    }

    /**
     * Update the specified book in storage.
     *
     * @param Request $request
     * @param Book $book
     * @return RedirectResponse
     */
    public function update(Request $request, Book $book): RedirectResponse
    {
        // Get the current year for validation as books cannot be published in the future
        $current_year = date('Y');

        // Validate the request to make sure that the data provide satisfies the rules below
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publication_year' => "required|integer|max:$current_year",
            'number_of_pages' => 'required|integer|min:1',
        ]);

        // Check to see if validation failed
        if ($validator->fails()) {
            // Redirect to the book edit template again
            return to_route('books.edit', [$book])
                // Provide error details from the validator
                ->withErrors($validator)
                // Return the data submitted to fill in the form again
                ->withInput();
        }

        // Update book with the new data
        $book->title = $request->title;
        $book->author = $request->author;
        $book->publication_year = $request->publication_year;
        $book->number_of_pages = $request->number_of_pages;
        // Save the changes to the book to the database
        $book->save();

        // Redirect to show the single book and provide the page with the appropriate data
        return to_route('books.show', ['book' => $book, 'message' =>'Book Updated Successfully']);

    }
}
