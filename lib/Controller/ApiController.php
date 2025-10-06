<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2025 Matthieu Gallien <matthieu_gallien@yahoo.fr>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
namespace OCA\MusicManager\Controller;

use OCP\AppFramework\Http;
use OCP\AppFramework\Http\Attribute\ApiRoute;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use getID3;
use getid3_exception;
use getid3_lib;

/**
 * @psalm-suppress UnusedClass
 */
class ApiController extends OCSController {
	/**
	 * An API endpoint to get all tracks
	 *
	 * @return DataResponse<Http::STATUS_OK, array{message: string}, array{}>
	 *
	 * 200: Data returned
	 */
	#[NoAdminRequired]
	#[ApiRoute(verb: 'GET', url: '/tracks')]
	public function allTracks(): DataResponse {
		return new DataResponse(
			['message' => 'All the tracks from MusicManager']
		);
	}
}
