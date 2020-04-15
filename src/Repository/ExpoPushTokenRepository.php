<?php

declare(strict_types=1);

/*
 * This file is part of markenzoo/contao-expo-push-notification-bundle.
 *
 * Copyright (c) 2020 markenzoo eG
 *
 * @package   markenzoo/contao-expo-push-notification-bundle
 * @author    Felix Kästner <kaestner@markenzoo.de>
 * @copyright 2020 markenzoo eG
 * @license   https://github.com/markenzoo/contao-expo-push-notification-bundle/blob/master/LICENSE MIT License
 */

namespace Markenzoo\ContaoExpoPushNotificationBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Markenzoo\ContaoExpoPushNotificationBundle\Entity\ExpoPushToken;

class ExpoPushTokenRepository extends ServiceEntityRepository
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExpoPushToken::class);
    }

    /**
     * Finds a single entity by a given string token.
     *
     * @param string $token
     * @param array  $orderBy
     *
     * @return object|null the entity instance or NULL if the entity can not be found
     */
    public function findOneByToken(string $token, array $orderBy = [])
    {
        return $this->findOneBy(['token' => $token], $orderBy);
    }

    /**
     * Persists a string token in the database.
     *
     * @param string $token
     *
     * @return ExpoPushToken
     */
    public function saveToken(string $token): ExpoPushToken
    {
        $objToken = $this->findOneByToken($token);

        if (null === $objToken) {
            $objToken = new ExpoPushToken();
            $objToken->setToken($token);
            $this->_em->persist($objToken);
            $this->_em->flush();
        }

        return $objToken;
    }

    /**
     * Subscribe a given token for notifications.
     *
     * @param string $token
     *
     * @return ExpoPushToken
     */
    public function subscribeToken(string $token): ExpoPushToken
    {
        $objToken = $this->saveToken($token);
        $objToken->setActive(true);
        $this->_em->persist($objToken);
        $this->_em->flush();

        return $objToken;
    }

    /**
     * Unsubscribe a given token for notifications.
     *
     * @param string $token
     *
     * @return ExpoPushToken
     */
    public function unsubscribeToken(string $token): ExpoPushToken
    {
        $objToken = $this->saveToken($token);
        $objToken->setActive(false);
        $this->_em->persist($objToken);
        $this->_em->flush();

        return $objToken;
    }
}
