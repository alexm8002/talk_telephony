# Talk Telephony (Nextcloud app)

Prototype app for Nextcloud 33 providing per-user SIP/PBX credentials to the Talk Telephony Gateway.

## Features

- Personal settings: extension, SIP username/auth user, password, caller ID.
- Admin settings: create/update/delete mappings for any Nextcloud user.
- SIP passwords encrypted at rest through Nextcloud `OCP\\Security\\ICrypto`.
- Gateway API protected by a bearer token; the token itself is only stored hashed.
- API endpoint: `GET /apps/talk_telephony/api/v1/gateway/accounts`.

## Install (development)

Copy the `talk_telephony` directory to `custom_apps/` and run:

```bash
php occ app:enable talk_telephony
php occ migrations:execute talk_telephony 010000Date20260920150000
```

Normally enabling the app should apply migrations automatically.

## Gateway API

Generate a gateway token from Administration -> Additional settings -> Talk Telephony Gateway.

Example:

```bash
curl -H 'Authorization: Bearer TOKEN' \
  https://drive.example.com/index.php/apps/talk_telephony/api/v1/gateway/accounts
```

Response:

```json
{
  "version": 1,
  "accounts": [
    {
      "extension": "100",
      "username": "100",
      "auth_user": "100",
      "password": "...",
      "caller_id": "+33xxxxxx",
      "nextcloud_user": "nextcloud_user_ID",
      "default": true
    }
  ]
}
```
