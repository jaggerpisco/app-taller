<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ItemForm extends Form
{
    #[Validate('required|exists:inventory_categories,id')]
    public $category_id = '';

    #[Validate('required|string|max:100')]
    public $brand = '';

    #[Validate('nullable|string|max:100')]
    public $model = '';

    #[Validate('nullable|string|max:100')]
    public $serial_number = '';

    #[Validate('nullable|string|max:255')]
    public $description = '';

    #[Validate('required|in:bueno,malogrado,en_revision')]
    public $condition = 'bueno';

    #[Validate('nullable|string|max:500')]
    public $observations = '';

    // Método para limpiar el formulario
    public function resetFields()
    {
        $this->reset();
    }
}