<?php
/**
 * SPDX-FileCopyrightText: 2026 2M Production Electrique
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\TalkTelephony\AppInfo;

use OCP\AppFramework\App;

class Application extends App {
    public const APP_ID = 'talk_telephony';

    public function __construct() {
        parent::__construct(self::APP_ID);
    }
}
