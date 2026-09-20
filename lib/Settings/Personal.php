<?php
/**
 * SPDX-FileCopyrightText: 2026 2M Production Electrique
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\TalkTelephony\Settings;

use OCP\AppFramework\Http\TemplateResponse;
use OCP\Settings\ISettings;

class Personal implements ISettings {
    public function getForm(): TemplateResponse {
        return new TemplateResponse('talk_telephony', 'personal');
    }
    public function getSection(): string { return 'additional'; }
    public function getPriority(): int { return 50; }
}
