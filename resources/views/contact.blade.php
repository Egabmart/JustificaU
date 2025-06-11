@extends('layouts.app')

@section('content')
    <div class="bg-white dark:bg-gray-800 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-blue-600 dark:text-blue-400 font-semibold tracking-wide uppercase">Ponte en Contacto</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                    Estamos aquí para ayudarte
                </p>
            </div>

            <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="text-gray-700 dark:text-gray-300">
                    <h3 class="text-xl font-semibold mb-4">Información de la Oficina</h3>
                    <p class="mb-2"><strong>Dirección:</strong> Rotonda de la Centroamérica, 700 mts. al sur, Managua, Nicaragua.</p>
                    <p class="mb-2"><strong>Teléfono:</strong> (+505) 2278-3800</p>
                    <p class="mb-2"><strong>Email:</strong> admision@uam.edu.ni</p>
                    <div class="mt-6 h-64 bg-gray-300 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                        <p class="text-gray-500">Espacio para un mapa interactivo</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Envíanos un Mensaje</h3>
                    <form action="#" method="POST" class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre Completo</label>
                            <input type="text" name="name" id="name" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo Electrónico</label>
                            <input type="email" name="email" id="email" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mensaje</label>
                            <textarea name="message" id="message" rows="4" class="mt-1 block w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        <div>
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Enviar Mensaje
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection