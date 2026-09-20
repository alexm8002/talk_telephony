(function () {
  const url = OC.generateUrl('/apps/talk_telephony/api/v1/account');
  const headers = {'Content-Type':'application/json', 'requesttoken': OC.requestToken};
  const status = document.getElementById('tt-status');
  const setStatus = (m, ok=true) => { status.textContent = m; status.dataset.ok = ok ? '1' : '0'; };
  async function load() {
    const r = await fetch(url, {headers}); const j = await r.json();
    if (j.account) {
      document.getElementById('tt-extension').value = j.account.extension || '';
      document.getElementById('tt-username').value = j.account.username || '';
      document.getElementById('tt-auth-user').value = j.account.auth_user || '';
      document.getElementById('tt-caller-id').value = j.account.caller_id || '';
      document.getElementById('tt-password').placeholder = j.account.has_password ? 'mot de passe enregistré — laisser vide pour conserver' : 'mot de passe SIP';
    }
  }
  document.getElementById('tt-save').addEventListener('click', async () => {
    const body = {
      extension: document.getElementById('tt-extension').value,
      username: document.getElementById('tt-username').value || null,
      auth_user: document.getElementById('tt-auth-user').value || null,
      password: document.getElementById('tt-password').value || null,
      caller_id: document.getElementById('tt-caller-id').value,
    };
    const r = await fetch(url, {method:'PUT', headers, body:JSON.stringify(body)}); const j = await r.json();
    setStatus(r.ok ? 'Configuration enregistrée.' : (j.error || 'Erreur'), r.ok);
    if (r.ok) { document.getElementById('tt-password').value=''; await load(); }
  });
  document.getElementById('tt-delete').addEventListener('click', async () => {
    if (!confirm('Supprimer votre association SIP ?')) return;
    const r = await fetch(url, {method:'DELETE', headers});
    setStatus(r.ok ? 'Association supprimée.' : 'Erreur', r.ok);
    if (r.ok) location.reload();
  });
  load().catch(e => setStatus(String(e), false));
})();
