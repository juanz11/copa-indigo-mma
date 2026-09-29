<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users,email',
            'id_number'    => 'required|string|max:50',
            'phone_prefix' => 'required|in:0414,0424,0412,0422,0426,0416',
            'phone_number' => 'required|digits:7',
            'password'     => 'required|string|min:6|confirmed',
        ], [
            'email.unique' => 'Este correo ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        // Normalizar cédula: quitar puntos/espacios y unificar prefijo en mayúscula (V-12345678)
        $validated['id_number'] = strtoupper(preg_replace('/[^A-Za-z0-9\-]/', '', $validated['id_number']));

        // Validación de cédula con números repetidos
        $this->validateIdNumberPatterns($validated['id_number']);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'id_number' => $validated['id_number'],
            'phone'     => $validated['phone_prefix'] . '-' . $validated['phone_number'],
            'password'  => Hash::make($validated['password']),
            'role'      => 'user',
        ]);

        Auth::login($user);

        return redirect()->intended(route('home'))->with('success', 'Registro exitoso. Ya puedes comprar tu entrada.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (auth()->user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->intended(route('home'));
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    /**
     * Valida patrones de cédula con números repetidos
     */
    private function validateIdNumberPatterns($idNumber)
    {
        // Eliminar espacios y caracteres no numéricos
        $clean = preg_replace('/[^0-9]/', '', $idNumber);

        // Verificar si algún número se repite 6 o más veces
        $counts = array_count_values(str_split($clean));
        foreach ($counts as $digit => $count) {
            if ($count >= 6) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'id_number' => 'La cédula contiene un número repetido demasiadas veces. Por favor, verifica la información.',
                ]);
            }
        }
    }
}
