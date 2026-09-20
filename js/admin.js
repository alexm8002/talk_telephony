(function () {
  const base = OC.generateUrl('/apps/talk_telephony/api/v1/admin');
  const headers = {'Content-Type':'application/json', 'requesttoken': OC.requestToken};
  const status = document.getElementById('tta-status');
  const setStatus = (m, ok=true) => { status.textContent = m; status.dataset.ok = ok ? '1' : '0'; };
  async function load() {
    const r = await fetch(base + '/accounts', {headers}); const j = await r.json();
    const tbody = document.querySelector('#tta-table tbody'); tbody.innerHTML='';
    for (const a of (j.accounts || [])) {
      const tr=document.createElement('tr');
      tr.innerHTML=`<td>${escapeHtml(a.user_id)}</td><td>${escapeHtml(a.extension)}</td><td>${escapeHtml(a.auth_user)}</td><td>${escapeHtml(a.caller_id||'')}</td><td>${a.enabled?'oui':'non'}</td><td>${a.default?'oui':'non'}</td><td><button data-user="${escapeAttr(a.user_id)}">Supprimer</button></td>`;
      tr.querySelector('button').addEventListener('click', async () => { await fetch(base + '/accounts/' + encodeURIComponent(a.user_id), {method:'DELETE', headers}); await load(); });
      tbody.appendChild(tr);
    }
  }
  function escapeHtml(v){return String(v).replace(/[&<>'"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[c]));}
  function escapeAttr(v){return escapeHtml(v);}
  document.getElementById('tta-save').addEventListener('click', async () => {
    const user=document.getElementById('tta-user').value.trim();
    const body={extension:document.getElementById('tta-extension').value,username:document.getElementById('tta-username').value||null,auth_user:document.getElementById('tta-auth-user').value||null,password:document.getElementById('tta-password').value||null,caller_id:document.getElementById('tta-caller-id').value,enabled:document.getElementById('tta-enabled').checked,default:document.getElementById('tta-default').checked};
    const r=await fetch(base+'/accounts/'+encodeURIComponent(user),{method:'PUT',headers,body:JSON.stringify(body)}); const j=await r.json();
    setStatus(r.ok?'Compte enregistré.':(j.error||'Erreur'),r.ok); if(r.ok){document.getElementById('tta-password').value='';await load();}
  });
  document.getElementById('tta-sync-talk').addEventListener('click', async () => {
    const r=await fetch(base+'/sync-talk-mappings',{method:'POST',headers,body:'{}'}); const j=await r.json();
    setStatus(r.ok?`Mappings Talk synchronisés (${j.synced||0} actifs).`:(j.error||'Erreur'),r.ok);
  });
  document.getElementById('tta-token').addEventListener('click', async () => {
    const r=await fetch(base+'/gateway/token',{method:'PUT',headers,body:'{}'}); const j=await r.json();
    if(r.ok){document.getElementById('tta-token-output').textContent='Token à copier maintenant dans le gateway : '+j.token;} else {setStatus(j.error||'Erreur',false);}
  });
  load().catch(e=>setStatus(String(e),false));
})();
