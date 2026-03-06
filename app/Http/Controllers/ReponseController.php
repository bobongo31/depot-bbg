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
     * Mapping Directions => Services
     */
    private array $directionServices = [
        'DRHSG' => [
            'Ressources Humaines',
            'Services Généraux',
            'Ressources Humaines et Services Généraux'
        ],
        'DF' => [
            'Comptabilité',
            'Trésorerie',
            'Caisse'
        ],
        'DCP' => ['Coordination'],
        'DPC' => [
            'Services de la Promotion Culturelle',
            'Production et Animation Culturelle'
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
            'Assistant DGA'
        ],
    ];
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

    /* =========================
     * 3. PASSER L’ACCUSÉ À TRAITÉ (uniquement pour réponse finale)
     * ========================= */
    $accuseQuery = AccuseReception::where('numero_enregistrement', $reponseFinale->numero_enregistrement);
    if (!empty($reponseFinale->numero_reference)) {
        $accuseQuery->where('numero_reference', $reponseFinale->numero_reference);
    }

    $accuse = $accuseQuery->first();
    if ($accuse) {
        $accuse->statut = 'traité';
        $accuse->save();
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

    // 🔐 Autorisation (services) - utiliser normalisation centralisée
    $userServices = $this->normalizeServiceArray($user->service);
    $telegrammeServices = $this->normalizeServiceArray($telegramme ? $telegramme->service_concerne : null);

    if (
        !$this->isAdmin($user) &&
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
    $userServices = $this->normalizeServiceArray($user->service);
    $telegrammeServices = $this->normalizeServiceArray($telegramme ? $telegramme->service_concerne : null);

    // Autorisation: admin ou intersection non vide
        if (!$user || (!$this->isAdmin($user) && count(array_intersect($userServices, $telegrammeServices)) === 0)) {
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
    $userServices = $this->normalizeServiceArray($user->service);
    $telegrammeServices = $this->normalizeServiceArray($telegramme->service_concerne);

    // Autorisation: admin ou intersection non vide
    $authorized = false;
    if ($this->isAdmin($user)) {
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
            return redirect()->route('home')->with('error', 'Utilisateur non authentifié');
        }

        $q       = $request->query('q');
        $service = $request->query('service');
        $from    = $request->query('from');
        $to      = $request->query('to');
        $perPage = 10;

        /*
        |--------------------------------------------------------------------------
        | SERVICES UTILISATEUR (étendus via mapping)
        |--------------------------------------------------------------------------
        */
        $userServices = $this->expandUserServices($user->service);

        /*
        |--------------------------------------------------------------------------
        | RÉPONSES
        |--------------------------------------------------------------------------
        */
        // Charger toutes les réponses (avec relations) puis filtrer en mémoire
        $reponsesAll = Reponse::with('telegramme')
            ->orderBy('created_at', 'desc')
            ->get();

        if (!$this->isAdmin($user)) {
            $reponsesAll = $reponsesAll->filter(function ($r) use ($userServices, $user) {
                // Always allow the owner to see their own response
                if (isset($r->user_id) && $r->user_id == $user->id) {
                    return true;
                }

                $servicesConcerne = $this->normalizeServiceArray($r->service_concerne);
                return count(array_intersect($userServices, $servicesConcerne)) > 0;
            });
        }

        // Pagination manuelle
        $page = $request->get('reponses_page', 1);

        $reponses = new \Illuminate\Pagination\LengthAwarePaginator(
            $reponsesAll->forPage($page, $perPage)->values(),
            $reponsesAll->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | ENRICHIR RÉPONSES (statuts, services affichés)
        |--------------------------------------------------------------------------
        */
        $reponses->getCollection()->transform(function ($r) {
            // Services affichés
            $r->services_affiches = $this->normalizeServiceArray($r->service_concerne);

            // Sécurité : réponse sans télégramme
            if (!$r->telegramme) {
                $r->isLate = false;
                $r->statutLabel = '—';
                $r->deadlineFormatted = null;
                return $r;
            }

            // ⏱ Date limite = télégramme + 3 jours (HEURE INCLUSE)
            $deadline = $r->telegramme->created_at->copy()->addDays(3);

            // 🔥 Comparaison AVEC HEURE
            $r->isLate = $r->created_at->greaterThan($deadline);

            $r->statutLabel = $r->isLate
                ? 'EN RETARD'
                : 'DANS LE DÉLAI';

            $r->deadlineFormatted = $deadline->translatedFormat('d/m/Y H:i');

            return $r;
        });

        /*
        |--------------------------------------------------------------------------
        | TÉLÉGRAMMES EN ATTENTE
        |--------------------------------------------------------------------------
        */
        // Charger tous les télégrammes en attente puis filtrer en mémoire
        $telegrammesAll = Telegramme::whereDoesntHave('reponses')
            ->orderBy('created_at', 'desc')
            ->get();

        if (!$this->isAdmin($user)) {
            $telegrammesAll = $telegrammesAll->filter(function ($t) use ($userServices, $user) {
                // Always allow the creator to see their own telegramme
                if (isset($t->user_id) && $t->user_id == $user->id) {
                    return true;
                }

                $services = $this->normalizeServiceArray($t->service_concerne);
                return count(array_intersect($userServices, $services)) > 0;
            });
        }

        // Pagination manuelle pour les télégrammes
        $pageT = $request->get('telegrammes_page', 1);

        $telegrammesEnAttente = new \Illuminate\Pagination\LengthAwarePaginator(
            $telegrammesAll->forPage($pageT, $perPage)->values(),
            $telegrammesAll->count(),
            $perPage,
            $pageT,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | ENRICHIR TÉLÉGRAMMES
        |--------------------------------------------------------------------------
        */
        $telegrammesEnAttente->getCollection()->transform(function ($t) {
            $services = $this->normalizeServiceArray($t->service_concerne);
            $t->services_affiches = $services;

            // Date limite = création + 3 jours
            $deadline = $t->created_at->copy()->addDays(3);
            $now = now();

            // Dernière réponse (défensive - dans ce listing we expect none, but keep logic reusable)
            $lastReponse = null;
            if (isset($t->reponses) && is_iterable($t->reponses)) {
                $lastReponse = collect($t->reponses)->sortByDesc('created_at')->first();
            }

            if ($lastReponse) {
                if ($lastReponse->created_at->lessThanOrEqualTo($deadline)) {
                    $t->statutLabel = 'DANS LE DÉLAI';
                    $t->isLate = false;
                } else {
                    $t->statutLabel = 'EN RETARD';
                    $t->isLate = true;
                }
            } else {
                // Aucune réponse
                if ($now->greaterThan($deadline)) {
                    $t->statutLabel = 'EN ATTENTE';
                    $t->isLate = true;
                } else {
                    $t->statutLabel = 'EN COURS';
                    $t->isLate = false;
                }
            }

            // Pour affichage optionnel
            $t->remainingHours = max(0, $now->diffInHours($deadline));
            $t->dateLimite = mb_strtoupper(
                $deadline->translatedFormat('d M Y H:i'),
                'UTF-8'
            );

            return $t;
        });

        /*
        |--------------------------------------------------------------------------
        | GROUPEMENT PAR DATE
        |--------------------------------------------------------------------------
        */
        $reponsesGrouped = $reponses->getCollection()
            ->groupBy(fn ($r) => $r->created_at->format('Y-m-d'));

        /*
        |--------------------------------------------------------------------------
        | LISTE SERVICES (FILTRE)
        |--------------------------------------------------------------------------
        */
        $services = collect($this->directionServices)
            ->flatten()
            ->unique()
            ->sort()
            ->values()
            ->all();

        return view('reponses.index', compact(
            'reponses',
            'reponsesGrouped',
            'telegrammesEnAttente',
            'services'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    private function isAdmin($user): bool
    {
        $role = $user->role ?? null;

        if ($role === 'admin' || $role === 'DG') {
            return true;
        }

        if (method_exists($user, 'hasRole') && ($user->hasRole('admin') || $user->hasRole('DG'))) {
            return true;
        }

        return false;
    }

    private function expandUserServices($raw): array
    {
        $base = $this->normalizeServiceArray($raw);
        $expanded = $base;

        foreach ($base as $svc) {
            if (isset($this->directionServices[$svc])) {
                foreach ($this->directionServices[$svc] as $mapped) {
                    $expanded[] = $this->normalizeServiceLabel($mapped);
                }
            }
        }

        return array_values(array_unique($expanded));
    }

    private function normalizeServiceLabel(string $value): string
    {
        $v = mb_strtolower(trim($value), 'UTF-8');
        $v = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $v) ?: $v;
        $v = preg_replace('/[^a-z0-9 ]+/u', '', $v);
        $v = preg_replace('/\s+/', ' ', $v);
        return trim($v);
    }

    private function normalizeServiceArray($value): array
    {
        if (!$value) return [];

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            } else {
                $value = [$value];
            }
        }

        if (!is_array($value)) return [];

        return array_values(array_unique(array_map(
            fn($s) => $this->normalizeServiceLabel((string)$s),
            $value
        )));
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

        // Allow admins (and DG) to access any telegramme for replying
        if (
            !$prefillTelegramme &&
            auth()->check() &&
            $this->isAdmin(auth()->user())
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
    // NOTE: l'accusé n'est plus passé à 'traité' ici —
    // seul l'enregistrement d'une réponse finale doit le faire.

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
            if (!$draft && auth()->user() && $this->isAdmin(auth()->user())) {
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
     * Supprime une réponse finale (et ses annexes).
     */
    public function destroyReponseFinale($id)
    {
        $user = auth()->user();

        $reponseFinale = ReponseFinale::with('annexes')->findOrFail($id);

        // Autorisation : admin/DG ou propriétaire de la réponse finale
        if (!$user || (!$this->isAdmin($user) && $reponseFinale->user_id !== $user->id)) {
            return redirect()->route('reponses.index')->with('error', 'Vous n\'avez pas l\'autorisation de supprimer cette réponse finale.');
        }

        // Supprimer fichiers annexes
        if (!empty($reponseFinale->annexes) && is_iterable($reponseFinale->annexes)) {
            foreach ($reponseFinale->annexes as $annexe) {
                if (!empty($annexe->file_path) && Storage::disk('public')->exists($annexe->file_path)) {
                    Storage::disk('public')->delete($annexe->file_path);
                }
            }
        }

        // Supprimer les annexes en base
        Annexe::where('reponse_finale_id', $reponseFinale->id)->delete();

        // Supprimer la réponse finale
        $reponseFinale->delete();

        return redirect()->route('reponses.index')->with('success', 'Réponse finale supprimée avec succès.');
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

    private function formatDelaiTelegramme(\Carbon\Carbon $createdAt): array
{
    $deadline = $createdAt->copy()->addWeek(); // délai 7 jours
    $now = now();

    $hoursRemaining = $now->greaterThan($deadline)
        ? 0
        : $now->diffInHours($deadline);

    $dateLimite = mb_strtoupper(
        $deadline->translatedFormat('d M Y'),
        'UTF-8'
    );

    return [
        'isLate' => $now->greaterThan($deadline),
        'delaiFormatted' => sprintf('%02dH LIMITE %s', $hoursRemaining, $dateLimite),
    ];
}


    /**
     * Normalise un label de service pour comparaison: trim, lowercase, translit accents -> ASCII,
     * collapse espaces et retirer caractères non-alphanumériques.
     */
    

    /**
     * Retourne un tableau normalisé de labels de services à partir d'une chaîne JSON/CSV/array.
     */
    
}