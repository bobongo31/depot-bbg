<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bienvenue - Code d'accès requis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #1d3557, #457b9d);
            color: #f1faee;
            font-family: 'Segoe UI', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }
        .card-header {
            background-color: #457b9d;
            color: #f1faee;
            font-weight: bold;
            text-align: center;
        }
        .btn-primary {
            background-color: #1d3557;
            border: none;
        }
        .btn-primary:hover {
            background-color: #16324f;
        }
        .welcome-title {
            text-align: center;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="welcome-title"><i class="fas fa-lock"></i> Bienvenue - Code d'accès requis</h2>

            <div class="card">
                <div class="card-header">🔐 Authentification Sécurisée</div>
                <div class="card-body bg-light text-dark">
                    <form method="POST" action="{{ route('code.verifier') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="code" class="form-label">Veuillez saisir le code d'accès</label>
                            <input type="text" class="form-control" name="code" id="code" required>
                        </div>

                        @if(session('error'))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-check-circle"></i> Valider
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS (optionnel si tu veux les interactions dynamiques) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
