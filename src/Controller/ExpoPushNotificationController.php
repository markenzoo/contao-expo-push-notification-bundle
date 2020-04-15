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

namespace Markenzoo\ContaoExpoPushNotificationBundle\Controller;

use Markenzoo\ContaoExpoPushNotificationBundle\Repository\ExpoPushTokenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ExpoNotificationsController provides all routes.
 *
 * @Route("/api", defaults={"_scope" = "frontend", "_token_check" = false})
 */
class ExpoPushNotificationController extends AbstractController
{
    /**
     * Only valid request header content type.
     */
    public const CONTENT_TYPE = 'application/json';

    /**
     * Response message if the post request doesn't provide a header of application/json.
     */
    public const MSG_UNSUPPORTED_CONTENT_TYPE = 'Invalid content type header.';

    /**
     * Response message when provided json body is invalid.
     */
    public const MSG_INVALID_REQUEST_BODY = 'Invalid request body.';

    /**
     * Saves a token provided as json body to the request in the database to use for notifications.
     *
     * @Route ("/notifications", name="expo_push_notifications_post", methods={"POST"})
     *
     * @param Request                 $request    Current request
     * @param ExpoPushTokenRepository $repository
     *
     * @return JsonResponse
     */
    public function notificationsAction(Request $request, ExpoPushTokenRepository $repository): JsonResponse
    {
        $contentType = $request->headers->get('content-type');
        if (self::CONTENT_TYPE !== $contentType) {
            return $this->createJsonResponse(
                self::MSG_UNSUPPORTED_CONTENT_TYPE,
                Response::HTTP_UNSUPPORTED_MEDIA_TYPE
            );
        }

        try {
            $data = json_decode((string) $request->getContent(false), true);
            $token = $data['token']['value'] ?? $data['token'];
            if (null === $token || empty($token)) {
                throw new \Exception();
            }
        } catch (\Throwable $th) {
            return $this->createJsonResponse(
                self::MSG_INVALID_REQUEST_BODY,
                Response::HTTP_BAD_REQUEST
            );
        }

        $repository->saveToken($token);

        return $this->createJsonResponse('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Change subscription of a token provided as json body to the request in the database to use for notifications.
     *
     * @Route ("/notifications", name="expo_push_notifications_subscription_patch", methods={"PUT", "PATCH"})
     *
     * @param Request                 $request    Current request
     * @param ExpoPushTokenRepository $repository
     *
     * @return JsonResponse
     */
    public function notificationsSubscriptionAction(Request $request, ExpoPushTokenRepository $repository): JsonResponse
    {
        $contentType = $request->headers->get('content-type');
        if (self::CONTENT_TYPE !== $contentType) {
            return $this->createJsonResponse(
                self::MSG_UNSUPPORTED_CONTENT_TYPE,
                Response::HTTP_UNSUPPORTED_MEDIA_TYPE
            );
        }

        try {
            $data = json_decode((string) $request->getContent(false), true);
            $token = $data['token']['value'] ?? $data['token'];
            $active = $data['token']['active'] ?? $data['active'];
            if (null === $token || empty($token) || null === $active) {
                throw new \Exception();
            }
        } catch (\Throwable $th) {
            return $this->createJsonResponse(
                self::MSG_INVALID_REQUEST_BODY,
                Response::HTTP_BAD_REQUEST
            );
        }

        if ($active) {
            $repository->subscribeToken($token);
        } else {
            $repository->unsubscribeToken($token);
        }

        return $this->createJsonResponse('', Response::HTTP_NO_CONTENT);
    }

    /**
     * Create a http response with content type set to application/json and message provided as data.
     *
     * @param string $message
     * @param int    $status
     *
     * @return JsonResponse
     */
    protected function createJsonResponse(string $message, int $status = 200): JsonResponse
    {
        $data = \json_encode(['message' => $message]);

        return new JsonResponse($data, $status);
    }
}
