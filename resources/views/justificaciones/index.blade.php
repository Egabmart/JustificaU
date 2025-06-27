<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Justificaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">¡Éxito!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="flex justify-end mb-4">
                        <a href="{{ route('justificaciones.create') }}" class="bg-uam-blue-500 hover:bg-uam-blue-600 text-white font-bold py-2 px-4 rounded">
                            Crear Nueva Justificación
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estudiante</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Clase</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Constancia</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Acciones</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($justificaciones as $justificacione)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $justificacione->student_name }}<br><span class="text-xs text-gray-500">{{$justificacione->student_id}}</span></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ $justificacione->clase }}</span><br>
                                            <span class="text-xs">Grupo: {{ $justificacione->grupo }}</span><br>
                                            <span class="text-xs italic">Prof. {{ $justificacione->profesor }}</span><br>
                                            <span class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($justificacione->fecha)->format('d/m/Y') }} | 
                                                {{ \Carbon\Carbon::parse($justificacione->hora_inicio)->format('h:i A') }} - {{ \Carbon\Carbon::parse($justificacione->hora_fin)->format('h:i A') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            @if($justificacione->constancia_path)
                                                <a href="{{ asset('storage/' . $justificacione->constancia_path) }}" target="_blank" class="text-uam-blue-500 hover:underline">Ver Archivo</a>
                                            @else
                                                <span>N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($justificacione->status == 'Aprobada')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aprobada</span>
                                            @elseif ($justificacione->status == 'Rechazada')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rechazada</span>
                                                @if($justificacione->rejection_reason)
                                                    <p class="text-xs text-gray-500 mt-1 italic" title="{{ $justificacione->rejection_reason }}">Motivo: {{ \Illuminate\Support\Str::limit($justificacione->rejection_reason, 20) }}</p>
                                                @endif
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end items-center space-x-3">
                                            @if (Auth::user()->role === 'admin')
                                                <a href="{{ route('justificaciones.edit', $justificacione) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">Ver</a>
                                            @else
                                                <a href="{{ route('justificaciones.edit', $justificacione) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">Editar</a>
                                            @endif
                                            <form action="{{ route('justificaciones.destroy', $justificacione) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta justificación?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No hay justificaciones registradas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $justificaciones->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>