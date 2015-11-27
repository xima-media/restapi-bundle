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

    /**
     * ApiUserProvider constructor.
     *
     * @param EntityManager $entityManager
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
    public function loadUserByUsername($apiKey)
    {
        $user = $this->em
            ->getRepository('XimaRestApiBundle:User\\ApiUser')
            ->findOneBy(array('key' => $apiKey, 'isActive' => 1));

        if ($user) {
            $user->addRole('ROLE_USER');
            return $user;
        }

        return false;
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