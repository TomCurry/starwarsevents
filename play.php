<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;
use Yoda\EventBundle\Entity\Event;

$loader = require_once __DIR__.'/app/bootstrap.php.cache';
Debug::enable();

require_once __DIR__.'/app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$kernel->boot();

$container = $kernel->getContainer();
$container->enterScope('request');
$container->set('request', $request);

use Doctrine\ORM\EntityManager;

/**
 * @var EntityManager $em
 */
$em = $container->get('doctrine')->getManager();

$wayne = $em->getRepository('UserBundle:User')
    ->findOneByUsernameOrEmail('wayne');

$wayne->setPlainPassword('newpass');
$em->persist($wayne);
$em->flush();
