<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SocioController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));
        $estatus = mb_strtolower(trim((string) $request->string('estatus')));
        $allowedStatuses = ['activa', 'inactiva', 'inerte'];

        if (! in_array($estatus, $allowedStatuses, true)) {
            $estatus = 'activa';
        }

        $sociosQuery = Socio::query()
            ->withCount('empresas')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery
                        ->where('nombre', 'like', "%{$search}%")
                        ->orWhere('rfc', 'like', "%{$search}%")
                        ->orWhere('direccion', 'like', "%{$search}%")
                        ->orWhereHas('empresas', function ($empresaQuery) use ($search) {
                            $empresaQuery->where('nombre', 'like', "%{$search}%");
                        });
                });
            })
            ->when($estatus !== '', fn ($query) => $query->where('estatus', $estatus))
            ->orderBy('nombre');

        $sociosStats = Socio::query();

        return view('socios.index', [
            'socios' => $sociosQuery->paginate(10)->withQueryString(),
            'search' => $search,
            'estatus' => $estatus,
            'estadisticas' => [
                'total' => $sociosStats->count(),
                'activas' => Socio::where('estatus', 'activa')->count(),
                'inactivas' => Socio::where('estatus', 'inactiva')->count(),
                'inertes' => Socio::where('estatus', 'inerte')->count(),
                'asignados' => Socio::has('empresas')->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('socios.create', [
            'socio' => new Socio(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateSocioData($request);

        foreach ($this->socioFileFields() as $field) {
            if ($request->hasFile($field)) {
                $directory = $field === 'foto_usuario' ? 'empresas/socios/fotos' : 'empresas/socios';
                $data[$field] = $this->storeUploadedFileWithLog(
                    $request->file($field),
                    $directory,
                    $field,
                    ['context' => 'socio.store']
                );
            }
        }

        $socio = Socio::create($data);

        return redirect()
            ->route('socios.edit', $socio)
            ->with('success', 'El socio se creo correctamente.');
    }

    public function show(Socio $socio): View
    {
        $socio->load('empresas');

        return view('socios.show', compact('socio'));
    }

    public function edit(Socio $socio): View
    {
        $socio->load('empresas');

        return view('socios.edit', [
            'socio' => $socio,
        ]);
    }

    public function update(Request $request, Socio $socio): RedirectResponse
    {
        $data = $this->validateSocioData($request, $socio->id);

        foreach ($this->socioFileFields() as $field) {
            if ($request->hasFile($field)) {
                if ($socio->{$field}) {
                    Storage::disk('public')->delete($socio->{$field});
                }

                $directory = $field === 'foto_usuario' ? 'empresas/socios/fotos' : 'empresas/socios';
                $data[$field] = $this->storeUploadedFileWithLog(
                    $request->file($field),
                    $directory,
                    $field,
                    [
                        'context' => 'socio.update',
                        'socio_id' => $socio->id,
                    ]
                );
            }
        }

        $socio->update($data);

        return redirect()
            ->route('socios.edit', $socio)
            ->with('success', 'El socio se actualizo correctamente.');
    }

    public function destroy(Socio $socio): RedirectResponse
    {
        foreach ($this->socioFileFields() as $field) {
            if ($socio->{$field}) {
                Storage::disk('public')->delete($socio->{$field});
            }
        }

        $socio->empresas()->detach();
        $socio->delete();

        return redirect()
            ->route('socios.index')
            ->with('success', 'El socio se elimino correctamente.');
    }

    protected function validateSocioData(Request $request, ?int $socioId = null): array
    {
        return $request->validate([
            'estatus' => ['required', 'in:activa,inactiva,inerte'],
            'nombre' => ['required', 'string', 'max:255'],
            'direccion' => ['required', 'string', 'max:255'],
            'rfc' => ['required', 'string', 'max:13', 'unique:socios,rfc,' . $socioId],
            'contrasena' => ['nullable', 'string', 'max:255'],
            'foto_usuario' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'ine_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:524288'],
            'csf_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:524288'],
            'certificado_cer' => ['nullable', 'file', 'extensions:cer', 'max:524288'],
            'llave_key' => ['nullable', 'file', 'extensions:key', 'max:524288'],
        ]);
    }

    protected function socioFileFields(): array
    {
        return ['foto_usuario', 'ine_pdf', 'csf_pdf', 'certificado_cer', 'llave_key'];
    }

    protected function storeUploadedFileWithLog($uploadedFile, string $directory, string $field, array $context = []): string
    {
        Log::info('AM+ intento de subida detectado.', array_merge($context, [
            'field' => $field,
            'directory' => $directory,
            'original_name' => $uploadedFile?->getClientOriginalName(),
            'mime_type' => $uploadedFile?->getClientMimeType(),
            'size' => $uploadedFile?->getSize(),
        ]));

        try {
            $storedPath = $uploadedFile->store($directory, 'public');

            Log::info('AM+ archivo subido correctamente.', array_merge($context, [
                'field' => $field,
                'directory' => $directory,
                'stored_path' => $storedPath,
                'exists_after_store' => Storage::disk('public')->exists($storedPath),
            ]));

            return $storedPath;
        } catch (\Throwable $exception) {
            Log::error('AM+ error al subir archivo.', array_merge($context, [
                'field' => $field,
                'directory' => $directory,
                'original_name' => $uploadedFile?->getClientOriginalName(),
                'message' => $exception->getMessage(),
            ]));

            throw $exception;
        }
    }
}
