{{-- ===================== RÉPONSES ===================== --}}
<h2 class="mb-4">
    <i class="fas fa-list-alt"></i> Liste des réponses
</h2>

@if($reponses->count() > 0)
    @foreach($reponsesGrouped as $date => $reponsesDuJour)
        <div class="mb-5">
            <div class="date-header">
                <i class="fas fa-calendar-day"></i>
                {{ \Carbon\Carbon::parse($date)->translatedFormat('l d F Y') }}
            </div>

            <div class="table-container table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Référence</th>
                            <th>Destinataire</th>
                            <th class="hide-mobile">Expéditeur</th>
                            <th class="hide-mobile">Résumé</th>
                            <th>Heure</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reponsesDuJour as $reponse)
                            <tr class="{{ $reponse->isLate ? 'late-row' : '' }}">
                                <td>{{ $reponse->numero_enregistrement }}</td>
                                <td>{{ $reponse->numero_reference }}</td>

                                <td class="wrap">
                                    @foreach($reponse->services_affiches as $svc)
                                        <span class="badge bg-info text-dark me-1">{{ $svc }}</span>
                                    @endforeach
                                </td>

                                <td class="hide-mobile">{{ $reponse->observation }}</td>
                                <td class="hide-mobile wrap resume">{{ $reponse->commentaires }}</td>
                                <td>{{ optional($reponse->created_at)->format('H:i') }}</td>

                                <td class="status">
                                    @if($reponse->statutLabel === '—')
                                        <span class="badge-status" style="background:#9aa6b2">—</span>
                                    @elseif($reponse->isLate)
                                        <span class="badge-status badge-late">EN RETARD</span>
                                    @else
                                        <span class="badge-status badge-on-time">DANS LE DÉLAI</span>
                                    @endif
                                </td>

                                <td class="actions-cell">
                                    <a class="btn btn-outline-info btn-sm"
                                       href="{{ route('reponse.show', $reponse->id) }}">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if($reponse->telegramme_id)
                                        <a class="btn btn-outline-primary btn-sm"
                                           href="{{ route('reponses.create', ['telegramme_id' => $reponse->telegramme_id]) }}">
                                            <i class="fas fa-reply"></i>
                                        </a>
                                    @endif

                                    @if(auth()->user() && (
                                        auth()->user()->role === 'admin' ||
                                        auth()->user()->role === 'DG' ||
                                        (method_exists(auth()->user(),'hasRole') && (
                                            auth()->user()->hasRole('admin') ||
                                            auth()->user()->hasRole('DG')
                                        ))
                                    ))
                                        <a class="btn btn-outline-success btn-sm"
                                           href="{{ action([App\Http\Controllers\ReponseController::class, 'formAjouterReponseFinale'], ['reponseId' => $reponse->id]) }}"
                                           title="Ajouter réponse finale">
                                            <i class="fas fa-file-signature"></i>
                                        </a>
                                    @endif

                                    <button class="btn btn-outline-danger btn-sm ajax-delete-btn"
                                            data-url="{{ route('reponses.destroy', $reponse->id) }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

    @if($reponses->hasPages())
        <div class="mt-3 d-flex justify-content-center">
            {{ $reponses->appends(request()->except('reponses_page'))->links() }}
        </div>
    @endif
@else
    <div class="alert alert-light border">
        Aucune réponse trouvée.
    </div>
@endif


{{-- ===================== TÉLÉGRAMMES ===================== --}}
<div class="telegrames">
    <h3 class="mt-4 mb-3">
        <i class="fas fa-paper-plane"></i>
        Télégrammes en attente ({{ $telegrammesEnAttente->total() }})
    </h3>

    <div class="table-container table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Réf</th>
                    <th>Service</th>
                    <th class="hide-mobile">Date</th>
                    <th class="hide-mobile">Résumé</th>
                    <th class="hide-mobile">Expéditeur</th>
                    <th>Délai</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($telegrammesEnAttente as $telegramme)
                    <tr class="{{ $telegramme->isLate ? 'table-danger' : 'table-success' }}">
                        <td>{{ $telegramme->numero_enregistrement }}</td>
                        <td>{{ $telegramme->numero_reference }}</td>

                        <td class="wrap">
                            @foreach($telegramme->services_affiches as $svc)
                                <span class="badge bg-info text-dark me-1">{{ $svc }}</span>
                            @endforeach
                        </td>

                        <td class="hide-mobile">
                            {{ optional($telegramme->created_at)->translatedFormat('d/m/Y H:i') }}
                        </td>

                        <td class="hide-mobile wrap resume">{{ $telegramme->commentaires }}</td>
                        <td class="hide-mobile">{{ $telegramme->observation }}</td>

                        <td class="time status">
                            @if($telegramme->statutLabel === 'EN RETARD')
                                <span class="badge badge-late">
                                    EN RETARD
                                    <br>
                                    <small>LIMITE {{ $telegramme->dateLimite }}</small>
                                </span>
                            @elseif($telegramme->statutLabel === 'EN ATTENTE')
                                <span class="badge bg-warning text-dark">
                                    EN ATTENTE
                                    <br>
                                    <small>LIMITE {{ $telegramme->dateLimite }}</small>
                                </span>
                            @elseif($telegramme->statutLabel === 'EN COURS')
                                <span class="badge bg-info">
                                    EN COURS
                                    <br>
                                    <small>LIMITE {{ $telegramme->dateLimite }}</small>
                                </span>
                            @else
                                <span class="badge badge-on-time">
                                    DANS LE DÉLAI
                                    <br>
                                    <small>LIMITE {{ $telegramme->dateLimite }}</small>
                                </span>
                            @endif
                        </td>

                        <td class="actions-cell">
                            <a class="btn btn-outline-info btn-sm"
                               href="{{ route('telegramme.show', $telegramme->id) }}">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a class="btn btn-outline-primary btn-sm"
                               href="{{ route('reponses.create', ['telegramme_id' => $telegramme->id]) }}">
                                <i class="fas fa-reply"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Aucun télégramme</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($telegrammesEnAttente->hasPages())
        <div class="mt-3 d-flex justify-content-center">
            {{ $telegrammesEnAttente->appends(request()->except('telegrammes_page'))->links() }}
        </div>
    @endif
</div>