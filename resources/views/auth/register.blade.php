<x-guest-layout>
    @php
    // Datos de la oferta académica de la UAM
    $ofertaAcademica = [
        "Facultad de Ciencias Administrativas y Económicas" => [
            "Administración de Empresas",
            "Contabilidad y Finanzas",
            "Economía Empresarial",
            "Negocios Internacionales"
        ],
        "Facultad de Ciencias Jurídicas, Humanidades y Relaciones Internacionales" => [
            "Derecho",
            "Diplomacia y Relaciones Internacionales"
        ],
        "Facultad de Ciencias Médicas" => [
            "Medicina",
            "Psicología",
            "Nutrición"
        ],
        "Facultad de Odontología" => [
            "Odontología"
        ],
        "Facultad de Ingeniería y Arquitectura" => [
            "Arquitectura",
            "Ingeniería Civil",
            "Ingeniería Industrial",
            "Ingeniería en Sistemas de Información"
        ],
        "Facultad de Marketing, Diseño y Ciencias de la Comunicación" => [
            "Marketing y Publicidad",
            "Diseño y Comunicación Visual",
            "Comunicación y Relaciones Públicas"
        ]
    ];
    @endphp

    

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Nombre Completo')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Correo Electrónico')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="cif" :value="__('CIF (Carnet de Identificación)')" />
            <x-text-input id="cif" class="block mt-1 w-full" type="text" name="cif" :value="old('cif')" required />
            <x-input-error :messages="$errors->get('cif')" class="mt-2" />
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div>
                <x-input-label for="facultad" :value="__('Facultad')" />
                <select id="facultad" name="facultad" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                    <option value="">Seleccione una facultad</option>
                    @foreach (array_keys($ofertaAcademica) as $facultad)
                        <option value="{{ $facultad }}" {{ old('facultad') == $facultad ? 'selected' : '' }}>{{ $facultad }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('facultad')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="carrera" :value="__('Carrera')" />
                <select id="carrera" name="carrera" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                    <option value="">Seleccione una carrera</option>
                </select>
                <x-input-error :messages="$errors->get('carrera')" class="mt-2" />
            </div>
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100" href="{{ route('login') }}">
                {{ __('¿Ya estás registrado?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Registrarse') }}
            </x-primary-button>
        </div>
    </form>

    {{-- Lógica de JavaScript para los menús desplegables dinámicos --}}
    <script>
        const ofertaAcademica = @json($ofertaAcademica);
        const facultadSelect = document.getElementById('facultad');
        const carreraSelect = document.getElementById('carrera');
        const oldCarrera = "{{ old('carrera') }}";

        facultadSelect.addEventListener('change', function() {
            const selectedFacultad = this.value;
            // Limpiar opciones anteriores
            carreraSelect.innerHTML = '<option value="">Seleccione una carrera</option>';

            if (selectedFacultad && ofertaAcademica[selectedFacultad]) {
                const carreras = ofertaAcademica[selectedFacultad];
                carreras.forEach(function(carrera) {
                    const option = document.createElement('option');
                    option.value = carrera;
                    option.textContent = carrera;
                    carreraSelect.appendChild(option);
                });
            }
        });

        // Si hay un valor antiguo (por un error de validación), volvemos a popular las carreras
        if (facultadSelect.value) {
            facultadSelect.dispatchEvent(new Event('change'));
            // Y si había una carrera seleccionada, la volvemos a seleccionar
            if(oldCarrera) {
                setTimeout(() => {
                    carreraSelect.value = oldCarrera;
                }, 100);
            }
        }
    </script>
</x-guest-layout>