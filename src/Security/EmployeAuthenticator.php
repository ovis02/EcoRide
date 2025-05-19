<?php

namespace App\Security;

use App\Entity\Employe; // Assure-toi que le chemin vers ton entité Employé est correct
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class EmployeAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait; // Pour gérer la redirection vers la page demandée avant la connexion

    public const LOGIN_ROUTE = 'employe_login'; // Nom de la route pour la page de connexion des employés

    private UrlGeneratorInterface $urlGenerator;
    private EntityManagerInterface $entityManager;

    public function __construct(UrlGeneratorInterface $urlGenerator, EntityManagerInterface $entityManager)
    {
        $this->urlGenerator = $urlGenerator;
        $this->entityManager = $entityManager;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', ''); // Récupère l'email depuis la requête
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email); // Stocke l'email en session

        return new Passport(
            new UserBadge($email, function ($userIdentifier) {
                // Charge l'employé depuis la base de données
                return $this->entityManager->getRepository(Employe::class)->findOneBy(['email' => $userIdentifier]);
            }),
            new PasswordCredentials($request->request->get('password', '')), // Récupère le mot de passe
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token', '')), // Gestion du token CSRF
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            // Si l'utilisateur avait demandé une page avant de se connecter, on le redirige vers cette page
            return new RedirectResponse($targetPath);
        }

        // Sinon, on le redirige vers le dashboard des employés (la page que tu souhaites après la connexion)
        return new RedirectResponse($this->urlGenerator->generate('employe_dashboard')); // Utilise le nom de la route ici
    }

    protected function getLoginUrl(Request $request): string
    {
        // Retourne l'URL de la page de connexion
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
