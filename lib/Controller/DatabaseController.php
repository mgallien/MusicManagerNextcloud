<?php
/** lib/Controller/DatabaseController.php */
declare(strict_types=1);

// SPDX-FileCopyrightText: Matthieu Gallien <matthieu_gallien@yahoo.fr>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\MusicManager\Controller;

use OCP\AppFramework\Http;
use OCP\AppFramework\Http\Attribute\ApiRoute;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\IRequest;
use OCP\Files\IAppData;
use OCP\IUserSession;
use OCP\IConfig;
use OCA\MusicManager\MusicManagerDatabaseManager;

/**
 * @psalm-suppress UnusedClass
 */
class DatabaseController extends OCSController {
    private IAppData $appData;
	private IUserSession $userSession;
	private IConfig $config;
	private MusicManagerDatabaseManager $dbManager;
	private string $databaseFolder = '/database';

    public function __construct(string $appName, IRequest $request, IAppData $appData, IUserSession $userSession, IConfig $config, MusicManagerDatabaseManager $dbManager)
    {
        parent::__construct($appName, $request);
        $this->appData = $appData;
		$this->userSession = $userSession;
		$this->config = $config;
		$this->dbManager = $dbManager;
		
		$this->dbManager->syncDatabase();
    }

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
			return new DataResponse();
		}
		$userAppDataFolder = $appDataRootFolder->getFolder($this->userSession->getUser()->getUID());
		if (!$userAppDataFolder->fileExists($this->databaseFolder)) {
			return new DataResponse();
		}
		$dataDirPath = $this->config->getSystemValue('datadirectory');
		$instanceId = $this->config->getSystemValue('instanceid');
		$appDataDatabaseFileName = $dataDirPath . '/appdata_' . $instanceId . '/elisa/' . $this->userSession->getUser()->getUID() . $this->databaseFolder . '/elisaDatabase.db';

		return new DataResponse($appDataDatabaseFileName);
	}
}
