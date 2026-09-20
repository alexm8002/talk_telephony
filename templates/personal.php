<?php
script('talk_telephony', 'personal');
style('talk_telephony', 'settings');
?>
<div id="talk-telephony-personal" class="section">
    <h2>Téléphonie Talk</h2>
    <p>Associez votre compte Nextcloud à votre extension SIP/PBX.</p>
    <div class="tt-grid">
        <label>Extension <input id="tt-extension" type="text" inputmode="numeric" placeholder="100"></label>
        <label>Utilisateur SIP <input id="tt-username" type="text" placeholder="identique à l’extension par défaut"></label>
        <label>Utilisateur d’authentification <input id="tt-auth-user" type="text" placeholder="identique par défaut"></label>
        <label>Mot de passe SIP <input id="tt-password" type="password" autocomplete="new-password" placeholder="laisser vide pour conserver"></label>
        <label>Caller ID <input id="tt-caller-id" type="text" placeholder="+33345283087"></label>
    </div>
    <p><button id="tt-save" class="primary">Enregistrer</button> <button id="tt-delete">Supprimer l’association</button></p>
    <p id="tt-status" class="tt-status"></p>
</div>
