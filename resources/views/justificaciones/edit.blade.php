<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Justificación') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('justificaciones.update', $justificacione) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Los campos del estudiante solo los puede editar un admin --}}
                        @if(auth()->user()->role === 'admin')
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                             <h3 class="font-semibold text-lg mb-2">Datos del Estudiante (Solo Admin)</h3>
                             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="student_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del Estudiante</label>
                                    <input type="text" name="student_name" id="student_name" value="{{ old('student_name', $justificacione->student_name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600">
                                    @error('student_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="student_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Carnet del Estudiante</label>
                                    <input type="text" name="student_id" id="student_id" value="{{ old('student_id', $justificacione->student_id) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600">
                                    @error('student_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="clase" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Clase</label>
                                <input type="text" name="clase" id="clase" value="{{ old('clase', $justificacione->clase) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600">
                                @error('clase')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="grupo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Grupo</label>
                                <input type="text" name="grupo" id="grupo" value="{{ old('grupo', $justificacione->grupo) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600">
                                @error('grupo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="fecha" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha</label>
                                <input type="date" name="fecha" id="fecha" value="{{ old('fecha') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600">
                                @error('fecha')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                            <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Motivo</label>
                            <textarea name="reason" id="reason" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600">{{ old('reason', $justificacione->reason) }}</textarea>
                            @error('reason')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="constancia" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subir Nueva Constancia (Opcional)</label>
                            @if ($justificacione->constancia_path)
                                <p class="text-sm text-gray-500 mt-1">Archivo actual: <a href="{{ asset('storage/' . $justificacione->constancia_path) }}" target="_blank" class="text-uam-blue-500 hover:underline">Ver Constancia</a></p>
                            @endif
                            <input type="file" name="constancia" id="constancia" class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-uam-blue-50 file:text-uam-blue-600 hover:file:bg-uam-blue-100">
                            @error('constancia')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        @if(auth()->user()->role === 'admin')
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
                            <select name="status" id="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600">
                                <option value="Pendiente" @selected(old('status', $justificacione->status) == 'Pendiente')>Pendiente</option>
                                <option value="Aprobada" @selected(old('status', $justificacione->status) == 'Aprobada')>Aprobada</option>
                                <option value="Rechazada" @selected(old('status', 'Rechazada') == 'Rechazada')>Rechazada</option>
                            </select>
                            @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div id="rejection_reason_container" class="{{ old('status', $justificacione->status) == 'Rechazada' ? '' : 'hidden' }}">
                            <label for="rejection_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Motivo del Rechazo (Obligatorio si se rechaza)</label>
                            <textarea name="rejection_reason" id="rejection_reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-600">{{ old('rejection_reason', $justificacione->rejection_reason) }}</textarea>
                            @error('rejection_reason')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        @endif
                        
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('justificaciones.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700">Cancelar</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-uam-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-uam-blue-600">Actualizar Justificación</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const statusSelect = document.getElementById('status');
        if (statusSelect) {
            const rejectionContainer = document.getElementById('rejection_reason_container');
            statusSelect.addEventListener('change', function() {
                if (this.value === 'Rechazada') {
                    rejectionContainer.classList.remove('hidden');
                } else {
                    rejectionContainer.classList.add('hidden');
                }
            });
        }
    </script>
</x-app-layout>