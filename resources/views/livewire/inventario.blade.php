
<div class="max-w-7xl mx-auto px-4 py-8">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Inventario de Herramientas</h1>
            <p class="text-sm text-gray-500 mt-1">Gestión de equipos e instrumentos</p>
        </div>
        <button wire:click="openCreate"
            class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Añadir Ítem
        </button>
    </div>

    {{-- FILTROS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text"
                placeholder="Buscar código, marca, modelo, serie..."
                class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"/>
        </div>
        <select wire:model.live="filterCategory"
            class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="">Todas las categorías</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
        <select wire:model.live="filterCondition"
            class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="">Todas las condiciones</option>
            <option value="bueno">Bueno</option>
            <option value="malogrado">Malogrado</option>
            <option value="en_revision">En Revisión</option>
        </select>
    </div>

    {{-- TABLA --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 uppercase tracking-wide text-xs">Código</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 uppercase tracking-wide text-xs">Categoría</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 uppercase tracking-wide text-xs">Marca</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 uppercase tracking-wide text-xs">Modelo</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 uppercase tracking-wide text-xs">N° Serie</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 uppercase tracking-wide text-xs">Condición</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600 uppercase tracking-wide text-xs">Descripción</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($items as $item)
                        <tr wire:key="item-{{ $item->id }}" class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-mono font-semibold text-gray-800">{{ $item->code }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $item->category?->name ?? 'Sin categoría' }}</td>
                            <td class="px-4 py-3 text-gray-800 font-medium">{{ $item->brand }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $item->model ?? '-' }}</td>
                            <td class="px-4 py-3 font-mono text-gray-600 text-xs">{{ $item->serial_number ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $item->conditionColor() }}">
                                    {{ $item->conditionLabel() }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500 max-w-xs truncate">{{ $item->description ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2 justify-end">
                                    <button wire:click="openEdit({{ $item->id }})" class="text-blue-600 hover:text-blue-800 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $item->id }})" class="text-red-500 hover:text-red-700 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-gray-400">
                                <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                No hay ítems registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($items->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $items->links() }}
            </div>
        @endif
    </div>

    {{-- MODAL CREAR / EDITAR --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h2 class="text-base font-semibold text-gray-900">
                        {{ $editingId ? 'Editar Ítem' : 'Nuevo Ítem' }}
                    </h2>
                    <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-5 space-y-4">
                    @if($previewCode)
                        <div class="flex items-center gap-2 bg-green-50 border border-green-200 rounded-lg px-3 py-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span class="text-sm text-green-700">Código asignado: <strong class="font-mono">{{ $previewCode }}</strong></span>
                        </div>
                    @endif

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Categoría <span class="text-red-500">*</span></label>
                        <select wire:model.live="category_id"
                            class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('category_id') border-red-400 @enderror"
                            {{ $editingId ? 'disabled' : '' }}>
                            <option value="">Seleccionar categoría...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->prefix }})</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Marca <span class="text-red-500">*</span></label>
                            <input wire:model="brand" type="text" placeholder="Ej. FLUKE"
                                class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 @error('brand') border-red-400 @enderror"/>
                            @error('brand') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Modelo</label>
                            <input wire:model="model" type="text" placeholder="Ej. 302+"
                                class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"/>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">N° de Serie</label>
                            <input wire:model="serial_number" type="text" placeholder="Ej. 66211326MV"
                                class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"/>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Condición <span class="text-red-500">*</span></label>
                            <select wire:model="condition"
                                class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="bueno">Bueno</option>
                                <option value="malogrado">Malogrado</option>
                                <option value="en_revision">En Revisión</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Descripción</label>
                        <input wire:model="description" type="text" placeholder="Ej. tiene accesorios"
                            class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"/>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Observaciones</label>
                        <textarea wire:model="observations" rows="2"
                            placeholder="Ej. no tiene sus cabelcitos..."
                            class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 resize-none"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100">
                    <button wire:click="$set('showModal', false)"
                        class="text-sm px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                        Cancelar
                    </button>
                    <button wire:click="save"
                        class="text-sm px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium transition">
                        {{ $editingId ? 'Guardar cambios' : 'Crear ítem' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL ELIMINAR --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm p-6 text-center">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-1">¿Eliminar ítem?</h3>
                <p class="text-sm text-gray-500 mb-6">Esta acción no se puede deshacer.</p>
                <div class="flex gap-3 justify-center">
                    <button wire:click="$set('showDeleteModal', false)"
                        class="text-sm px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                        Cancelar
                    </button>
                    <button wire:click="delete"
                        class="text-sm px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-medium transition">
                        Sí, eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
