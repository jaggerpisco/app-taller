<div class="max-w-7xl mx-auto px-2 sm:px-4 py-4 sm:py-8">

    {{-- HEADER RESPONSIVO --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div class="w-full sm:w-auto">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">Inventario de Herramientas</h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">Gestión de equipos e instrumentos del taller</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
            <button wire:click="openCategoryCreate"
                class="inline-flex items-center justify-center gap-2 bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 text-indigo-700 text-sm font-semibold px-4 py-2.5 rounded-xl transition duration-150 shadow-sm shadow-indigo-100/50 w-full sm:w-auto">
                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                + Configurar Categorías
            </button>

            <button wire:click="openCreate"
                class="inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition w-full sm:w-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Añadir Ítem
            </button>
        </div>
    </div>

    {{-- NOTIFICACIÓN DE ÉXITO --}}
    @if (session()->has('category_success'))
        <div class="mb-6 flex items-center gap-2 bg-green-50 border border-green-200 rounded-xl px-4 py-3 text-sm text-green-700 font-medium">
            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('category_success') }}
        </div>
    @endif

    {{-- FILTROS RESPONSIVOS --}}
    <div class="flex flex-col sm:grid sm:grid-cols-3 gap-3 mb-6">
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" type="text"
                placeholder="Buscar código, marca, modelo, serie..."
                class="w-full pl-9 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/10 transition"/>
        </div>
        
        <select wire:model.live="filterCategory"
            class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/10 transition text-gray-900">
            <option value="">Todas las categorías</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="filterCondition"
            class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/10 transition text-gray-900">
            <option value="">Todas las condiciones</option>
            <option value="bueno">Bueno</option>
            <option value="malogrado">Malogrado</option>
            <option value="en_revision">En Revisión</option>
        </select>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm shadow-gray-100/50">
        
        {{-- VISTA ESCRITORIO: Tabla clásica con Efecto puro de Opacidad --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50/50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-4 font-semibold text-gray-600 uppercase tracking-wide text-xs">Código</th>
                        <th class="text-left px-4 py-4 font-semibold text-gray-600 uppercase tracking-wide text-xs">Categoría</th>
                        <th class="text-left px-4 py-4 font-semibold text-gray-600 uppercase tracking-wide text-xs">Marca</th>
                        <th class="text-left px-4 py-4 font-semibold text-gray-600 uppercase tracking-wide text-xs">Modelo</th>
                        <th class="text-left px-4 py-4 font-semibold text-gray-600 uppercase tracking-wide text-xs">N° Serie</th>
                        <th class="text-left px-4 py-4 font-semibold text-gray-600 uppercase tracking-wide text-xs">Condición</th>
                        <th class="text-left px-4 py-4 font-semibold text-gray-600 uppercase tracking-wide text-xs">Descripción</th>
                        <th class="px-4 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($items as $item)
                        <tr wire:key="item-desktop-{{ $item->id }}" class="hover:bg-slate-50/85 transition duration-150 group">
                            <td class="px-4 py-3.5 font-mono font-semibold text-gray-900">{{ $item->code }}</td>
                            <td class="px-4 py-3.5 text-gray-600">{{ $item->category?->name ?? 'Sin categoría' }}</td>
                            <td class="px-4 py-3.5 text-gray-800 font-medium">{{ $item->brand }}</td>
                            <td class="px-4 py-3.5 text-gray-600">{{ $item->model ?? '-' }}</td>
                            <td class="px-4 py-3.5 font-mono text-gray-600 text-xs">{{ $item->serial_number ?? '-' }}</td>
                            <td class="px-4 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $item->conditionColor() }}">
                                    {{ $item->conditionLabel() }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-gray-500 max-w-xs truncate">{{ $item->description ?? '-' }}</td>
                            
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                {{-- SOLUCIÓN FINAL: Solo usamos opacity y transition, tal como en tu php.txt --}}
                                <div class="inline-flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    
                                    <button wire:click="openEdit({{ $item->id }})" 
                                        class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 bg-white border border-gray-100 rounded-xl transition duration-150 shadow-sm flex items-center justify-center" 
                                        title="Editar Herramienta">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </button>

                                    <button wire:click="confirmDelete({{ $item->id }})" 
                                        class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 bg-white border border-gray-100 rounded-xl transition duration-150 shadow-sm flex items-center justify-center" 
                                        title="Eliminar de Inventario">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-16 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                No hay herramientas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- VISTA MÓVIL --}}
        <div class="md:hidden divide-y divide-gray-100">
            @forelse($items as $item)
                <div wire:key="item-mobile-{{ $item->id }}" class="p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="font-mono font-bold text-gray-950 text-base">{{ $item->code }}</span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $item->conditionColor() }}">
                            {{ $item->conditionLabel() }}
                        </span>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900">{{ $item->brand }} <span class="text-gray-600 font-normal">{{ $item->model ? '/ '.$item->model : '' }}</span></h4>
                        <p class="text-xs text-gray-500 font-mono mt-1">N° Serie: {{ $item->serial_number ?? 'S/N' }}</p>
                    </div>
                    <div class="flex items-start gap-4 text-xs text-gray-600">
                        <div class="flex-1">
                            <span class="font-medium text-gray-700">Categoría:</span> {{ $item->category?->name ?? 'S/C' }}
                        </div>
                        <div class="flex-1 text-right truncate">
                            {{ $item->description ?? '-' }}
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2 pt-3 border-t border-gray-50">
                        <button wire:click="openEdit({{ $item->id }})" class="flex-1 inline-flex items-center justify-center gap-1.5 text-sm font-medium px-3 py-2 bg-blue-50 text-blue-700 rounded-xl">
                            Editar
                        </button>
                        <button wire:click="confirmDelete({{ $item->id }})" class="flex-1 inline-flex items-center justify-center gap-1.5 text-sm font-medium px-3 py-2 bg-red-50 text-red-700 rounded-xl">
                            Eliminar
                        </button>
                    </div>
                </div>
            @empty
                <div class="px-4 py-12 text-center text-gray-400">
                    No hay registros que coincidan con la búsqueda.
                </div>
            @endforelse
        </div>

        @if($items->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 bg-gray-50/50">
                {{ $items->links() }}
            </div>
        @endif
    </div>

    {{-- MODAL CREAR / EDITAR ÍTEM --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/20 backdrop-blur-sm px-3.5 transition-all duration-300">
            <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 w-full max-w-lg overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50/30">
                    <h2 class="text-base font-bold text-gray-900 tracking-tight">
                        {{ $editingId ? 'Editar Herramienta' : 'Registrar Nueva Herramienta' }}
                    </h2>
                    <button wire:click="$set('showModal', false)" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-5 space-y-4">
                    @if($previewCode)
                        <div class="flex items-center gap-2.5 bg-green-50 border border-green-100 rounded-xl px-4 py-2.5">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span class="text-sm text-green-800">Código de secuencia: <strong class="font-mono text-base font-bold">{{ $previewCode }}</strong></span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-800 mb-1.5">Categoría *</label>
                            <select wire:model.live="category_id"
                                class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/10 transition" {{ $editingId ? 'disabled' : '' }}>
                                <option value="">Seleccionar...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->prefix }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-800 mb-1.5">Condición *</label>
                            <select wire:model="condition"
                                class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/10 transition">
                                <option value="bueno">Bueno</option>
                                <option value="malogrado">Malogrado</option>
                                <option value="en_revision">En Revisión</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-col sm:grid sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-800 mb-1.5">Marca *</label>
                            <input wire:model="brand" type="text" class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500/15"/>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-800 mb-1.5">Modelo</label>
                            <input wire:model="model" type="text" class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500/15"/>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-800 mb-1.5">N° Serie</label>
                            <input wire:model="serial_number" type="text" class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 font-mono text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500/15"/>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-800 mb-1.5">Descripción Breve</label>
                            <input wire:model="description" type="text" class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500/15"/>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-800 mb-1.5">Observaciones</label>
                            <textarea wire:model="observations" rows="2" class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-gray-900 resize-none focus:outline-none focus:ring-2 focus:ring-green-500/15"></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2.5 px-6 py-4 border-t border-gray-50 bg-gray-50/30">
                    <button wire:click="$set('showModal', false)" class="text-xs px-4 py-2.5 rounded-xl border border-gray-200 text-gray-700 font-medium hover:bg-gray-50 transition">Cancelar</button>
                    <button wire:click="save" class="text-xs px-4 py-2.5 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold shadow-sm">
                        {{ $editingId ? 'Guardar Cambios' : 'Crear Herramienta' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL DE GESTIÓN DE CATEGORÍAS COMPACTO --}}
    @if($showCategoryModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/20 backdrop-blur-sm px-3.5 transition-all duration-300">
            <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 w-full max-w-md transform transition-all overflow-hidden">
                
                {{-- Cabecera --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <div>
                        <h2 class="text-base font-bold text-gray-900 tracking-tight">
                            {{ $isEditingCategory ? 'Editar Categoría' : 'Configurar Categorías' }}
                        </h2>
                    </div>
                    <button wire:click="$set('showCategoryModal', false)" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Contenido Dinámico --}}
                <div class="p-6 space-y-5 max-h-[80vh] overflow-y-auto">
                    
                    <form wire:submit.prevent="{{ $isEditingCategory ? 'updateCategory' : 'saveCategory' }}" class="space-y-4 bg-slate-50/60 p-4 rounded-2xl border border-gray-100">
                        <div>
                            <label class="block text-xs font-semibold text-gray-800 mb-1">Nombre *</label>
                            <input wire:model="newCategoryName" type="text" placeholder="Ej. Amperímetros"
                                class="w-full text-sm bg-white border border-gray-200 rounded-xl px-3 py-2 text-gray-900 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/10 transition @error('newCategoryName') border-red-400 @enderror"/>
                            @error('newCategoryName') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-800 mb-1">Prefijo Único *</label>
                            <input wire:model="newCategoryPrefix" type="text" placeholder="Ej. AMP"
                                class="w-full text-sm bg-white border border-gray-200 rounded-xl px-3 py-2 font-mono uppercase tracking-wider text-gray-900 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-500/10 transition @error('newCategoryPrefix') border-red-400 @enderror"
                                {{ $isEditingCategory ? 'disabled' : '' }}/>
                            @error('newCategoryPrefix') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center gap-2 pt-1">
                            @if($isEditingCategory)
                                <button type="button" wire:click="openCategoryCreate" class="flex-1 text-xs py-2 rounded-xl border border-gray-200 text-gray-600 bg-white font-medium hover:bg-gray-50 transition">
                                    Cancelar
                                </button>
                            @endif
                            <button type="submit" class="flex-1 text-xs py-2 rounded-xl bg-green-600 hover:bg-green-700 text-white font-semibold transition shadow-sm">
                                {{ $isEditingCategory ? 'Actualizar' : 'Guardar' }}
                            </button>
                        </div>
                    </form>

                    {{-- LISTA DE CATEGORÍAS CON HOVER SIMPLE --}}
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Categorías Activas</h3>
                        <div class="divide-y divide-gray-100 border border-gray-100 rounded-2xl overflow-hidden bg-white">
                            @foreach($categories as $cat)
                                {{-- Usamos el 'group' clásico que nunca falla --}}
                                <div class="p-3.5 flex items-center justify-between hover:bg-slate-50 transition group">
                                    <div class="truncate pr-2">
                                        <span class="text-sm font-bold text-gray-900">{{ $cat->name }}</span>
                                        <span class="ml-1.5 text-[9px] font-mono font-bold bg-indigo-50 border border-indigo-100 text-indigo-700 px-1.5 py-0.5 rounded uppercase">{{ $cat->prefix }}</span>
                                    </div>
                                    
                                    {{-- Opacidad controlada por el group-hover y bloqueo de clics fantasmas --}}
                                    <div class="opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto transition-opacity duration-150 flex-shrink-0">
                                        <button wire:click="openCategoryEdit({{ $cat->id }})" class="px-2.5 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg transition text-xs font-bold shadow-sm">
                                            Editar
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif

    {{-- MODAL ELIMINAR --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500/20 backdrop-blur-md px-4">
            <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 w-full max-w-sm p-6 text-center transform transition-all overflow-hidden">
                <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-5 shadow-inner shadow-red-200/50">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <h3 class="text-lg font-extrabold text-gray-950 mb-1 tracking-tight">¿Eliminar herramienta?</h3>
                <p class="text-sm text-gray-500 mb-7 leading-relaxed">Esta acción quitará el registro del inventario permanentemente y no se puede deshacer.</p>
                <div class="flex gap-3 justify-center">
                    <button wire:click="$set('showDeleteModal', false)" class="flex-1 text-sm px-4 py-3 rounded-xl border border-gray-200 text-gray-700 font-medium hover:bg-gray-50 transition">Cancelar</button>
                    <button wire:click="delete" class="flex-1 text-sm px-4 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold transition shadow-sm">Sí, eliminar</button>
                </div>
            </div>
        </div>
    @endif
</div>