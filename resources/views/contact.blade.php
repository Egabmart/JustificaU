@extends('layouts.guest-public')

@section('content')
    <div class="bg-white dark:bg-gray-800 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-uam-blue-500 font-semibold tracking-wide uppercase">Ponte en Contacto</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                    Estamos aquí para ayudarte
                </p>
            </div>

            <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="text-gray-700 dark:text-gray-300">
                    <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Información de la Oficina</h3>
                    <p class="mb-2"><strong>Dirección:</strong> Rotonda de la Centroamérica, 700 mts. al sur, Managua, Nicaragua.</p>
                    <p class="mb-2"><strong>Teléfono:</strong> (+505) 2278-3800</p>
                    <p class="mb-2"><strong>Email:</strong> admision@uam.edu.ni</p>
                    
                    <div class="mt-6 h-64 bg-gray-300 dark:bg-gray-700 rounded-lg overflow-hidden">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3900.825852924302!2d-86.26249258567!3d12.123512334810762!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8f73fe0033bb7599%3A0x83344605423528f1!2sUniversidad%20Americana%20(UAM)!5e0!3m2!1ses-419!2sni!4v1687023000000!5m2!1ses-419!2sni" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>

                <div>
                    <h3 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Envíanos un Mensaje</h3>
                    <div class="mt-1 p-6 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <p class="text-gray-600 dark:text-gray-300 mb-4">
                            Para consultas, admisiones o soporte, la mejor manera de contactarnos es a través de correo electrónico. Nuestro equipo responderá a la brevedad posible.
                        </p>
                        <a href="mailto:admision@uam.edu.ni" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-uam-blue-500 hover:bg-uam-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uam-blue-500">
                            Contactar por Correo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection