<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(): View
    {
        return view('users.index', [
            'title' => 'Usuarios',
            'heading' => 'Usuarios',
            'estadisticas' => [
                'total' => User::count(),
                'administradores' => User::where('role', 'administrador')->count(),
                'capturistas' => User::where('role', 'capturista')->count(),
                'usuarios' => User::where('role', 'usuario')->count(),
            ],
            'usuarios' => User::latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('users.create', [
            'title' => 'Nuevo usuario',
            'heading' => 'Nuevo usuario',
            'user' => new User(),
            'roles' => User::roles(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        User::create($data);

        return redirect()->route('users.index')
            ->with('success', 'El usuario se registro correctamente.');
    }

    public function edit(User $user): View
    {
        return view('users.edit', [
            'title' => 'Editar usuario',
            'heading' => 'Editar usuario',
            'user' => $user,
            'roles' => User::roles(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validateData($request, $user);

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'El usuario se actualizo correctamente.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('users.index')
                ->with('success', 'No puedes eliminar tu propio usuario mientras tienes la sesion activa.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'El usuario se elimino correctamente.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validateData(Request $request, ?User $user = null): array
    {
        $isUpdate = $user !== null;

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'role' => ['required', Rule::in(array_keys(User::roles()))],
            'password' => [$isUpdate ? 'nullable' : 'required', 'string', 'min:8', 'max:255'],
        ], [
            'name.required' => 'Captura el nombre del usuario.',
            'email.required' => 'Captura el correo electronico.',
            'email.email' => 'Captura un correo electronico valido.',
            'email.unique' => 'Ese correo electronico ya esta registrado.',
            'role.required' => 'Selecciona el rol del usuario.',
            'password.required' => 'Captura la contrasena.',
            'password.min' => 'La contrasena debe tener al menos 8 caracteres.',
        ]);
    }
}
