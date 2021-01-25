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

namespace Markenzoo\ContaoExpoPushNotificationBundle;

use Contao\Backend;
use Contao\ContentModel;
use Contao\DataContainer;
use Contao\Message;
use Contao\System;
use Markenzoo\ContaoExpoPushNotificationBundle\Entity\ExpoPushToken;
use Markenzoo\ContaoExpoPushNotificationBundle\Model\ExpoPushNotificationModel;
use Solvecrew\ExpoNotificationsBundle\Model\NotificationContentModel;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ExpoPushNotifications extends Backend
{
    /**
     * The info to hint invalid expo notifications API endpoint loaded using the configuration.
     */
    public const INVALID_EXPO_ENDPOINT_MESSAGE = 'Invalid Expo API endpoint configured.';
    /**
     * Valid priority values
     * see: https://docs.expo.io/versions/v37.0.0/guides/push-notifications/#message-request-format.
     */
    private const PRIORITIES = ['default', 'normal', 'high'];

    /**
     * Value to specify to play the device's default notification sound on iOS
     * see: https://docs.expo.io/versions/v37.0.0/guides/push-notifications/#message-request-format.
     */
    private const IOS_SOUND = 'default';

    /**
     * Try and Send a given Notification.
     *
     * @param DataContainer $dc
     *
     * @return RedirectResponse
     */
    public function send(DataContainer $dc)
    {
        // get notification object
        $objNotification = ExpoPushNotificationModel::findByPk($dc->id);

        if (null === $objNotification) {
            return $this->getRedirectResponse();  // not object found
        }

        if (null !== $objNotification->dateSent) {
            Message::addError($GLOBALS['TL_LANG']['tl_expo_push_notification']['response']['duplicate']); // object already sent

            return $this->getRedirectResponse();
        }

        // get an array of recipient tokens
        $recipients = $this->importRecipients();

        if (empty($recipients)) {
            Message::addError($GLOBALS['TL_LANG']['tl_expo_push_notification']['response']['no_recipients']); // object already sent

            return $this->getRedirectResponse();
        }

        // Create a NotificationContentModel holding all shared data
        $notificationContentModel = (new NotificationContentModel())->setTitle($objNotification->title)->setBody($objNotification->message);

        // set notification
        $arrData = [];
        if ($objNotification->data) {
            $objElement = ContentModel::findByPk((int) $objNotification->data);
            $arrData = $objElement->row();
        }
        $arrData['title'] = $objNotification->title;
        $arrData['message'] = $objNotification->message;

        if (isset($GLOBALS['TL_HOOKS']['loadPushNotificationData']) && \is_array($GLOBALS['TL_HOOKS']['loadPushNotificationData'])) {
            foreach ($GLOBALS['TL_HOOKS']['loadPushNotificationData'] as $callback) {
                $this->import($callback[0]);
                $arrData = $this->{$callback[0]}->{$callback[1]}($objNotification, $arrData);
            }
        }

        $notificationContentModel->setData($arrData);

        // set notification priority
        if (\in_array($objNotification->priority, self::PRIORITIES, true)) {
            $notificationContentModel->setPriority($objNotification->priority);
        }

        // set expiration timestamp
        if (!empty($objNotification->expiration) && $this->isTimeStamp($objNotification->expiration)) {
            $notificationContentModel->setExpiration((int) $objNotification->expiration);
        }

        // TODO: iOS subtitle currently not supported by Solvecrew\ExpoNotificationsBundle

        // set notification sound on iOS
        if ($objNotification->sound) {
            $notificationContentModel->setSound(self::IOS_SOUND);
        }

        // set notification badge number on iOS
        if ($objNotification->badge) {
            $notificationContentModel->setBadge((int) $objNotification->badge);
        }

        // TODO: android channel ID currently not supported by Solvecrew\ExpoNotificationsBundle

        // get notification managaer from Solvecrew\ExpoNotificationsBundle
        $notificationManager = $this->getNotificationManager();

        // Chunk recipients into arrays of 5 to avoid deconding issue when response is to large
        $recipients = array_chunk($recipients, 5);

        // Deliver the notification in chunks
        foreach ($recipients as $chunk) {
            // generate an array of all notifications to send them in one request
            $notificationContentModels = [];

            foreach ($chunk as $recipient) {
                // get token from ExpoPushToken object
                $token = $recipient->getToken();

                if ($token) {
                    $notificationContentModels[] = ((clone $notificationContentModel)->setTo($token));
                }
            }

            // Send the notifications.
            $httpResponse = $notificationManager->sendNotificationsHttp($notificationContentModels);

            if (isset($GLOBALS['TL_HOOKS']['parsePushNotificationResponse']) && \is_array($GLOBALS['TL_HOOKS']['parsePushNotificationResponse'])) {
                foreach ($GLOBALS['TL_HOOKS']['parsePushNotificationResponse'] as $callback) {
                    $this->import($callback[0]);
                    $arrData = $this->{$callback[0]}->{$callback[1]}($httpResponse);
                }
            }

            foreach ($httpResponse as $response) {
                if ('ok' !== $response['status']) {
                    $message = $response['message'];
                    if (\is_array($response['details']) && !empty($response['details'])) {
                        $message .= ' - '.$response['details'][0];
                    }
                    Message::addInfo($message);
                }
            }
        }

        $time = time();

        $this->Database->prepare('UPDATE tl_expo_push_notification SET dateSent=? WHERE id=?')
                       ->execute($time, $dc->id)
        ;

        Message::addConfirmation($GLOBALS['TL_LANG']['tl_expo_push_notification']['response']['success']);

        return $this->getRedirectResponse();
    }

    /**
     * Returns a redirect response to reload the page.
     *
     * @throws \RuntimeException
     */
    private function getRedirectResponse(): RedirectResponse
    {
        $request = System::getContainer()->get('request_stack')->getCurrentRequest();

        if (null === $request) {
            throw new \RuntimeException('The request stack did not contain a request');
        }

        // return new RedirectResponse($request->getRequestUri()'/contao?do=expo_notifications');
        return new RedirectResponse('/contao?do=expo_notifications');
    }

    /**
     * Returns an array of all ExpoPushToken objects stored in the database which have active subscriptions.
     *
     * @return array
     */
    private function importRecipients(): array
    {
        // Get entity_manager
        $entityManager = $this->getDoctrine();

        // Get entity repository
        $repository = $entityManager->getRepository(ExpoPushToken::class);

        // Get an array of ExpoPushToken objects
        return $repository->findBy(['active' => true]) ?? [];
    }

    /**
     * Checks if the specified string is a valid unix timestamp.
     *
     * @param string $string
     *
     * @return bool
     */
    private function isTimeStamp($string): bool
    {
        try {
            new \DateTime('@'.$string);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    private function getDoctrine(): ?object
    {
        return System::getContainer()->get('doctrine.orm.default_entity_manager');
    }

    private function getNotificationManager(): ?object
    {
        return System::getContainer()->get('sc_expo_notifications.notification_manager');
    }
}
