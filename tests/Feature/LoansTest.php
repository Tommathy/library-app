<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoansTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Check to see if the loan creation page can be displayed
     *
     * @return void
     */
    public function test_new_loan_creation_is_displayed(): void
    {

        // Create a user to log in as
        $user = User::factory()->create();
        // Create a book to loan out
        $book = Book::factory()->create();

        $response = $this
            // Log in as this user
            ->actingAs($user)
            // Go to the page to loan this book out
            ->get(route('books.loans.create', $book));

        // Check to make sure that we can access this page
        $response->assertOk();
    }

    /**
     * Make sure that you cannot create a loan logged out
     *
     * @return void
     */
    public function test_do_not_allow_creation_of_loan_logged_out(): void
    {
        // Create a book to loan out
        $book = Book::factory()->create();

        // Create dummy loan data to be sent
        $loanData = [
            "book_id" => $book->id,
            "borrower_name" => "Tom",
            "loan_start_date" => "2023-01-13",
            "loan_end_date" => null
        ];

        $response = $this
            // Send a SJON request to the store endpoint with the dummy loan data
            ->json('POST', route('books.loans.store', $book), $loanData);

        // Make sure that the response code is unauthorized
        $response->assertUnauthorized();

        // Make sure that no new loan was created in the database
        $this->assertDatabaseMissing('loans', $loanData);
    }

    /**
     * Check to see that a loan can be created when logged in
     *
     * @return void
     */
    public function test_new_loan_can_be_created(): void
    {
        // Create a user to log in as
        $user = User::factory()->create();
        // Create a book to loan out
        $book = Book::factory()->create();

        // Create dummy loan data to be sent
        $loanData = [
            "book_id" => $book->id,
            "borrower_name" => "Tom",
            "loan_start_date" => "2023-01-13",
            "loan_end_date" => null
        ];

        $response = $this
            // Log in as this user
            ->actingAs($user)
            // Send a JSON request to the store endpoint with the dummy loan data
            ->json('POST', route('books.loans.store', $book), $loanData);

        // Make sure that the response code is a redirect and redirecting to show the book
        $response->assertRedirect(route('books.show', $book));

        // Make sure that a new loan was created in the database
        $this->assertDatabaseHas('loans', $loanData);
    }

    /**
     * Make sure a loaned book can be marked as returned
     *
     * @return void
     */
    public function test_loaned_book_can_be_returned(): void
    {
        // Create a user to log in as
        $user = User::factory()->create();
        // Create a book to loan out
        $book = Book::factory()->create();

        // Create dummy loan data to be sent
        $loanData = [
            "book_id" => $book->id,
            "borrower_name" => "Tom",
            "loan_start_date" => "2023-01-13",
            "loan_end_date" => null
        ];

        // Set the loan details for the book
        $loan = new Loan($loanData);
        // Add the loan data to the book
        $book->loans()->save($loan);

        // Make sure that the loan data was saved to the database
        $this->assertDatabaseHas('loans', $loanData);

        // Create dummy request data to return the book
        $returnLoanData = [
            "returnBook" => true
        ];

        $response = $this
            // Log in as this user
            ->actingAs($user)
            // Send JSON request using dummy return data to update the loan and return the book
            ->json('PATCH', route('books.loans.update', [$book, $loan]), $returnLoanData);

        // Make sure that the response is a redirect to show the book
        $response->assertRedirect(route('books.show', $book));

        // Pull updated loan data form the database
        $loan->refresh();

        // Make sure that the model still exists
        $this->assertModelExists($loan);
        // Make sure that the end date for the loan was updated. This means to not be null as it is by default
        $this->assertNotEmpty($loan->loan_end_date);

        // Make sure that the new return date is the date today
        self::assertEquals(date('Y-m-d'), date_format( $loan->loan_end_date, 'Y-m-d' ));
    }
}
