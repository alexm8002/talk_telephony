<?php
namespace OCA\TalkTelephony\Controller;

use OCA\TalkTelephony\Service\AccountService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\JSONResponse;
use OCP\IConfig;
use OCP\IRequest;
use OCP\IUserManager;
use OCP\Security\ISecureRandom;

class AdminController extends Controller {
    public function __construct(
        string $appName,
        IRequest $request,
        private AccountService $service,
        private IConfig $config,
        private IUserManager $userManager,
        private ISecureRandom $secureRandom,
    ) {
        parent::__construct($appName, $request);
    }

    public function listAccounts(): JSONResponse {
        return new JSONResponse(['accounts' => array_map(fn($a) => $a->jsonSerialize(), $this->service->getAll())]);
    }

    public function saveAccount(
        string $userId,
        string $extension,
        ?string $password = null,
        ?string $username = null,
        ?string $auth_user = null,
        string $caller_id = '',
        bool $enabled = true,
        bool $default = false,
    ): JSONResponse {
        if ($this->userManager->get($userId) === null) {
            return new JSONResponse(['error' => 'Utilisateur Nextcloud introuvable'], 404);
        }
        try {
            $account = $this->service->save($userId, $extension, $password, $username, $auth_user, $caller_id, $enabled, $default);
            return new JSONResponse(['account' => $account->jsonSerialize()]);
        } catch (\InvalidArgumentException $e) {
            return new JSONResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function deleteAccount(string $userId): JSONResponse {
        $this->service->deleteByUser($userId);
        return new JSONResponse(['ok' => true]);
    }

    public function syncTalkMappings(): JSONResponse {
        try {
            return new JSONResponse(['ok' => true] + $this->service->syncAllTalkMappings());
        } catch (\InvalidArgumentException $e) {
            return new JSONResponse(['error' => $e->getMessage()], 400);
        }
    }

    public function getGatewayConfig(): JSONResponse {
        return new JSONResponse([
            'configured' => $this->config->getAppValue('talk_telephony', 'gateway_token_hash', '') !== '',
        ]);
    }

    public function setGatewayToken(?string $token = null): JSONResponse {
        $token = trim((string)$token);
        if ($token === '') {
            $token = $this->secureRandom->generate(48, ISecureRandom::CHAR_HUMAN_READABLE);
        }
        $this->config->setAppValue('talk_telephony', 'gateway_token_hash', hash('sha256', $token));
        return new JSONResponse(['token' => $token]);
    }
}
