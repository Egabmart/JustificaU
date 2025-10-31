<?php

namespace App\Http\Controllers\Auth;

use App\Factories\UserFactory;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // AÑADIMOS LAS REGLAS DE VALIDACIÓN PARA LOS NUEVOS CAMPOS
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'cif' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'facultad' => ['required', 'string', 'max:255'],
            'carrera' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // AÑADIMOS LOS NUEVOS CAMPOS AL CREAR EL USUARIO
        $user = UserFactory::create(User::ROLE_STUDENT, [
            'name' => $request->name,
            'email' => $request->email,
            'cif' => $request->cif,
            'facultad' => $request->facultad,
            'carrera' => $request->carrera,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}