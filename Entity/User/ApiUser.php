<?php
namespace Xima\RestApiBundle\Entity\User;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class ApiUser
 *
 * @author Steve Lenz <steve.lenz@xima.de>, XIMA Media GmbH
 * @package Xima\RestApiBundle\Model\User
 *
 * @ORM\Entity()
 * @ORM\Table(name="xima_rest_api_user")
 * @UniqueEntity("email")
 * @ORM\HasLifecycleCallbacks
 */
class ApiUser implements UserInterface
{

    const ROLE_XIMA_REST_API_READ = 'ROLE_XIMA_REST_API_READ';
    const ROLE_XIMA_REST_API_WRITE = 'ROLE_XIMA_REST_API_WRITE';

    /**
     * @ORM\Column(name="roles", type="json", nullable=true)
     */
    private $roles;

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="username", type="string", length=100, nullable=false)
     * @Assert\NotBlank
     * @Assert\Length(
     *     min = 3,
     *     max = 100
     * )
     */
    private $username;

    /**
     * @ORM\Column(name="email", type="string", length=100, unique=true)
     * @Assert\NotBlank
     * @Assert\Length(
     *     max = 100
     * )
     * @Assert\Email
     */
    private $email;

    /**
     * @ORM\Column(name="api_key", type="string", length=40, nullable=false)
     */
    private $key;
    /**
     * @ORM\Column(name="website", type="string", length=255, nullable=true)
     * @Assert\Length(
     *     max = 255
     * )
     * @Assert\Url()
     */

    private $website;
    /**
     * @ORM\Column(name="comment", type="text", nullable=true)
     */

    private $comment;
    /**
     * @ORM\Column(name="is_active", type="boolean", nullable=true)
     */

    private $isActive;
    /**
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */

    private $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = array();
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
    }

    /**
     * @ORM\PreUpdate()
     */
    public function onUpdate()
    {
        $this->updatedAt = new \DateTime('now');
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set key
     *
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set comment
     *
     * @param string $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set website
     *
     * @param string $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param $role
     * @return $this
     */
    public function addRole($role)
    {
        $role = strtoupper($role);

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * Never use this to check if this user has access to anything!
     *
     * Use the SecurityContext, or an implementation of AccessDecisionManager
     * instead, e.g.
     *
     *         $securityContext->isGranted('ROLE_USER');
     *
     * @param string $role
     *
     * @return boolean
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    /**
     * Returns the user roles
     *
     * @return array The roles
     */
    public function getRoles()
    {
        return array_unique($this->roles);
    }

    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    public function getPassword()
    {
        // TODO: Implement getPassword() method.
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

}
