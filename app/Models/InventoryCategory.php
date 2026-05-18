<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryCategory extends Model
{
    protected $fillable = ['name', 'prefix', 'description'];

    public function items()
    {
        return $this->hasMany(InventoryItem::class, 'category_id');
    }

    public function generateNextCode(): string
    {
        $last = $this->items()
        ->orderByRaw('CAST(SUBSTR(code, INSTR(code, "-") + 1) AS UNSIGNED) DESC')
        ->first();

        $nextNumber = $last
            ? (int) explode('-', $last->code)[1] + 1
            : 1;

        return $this->prefix . '-' . str_pad($nextNumber, 2, '0', STR_PAD_LEFT);
    }
}