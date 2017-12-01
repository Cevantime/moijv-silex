<?php

namespace DAO;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Entity\User;
/**
 * Description of UserDAO
 *
 * @author Etudiant
 */
class UserDAO extends \SimpleDAO\DAO implements UserProviderInterface
{
    
    public function loadUserByUsername($username)
    {
        // SELECT * FROM user WHERE username = ? LIMIT 1
        // bindValue(1, $username)
        return $this->findOne(array('username = ?'=>$username));
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === '\Entity\User';
    }

}
