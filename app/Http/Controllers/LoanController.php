<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use DateTime;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class LoanController extends Controller
{
    /**
     * Show the loans index page
     */
    public function index(): Response
    {
        // Render inertia template and provide it with array of loans
        return Inertia::render('Loans/Index', [
            'books' => Book::query()->orderByDesc('publication_year')->take(10)->get(),
        ]);
    }

    /**
     * Show a single loan
     *
     * @param Book $book
     * @return Response
     */
    public function show(Book $book, Loan $loan): Response
    {
        // Render inertia template to show a single loan and provide it with book and loan data
        return Inertia::render('Loans/Show', [
            'book' => $book,
            'loan' => $loan,
        ]);
    }

    /**
     * Show a form to create a loan
     *
     * @return Response
     */
    public function create($book_id): Response
    {

        // Render inertia template to show the form to create a loan for a book
        return Inertia::render('Loans/Create', [
            'book' => Book::query()->find($book_id),
        ]);
    }

    /**
     * Show the form for creating a new loan.
     *
     * @param Request $request
     * @param Book $book
     * @return RedirectResponse
     */
    public function store(Request $request, Book $book): RedirectResponse
    {
        // Validate the request to make sure that the data provide satisfies the rules below
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|integer',
            'borrower_name' => 'required|string|max:255',
            'loan_start_date' => "required|date",
            'loan_end_date' => 'nullable|date',
        ]);

        // Check to see if validation failed
        if ($validator->fails()) {
            // Redirect to the loan create template again
            return to_route('books.loans.create', $book->id)
                // Provide error details from the validator
                ->withErrors($validator)
                // Return the data submitted to fill in the form again
                ->withInput();
        }

        // Create a new loan from the request data and add it to the book
        $loan = new Loan($request->all());
        $book->loans()->save($loan);

        // Redirect to show the book with the new loan data
        return to_route('books.show', $book);
    }

    /**
     * Handle the incoming data to edit a loan
     *
     * @param Book $book
     * @return Response
     */
    public function edit( Book $book ): Response
    {
        // Render the inertia template and provide it with the loan data
        return Inertia::render('Loans/Edit', [
            'book' => $book,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Book $book
     * @param Loan $loan
     * @return RedirectResponse
     */
    public function update(Request $request, Book $book, Loan $loan): RedirectResponse
    {

        // Check to see if the request is to return the book
        if ($request->get('returnBook') === true) {
            // The request is to return the book so update the loan_end_date to the date today
            $loan->loan_end_date = new DateTime();
        } else {
            // Validate the request to make sure that the data provide satisfies the rules below
            $validator = Validator::make($request->all(), [
                'borrower_name' => 'required|string|max:255',
                'loan_start_date' => "required|date",
                'loan_end_date' => 'nullable|date',
            ]);

            // Check to see if validation failed
            if ($validator->fails()) {
                // Redirect to the book template again
                return to_route('books.show', $book)
                    // Provide error details from the validator
                    ->withErrors($validator)
                    // Return the data submitted to fill in the form again
                    ->withInput();
            }

            // Update loan with the new data
            $loan->borrower_name = $request->borrower_name;
            $loan->loan_start_date = $request->loan_start_date;
            $loan->loan_end_date = $request->loan_end_date;
        }

        // Save the changes to the loan to the database
        $loan->save();

        // Redirect to show the single book and provide the page with the appropriate data
        return to_route('books.show', $book);

    }
}
