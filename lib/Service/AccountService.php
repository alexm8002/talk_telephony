<?php
/**
 * SPDX-FileCopyrightText: 2026 2M Production Electrique
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\TalkTelephony\Service;

use OCA\TalkTelephony\Db\Account;
use OCA\TalkTelephony\Db\AccountMapper;
use OCP\IDBConnection;
use OCP\Security\ICrypto;

class AccountService {
    public function __construct(
        private AccountMapper $mapper,
        private ICrypto $crypto,
        private TalkPhoneNumberSyncService $talkSync,
        private IDBConnection $db,
    ) {}

    public function getByUser(string $userId): ?Account {
        return $this->mapper->findByUserId($userId);
    }

    /** @return Account[] */
    public function getAll(): array {
        return $this->mapper->findAllAccounts();
    }

    public function save(
        string $userId,
        string $extension,
        ?string $password,
        ?string $username = null,
        ?string $authUser = null,
        string $callerId = '',
        bool $enabled = true,
        bool $isDefault = false,
    ): Account {
        $extension = $this->talkSync->normalize($extension);

        $conflict = $this->mapper->findByExtension($extension);
        if ($conflict !== null && $conflict->getUserId() !== $userId) {
            throw new \InvalidArgumentException('Cette extension est déjà attribuée à un autre utilisateur');
        }
        $this->talkSync->assertAvailable($extension, $userId);

        $account = $this->mapper->findByUserId($userId) ?? new Account();
        $oldExtension = $account->getId() !== null ? $account->getExtension() : null;

        $account->setUserId($userId);
        $account->setExtension($extension);
        $account->setUsername(trim($username ?: $extension));
        $account->setAuthUser(trim($authUser ?: ($username ?: $extension)));
        $account->setCallerId(trim($callerId));
        $account->setEnabled($enabled);
        $account->setIsDefault($isDefault);
        $account->setUpdatedAt(time());

        if ($password !== null && $password !== '') {
            $account->setPasswordEnc($this->crypto->encrypt($password));
        } elseif ($account->getId() === null || $account->getPasswordEnc() === '') {
            throw new \InvalidArgumentException('Le mot de passe SIP est obligatoire');
        }

        $this->db->beginTransaction();
        try {
            $saved = $account->getId() === null ? $this->mapper->insert($account) : $this->mapper->update($account);
            $this->talkSync->sync($oldExtension, $extension, $userId, $enabled);
            $this->db->commit();
            return $saved;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function deleteByUser(string $userId): void {
        $account = $this->mapper->findByUserId($userId);
        if ($account === null) {
            return;
        }

        $this->db->beginTransaction();
        try {
            $this->talkSync->removeIfOwned($account->getExtension(), $userId);
            $this->mapper->delete($account);
            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function syncAllTalkMappings(): array {
        $synced = 0;
        $disabled = 0;
        foreach ($this->mapper->findAllAccounts() as $account) {
            $extension = $this->talkSync->normalize($account->getExtension());
            $this->talkSync->assertAvailable($extension, $account->getUserId());
            $this->talkSync->sync($extension, $extension, $account->getUserId(), $account->getEnabled());
            if ($account->getEnabled()) {
                $synced++;
            } else {
                $disabled++;
            }
        }
        return ['synced' => $synced, 'disabled' => $disabled];
    }

    public function gatewayPayload(): array {
        $accounts = [];
        foreach ($this->mapper->findAllAccounts() as $account) {
            if (!$account->getEnabled()) {
                continue;
            }
            $accounts[] = [
                'extension' => $account->getExtension(),
                'username' => $account->getUsername(),
                'auth_user' => $account->getAuthUser(),
                'password' => $this->crypto->decrypt($account->getPasswordEnc()),
                'caller_id' => $account->getCallerId(),
                'nextcloud_user' => $account->getUserId(),
                'default' => $account->getIsDefault(),
            ];
        }
        return ['version' => 1, 'accounts' => $accounts];
    }
}
