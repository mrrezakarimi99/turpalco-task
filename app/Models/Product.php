<?php

namespace App\Models;

use App\Scopes\ProductFilterAvailable;
use App\Scopes\ProductFilterEndTime;
use App\Scopes\ProductFilterStartTime;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory, Filterable;

    protected $fillable = ['name', 'description', 'thumbnail'];

    protected $appends = ['minimumPrice'];

    /**
     * @return HasMany
     */
    public function availabilities(): HasMany
    {
        return $this->hasMany(Availability::class);
    }

    public function minimumPrice(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return $this->availabilities->min('price');
            },
        );
    }

    protected function getFilters(): array
    {
        return [
            ProductFilterStartTime::class,
            ProductFilterEndTime::class,
            ProductFilterAvailable::class,
        ];
    }
}
