<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage; // <-- Añadido
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // --- Lógica para manejar la subida del avatar ---
        if ($request->hasFile('avatar')) {
            // Validar la imagen
            $request->validate([
                'avatar' => ['image', 'mimes:jpg,jpeg,png', 'max:1024'], // 1MB Max
            ]);

            // Borrar el avatar antiguo si existe
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Guardar el nuevo avatar y actualizar la ruta en el modelo
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }
        // --- Fin de la lógica del avatar ---

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Borrar el archivo de avatar si existe
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return Redirect::to('/');
    }
}
