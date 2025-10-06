<?php

declare(strict_types=1);
/**
 * SPDX-FileCopyrightText: 2025 Matthieu Gallien <matthieu_gallien@yahoo.fr>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */
use Rector\Config\RectorConfig;

return RectorConfig::configure()
	->withPaths([
		__DIR__ . '/lib',
		__DIR__ . '/tests',
	])
	->withPhpSets(php80: true)
	->withPreparedSets(
		deadCode: true,
		codeQuality: true,
		codingStyle: true,
		typeDeclarations: true,
		privatization: true,
		instanceOf: true,
		earlyReturn: true,
		strictBooleans: true,
		carbon: true,
		rectorPreset: true,
		phpunitCodeQuality: true,
		doctrineCodeQuality: true,
		symfonyCodeQuality: true,
		symfonyConfigs: true,
		twig: true,
		phpunit: true,
	);
