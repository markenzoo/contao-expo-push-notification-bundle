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

namespace Markenzoo\ContaoExpoPushNotificationBundle\Tests;

use Markenzoo\ContaoExpoPushNotificationBundle\ContaoExpoPushNotificationBundle;
use PHPUnit\Framework\TestCase;

class ContaoExpoPushNotificationBundleTest extends TestCase
{
    public function testCanBeInstantiated(): void
    {
        $bundle = new ContaoExpoPushNotificationBundle();

        $this->assertInstanceOf('Markenzoo\ContaoExpoPushNotificationBundle\ContaoExpoPushNotificationBundle', $bundle);
    }
}
