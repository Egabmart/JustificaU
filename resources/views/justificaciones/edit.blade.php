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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="student_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del Estudiante</label>
                                <input type="text" name="student_name" id="student_name" value="{{ old('student_name', $justificacione->student_name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                            </div>

                            <div>
                                <label for="student_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Carnet del Estudiante</label>
                                <input type="text" name="student_id" id="student_id" value="{{ old('student_id', $justificacione->student_id) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                            </div>

                             <div>
                                <label for="clase" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Clase</label>
                                <input type="text" name="clase" id="clase" value="{{ old('clase', $justificacione->clase) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                            </div>
                            
                            <div>
                                <label for="grupo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Grupo</label>
                                <input type="text" name="grupo" id="grupo" value="{{ old('grupo', $justificacione->grupo) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                            </div>

                            <div>
                                <label for="hora" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hora</label>
                                <input type="time" name="hora" id="hora" value="{{ old('hora', $justificacione->hora) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de Inicio</label>
                                <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $justificacione->start_date) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de Fin</label>
                                <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $justificacione->end_date) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                            </div>
                        </div>

                        <div>
                            <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Motivo</label>
                            <textarea name="reason" id="reason" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">{{ old('reason', $justificacione->reason) }}</textarea>
                        </div>
                        
                        <div>
                            <label for="constancia" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subir Nueva Constancia (Opcional)</label>
                            @if ($justificacione->constancia_path)
                                <p class="text-sm text-gray-500 mt-1">Archivo actual: <a href="{{ asset('storage/' . $justificacione->constancia_path) }}" target="_blank" class="text-uam-blue-500 hover:underline">Ver Constancia</a></p>
                            @endif
                            <input type="file" name="constancia" id="constancia" class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-uam-blue-50 file:text-uam-blue-600 hover:file:bg-uam-blue-100">
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
                            <select name="status" id="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                                <option value="Pendiente" {{ $justificacione->status == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="Aprobada" {{ $justificacione->status == 'Aprobada' ? 'selected' : '' }}>Aprobada</option>
                                <option value="Rechazada" {{ $justificacione->status == 'Rechazada' ? 'selected' : '' }}>Rechazada</option>
                            </select>
                        </div>
                        
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('justificaciones.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700">Cancelar</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-uam-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-uam-blue-600">Actualizar Justificación</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>