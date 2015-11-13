<?php
namespace Xima\RestApiBundle\Security;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Xima\RestApiBundle\Entity\User\ApiUser;

class ApiUserProvider implements UserProviderInterface
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    private $apiUser;

    /**
     * ApiUserProvider constructor.
     *
     * @param $apiKey
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param $apiKey
     * @return bool
     */
    public function getUsernameForApiKey($apiKey)
    {
        $this->apiUser = $this->em
            ->getRepository('XimaRestApiBundle:User\\ApiUser')
            ->findOneBy(array('key' => $apiKey, 'isActive' => 1));

        if ($this->apiUser){
            return $this->apiUser->getUsername();
        }

        return false;
    }

    /**
     * @param string $username
     * @return \Symfony\Component\Security\Core\User\User
     */
    public function loadUserByUsername($username)
    {
        return $this->apiUser;
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     */
    public function refreshUser(UserInterface $user)
    {
        // this is used for storing authentication in the session
        // but in this example, the token is sent in each request,
        // so authentication can be stateless. Throwing this exception
        // is proper to make things stateless
        throw new UnsupportedUserException();
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return 'Symfony\Component\Security\Core\User\User' === $class;
    }
}