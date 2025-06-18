<x-guest-layout>
    <!-- Mensaje introductorio -->
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('¿Olvidaste tu contraseña? No hay problema. Solo indícanos tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña y podrás elegir una nueva.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Correo Electrónico -->
        <div>
            <x-input-label for="email" :value="__('Correo Electrónico')" />
            <x-text-input
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Botón Enviar idéntico al de Registro -->
        <div class="flex items-center justify-end mt-4">
            <button
                type="submit"
                class="inline-flex items-center px-4 py-2 bg-[#0199a7] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#017d8a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0199a7] transition ease-in-out duration-150"
            >
                {{ __('Enviar') }}
            </button>
        </div>
    </form>
</x-guest-layout>
