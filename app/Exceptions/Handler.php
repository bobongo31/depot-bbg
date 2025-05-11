<?php
namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Actions lors de l'enregistrement des erreurs
        });
    }

    public function render($request, Throwable $exception)
    {
        // Définir l'IP du développeur qui verra les erreurs détaillées
        $developerIp = env('DEVELOPER_IP', '172.31.80.1');  // Valeur par défaut au cas où

        // Log de débogage : Afficher l'IP utilisée
        \Log::debug("Request IP: " . $request->ip());

        // Vérification si l'IP du client est celle du développeur
        if ($request->getClientIp() === $developerIp) {
            // Activer les erreurs détaillées uniquement pour l'IP du développeur
            config(['app.debug' => true]);
        } else {
            // Désactiver les erreurs détaillées pour les autres utilisateurs
            config(['app.debug' => false]);

            // Gérer les erreurs HTTP (503, 403, etc.)
            if ($exception instanceof HttpExceptionInterface) {
                $status = $exception->getStatusCode();
                if (view()->exists("errors.{$status}")) {
                    return response()->view("errors.{$status}", [], $status);
                }
            }

            // Retourner une erreur générique 500 si aucune vue spécifique n'est trouvée
            return response()->view('errors.500', [], 500);
        }

        // Par défaut, gérer les exceptions comme Laravel le ferait normalement
        return parent::render($request, $exception);
    }
}
