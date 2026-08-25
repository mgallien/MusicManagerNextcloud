<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2019 Nextcloud GmbH and Nextcloud contributors
 * SPDX-FileCopyrightText: 2025 Matthieu Gallien <matthieu_gallien@yahoo.fr>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace OCA\MusicManager\AppInfo;

use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCA\MusicManager\Hooks\MusicManagerFilesHooksStatic;
use OCP\Util;

class Application extends App implements IBootstrap {
	public const APP_ID = 'musicmanager';

	/** @psalm-suppress PossiblyUnusedMethod */
	public function __construct() {
		parent::__construct(self::APP_ID);
	}

	public function register(IRegistrationContext $context): void {
                Util::connectHook('OC_Filesystem', 'post_create', MusicManagerFilesHooksStatic::class, 'fileCreate');
                Util::connectHook('OC_Filesystem', 'post_update', MusicManagerFilesHooksStatic::class, 'fileUpdate');
                Util::connectHook('OC_Filesystem', 'delete', MusicManagerFilesHooksStatic::class, 'fileDelete');
                Util::connectHook('OC_Filesystem', 'rename', MusicManagerFilesHooksStatic::class, 'fileMove');
                Util::connectHook('OC_Filesystem', 'post_rename', MusicManagerFilesHooksStatic::class, 'fileMovePost');
	}

	public function boot(IBootContext $context): void {
	}
}
