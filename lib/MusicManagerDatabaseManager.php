<?php
/** lib/MusicManagerDatabaseManager.php */
declare(strict_types=1);

// SPDX-FileCopyrightText: Matthieu Gallien <matthieu_gallien@yahoo.fr>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\MusicManager;

use OCP\Files\IAppData;
use OCP\Files\IRootFolder;
use OCP\Files\Folder;
use OCP\Files\File;
use OCP\Files\SimpleFS\ISimpleFolder;
use OCP\Files\SimpleFS\ISimpleFile;
use OCP\IUserSession;
use OCP\IUser;
use OCP\Files\NotFoundException;
use OCP\IConfig;

class MusicManagerDatabaseManager {
	private string $databaseFolder = '/database';
	private string $appDataDatabaseFileName;

	public function __construct(private IAppData $appData,
								private IRootFolder $rootFolder,
	                            private IUserSession $userSession,
	                            private IConfig $config) {
			$appDataRootFolder = $this->appData->getFolder('/');
			if (!$appDataRootFolder->fileExists($this->userSession->getUser()->getUID())) {
				$appDataRootFolder->newFolder($this->userSession->getUser()->getUID());
			}
			$userAppDataFolder = $appDataRootFolder->getFolder($this->userSession->getUser()->getUID());
			if (!$userAppDataFolder->fileExists($this->databaseFolder)) {
				$userAppDataFolder->newFolder($this->databaseFolder);
			}
			$dataDirPath = $this->config->getSystemValue('datadirectory');
			$instanceId = $this->config->getSystemValue('instanceid');
			$this->appDataDatabaseFileName = $dataDirPath . '/appdata_' . $instanceId . '/elisa/' . $this->userSession->getUser()->getUID() . $this->databaseFolder . '/elisaDatabase.db';
	}

	public function getDatabase(): ElisaDatabase {
		try {
			return new ElisaDatabase($this->logger, $this->appDataDatabaseFileName);
		}
		catch (NotFoundException $ex)
		{
			return new ElisaDatabase($this->logger, '');
		}
	}

	public function syncDatabase() {
			$appDataRootFolder = $this->appData->getFolder('/');
			if (!$appDataRootFolder->fileExists($this->databaseFolder)) {
				return;
			}
			$dbFolder = $appDataRootFolder->getFolder($this->databaseFolder);
			if (!$dbFolder->fileExists('elisaDatabase.db')) {
				return;
			}
			$appDataDbFile = $dbFolder->getFile('elisaDatabase.db');

			$userFolder = $this->rootFolder->getUserFolder($this->userSession->getUser()->getUID());
			$appDataRootFolder = $userFolder->get('/Musique');
			if ($appDataRootFolder->nodeExists('elisaDatabase.db')) {
				$dbFile = $appDataRootFolder->get('elisaDatabase.db');
			}
			else {
				$dbFile = $appDataRootFolder->newFile('elisaDatabase.db');
			}
			$dbFile->putContent($appDataDbFile->getContent());
	}
}
