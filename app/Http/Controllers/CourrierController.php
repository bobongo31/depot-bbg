<?php

namespace App\Http\Controllers;

use App\Models\Courrier;
use Illuminate\Http\Request;
use PDF;

class CourrierController extends Controller
{
    /**
     * Afficher le formulaire de réception de courrier.
     */
    public function create()
    {
        return view('courrier.create');
    }

    /**
     * Enregistrer un courrier dans la base de données.
     */
    public function store(Request $request)
    {
        // Validation des champs du formulaire
        $request->validate([
            'date_reception' => 'required|date',
            'expediteur' => 'required|string',
            'objet' => 'required|string',
            'fichier' => 'nullable|file|mimes:pdf,jpeg,png,docx',
            'avis' => 'nullable|string',
        ]);

        // Création d'une nouvelle entrée dans la table courriers
        $courrier = new Courrier();
        $courrier->date_reception = $request->date_reception;
        $courrier->numero_enregistrement = 'C'.now()->timestamp; // Générer un numéro unique
        $courrier->expediteur = $request->expediteur;
        $courrier->objet = $request->objet;
        $courrier->contenu = $request->contenu;
        $courrier->fichier = $request->file('fichier') ? $request->file('fichier')->store('courriers') : null;
        $courrier->status = 'reçu';
        $courrier->annotations = $request->avis;
        $courrier->save();

        // Génération d'un accusé de réception en PDF
        $pdf = PDF::loadView('courrier.accuse', compact('courrier'));
        return $pdf->download('accuse_de_reception_'.$courrier->numero_enregistrement.'.pdf');
    }

    /**
     * Afficher le tableau des courriers reçus avec options de recherche.
     */
    public function index(Request $request)
    {
        $query = Courrier::query();
        
        if ($request->has('search')) {
            $query->where('expediteur', 'like', '%'.$request->search.'%')
                  ->orWhere('numero_enregistrement', 'like', '%'.$request->search.'%')
                  ->orWhere('objet', 'like', '%'.$request->search.'%');
        }

        $courriers = $query->get();
        return view('courrier.index', compact('courriers'));
    }

    /**
     * Afficher le détail d'un courrier reçu.
     */
    public function show($id)
    {
        $courrier = Courrier::findOrFail($id);
        return view('courrier.show', compact('courrier'));
    }

    /**
     * Valider un courrier par le chef de service.
     */
    public function validateCourrier($id)
    {
        $courrier = Courrier::findOrFail($id);
        $courrier->status = 'validé';
        $courrier->validated_by = auth()->id();
        $courrier->validation_date = now();
        $courrier->save();

        return redirect()->route('courrier.index');
    }

    /**
     * Transmettre un courrier validé au directeur général.
     */
    public function transmitToDirector($id)
    {
        $courrier = Courrier::findOrFail($id);
        $courrier->transmis_a_directeur = true;
        $courrier->save();

        // Envoi d'une notification ou d'un email (optionnel)
        // Notification::send($director, new CourrierTransmisNotification($courrier));

        return redirect()->route('courrier.index');
    }

    /**
     * Afficher l'historique des courriers validés pour le chef de service.
     */
    public function validationHistory()
    {
        $courriers = Courrier::where('status', 'validé')->get();
        return view('courrier.validation_history', compact('courriers'));
    }
}
