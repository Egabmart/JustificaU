@extends('layouts.app')

@section('content')
    <div class="py-12 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-custom-cyan dark:text-custom-cyan font-semibold tracking-wide uppercase">Nuestra Plataforma</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                    Una mejor manera de gestionar justificaciones
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 dark:text-gray-400 lg:mx-auto">
                    JustificaU es una iniciativa para modernizar y simplificar la forma en que estudiantes y personal administrativo interactúan con los procesos académicos.
                </p>
            </div>

            <div class="mt-10">
                <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                    <div class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-custom-cyan text-white">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9V3m-9 9h18" />
                                </svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900 dark:text-white">Nuestra Misión</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500 dark:text-gray-400">
                            Proveer una herramienta digital, centralizada y eficiente que facilite la presentación, validación y consulta de justificaciones, mejorando la comunicación y transparencia dentro de la comunidad universitaria.
                        </dd>
                    </div>

                    <div class="relative">
                        <dt>
                            <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-custom-cyan text-white">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="ml-16 text-lg leading-6 font-medium text-gray-900 dark:text-white">Nuestra Visión</p>
                        </dt>
                        <dd class="mt-2 ml-16 text-base text-gray-500 dark:text-gray-400">
                            Ser la plataforma estándar en instituciones educativas para la gestión de procesos administrativos, reconocida por su facilidad de uso, fiabilidad y por contribuir a un ecosistema académico más ágil y moderno.
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
@endsection