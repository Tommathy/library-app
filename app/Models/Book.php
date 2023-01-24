<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string title
 * @property string author
 * @property int publication_year
 * @property int number_of_pages
 *
 * @mixin Builder
 */
class Book extends Model
{
    use HasFactory;

    // Do not add created_at and update_at columns
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'author',
        'publication_year',
        'number_of_pages',
    ];

    /**
     * Get the loans for the book.
     *
     * @return HasMany
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Determine if the book has been loaned out to a user.
     *
     * @return bool
     */
    public function isLoanedOut(): bool
    {
        // Having a null end date would signify that the book has not been returned
        return $this->loans()->whereNull('loan_end_date')->exists();
    }
}
