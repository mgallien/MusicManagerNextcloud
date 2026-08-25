<?php
/** lib/ElisaDatabase.php */
declare(strict_types=1);

// SPDX-FileCopyrightText: Matthieu Gallien <matthieu_gallien@yahoo.fr>
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace OCA\MusicManager;

use SQLite3;
use OCP\ILogger;
use OCP\Files\Node;

class MusicManagerDatabase extends SQLite3
{
	function __construct(private ILogger $logger,
                         private string $dbName)
	{
		parent::__construct($dbName);

		$this->exec('CREATE TABLE IF NOT EXISTS `Composer`
		             (`ID` INTEGER PRIMARY KEY NOT NULL,
		              `Name` VARCHAR(55) NOT NULL,
		              UNIQUE (`Name`))');

		$this->exec('CREATE TABLE IF NOT EXISTS `Artists`
		             (`ID` INTEGER PRIMARY KEY NOT NULL,
		              `Name` VARCHAR(55) NOT NULL,
		              UNIQUE (`Name`))');

		$this->exec('CREATE TABLE IF NOT EXISTS `DatabaseVersion`
		             (`Version` INTEGER PRIMARY KEY NOT NULL default 0)');

		$this->exec('CREATE TABLE IF NOT EXISTS `DatabaseVersionV15`
		             (`Version` INTEGER PRIMARY KEY NOT NULL)');

		$this->exec('CREATE TABLE IF NOT EXISTS `DiscoverSource`
		             (`ID` INTEGER PRIMARY KEY NOT NULL,
		              `Name` VARCHAR(55) NOT NULL,
		              UNIQUE (`Name`))');

		$this->exec('CREATE TABLE IF NOT EXISTS `Albums`
		             (`ID` INTEGER PRIMARY KEY NOT NULL,
		              `Title` VARCHAR(55) NOT NULL,
		              `ArtistName` VARCHAR(55),
		              `AlbumPath` VARCHAR(255) NOT NULL,
		              `CoverFileName` VARCHAR(255) NOT NULL,
		              UNIQUE (`Title`, `ArtistName`, `AlbumPath`),
		              CONSTRAINT fk_artists FOREIGN KEY (`ArtistName`) REFERENCES `Artists`(`Name`) ON DELETE CASCADE)');

		$this->exec('CREATE TABLE IF NOT EXISTS `Genre`
		             (`ID` INTEGER PRIMARY KEY NOT NULL,
		              `Name` VARCHAR(85) NOT NULL,
		              UNIQUE (`Name`))');

		$this->exec('CREATE TABLE IF NOT EXISTS `Lyricist`
		             (`ID` INTEGER PRIMARY KEY NOT NULL,
		              `Name` VARCHAR(55) NOT NULL,
		              UNIQUE (`Name`))');

		$this->exec('CREATE TABLE IF NOT EXISTS "Radios"
		             (`ID` INTEGER PRIMARY KEY AUTOINCREMENT,
		              `HttpAddress` VARCHAR(255) NOT NULL,
		              `ImageAddress` VARCHAR(255) NOT NULL,
		              `Title` VARCHAR(85) NOT NULL,
		              `Rating` INTEGER NOT NULL DEFAULT 0,
		              `Genre` VARCHAR(55), `Comment` VARCHAR(255),
		              UNIQUE (`HttpAddress`),
		              UNIQUE (`Title`, `HttpAddress`),
		              CONSTRAINT fk_tracks_genre FOREIGN KEY (`Genre`) REFERENCES `Genre`(`Name`))');

		$this->exec('CREATE TABLE IF NOT EXISTS "Tracks"
		             (`ID` INTEGER PRIMARY KEY AUTOINCREMENT,
		              `FileName` VARCHAR(255) NOT NULL,
		              `Priority` INTEGER NOT NULL,
		              `Title` VARCHAR(85) NOT NULL,
		              `ArtistName` VARCHAR(55),
		              `AlbumTitle` VARCHAR(55),
		              `AlbumArtistName` VARCHAR(55),
		              `AlbumPath` VARCHAR(255),
		              `TrackNumber` INTEGER,
		              `DiscNumber` INTEGER,
		              `Duration` INTEGER NOT NULL,
		              `Rating` INTEGER NOT NULL DEFAULT 0,
		              `Genre` VARCHAR(55),
		              `Composer` VARCHAR(55),
		              `Lyricist` VARCHAR(55),
		              `Comment` VARCHAR(255),
		              `Year` INTEGER,
		              `Channels` INTEGER,
		              `BitRate` INTEGER,
		              `SampleRate` INTEGER,
		              `HasEmbeddedCover` BOOLEAN NOT NULL,
		              UNIQUE (`FileName`),
		              UNIQUE (`Priority`, `Title`, `ArtistName`, `AlbumTitle`, `AlbumArtistName`, `AlbumPath`, `TrackNumber`, `DiscNumber`),
		              CONSTRAINT fk_fileName FOREIGN KEY (`FileName`) REFERENCES `TracksData`(`FileName`) ON DELETE CASCADE,
		              CONSTRAINT fk_artist FOREIGN KEY (`ArtistName`) REFERENCES `Artists`(`Name`),
		              CONSTRAINT fk_tracks_composer FOREIGN KEY (`Composer`) REFERENCES `Composer`(`Name`),
		              CONSTRAINT fk_tracks_lyricist FOREIGN KEY (`Lyricist`) REFERENCES `Lyricist`(`Name`),
		              CONSTRAINT fk_tracks_genre FOREIGN KEY (`Genre`) REFERENCES `Genre`(`Name`),
		              CONSTRAINT fk_tracks_album FOREIGN KEY (`AlbumTitle`, `AlbumArtistName`, `AlbumPath`) REFERENCES `Albums`(`Title`, `ArtistName`, `AlbumPath`))');

		$this->exec('CREATE TABLE IF NOT EXISTS "TracksData"
		             (`FileName` VARCHAR(255) NOT NULL,
		              `FileModifiedTime` DATETIME NOT NULL,
		              `ImportDate` INTEGER NOT NULL,
		              `FirstPlayDate` INTEGER,
		              `LastPlayDate` INTEGER,
		              `PlayCounter` INTEGER NOT NULL,
		              PRIMARY KEY (`FileName`))');

		$this->exec('CREATE INDEX IF NOT EXISTS `AlbumArtistNameIndex` ON `Tracks` (`AlbumArtistName`)');

		$this->exec('CREATE INDEX IF NOT EXISTS `ArtistNameAlbumsIndex` ON `Albums` (`ArtistName`)');

		$this->exec('CREATE INDEX IF NOT EXISTS `ArtistNameIndex` ON `Tracks` (`ArtistName`)');

		$this->exec('CREATE INDEX IF NOT EXISTS `TitleAlbumsIndex` ON `Albums` (`Title`)');

		$this->exec('CREATE INDEX IF NOT EXISTS `TracksAlbumIndex` ON `Tracks` (`AlbumTitle`, `AlbumArtistName`, `AlbumPath`)');

		$this->exec('CREATE INDEX IF NOT EXISTS `TracksFileNameIndex` ON `Tracks` (`FileName`)');

		$this->exec('CREATE INDEX IF NOT EXISTS `TracksUniqueData` ON `Tracks` (`Title`, `ArtistName`, `AlbumTitle`, `AlbumArtistName`, `AlbumPath`, `TrackNumber`, `DiscNumber`)');

		$this->exec('CREATE INDEX IF NOT EXISTS `TracksUniqueDataPriority` ON `Tracks` (`Priority`, `Title`, `ArtistName`, `AlbumTitle`, `AlbumArtistName`, `AlbumPath`, `TrackNumber`, `DiscNumber`)');
	}

	public function addAudioFiles($filesList): void {
		$this->logger->warning('list of music files');

		$stmt = $this->prepare("INSERT INTO `TracksData` (`FileName`, `FileModifiedTime`, `ImportDate`, `PlayCounter`) VALUES (:filePath , :modifiedTime , :insertTime, 0)");

		foreach ($filesList as $key => $value) {
			$this->logger->warning('music file ' . $key . ' ' . $value->getPath());

			$stmt->clear();

			$stmt->bindValue(':filePath', $value->getPath(), SQLITE3_TEXT);
			$stmt->bindValue(':modifiedTime', $value->getMtime(), SQLITE3_INTEGER);
			$stmt->bindValue(':insertTime', time(), SQLITE3_INTEGER);

			$stmt->execute();
		}
	}

	public function removeAudioFiles($path): void {
		$this->logger->info('remove ' . $path . 'from db file');
	}

	public function updateAudioFiles($path): void {
		$this->logger->info('update ' . $path . 'in db file');
	}
}
