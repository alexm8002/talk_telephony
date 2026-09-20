<?php
/**
 * SPDX-FileCopyrightText: 2026 2M Production Electrique
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace OCA\TalkTelephony\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;

class Version010000Date20260920150000 extends SimpleMigrationStep {
    public function changeSchema(IOutput $output, Closure $schemaClosure, array $options): ?ISchemaWrapper {
        /** @var ISchemaWrapper $schema */
        $schema = $schemaClosure();
        if ($schema->hasTable('talk_telephony_accounts')) {
            return null;
        }

        $table = $schema->createTable('talk_telephony_accounts');
        $table->addColumn('id', 'bigint', ['autoincrement' => true, 'notnull' => true, 'unsigned' => true]);
        $table->addColumn('user_id', 'string', ['notnull' => true, 'length' => 64]);
        $table->addColumn('extension', 'string', ['notnull' => true, 'length' => 32]);
        $table->addColumn('username', 'string', ['notnull' => true, 'length' => 128]);
        $table->addColumn('auth_user', 'string', ['notnull' => true, 'length' => 128]);
        $table->addColumn('password_enc', 'text', ['notnull' => true]);
        $table->addColumn('caller_id', 'string', ['notnull' => true, 'length' => 64, 'default' => '']);
        $table->addColumn('enabled', 'boolean', ['notnull' => true, 'default' => true]);
        $table->addColumn('is_default', 'boolean', ['notnull' => true, 'default' => false]);
        $table->addColumn('updated_at', 'bigint', ['notnull' => true, 'default' => 0]);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['user_id'], 'tt_user_uidx');
        $table->addUniqueIndex(['extension'], 'tt_ext_uidx');
        $table->addIndex(['enabled'], 'tt_enabled_idx');
        return $schema;
    }
}
