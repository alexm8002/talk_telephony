<?php
/**
 * SPDX-FileCopyrightText: 2026 2M Production Electrique
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\TalkTelephony\Controller;

use OCA\TalkTelephony\Service\AccountService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\Attribute\NoAdminRequired;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IRequest;

class AccountController extends Controller {
    public function __construct(
        string $appName,
        IRequest $request,
        private AccountService $service,
        private ?string $userId,
    ) {
        parent::__construct($appName, $request);
    }

    #[NoAdminRequired]
    public function getPersonal(): JSONResponse {
        if ($this->userId === null) {
            return new JSONResponse(['error' => 'Utilisateur non connecté'], 401);
        }
        $account = $this->service->getByUser($this->userId);
        return new JSONResponse(['account' => $account?->jsonSerialize()]);
    }

    #[NoAdminRequired]
    public function savePersonal(
        string $extension,
        ?string $password = null,
        ?string $username = null,
        ?string $auth_user = null,
        string $caller_id = '',
    ): JSONResponse {
        if ($this->userId === null) {
            return new JSONResponse(['error' => 'Utilisateur non connecté'], 401);
        }
        try {
            $account = $this->service->save($this->userId, $extension, $password, $username, $auth_user, $caller_id, true, false);
            return new JSONResponse(['account' => $account->jsonSerialize()]);
        } catch (\InvalidArgumentException $e) {
            return new JSONResponse(['error' => $e->getMessage()], 400);
        }
    }

    #[NoAdminRequired]
    public function deletePersonal(): JSONResponse {
        if ($this->userId === null) {
            return new JSONResponse(['error' => 'Utilisateur non connecté'], 401);
        }
        $this->service->deleteByUser($this->userId);
        return new JSONResponse(['ok' => true]);
    }
}
