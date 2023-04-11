<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    use HasFactory;

    const TABLE_NAME = 'cars';
    const PUBLIC_FIELDS = [
        'id',
        'make',
        'model',
        'year',
    ];

    protected $casts = [
        'year' => 'integer'
    ];

    protected $fillable = [
        'make',
        'model',
        'year',
    ];

    protected $table = self::TABLE_NAME;

    /**
     * @return HasMany
     */
    public function trips(): HasMany
    {
        return $this->hasMany(CarTrip::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
