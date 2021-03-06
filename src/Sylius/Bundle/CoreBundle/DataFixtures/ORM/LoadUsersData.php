<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\CoreBundle\Entity\User;

/**
 * User fixtures.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class LoadUsersData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        //administrator
        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('admin@pigalle.com.ar');
        $user->setPlainPassword('123456');
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_SYLIUS_ADMIN'));

        $manager->persist($user);
        $manager->flush();

        $this->setReference('User-Administrator', $user);

        //mayorista
        $user = new User();
        $user->setUsername('mayorista');
        $user->setEmail('mayorista@pigalle.com.ar');
        $user->setPlainPassword('123456');
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_USER_MAYORISTA'));
        
        $manager->persist($user);
        $manager->flush();
        
        $this->setReference('User-Administrator', $user);
        
        //Normal users
        for ($i = 1; $i <= 15; $i++) {
            $user = new User();

            $username = $this->faker->username;

            $user->setUsername($username);
            $user->setEmail($username.'@example.com');
            $user->setPlainPassword($username);
            $user->setEnabled($this->faker->boolean());

            $manager->persist($user);

            $this->setReference('Sylius.User-'.$i, $user);
        }

        $manager->flush();

    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
