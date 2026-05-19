<?php

namespace App\Livewire;

use Livewire\Component;
use App\Livewire\Forms\ItemForm;
use App\Livewire\Forms\CategoryForm;

class Inventario extends Component
{
    // 1. Instanciamos los Form Objects
    public ItemForm $itemForm;
    public CategoryForm $categoryForm;

    // 2. Variables de estado para los modales de la interfaz
    public $showModal = false;          // Controla el modal de herramientas/ítems
    public $showCategoryModal = false;  // Controla el modal de categorías 
    public $showDeleteModal = false; 

    // 3. Estado de edición
    public $editingId = null;           // Guarda el ID cuando se edita un registro
    public $isEditingCategory = false;  // Banderas de control de edición de categorías

    // 4. Vista previa de códigos
    public $previewCode = '';           // Almacena el código correlativo previo

    /**
     * Motor de guardado para el modal de Herramientas (Ítems)
     */
  public function saveItem()
    {
        // 1. Valida usando las reglas de ItemForm.php
        $this->itemForm->validate();

        // 2. Extraemos los datos limpios del formulario en un array
        $data = $this->itemForm->all();

        // 3. Obtenemos el prefijo de la categoría seleccionada (ej: AMP)
        $category = \App\Models\InventoryCategory::find($data['category_id']);
        $prefix = $category ? $category->prefix : 'HERR';

        // 4. Buscamos cuántas herramientas existen ya en esta categoría para calcular el correlativo
        $count = \App\Models\InventoryItem::where('category_id', $data['category_id'])->count();
        $nextNumber = str_pad($count + 1, 3, '0', STR_PAD_LEFT); // Formatea a 3 dígitos (ej: 001, 002)

        // 5. Inyectamos el código autogenerado al array antes de guardar (ej: AMP-001)
        $data['code'] = $prefix . '-' . $nextNumber;

        // 6. Creamos el registro oficial en la base de datos con su código incluido
        \App\Models\InventoryItem::create($data);

        // 7. Limpiamos el formulario y cerramos el modal
        $this->itemForm->reset();
        $this->showModal = false;

        session()->flash('message', 'Herramienta guardada con éxito.');
    }
    /**
     * Motor de guardado para el modal de Categorías
     */
    public function saveCategory()
    {
        // 1. Valida usando las reglas de CategoryForm.php
        $this->categoryForm->validate();

        // 2. Inserta la nueva categoría en la base de datos de manera oficial
        \App\Models\InventoryCategory::create($this->categoryForm->all());

        // 3. Limpia el formulario y apaga el modal
        $this->categoryForm->reset();
        $this->showCategoryModal = false;

        // Opcional: Alerta de éxito para la interfaz
        session()->flash('message', 'Categoría registrada con éxito.');
    }

    public function save()
    {
        $this->saveItem();
    }

    public function render()
    {
        return view('livewire.inventario', [
            'categories' => \App\Models\InventoryCategory::all(), 
            'items' => \App\Models\InventoryItem::paginate(10), 
        ]);
    }

    public function openCreate()
    {
        $this->itemForm->reset(); 
        $this->showModal = true; 
    }

    public function openCategoryCreate()
    {
        $this->categoryForm->reset(); 
        $this->showCategoryModal = true; 
    }
}