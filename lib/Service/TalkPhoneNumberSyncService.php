<?php
/**
 * SPDX-FileCopyrightText: 2026 2M Production Electrique
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\TalkTelephony\Service;

use OCA\Talk\Model\PhoneNumber;
use OCA\Talk\Model\PhoneNumberMapper;
use OCA\Talk\Service\PhoneNumberValidation;
use OCP\AppFramework\Db\DoesNotExistException;

class TalkPhoneNumberSyncService {
    public function __construct(
        private PhoneNumberMapper $mapper,
        private PhoneNumberValidation $validation,
    ) {}

    public function normalize(string $extension): string {
        try {
            return $this->validation->validateNumber(trim($extension));
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException('Extension invalide', 0, $e);
        }
    }

    public function assertAvailable(string $extension, string $userId): void {
        try {
            $entry = $this->mapper->findByPhoneNumber($extension);
        } catch (DoesNotExistException) {
            return;
        }

        if ($entry->getActorId() !== $userId) {
            throw new \InvalidArgumentException('Cette extension est déjà attribuée à un autre utilisateur dans Talk');
        }
    }

    public function sync(?string $oldExtension, string $newExtension, string $userId, bool $enabled): void {
        if ($oldExtension !== null && $oldExtension !== '' && $oldExtension !== $newExtension) {
            $this->removeIfOwned($oldExtension, $userId);
        }

        if (!$enabled) {
            $this->removeIfOwned($newExtension, $userId);
            return;
        }

        try {
            $entry = $this->mapper->findByPhoneNumber($newExtension);
            if ($entry->getActorId() !== $userId) {
                throw new \InvalidArgumentException('Cette extension est déjà attribuée à un autre utilisateur dans Talk');
            }
            return;
        } catch (DoesNotExistException) {
            // Create the native Talk mapping below.
        }

        $entry = new PhoneNumber();
        $entry->setPhoneNumber($newExtension);
        $entry->setActorId($userId);
        $this->mapper->insert($entry);
    }

    public function removeIfOwned(string $extension, string $userId): void {
        try {
            $entry = $this->mapper->findByPhoneNumber($extension);
        } catch (DoesNotExistException) {
            return;
        }

        if ($entry->getActorId() === $userId) {
            $this->mapper->delete($entry);
        }
    }
}
