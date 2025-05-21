<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Translation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pack_id',
        'from_translation',
        'to_translation',
    ];

    /**
     * Ensure the from_translation is always stored in lowercase.
     *
     * @param  string  $value
     * @return void
     */
    public function setFromTranslationAttribute(string $value): void
    {
        $this->attributes['from_translation'] = strtolower($value);
    }

    /**
     * Ensure the to_translation is always stored in lowercase.
     *
     * @param  string  $value
     * @return void
     */
    public function setToTranslationAttribute(string $value): void
    {
        $this->attributes['to_translation'] = strtolower($value);
    }

    public function pack(): BelongsTo
    {
        return $this->belongsTo(Pack::class);
    }
}
