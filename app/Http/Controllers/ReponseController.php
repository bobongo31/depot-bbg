<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reponse;
use App\Models\Telegramme;
use App\Models\Annexe;
use App\Models\AccuseReception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\ReponseFinale;
use Carbon\Carbon;



class ReponseController extends Controller
{
    /**
     * Affiche les détails d'un télégramme et de la réponse associée.
     */
    
    
     public function afficherFormulaireReponse($accuseDeReceptionId)
     {
         $accuseDeReception = AccuseReception::findOrFail($accuseDeReceptionId);
         
         return view('reponses.reponse_form', compact('accuseDeReception'));
     }

     public function formAjouterReponseFinale($reponseId)
{
    $reponse = Reponse::findOrFail($reponseId);
    return view('reponses.ajouter_finale', compact('reponse'));
}

     
     public function ajouterReponseFinale(Request $request, $reponseId)
{
    // Validation des données d'entrée
    $request->validate([
        'numero_enregistrement' => 'required|string|max:255', // Validation pour numéro d'enregistrement
        'numero_reference' => 'nullable|string|max:255', // Validation pour numéro de référence (non requis)
        'service_concerne' => 'required|string|max:255',
        'observation' => 'nullable|string',
        'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:10240', // Validation pour les fichiers
    ]);

    // Récupérer la réponse existante par son ID
    $reponseExistante = Reponse::findOrFail($reponseId);

    // Créer une nouvelle réponse finale
    $reponseFinale = new ReponseFinale();
    $reponseFinale->numero_enregistrement = $request->input('numero_enregistrement');
    $reponseFinale->numero_reference = $request->input('numero_reference');
    // Normalize service_concerne before saving (may be array, JSON string, nested array, etc.)
    $reponseFinale->service_concerne = $this->normalizeToJson($request->input('service_concerne'));
    $reponseFinale->observation = $request->input('observation');
    $reponseFinale->telegramme_id = $reponseExistante->telegramme_id;
    $reponseFinale->reponse_id = $reponseId;
    $reponseFinale->user_id = auth()->id(); // ✅ Lien vers l'utilisateur connecté
    $reponseFinale->save();


    // Lier l'annexe si un fichier est téléchargé
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filePath = $file->store('annexes', 'public'); // Sauvegarde dans le répertoire 'annexes'

        // Créer une nouvelle annexe
        $annexe = new Annexe();
        $annexe->file_path = $filePath;
        $annexe->reponse_finale_id = $reponseFinale->id;
        $annexe->telegramme_id = $reponseExistante->telegramme_id; // Lier l'annexe au télégramme
        $annexe->save();
    }

    // Retourner une réponse pour confirmer la création
        return redirect()->route('reponses.showFinale', ['id' => $reponseFinale->id])
    ->with('success', 'Réponse finale ajoutée avec succès et annexe téléchargée.');

}


public function show($id)
{
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('home')
            ->with('error', 'Utilisateur non authentifié.');
    }

    // ✅ ICI EXACTEMENT
    $reponse = Reponse::with([
        'telegramme',
        'telegramme.accuseReception',
        'telegramme.annexes'
    ])->findOrFail($id);

    $telegramme = $reponse->telegramme;

    // 🔐 Autorisation (services)
    $userServices = json_decode($user->service, true) ?? [];
    $userServices = array_map(fn ($s) => mb_strtolower(trim($s)), $userServices);

    $telegrammeServices = [];
    if ($telegramme) {
        $telegrammeServices = json_decode($telegramme->service_concerne, true) ?? [];
        $telegrammeServices = array_map(fn ($s) => mb_strtolower(trim($s)), $telegrammeServices);
    }

    if (
        $user->role !== 'admin' &&
        count(array_intersect($userServices, $telegrammeServices)) === 0
    ) {
        return redirect()->route('home')
            ->with('error', 'Accès refusé : service non autorisé.');
    }

    // ✅ PAS besoin de $accuseReception séparé
    return view('reponses.show', compact('reponse', 'telegramme'));
}




public function showFinale($id)
{
    $user = auth()->user();

    $reponseFinale = ReponseFinale::with(['reponse.telegramme', 'annexes'])->findOrFail($id);
    $reponse = $reponseFinale->reponse;
    $telegramme = $reponse->telegramme ?? null;

    if (!$user) {
        return redirect()->route('home')->with('error', 'Utilisateur non authentifié.');
    }

    // Préparer services utilisateur (champ JSON)
    $userServices = json_decode($user->service, true) ?? [];
    $userServices = array_map('mb_strtolower', array_map('trim', $userServices));

    // Préparer services du télégramme
    $telegrammeServices = [];
    if ($telegramme) {
        $telegrammeServices = json_decode($telegramme->service_concerne, true) ?? [];
        $telegrammeServices = array_map('mb_strtolower', array_map('trim', $telegrammeServices));
    }

    // Autorisation: admin ou intersection non vide
    if (!$user || ($user->role !== 'admin' && count(array_intersect($userServices, $telegrammeServices)) === 0)) {
        return redirect()->route('home')->with('error', 'Accès refusé : service non autorisé.');
    }

    return view('reponses.show_finale', compact('reponseFinale', 'reponse'));
}

public function showWithTelegramme($id)
{
    $user = auth()->user();
    $telegramme = Telegramme::with('annexes')->findOrFail($id);

    if (!$user) {
        return redirect()->route('home')->with('error', 'Utilisateur non authentifié.');
    }

    // Préparer services utilisateur (champ JSON)
    $userServices = json_decode($user->service, true) ?? [];
    $userServices = array_map('mb_strtolower', array_map('trim', $userServices));

    // Préparer services du télégramme
    $telegrammeServices = json_decode($telegramme->service_concerne, true) ?? [];
    $telegrammeServices = array_map('mb_strtolower', array_map('trim', $telegrammeServices));

    // Autorisation: admin ou intersection non vide
    $authorized = false;
    if ($user->role === 'admin') {
        $authorized = true;
    } else {
        $authorized = count(array_intersect($userServices, $telegrammeServices)) > 0;
    }

    if (!$authorized) {
        return redirect()->route('home')->with('error', 'Accès refusé : service non autorisé.');
    }

    // 🔹 Récupération de l'accusé de réception
    $accuseReception = AccuseReception::where('numero_enregistrement', $telegramme->numero_enregistrement)
        ->where('numero_reference', $telegramme->numero_reference)
        ->with('annexes')
        ->first();

    return view('telegramme.show', compact('telegramme', 'accuseReception'));
} // <-- fermeture explicite de showWithTelegramme()



/**
 * Affiche la liste des réponses et des télégrammes.
 */
public function index(Request $request)

{
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('home')->with('error', 'Utilisateur non authentifié.');
    }

    // Filtres
    $q             = $request->query('q');
    $serviceFilter = $request->query('service');
    $from          = $request->query('from');
    $to            = $request->query('to');

    // Nombre d'éléments par page
    $perPage = 10;

    /*
    --------------------------------------------------------------------------
    | ADMIN
    --------------------------------------------------------------------------
    */
    if ($user->hasRole('admin')) {

        // Réponses (pagination dédiée: reponses_page)
        $query = Reponse::with(['telegramme', 'accuseReception'])
                         ->orderBy('created_at', 'desc');


        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('numero_enregistrement', 'like', "%{$q}%")
                    ->orWhere('numero_reference', 'like', "%{$q}%")
                    ->orWhere('observation', 'like', "%{$q}%")
                    ->orWhere('commentaires', 'like', "%{$q}%");
            });
        }

        if ($serviceFilter) {
            $query->where('service_concerne', 'like', '%"' . addslashes($serviceFilter) . '"%');
        }

        if ($from) {
            $query->where('created_at', '>=', Carbon::parse($from)->startOfDay());
        }

        if ($to) {
            $query->where('created_at', '<=', Carbon::parse($to)->endOfDay());
        }

        // Paginate responses with custom page name to avoid conflict with telegrammes
        $reponses = $query->paginate($perPage, ['*'], 'reponses_page')->appends($request->query());

        // Télégrammes en attente (pagination dédiée: telegrammes_page)
        $tquery = Telegramme::with('annexes')->whereDoesntHave('reponses');

        if ($q) {
            $tquery->where(function ($sub) use ($q) {
                $sub->where('numero_enregistrement', 'like', "%{$q}%")
                    ->orWhere('numero_reference', 'like', "%{$q}%")
                    ->orWhere('observation', 'like', "%{$q}%")
                    ->orWhere('commentaires', 'like', "%{$q}%");
            });
        }

        if ($serviceFilter) {
            $tquery->where('service_concerne', 'like', '%"' . addslashes($serviceFilter) . '"%');
        }

        if ($from) {
            $tquery->where('created_at', '>=', Carbon::parse($from)->startOfDay());
        }

        if ($to) {
            $tquery->where('created_at', '<=', Carbon::parse($to)->endOfDay());
        }

        $telegrammesEnAttente = $tquery->paginate($perPage, ['*'], 'telegrammes_page')->appends($request->query());
    }

    /*
    --------------------------------------------------------------------------
    | NON ADMIN
    --------------------------------------------------------------------------
    */
    else {

        // Services utilisateur (JSON safe)
        $userServices = json_decode($user->service, true);
        $userServices = is_array($userServices)
            ? array_map(fn($s) => mb_strtolower(trim($s)), $userServices)
            : [];

        /*
        | Réponses
        */
        $allReponses = Reponse::with('telegramme')->orderBy('created_at', 'desc')->get();

        $reponsesFiltered = $allReponses->filter(function ($r) use ($userServices, $q, $from, $to) {

            $services = json_decode($r->service_concerne, true);
            $services = is_array($services)
                ? array_map(fn($s) => mb_strtolower(trim($s)), $services)
                : [];

            // Autorisation service
            if (count(array_intersect($userServices, $services)) === 0) {
                return false;
            }

            // Recherche texte
            if ($q) {
                foreach (['numero_enregistrement', 'numero_reference', 'observation', 'commentaires'] as $field) {
                    $value = $this->safeString($r->{$field});

                    if ($value !== '' && stripos($value, $q) !== false) {
                        return true;
                    }
                }
                return false;
            }

            // Dates
            if ($from && $r->created_at < Carbon::parse($from)->startOfDay()) return false;
            if ($to && $r->created_at > Carbon::parse($to)->endOfDay()) return false;

            return true;
        });

        // Pagination manuelle pour les réponses (page param: reponses_page)
        $reponsesPage = (int) request()->get('reponses_page', 1);
        $items       = $reponsesFiltered->slice(($reponsesPage - 1) * $perPage, $perPage)->values();

        $reponses = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $reponsesFiltered->count(),
            $perPage,
            $reponsesPage,
            ['path' => request()->url(), 'query' => request()->query(), 'pageName' => 'reponses_page']
        );

        /*
        | Télégrammes en attente
        */
        $allTelegrammes = Telegramme::with('annexes')
            ->whereDoesntHave('reponses')
            ->orderBy('created_at', 'desc')
            ->get();

        $telegrammesFiltered = $allTelegrammes->filter(function ($t) use ($userServices, $q, $from, $to) {

            $services = json_decode($t->service_concerne, true);
            $services = is_array($services)
                ? array_map(fn($s) => mb_strtolower(trim($s)), $services)
                : [];

            if (count(array_intersect($userServices, $services)) === 0) {
                return false;
            }

            if ($q) {
                foreach (['numero_enregistrement', 'numero_reference', 'observation', 'commentaires'] as $field) {
                    $value = $this->safeString($t->{$field});

                    if ($value !== '' && stripos($value, $q) !== false) {
                        return true;
                    }
                }
                return false;
            }

            if ($from && $t->created_at < Carbon::parse($from)->startOfDay()) return false;
            if ($to && $t->created_at > Carbon::parse($to)->endOfDay()) return false;

            return true;
        })->values();

        // Pagination manuelle pour telegrammes (page param: telegrammes_page)
        $telegrammesPage = (int) request()->get('telegrammes_page', 1);
        $tgItems = $telegrammesFiltered->slice(($telegrammesPage - 1) * $perPage, $perPage)->values();

        $telegrammesEnAttente = new \Illuminate\Pagination\LengthAwarePaginator(
            $tgItems,
            $telegrammesFiltered->count(),
            $perPage,
            $telegrammesPage,
            ['path' => request()->url(), 'query' => request()->query(), 'pageName' => 'telegrammes_page']
        );
    }

    /*
    --------------------------------------------------------------------------
    | Délais / alertes
    --------------------------------------------------------------------------
    */
    foreach ($telegrammesEnAttente as $telegramme) {
        $dueDate = $telegramme->created_at->copy()->addHours(168);
        $telegramme->remainingHours = max(0, round($dueDate->diffInHours(now())));
        $telegramme->isLate    = $dueDate < now();
        $telegramme->isWarning = !$telegramme->isLate && $telegramme->remainingHours <= 48;
    }

    /*
    |--------------------------------------------------------------------------
    | Groupement & filtres services
    |--------------------------------------------------------------------------
    */
    $reponsesGrouped = $reponses->getCollection()
        ->groupBy(fn($r) => $r->created_at->format('Y-m-d'));

    $serviceSet = [];
    $serviceStrings = array_merge(
        Reponse::pluck('service_concerne')->toArray(),
        Telegramme::pluck('service_concerne')->toArray()
    );

    foreach ($serviceStrings as $s) {
        if (!$s) continue;
        $decoded = json_decode($s, true);
        if (is_array($decoded)) {
            foreach ($decoded as $item) {
                $serviceSet[] = trim((string)$item);
            }
        }
    }

    $services = collect($serviceSet)->filter()->unique()->sort()->values()->all();

    return view('reponses.index', compact(
        'telegrammesEnAttente',
        'reponses',
        'reponsesGrouped',
        'services'
    ));
}


   
    /**
     * Affiche le formulaire de création d'une réponse.
     * Si un `telegramme_id` est passé en paramètre, il est inclus dans le formulaire.
     */
        public function create(Request $request)
{
    $telegramme_id = $request->query('telegramme_id');

    // Récupère les numéros d'enregistrement depuis la table telegrammes
    $telegrammes = Telegramme::all();

    // Si un telegramme_id est fourni, récupérer l'objet pour préremplissage
    $prefillTelegramme = null;

    if ($telegramme_id) {
        $prefillTelegramme = Telegramme::with('annexes')->find($telegramme_id);

        // Allow admins to access any telegramme for replying
        if (
            !$prefillTelegramme &&
            auth()->check() &&
            auth()->user()->hasRole('admin')
        ) {
            $prefillTelegramme = Telegramme::with('annexes')->find($telegramme_id);
        }
    }

    return view('reponses.create', compact(
        'telegramme_id',
        'telegrammes',
        'prefillTelegramme'
    ));
}

   
    /**
     * Stocke une réponse dans la base de données et enregistre les annexes associées.
     * Si un telegramme_id est fourni, la réponse est associée à ce télégramme.
     */
   public function store(Request $request)
{
    $validated = $request->validate([
        'numero_enregistrement' => 'required|string',
        'numero_reference'      => 'nullable|string',
        'service_concerne'      => 'required|array',
        'service_concerne.*'    => 'string',
        'observation'           => 'nullable|string',
        'commentaires'          => 'required|string',
        'annexes'               => 'nullable|array',
        'annexes.*'             => 'mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        'telegramme_id'         => 'nullable|exists:telegrammes,id',
    ]);

    $reponse = new Reponse();
    $reponse->numero_enregistrement = $validated['numero_enregistrement'];
    $reponse->numero_reference      = $validated['numero_reference'];
    // Convert input to a valid JSON string (never store raw arrays)
    $reponse->service_concerne      = $this->normalizeToJson($validated['service_concerne']);
    $reponse->observation           = $validated['observation'];
    $reponse->commentaires          = $validated['commentaires'];
    $reponse->user_id               = auth()->id();

    if (!empty($validated['telegramme_id'])) {
        $reponse->telegramme_id = $validated['telegramme_id'];
    }

    $reponse->save();

    if ($request->hasFile('annexes')) {
        foreach ($request->file('annexes') as $file) {
            if ($file->isValid()) {
                $filePath = $file->store('annexes', 'public');
                $reponse->annexes()->create([
                    'file_path' => $filePath,
                ]);
            }
        }
    }

    /* =========================
     * 3. PASSER L’ACCUSÉ À TRAITÉ
     * ========================= */
    $accuse = AccuseReception::where(
        'numero_enregistrement',
        $validated['numero_enregistrement']
    )->first();

    if ($accuse) {
        $accuse->statut = 'traité';
        $accuse->save();
    }

    return redirect()->route('reponses.index')->with('success', 'Réponse enregistrée avec succès !');
}

    /**
     * Affiche le formulaire de création d'un télégramme.
     */
    public function createTelegramme(Request $request)
    {
        $accuse_receptions = AccuseReception::all(); // Récupère tous les enregistrements
        $draft = null;
        $telegrammeId = $request->query('telegramme_id');
        if ($telegrammeId) {
            $draft = Telegramme::with('annexes')->where('id', $telegrammeId)->where('user_id', auth()->id())->first();
            // allow admins to view any draft
            if (!$draft && auth()->user() && auth()->user()->hasRole('admin')) {
                $draft = Telegramme::with('annexes')->find($telegrammeId);
            }
        }
        return view('telegramme.create', compact('accuse_receptions', 'draft'));
    }
   
    /**
     * Stocke un télégramme dans la base de données et enregistre ses annexes.
     */
    public function storeTelegramme(Request $request)
{
    /* =========================
     * 1. VALIDATION
     * ========================= */
    $validated = $request->validate([
        'numero_enregistrement' => 'required|string',
        'numero_reference'      => 'nullable|string',
        'service_concerne'      => 'required|array|min:1',
        'service_concerne.*'    => 'string',
        'observation'           => 'nullable|string',
        'commentaires'          => 'nullable|string',
        'annexes'               => 'nullable|array',
        'annexes.*'             => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:20048',
    ]);

    /* =========================
     * 2. NORMALISATION SERVICES
     * ========================= */
    $services = array_values(array_unique(array_filter(
        $validated['service_concerne']
    )));

    if (empty($services)) {
        return back()
            ->withErrors(['service_concerne' => 'Veuillez sélectionner au moins un service.'])
            ->withInput();
    }

    /* =========================
     * 3. CRÉATION DU TÉLÉGRAMME
     * ========================= */
    $telegramme = Telegramme::create([
        'user_id'               => auth()->id(),
        'numero_enregistrement' => $validated['numero_enregistrement'],
        'numero_reference'      => $validated['numero_reference'] ?? null,
        'service_concerne'      => json_encode($services, JSON_UNESCAPED_UNICODE),
        'observation'           => $validated['observation'] ?? null,
        'commentaires'          => $validated['commentaires'] ?? null,
        'statut'                => 'en attente', // ENUM valide
    ]);

    /* =========================
     * 4. ANNEXES
     * ========================= */
    if ($request->hasFile('annexes')) {
        foreach ($request->file('annexes') as $file) {
            if ($file->isValid()) {
                $path = $file->store('annexes', 'public');

                Annexe::create([
                    'telegramme_id' => $telegramme->id,
                    'file_path'     => $path,
                ]);
            }
        }
    }

    /* =========================
     * 5. MISE À JOUR ACCUSÉ
     * ========================= */
    $accuse = AccuseReception::where(
        'numero_enregistrement',
        $validated['numero_enregistrement']
    )->first();

    if ($accuse) {
        $accuse->statut = 'en attente';
        $accuse->save();
    }

    /* =========================
     * 6. FIN
     * ========================= */
    return redirect()
        ->route('reponses.index')
        ->with('success', 'Télégramme enregistré avec succès !');
}


    // Save draft for telegramme (autosave)
    public function saveDraftTelegramme(Request $request)
    {
        $data = $request->only(['numero_enregistrement','numero_reference','service_concerne','observation','commentaires','uploaded_paths','draft_id']);
        $draftId = $data['draft_id'] ?? null;

        if ($draftId) {
            $telegramme = Telegramme::where('id', $draftId)->where('user_id', auth()->id())->first();
        } else {
            $telegramme = null;
        }

        // --- Calculer service_codes à partir de service_concerne si présent ---
        $servicesConfig = config('services', []);
        $normalize = function($v) {
            // Safely flatten arrays/objects to string (handles nested arrays) to avoid PHP warnings
            if (is_array($v) || is_object($v)) {
                $parts = [];
                if (is_array($v)) {
                    array_walk_recursive($v, function($item) use (&$parts) {
                        $parts[] = (string)$item;
                    });
                } else {
                    $parts[] = (string)$v;
                }
                $v = implode(' ', $parts);
            }

            $s = trim((string)$v);
            $trans = @iconv('UTF-8', 'ASCII//TRANSLIT', $s);
            if ($trans === false) { $trans = $s; }
            $trans = mb_strtolower($trans);
            return preg_replace('/[^a-z0-9]+/u', '', $trans);
        };
        $labelToCode = [];
        foreach ($servicesConfig as $code => $label) {
            $labelToCode[$normalize($label)] = $code;
        }

        $inputServices = $data['service_concerne'] ?? [];

        // 🔒 NORMALISATION
        if (is_string($inputServices)) {
            $decoded = json_decode($inputServices, true);
            if (json_last_error() === JSON_ERROR_NONE && $decoded !== null) {
                $inputServices = $decoded;
            } else {
                $inputServices = [$inputServices];
            }
        }

        if (!is_array($inputServices)) {
            $inputServices = [];
        }

        $codes = [];
        foreach ((array)$inputServices as $item) {
            if (is_array($item)) {
                $item = implode(' ', $item);
            }
            $itemTrim = trim((string)$item);
            if (isset($servicesConfig[$itemTrim])) { $codes[] = $itemTrim; continue; }
            $norm = $normalize($itemTrim);
            if (isset($labelToCode[$norm])) { $codes[] = $labelToCode[$norm]; continue; }
            foreach ($servicesConfig as $code => $label) {
                $labelStr = $this->safeString($label);
                $itemStr = $this->safeString($itemTrim);

                if ($labelStr !== '' && $itemStr !== '' && (stripos($labelStr, $itemStr) !== false || stripos($itemStr, $labelStr) !== false)) {
                    $codes[] = $code; break;
                }
             }
        }
        $codes = array_values(array_unique(array_filter($codes)));

        if ($telegramme) {
            $telegramme->numero_enregistrement = $data['numero_enregistrement'] ?? $telegramme->numero_enregistrement;
            $telegramme->numero_reference = $data['numero_reference'] ?? $telegramme->numero_reference;
            // Use normalizer to ensure a JSON string is stored (handles array, json-string, nested arrays)
            $telegramme->service_concerne = $this->normalizeToJson(
                $data['service_concerne'] ?? json_decode($telegramme->service_concerne, true)
            );
            $telegramme->service_codes = $codes ? json_encode($codes) : null;
            $telegramme->observation = $data['observation'] ?? $telegramme->observation;
            $telegramme->commentaires = $data['commentaires'] ?? $telegramme->commentaires;
            $telegramme->statut = 'brouillon';
            $telegramme->save();
        } else {
            $telegramme = new Telegramme();
            $telegramme->user_id = auth()->id();
            $telegramme->numero_enregistrement = $data['numero_enregistrement'] ?? null;
            $telegramme->numero_reference = $data['numero_reference'] ?? null;
            $telegramme->service_concerne = $this->normalizeToJson($data['service_concerne'] ?? []);
            $telegramme->service_codes = $codes ? json_encode($codes) : null;
            $telegramme->observation = $data['observation'] ?? null;
            $telegramme->commentaires = $data['commentaires'] ?? null;
            $telegramme->statut = 'brouillon';
            $telegramme->save();
        }

        if (!empty($data['uploaded_paths'])) {
            $paths = json_decode($data['uploaded_paths'], true);
            if (is_array($paths)) {
                foreach ($paths as $p) {
                    $exists = Annexe::where('telegramme_id', $telegramme->id)->where('file_path', $p)->exists();
                    if (! $exists) {
                        Annexe::create(['telegramme_id' => $telegramme->id, 'file_path' => $p]);
                    }
                }
            }
        }

        return response()->json(['status' => 'ok', 'id' => $telegramme->id]);
    }

   
    /**
     * Supprime une réponse et, si elle est associée à un télégramme,
     * supprime également le télégramme et ses annexes.
     */
        public function destroyTelegramme($id)
{
    $user = auth()->user();

    if (!$user || !$user->isAdmin()) {
        return redirect()
            ->route('telegrammes.index')
            ->with('error', 'Vous n’avez pas l’autorisation de supprimer ce télégramme.');
    }

    $telegramme = Telegramme::with('annexes')->findOrFail($id);

    // 🔥 Supprimer les fichiers annexes du disque
    if (!empty($telegramme->annexes) && is_iterable($telegramme->annexes)) {
        foreach ($telegramme->annexes as $annexe) {
            if (!empty($annexe->file_path) && Storage::disk('public')->exists($annexe->file_path)) {
                Storage::disk('public')->delete($annexe->file_path);
            }
        }
    }

    // 🧹 Supprimer les annexes en base
    Annexe::where('telegramme_id', $telegramme->id)->delete();

    // 🗑️ Supprimer le télégramme
    $telegramme->delete();

    return redirect()
        ->route('telegrammes.index')
        ->with('success', 'Télégramme supprimé avec succès.');
}

    
     public function destroy($id)
    {
        $reponse = Reponse::findOrFail($id);
       
        if ($reponse->telegramme_id) {
            $telegramme = Telegramme::find($reponse->telegramme_id);
            if ($telegramme) {
                // Supprimer les annexes associées au télégramme
                Annexe::where('telegramme_id', $telegramme->id)->delete();
                $telegramme->delete();
            }
        }
   
        $reponse->delete();
   
        return redirect()->route('reponses.index')->with('success', 'Réponse et son télégramme associé supprimés avec succès.');
    }

    /**
     * Normalize different input types to a JSON string suitable for DB storage.
     * Accepts: null, JSON string, plain string, array (including nested arrays), other scalar.
     * Returns: JSON string or null.
     */
    private function safeString($value): string
    {
        if (is_array($value) || is_object($value)) {
            $json = json_encode($value, JSON_UNESCAPED_UNICODE);
            return $json === false ? '' : $json;
        }

        return (string) ($value ?? '');
    }

    private function normalizeToJson($value)
    {
        if (is_null($value)) {
            return null;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return json_encode($decoded);
            }
            return json_encode([$value]);
        }

        if (is_array($value)) {
            return json_encode($value);
        }

        return json_encode([(string)$value]);
    }

}