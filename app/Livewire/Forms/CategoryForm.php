<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class CategoryForm extends Form
{
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|string|max:10|unique:inventory_categories,prefix')]
    public $prefix = '';

    #[Validate('nullable|string|max:500')]
    public $description = '';

    public function resetFields()
    {
        $this->reset();
    }
}