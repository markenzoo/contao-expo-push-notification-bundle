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

$GLOBALS['TL_DCA']['tl_expo_push_notification'] = [
    'config' => [
        'dataContainer' => 'Table',
        'enableVersioning' => true,
        'markAsCopy' => 'title',
        'sql' => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
        'onload_callback' => [
            ['tl_expo_push_notification', 'checkPermission'],
        ],
        'onsubmit_callback' => [
            ['tl_expo_push_notification', 'storeDateAdded'],
        ],
    ],
    'list' => [
        'sorting' => [
            'mode' => 2,
            'fields' => ['dateAdded'],
            'panelLayout' => 'filter;sort,search,limit',
        ],
        'label' => [
            'fields' => ['title', 'dateAdded', 'dateSent'],
            'showColumns' => true,
        ],
        'global_operations' => [
            'all' => [
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
            ],
        ],
        'operations' => [
            'edit' => [
                'href' => 'act=edit',
                'icon' => 'edit.svg',
            ],
            'copy' => [
                'href' => 'act=copy',
                'icon' => 'copy.svg',
                'attributes' => 'onclick="Backend.getScrollOffset()"',
            ],
            'delete' => [
                'href' => 'act=delete',
                'icon' => 'delete.svg',
                'attributes' => 'onclick="if(!confirm(\''.$GLOBALS['TL_LANG']['MSC']['deleteConfirm'].'\'))return false;Backend.getScrollOffset()"',
            ],
            'show' => [
                'href' => 'act=show',
                'icon' => 'show.svg',
            ],
            'send' => [
                'href' => 'key=send',
                'icon' => 'bundles/contaonewsletter/send.svg',
            ],
        ],
    ],
    'palettes' => [
        'default' => '{title_legend},title,message;{data_legend},data;{advanced_legend:hide},priority,expiration;{ios_legend:hide},subtitle,sound,badge;{android_legend:hide},channel',
    ],
    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],
        'pid' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'ptable' => [
            'sql' => "varchar(64) NOT NULL default ''",
        ],
        'sorting' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'dateAdded' => [
            'label' => &$GLOBALS['TL_LANG']['MSC']['dateAdded'],
            'default' => time(),
            'sorting' => true,
            'flag' => 6,
            'eval' => ['rgxp' => 'datim', 'doNotCopy' => true],
            'sql' => 'int(10) unsigned NOT NULL default 0',
        ],
        'dateSent' => [
            'label' => &$GLOBALS['TL_LANG']['MSC']['dateSent'],
            'sorting' => true,
            'flag' => 6,
            'eval' => ['rgxp' => 'datim', 'doNotCopy' => true],
            'sql' => 'int(10) unsigned NULL',
        ],
        'title' => [
            'label' => &$GLOBALS['TL_LANG']['tl_expo_push_notification']['title'],
            'exclude' => true,
            'sorting' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'message' => [
            'label' => &$GLOBALS['TL_LANG']['tl_expo_push_notification']['message'],
            'exclude' => true,
            'sorting' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'data' => [
            'exclude' => true,
            'inputType' => 'picker',
            'eval' => ['mandatory' => false, 'tl_class' => 'clr'],
            'sql' => 'int(10) unsigned NOT NULL default 0',
            'relation' => ['type' => 'hasOne', 'load' => 'lazy', 'table' => 'tl_content'],
        ],
        'priority' => [
            'label' => &$GLOBALS['TL_LANG']['tl_expo_push_notification']['priority'],
            'exclude' => true,
            'filter' => true,
            'sorting' => true,
            'default' => 'default',
            'inputType' => 'select',
            'options' => $GLOBALS['TL_LANG']['tl_expo_push_notification']['priority']['options'],
            'eval' => ['tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'expiration' => [
            'exclude' => true,
            'label' => &$GLOBALS['TL_LANG']['tl_expo_push_notification']['expiration'],
            'inputType' => 'text',
            'eval' => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql' => "varchar(10) NOT NULL default ''",
        ],
        'subtitle' => [
            'label' => &$GLOBALS['TL_LANG']['tl_expo_push_notification']['subtitle'],
            'exclude' => true,
            'sorting' => true,
            'inputType' => 'text',
            'eval' => ['maxlength' => 255, 'tl_class' => 'w50 clr'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'sound' => [
            'label' => &$GLOBALS['TL_LANG']['tl_expo_push_notification']['sound'],
            'default' => 0,
            'exclude' => true,
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w50 m12'],
            'sql' => "char(1) NOT NULL default ''",
        ],
        'badge' => [
            'label' => &$GLOBALS['TL_LANG']['tl_expo_push_notification']['badge'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['maxlength' => 10, 'rgxp' => 'natural', 'tl_class' => 'w50 clr'],
            'sql' => 'int(10) unsigned NULL',
        ],
        'channel' => [
            'label' => &$GLOBALS['TL_LANG']['tl_expo_push_notification']['channel'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['maxlength' => 10, 'rgxp' => 'natural', 'tl_class' => 'w50'],
            'sql' => 'int(10) unsigned NULL',
        ],
    ],
];

class tl_expo_push_notification extends Contao\Backend
{
    /**
     * Import the back end user object.
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('Contao\BackendUser', 'User');
    }

    /**
     * Check permissions to edit the table.
     *
     * @throws Contao\CoreBundle\Exception\AccessDeniedException
     */
    public function checkPermission(): void
    {
        /*
         * @psalm-suppress UndefinedMagicPropertyFetch
         */
        if ($this->User->isAdmin) {
            return;
        }

        // Check permissions to add push notifications
        if (!$this->User->hasAccess('create', 'expo_push_notification')) {
            $GLOBALS['TL_DCA']['tl_expo_push_notification']['config']['closed'] = true;
            $GLOBALS['TL_DCA']['tl_expo_push_notification']['config']['notCreatable'] = true;
            $GLOBALS['TL_DCA']['tl_expo_push_notification']['config']['notCopyable'] = true;
        }

        // Check permissions to edit push notifications
        if (!$this->User->hasAccess('edit', 'expo_push_notification')) {
            $GLOBALS['TL_DCA']['tl_expo_push_notification']['config']['notDeletable'] = true;
        }

        // Check permissions to push notifications
        if (!$this->User->hasAccess('delete', 'expo_push_notification')) {
            $GLOBALS['TL_DCA']['tl_expo_push_notification']['config']['notEditable'] = true;
        }

        // Check current action
        switch (Contao\Input::get('act')) {
            case 'select':
                // Allow
                break;

            case 'copy':
            case 'create':
                if (!$this->User->hasAccess('create', 'expo_push_notification')) {
                    throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to create push notifications.');
                }
                break;

            case 'edit':
                if (!$this->User->hasAccess('edit', 'expo_push_notification')) {
                    throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to edit push notifications.');
                }
                break;

            case 'delete':
                if (!$this->User->hasAccess('delete', 'expo_push_notification')) {
                    throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to delete push notifications.');
                }
                break;

            default:
                if (Contao\Input::get('act')) {
                    throw new Contao\CoreBundle\Exception\AccessDeniedException('Not enough permissions to '.Contao\Input::get('act').' calendars.');
                }
                break;
        }
    }

    /**
     * Store the date when the account has been added.
     *
     * @param Contao\DataContainer $dc
     */
    public function storeDateAdded($dc): void
    {
        // Front end call
        if (!$dc instanceof Contao\DataContainer) {
            return;
        }

        // Return if there is no active record (override all)
        if (!$dc->activeRecord || $dc->activeRecord->dateAdded > 0) {
            return;
        }

        $time = time();

        $this->Database->prepare('UPDATE tl_expo_push_notification SET dateAdded=? WHERE id=?')
                       ->execute($time, $dc->id)
        ;
    }
}
