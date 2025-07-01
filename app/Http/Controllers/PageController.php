<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class PageController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

    // --- MÉTODO NUEVO PARA ENVIAR EL CORREO ---
    public function sendContactEmail(Request $request)
    {
        $contactData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:2000',
        ]);

        Mail::to('justificauam@gmail.com')->send(new ContactFormMail($contactData));

        return redirect()->route('contact')->with('success', '¡Tu mensaje ha sido enviado con éxito!');
    }
}