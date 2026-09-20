<?php
return [
    'routes' => [
        ['name' => 'account#getPersonal', 'url' => '/api/v1/account', 'verb' => 'GET'],
        ['name' => 'account#savePersonal', 'url' => '/api/v1/account', 'verb' => 'PUT'],
        ['name' => 'account#deletePersonal', 'url' => '/api/v1/account', 'verb' => 'DELETE'],
        ['name' => 'admin#listAccounts', 'url' => '/api/v1/admin/accounts', 'verb' => 'GET'],
        ['name' => 'admin#saveAccount', 'url' => '/api/v1/admin/accounts/{userId}', 'verb' => 'PUT'],
        ['name' => 'admin#deleteAccount', 'url' => '/api/v1/admin/accounts/{userId}', 'verb' => 'DELETE'],
        ['name' => 'admin#syncTalkMappings', 'url' => '/api/v1/admin/sync-talk-mappings', 'verb' => 'POST'],
        ['name' => 'admin#getGatewayConfig', 'url' => '/api/v1/admin/gateway', 'verb' => 'GET'],
        ['name' => 'admin#setGatewayToken', 'url' => '/api/v1/admin/gateway/token', 'verb' => 'PUT'],
        ['name' => 'gateway#accounts', 'url' => '/api/v1/gateway/accounts', 'verb' => 'GET'],
    ],
];
