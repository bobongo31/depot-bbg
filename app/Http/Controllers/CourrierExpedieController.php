<?php

namespace App\Http\Controllers;

use App\Models\CourrierExpedie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CourrierExpedieController extends Controller
{
    /* ==========================
     * LISTE
     * ========================== */
    public function index()
{
    $user = Auth::user();

    $courriers = CourrierExpedie::with('copies')
        ->latest()
        ->paginate(15);

    return view('courrier_expedie.index', compact('courriers'));
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

    Log::info('Data validée', $data);

    // Annexes
    $data['annexes'] = $request->filled('annexes_paths')
        ? json_decode($request->annexes_paths, true)
        : [];

    Log::info('Annexes décodées', $data['annexes'] ?? []);

    // Copies
    $copies = $request->filled('copies')
        ? json_decode($request->copies, true)
        : [];

    Log::info('Copies brutes (JSON décodé)', $copies);

    DB::transaction(function () use ($data, $copies) {

        Log::info('--- Transaction START ---');

        // création courrier
        $courrier = CourrierExpedie::create($data);

        Log::info('Courrier créé', [
            'id' => $courrier->id
        ]);

        // sauvegarde copies
        foreach ($copies as $index => $copy) {

            Log::info("Traitement copy #{$index}", $copy);

            if (!empty($copy['direction']) && !empty($copy['service'])) {

                $created = $courrier->copies()->create([
                    'direction' => $copy['direction'],
                    'service'   => $copy['service'],
                ]);

                Log::info('Copy créée', [
                    'copy_id' => $created->id ?? null
                ]);
            } else {
                Log::warning('Copy ignorée (direction/service manquant)', $copy);
            }
        }

        Log::info('--- Transaction END ---');
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

    return $courrier->copies()
        ->where('service', $user->service)
        ->where('direction', $user->direction)
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

        // dernier chunk → fusion
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
