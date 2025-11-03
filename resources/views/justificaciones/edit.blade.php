<x-app-layout>
    {{-- El título de la página cambiará dinámicamente según el rol del usuario --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            @if(Auth::user()->role === 'admin')
                {{ __('Ver Justificación') }}
            @else
                {{ __('Editar Justificación') }}
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">

                    {{-- ============================================= --}}
                    {{-- VISTA PARA EL ADMINISTRADOR (SOLO LECTURA + ESTADO) --}}
                    {{-- ============================================= --}}
                    @if (Auth::user()->role === 'admin')
                        <div class="mb-8 space-y-4">
                            <h3 class="text-lg font-bold border-b border-gray-200 dark:border-gray-700 pb-2">Resumen de la Solicitud</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">
                                <div><p class="text-sm font-medium text-gray-500 dark:text-gray-400">Estudiante</p><p>{{ $justificacione->student_name }}</p></div>
                                <div><p class="text-sm font-medium text-gray-500 dark:text-gray-400">Carnet (CIF)</p><p>{{ $justificacione->student_id }}</p></div>
                                <div><p class="text-sm font-medium text-gray-500 dark:text-gray-400">Clase y Grupo</p><p>{{ $justificacione->clase }} (Grupo: {{ $justificacione->grupo }})</p></div>
                                <div><p class="text-sm font-medium text-gray-500 dark:text-gray-400">Profesor</p><p>{{ $justificacione->profesor ?? 'No asignado' }}</p></div>
                                <div><p class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha y Hora</p><p>{{ \Carbon\Carbon::parse($justificacione->fecha)->format('d/m/Y') }} | {{ \Carbon\Carbon::parse($justificacione->hora_inicio)->format('h:i A') }} - {{ \Carbon\Carbon::parse($justificacione->hora_fin)->format('h:i A') }}</p></div>
                                <div><p class="text-sm font-medium text-gray-500 dark:text-gray-400">Constancia Adjunta</p><p>@if ($justificacione->constancia_path)<a href="{{ asset('storage/' . $justificacione->constancia_path) }}" target="_blank" class="text-uam-blue-500 hover:underline">Ver Archivo</a>@else<span>No se adjuntó archivo.</span>@endif</p></div>
                            </div>
                            <div class="mt-4"><p class="text-sm font-medium text-gray-500 dark:text-gray-400">Motivo</p><p class="mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-md border border-gray-200 dark:border-gray-600">{{ $justificacione->reason }}</p></div>
                        </div>

                        <form method="POST" action="{{ route('justificaciones.update', $justificacione) }}" class="space-y-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            @csrf
                            @method('PUT')
                            <h3 class="text-lg font-bold">Gestionar Estado</h3>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Estado actual:</span>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $justificacione->statusBadgeClass() }}">{{ $justificacione->statusLabel() }}</span>
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium">Cambiar estado a:</label>
                                @if(!empty($allowedStatuses))
                                    <select name="status" id="status" required class="mt-1 block w-full md:w-1/2 rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600">
                                        <option value="" disabled {{ old('status') ? '' : 'selected' }}>Seleccione una opción</option>
                                        @foreach($allowedStatuses as $status)
                                            <option value="{{ $status }}" @selected(old('status') === $status)>{{ $statusLabels[$status] ?? ucfirst(strtolower($status)) }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No hay transiciones disponibles desde este estado.</p>
                                @endif
                            </div>
                            <div id="rejection_reason_container" class="{{ old('status', $justificacione->status) === \App\Models\Justificacion::STATUS_RECHAZADA ? '' : 'hidden' }}">
                                <label for="rejection_reason" class="block text-sm font-medium">Motivo del Rechazo</label>
                                <textarea name="rejection_reason" id="rejection_reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600">{{ old('rejection_reason', $justificacione->rejection_reason) }}</textarea>
                                @error('rejection_reason')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex items-center justify-end space-x-4">
                                <a href="{{ route('justificaciones.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700">Cancelar</a>
                                @if(!empty($allowedStatuses))
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-uam-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-uam-blue-600">Guardar Estado</button>
                                @endif
                            </div>
                            
                        </form>

                    {{-- =================================== --}}
                    {{-- VISTA PARA EL ESTUDIANTE (EDITABLE) --}}
                    {{-- =================================== --}}
                    @else
                        @php
                            $datosCarrera = config('academic.' . Auth::user()->carrera, []);
                            $anios = array_keys($datosCarrera);
                        @endphp
                        <form method="POST" action="{{ route('justificaciones.update', $justificacione) }}" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="anio_carrera" class="block text-sm font-medium">Año de la Carrera</label>
                                    <select id="anio_carrera" name="anio_carrera" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600">
                                        <option value="">Seleccione un año</option>
                                        @foreach ($anios as $anio)
                                            <option value="{{ $anio }}" @if($anio == $anioSeleccionado) selected @endif>{{ $anio }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="clase" class="block text-sm font-medium">Clase</label>
                                    <select id="clase" name="clase" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600" disabled>
                                        <option value="">Seleccione un año primero</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="grupo" class="block text-sm font-medium">Grupo</label>
                                    <select id="grupo" name="grupo" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600" disabled>
                                        <option value="">Seleccione una clase primero</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="fecha" class="block text-sm font-medium">Fecha</label>
                                    <input type="date" name="fecha" id="fecha" value="{{ old('fecha', $justificacione->fecha) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600">
                                </div>
                                <div>
                                    <label for="hora_inicio" class="block text-sm font-medium">Hora de Inicio</label>
                                    <input type="time" name="hora_inicio" id="hora_inicio" value="{{ old('hora_inicio', \Carbon\Carbon::parse($justificacione->hora_inicio)->format('H:i')) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600">
                                </div>
                                <div>
                                    <label for="hora_fin" class="block text-sm font-medium">Hora de Fin</label>
                                    <input type="time" name="hora_fin" id="hora_fin" value="{{ old('hora_fin', \Carbon\Carbon::parse($justificacione->hora_fin)->format('H:i')) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600">
                                </div>
                            </div>

                            <div>
                                <label for="reason" class="block text-sm font-medium">Motivo</label>
                                <textarea name="reason" id="reason" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600">{{ old('reason', $justificacione->reason) }}</textarea>
                            </div>

                            <div>
                                <label for="constancia" class="block text-sm font-medium">Subir Nueva Constancia (Opcional)</label>
                                @if ($justificacione->constancia_path)
                                    <p class="text-sm mt-1">Archivo actual: <a href="{{ asset('storage/' . $justificacione->constancia_path) }}" target="_blank" class="text-uam-blue-500 hover:underline">Ver Constancia</a></p>
                                @endif
                                <input type="file" name="constancia" id="constancia" class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-uam-blue-50 file:text-uam-blue-600 hover:file:bg-uam-blue-100">
                            </div>

                            <div class="flex items-center justify-end space-x-4">
                                <a href="{{ route('justificaciones.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700">Cancelar</a>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-uam-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-uam-blue-600">Actualizar Justificación</button>
                            </div>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- Script para ambos roles, con lógica separada por if --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lógica para mostrar/ocultar el motivo de rechazo (SOLO para el Admin)
            const statusSelect = document.getElementById('status');
            if (statusSelect) {
                const rejectionContainer = document.getElementById('rejection_reason_container');
                statusSelect.addEventListener('change', function() {
                    rejectionContainer.classList.toggle('hidden', this.value !== '{{ \App\Models\Justificacion::STATUS_RECHAZADA }}');
                });
            }

            // Lógica para los dropdowns dinámicos (SOLO para el Estudiante)
            const anioSelect = document.getElementById('anio_carrera');
            if (anioSelect) {
                const datosCarrera = @json(config('academic.' . Auth::user()->carrera, []));
                const claseSelect = document.getElementById('clase');
                const grupoSelect = document.getElementById('grupo');
                
                // --- Datos que vienen del controlador ---
                const anioGuardado = @json($anioSeleccionado);
                const claseGuardada = @json($justificacione->clase);
                const grupoGuardado = @json($justificacione->grupo);
                
                let clasesDelAnio = [];

                function poblarClases(anioSeleccionado, claseParaSeleccionar = null) {
                    claseSelect.innerHTML = '<option value="">Seleccione una clase</option>';
                    claseSelect.disabled = true;

                    if (anioSeleccionado && datosCarrera[anioSeleccionado]) {
                        clasesDelAnio = datosCarrera[anioSeleccionado];
                        Object.keys(clasesDelAnio).forEach(function(nombreClase) {
                            const option = document.createElement('option');
                            option.value = nombreClase;
                            option.textContent = nombreClase;
                            claseSelect.appendChild(option);
                        });
                        claseSelect.disabled = false;
                        if(claseParaSeleccionar) {
                            claseSelect.value = claseParaSeleccionar;
                        }
                    }
                }

                function poblarGrupos(claseSeleccionada, grupoParaSeleccionar = null) {
                    grupoSelect.innerHTML = '<option value="">Seleccione un grupo</option>';
                    grupoSelect.disabled = true;

                    if (claseSeleccionada && clasesDelAnio[claseSeleccionada] && clasesDelAnio[claseSeleccionada].grupos) {
                        clasesDelAnio[claseSeleccionada].grupos.forEach(function(grupo) {
                            const option = document.createElement('option');
                            option.value = grupo;
                            option.textContent = grupo;
                            grupoSelect.appendChild(option);
                        });
                        grupoSelect.disabled = false;
                        if(grupoParaSeleccionar) {
                            grupoSelect.value = grupoParaSeleccionar;
                        }
                    }
                }

                anioSelect.addEventListener('change', () => poblarClases(anioSelect.value));
                claseSelect.addEventListener('change', () => poblarGrupos(claseSelect.value));

                // --- ¡LÓGICA MEJORADA PARA PRE-CARGAR LOS DATOS! ---
                if (anioGuardado) {
                    anioSelect.value = anioGuardado;
                    poblarClases(anioGuardado, claseGuardada);
                    poblarGrupos(claseGuardada, grupoGuardado);
                }
            }
        });
    </script>
</x-app-layout>