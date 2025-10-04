<?php

declare(strict_types=1);

use OCP\Util;

Util::addScript(OCA\MusicManager\AppInfo\Application::APP_ID, OCA\MusicManager\AppInfo\Application::APP_ID . '-main');
Util::addStyle(OCA\MusicManager\AppInfo\Application::APP_ID, OCA\MusicManager\AppInfo\Application::APP_ID . '-main');

?>

<div id="musicmanager"></div>
