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

$GLOBALS['TL_DCA']['tl_user']['palettes']['extend'] = str_replace('fop;', 'fop;{expo_push_notifications_legend},expo_push_notification;', $GLOBALS['TL_DCA']['tl_user']['palettes']['extend']);
$GLOBALS['TL_DCA']['tl_user']['palettes']['custom'] = str_replace('fop;', 'fop;{expo_push_notifications_legend},expo_push_notification;', $GLOBALS['TL_DCA']['tl_user']['palettes']['custom']);

$GLOBALS['TL_DCA']['tl_user']['fields']['expo_push_notification'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_user']['expo_push_notification'],
    'inputType' => 'checkbox',
    'options' => [
        'edit',
        'create',
        'delete',
    ],
    'eval' => [
        'multiple' => true,
    ],
    'reference' => &$GLOBALS['TL_LANG']['tl_user']['reference'],
    'exclude' => true,
    'sql' => 'blob NULL',
];
