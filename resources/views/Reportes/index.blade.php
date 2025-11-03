<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Reporte de Clases con Justificaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Reportes por Clase, Grupo y Carrera</h3>
                    
                    {{-- Este div mostrará los mensajes de éxito o error al enviar un reporte --}}
                    <div id="report-feedback" class="hidden p-3 mb-4 rounded-md"></div>
                    
                    @if($reportes->isEmpty())
                        <p>No hay justificaciones registradas por el momento.</p>
                    @else
                        <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-md">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Carrera</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Clase</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Grupo</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Profesor</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alumnos</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($reportes as $reporte)
                                        {{-- Añadimos todos los data- attributes a la fila para acceder a ellos fácilmente con JavaScript --}}
                                        <tr data-carrera="{{ $reporte->carrera }}" data-clase="{{ $reporte->clase }}" data-grupo="{{ $reporte->grupo }}" data-profesor="{{ $reporte->profesor }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $reporte->carrera }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $reporte->clase }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $reporte->grupo }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $reporte->profesor }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                <div class="flex items-center space-x-4">
                                                    <span>{{ $reporte->total_alumnos }}</span>
                                                    <button class="ver-alumnos-btn text-indigo-600 hover:text-indigo-900 font-medium">(Ver)</button>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <button class="enviar-reporte-btn bg-uam-blue-500 hover:bg-uam-blue-600 text-white text-xs font-bold py-1 px-3 rounded">
                                                    Enviar Reporte
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="alumnos-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">Alumnos en la Clase</h3>
                <div class="mt-2 px-7 py-3">
                    <ul id="alumnos-lista" class="text-sm text-gray-500 dark:text-gray-300 text-left divide-y divide-gray-200 dark:divide-gray-700">
                        </ul>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="close-modal-btn" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- CONSTANTES PARA LOS ELEMENTOS DEL DOM ---
            const modal = document.getElementById('alumnos-modal');
            const closeModalBtn = document.getElementById('close-modal-btn');
            const alumnosLista = document.getElementById('alumnos-lista');
            const modalTitle = document.getElementById('modal-title');
            const feedbackDiv = document.getElementById('report-feedback');

            // --- LÓGICA PARA EL BOTÓN "VER" (ABRIR POP-UP) ---
            const statusLabels = @json(\App\Models\Justificacion::statusLabels());
            const statusTextClasses = @json([
                \App\Models\Justificacion::STATUS_APROBADA => 'text-green-500',
                \App\Models\Justificacion::STATUS_RECHAZADA => 'text-red-500',
                \App\Models\Justificacion::STATUS_ENVIADA => 'text-yellow-500',
                \App\Models\Justificacion::STATUS_EXPIRADA => 'text-gray-500',
            ]);

            document.querySelectorAll('.ver-alumnos-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const clase = row.dataset.clase;
                    const grupo = row.dataset.grupo;
                    const profesor = row.dataset.profesor;
                    
                    const url = new URL("{{ route('reportes.alumnos') }}");
                    url.searchParams.append('clase', clase);
                    url.searchParams.append('grupo', grupo);
                    url.searchParams.append('profesor', profesor);

                    alumnosLista.innerHTML = '<li>Cargando...</li>';
                    modalTitle.innerText = `Alumnos en: ${clase} - G${grupo}`;
                    modal.classList.remove('hidden');

                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            alumnosLista.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(alumno => {
                                    const li = document.createElement('li');
                                    li.className = 'py-2 flex justify-between items-center';
                                    
                                    const status = alumno.status || '';
                                    const statusClass = statusTextClasses[status] || 'text-gray-500';
                                    const statusLabel = statusLabels[status] || status;

                                    li.innerHTML = `<span>${alumno.student_name}</span><span class="font-bold ${statusClass}">${statusLabel}</span>`;
                                    alumnosLista.appendChild(li);
                                });
                            } else {
                                alumnosLista.innerHTML = '<li>No se encontraron alumnos para este reporte.</li>';
                            }
                        })
                        .catch(error => {
                            console.error('Error al cargar los alumnos:', error);
                            alumnosLista.innerHTML = '<li>Error al cargar los datos.</li>';
                        });
                });
            });

            // --- LÓGICA PARA EL BOTÓN "ENVIAR REPORTE" ---
            document.querySelectorAll('.enviar-reporte-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const row = this.closest('tr');
                    const reporteData = {
                        carrera: row.dataset.carrera,
                        clase: row.dataset.clase,
                        grupo: row.dataset.grupo,
                        profesor: row.dataset.profesor,
                    };

                    this.innerText = 'Enviando...';
                    this.disabled = true;

                    fetch("{{ route('reportes.enviar') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(reporteData)
                    })
                    .then(response => response.json().then(data => ({ ok: response.ok, data })))
                    .then(({ ok, data }) => {
                        feedbackDiv.classList.remove('hidden');
                        if (ok && data.success) {
                            feedbackDiv.className = 'p-3 mb-4 rounded-md bg-green-100 border border-green-400 text-green-700';
                            feedbackDiv.innerText = data.message;
                        } else {
                            feedbackDiv.className = 'p-3 mb-4 rounded-md bg-red-100 border border-red-400 text-red-700';
                            feedbackDiv.innerText = `Error: ${data.message || 'Ocurrió un problema inesperado.'}`;
                        }
                    })
                    .catch(error => {
                        console.error('Error de red:', error);
                        feedbackDiv.className = 'p-3 mb-4 rounded-md bg-red-100 border border-red-400 text-red-700';
                        feedbackDiv.innerText = 'Error de conexión. No se pudo contactar al servidor.';
                    })
                    .finally(() => {
                        button.innerText = 'Enviar Reporte';
                        button.disabled = false;
                        setTimeout(() => { feedbackDiv.classList.add('hidden'); }, 6000);
                    });
                });
            });

            // --- LÓGICA PARA CERRAR EL POP-UP ---
            closeModalBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
            });

            window.addEventListener('click', function(event) {
                if (event.target == modal) {
                    modal.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>