<?php
namespace Xima\RestApiBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class ApiKeyUserProvider implements UserProviderInterface
{
    private $apiKey;
    
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }
    
    public function loadUserByUsername($apiKey)
    {
        if ($apiKey === $this->apiKey) {
            return new User(
                $apiKey,
                null,
                array('ROLE_USER')
            );
        }

        return false;
    }

    public function refreshUser(UserInterface $user)
    {
        // this is used for storing authentication in the session
        // but in this example, the token is sent in each request,
        // so authentication can be stateless. Throwing this exception
        // is proper to make things stateless
        throw new UnsupportedUserException();
    }

    public function supportsClass($class)
    {
        return 'Symfony\Component\Security\Core\User\User' === $class;
    }
}