<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarTrip extends Model
{
    use HasFactory;

    const TABLE_NAME = 'car_trips';
    const PUBLIC_FIELDS = [
        'id',
        'date',
        'miles',
        'total',
    ];

    protected $casts = [
        'date' => 'date:m/d/Y',
    ];

    protected $fillable = [
        'date',
        'miles',
    ];

    protected $table = self::TABLE_NAME;

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    /**
     * @return float
     */
    public function getTotalAttribute(): float
    {
        return $this->newQuery()
            ->where('car_id', $this->getAttribute('car_id'))
            ->sum('miles');
    }

}
