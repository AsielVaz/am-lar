<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\UserController;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function (Request $request) {
        $search = trim((string) $request->string('search'));
        $estatus = mb_strtolower(trim((string) $request->string('estatus')));
        $allowedStatuses = ['activa', 'inactiva', 'inerte'];
        $normalizeStatus = static fn (?string $value): string => mb_strtolower(trim((string) $value));

        if (! in_array($estatus, $allowedStatuses, true)) {
            $estatus = '';
        }

        $empresasBase = Empresa::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery
                        ->where('nombre', 'like', "%{$search}%")
                        ->orWhere('rfc', 'like', "%{$search}%")
                        ->orWhere('direccion', 'like', "%{$search}%")
                        ->orWhere('correo', 'like', "%{$search}%");
                });
            })
            ->orderBy('nombre')
            ->get();

        $empresas = $estatus === ''
            ? $empresasBase
            : $empresasBase->filter(fn (Empresa $empresa) => $normalizeStatus($empresa->estatus) === $estatus)->values();

        $estadisticasEmpresas = Empresa::query()->get();

        $empresasAgrupadas = $empresas
            ->groupBy(function (Empresa $empresa) {
                $initial = mb_strtoupper(mb_substr(trim($empresa->nombre), 0, 1));

                return preg_match('/^\p{L}$/u', $initial) ? $initial : '#';
            })
            ->sortKeys()
            ->map(fn (Collection $group) => $group->values());

        return view('dashboard.index', [
            'estadisticas' => [
                'total' => $estadisticasEmpresas->count(),
                'activas' => $estadisticasEmpresas->filter(fn (Empresa $empresa) => $normalizeStatus($empresa->estatus) === 'activa')->count(),
                'inactivas' => $estadisticasEmpresas->filter(fn (Empresa $empresa) => $normalizeStatus($empresa->estatus) === 'inactiva')->count(),
                'inertes' => $estadisticasEmpresas->filter(fn (Empresa $empresa) => $normalizeStatus($empresa->estatus) === 'inerte')->count(),
                'altaPrioridad' => $estadisticasEmpresas->filter(fn (Empresa $empresa) => mb_strtolower(trim((string) $empresa->prioridad)) === 'alta')->count(),
            ],
            'empresasAgrupadas' => $empresasAgrupadas,
            'empresasTotalFiltradas' => $empresas->count(),
            'search' => $search,
            'estatus' => $estatus,
        ]);
    })->name('dashboard');

    Route::middleware('role:administrador')->group(function () {
        Route::get('/empresas/create', [EmpresaController::class, 'create'])->name('empresas.create');
        Route::post('/empresas', [EmpresaController::class, 'store'])->name('empresas.store');
        Route::delete('/empresas/{empresa}', [EmpresaController::class, 'destroy'])->whereNumber('empresa')->name('empresas.destroy');
        Route::delete('/socios/{socio}', [SocioController::class, 'destroy'])->whereNumber('socio')->name('socios.destroy');
        Route::resource('users', UserController::class)->except('show');
    });

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::middleware('role:administrador,capturista')->group(function () {
        Route::get('/empresas', [EmpresaController::class, 'index'])->name('empresas.index');
        Route::get('/empresas/{empresa}/edit', [EmpresaController::class, 'edit'])->whereNumber('empresa')->name('empresas.edit');
        Route::put('/empresas/{empresa}', [EmpresaController::class, 'update'])->whereNumber('empresa')->name('empresas.update');
        Route::get('/socios', [SocioController::class, 'index'])->name('socios.index');
        Route::get('/socios/create', [SocioController::class, 'create'])->name('socios.create');
        Route::post('/socios', [SocioController::class, 'store'])->name('socios.store');
        Route::get('/socios/{socio}', [SocioController::class, 'show'])->whereNumber('socio')->name('socios.show');
        Route::get('/socios/{socio}/edit', [SocioController::class, 'edit'])->whereNumber('socio')->name('socios.edit');
        Route::put('/socios/{socio}', [SocioController::class, 'update'])->whereNumber('socio')->name('socios.update');
    });

    Route::get('/empresas/{empresa}', [EmpresaController::class, 'show'])->whereNumber('empresa')->name('empresas.show');
});
