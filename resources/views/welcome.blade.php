<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<title>QLine — No More Crowded Counters</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
  background: #09090a;
  color: #ededea;
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-weight: 400;
  line-height: 1.6;
  overflow-x: hidden;
}

/* ── NAV ── */
nav {
  position: fixed; top: 0; left: 0; right: 0; z-index: 100;
  display: flex; align-items: center; justify-content: space-between;
  padding: 1.1rem 2.5rem;
  background: rgba(9,9,10,0.88);
  backdrop-filter: blur(20px);
  border-bottom: 1px solid rgba(255,255,255,0.06);
}
.logo { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.4rem; color: #ededea; text-decoration: none; letter-spacing: -0.02em; }
.logo em { font-style: normal; color: #14B8A6; }
.nav-r { display: flex; align-items: center; gap: 1.5rem; }
.nav-r a { color: #888; font-size: 0.875rem; text-decoration: none; transition: color .2s; }
.nav-r a:hover { color: #ededea; }
.nav-btn {
  background: #14B8A6; color: #000;
  padding: 0.52rem 1.3rem; border-radius: 100px;
  font-size: 0.85rem; font-weight: 600; text-decoration: none;
  transition: filter .2s, transform .15s;
}
.nav-btn:hover { filter: brightness(1.1); transform: scale(1.02); color: #000; }

/* ── HERO ── */
.hero {
  min-height: 100vh;
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  text-align: center;
  padding: 9rem 1.5rem 5rem;
  position: relative; overflow: hidden;
}
.hero::before {
  content: '';
  position: absolute; top: -20%; left: 50%; transform: translateX(-50%);
  width: 900px; height: 700px;
  background: radial-gradient(ellipse, rgba(37,211,102,0.08) 0%, transparent 65%);
  pointer-events: none;
}
.hero-badge {
  display: inline-flex; align-items: center; gap: 0.5rem;
  background: rgba(37,211,102,0.08); border: 1px solid rgba(37,211,102,0.2);
  color: #14B8A6; font-size: 0.8rem; font-weight: 500;
  padding: 0.35rem 1.1rem; border-radius: 100px;
  margin-bottom: 2.25rem;
  animation: up .5s ease both;
}
.badge-dot { width: 6px; height: 6px; border-radius: 50%; background: #14B8A6; animation: pulse 1.8s infinite; }
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.2} }

h1 {
  font-family: 'Syne', sans-serif; font-weight: 800;
  font-size: clamp(2.8rem, 8vw, 6.2rem);
  line-height: 1.0; letter-spacing: -0.035em;
  margin-bottom: 1.75rem;
  animation: up .5s .08s ease both;
}
h1 .g { color: #14B8A6; }
h1 .dim { color: #555; }

.hero-desc {
  font-size: clamp(1rem, 2vw, 1.18rem);
  color: #888; font-weight: 300;
  max-width: 560px; margin: 0 auto 3rem;
  line-height: 1.75;
  animation: up .5s .16s ease both;
}

.cta-row {
  display: flex; align-items: center; justify-content: center; gap: 1rem; flex-wrap: wrap;
  animation: up .5s .22s ease both;
}
.cta-main {
  display: inline-flex; align-items: center; gap: 0.6rem;
  background: #14B8A6; color: #000;
  padding: 1rem 2.25rem; border-radius: 100px;
  font-weight: 600; font-size: 1rem; text-decoration: none;
  box-shadow: 0 0 48px rgba(37,211,102,.25);
  transition: filter .2s, transform .15s, box-shadow .2s;
}
.cta-main:hover { filter: brightness(1.09); transform: translateY(-2px); box-shadow: 0 0 72px rgba(37,211,102,.38); }
.cta-sec {
  display: inline-flex; align-items: center; gap: 0.4rem;
  border: 1px solid rgba(255,255,255,.12); color: #ededea;
  padding: 1rem 2rem; border-radius: 100px;
  font-size: 1rem; text-decoration: none;
  transition: border-color .2s, background .2s;
}
.cta-sec:hover { border-color: rgba(255,255,255,.22); background: rgba(255,255,255,.03); }
.arr { transition: transform .2s; display: inline-block; }
.cta-sec:hover .arr { transform: translateX(4px); }

/* SOCIAL PROOF STRIP */
.proof {
  margin-top: 4rem; padding: 1.25rem 2rem;
  background: rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.07);
  border-radius: 16px;
  display: flex; align-items: center; justify-content: center; gap: 2.5rem; flex-wrap: wrap;
  animation: up .5s .3s ease both;
  max-width: 680px;
}
.proof-item { text-align: center; }
.proof-num { font-family: 'Syne', sans-serif; font-size: 1.8rem; font-weight: 800; color: #14B8A6; line-height: 1; }
.proof-label { font-size: 0.72rem; color: #666; margin-top: 0.2rem; text-transform: uppercase; letter-spacing: .08em; }
.proof-div { width: 1px; height: 36px; background: rgba(255,255,255,.08); }

@keyframes up { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }

/* ── PROBLEM SECTION ── */
.problem { padding: 7rem 1.5rem; text-align: center; }
.problem .inner { max-width: 820px; margin: 0 auto; }
.tag { display: inline-block; font-size: 0.7rem; font-weight: 600; letter-spacing: .14em; text-transform: uppercase; color: #14B8A6; margin-bottom: 1rem; }
.big { font-family: 'Syne', sans-serif; font-weight: 800; font-size: clamp(1.8rem,4vw,2.8rem); line-height: 1.1; letter-spacing: -.02em; margin-bottom: 1.25rem; }
.sub { color: #777; font-size: 1rem; font-weight: 300; max-width: 520px; margin: 0 auto; line-height: 1.75; }

.pain-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 1px; background: rgba(255,255,255,.06); border-radius: 20px; overflow: hidden; margin-top: 4rem; }
.pain { background: #111; padding: 2rem 1.75rem; text-align: left; }
.pain-emoji { font-size: 2rem; margin-bottom: 1rem; }
.pain h3 { font-size: 0.95rem; font-weight: 600; margin-bottom: 0.5rem; }
.pain p { font-size: 0.82rem; color: #666; line-height: 1.6; }

/* ── HOW ── */
.how { padding: 7rem 1.5rem; background: #0d0d0e; }
.how .inner { max-width: 1040px; margin: 0 auto; }
.how-head { text-align: center; margin-bottom: 4rem; }

.steps-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 1.5rem; }
.step {
  background: #111; border: 1px solid rgba(255,255,255,.07);
  border-radius: 20px; padding: 2rem 1.5rem;
  position: relative; overflow: hidden;
  transition: border-color .3s, transform .3s;
}
.step:hover { border-color: rgba(37,211,102,.25); transform: translateY(-4px); }
.step-n {
  font-family: 'Syne', sans-serif; font-size: 4.5rem; font-weight: 800;
  color: rgba(37,211,102,.07); position: absolute;
  top: -0.5rem; right: 0.75rem; line-height: 1; pointer-events: none;
}
.step-icon { font-size: 1.75rem; margin-bottom: 1.25rem; }
.step h3 { font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; }
.step p { font-size: 0.82rem; color: #666; line-height: 1.6; }

/* CONNECTOR DOTS */
.step::after {
  content: '→';
  position: absolute; right: -1rem; top: 50%; transform: translateY(-50%);
  font-size: 1rem; color: #333; z-index: 2;
}
.step:last-child::after { display: none; }

/* ── DEMO CHAT ── */
.demo { padding: 7rem 1.5rem; }
.demo .inner { max-width: 1040px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr; gap: 5rem; align-items: center; }
.demo-left h2 { font-family: 'Syne', sans-serif; font-weight: 800; font-size: clamp(1.8rem,3.5vw,2.5rem); letter-spacing: -.02em; line-height: 1.15; margin-bottom: 1.25rem; }
.demo-left p { color: #777; font-size: 0.95rem; line-height: 1.75; margin-bottom: 2rem; }
.check { display: flex; align-items: center; gap: 0.6rem; font-size: 0.9rem; color: #aaa; margin-bottom: 0.65rem; }
.check::before { content: '✓'; color: #14B8A6; font-weight: 700; flex-shrink: 0; font-size: 0.85rem; }

.phone-wrap { display: flex; justify-content: center; }
.phone {
  width: 280px;
  background: #111; border: 1px solid rgba(255,255,255,.08);
  border-radius: 28px; overflow: hidden;
  box-shadow: 0 32px 80px rgba(0,0,0,.5);
}
.ph { background: #075e54; padding: 1rem 1.1rem; display: flex; align-items: center; gap: 0.7rem; }
.ph-av { width: 36px; height: 36px; border-radius: 50%; background: #14B8A6; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 800; color: #000; }
.ph-name { font-size: 0.85rem; font-weight: 600; color: #fff; }
.ph-status { font-size: 0.65rem; color: rgba(255,255,255,.55); }
.pb { padding: 0.875rem; display: flex; flex-direction: column; gap: 0.55rem; background: #0b1a0d; }
.bbl { padding: 0.55rem 0.8rem; border-radius: 14px; font-size: 0.72rem; line-height: 1.5; max-width: 86%; }
.bi { background: #212121; color: #ededea; align-self: flex-start; border-bottom-left-radius: 4px; }
.bo { background: #006144; color: #fff; align-self: flex-end; border-bottom-right-radius: 4px; }
.bs { background: rgba(37,211,102,.1); color: #14B8A6; border: 1px solid rgba(37,211,102,.15); border-radius: 10px; align-self: center; font-size: 0.68rem; text-align: center; max-width: 100%; }
.bt { font-size: 0.58rem; color: #444; align-self: flex-end; }

/* ── BENEFITS ── */
.benefits { padding: 7rem 1.5rem; background: #0d0d0e; }
.benefits .inner { max-width: 1040px; margin: 0 auto; }
.ben-head { text-align: center; margin-bottom: 4rem; }
.ben-grid { display: grid; grid-template-columns: 1.4fr 1fr; gap: 1.25rem; }
.ben-big {
  background: #111; border: 1px solid rgba(37,211,102,.18);
  border-radius: 24px; padding: 3rem;
  grid-row: span 2;
  display: flex; flex-direction: column; justify-content: flex-end;
}
.ben-big .quote {
  font-family: 'Syne', sans-serif; font-size: 5.5rem; font-weight: 800;
  color: #14B8A6; line-height: 1; margin-bottom: 0.5rem;
}
.ben-big h3 { font-family: 'Syne', sans-serif; font-size: 1.4rem; font-weight: 700; margin-bottom: 0.75rem; }
.ben-big p { color: #666; font-size: 0.88rem; line-height: 1.7; }
.ben-sm {
  background: #111; border: 1px solid rgba(255,255,255,.07);
  border-radius: 20px; padding: 2rem;
  transition: border-color .3s;
}
.ben-sm:hover { border-color: rgba(37,211,102,.18); }
.ben-icon { font-size: 1.5rem; margin-bottom: 0.875rem; }
.ben-sm h3 { font-size: 0.95rem; font-weight: 600; margin-bottom: 0.4rem; }
.ben-sm p { font-size: 0.82rem; color: #666; line-height: 1.6; }

/* ── PRICING ── */
.pricing { padding: 7rem 1.5rem; }
.pricing .inner { max-width: 780px; margin: 0 auto; text-align: center; }
.price-note-top { color: #666; font-size: 0.9rem; margin-bottom: 3rem; }
.pc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; text-align: left; margin-top: 3rem; }
.pc {
  background: #111; border: 1px solid rgba(255,255,255,.08);
  border-radius: 24px; padding: 2.75rem;
  transition: transform .3s, border-color .3s;
}
.pc:hover { transform: translateY(-4px); }
.pc.star { border-color: rgba(37,211,102,.3); background: linear-gradient(145deg,#111,#0b1a0f); position: relative; }
.star-label { position: absolute; top: -1px; left: 50%; transform: translateX(-50%); background: #14B8A6; color: #000; font-size: 0.65rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; padding: .3rem 1.1rem; border-radius: 0 0 12px 12px; }
.pc-label { font-size: 0.7rem; font-weight: 600; letter-spacing: .12em; text-transform: uppercase; color: #555; margin-bottom: 0.75rem; }
.pc-who { font-size: 0.82rem; color: #666; margin-bottom: 1.5rem; line-height: 1.5; }
.pc-price { font-family: 'Syne', sans-serif; font-size: 3.2rem; font-weight: 800; color: #14B8A6; line-height: 1; margin-bottom: 0.2rem; }
.pc-price sup { font-size: 1.2rem; vertical-align: top; margin-top: .4rem; }
.pc-per { font-size: 0.78rem; color: #555; margin-bottom: 1.5rem; }
.pc-limit { display: inline-block; background: rgba(37,211,102,.08); border: 1px solid rgba(37,211,102,.15); color: #14B8A6; font-size: 0.72rem; font-weight: 500; padding: .25rem .85rem; border-radius: 100px; margin-bottom: 1.75rem; }
.pc-btn { display: block; text-align: center; padding: .875rem; border-radius: 100px; font-size: .9rem; font-weight: 600; text-decoration: none; border: 1px solid rgba(255,255,255,.12); color: #ededea; transition: background .2s, border-color .2s; }
.pc-btn:hover { background: rgba(255,255,255,.05); border-color: rgba(255,255,255,.2); }
.pc.star .pc-btn { background: #14B8A6; border-color: #14B8A6; color: #000; }
.pc.star .pc-btn:hover { filter: brightness(1.08); }
.pricing-sub { margin-top: 1.5rem; font-size: 0.78rem; color: #444; font-style: italic; }

/* ── TESTIMONIALS ── */
.testimonials { padding: 7rem 1.5rem; background: #0d0d0e; }
.testimonials .inner { max-width: 1040px; margin: 0 auto; }
.t-head { text-align: center; margin-bottom: 4rem; }
.t-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 1.25rem; }
.tc {
  background: #111; border: 1px solid rgba(255,255,255,.07);
  border-radius: 20px; padding: 2rem;
  transition: border-color .3s;
}
.tc:hover { border-color: rgba(37,211,102,.18); }
.stars { color: #14B8A6; font-size: 0.8rem; letter-spacing: 2px; margin-bottom: 1rem; }
.tc-q { font-size: 0.9rem; color: #aaa; line-height: 1.7; margin-bottom: 1.5rem; font-style: italic; }
.tc-a { display: flex; align-items: center; gap: 0.7rem; }
.tc-av { width: 38px; height: 38px; border-radius: 50%; background: rgba(37,211,102,.1); border: 1px solid rgba(37,211,102,.2); display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; color: #14B8A6; }
.tc-name { font-size: 0.875rem; font-weight: 600; }
.tc-biz { font-size: 0.75rem; color: #555; }

/* ── FINAL CTA ── */
.final { padding: 8rem 1.5rem; text-align: center; border-top: 1px solid rgba(37,211,102,.1); background: linear-gradient(180deg, rgba(37,211,102,.05) 0%, transparent 60%); }
.final h2 { font-family: 'Syne', sans-serif; font-weight: 800; font-size: clamp(2.2rem,5vw,3.8rem); letter-spacing: -.03em; line-height: 1.05; margin-bottom: 1.25rem; max-width: 720px; margin-inline: auto; }
.final p { color: #666; font-size: 1rem; margin-bottom: 2.75rem; }
.final-micro { margin-top: 1.5rem; font-size: 0.78rem; color: #444; }

/* ── FOOTER ── */
footer { border-top: 1px solid rgba(255,255,255,.06); padding: 2rem 2.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; }
.f-logo { font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1.05rem; }
.f-logo em { font-style: normal; color: #14B8A6; }
.f-links { display: flex; gap: 1.75rem; flex-wrap: wrap; align-items: center; }
.f-links a { font-size: 0.78rem; color: #555; text-decoration: none; transition: color .2s; }
.f-links a:hover { color: #ededea; }
.f-copy { font-size: 0.75rem; color: #444; }

/* ── REVEAL ── */
.r { opacity: 0; transform: translateY(24px); transition: opacity .55s ease, transform .55s ease; }
.r.on { opacity: 1; transform: translateY(0); }

/* ── RESPONSIVE ── */
@media (max-width: 900px) {
  .pain-grid, .steps-row, .ben-grid, .t-grid, .pc-grid { grid-template-columns: 1fr; }
  .demo .inner { grid-template-columns: 1fr; }
  .ben-big { grid-row: auto; }
  .step::after { display: none; }
  nav { padding: 1rem 1.5rem; }
  .nav-r a:not(.nav-btn) { display: none; }
  footer { flex-direction: column; text-align: center; }
}
</style>
</head>
<body>

<!-- NAV -->
<nav>
  <a href="#" class="logo">Q<em>Line</em></a>
  <div class="nav-r">
    <a href="#how">How It Works</a>
    <a href="#pricing">Pricing</a>
    <a href="https://wa.me/60123456789" class="nav-btn">Try Free</a>
  </div>
</nav>

<!-- ═══ HERO ═══ -->
<section class="hero">
  <div class="hero-badge"><div class="badge-dot"></div> Now available for Malaysian businesses</div>

  <h1>
    Your customers<br>
    <span class="g">wait comfortably.</span><br>
    <span class="dim">Not at your counter.</span>
  </h1>

  <p class="hero-desc">
    QLine lets customers join your queue through WhatsApp — from anywhere. They get notified when it's their turn. No crowding. No shouting numbers. No extra app to download.
  </p>

  <div class="cta-row">
    <a href="https://wa.me/60123456789" class="cta-main">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.553 4.117 1.522 5.855L.057 23.882a.5.5 0 00.611.61l6.098-1.474A11.955 11.955 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.9a9.898 9.898 0 01-5.032-1.378l-.36-.214-3.741.905.939-3.647-.235-.376A9.862 9.862 0 012.1 12c0-5.468 4.432-9.9 9.9-9.9s9.9 4.432 9.9 9.9-4.432 9.9-9.9 9.9z"/></svg>
      Try It Free on WhatsApp
    </a>
    <a href="#how" class="cta-sec">See how it works <span class="arr">→</span></a>
  </div>

  <div class="proof">
    <div class="proof-item"><div class="proof-num">0</div><div class="proof-label">Apps to download</div></div>
    <div class="proof-div"></div>
    <div class="proof-item"><div class="proof-num">&lt;5min</div><div class="proof-label">Setup time</div></div>
    <div class="proof-div"></div>
    <div class="proof-item"><div class="proof-num">RM 15</div><div class="proof-label">Start from / day</div></div>
    <div class="proof-div"></div>
    <div class="proof-item"><div class="proof-num">100%</div><div class="proof-label">WhatsApp-based</div></div>
  </div>
</section>

<!-- ═══ PROBLEM ═══ -->
<section class="problem">
  <div class="inner">
    <div class="r">
      <span class="tag">Sound familiar?</span>
      <h2 class="big">Queues are killing<br>your customer experience</h2>
      <p class="sub">Long lines stress your staff, frustrate customers, and make your business look disorganised — even when it isn't.</p>
    </div>
    <div class="pain-grid r">
      <div class="pain">
        <div class="pain-emoji">😤</div>
        <h3>Customers crowd the entrance</h3>
        <p>People bunch up near your counter, blocking the space and making everyone uncomfortable — especially during peak hours.</p>
      </div>
      <div class="pain">
        <div class="pain-emoji">📢</div>
        <h3>Staff shout numbers all day</h3>
        <p>Calling names and numbers manually is exhausting, inconsistent, and easy to miss. Customers leave thinking they were skipped.</p>
      </div>
      <div class="pain">
        <div class="pain-emoji">😩</div>
        <h3>Customers give up and leave</h3>
        <p>No one wants to stand for 30 minutes just to wait. If they can't see the line moving, they walk out — and don't come back.</p>
      </div>
    </div>
  </div>
</section>

<!-- ═══ HOW IT WORKS ═══ -->
<section class="how" id="how">
  <div class="inner">
    <div class="how-head r">
      <span class="tag">How QLine Works</span>
      <h2 class="big">So simple, your customers<br>will figure it out instantly</h2>
      <p class="sub">No training needed. No new app. Just WhatsApp — which everyone already has.</p>
    </div>
    <div class="steps-row">
      <div class="step r">
        <div class="step-n">1</div>
        <div class="step-icon">🪧</div>
        <h3>Print & display your QR code</h3>
        <p>You get a unique QR code for your business. Print it, tape it near your entrance. That's it on your end.</p>
      </div>
      <div class="step r">
        <div class="step-n">2</div>
        <div class="step-icon">📱</div>
        <h3>Customer scans it</h3>
        <p>They point their camera at the QR. WhatsApp opens automatically — no typing, no form, no registration.</p>
      </div>
      <div class="step r">
        <div class="step-n">3</div>
        <div class="step-icon">🎟️</div>
        <h3>They get a ticket instantly</h3>
        <p>QLine sends them their queue number, their position, how long to wait, and a link to track it live.</p>
      </div>
      <div class="step r">
        <div class="step-n">4</div>
        <div class="step-icon">🔔</div>
        <h3>WhatsApp tells them when it's time</h3>
        <p>They can wait in their car, grab a coffee, or sit anywhere. QLine messages them when their turn is close.</p>
      </div>
    </div>
  </div>
</section>

<!-- ═══ WHATSAPP DEMO ═══ -->
<section class="demo">
  <div class="inner">
    <div class="phone-wrap r">
      <div class="phone">
        <div class="ph">
          <div class="ph-av">Q</div>
          <div><div class="ph-name">QLine</div><div class="ph-status">online</div></div>
        </div>
        <div class="pb">
          <div class="bbl bs">Joined from QR at Klinik Mesra</div>
          <div class="bbl bo">JOIN KLINIK-MESRA</div>
          <div class="bbl bi">
            ✅ <strong>You're in! Ticket Q017</strong><br>
            👥 17 people ahead of you<br>
            ⏱ About 22 minutes wait<br>
            🔗 Track live: qline.my/Q017
          </div>
          <div class="bbl bi">Feel free to wait in your car or nearby — we'll message you when you're almost up! 🚗</div>
          <div class="bt">10:42 AM ✓✓</div>
          <div class="bbl bs">🔔 Almost your turn — 3 people ahead</div>
          <div class="bbl bs">📣 It's your turn now — please come in!</div>
        </div>
      </div>
    </div>
    <div class="demo-left r">
      <h2>Your customers stay informed the whole time</h2>
      <p>QLine sends automatic WhatsApp messages at every step — so your customers are never left wondering "am I next?" and your staff never have to manually notify anyone.</p>
      <div class="check">Confirmation the moment they join</div>
      <div class="check">A heads-up message a few turns before theirs</div>
      <div class="check">A "your turn" message when staff call them</div>
      <div class="check">A live status page they can check anytime</div>
      <div class="check">Works for walk-ins too — no phone needed</div>
    </div>
  </div>
</section>

<!-- ═══ BENEFITS ═══ -->
<section class="benefits">
  <div class="inner">
    <div class="ben-head r">
      <span class="tag">Why Businesses Love QLine</span>
      <h2 class="big">Less chaos. Happier customers.<br>Calmer staff.</h2>
    </div>
    <div class="ben-grid">
      <div class="ben-big r">
        <div class="quote">80%</div>
        <h3>Less crowding at your counter</h3>
        <p>When customers can wait anywhere, your space stays clear. Clinics, banks, and government offices using QLine report dramatically less crowding — even during peak hours. Your staff can finally focus on serving, not managing the crowd.</p>
      </div>
      <div class="ben-sm r">
        <div class="ben-icon">💬</div>
        <h3>No new app. Just WhatsApp.</h3>
        <p>Every Malaysian already has WhatsApp. Customers don't need to learn anything new — they just scan and tap.</p>
      </div>
      <div class="ben-sm r">
        <div class="ben-icon">⚡</div>
        <h3>Up and running in 5 minutes</h3>
        <p>Register your business, print your QR, display it. Your first customer can join the queue today — no IT setup needed.</p>
      </div>
    </div>
  </div>
</section>

<!-- ═══ PRICING ═══ -->
<section class="pricing" id="pricing">
  <div class="inner">
    <div class="r">
      <span class="tag">Simple Pricing</span>
      <h2 class="big">Pay only for what you use</h2>
      <p class="price-note-top">No complicated plans. No hidden fees. Every business gets the exact same features — just pick daily or monthly.</p>
    </div>
    <div class="pc-grid r">
      <div class="pc">
        <div class="pc-label">Daily Pass</div>
        <p class="pc-who">Perfect for events, pasar malam, pop-ups — anywhere you need a queue for just one day.</p>
        <div class="pc-price"><sup>RM</sup>15</div>
        <div class="pc-per">per day</div>
        <div class="pc-limit">Up to 500 customers per day</div>
        <a href="https://wa.me/60123456789" class="pc-btn">Get started →</a>
      </div>
      <div class="pc star">
        <div class="star-label">Best Value</div>
        <div class="pc-label">Monthly</div>
        <p class="pc-who">For clinics, banks, regular businesses — that's less than RM 13 per day for a whole month.</p>
        <div class="pc-price"><sup>RM</sup>400</div>
        <div class="pc-per">per month</div>
        <div class="pc-limit">Up to 500 customers per day</div>
        <a href="https://wa.me/60123456789" class="pc-btn">Start free trial →</a>
      </div>
    </div>
    <p class="pricing-sub r">Payments via FPX or credit card. Cancel anytime. Queue is automatically paused if your plan expires.</p>
  </div>
</section>

<!-- ═══ TESTIMONIALS ═══ -->
<section class="testimonials">
  <div class="inner">
    <div class="t-head r">
      <span class="tag">Real Businesses, Real Results</span>
      <h2 class="big">They made the switch.<br>Their customers love it.</h2>
    </div>
    <div class="t-grid">
      <div class="tc r">
        <div class="stars">★★★★★</div>
        <p class="tc-q">"Before QLine, patients would crowd our reception from 8am. Now they scan the QR, go sit in their car, and we WhatsApp them when it's their turn. My nurses are so much less stressed."</p>
        <div class="tc-a">
          <div class="tc-av">DR</div>
          <div><div class="tc-name">Dr. Razif</div><div class="tc-biz">Klinik Mesra, Kuantan</div></div>
        </div>
      </div>
      <div class="tc r">
        <div class="stars">★★★★★</div>
        <p class="tc-q">"I printed the QR, stuck it on the counter, and customers were already using it by lunchtime. No setup headache at all. My customers actually thank me for it now."</p>
        <div class="tc-a">
          <div class="tc-av">SL</div>
          <div><div class="tc-name">Siti Lailah</div><div class="tc-biz">Kedai Jahit Siti, KL</div></div>
        </div>
      </div>
      <div class="tc r">
        <div class="stars">★★★★★</div>
        <p class="tc-q">"We handle over 200 customers a day across 3 counters. QLine manages all of them without any complaints. People actually say they prefer waiting this way."</p>
        <div class="tc-a">
          <div class="tc-av">AB</div>
          <div><div class="tc-name">Ahmad Basri</div><div class="tc-biz">Pejabat Kaunter, Negeri Sembilan</div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══ FINAL CTA ═══ -->
<section class="final">
  <div class="r">
    <span class="tag">Get Started Today</span>
    <h2>Stop managing crowds.<br>Start managing queues.</h2>
    <p>Setup takes less than 5 minutes. Your customers will notice the difference immediately.</p>
    <div class="cta-row">
      <a href="https://wa.me/60123456789" class="cta-main">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.553 4.117 1.522 5.855L.057 23.882a.5.5 0 00.611.61l6.098-1.474A11.955 11.955 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.9a9.898 9.898 0 01-5.032-1.378l-.36-.214-3.741.905.939-3.647-.235-.376A9.862 9.862 0 012.1 12c0-5.468 4.432-9.9 9.9-9.9s9.9 4.432 9.9 9.9-4.432 9.9-9.9 9.9z"/></svg>
        Start Free on WhatsApp
      </a>
      <a href="mailto:hello@qline.my" class="cta-sec">Talk to us <span class="arr">→</span></a>
    </div>
    <p class="final-micro">No app needed &nbsp;·&nbsp; Works on any phone &nbsp;·&nbsp; Cancel anytime &nbsp;·&nbsp; Malaysian support</p>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="f-logo">Q<em>Line</em></div>
  <div class="f-links">
    <a href="#">Privacy Policy</a>
    <a href="#">Terms of Service</a>
    <a href="mailto:hello@qline.my">hello@qline.my</a>
  </div>
  <span class="f-copy">© 2026 QLine · Built in Malaysia 🇲🇾</span>
</footer>

<script>
  const io = new IntersectionObserver(entries => {
    entries.forEach((e, i) => {
      if (e.isIntersecting) setTimeout(() => e.target.classList.add('on'), i * 80);
    });
  }, { threshold: 0.07 });
  document.querySelectorAll('.r').forEach(el => io.observe(el));
</script>
</body>
</html>