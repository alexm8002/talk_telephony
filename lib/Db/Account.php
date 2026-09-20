<?php
namespace OCA\TalkTelephony\Db;

use OCP\AppFramework\Db\Entity;

class Account extends Entity implements \JsonSerializable {
    protected $userId = '';
    protected $extension = '';
    protected $username = '';
    protected $authUser = '';
    protected $passwordEnc = '';
    protected $callerId = '';
    protected $enabled = true;
    protected $isDefault = false;
    protected $updatedAt = 0;

    public function __construct() {
        $this->addType('id', 'integer');
        $this->addType('userId', 'string');
        $this->addType('extension', 'string');
        $this->addType('username', 'string');
        $this->addType('authUser', 'string');
        $this->addType('passwordEnc', 'string');
        $this->addType('callerId', 'string');
        $this->addType('enabled', 'boolean');
        $this->addType('isDefault', 'boolean');
        $this->addType('updatedAt', 'integer');
    }

    public function jsonSerialize(): array {
        return [
            'id' => $this->getId(),
            'user_id' => $this->getUserId(),
            'extension' => $this->getExtension(),
            'username' => $this->getUsername(),
            'auth_user' => $this->getAuthUser(),
            'caller_id' => $this->getCallerId(),
            'enabled' => $this->getEnabled(),
            'default' => $this->getIsDefault(),
            'has_password' => $this->getPasswordEnc() !== '',
            'updated_at' => $this->getUpdatedAt(),
        ];
    }
}
