<?php

namespace App\Livewire;

use Livewire\Component;
use App\Livewire\Forms\ItemForm;
use App\Livewire\Forms\CategoryForm;
use App\Models\InventoryItem;
use Livewire\WithPagination;

class Inventario extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategory = '';
    public $selectedCondition = '';

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

        try {
            // 6. Creamos el registro oficial en la base de datos con su código incluido
            \App\Models\InventoryItem::create($data);
        } catch (\Exception $e) {
            session()->flash('error', 'Ocurrió un error al guardar la herramienta (posible código duplicado). Inténtalo de nuevo.');
            return;
        }

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

    // --- AGREGA ESTOS MÉTODOS ABAJO DE TUS PROPIEDADES PÚBLICAS ---
    // Resetean la paginación automáticamente cuando cambia cualquier filtro
    public function updatingSearch() { $this->resetPage(); }
    public function updatingSelectedCategory() { $this->resetPage(); }
    public function updatingSelectedCondition() { $this->resetPage(); }


    // --- REEMPLAZA TU MÉTODO RENDER POR ESTE OPTIMIZADO ---
    public function render()
    {
        // Creamos una consulta base dinámica para los ítems
        $itemsQuery = \App\Models\InventoryItem::query();

        // 1. Filtro por barra de búsqueda (Busca en producto, marca o código si el usuario escribe algo)
        $itemsQuery->when($this->search, function ($query) {
            $query->where(function ($subQuery) {
                $subQuery->where('producto', 'like', '%' . $this->search . '%')
                         ->orWhere('marca', 'like', '%' . $this->search . '%')
                         ->orWhere('code', 'like', '%' . $this->search . '%');
            });
        });

        // 2. Filtro por Categoría seleccionada
        $itemsQuery->when($this->selectedCategory, function ($query) {
            $query->where('category_id', $this->selectedCategory);
        });

        // 3. Filtro por Condición (Bueno, Malogrado, etc.)
        $itemsQuery->when($this->selectedCondition, function ($query) {
            $query->where('condition', $this->selectedCondition); // Cambia 'condicion' por el nombre exacto de tu columna en la BD
        });

        return view('livewire.inventario', [
            'categories' => \App\Models\InventoryCategory::all(), 
            'items' => $itemsQuery->paginate(10), // Mandamos la consulta ya filtrada y paginada
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