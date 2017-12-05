<?php

namespace DAO;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * Description of UserDAO
 *
 * @author Etudiant
 */
class AdminDAO extends UserDAO
{

    protected $tableName = 'user';
    protected $entityClassName = '\Entity\User';

    public function loadUserByUsername($username)
    {
        // SELECT * FROM user WHERE username = ? LIMIT 1
        // bindValue(1, $username)
        $user = $this->findOne(array(
            'username = ?' => $username,
            'role LIKE ?' => '%ROLE_ADMIN%'
        ));

        if (!$user) {
            throw new UsernameNotFoundException("User with username $username does not exist");
        }

        return $user;
    }

}
