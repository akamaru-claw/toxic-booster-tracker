<?php
// Security headers — vor allem anderem
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: no-referrer');
header('Permissions-Policy: camera=(), microphone=(), geolocation=(), tracking=()');
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; connect-src 'self'; font-src 'self'; frame-ancestors 'none'; form-action 'self';");
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="referrer" content="no-referrer">
<meta name="description" content="Toxic Booster Genesis Edition — Sammelkarten-Tracker der Einundzwanzig Community">
<title>🧪 Toxic Booster Tracker</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{
  --bg:#0a0a0a;--surface:#141420;--surface2:#1c1c2e;--border:#2a2a3e;
  --toxic:#39ff14;--toxic-dim:#1a8a0a;--orange:#ff6b2b;--purple:#9b59b6;
  --green:#4ecca3;--yellow:#ffd93d;--red:#e74c3c;--blue:#3498db;
  --text:#e8e8e8;--muted:#666;--muted2:#444
}
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',system-ui,sans-serif;background:var(--bg);color:var(--text);min-height:100vh;-webkit-tap-highlight-color:transparent;overflow-x:hidden}

/* Toxic glow */
@keyframes toxicPulse{0%,100%{text-shadow:0 0 8px var(--toxic-dim)}50%{text-shadow:0 0 20px var(--toxic),0 0 40px var(--toxic-dim)}}
.toxic-glow{animation:toxicPulse 3s ease-in-out infinite}

/* Auth */
.auth-wrap{display:flex;align-items:center;justify-content:center;min-height:100vh;padding:20px}
.auth-box{width:100%;max-width:380px;text-align:center}
.logo{font-size:48px;margin-bottom:8px}
.logo h1{font-size:26px;font-weight:900;letter-spacing:-1px}
.logo h1 .t{color:var(--toxic)}
.logo .sub{color:var(--muted);font-size:13px;margin-top:6px;line-height:1.4}
.auth-form{display:flex;flex-direction:column;gap:12px;margin:24px 0 0}
.auth-form input{background:var(--surface);border:2px solid var(--border);color:var(--text);border-radius:10px;padding:14px 16px;font-size:16px;outline:none;transition:border-color .2s}
.auth-form input:focus{border-color:var(--toxic)}
.auth-form input::placeholder{color:var(--muted2)}
.btn{border:none;border-radius:10px;padding:14px;font-size:15px;font-weight:700;cursor:pointer;transition:all .15s}
.btn:active{transform:scale(.97)}
.btn-primary{background:var(--toxic);color:#000}
.btn-primary:hover{background:#5fff3a}
.btn-ghost{background:var(--surface);border:2px solid var(--border);color:var(--text)}
.btn-ghost:hover{border-color:var(--toxic)}
.auth-error{color:var(--red);font-size:13px;min-height:20px;margin-bottom:4px}
.auth-note{color:var(--muted2);font-size:12px;margin-top:16px;line-height:1.5}
.auth-note a{color:var(--toxic);text-decoration:none}

/* App Shell */
.app{display:none}
.app.show{display:block}
.topbar{position:sticky;top:0;z-index:20;background:rgba(10,10,10,.92);backdrop-filter:blur(12px);border-bottom:1px solid var(--border);padding:12px 16px 8px}
.topbar-row{display:flex;justify-content:space-between;align-items:center}
.topbar h1{font-size:17px;font-weight:800;letter-spacing:-0.5px}
.topbar h1 .t{color:var(--toxic)}
.topbar-right{display:flex;align-items:center;gap:10px}
.user-badge{font-size:12px;color:var(--muted);background:var(--surface);padding:4px 10px;border-radius:20px}
.btn-sm{font-size:11px;padding:6px 10px;border-radius:8px}

.stats-bar{display:flex;gap:0;margin-top:10px}
.stat{flex:1;text-align:center;padding:6px 0}
.stat-val{font-size:22px;font-weight:800;display:block}
.stat-label{font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:.5px}
.stat-val.s-green{color:var(--green)}.stat-val.s-yellow{color:var(--yellow)}.stat-val.s-red{color:var(--red)}.stat-val.s-blue{color:var(--blue)}

/* Tabs */
.tab-bar{display:flex;position:sticky;top:80px;z-index:15;background:var(--bg);border-bottom:1px solid var(--border)}
.tab-btn{flex:1;padding:10px 4px;text-align:center;font-size:11px;font-weight:700;color:var(--muted);border:none;border-bottom:3px solid transparent;background:none;cursor:pointer;text-transform:uppercase;letter-spacing:.5px;transition:all .2s}
.tab-btn.active{color:var(--toxic);border-bottom-color:var(--toxic)}
.tab-btn .tab-n{display:block;font-size:18px;margin-bottom:2px;font-weight:800}

/* Panels */
.panel{display:none;padding:12px 12px 100px}
.panel.active{display:block}

/* Card grid */
.grid{display:grid;grid-template-columns:repeat(3,1fr);gap:8px}
@media(min-width:420px){.grid{grid-template-columns:repeat(4,1fr);gap:10px}}
@media(min-width:640px){.grid{grid-template-columns:repeat(5,1fr)}}
@media(min-width:900px){.grid{grid-template-columns:repeat(7,1fr)}}

.c{background:var(--surface);border:2px solid var(--border);border-radius:12px;padding:6px 4px;text-align:center;cursor:pointer;user-select:none;transition:all .12s;position:relative;overflow:hidden}
.c:active{transform:scale(.94)}
.c .c-num{font-size:22px;font-weight:900;line-height:1;color:var(--muted2)}
.c .c-count{font-size:18px;font-weight:800;margin:2px 0}
.c .c-info{font-size:9px;color:var(--muted);min-height:14px}
.c .c-bar{height:3px;background:var(--border);border-radius:2px;margin-top:4px;overflow:hidden}
.c .c-bar-f{height:100%;border-radius:2px;transition:width .3s}

/* Card states */
.c.s-none{opacity:.35}.c.s-none .c-count{color:var(--muted2)}
.c.s-one{border-color:var(--green)}.c.s-one .c-count{color:var(--green)}.c.s-one .c-bar-f{background:var(--green)}
.c.s-dup{border-color:var(--yellow)}.c.s-dup .c-count{color:var(--yellow)}.c.s-dup .c-bar-f{background:var(--yellow)}
.c.s-max{border-color:var(--toxic);opacity:.6}.c.s-max .c-count{color:var(--toxic)}.c.s-max .c-bar-f{background:var(--toxic)}

/* Toxic glow on dup border */
.c.s-dup::before{content:'';position:absolute;inset:-1px;border-radius:12px;border:1px solid rgba(57,255,20,.2);pointer-events:none}

/* Trade chips */
.section{margin-bottom:20px}
.section h3{font-size:13px;color:var(--muted);margin-bottom:10px;display:flex;align-items:center;gap:6px;text-transform:uppercase;letter-spacing:.5px}
.chips{display:flex;flex-wrap:wrap;gap:8px}
.chip{background:var(--surface);border:1px solid var(--border);border-radius:20px;padding:6px 14px;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:6px;cursor:pointer;transition:border-color .15s}
.chip:active{transform:scale(.95)}
.chip.dup{border-color:var(--yellow);color:var(--yellow)}
.chip.missing{border-color:var(--red);color:var(--red)}
.chip .badge{font-size:10px;background:rgba(255,255,255,.08);border-radius:10px;padding:2px 8px;color:var(--text)}

.empty{text-align:center;padding:48px 24px;color:var(--muted2);font-size:14px}

/* Modal */
.m-overlay{position:fixed;inset:0;background:rgba(0,0,0,.8);z-index:100;display:none;align-items:center;justify-content:center;padding:16px}
.m-overlay.show{display:flex}
.m{background:var(--surface2);border:1px solid var(--border);border-radius:16px;padding:24px;width:100%;max-width:320px}
.m h2{font-size:18px;font-weight:800}.m h2 .t{color:var(--toxic)}
.m-sub{font-size:12px;color:var(--muted);margin-bottom:16px}
.m-big{font-size:52px;font-weight:900;text-align:center;margin:12px 0;color:var(--toxic)}
.m-row{display:flex;gap:8px;justify-content:center;margin-bottom:12px}
.m-btn{width:52px;height:44px;border:2px solid var(--border);background:transparent;color:var(--text);border-radius:10px;font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-weight:700;transition:all .12s}
.m-btn:active{background:var(--toxic);color:#000;border-color:var(--toxic)}
.m-presets{display:flex;gap:8px;margin:4px 0 12px}
.m-presets button{flex:1;padding:10px;border:1px solid var(--border);background:transparent;color:var(--muted);border-radius:8px;font-size:12px;cursor:pointer;font-weight:600}
.m-presets button:active{border-color:var(--toxic);color:var(--toxic)}
.m-close{width:100%;padding:12px;border:1px solid var(--border);background:transparent;color:var(--muted);border-radius:10px;font-size:13px;cursor:pointer}

/* Loading */
.spinner{display:flex;align-items:center;justify-content:center;min-height:100vh;color:var(--muted);font-size:14px;gap:8px}
.spinner.off{display:none}
.spin{animation:spin 1s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}

/* Reset */
.danger-btn{background:var(--surface);border:1px solid var(--border);color:var(--muted2);padding:10px;width:100%;border-radius:10px;font-size:12px;cursor:pointer;margin-top:20px}
.danger-btn:active{border-color:var(--red);color:var(--red)}

/* Footer */
.footer{text-align:center;padding:20px;color:var(--muted2);font-size:10px;line-height:1.6}
.footer a{color:var(--muted);text-decoration:none}
</style>
</head>
<body>

<!-- AUTH -->
<div class="auth-wrap" id="authView">
  <div class="auth-box">
    <div class="logo">
      <div style="font-size:48px">🧪</div>
      <h1><span class="t toxic-glow">Toxic</span> Booster</h1>
      <div class="sub">Genesis Edition · Einundzwanzig Zitadelle<br>21 Karten × 210 Stück — Track your stack</div>
    </div>
    <div class="auth-error" id="authErr"></div>
    <div class="auth-form" id="authForm">
      <input type="text" id="iUser" placeholder="Username" autocomplete="username" autocapitalize="off" spellcheck="false">
      <input type="password" id="iPass" placeholder="Passwort" autocomplete="current-password">
      <button class="btn btn-primary" onclick="doAuth('login')">Einloggen</button>
      <button class="btn btn-ghost" onclick="doAuth('register')">Account erstellen</button>
    </div>
    <div class="auth-note">
      🔒 Kein Tracking. Keine E-Mail. Keine Analytics.<br>
      Deine Daten gehören dir. <a href="https://github.com/akamaru-claw/toxic-booster-tracker" target="_blank" rel="noopener">Open Source</a>.
    </div>
  </div>
</div>

<!-- LOADING -->
<div class="spinner" id="spinner"><span class="spin" style="display:inline-block">⚡</span> Verifiziere…</div>

<!-- APP -->
<div class="app" id="appView">

  <div class="topbar">
    <div class="topbar-row">
      <h1>🧪 <span class="t">Toxic</span> Booster</h1>
      <div class="topbar-right">
        <span class="user-badge" id="uBadge"></span>
        <button class="btn btn-ghost btn-sm" onclick="doLogout()">Logout</button>
      </div>
    </div>
    <div class="stats-bar">
      <div class="stat"><span class="stat-val s-green" id="sO">0</span><span class="stat-label">Besitzt</span></div>
      <div class="stat"><span class="stat-val s-yellow" id="sD">0</span><span class="stat-label">Doppelt</span></div>
      <div class="stat"><span class="stat-val s-red" id="sM">21</span><span class="stat-label">Fehlt</span></div>
      <div class="stat"><span class="stat-val s-blue" id="sT">0</span><span class="stat-label">Gesamt</span></div>
    </div>
  </div>

  <div class="tab-bar">
    <button class="tab-btn active" data-p="overview"><span class="tab-n" id="tnO">0/21</span>Überblick</button>
    <button class="tab-btn" data-p="trade"><span class="tab-n" id="tnT">0</span>Tauschen</button>
    <button class="tab-btn" data-p="need"><span class="tab-n" id="tnN">21</span>Suche</button>
  </div>

  <div class="panel active" id="p-overview">
    <div class="grid" id="grid"></div>
    <button class="danger-btn" onclick="resetAll()">🔄 Sammlung zurücksetzen</button>
    <div class="footer">
      Toxic Booster Genesis Edition © MX12 · <a href="https://mx12.art" target="_blank" rel="noopener">mx12.art</a><br>
      Community Tool · <a href="https://github.com/akamaru-claw/toxic-booster-tracker" target="_blank" rel="noopener">GitHub</a> · Keine Affiliate-Links
    </div>
  </div>

  <div class="panel" id="p-trade">
    <div class="section">
      <h3>📤 Doppelte zum Tauschen</h3>
      <div class="chips" id="tradeChips"></div>
    </div>
  </div>

  <div class="panel" id="p-need">
    <div class="section">
      <h3>📥 Fehlende Karten gesucht</h3>
      <div class="chips" id="needChips"></div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="m-overlay" id="modal">
  <div class="m">
    <h2>Karte <span class="t" id="mNum">#1</span></h2>
    <div class="m-sub" id="mSub">Max: 210 · Toxic Booster Genesis</div>
    <div class="m-big" id="mCount">0</div>
    <div class="m-row">
      <button class="m-btn" onclick="adj(-10)">−10</button>
      <button class="m-btn" onclick="adj(-1)">−1</button>
      <button class="m-btn" onclick="adj(1)">+1</button>
      <button class="m-btn" onclick="adj(10)">+10</button>
    </div>
    <div class="m-presets">
      <button onclick="setC(0)">0</button>
      <button onclick="setC(1)">1</button>
      <button onclick="setC(5)">5</button>
      <button onclick="setC(210)">MAX</button>
    </div>
    <button class="m-close" onclick="closeM()">Schließen</button>
  </div>
</div>

<script>
const A='auth_api.php',C='cards_api.php',N=21,MX=210;
let tk=localStorage.getItem('tb_tk')||'',un=localStorage.getItem('tb_un')||'';
let cards=Array(N).fill(0),ac=null,sv=null;

async function api(u,d){const r=await fetch(u,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(d)});return r.json()}

function show(v){document.getElementById('authView').style.display='none';document.getElementById('spinner').classList.add('off');document.getElementById('appView').classList.remove('show');if(v==='auth'){document.getElementById('authView').style.display='flex'}else{document.getElementById('appView').classList.add('show');document.getElementById('uBadge').textContent=un}}

async function doAuth(act){
  const u=document.getElementById('iUser').value.trim(),p=document.getElementById('iPass').value;
  if(!u||!p){document.getElementById('authErr').textContent='Username & Passwort eingeben';return}
  const r=await api(A,{action:act,username:u,password:p});
  if(!r.ok){document.getElementById('authErr').textContent=r.error;return}
  tk=r.token;un=r.username;localStorage.setItem('tb_tk',tk);localStorage.setItem('tb_un',un);
  document.getElementById('authErr').textContent='';
  await loadC();show('app');render()
}

async function doLogout(){
  if(tk)await api(A,{action:'logout',token:tk});
  tk='';un='';localStorage.removeItem('tb_tk');localStorage.removeItem('tb_un');
  cards=Array(N).fill(0);show('auth')
}

async function init(){
  if(!tk){show('auth');return}
  const r=await api(A,{action:'verify',token:tk});
  if(!r.ok){tk='';localStorage.removeItem('tb_tk');localStorage.removeItem('tb_un');show('auth');return}
  un=r.username;localStorage.setItem('tb_un',un);
  await loadC();show('app');render()
}

async function loadC(){
  const r=await api(C,{action:'load',token:tk});
  if(r.ok&&r.cards)cards=r.cards
}

function saveC(){clearTimeout(sv);sv=setTimeout(async()=>{await api(C,{action:'save',token:tk,cards})},400)}

function render(){
  const g=document.getElementById('grid');g.innerHTML='';
  let o=0,d=0,m=0,t=0;
  for(let i=0;i<N;i++){
    const n=i+1,v=cards[i],pct=v/MX*100;
    let cls=v===0?'s-none':v===1?'s-one':v>=MX?'s-max':'s-dup';
    if(v===0)m++;else{o++;if(v>1)d+=v-1;t+=v}
    const el=document.createElement('div');el.className='c '+cls;el.onclick=()=>openM(i);
    el.innerHTML=`<div class="c-num">#${n}</div><div class="c-count">${v||'—'}</div><div class="c-info">${v>1?(v-1)+'× doppelt':v===1?'✓':'fehlt'}</div><div class="c-bar"><div class="c-bar-f" style="width:${Math.min(pct,100)}%"></div></div>`;
    g.appendChild(el)
  }
  document.getElementById('sO').textContent=o;document.getElementById('sD').textContent=d;document.getElementById('sM').textContent=m;document.getElementById('sT').textContent=t;
  document.getElementById('tnO').textContent=o+'/21';document.getElementById('tnT').textContent=d;document.getElementById('tnN').textContent=m;
  
  // Trade chips
  const tc=document.getElementById('tradeChips');tc.innerHTML='';let ht=false;
  for(let i=0;i<N;i++){if(cards[i]>1){ht=true;const ch=document.createElement('div');ch.className='chip dup';ch.innerHTML=`#${i+1} <span class="badge">${cards[i]-1}×</span>`;ch.onclick=()=>openM(i);tc.appendChild(ch)}}
  if(!ht)tc.innerHTML='<div class="empty">Noch keine doppelten Karten 🤷</div>';
  
  // Need chips
  const nc=document.getElementById('needChips');nc.innerHTML='';let hm=false;
  for(let i=0;i<N;i++){if(cards[i]===0){hm=true;const ch=document.createElement('div');ch.className='chip missing';ch.innerHTML=`#${i+1}`;ch.onclick=()=>openM(i);nc.appendChild(ch)}}
  if(!hm)nc.innerHTML='<div class="empty">Vollständig! 🧪🎉</div>';
}

function openM(i){ac=i;document.getElementById('mNum').textContent='#'+(i+1);document.getElementById('mCount').textContent=cards[i];document.getElementById('modal').classList.add('show')}
function adj(d){if(ac===null)return;cards[ac]=Math.max(0,Math.min(MX,cards[ac]+d));document.getElementById('mCount').textContent=cards[ac];saveC();render()}
function setC(v){if(ac===null)return;cards[ac]=Math.max(0,Math.min(MX,v));document.getElementById('mCount').textContent=cards[ac];saveC();render()}
function closeM(){document.getElementById('modal').classList.remove('show');ac=null}
function resetAll(){if(!confirm('Alle Karten auf 0 setzen?'))return;cards.fill(0);saveC();render()}

document.getElementById('modal').addEventListener('click',e=>{if(e.target.id==='modal')closeM()});
document.querySelectorAll('.tab-btn').forEach(b=>b.addEventListener('click',()=>{document.querySelectorAll('.tab-btn').forEach(t=>t.classList.remove('active'));document.querySelectorAll('.panel').forEach(p=>p.classList.remove('active'));b.classList.add('active');document.getElementById('p-'+b.dataset.p).classList.add('active')}));
document.getElementById('iPass').addEventListener('keydown',e=>{if(e.key==='Enter')doAuth('login')});
document.getElementById('iUser').addEventListener('keydown',e=>{if(e.key==='Enter')document.getElementById('iPass').focus()});
document.addEventListener('keydown',e=>{if(!document.getElementById('modal').classList.contains('show'))return;if(e.key==='Escape')closeM();if(e.key==='+'||e.key==='=')adj(1);if(e.key==='-')adj(-1)});

init();
</script>
</body>
</html>