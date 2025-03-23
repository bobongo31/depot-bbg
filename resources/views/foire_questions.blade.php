@extends('layouts.app')

@section('content')
<div class="container">
    <h2><i class="fa-solid fa-question-circle"></i> Foire Aux Questions (FAQ)</h2>

    <div class="accordion" id="faqAccordion">
        <!-- Question 1 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Qu'est-ce que la messagerie interne de l'entreprise ?
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    La messagerie interne est une plateforme qui permet aux employés de communiquer rapidement et de manière sécurisée via des messages texte.
                </div>
            </div>
        </div>

        <!-- Question 2 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Comment accéder à la messagerie ?
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Pour accéder à la messagerie, connectez-vous à votre compte sur notre application interne, puis naviguez vers la section "Messagerie".
                </div>
            </div>
        </div>

        <!-- Question 3 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    Est-ce que mes messages sont sécurisés ?
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Oui, tous les messages envoyés via notre plateforme sont cryptés pour garantir la sécurité et la confidentialité.
                </div>
            </div>
        </div>

        <!-- Question 4 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFour">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                    Comment envoyer un message avec une pièce jointe ?
                </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Vous pouvez ajouter une pièce jointe en cliquant sur l'icône de trombone située à côté de la zone de texte, puis sélectionnez le fichier à envoyer.
                </div>
            </div>
        </div>

        <!-- Question 5 -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFive">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                    Comment changer mon mot de passe ?
                </button>
            </h2>
            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Pour changer votre mot de passe, allez dans les paramètres de votre compte, cliquez sur "Sécurité", puis suivez les instructions pour modifier votre mot de passe.
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
