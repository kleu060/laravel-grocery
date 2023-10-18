<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\PnsProduct;

class ProductPrice extends Model
{
    use HasFactory;

    /**
     * @return BelongsTo
     */
    public function pnsProduct(): BelongsTo
    {
        return $this->belongsTo(PnsProduct::class);
    }
}
