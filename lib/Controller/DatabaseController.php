<?php
/** lib/Controller/DatabaseController.php */
declare(strict_types=1);

// SPDX-FileCopyrightText: Matthieu Gallien <matthieu_gallien@yahoo.fr>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\MusicManager\Controller;

use OCP\AppFramework\Http;
use OCP\AppFramework\Http\Attribute\ApiRoute;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\DownloadResponse;
use OCP\AppFramework\Http\NotFoundResponse;
use OCP\AppFramework\OCSController;

/**
 * @psalm-suppress UnusedClass
 */
class DatabaseController extends OCSController {
	/**
	 * API endpoint to access music database
	 *
	 * @return DataResponse<Http::STATUS_OK, array{message: string}, array{}>
	 *
	 * 200: Data returned
	 */
	#[NoAdminRequired]
	#[ApiRoute(verb: 'GET', url: '/database')]
	public function getDatabase(): DataResponse {
		$appDataRootFolder = $this->appData->getFolder('/');
		if (!$appDataRootFolder->fileExists($this->userSession->getUser()->getUID())) {
			return new NotFoundResponse();
		}
		$userAppDataFolder = $appDataRootFolder->getFolder($this->userSession->getUser()->getUID());
		if (!$userAppDataFolder->fileExists($this->databaseFolder)) {
			return new NotFoundResponse();
		}
		$dataDirPath = $this->config->getSystemValue('datadirectory');
		$instanceId = $this->config->getSystemValue('instanceid');
		$appDataDatabaseFileName = $dataDirPath . '/appdata_' . $instanceId . '/elisa/' . $this->userSession->getUser()->getUID() . $this->databaseFolder . '/elisaDatabase.db';

		return new DownloadResponse($appDataDatabaseFileName);
	}
}
