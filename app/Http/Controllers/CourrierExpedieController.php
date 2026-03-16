<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CourrierExpedie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class CourrierExpedieController extends Controller
{
    /* ==========================
     * LISTE
     * ========================== */

     private array $directionServices = [
        'DRHSG' => [
            'Ressources Humaines',
            'Services Généraux',
            'Ressources Humaines et Services Généraux',
        ],
        'DF' => [
            'Comptabilité',
            'Trésorerie',
            'Caisse',
        ],
        'DCP' => ['Coordination'],
        'DPC' => [
            'Services de la Promotion Culturelle',
            'Production et Animation Culturelle',
        ],
        'CI' => ['Audit interne'],
        'DMR' => ['Taxation', 'Mobilisation de la Redevance'],
        'DR' => ['Recouvrement'],
        'DEFP' => ['Études', 'Planification', 'Formation'],
        'Autres' => [
            'Informatique',
            'Juridique et Contentieux',
            'Secrétariat DG',
            'Assistant DG',
            'Communication',
            'Assistant DGA',
        ],
    ];

    public function index(Request $request)
    {
        $user = Auth::user();

        $courriersAll = CourrierExpedie::with('copies')
            ->orderByDesc('id')
            ->get();

        $userServices = $this->normalizeArrayValues($user->service ?? null);

        if (!$this->isPrivileged($user)) {
            $courriersAll = $courriersAll->filter(function ($courrier) use ($user, $userServices) {
                // Le créateur voit toujours son courrier
                if ((int) $courrier->user_id === (int) $user->id) {
                    return true;
                }

                // Un agent/service normal ne voit que les copies faites exactement à son service
                $copyServices = collect($courrier->copies ?? [])
                    ->flatMap(function ($copy) {
                        return $this->normalizeArrayValues($copy->service ?? null);
                    })
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

                return count(array_intersect($userServices, $copyServices)) > 0;
            })->values();
        }

        $page = (int) $request->get('page', 1);
        $perPage = 15;

        $courriers = new LengthAwarePaginator(
            $courriersAll->forPage($page, $perPage)->values(),
            $courriersAll->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        if ($courriers->count() === 0 && $courriers->total() > 0 && $courriers->currentPage() > 1) {
            return redirect()->route('courrier_expedie.index', ['page' => 1]);
        }

        return view('courrier_expedie.index', compact('courriers'));
    }

    protected function isPrivileged($user): bool
    {
        return $user && in_array($user->role, ['admin', 'DG', 'DGA'], true);
    }

    protected function normalizeValue(?string $value): ?string
    {
        if (!filled($value)) {
            return null;
        }

        $value = Str::ascii($value);
        $value = mb_strtolower(trim($value), 'UTF-8');
        $value = preg_replace('/\s+/u', ' ', $value);

        return $value ?: null;
    }

    protected function normalizeArrayValues($value): array
    {
        if (blank($value)) {
            return [];
        }

        if (is_array($value)) {
            $items = $value;
        } elseif (is_string($value)) {
            $trimmed = trim($value);
            $decoded = json_decode($trimmed, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $items = $decoded;
            } else {
                $items = preg_split('/[,;|\/]+/u', $trimmed) ?: [];
            }
        } else {
            $items = [(string) $value];
        }

        return collect($items)
            ->flatten()
            ->map(fn ($item) => $this->normalizeValue(is_scalar($item) ? (string) $item : null))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    protected function expandUserScopes($user): array
    {
        $scopes = collect([
            ...$this->normalizeArrayValues($user->service ?? null),
            ...$this->normalizeArrayValues($user->direction ?? null),
        ]);

        foreach ($scopes->values() as $scope) {
            foreach ($this->directionServices as $directionCode => $services) {
                $normalizedDirectionCode = $this->normalizeValue($directionCode);

                $normalizedServices = collect($services)
                    ->map(fn ($service) => $this->normalizeValue($service))
                    ->filter()
                    ->unique()
                    ->values();

                if (
                    $scope === $normalizedDirectionCode ||
                    $normalizedServices->contains($scope)
                ) {
                    $scopes = $scopes
                        ->push($normalizedDirectionCode)
                        ->merge($normalizedServices);
                }
            }
        }

        return $scopes
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    /* ==========================
     * FORM CREATE
     * ========================== */
    public function create()
    {
        return view('courrier_expedie.create');
    }

    /* ==========================
     * STORE
     * ========================== */
    public function store(Request $request)
    {
        Log::info('=== STORE COURRIER EXPEDIE : START ===');
        Log::info('Request all()', $request->all());

        $data = $request->validate([
            'numero_ordre'    => 'required|string|max:50',
            'date_expedition' => 'required|date',
            'numero_lettre'   => 'required|string|unique:courrier_expedies,numero_lettre',
            'destinataire'    => 'required|string|max:255',
            'resume'          => 'required|string',
            'observation'     => 'nullable|string',
        ]);

        $data['user_id'] = Auth::id();

        $data['annexes'] = $request->filled('annexes_paths')
            ? json_decode($request->annexes_paths, true)
            : [];

        $copies = $request->filled('copies')
            ? json_decode($request->copies, true)
            : [];

        DB::transaction(function () use ($data, $copies) {
            $courrier = CourrierExpedie::create($data);

            foreach ($copies as $copy) {
                if (!empty($copy['direction']) && !empty($copy['service'])) {
                    $courrier->copies()->create([
                        'direction' => trim($copy['direction']),
                        'service'   => trim($copy['service']),
                    ]);
                }
            }
        });

        Log::info('=== STORE COURRIER EXPEDIE : END ===');

        return redirect()
            ->route('courrier_expedie.index')
            ->with('success', 'Courrier expédié enregistré avec succès');
    }

    public function view(User $user, CourrierExpedie $courrier)
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ((int) $courrier->user_id === (int) $user->id) {
            return true;
        }

        if (blank($user->service) || blank($user->direction)) {
            return false;
        }

        return $courrier->copies()
            ->whereRaw('LOWER(TRIM(service)) = ?', [trim(mb_strtolower($user->service))])
            ->whereRaw('LOWER(TRIM(direction)) = ?', [trim(mb_strtolower($user->direction))])
            ->exists();
    }

    /* ==========================
     * SHOW
     * ========================== */
    public function show(CourrierExpedie $courrierExpedie)
    {
        return view('courrier_expedie.show', compact('courrierExpedie'));
    }

    /* ==========================
     * EDIT
     * ========================== */
    public function edit(CourrierExpedie $courrierExpedie)
    {
        return view('courrier_expedie.edit', compact('courrierExpedie'));
    }

    /* ==========================
     * UPDATE
     * ========================== */
    public function update(Request $request, CourrierExpedie $courrierExpedie)
    {
        $data = $request->validate([
            'numero_ordre'    => 'required|string|max:50',
            'date_expedition' => 'required|date',
            'numero_lettre'   => 'required|string|unique:courrier_expedies,numero_lettre,' . $courrierExpedie->id,
            'destinataire'    => 'required|string|max:255',
            'resume'          => 'required|string',
            'observation'     => 'nullable|string',
        ]);

        $courrierExpedie->update($data);

        return redirect()
            ->route('courrier_expedie.show', $courrierExpedie->id)
            ->with('success', 'Courrier expédié mis à jour');
    }

    /* ==========================
     * DELETE
     * ========================== */
    public function destroy(CourrierExpedie $courrierExpedie)
    {
        if (is_array($courrierExpedie->annexes)) {
            foreach ($courrierExpedie->annexes as $file) {
                Storage::disk('public')->delete($file);
            }
        }

        $courrierExpedie->delete();

        return redirect()
            ->route('courrier_expedie.index')
            ->with('success', 'Courrier expédié supprimé');
    }

    /* ==========================
     * UPLOAD ANNEXE PAR CHUNK
     * ========================== */
    public function uploadChunk(Request $request)
    {
        $request->validate([
            'chunk'        => 'required|file',
            'file_id'      => 'required|string',
            'chunk_index'  => 'required|integer',
            'total_chunks' => 'required|integer',
            'filename'     => 'required|string',
        ]);

        $tempDir = storage_path('app/chunks/courrier_expedie/' . $request->file_id);

        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $chunkPath = $tempDir . '/' . $request->chunk_index;

        file_put_contents(
            $chunkPath,
            file_get_contents($request->file('chunk')->getRealPath())
        );

        if ($request->chunk_index + 1 == $request->total_chunks) {
            $finalName = 'courrier_expedie/' . Str::uuid() . '_' . $request->filename;
            $finalPath = storage_path('app/public/' . $finalName);

            if (!is_dir(dirname($finalPath))) {
                mkdir(dirname($finalPath), 0777, true);
            }

            $out = fopen($finalPath, 'ab');

            for ($i = 0; $i < $request->total_chunks; $i++) {
                fwrite($out, file_get_contents($tempDir . '/' . $i));
                @unlink($tempDir . '/' . $i);
            }

            fclose($out);
            @rmdir($tempDir);

            return response()->json([
                'status' => 'merged',
                'path'   => $finalName,
            ]);
        }

        return response()->json(['status' => 'chunk_received']);
    }
}