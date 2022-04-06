<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\PasswordUpgradeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;

class SecurityAuthenticator extends AbstractAuthenticator
{

    public function __construct(protected UserRepository $userRepository, protected UserPasswordHasherInterface $encoder) {}

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'security_login'
            && $request->isMethod('POST');
    }

    public function authenticate(Request $request): PassportInterface
    {

        $username   = $request->get('login')['username'];
        $password   = $request->get('login')['password'];
        $token      = $request->get('login')['_token'];

        return new Passport(
            new UserBadge($username, function($userIdentifier) {
                // Optionally pass a callback to load the User manually
                $user = $this->userRepository->findOneBy(['username' => $userIdentifier]);
                
                if(!$user) {
                    throw new AuthenticationException("Utilisateur introuvable. Veuillez ressayer.");
                }
    
                return $user;
            }),
            new CustomCredentials(function($credentials, User $user) {
                // Check if valid user password
                $isValid = $this->encoder->isPasswordValid($user, $credentials);
                
                if(!$isValid) {
                    throw new AuthenticationException('Mot de passe incorrect.');
                }
                
                return true;
            }, $password),
            [
                // CSRF protection using a "csrf_token" field
                new CsrfTokenBadge('login', $token),

                // Support for upgrading the password hash
                new PasswordUpgradeBadge($password)
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse('/');
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        $request->getSession()->set(Security::LAST_USERNAME, $request->get('login')['username']);
        return new RedirectResponse('/login');
    }

    public function start() {}
}
