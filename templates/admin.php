<?php
/**
 * SPDX-FileCopyrightText: 2026 2M Production Electrique
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

script('talk_telephony', 'admin');
style('talk_telephony', 'settings');
?>
<div id="talk-telephony-admin" class="section">
    <h2>Talk Telephony Gateway</h2>
    <p>Comptes SIP utilisés par le gateway Talk.</p>
    <div class="tt-grid">
        <label>User ID Nextcloud <input id="tta-user" type="text" placeholder="alexandre.martinez"></label>
        <label>Extension <input id="tta-extension" type="text" inputmode="numeric" placeholder="100"></label>
        <label>Utilisateur SIP <input id="tta-username" type="text"></label>
        <label>Utilisateur d’authentification <input id="tta-auth-user" type="text"></label>
        <label>Mot de passe SIP <input id="tta-password" type="password" autocomplete="new-password"></label>
        <label>Caller ID <input id="tta-caller-id" type="text" placeholder="+33345283087"></label>
        <label><input id="tta-enabled" type="checkbox" checked> Compte activé</label>
        <label><input id="tta-default" type="checkbox"> Compte par défaut</label>
    </div>
    <p><button id="tta-save" class="primary">Créer / mettre à jour</button></p>
    <p><button id="tta-sync-talk">Synchroniser les mappings Talk</button></p>
    <h3>Token API du gateway</h3>
    <p><button id="tta-token">Générer un nouveau token</button></p>
    <p id="tta-token-output" class="tt-secret"></p>
    <h3>Comptes configurés</h3>
    <table class="grid" id="tta-table"><thead><tr><th>User</th><th>Extension</th><th>Auth user</th><th>Caller ID</th><th>Actif</th><th>Défaut</th><th></th></tr></thead><tbody></tbody></table>
    <p id="tta-status" class="tt-status"></p>
</div>
