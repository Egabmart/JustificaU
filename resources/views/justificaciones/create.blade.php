<x-app-layout>
    {{-- Obtenemos los datos académicos para la carrera del usuario actual --}}
    @php
        $datosCarrera = config('academic.' . Auth::user()->carrera, []);
        $anios = array_keys($datosCarrera);
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear Nueva Justificación') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h3 class="font-semibold text-lg">Datos del Estudiante</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Estos datos se toman de tu perfil.</p>
                        <div class="mt-2">
                            <p><strong>Nombre:</strong> {{ Auth::user()->name }}</p>
                            <p><strong>CIF:</strong> {{ Auth::user()->cif }}</p>
                            <p><strong>Carrera:</strong> {{ Auth::user()->carrera }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('justificaciones.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="anio_carrera" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Año de la Carrera</label>
                                <select id="anio_carrera" name="anio_carrera" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600">
                                    <option value="">Seleccione un año</option>
                                    @foreach ($anios as $anio)
                                        <option value="{{ $anio }}" {{ old('anio_carrera') == $anio ? 'selected' : '' }}>{{ $anio }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="clase" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Clase</label>
                                <select id="clase" name="clase" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600" disabled>
                                    <option value="">Seleccione un año primero</option>
                                </select>
                                @error('clase')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="grupo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Grupo</label>
                                <select id="grupo" name="grupo" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600" disabled>
                                    <option value="">Seleccione una clase primero</option>
                                </select>
                                @error('grupo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="fecha" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha</label>
                                <input type="date" name="fecha" id="fecha" value="{{ old('fecha') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600">
                                @error('fecha')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="hora_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hora de Inicio</label>
                                <input type="time" name="hora_inicio" id="hora_inicio" value="{{ old('hora_inicio') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600">
                                @error('hora_inicio')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="hora_fin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hora de Fin</label>
                                <input type="time" name="hora_fin" id="hora_fin" value="{{ old('hora_fin') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600">
                                @error('hora_fin')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        
                        <div>
                            <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Motivo de la Justificación</label>
                            <textarea name="reason" id="reason" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600">{{ old('reason') }}</textarea>
                            @error('reason')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="constancia" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Constancia de Respaldo (Obligatorio)</label>
                            <input type="file" name="constancia" id="constancia" required class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-uam-blue-50 file:text-uam-blue-600 hover:file:bg-uam-blue-100">
                            @error('constancia')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('justificaciones.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700">Cancelar</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-uam-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-uam-blue-600">Guardar Justificación</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const datosCarrera = @json($datosCarrera);
        const anioSelect = document.getElementById('anio_carrera');
        const claseSelect = document.getElementById('clase');
        const grupoSelect = document.getElementById('grupo');
        const oldAnio = "{{ old('anio_carrera') }}";
        const oldClase = "{{ old('clase') }}";
        const oldGrupo = "{{ old('grupo') }}";

        let clasesDelAnio = {}; // Usamos un objeto para guardar las clases del año

        anioSelect.addEventListener('change', function() {
            const selectedAnio = this.value;
            claseSelect.innerHTML = '<option value="">Seleccione una clase</option>';
            claseSelect.disabled = true;
            grupoSelect.innerHTML = '<option value="">Seleccione una clase primero</option>';
            grupoSelect.disabled = true;

            if (selectedAnio && datosCarrera[selectedAnio]) {
                clasesDelAnio = datosCarrera[selectedAnio]; // Guardamos el objeto de clases
                // Iteramos sobre las llaves del objeto (los nombres de las clases)
                Object.keys(clasesDelAnio).forEach(function(nombreClase) {
                    const option = document.createElement('option');
                    option.value = nombreClase;
                    option.textContent = nombreClase;
                    claseSelect.appendChild(option);
                });
                claseSelect.disabled = false;
            } else {
                claseSelect.innerHTML = '<option value="">Seleccione un año primero</option>';
            }
        });

        claseSelect.addEventListener('change', function() {
            const selectedClaseNombre = this.value;
            grupoSelect.innerHTML = '<option value="">Seleccione un grupo</option>';
            grupoSelect.disabled = true;

            // Buscamos la clase seleccionada directamente en el objeto
            const claseSeleccionada = clasesDelAnio[selectedClaseNombre];

            if (claseSeleccionada && claseSeleccionada.grupos) {
                claseSeleccionada.grupos.forEach(function(grupo) {
                    const option = document.createElement('option');
                    option.value = grupo;
                    option.textContent = grupo;
                    grupoSelect.appendChild(option);
                });
                grupoSelect.disabled = false;
            } else {
                grupoSelect.innerHTML = '<option value="">No hay grupos disponibles</option>';
            }
        });

        // Lógica para mantener los valores antiguos si hay un error de validación
        if (oldAnio) {
            anioSelect.value = oldAnio;
            anioSelect.dispatchEvent(new Event('change'));
            setTimeout(() => {
                if (oldClase) {
                    claseSelect.value = oldClase;
                    claseSelect.dispatchEvent(new Event('change'));
                    setTimeout(() => {
                        if (oldGrupo) {
                            grupoSelect.value = oldGrupo;
                        }
                    }, 100);
                }
            }, 100);
        }
    </script>
</x-app-layout>