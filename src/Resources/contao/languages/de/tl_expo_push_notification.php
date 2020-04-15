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

$GLOBALS['TL_LANG']['tl_expo_push_notification']['send'] = ['Push Benachrichtigung versenden', 'Push Benachrichtigung ID %s versenden'];

/*
 * Fields
 */
$GLOBALS['TL_LANG']['tl_expo_push_notification']['title_legend'] = 'Titel - Einstellungen';
$GLOBALS['TL_LANG']['tl_expo_push_notification']['title'] = ['Titel', 'Legen Sie einen Titel für die Benachrichtigung fest'];
$GLOBALS['TL_LANG']['tl_expo_push_notification']['message'] = ['Nachricht', 'Legen Sie eine Nachricht für die Benachrichtigung fest'];

$GLOBALS['TL_LANG']['tl_expo_push_notification']['data_legend'] = 'Daten - Einstellungen';
$GLOBALS['TL_LANG']['tl_expo_push_notification']['data'] = ['Bezogenes Inhaltselement', 'Bitte wählen Sie das Inhaltselement aus, auf das sich die Benachrichtigung bezieht.'];

$GLOBALS['TL_LANG']['tl_expo_push_notification']['advanced_legend'] = 'Erweiterte - Einstellungen';
$GLOBALS['TL_LANG']['tl_expo_push_notification']['priority'] = ['Priorität', 'Legen Sie die Priorität der Nachricht fest. (Optional)'];
$GLOBALS['TL_LANG']['tl_expo_push_notification']['expiration'] = ['Endzeit', 'Legen Sie den Zeitraum fest, in der die Benachrichtigung versendet wird. (Optional)'];

$GLOBALS['TL_LANG']['tl_expo_push_notification']['priority']['options']['default'] = 'Standard';
$GLOBALS['TL_LANG']['tl_expo_push_notification']['priority']['options']['normal'] = 'Normal';
$GLOBALS['TL_LANG']['tl_expo_push_notification']['priority']['options']['high'] = 'Hoch';

$GLOBALS['TL_LANG']['tl_expo_push_notification']['ios_legend'] = 'iOS - Einstellungen';
$GLOBALS['TL_LANG']['tl_expo_push_notification']['subtitle'] = ['Untertitel', 'Legen Sie den Untertitel für die Benachrichtigung fest'];
$GLOBALS['TL_LANG']['tl_expo_push_notification']['sound'] = ['Ton aktivieren', 'Aktivieren Sie das Abspielen eines Tons beim Erhalt der Push Benachrichtigung.'];
$GLOBALS['TL_LANG']['tl_expo_push_notification']['badge'] = ['Nummer anzeigen', 'Legen Sie eine Zahl fest, die am Icon der App auf dem Startbildschirm erscheint.'];

$GLOBALS['TL_LANG']['tl_expo_push_notification']['android_legend'] = 'Android - Einstellungen';
$GLOBALS['TL_LANG']['tl_expo_push_notification']['channel'] = ['Channel-ID festlegen', 'Legen Sie eine Channel-ID für die Benachrichtigun fest.'];

$GLOBALS['TL_LANG']['tl_expo_push_notification']['response']['success'] = 'Benachrichtigung erfolgreich gesendet';
$GLOBALS['TL_LANG']['tl_expo_push_notification']['response']['duplicate'] = 'Benachrichtigung wurde bereits gesendet. Bitte erstellen Sie eine neue Benachrichtigung zum versenden.';
$GLOBALS['TL_LANG']['tl_expo_push_notification']['response']['no_recipients'] = 'Keine Empfänger vorhanden. Bitte installieren Sie die App um sich für Benachrichtigungen zu registrieren.';
