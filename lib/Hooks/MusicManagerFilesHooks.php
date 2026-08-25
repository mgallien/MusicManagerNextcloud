<?php
/** lib/Hooks/ElisaFilesHooks.php */
declare(strict_types=1);

// SPDX-FileCopyrightText: Matthieu Gallien <matthieu_gallien@yahoo.fr>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\Elisa\Hooks;

use OCA\Elisa\ElisaDatabaseManager;
use OCP\Files\IRootFolder;
use OCP\BackgroundJob\IJobList;
use Psr\Log\LoggerInterface;

class ElisaFilesHooks {
	public function __construct(private IJobList $jobList,
	                            private IRootFolder $rootFolder,
	                            private LoggerInterface $logger,
	                            private ElisaDatabaseManager $dbManager) {
	}

	public function fileCreate($path): void {
		$this->logger->info('file create: ' . $path);

		$allAudioFiles = $this->rootFolder->searchByMime('audio');

		$this->logger->warning('list of music files');
		foreach ($allAudioFiles as $key => $value) {
			$this->logger->warning('music file ' . $key . ' ' . $value->getPath());
		}

		$this->dbManager->getDatabase()->addAudioFiles($allAudioFiles);
		$this->dbManager->syncDatabase();
	}

	public function fileUpdate($path): void {
		$this->logger->info('file update: ' . $path);

		$allAudioFiles = $this->rootFolder->searchByMime('audio');

		$this->logger->warning('list of music files');
		foreach ($allAudioFiles as $key => $value) {
			$this->logger->warning('music file ' . $key . ' ' . $value->getPath());
		}

		$this->dbManager->getDatabase()->addAudioFiles($allAudioFiles);
		$this->dbManager->syncDatabase();
	}

	public function fileDelete($path): void {
		$this->logger->info('file delete: ' . $path);

		$allAudioFiles = $this->rootFolder->searchByMime('audio');

		$this->logger->warning('list of music files');
		foreach ($allAudioFiles as $key => $value) {
			$this->logger->warning('music file ' . $key . ' ' . $value->getPath());
		}

		$this->dbManager->getDatabase()->addAudioFiles($allAudioFiles);
		$this->dbManager->syncDatabase();
	}

	public function fileMove($oldpath, $newpath): void {
		$this->logger->info('file move: from ' . $oldpath . ' to ' . $newpath);

		$allAudioFiles = $this->rootFolder->searchByMime('audio');

		$this->logger->warning('list of music files');
		foreach ($allAudioFiles as $key => $value) {
			$this->logger->warning('music file ' . $key . ' ' . $value->getPath());
		}

		$this->dbManager->getDatabase()->addAudioFiles($allAudioFiles);
		$this->dbManager->syncDatabase();
	}

	public function fileMovePost($oldpath, $newpath): void {
		$this->logger->info('file move post: from ' . $oldpath . ' to ' . $newpath);
	}
}
