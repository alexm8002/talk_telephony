<?php
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
