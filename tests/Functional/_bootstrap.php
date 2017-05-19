<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

use Spryker\Shared\Testify\SystemUnderTestBootstrap;
use Codeception\Util\Autoload;

$bootstrap = SystemUnderTestBootstrap::getInstance();
$bootstrap->bootstrap(SystemUnderTestBootstrap::APPLICATION_ZED);

Autoload::addNamespace();
