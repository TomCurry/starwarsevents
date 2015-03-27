<?php

namespace Yoda\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Yoda\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;


class LoadUsers implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     *
     * @var ContainerInterface
     */
    private $container;
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('darth');
        $user->setEmail('darth@example.com');
        //$user->setPassword($this->encodePassword($user, 'darthpass'));
        $user->setPlainPassword('darthpass');
        $manager->persist($user);
        
        $admin = new User();
        $admin->setUsername('wayne');
        $admin->setEmail('wayne@example.com');
        //$admin->setPassword($this->encodePassword($admin, 'waynepass'));
        $admin->setPlainPassword('waynepass');
        $admin->setRoles(array('ROLE_ADMIN'));
        $manager->persist($admin);
                
        $manager->flush();
    }
    
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }
    
    public function getOrder()
    {
        return 10;
    }

}

