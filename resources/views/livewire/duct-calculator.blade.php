<div class="premium-card rounded-3xl p-8 max-w-6xl mx-auto my-6 border border-neutral-200 bg-white shadow-xl">
    <h2 class="text-3xl font-black mb-8 text-gray-900 border-b pb-4">Calculadora de Trazado: Desvío en "S"</h2>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        
        <div class="lg:col-span-7 space-y-5">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                    <label class="block text-sm font-bold text-slate-700 mb-3">Ancho (W) - Pulgadas</label>
                    <div class="flex items-center gap-3">
                        <input type="range" min="1" max="40" step="0.5" wire:model.live="ancho_w" class="w-full h-2 bg-slate-300 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                        <input type="number" step="any" wire:model.live="ancho_w" class="w-20 p-2 border border-slate-300 rounded-xl text-center font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                    <label class="block text-sm font-bold text-slate-700 mb-3">Fondo (D) - Pulgadas</label>
                    <div class="flex items-center gap-3">
                        <input type="range" min="1" max="40" step="0.5" wire:model.live="fondo_d" class="w-full h-2 bg-slate-300 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                        <input type="number" step="any" wire:model.live="fondo_d" class="w-20 p-2 border border-slate-300 rounded-xl text-center font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                    <label class="block text-sm font-bold text-slate-700 mb-3">Desvío (O) - Pulgadas</label>
                    <div class="flex items-center gap-3">
                        <input type="range" min="1" max="40" step="0.5" wire:model.live="desvio_o" class="w-full h-2 bg-slate-300 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                        <input type="number" step="any" wire:model.live="desvio_o" class="w-20 p-2 border border-slate-300 rounded-xl text-center font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                    <label class="block text-sm font-bold text-slate-700 mb-3">Longitud (L) - Pulgadas</label>
                    <div class="flex items-center gap-3">
                        <input type="range" min="1" max="40" step="0.5" wire:model.live="longitud_l" class="w-full h-2 bg-slate-300 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                        <input type="number" step="any" wire:model.live="longitud_l" class="w-20 p-2 border border-slate-300 rounded-xl text-center font-bold text-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>
                </div>
            </div>

            <div class="bg-indigo-50 p-6 rounded-2xl border border-indigo-200 grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                <div>
                    <p class="text-[10px] text-indigo-500 font-bold uppercase tracking-wider mb-1 whitespace-normal break-words">Radio Centro</p>
                    <p class="text-2xl text-indigo-900 font-black">{{ $resultados['radio_central'] }}"</p>
                </div>
                <div>
                    <p class="text-[10px] text-indigo-500 font-bold uppercase tracking-wider mb-1 whitespace-normal break-words">Garganta (Int)</p>
                    <p class="text-2xl text-indigo-900 font-black">{{ $resultados['radio_interior'] }}"</p>
                </div>
                <div>
                    <p class="text-[10px] text-indigo-500 font-bold uppercase tracking-wider mb-1 whitespace-normal break-words">Lomo (Ext)</p>
                    <p class="text-2xl text-indigo-900 font-black">{{ $resultados['radio_exterior'] }}"</p>
                </div>
                <div class="bg-indigo-600 text-white rounded-xl p-2 text-center flex flex-col justify-center shadow-md">
                    <p class="text-[10px] font-bold uppercase tracking-wider opacity-80 whitespace-normal break-words">Largo Tira</p>
                    <p class="text-xl font-black">{{ $resultados['largo_wrapper'] }}"</p>
                </div>
            </div>
        </div>

        <div class="lg:col-span-5 flex flex-col items-center justify-center bg-slate-50 rounded-3xl p-8 min-h-[400px] relative overflow-hidden shadow-2xl text-slate-100 border-2 border-slate-200">
            
            <span class="absolute top-4 left-4 bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm">
                Plano de Trazado Digital
            </span>

            @php
                // --- MATEMÁTICA DEL LIENZO SVG ---
                // 1. Escala: Multiplicamos las pulgadas por 30 para que 1 pulgada = 30 píxeles en pantalla.
                $escala = 30; 
                
                // 2. Variables transformadas a píxeles (w_px = Ancho, o_px = Desvío, l_px = Largo)
                $w_px = max((float)$ancho_w, 1) * $escala;
                $o_px = max((float)$desvio_o, 0.1) * $escala;
                $l_px = max((float)$longitud_l, 1) * $escala;
                
                // 3. Márgenes (Padding): Dejamos 80 píxeles de espacio libre alrededor del dibujo 
                // para que las flechas y los textos no se salgan del cuadro y se corten.
                $padX = 110;
                $padY = 80;
                
                // 4. Tamaño total del lienzo = Tamaño de la pieza + los márgenes
                $ancho_lienzo = $l_px + ($padX * 2);
                $altura_lienzo = $w_px + $o_px + ($padY * 2);
                
                // 5. Punto medio de la longitud (Aquí es donde la curva "S" cambia de dirección)
                $mitad_L = $l_px / 2;
            @endphp

            <div class="w-full max-w-[440px] h-auto flex items-center justify-center py-4 transition-all duration-300">
                
                <svg viewBox="0 0 {{ $ancho_lienzo }} {{ $altura_lienzo }}" width="100%" height="100%" class="overflow-visible drop-shadow-2xl">

                    <defs>
                        <marker id="flecha" viewBox="0 0 10 10" refX="5" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
                            <path d="M 0 1 L 10 5 L 0 9 z" fill="#6366f1" />
                        </marker>
                    </defs>

                    <g transform="translate({{ $padX }}, {{ $padY }})">

                        {{-- ==================== SHAPE PRINCIPAL ==================== --}}
                        <path d="M 0,0 
                                C {{ $mitad_L }},0 {{ $mitad_L }},{{ $o_px }} {{ $l_px }},{{ $o_px }}
                                L {{ $l_px }},{{ $o_px + $w_px }}
                                C {{ $mitad_L }},{{ $o_px + $w_px }} {{ $mitad_L }},{{ $w_px }} 0,{{ $w_px }} 
                                Z" 
                            fill="#ffffff" 
                            stroke="#6366f1" 
                            stroke-width="4" 
                            class="transition-all duration-300" />

                        {{-- Línea central (eje neutro) --}}
                        <path d="M 0,{{ $w_px / 2 }} 
                                C {{ $mitad_L }},{{ $w_px / 2 }} {{ $mitad_L }},{{ $o_px + ($w_px / 2) }} {{ $l_px }},{{ $o_px + ($w_px / 2) }}" 
                            fill="transparent" 
                            stroke="#4338ca" 
                            stroke-width="2.5"
                            stroke-dasharray="8,5" />

                        {{-- Bordes laterales (entrada y salida) --}}
                        <line x1="0"        y1="0"              x2="0"        y2="{{ $w_px }}"              stroke="#818cf8" stroke-width="5" stroke-linecap="round" />
                        <line x1="{{ $l_px }}" y1="{{ $o_px }}" x2="{{ $l_px }}" y2="{{ $o_px + $w_px }}" stroke="#818cf8" stroke-width="5" stroke-linecap="round" />

                        {{-- ==================== COTA W (izquierda) ==================== --}}
                        <line x1="-45" y1="0"         x2="0" y2="0"         stroke="#334155" stroke-width="1.5" stroke-dasharray="4,4" />
                        <line x1="-45" y1="{{ $w_px }}" x2="0" y2="{{ $w_px }}" stroke="#334155" stroke-width="1.5" stroke-dasharray="4,4" />
                        <line x1="-35" y1="2"
                            x2="-35" y2="{{ $w_px - 2 }}"
                            stroke="#6366f1" stroke-width="2"
                            marker-start="url(#flecha)"
                            marker-end="url(#flecha)" />
                        <text x="-48" y="{{ $w_px / 2 + 5 }}"
                            fill="#818cf8" font-size="14" font-weight="900"
                            text-anchor="end">
                            W: {{ $ancho_w }}"
                        </text>

                        {{-- ==================== COTA L (inferior) ==================== --}}
                        <line x1="0"           y1="{{ $w_px }}"              x2="0"           y2="{{ $w_px + $o_px + 45 }}" stroke="#334155" stroke-width="1.5" stroke-dasharray="4,4" />
                        <line x1="{{ $l_px }}" y1="{{ $w_px + $o_px }}"     x2="{{ $l_px }}" y2="{{ $w_px + $o_px + 45 }}" stroke="#334155" stroke-width="1.5" stroke-dasharray="4,4" />
                        <line x1="2"
                            y1="{{ $w_px + $o_px + 35 }}"
                            x2="{{ $l_px - 2 }}"
                            y2="{{ $w_px + $o_px + 35 }}"
                            stroke="#6366f1" stroke-width="2"
                            marker-start="url(#flecha)"
                            marker-end="url(#flecha)" />
                        <text x="{{ $l_px / 2 }}" y="{{ $w_px + $o_px + 60 }}"
                            fill="#818cf8" font-size="14" font-weight="900"
                            text-anchor="middle">
                            L: {{ $longitud_l }}"
                        </text>

                        {{-- ==================== COTA O (derecha) ==================== --}}
                        @if($desvio_o > 0)

                            {{-- Línea guía superior --}}
                            <line
                                x1="0"
                                y1="0"
                                x2="{{ $l_px + 55 }}"
                                y2="0"
                                stroke="#334155"
                                stroke-width="1.5"
                                stroke-dasharray="4,4" />

                            {{-- Línea guía inferior --}}
                            <line
                                x1="{{ $l_px }}"
                                y1="{{ $o_px }}"
                                x2="{{ $l_px + 55 }}"
                                y2="{{ $o_px }}"
                                stroke="#334155"
                                stroke-width="1.5"
                                stroke-dasharray="4,4" />

                            {{-- Cota O --}}
                            <line
                                x1="{{ $l_px + 40 }}"
                                y1="2"
                                x2="{{ $l_px + 40 }}"
                                y2="{{ $o_px - 2 }}"
                                stroke="#6366f1"
                                stroke-width="2"
                                marker-start="url(#flecha)"
                                marker-end="url(#flecha)" />

                            <text
                                x="{{ $l_px + 48 }}"
                                y="{{ ($o_px / 2) + 5 }}"
                                fill="#818cf8"
                                font-size="14"
                                font-weight="900"
                                text-anchor="start">
                                O: {{ $desvio_o }}"
                            </text>

                        @endif

                        {{-- ==================== PUNTOS CENTRALES ==================== --}}
                        <circle cx="0"        cy="{{ $w_px / 2 }}"              r="4" fill="#22c55e" />
                        <circle cx="{{ $l_px }}" cy="{{ $o_px + ($w_px / 2) }}" r="4" fill="#22c55e" />

                    </g>
                </svg>
            
            </div>

            <p class="text-[10px] text-slate-500 italic text-center mt-4">
                * No olvides añadir tus pestañas (Pittsburgh / Engargolado) antes de cortar.
            </p>
        
        </div>
    </div>
</div>