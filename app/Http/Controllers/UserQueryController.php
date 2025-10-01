<?php

namespace App\Http\Controllers;

use App\Models\UserQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserQueryController extends Controller
{
    public function index(): View
    {
        $queries = Auth::user()->userQueries()->latest()->get();
        return view('history.index', ['queries' => $queries]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'type' => 'required|string',
            'value_type' => 'required|string|in:buy_price,sell_price',
        ]);

        $request->user()->userQueries()->create($request->only('type', 'value_type'));

        return redirect()->route('history.index')->with('success', '¡Consulta guardada en tu historial!');
    }

    public function destroy(UserQuery $userQuery): RedirectResponse
    {
        // Asegurarse de que el usuario solo pueda borrar sus propias consultas
        if ($userQuery->user_id !== Auth::id()) {
            abort(403);
        }

        $userQuery->delete();

        return redirect()->route('history.index')->with('success', 'Consulta eliminada de tu historial.');
    }
}