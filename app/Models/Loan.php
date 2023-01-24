<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int book_id
 * @property string borrower_name
 * @property DateTime loan_start_date
 * @property DateTime loan_end_date
 *
 * @mixin Builder
 */
class Loan extends Model
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
        'book_id',
        'borrower_name',
        'loan_start_date',
        'loan_end_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'loan_start_date' => 'datetime:Y-m-d',
        'loan_end_date' => 'datetime:Y-m-d',
    ];

    /**
     * Get the book that owns the loan.
     *
     * @return BelongsTo
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
