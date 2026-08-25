<?php
/** lib/Hooks/ElisaFilesHooksStatic.php */
declare(strict_types=1);

// SPDX-FileCopyrightText: Matthieu Gallien <matthieu_gallien@yahoo.fr>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\Elisa\Hooks;

/**
 * The class to handle the filesystem hooks
 */
class ElisaFilesHooksStatic {
	/**
	 * @return ElisaFilesHooks
	 */
	protected static function getHooks(): ElisaFilesHooks {
		return \OC::$server->query(ElisaFilesHooks::class);
	}

	/**
	 * Handle file create events
	 * @param array $params The hook params
	 */
	public static function fileCreate($params): void {
		self::getHooks()->fileCreate($params['path']);
	}

	/**
	 * Handle file update events
	 * @param array $params The hook params
	 */
	public static function fileUpdate($params): void {
		self::getHooks()->fileUpdate($params['path']);
	}

	/**
	 * Handle file delete events
	 * @param array $params The hook params
	 */
	public static function fileDelete($params): void {
		self::getHooks()->fileDelete($params['path']);
	}

	/**
	 * Handle file move events
	 * @param array $params The hook params
	 */
	public static function fileMove($params): void {
		self::getHooks()->fileMove($params['oldpath'], $params['newpath']);
	}

	/**
	 * Handle file post move events
	 * @param array $params The hook params
	 */
	public static function fileMovePost($params): void {
		self::getHooks()->fileMovePost($params['oldpath'], $params['newpath']);
	}
}
