<?php
namespace Xima\RestApiBundle\Security;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class ApiKeyAuthenticator implements SimplePreAuthenticatorInterface
{
    const PARAMETER_NAME = 'api_key';

    protected $userProvider;

    /**
     * @var array $requiredRoles
     */
    protected $requiredRoles = array();

    /**
     * ApiKeyAuthenticator constructor.
     * @param $userProvider
     * @param array $requiredRoles
     */
    public function __construct($userProvider, $requiredRoles = array())
    {
        $this->userProvider = $userProvider;
        $this->requiredRoles = $requiredRoles;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $providerKey
     * @return \Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken
     */
    public function createToken(Request $request, $providerKey)
    {
        if (!$request->query->has(self::PARAMETER_NAME)) {
            throw new BadCredentialsException('No API key set.');
        }

        return new PreAuthenticatedToken(
            'anon.',
            $request->query->get(self::PARAMETER_NAME),
            $providerKey
        );
    }

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @param \Symfony\Component\Security\Core\User\UserProviderInterface $userProvider
     * @param $providerKey
     * @return \Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken
     */
    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        $user = $this->userProvider->loadUserByUsername($token->getCredentials());

        /**
         * check if api key exists
         */
        if (!$user) {
            throw new Exception(
                sprintf('Invalid API Key.')
            );
        }

        /**
         * check if api user has required roles, if any
         */
        foreach ($this->requiredRoles as $requiredRole) {
            if (!$user->hasRole($requiredRole)) {
                throw new Exception(
                    sprintf('API Key does not have sufficient rights.')
                );
            }
        }

        return new PreAuthenticatedToken(
            $user,
            $token->getCredentials(),
            $providerKey,
            $user->getRoles()
        );
    }

    /**
     * @param \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @param $providerKey
     * @return bool
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }
}