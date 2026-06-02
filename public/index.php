<?php
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
<title>🧪 Toxic Booster Tracker</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{--bg:#0a0a0a;--s1:#141420;--s2:#1c1c2e;--bd:#2a2a3e;--t:#39ff14;--td:#1a8a0a;--og:#ff6b2b;--pu:#9b59b6;--gn:#4ecca3;--yl:#ffd93d;--rd:#e74c3c;--bl:#3498db;--tx:#e8e8e8;--mt:#666;--mt2:#444}
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',system-ui,sans-serif;background:var(--bg);color:var(--tx);min-height:100vh;-webkit-tap-highlight-color:transparent;overflow-x:hidden}
@keyframes tp{0%,100%{text-shadow:0 0 8px var(--td)}50%{text-shadow:0 0 20px var(--t),0 0 40px var(--td)}}
.tg{animation:tp 3s ease-in-out infinite}

/* Auth */
.aw{display:flex;align-items:center;justify-content:center;min-height:100vh;padding:20px}
.ab{width:100%;max-width:380px;text-align:center}
.logo{font-size:48px;margin-bottom:8px}
.logo h1{font-size:26px;font-weight:900;letter-spacing:-1px}
.logo h1 .t{color:var(--t)}
.logo .sub{color:var(--mt);font-size:13px;margin-top:6px;line-height:1.4}
.af{display:flex;flex-direction:column;gap:12px;margin:24px 0 0}
.af input{background:var(--s1);border:2px solid var(--bd);color:var(--tx);border-radius:10px;padding:14px 16px;font-size:16px;outline:none;transition:border-color .2s}
.af input:focus{border-color:var(--t)}
.af input::placeholder{color:var(--mt2)}
.btn{border:none;border-radius:10px;padding:14px;font-size:15px;font-weight:700;cursor:pointer;transition:all .15s}
.btn:active{transform:scale(.97)}
.bp{background:var(--t);color:#000}
.bg{background:var(--s1);border:2px solid var(--bd);color:var(--tx)}
.ae{color:var(--rd);font-size:13px;min-height:20px;margin-bottom:4px}
.an{color:var(--mt2);font-size:12px;margin-top:16px;line-height:1.5}
.an a{color:var(--t);text-decoration:none}

/* App */
.app{display:none}
.app.show{display:block}
.tb{position:sticky;top:0;z-index:20;background:rgba(10,10,10,.92);backdrop-filter:blur(12px);border-bottom:1px solid var(--bd);padding:12px 16px 8px}
.tb-r{display:flex;justify-content:space-between;align-items:center}
.tb h1{font-size:17px;font-weight:800;letter-spacing:-.5px}
.tb h1 .t{color:var(--t)}
.tb-rt{display:flex;align-items:center;gap:10px}
.ub{font-size:12px;color:var(--mt);background:var(--s1);padding:4px 10px;border-radius:20px}
.bs{font-size:11px;padding:6px 10px;border-radius:8px}
.sb{display:flex;gap:0;margin-top:10px}
.st{flex:1;text-align:center;padding:6px 0}
.sv{font-size:22px;font-weight:800;display:block}
.sl{font-size:10px;color:var(--mt);text-transform:uppercase;letter-spacing:.5px}
.sv.sg{color:var(--gn)}.sv.sy{color:var(--yl)}.sv.sr{color:var(--rd)}.sv.sb2{color:var(--bl)}

/* Tabs */
.tbr{display:flex;position:sticky;top:80px;z-index:15;background:var(--bg);border-bottom:1px solid var(--bd);overflow-x:auto;-webkit-overflow-scrolling:touch}
.tbr::-webkit-scrollbar{display:none}
.tb2{flex:1;min-width:0;padding:10px 2px;text-align:center;font-size:10px;font-weight:700;color:var(--mt);border:none;border-bottom:3px solid transparent;background:none;cursor:pointer;text-transform:uppercase;letter-spacing:.3px;transition:all .2s;white-space:nowrap}
.tb2.active{color:var(--t);border-bottom-color:var(--t)}
.tb2 .tn{display:block;font-size:16px;margin-bottom:2px;font-weight:800}

/* Panels */
.pn{display:none;padding:12px 12px 100px}
.pn.active{display:block}

/* Grid */
.gr{display:grid;grid-template-columns:repeat(3,1fr);gap:8px}
@media(min-width:420px){.gr{grid-template-columns:repeat(4,1fr);gap:10px}}
@media(min-width:640px){.gr{grid-template-columns:repeat(5,1fr)}}
@media(min-width:900px){.gr{grid-template-columns:repeat(7,1fr)}}
.c{background:var(--s1);border:2px solid var(--bd);border-radius:12px;padding:6px 4px 4px;text-align:center;cursor:pointer;user-select:none;transition:all .12s;position:relative;overflow:hidden;-webkit-touch-callout:none;-webkit-user-select:none}
.c:active{transform:scale(.94)}
.c .c-flash{position:absolute;inset:0;border-radius:10px;pointer-events:none;opacity:0;transition:opacity .3s}
.c .c-flash.up{background:rgba(78,204,163,.2)}.c .c-flash.dn{background:rgba(231,76,60,.2)}
.c .c-flash.show{opacity:1;transition:opacity 0s}
.c .c-hold-hint{position:absolute;bottom:2px;left:50%;transform:translateX(-50%);font-size:8px;color:var(--rd);opacity:0;transition:opacity .2s;white-space:nowrap;pointer-events:none}
.c .c-hold-hint.show{opacity:1}
.c .cn{font-size:14px;font-weight:900;line-height:1;color:var(--mt2)}
.c .c-emoji{font-size:28px;line-height:1;margin-bottom:2px}
.c .c-name{font-size:10px;font-weight:700;color:var(--tx);margin:1px 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.c .cc{font-size:18px;font-weight:800;margin:2px 0}
.c .ci{font-size:9px;color:var(--mt);min-height:14px}
.c .cb{height:3px;background:var(--bd);border-radius:2px;margin-top:4px;overflow:hidden}
.c .cf{height:100%;border-radius:2px;transition:width .3s}
.c.s0{opacity:.35}.c.s0 .cc{color:var(--mt2)}
.c.s1{border-color:var(--gn)}.c.s1 .cc{color:var(--gn)}.c.s1 .cf{background:var(--gn)}
.c.sd{border-color:var(--yl)}.c.sd .cc{color:var(--yl)}.c.sd .cf{background:var(--yl)}
.c.sm{border-color:var(--t);opacity:.6}.c.sm .cc{color:var(--t)}.c.sm .cf{background:var(--t)}
.c.sd::before{content:'';position:absolute;inset:-1px;border-radius:12px;border:1px solid rgba(57,255,20,.15);pointer-events:none}

/* Chips */
.sc{margin-bottom:20px}
.sc h3{font-size:13px;color:var(--mt);margin-bottom:10px;display:flex;align-items:center;gap:6px;text-transform:uppercase;letter-spacing:.5px}
.chs{display:flex;flex-wrap:wrap;gap:8px}
.ch{background:var(--s1);border:1px solid var(--bd);border-radius:20px;padding:6px 14px;font-size:13px;font-weight:600;display:inline-flex;align-items:center;gap:6px;cursor:pointer;transition:border-color .15s}
.ch:active{transform:scale(.95)}
.ch.dy{border-color:var(--yl);color:var(--yl)}
.ch.mi{border-color:var(--rd);color:var(--rd)}
.ch .bd{font-size:10px;background:rgba(255,255,255,.08);border-radius:10px;padding:2px 8px;color:var(--tx)}
.em{text-align:center;padding:48px 24px;color:var(--mt2);font-size:14px}

/* Trade marketplace */
.mp{margin-bottom:24px}
.mp h3{font-size:14px;font-weight:700;margin-bottom:12px;display:flex;align-items:center;gap:8px}
.match-card{background:var(--s2);border:1px solid var(--bd);border-radius:14px;padding:16px;margin-bottom:10px;cursor:pointer;transition:border-color .15s}
.match-card:active{border-color:var(--t)}
.match-card .mc-users{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;gap:8px}
.match-card .mc-user{font-size:15px;font-weight:700;text-align:center;flex:1}
.match-card .mc-arrow{font-size:20px;color:var(--t);flex-shrink:0}
.match-card .mc-cards{display:flex;flex-direction:column;gap:6px}
.match-card .mc-row{display:flex;align-items:center;gap:6px;font-size:12px;color:var(--mt)}
.match-card .mc-row .bd{color:var(--yl);font-weight:700}
.match-card .mc-row .mi{color:var(--rd);font-weight:700}
.offer-card{background:var(--s1);border:1px solid var(--bd);border-radius:10px;padding:12px;margin-bottom:8px}
.offer-card .oc-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:6px}
.offer-card .oc-user{font-weight:700;font-size:14px}
.offer-card .oc-count{font-size:12px;color:var(--yl)}
.offer-card .oc-card{font-size:13px;color:var(--tx)}
.offer-card .oc-btn{margin-top:8px}

/* Trade inbox */
.tr-card{background:var(--s2);border:1px solid var(--bd);border-radius:12px;padding:14px;margin-bottom:10px}
.tr-card.tr-sent{border-left:3px solid var(--bl)}
.tr-card.tr-recv{border-left:3px solid var(--t)}
.tr-card.tr-done{border-left:3px solid var(--gn);opacity:.6}
.tr-card.tr-rej{border-left:3px solid var(--rd);opacity:.5}
.tr-card .tr-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:6px}
.tr-card .tr-who{font-size:12px;color:var(--mt)}
.tr-card .tr-who span{color:var(--tx);font-weight:700}
.tr-card .tr-status{font-size:11px;padding:3px 8px;border-radius:10px;font-weight:700;text-transform:uppercase}
.tr-card .tr-status.proposed{background:rgba(57,255,20,.15);color:var(--t)}
.tr-card .tr-status.completed{background:rgba(78,204,163,.15);color:var(--gn)}
.tr-card .tr-status.rejected{background:rgba(231,76,60,.15);color:var(--rd)}
.tr-card .tr-status.cancelled{background:rgba(102,102,102,.15);color:var(--mt)}
.tr-card .tr-status.failed{background:rgba(231,76,60,.1);color:var(--rd)}
.tr-card .tr-detail{font-size:14px;margin:8px 0;line-height:1.5}
.tr-card .tr-detail .yl{color:var(--yl);font-weight:700}
.tr-card .tr-detail .rd{color:var(--rd);font-weight:700}
.tr-card .tr-action{display:flex;gap:8px;margin-top:8px}
.tr-card .tr-date{font-size:10px;color:var(--mt2);margin-top:6px}

/* Modal */
.mo{position:fixed;inset:0;background:rgba(0,0,0,.8);z-index:100;display:none;align-items:center;justify-content:center;padding:16px}
.mo.show{display:flex}
.md{background:var(--s2);border:1px solid var(--bd);border-radius:16px;padding:24px;width:100%;max-width:340px;max-height:90vh;overflow-y:auto}
.md h2{font-size:18px;font-weight:800;margin-bottom:4px}.md h2 .t{color:var(--t)}
.md-sub{font-size:12px;color:var(--mt);margin-bottom:16px}
.md-big{font-size:52px;font-weight:900;text-align:center;margin:12px 0;color:var(--t)}
.mr{display:flex;gap:8px;justify-content:center;margin-bottom:12px}
.mb{width:52px;height:44px;border:2px solid var(--bd);background:transparent;color:var(--tx);border-radius:10px;font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-weight:700;transition:all .12s}
.mb:active{background:var(--t);color:#000;border-color:var(--t)}
.mp2{display:flex;gap:8px;margin:4px 0 12px}
.mp2 button{flex:1;padding:10px;border:1px solid var(--bd);background:transparent;color:var(--mt);border-radius:8px;font-size:12px;cursor:pointer;font-weight:600}
.mp2 button:active{border-color:var(--t);color:var(--t)}
.mc2{width:100%;padding:12px;border:1px solid var(--bd);background:transparent;color:var(--mt);border-radius:10px;font-size:13px;cursor:pointer}

/* Trade propose modal */
.tp-fields{display:flex;flex-direction:column;gap:10px;margin:12px 0}
.tp-row{display:flex;gap:8px;align-items:center}
.tp-row label{font-size:12px;color:var(--mt);width:60px;flex-shrink:0}
.tp-row select,.tp-row input[type=number]{flex:1;background:var(--s1);border:1px solid var(--bd);color:var(--tx);border-radius:8px;padding:10px;font-size:14px}
.tp-row select:focus,.tp-row input:focus{border-color:var(--t);outline:none}
.tp-preview{background:var(--s1);border-radius:10px;padding:12px;margin:8px 0;text-align:center;font-size:14px;line-height:1.8}
.tp-preview .give{color:var(--yl);font-weight:700}
.tp-preview .want{color:var(--rd);font-weight:700}

.sp{display:flex;align-items:center;justify-content:center;min-height:100vh;color:var(--mt);font-size:14px;gap:8px}
.sp.off{display:none}
.sn{animation:sp2 1s linear infinite}
@keyframes sp2{to{transform:rotate(360deg)}}
.db{background:var(--s1);border:1px solid var(--bd);color:var(--mt2);padding:10px;width:100%;border-radius:10px;font-size:12px;cursor:pointer;margin-top:20px}
.db:active{border-color:var(--rd);color:var(--rd)}
.ft{text-align:center;padding:20px;color:var(--mt2);font-size:10px;line-height:1.6}
.ft a{color:var(--mt);text-decoration:none}
</style>
</head>
<body>

<div class="aw" id="aV"><div class="ab"><div class="logo"><div style="font-size:48px">🧪</div><h1><span class="t tg">Toxic</span> Booster</h1><div class="sub">Genesis Edition · Einundzwanzig Zitadelle<br>21 Karten × 210 Stück — Track & Trade</div></div><div class="ae" id="aE"></div><div class="af"><input type="text" id="iU" placeholder="Username" autocomplete="username" autocapitalize="off" spellcheck="false"><input type="password" id="iP" placeholder="Passwort" autocomplete="current-password"><button class="btn bp" onclick="doA('login')">Einloggen</button><button class="btn bg" onclick="doA('register')">Account erstellen</button></div><div class="an">🔒 Kein Tracking. Keine E-Mail. Keine Analytics.<br>Deine Daten gehören dir. <a href="https://github.com/akamaru-claw/toxic-booster-tracker" target="_blank" rel="noopener">Open Source</a>.</div></div></div>
<div class="sp" id="sp"><span class="sn" style="display:inline-block">⚡</span> Verifiziere…</div>
<div class="app" id="aA">
<div class="tb"><div class="tb-r"><h1>🧪 <span class="t">Toxic</span> Booster</h1><div class="tb-rt"><span class="ub" id="uB"></span><button class="btn bg bs" onclick="doLo()">Logout</button></div></div><div class="sb"><div class="st"><span class="sv sg" id="sO">0</span><span class="sl">Besitzt</span></div><div class="st"><span class="sv sy" id="sD">0</span><span class="sl">Doppelt</span></div><div class="st"><span class="sv sr" id="sM">21</span><span class="sl">Fehlt</span></div><div class="st"><span class="sv sb2" id="sT">0</span><span class="sl">Gesamt</span></div></div></div>
<div class="tbr"><button class="tb2 active" data-p="overview"><span class="tn" id="tnO">0/21</span>Überblick</button><button class="tb2" data-p="market"><span class="tn">🧪</span>Tauschbörse</button><button class="tb2" data-p="inbox"><span class="tn" id="tnI">0</span>Trades</button><button class="tb2" data-p="trade"><span class="tn" id="tnT2">0</span>Tauschen</button><button class="tb2" data-p="need"><span class="tn" id="tnN">21</span>Suche</button></div>

<div class="pn active" id="p-overview"><div class="gr" id="grid"></div><button class="db" onclick="resAll()">🔄 Sammlung zurücksetzen</button><div class="ft">Toxic Booster Genesis Edition © MX12 · <a href="https://mx12.art" target="_blank" rel="noopener">mx12.art</a><br>Community Tool · <a href="https://github.com/akamaru-claw/toxic-booster-tracker" target="_blank" rel="noopener">GitHub</a></div></div>

<div class="pn" id="p-market"><div id="marketContent"><div class="em">Lade Tauschbörse…</div></div></div>

<div class="pn" id="p-inbox"><div id="inboxContent"><div class="em">Lade Trades…</div></div></div>

<div class="pn" id="p-trade"><div class="sc"><h3>📤 Doppelte zum Tauschen</h3><div class="chs" id="trC"></div></div></div>
<div class="pn" id="p-need"><div class="sc"><h3>📥 Fehlende Karten gesucht</h3><div class="chs" id="neC"></div></div></div>
</div>

<!-- Card Edit Modal -->
<div class="mo" id="cMod"><div class="md"><h2><span class="t" id="mN">#1 Satoshi</span></h2><div class="md-sub" id="mS">Bitcoin-Schöpfer · Max: 210 · Toxic Booster Genesis</div><div class="md-big" id="mC">0</div><div class="mr"><button class="mb" onclick="aj(-10)">−10</button><button class="mb" onclick="aj(-1)">−1</button><button class="mb" onclick="aj(1)">+1</button><button class="mb" onclick="aj(10)">+10</button></div><div class="mp2"><button onclick="sC(0)">0</button><button onclick="sC(1)">1</button><button onclick="sC(5)">5</button><button onclick="sC(210)">MAX</button></div><button class="mc2" onclick="clM()">Schließen</button></div></div>

<!-- Trade Propose Modal -->
<div class="mo" id="tMod"><div class="md"><h2>🧪 <span class="t">Tausch</span> vorschlagen</h2><div class="md-sub" id="tSub"></div><div class="tp-fields"><div class="tp-row"><label>Ich biete</label><select id="tpOffer" onchange="updTP()"></select></div><div class="tp-row"><label style="width:40px">×</label><input type="number" id="tpOfferN" value="1" min="1" max="210" onchange="updTP()"></div><div class="tp-row"><label>Ich will</label><select id="tpWant" onchange="updTP()"></select></div><div class="tp-row"><label style="width:40px">×</label><input type="number" id="tpWantN" value="1" min="1" max="210" onchange="updTP()"></div></div><div class="tp-preview" id="tpPrev"></div><div class="ae" id="tpErr"></div><div class="mr"><button class="btn bp" style="flex:1" onclick="sendTrade()">Vorschlagen</button></div><button class="mc2" onclick="clTM()">Abbrechen</button></div></div>

<script>
const Au='auth_api.php',Ca='cards_api.php',Tr='trade_api.php',N=21,MX=210;
const CARDS=[
{n:1,name:'Satoshi',desc:'Bitcoin-Schöpfer',emoji:'₿'},
{n:2,name:'HODL',desc:'Halten',emoji:'🤲'},
{n:3,name:'Pleb',desc:'Normie',emoji:'🧑'},
{n:4,name:'DIP',desc:'Crash',emoji:'📉'},
{n:5,name:'Bear',desc:'Bärenmarkt',emoji:'🐻'},
{n:6,name:'Bull',desc:'Bullenmarkt',emoji:'🐂'},
{n:7,name:'Bag',desc:'Bagholder',emoji:'👜'},
{n:8,name:'Rekt',desc:'Liquidiert',emoji:'💀'},
{n:9,name:'Sats',desc:'Satoshis',emoji:'⚡'},
{n:10,name:'Node',desc:'Full Node',emoji:'🖥️'},
{n:11,name:'Fork',desc:'Hard Fork',emoji:'🔀'},
{n:12,name:'Hash',desc:'Hashrate',emoji:'⛏️'},
{n:13,name:'Block',desc:'Blockchain',emoji:'🧱'},
{n:14,name:'Fiat',desc:'Papierdollar',emoji:'💵'},
{n:15,name:'Seed',desc:'Seed Phrase',emoji:'🌱'},
{n:16,name:'Pump',desc:'Pumpe',emoji:'🚀'},
{n:17,name:'Dump',desc:'Dump',emoji:'⬇️'},
{n:18,name:'Whale',desc:'Wal',emoji:'🐋'},
{n:19,name:'FOMO',desc:'Angst zu verpassen',emoji:'😱'},
{n:20,name:'NGU',desc:'Number Go Up',emoji:'📈'},
{n:21,name:'Toxic',desc:'Toxic Booster',emoji:'🧪'}
];
let tk=localStorage.getItem('tb_tk')||'',un=localStorage.getItem('tb_un')||'';
let cards=Array(N).fill(0),ac=null,sv=null,tradeTarget=null,tradeTargetName='';
let marketData=null,inboxData=null;

async function api(u,d){const r=await fetch(u,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(d)});return r.json()}
function show(v){document.getElementById('aV').style.display='none';document.getElementById('sp').classList.add('off');document.getElementById('aA').classList.remove('show');if(v==='auth')document.getElementById('aV').style.display='flex';else{document.getElementById('aA').classList.add('show');document.getElementById('uB').textContent=un}}

async function doA(act){const u=document.getElementById('iU').value.trim(),p=document.getElementById('iP').value;if(!u||!p){document.getElementById('aE').textContent='Username & Passwort eingeben';return}const r=await api(Au,{action:act,username:u,password:p});if(!r.ok){document.getElementById('aE').textContent=r.error;return}tk=r.token;un=r.username;localStorage.setItem('tb_tk',tk);localStorage.setItem('tb_un',un);document.getElementById('aE').textContent='';await loadC();show('app');render()}
async function doLo(){if(tk)await api(Au,{action:'logout',token:tk});tk='';un='';localStorage.removeItem('tb_tk');localStorage.removeItem('tb_un');cards=Array(N).fill(0);show('auth')}
async function init(){if(!tk){show('auth');return}const r=await api(Au,{action:'verify',token:tk});if(!r.ok){tk='';localStorage.removeItem('tb_tk');localStorage.removeItem('tb_un');show('auth');return}un=r.username;localStorage.setItem('tb_un',un);await loadC();show('app');render()}
async function loadC(){const r=await api(Ca,{action:'load',token:tk});if(r.ok&&r.cards)cards=r.cards}
function saveC(){clearTimeout(sv);sv=setTimeout(async()=>{await api(Ca,{action:'save',token:tk,cards})},400)}

function render(){
  const g=document.getElementById('grid');g.innerHTML='';let o=0,d=0,m=0,t=0;
  for(let i=0;i<N;i++){const n=i+1,v=cards[i],pct=v/MX*100;let cls=v===0?'s0':v===1?'s1':v>=MX?'sm':'sd';if(v===0)m++;else{o++;if(v>1)d+=v-1;t+=v}
  const cd=CARDS[i],el=document.createElement('div');el.className='c '+cls;
  el.innerHTML=`<div class="c-flash" id="fl${i}"></div><div class="c-emoji">${cd.emoji}</div><div class="cn">#${n}</div><div class="c-name">${cd.name}</div><div class="cc">${v||'—'}</div><div class="ci">${v>1?(v-1)+'× doppelt':v===1?'✓':'fehlte'}</div><div class="cb"><div class="cf" style="width:${Math.min(pct,100)}%"></div></div><div class="c-hold-hint" id="hh${i}">−1</div>`;
  setupCardTouch(el,i);g.appendChild(el)}
  document.getElementById('sO').textContent=o;document.getElementById('sD').textContent=d;document.getElementById('sM').textContent=m;document.getElementById('sT').textContent=t;
  document.getElementById('tnO').textContent=o+'/21';document.getElementById('tnT2').textContent=d;document.getElementById('tnN').textContent=m;

  const tc=document.getElementById('trC');tc.innerHTML='';let ht=false;
  for(let i=0;i<N;i++){if(cards[i]>1){ht=true;const cd=CARDS[i];const ch=document.createElement('div');ch.className='ch dy';ch.innerHTML=`${cd.emoji} #${i+1} ${cd.name} <span class="bd">${cards[i]-1}×</span>`;ch.onclick=()=>openM(i);tc.appendChild(ch)}
}if(!ht)tc.innerHTML='<div class="em">Noch keine doppelten Karten 🤷</div>';

  const nc=document.getElementById('neC');nc.innerHTML='';let hm=false;
  for(let i=0;i<N;i++){if(cards[i]===0){hm=true;const cd=CARDS[i];const ch=document.createElement('div');ch.className='ch mi';ch.innerHTML=`${cd.emoji} #${i+1} ${cd.name}`;ch.onclick=()=>openM(i);nc.appendChild(ch)}
}if(!hm)nc.innerHTML='<div class="em">Vollständig! 🧪🎉</div>';

  loadInbox();
}

// === MARKETPLACE ===
async function loadMarket(){
  const r=await api(Tr,{action:'browse',token:tk});
  if(!r.ok){document.getElementById('marketContent').innerHTML='<div class="em">Fehler beim Laden</div>';return}
  marketData=r;
  let html='';

  // Matches
  if(r.matches&&r.matches.length>0){
    html+='<div class="mp"><h3>⚡ Perfect Matches</h3>';
    r.matches.forEach(m=>{
      const isMe=m.user_a_uid===getDbUid()||m.user_b_uid===getDbUid();
      const otherUid=isMe?(m.user_a_uid===getDbUid()?m.user_b_uid:m.user_a_uid):m.user_b_uid;
      const otherName=isMe?(m.user_a_uid===getDbUid()?m.user_b:m.user_a):m.user_b;
      const myGives=isMe?(m.user_a_uid===getDbUid()?m.a_gives:m.b_gives):[];
      const myWants=isMe?(m.user_a_uid===getDbUid()?m.b_gives:m.a_gives):[];
      
      if(isMe){
        html+=`<div class="match-card" onclick="openTradeTo(${otherUid},'${esc(otherName)}')"><div class="mc-users"><div class="mc-user">${esc(m.user_a)}</div><div class="mc-arrow">⟷</div><div class="mc-user">${esc(m.user_b)}</div></div><div class="mc-cards">`;
        m.a_gives.forEach(c=>{const cd=CARDS[c-1]||{emoji:'?',name:'#'+c};html+=`<div class="mc-row">${esc(m.user_a)} gibt <span class="bd">${cd.emoji} #${c} ${cd.name}</span> → ${esc(m.user_b)}</div>`});
        m.b_gives.forEach(c=>{const cd=CARDS[c-1]||{emoji:'?',name:'#'+c};html+=`<div class="mc-row">${esc(m.user_b)} gibt <span class="bd">${cd.emoji} #${c} ${cd.name}</span> → ${esc(m.user_a)}</div>`});
        html+=`<div class="mc-row" style="color:var(--t);margin-top:4px">👆 Tippen um Tausch vorzuschlagen</div>`;
        html+=`</div></div>`;
      } else {
        html+=`<div class="match-card" onclick="openTradeTo(${otherUid},'${esc(otherName)}')"><div class="mc-users"><div class="mc-user">${esc(m.user_a)}</div><div class="mc-arrow">⟷</div><div class="mc-user">${esc(m.user_b)}</div></div><div class="mc-cards">`;
        m.a_gives.forEach(c=>{const cd=CARDS[c-1]||{emoji:'?',name:'#'+c};html+=`<div class="mc-row">${esc(m.user_a)} hat <span class="bd">${cd.emoji} #${c} ${cd.name}</span> doppelt</div>`});
        m.b_gives.forEach(c=>{const cd=CARDS[c-1]||{emoji:'?',name:'#'+c};html+=`<div class="mc-row">${esc(m.user_b)} hat <span class="bd">${cd.emoji} #${c} ${cd.name}</span> doppelt</div>`});
        html+=`</div></div>`;
      }
    });
    html+='</div>';
  }

  // Offers by card
  if(r.offers&&r.offers.length>0){
    html+='<div class="mp"><h3>📤 Angebote (doppelte Karten)</h3>';
    r.offers.forEach(o=>{
      const cd=CARDS[o.card-1]||{emoji:'?',name:'#'+o.card};
      html+=`<div class="offer-card"><div class="oc-header"><span class="oc-card">${cd.emoji} #${o.card} ${cd.name}</span><span class="oc-count">${o.users.length} Anbieter</span></div>`;
      o.users.forEach(u=>{
        if(u.uid!==getDbUid()){
          html+=`<div style="font-size:12px;margin:4px 0;display:flex;justify-content:space-between;align-items:center"><span>${esc(u.username)} — <span style="color:var(--yl)">${u.available}×</span></span><button class="btn bg" style="padding:4px 10px;font-size:10px" onclick="openTradeTo(${u.uid},'${esc(u.username)}')">${u.available>1?'Tauschen':'Anfragen'}</button></div>`;
        }
      });
      html+=`</div>`;
    });
    html+='</div>';
  }

  // Needs by card
  if(r.needs&&r.needs.length>0){
    html+='<div class="mp"><h3>📥 Gesuche (fehlende Karten)</h3>';
    r.needs.forEach(n=>{
      const cd=CARDS[n.card-1]||{emoji:'?',name:'#'+n.card};
      html+=`<div class="offer-card"><div class="oc-header"><span class="oc-card">${cd.emoji} #${n.card} ${cd.name}</span><span class="oc-count" style="color:var(--rd)">${n.users.length} Suchende</span></div>`;
      n.users.forEach(u=>{
        if(u.uid!==getDbUid()){
          html+=`<div style="font-size:12px;margin:4px 0;display:flex;justify-content:space-between;align-items:center"><span>${esc(u.username)}</span>`;
          // Can I help? Check if I have dups of this card
          const wantCd=CARDS[n.card-1]||{emoji:'?',name:'#'+n.card};
          if(cards[n.card-1]>1){
            html+=`<button class="btn bp" style="padding:4px 10px;font-size:10px" onclick="openTradeTo(${u.uid},'${esc(u.username)}',${n.card})">🎁 Bieten</button>`;
          }
          html+=`</div>`;
        }
      });
      html+=`</div>`;
    });
    html+='</div>';
  }

  if(!html)html='<div class="em">Noch keine anderen Sammler aktiv 🧪<br><br>Tipp: Teile den Link mit anderen Einundzwanzigern!</div>';
  document.getElementById('marketContent').innerHTML=html;
}

// === TRADE INBOX ===
async function loadInbox(){
  const r=await api(Tr,{action:'my-trades',token:tk});
  if(!r.ok)return;
  inboxData=r.trades;
  let pending=0;
  r.trades.received.forEach(t=>{if(t.status==='proposed')pending++});
  document.getElementById('tnI').textContent=pending||'';

  let html='';
  // Received
  if(r.trades.received.length>0){
    html+='<div class="sc"><h3>📥 Erhaltene Vorschläge</h3>';
    r.trades.received.forEach(t=>{
      const cls=t.status==='proposed'?'tr-recv':t.status==='completed'?'tr-done':t.status==='rejected'?'tr-rej':'tr-recv';
      html+=`<div class="tr-card ${cls}"><div class="tr-top"><div class="tr-who">von <span>${esc(t.proposer)}</span></div><div class="tr-status ${t.status}">${statusLabel(t.status)}</div></div><div class="tr-detail"><span class="yl">${esc(t.proposer)} gibt ${(()=>{
        const cd=CARDS[t.offer_card-1]||{emoji:'?',name:'#'+t.offer_card};
        return cd.emoji+' '+cd.name;
      })()} #${t.offer_card}${t.offer_count>1?' ×'+t.offer_count:''}</span><br>⟷<br><span class="rd">Du gibst ${(()=>{
        const cd=CARDS[t.want_card-1]||{emoji:'?',name:'#'+t.want_card};
        return cd.emoji+' '+cd.name;
      })()} #${t.want_card}${t.want_count>1?' ×'+t.want_count:''}</span></div>`;
      if(t.status==='proposed'){
        html+=`<div class="tr-action"><button class="btn bp" style="flex:1" onclick="respondTrade(${t.id},'accepted')">✅ Annehmen</button><button class="btn bg" style="flex:1" onclick="respondTrade(${t.id},'rejected')">❌ Ablehnen</button></div>`;
      }
      html+=`<div class="tr-date">${t.proposed_at}${t.responded_at?' · '+t.responded_at:''}</div></div>`;
    });
    html+='</div>';
  }

  // Sent
  if(r.trades.sent.length>0){
    html+='<div class="sc"><h3>📤 Gesendete Vorschläge</h3>';
    r.trades.sent.forEach(t=>{
      const cls=t.status==='proposed'?'tr-sent':t.status==='completed'?'tr-done':t.status==='rejected'?'tr-rej':'tr-sent';
      html+=`<div class="tr-card ${cls}"><div class="tr-top"><div class="tr-who">an <span>${esc(t.receiver)}</span></div><div class="tr-status ${t.status}">${statusLabel(t.status)}</div></div><div class="tr-detail"><span class="yl">Du gibst ${(()=>{
        const cd=CARDS[t.offer_card-1]||{emoji:'?',name:'#'+t.offer_card};
        return cd.emoji+' '+cd.name;
      })()} #${t.offer_card}${t.offer_count>1?' ×'+t.offer_count:''}</span><br>⟷<br><span class="rd">Du willst ${(()=>{
        const cd=CARDS[t.want_card-1]||{emoji:'?',name:'#'+t.want_card};
        return cd.emoji+' '+cd.name;
      })()} #${t.want_card}${t.want_count>1?' ×'+t.want_count:''}</span></div>`;
      if(t.status==='proposed'){
        html+=`<div class="tr-action"><button class="btn bg" style="flex:1" onclick="cancelTrade(${t.id})">Zurückziehen</button></div>`;
      }
      html+=`<div class="tr-date">${t.proposed_at}${t.responded_at?' · '+t.responded_at:''}</div></div>`;
    });
    html+='</div>';
  }

  if(!html)html='<div class="em">Keine Trades bisher 🧪<br><br>Geh in die Tauschbörse und schlage einen Tausch vor!</div>';
  document.getElementById('inboxContent').innerHTML=html;
}

function statusLabel(s){return{proposed:'⏳ Offen',completed:'✅ Erledigt',rejected:'❌ Abgelehnt',cancelled:'🔙 Zurückgezogen',failed:'💥 Fehlgeschlagen'}[s]||s}
function esc(s){const d=document.createElement('div');d.textContent=s;return d.innerHTML}
function getDbUid(){/* We don't store uid, derive from verify if needed */return -1}

function vib(ms){try{if(navigator.vibrate)navigator.vibrate(ms)}catch(e){}}
function setupCardTouch(el,i){
  let timer=null,startY=0,moved=false,longFired=false,lastTap=0;
  const HOLD=400;
  // Touch: tap=+1, hold=-1, double-tap=modal
  el.addEventListener('touchstart',(e)=>{
    moved=false;longFired=false;
    startY=e.touches[0].clientY;
    timer=setTimeout(()=>{longFired=true;if(cards[i]>0){cards[i]=Math.max(0,cards[i]-1);saveC();flash(i,'dn');vib(30);render()}},HOLD);
  },{passive:true});
  el.addEventListener('touchmove',(e)=>{if(Math.abs(e.touches[0].clientY-startY)>8){moved=true;if(timer){clearTimeout(timer);timer=null}}},{passive:true});
  el.addEventListener('touchend',(e)=>{
    e.preventDefault(); // kills synthetic click/mouseup
    if(timer){clearTimeout(timer);timer=null}
    if(longFired||moved)return;
    const now=Date.now();
    if(now-lastTap<350){openM(i);lastTap=0;return}
    lastTap=now;
    if(cards[i]<MX){cards[i]=Math.min(MX,cards[i]+1);saveC();flash(i,'up');vib(10);render()}
  });
  el.addEventListener('touchcancel',()=>{if(timer){clearTimeout(timer);timer=null}});
  // Desktop: click=+1, dblclick=modal, contextmenu=nothing
  el.addEventListener('click',(e)=>{
    if(e.sourceCapabilities&&e.sourceCapabilities.firesTouchEvents)return; // Chrome
    if(cards[i]<MX){cards[i]=Math.min(MX,cards[i]+1);saveC();flash(i,'up');render()}
  });
  el.addEventListener('dblclick',()=>{openM(i)});
  el.addEventListener('contextmenu',(e)=>{e.preventDefault()});
}
function flash(i,dir){
  const fl=document.getElementById('fl'+i);if(!fl)return;
  fl.className='c-flash '+dir+' show';
  requestAnimationFrame(()=>requestAnimationFrame(()=>{fl.classList.remove('show')}));
}

async function respondTrade(id,resp){
  const r=await api(Tr,{action:'respond',token:tk,trade_id:id,response:resp});
  if(r.ok){await loadC();render();loadInbox();loadMarket()}else{alert(r.error)}
}
async function cancelTrade(id){
  const r=await api(Tr,{action:'cancel',token:tk,trade_id:id});
  if(r.ok){loadInbox()}else{alert(r.error)}
}

// === TRADE PROPOSE MODAL ===
function openTradeTo(uid,name,prefillWant){
  tradeTarget=uid;tradeTargetName=name;
  document.getElementById('tSub').textContent=`Tausch mit ${name}`;
  // Populate offer dropdown with cards I have duplicates of
  const oSel=document.getElementById('tpOffer');oSel.innerHTML='';
  for(let i=0;i<N;i++){if(cards[i]>1){const cd=CARDS[i];const o=document.createElement('option');o.value=i+1;o.textContent=`${cd.emoji} #${i+1} ${cd.name} (${cards[i]-1}× doppelt)`;oSel.appendChild(o)}}
  // Populate want dropdown with cards I need
  const wSel=document.getElementById('tpWant');wSel.innerHTML='';
  for(let i=0;i<N;i++){if(cards[i]===0){const cd=CARDS[i];const o=document.createElement('option');o.value=i+1;o.textContent=`${cd.emoji} #${i+1} ${cd.name} (fehlt)`;wSel.appendChild(o)}}
  if(prefillWant)wSel.value=prefillWant;
  document.getElementById('tpOfferN').value=1;document.getElementById('tpWantN').value=1;
  document.getElementById('tpErr').textContent='';
  updTP();
  document.getElementById('tMod').classList.add('show');
}

function updTP(){
  const oc=document.getElementById('tpOffer').value,wc=document.getElementById('tpWant').value;
  const cdO=CARDS[oc-1]||{emoji:'?',name:'?'},cdW=CARDS[wc-1]||{emoji:'?',name:'?'};
  const on=document.getElementById('tpOfferN').value,wn=document.getElementById('tpWantN').value;
  document.getElementById('tpPrev').innerHTML=`Du gibst <span class="give">${cdO.emoji} ${cdO.name} × ${on}</span><br>⟷<br>Du willst <span class="want">${cdW.emoji} ${cdW.name} × ${wn}</span>`;
}

async function sendTrade(){
  const oc=parseInt(document.getElementById('tpOffer').value),wc=parseInt(document.getElementById('tpWant').value);
  const on=parseInt(document.getElementById('tpOfferN').value),wn=parseInt(document.getElementById('tpWantN').value);
  const r=await api(Tr,{action:'propose',token:tk,receiver_id:tradeTarget,offer_card:oc,offer_count:on,want_card:wc,want_count:wn});
  if(!r.ok){document.getElementById('tpErr').textContent=r.error;return}
  clTM();loadInbox();
  alert('Tausch vorgeschlagen! ✅');
}

function clTM(){document.getElementById('tMod').classList.remove('show');tradeTarget=null}

// === CARD MODAL ===
function openM(i){ac=i;const cd=CARDS[i];document.getElementById('mN').textContent=`#${i+1} ${cd.name}`;document.getElementById('mS').textContent=`${cd.desc} · Max: 210 · Toxic Booster Genesis`;document.getElementById('mC').textContent=cards[i];document.getElementById('cMod').classList.add('show')}
function aj(d){if(ac===null)return;cards[ac]=Math.max(0,Math.min(MX,cards[ac]+d));document.getElementById('mC').textContent=cards[ac];saveC();render()}
function sC(v){if(ac===null)return;cards[ac]=Math.max(0,Math.min(MX,v));document.getElementById('mC').textContent=cards[ac];saveC();render()}
function clM(){document.getElementById('cMod').classList.remove('show');ac=null}
function resAll(){if(!confirm('Alle Karten auf 0 setzen?'))return;cards.fill(0);saveC();render()}

// Events
document.getElementById('cMod').addEventListener('click',e=>{if(e.target.id==='cMod')clM()});
document.getElementById('tMod').addEventListener('click',e=>{if(e.target.id==='tMod')clTM()});
document.querySelectorAll('.tb2').forEach(b=>b.addEventListener('click',()=>{
  document.querySelectorAll('.tb2').forEach(t=>t.classList.remove('active'));
  document.querySelectorAll('.pn').forEach(p=>p.classList.remove('active'));
  b.classList.add('active');
  const pn=document.getElementById('p-'+b.dataset.p);
  pn.classList.add('active');
  if(b.dataset.p==='market')loadMarket();
  if(b.dataset.p==='inbox')loadInbox();
}));
document.getElementById('iP').addEventListener('keydown',e=>{if(e.key==='Enter')doA('login')});
document.getElementById('iU').addEventListener('keydown',e=>{if(e.key==='Enter')document.getElementById('iP').focus()});
document.addEventListener('keydown',e=>{if(document.getElementById('cMod').classList.contains('show')){if(e.key==='Escape')clM();if(e.key==='+'||e.key==='=')aj(1);if(e.key==='-')aj(-1)}});

init();
</script>
</body>
</html>