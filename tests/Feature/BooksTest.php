<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class BooksTest extends TestCase
{

    use RefreshDatabase;

    /**
     * Check to see if the book index page can be displayed
     *
     * @return void
     */
    public function test_books_index_is_displayed(): void
    {
        // Create a user to log in as
        $user = User::factory()->create();

        $response = $this
            // Log in as this user
            ->actingAs($user)
            // Go to the book index page
            ->get('/books');

        // Check to make sure that we can access this page
        $response->assertOk();
    }

    /**
     * Check to see that there is no books displayed when the table is empty
     *
     * @return void
     */
    public function test_books_index_has_no_books(): void
    {
        // Create a user to log in as
        $user = User::factory()->create();

        $this
            // Log in as this user
            ->actingAs($user)
            // Go to the book index page
            ->get('/books')
            // Check that we have the inertia app
            ->assertInertia(fn(AssertableInertia $page) => $page
                // Check that we have the inertia component
                ->component('Books/Index')
                // Make sure that there are no books saved
                ->has('books', 0)
            );

    }

    /**
     * Check to make sure that books get displayed once they are created
     *
     * @return void
     */
    public function test_books_index_has_books_after_creation(): void
    {
        // Create a user to log in as
        $user = User::factory()->create();
        // Create three books and save them
        Book::factory()->count(3)->create();

        $this
            // Log in as this user
            ->actingAs($user)
            // Go to the book index page
            ->get('/books')
            // Check that we have the inertia app
            ->assertInertia(fn(AssertableInertia $page) => $page
                // Check that we have the inertia component
                ->component('Books/Index')
                // Make sure we have the three books created from before
                ->has('books', 3)
            );
    }

    /**
     * Check to make sure that unauthorized users cannot create books
     *
     * @return void
     */
    public function test_do_not_allow_creation_of_book_logged_out(): void
    {

        // Create dummy book
        $bookData = [
            "title" => "Test Book Name",
            "author" => "Joe Blogs",
            "publication_year" => "2018",
            "number_of_pages" => "85"
        ];

        $response = $this
            // Make a request to create a book
            ->post(route('books.store'), $bookData);

        // Check to make sure the response is to redirect to the login page
        $response->assertRedirect('/login');

        // Make sure that no data was saved to the database
        $this->assertDatabaseMissing('books', $bookData);
    }

    /**
     * Make sure that a user can create a book when logged in
     *
     * @return void
     */
    public function test_allow_creation_of_book_logged_in(): void
    {
        // Create a user to log in as
        $user = User::factory()->create();

        // Create dummy book
        $bookData = [
            "title" => "Test Book Name",
            "author" => "Joe Blogs",
            "publication_year" => "2018",
            "number_of_pages" => "85"
        ];

        $response = $this
            // Log in as this user
            ->actingAs($user)
            // Send a post request to the store endpoint with the dummy book data
            ->post(route('books.store'), $bookData);

        // Make sure that the response code is a redirect
        $response->assertRedirect();

        // Make sure that a new book was created in the database
        $this->assertDatabaseHas('books', $bookData);
    }

}
