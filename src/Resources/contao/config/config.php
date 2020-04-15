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

use Markenzoo\ContaoExpoPushNotificationBundle\Model\ExpoPushNotificationModel;

$GLOBALS['BE_MOD']['app'] = [
    'expo_notifications' => [
        'tables' => ['tl_expo_push_notification'],
        'send' => ['Markenzoo\ContaoExpoPushNotificationBundle\ExpoPushNotifications', 'send'],
    ],
];

// CSS for Backend
if (\defined('TL_MODE') && TL_MODE === 'BE') {
    if (!\is_array($GLOBALS['TL_CSS'])) {
        $GLOBALS['TL_CSS'] = [];
    }
    $GLOBALS['TL_CSS'][] = 'bundles/contaoexpopushnotification/css/backend.css';
}

// register Model class
$GLOBALS['TL_MODELS']['tl_expo_push_notification'] = ExpoPushNotificationModel::class;

// Add permissions
$GLOBALS['TL_PERMISSIONS'][] = 'expo_push_notification';
