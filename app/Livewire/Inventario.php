<?php

namespace App\Livewire;

use App\Models\InventoryCategory;
use App\Models\InventoryItem;
use Livewire\Component;
use Livewire\WithPagination;

class Inventario extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterCategory = '';
    public string $filterCondition = '';

    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    public int|string $category_id = '';
    public string $brand = '';
    public string $model = '';
    public string $serial_number = '';
    public string $description = '';
    public string $condition = 'bueno';
    public string $observations = '';
    public string $previewCode = '';

    public bool $showCategoryModal = false;
    public bool $isEditingCategory = false;
    public ?int $editingCategoryId = null;
    public string $newCategoryName = '';
    public string $newCategoryPrefix = '';
    public string $newCategoryDescription = '';

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedFilterCategory(): void { $this->resetPage(); }
    public function updatedFilterCondition(): void { $this->resetPage(); }

    public function updatedCategoryId(): void
    {
        if ($this->category_id && !$this->editingId) {
            $cat = InventoryCategory::find($this->category_id);
            $this->previewCode = $cat ? $cat->generateNextCode() : '';
        }
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->editingId = null;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $item = InventoryItem::findOrFail($id);
        $this->editingId     = $id;
        $this->category_id   = $item->category_id;
        $this->brand         = $item->brand;
        $this->model         = $item->model ?? '';
        $this->serial_number = $item->serial_number ?? '';
        $this->description   = $item->description ?? '';
        $this->condition     = $item->condition;
        $this->observations  = $item->observations ?? '';
        $this->previewCode   = $item->code;
        $this->showModal     = true;
    }

    public function save(): void
    {
        $this->validate([
            'category_id'   => 'required|exists:inventory_categories,id',
            'brand'         => 'required|string|max:100',
            'model'         => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'description'   => 'nullable|string|max:255',
            'condition'     => 'required|in:bueno,malogrado,en_revision',
            'observations'  => 'nullable|string|max:500',
        ]);

        if ($this->editingId) {
            InventoryItem::findOrFail($this->editingId)->update([
                'category_id'   => $this->category_id,
                'brand'         => $this->brand,
                'model'         => $this->model ?: null,
                'serial_number' => $this->serial_number ?: null,
                'description'   => $this->description ?: null,
                'condition'     => $this->condition,
                'observations'  => $this->observations ?: null,
            ]);
        } else {
            $category = InventoryCategory::findOrFail($this->category_id);
            InventoryItem::create([
                'category_id'   => $this->category_id,
                'code'          => $category->generateNextCode(),
                'brand'         => $this->brand,
                'model'         => $this->model ?: null,
                'serial_number' => $this->serial_number ?: null,
                'description'   => $this->description ?: null,
                'condition'     => $this->condition,
                'observations'  => $this->observations ?: null,
            ]);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId      = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deletingId) {
            InventoryItem::findOrFail($this->deletingId)->delete();
        }
        $this->showDeleteModal = false;
        $this->deletingId      = null;
    }

    public function openCategoryCreate(): void
    {
        $this->resetCategoryForm();
        $this->isEditingCategory = false;
        $this->showCategoryModal = true;
    }

    public function openCategoryEdit(int $id): void
    {
        $this->resetCategoryForm();
        $category = InventoryCategory::findOrFail($id);
        
        $this->editingCategoryId = $id;
        $this->newCategoryName = $category->name;
        $this->newCategoryPrefix = $category->prefix;
        $this->newCategoryDescription = $category->description ?? '';
        
        $this->isEditingCategory = true;
        $this->showCategoryModal = true;
    }

    public function saveCategory(): void
    {
        $this->validate([
            'newCategoryName'        => 'required|string|min:3|max:100',
            'newCategoryPrefix'      => 'required|string|max:10|unique:inventory_categories,prefix',
            'newCategoryDescription' => 'nullable|string|max:255',
        ]);

        InventoryCategory::create([
            'name'        => $this->newCategoryName,
            'prefix'      => strtoupper($this->newCategoryPrefix),
            'description' => $this->newCategoryDescription ?: null,
        ]);

        $this->showCategoryModal = false;
        $this->resetCategoryForm();
        session()->flash('category_success', '¡Nueva categoría guardada con éxito!');
    }

    public function updateCategory(): void
    {
        $this->validate([
            'newCategoryName'        => 'required|string|min:3|max:100',
            'newCategoryPrefix'      => 'required|string|max:10|unique:inventory_categories,prefix,' . $this->editingCategoryId,
            'newCategoryDescription' => 'nullable|string|max:255',
        ]);

        $category = InventoryCategory::findOrFail($this->editingCategoryId);
        $category->update([
            'name'        => $this->newCategoryName,
            'prefix'      => strtoupper($this->newCategoryPrefix),
            'description' => $this->newCategoryDescription ?: null,
        ]);

        $this->showCategoryModal = false;
        $this->resetCategoryForm();
        session()->flash('category_success', '¡Categoría actualizada correctamente!');
    }

    private function resetForm(): void
    {
        $this->category_id   = '';
        $this->brand         = '';
        $this->model         = '';
        $this->serial_number = '';
        $this->description   = '';
        $this->condition     = 'bueno';
        $this->observations  = '';
        $this->previewCode   = '';
        $this->resetValidation();
    }

    private function resetCategoryForm(): void
    {
        $this->newCategoryName = '';
        $this->newCategoryPrefix = '';
        $this->newCategoryDescription = '';
        $this->editingCategoryId = null;
        $this->resetValidation();
    }

    public function render()
    {
        $items = InventoryItem::with('category')
            ->when($this->search, fn($q) =>
                $q->where('code', 'like', "%{$this->search}%")
                  ->orWhere('brand', 'like', "%{$this->search}%")
                  ->orWhere('model', 'like', "%{$this->search}%")
                  ->orWhere('serial_number', 'like', "%{$this->search}%")
            )
            ->when($this->filterCategory, fn($q) =>
                $q->where('category_id', $this->filterCategory)
            )
            ->when($this->filterCondition, fn($q) =>
                $q->where('condition', $this->filterCondition)
            )
            ->latest()
            ->paginate(10);

        $categories = InventoryCategory::orderBy('name')->get();

        return view('livewire.inventario', compact('items', 'categories'));
    }
}