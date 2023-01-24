<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JsonException;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A test to make sure that the profile page can be displayed
     *
     * @return void
     */
    public function test_profile_page_is_displayed(): void
    {
        // Create a user to log in as
        $user = User::factory()->create();

        $response = $this
            // Log in as this user
            ->actingAs($user)
            // Get the profile page
            ->get('/profile');

        // Make sure the request was successful
        $response->assertOk();
    }

    /**
     * Test to make sure that the profile can be updated
     *
     * @return void
     * @throws JsonException
     */
    public function test_profile_information_can_be_updated(): void
    {
        // Create a user to log in as
        $user = User::factory()->create();

        $response = $this
            // Log in as this user
            ->actingAs($user)
            // Update request to path the name and email fields
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            // Make sure the session has no errors
            ->assertSessionHasNoErrors()
            // And it redirects to the profile page
            ->assertRedirect('/profile');

        // Update the user with fresh info from the database
        $user->refresh();

        // Make sure the name is the new name that has been updated
        $this->assertSame('Test User', $user->name);
        // Make sure the email is the new email that has been updated
        $this->assertSame('test@example.com', $user->email);
        // Make sure the email verified field is no longer null
        $this->assertNull($user->email_verified_at);
    }

    /**
     * Test that the email verification field remains unchanged after the email field is unchanged
     *
     * @throws JsonException
     */
    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        // Create a user to log in as
        $user = User::factory()->create();

        $response = $this
            // Log in as this user
            ->actingAs($user)
            // Update request to path the name and email fields
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            // Make sure the session has no errors
            ->assertSessionHasNoErrors()
            // And it redirects to the profile page
            ->assertRedirect('/profile');

        // Update the user variable with fresh info from the database and make sure
        // the email_verified_at is not null
        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    /**
     * Make sure that a user can delete their account
     *
     * @throws JsonException
     */
    public function test_user_can_delete_their_account(): void
    {
        // Create a user to log in as
        $user = User::factory()->create();

        $response = $this
            // Log in as this user
            ->actingAs($user)
            // Send a delete request to the profile page with the password to confirm
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            // Make sure the session has no errors
            ->assertSessionHasNoErrors()
            // And it redirects to the root index page
            ->assertRedirect('/');

        // Make sure the user is now a quest and not authorized
        $this->assertGuest();
        // Pull fresh information from the database about the user and make sure that is it null.
        $this->assertNull($user->fresh());
    }

    /**
     * Test invalid password on profile deletion
     *
     * @return void
     */
    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        // Create a user to log in as
        $user = User::factory()->create();

        $response = $this
            // Log in as this user
            ->actingAs($user)
            // Simulate the previous page the user is coming from
            ->from('/profile')
            // Send a delete request to the profile page with the wrong password for confirmation
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            // Make sure the session has no errors
            ->assertSessionHasErrors('password')
            // And it redirects to the profile page
            ->assertRedirect('/profile');

        // Pull fresh information from the database about the user and make sure that is it null.
        $this->assertNotNull($user->fresh());
    }
}
