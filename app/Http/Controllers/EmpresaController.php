<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\EmpresaDocumento;
use App\Models\Socio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EmpresaController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));

        $empresasQuery = Empresa::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery
                        ->where('nombre', 'like', "%{$search}%")
                        ->orWhere('rfc', 'like', "%{$search}%")
                        ->orWhere('direccion', 'like', "%{$search}%")
                        ->orWhere('correo', 'like', "%{$search}%");
                });
            })
            ->orderBy('nombre');

        return view('empresas.index', [
            'empresas' => $empresasQuery->paginate(10)->withQueryString(),
            'search' => $search,
            'estadisticas' => [
                'total' => Empresa::count(),
                'activas' => Empresa::where('estatus', 'activa')->count(),
                'inactivas' => Empresa::where('estatus', 'inactiva')->count(),
                'inertes' => Empresa::where('estatus', 'inerte')->count(),
                'altaPrioridad' => Empresa::where('prioridad', 'alta')->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('empresas.create', [
            'empresa' => new Empresa(),
            'canEditCompanyData' => true,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateGeneralData($request);

        if ($request->hasFile('logo')) {
            $data['logo'] = $this->storeUploadedFileWithLog(
                $request->file('logo'),
                'empresas/logos',
                'logo',
                ['context' => 'empresa.store']
            );
        }

        $empresa = Empresa::create($data);

        return redirect()
            ->route('empresas.edit', $empresa)
            ->with('success', 'La empresa se creo correctamente. Ahora puedes capturar su documentacion y asignar socios.');
    }

    public function show(Empresa $empresa): View
    {
        $empresa->load(['documentos', 'socios']);

        return view('empresas.show', compact('empresa'));
    }

    public function edit(Empresa $empresa): View
    {
        $empresa->load(['documentos', 'socios']);

        return view('empresas.edit', [
            'empresa' => $empresa,
            'sociosDisponibles' => Socio::query()->orderBy('nombre')->get(['id', 'nombre', 'rfc', 'puesto']),
            'canEditCompanyData' => Auth::user()?->isAdministrador() ?? false,
        ]);
    }

    public function update(Request $request, Empresa $empresa): RedirectResponse
    {
        $section = $request->input('form_section', 'general');

        if ($section === 'partners') {
            return redirect()
                ->route('empresas.edit', $empresa)
                ->with('success', $this->syncSocioAssignments($request, $empresa));
        }

        if (Auth::user()?->isCapturista()) {
            $this->validateDocumentData($request);
            $this->syncDocumentos($request, $empresa);

            return redirect()
                ->route('empresas.edit', $empresa)
                ->with('success', 'Los documentos de la empresa se guardaron correctamente.');
        }

        $data = $this->validateGeneralData($request, $empresa->id);

        if ($request->hasFile('logo')) {
            if ($empresa->logo) {
                Storage::disk('public')->delete($empresa->logo);
            }

            $data['logo'] = $this->storeUploadedFileWithLog(
                $request->file('logo'),
                'empresas/logos',
                'logo',
                [
                    'context' => 'empresa.update',
                    'empresa_id' => $empresa->id,
                ]
            );
        }

        $empresa->update($data);
        $this->syncDocumentos($request, $empresa);

        return redirect()
            ->route('empresas.edit', $empresa)
            ->with('success', 'Los datos de la empresa y sus documentos se guardaron correctamente.');
    }

    public function destroy(Empresa $empresa): RedirectResponse
    {
        $empresa->load(['documentos']);

        if ($empresa->logo) {
            Storage::disk('public')->delete($empresa->logo);
        }

        if ($empresa->documentos) {
            foreach ($this->documentFields() as $field) {
                if ($empresa->documentos->{$field}) {
                    Storage::disk('public')->delete($empresa->documentos->{$field});
                }
            }
        }

        $empresa->socios()->detach();
        $empresa->delete();

        return redirect()
            ->route('empresas.index')
            ->with('success', 'La empresa se elimino correctamente.');
    }

    protected function validateGeneralData(Request $request, ?int $empresaId = null): array
    {
        return $request->validate(array_merge(
            $this->companyRules($empresaId),
            $this->documentRules(),
        ));
    }

    protected function validateDocumentData(Request $request): array
    {
        return $request->validate($this->documentRules());
    }

    protected function companyRules(?int $empresaId = null): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'rfc' => ['required', 'string', 'max:13', 'unique:empresas,rfc,' . $empresaId],
            'direccion' => ['required', 'string', 'max:500'],
            'codigo_postal' => ['required', 'string', 'max:10'],
            'estatus' => ['required', 'in:activa,inactiva,inerte'],
            'prioridad' => ['required', 'in:alta,media,baja'],
            'contrasena_iofacturo' => ['nullable', 'string', 'max:255'],
            'sitio_web' => ['nullable', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'correo' => ['nullable', 'email', 'max:255'],
            'fin_dominio_web' => ['nullable', 'date'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    protected function documentRules(): array
    {
        return [
            'acta_constitutiva_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'registro_publico_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'd32_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'sello_sat_key' => ['nullable', 'file', 'extensions:key', 'max:5120'],
            'sello_sat_key_contrasena' => ['nullable', 'string', 'max:255'],
            'sello_sat_cer' => ['nullable', 'file', 'extensions:cer', 'max:5120'],
            'fiel_key' => ['nullable', 'file', 'extensions:key', 'max:5120'],
            'fiel_key_contrasena' => ['nullable', 'string', 'max:255'],
            'fiel_cer' => ['nullable', 'file', 'extensions:cer', 'max:5120'],
            'comprobante_domicilio_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ];
    }

    protected function syncDocumentos(Request $request, Empresa $empresa): void
    {
        $documentos = $empresa->documentos ?? new EmpresaDocumento(['empresa_id' => $empresa->id]);

        foreach ($this->documentPasswordFields() as $field) {
            $documentos->{$field} = $request->input($field);
        }

        foreach ($this->documentFields() as $field) {
            if ($request->hasFile($field)) {
                if ($documentos->{$field}) {
                    Storage::disk('public')->delete($documentos->{$field});
                }

                $folder = match (true) {
                    in_array($field, ['sello_sat_key', 'sello_sat_cer', 'fiel_key', 'fiel_cer'], true) => 'empresas/certificados',
                    default => 'empresas/documentos',
                };

                $documentos->{$field} = $this->storeUploadedFileWithLog(
                    $request->file($field),
                    $folder,
                    $field,
                    [
                        'context' => 'empresa.documentos',
                        'empresa_id' => $empresa->id,
                    ]
                );

                if ($field === 'd32_pdf') {
                    $documentos->d32_vigencia_inicio = now()->toDateString();
                }

                if ($field === 'comprobante_domicilio_pdf') {
                    $documentos->comprobante_domicilio_vigencia_inicio = now()->toDateString();
                }
            }
        }

        if ($documentos->isDirty() || ! $documentos->exists) {
            $empresa->documentos()->save($documentos);
        }
    }

    protected function syncSocioAssignments(Request $request, Empresa $empresa): string
    {
        $data = $request->validate([
            'assignment_action' => ['required', 'in:assign,remove'],
            'socio_id' => ['required', 'integer', 'exists:socios,id'],
        ]);

        $socio = Socio::findOrFail($data['socio_id']);

        if ($data['assignment_action'] === 'assign') {
            $alreadyAssigned = $empresa->socios()->where('socios.id', $socio->id)->exists();
            $empresa->socios()->syncWithoutDetaching([$socio->id]);

            return $alreadyAssigned
                ? 'El socio ya estaba asignado a esta empresa.'
                : 'El socio se asigno correctamente a la empresa.';
        }

        $empresa->socios()->detach($socio->id);

        return 'El socio se quito correctamente de la empresa.';
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

    protected function documentFields(): array
    {
        return [
            'acta_constitutiva_pdf',
            'registro_publico_pdf',
            'd32_pdf',
            'sello_sat_key',
            'sello_sat_cer',
            'fiel_key',
            'fiel_cer',
            'comprobante_domicilio_pdf',
        ];
    }

    protected function documentPasswordFields(): array
    {
        return [
            'sello_sat_key_contrasena',
            'fiel_key_contrasena',
        ];
    }
}
