<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    protected $fillable = [
        'category_id', 'code', 'brand', 'model',
        'serial_number', 'description', 'condition', 'observations',
    ];

    public function category()
    {
        return $this->belongsTo(InventoryCategory::class, 'category_id');
    }

    public function conditionColor(): string
    {
        return match($this->condition) {
            'bueno'       => 'text-green-600 bg-green-50',
            'malogrado'   => 'text-red-600 bg-red-50',
            'en_revision' => 'text-yellow-600 bg-yellow-50',
            default       => 'text-gray-600 bg-gray-50',
        };
    }

    public function conditionLabel(): string
    {
        return match($this->condition) {
            'bueno'       => 'Bueno',
            'malogrado'   => 'Malogrado',
            'en_revision' => 'En Revisión',
            default       => '-',
        };
    }
}