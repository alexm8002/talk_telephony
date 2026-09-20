<?php
/**
 * SPDX-FileCopyrightText: 2026 2M Production Electrique
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\TalkTelephony\Controller;

use OCA\TalkTelephony\Service\AccountService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\Attribute\NoCSRFRequired;
use OCP\AppFramework\Http\Attribute\PublicPage;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IConfig;
use OCP\IRequest;

class GatewayController extends Controller {
    public function __construct(
        string $appName,
        IRequest $request,
        private AccountService $service,
        private IConfig $config,
    ) {
        parent::__construct($appName, $request);
    }

    #[PublicPage]
    #[NoCSRFRequired]
    public function accounts(): JSONResponse {
        $expectedHash = $this->config->getAppValue('talk_telephony', 'gateway_token_hash', '');
        if ($expectedHash === '') {
            return new JSONResponse(['error' => 'Gateway API non configurée'], 503);
        }
        $auth = $this->request->getHeader('Authorization');
        if (!preg_match('/^Bearer\s+(.+)$/i', $auth, $m)) {
            return new JSONResponse(['error' => 'Unauthorized'], 401);
        }
        $actualHash = hash('sha256', trim($m[1]));
        if (!hash_equals($expectedHash, $actualHash)) {
            return new JSONResponse(['error' => 'Unauthorized'], 401);
        }
        return new JSONResponse($this->service->gatewayPayload());
    }
}
