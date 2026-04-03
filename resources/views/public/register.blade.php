<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register — Qline</title>

<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">

<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{--primary:#14B8A6;--primary-dark:#0f766e;--bg:#f1f5f9;--card:#fff;--text:#0f172a;--muted:#64748b;--border:#e2e8f0;--error:#dc2626;--success:#16a34a}
body{font-family:'DM Sans',sans-serif;background:linear-gradient(135deg,#ecfeff,#f0fdfa);display:flex;align-items:center;justify-content:center;min-height:100vh;padding:20px}
.container{display:flex;max-width:920px;width:100%;border-radius:20px;overflow:hidden;box-shadow:0 20px 50px rgba(0,0,0,.08)}
.left{width:35%;background:linear-gradient(160deg,#0f766e,#134e4a);color:#fff;padding:40px;display:flex;flex-direction:column;justify-content:space-between}
.logo{font-family:'Syne';font-size:28px;font-weight:800}
.features div{margin-bottom:16px;font-size:13px;opacity:.9}
.right{width:65%;background:var(--card);padding:40px}

h1{font-family:'Syne';font-size:24px;margin-bottom:6px}
.subtitle{font-size:14px;color:var(--muted);margin-bottom:25px}

.field{position:relative;margin-bottom:18px}
.field input{width:100%;padding:14px 12px 14px 40px;border:1px solid var(--border);border-radius:10px;font-size:14px;background:#fff;outline:none}
.field label{position:absolute;left:40px;top:50%;transform:translateY(-50%);font-size:13px;color:var(--muted);pointer-events:none;transition:.2s}
.field input:focus+label,.field input:not(:placeholder-shown)+label{top:-8px;font-size:11px;background:#fff;padding:0 4px;color:var(--primary)}
.field input:focus{border-color:var(--primary);box-shadow:0 0 0 2px rgba(20,184,166,.15)}

.field.error input{border-color:var(--error)}
.field.success input{border-color:var(--success)}
.message{font-size:11px;margin-top:4px}
.message.error{color:var(--error)}
.message.success{color:var(--success)}

.icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:16px;opacity:.6}

.grid{display:grid;grid-template-columns:repeat(2,1fr);gap:10px}
.grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}

.strength{height:6px;border-radius:6px;background:#e5e7eb;margin-top:6px;overflow:hidden}
.strength-bar{height:100%;width:0%;transition:.3s;border-radius:6px}

button{width:100%;padding:12px;background:var(--primary);color:#fff;border:none;border-radius:10px;font-weight:600;margin-top:10px;cursor:pointer}
button:hover{background:var(--primary-dark)}

.footer{text-align:center;margin-top:15px;font-size:13px;color:var(--muted)}

@media(max-width:768px){.container{flex-direction:column}.left{display:none}.right{width:100%}}
</style>
</head>

<body>

<div class="container">

<div class="left">
  <div class="logo">Qline</div>
  <div class="features">
    <div>📱 WhatsApp-first queue</div>
    <div>📺 Live display</div>
    <div>⚡ Instant alerts</div>
    <div>📊 Smart analytics</div>
  </div>
  <div style="font-size:11px;opacity:.5">© 2026 Qline</div>
</div>

<div class="right">

<h1>Create your account</h1>
<div class="subtitle">Launch in 2 minutes</div>

<form method="POST" action="{{ route('register.store') }}">
@csrf

<div class="field" id="businessField">
<span class="icon">🏢</span>
<input type="text" name="business_name" value="{{ old('business_name') }}" required placeholder=" ">
<label>Business Name</label>
<div class="message"></div>
</div>

<div class="field" id="phoneField">
<span class="icon">📞</span>
<input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder=" ">
<label>Phone</label>
<div class="message"></div>
</div>

<div class="grid-3">
<div class="field"><span class="icon">🏙</span><input name="city" value="{{ old('city') }}" placeholder=" "><label>City</label></div>
<div class="field"><span class="icon">📍</span><input name="state" value="{{ old('state') }}" placeholder=" "><label>State</label></div>
<div class="field"><span class="icon">🔢</span><input name="postcode" value="{{ old('postcode') }}" placeholder=" "><label>Postcode</label></div>
</div>

<div class="field" id="nameField">
<span class="icon">👤</span>
<input type="text" name="name" value="{{ old('name') }}" required placeholder=" ">
<label>Full Name</label>
<div class="message"></div>
</div>

<div class="field" id="emailField">
<span class="icon">✉️</span>
<input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder=" ">
<label>Email</label>
<div class="message"></div>
</div>

<div class="grid">
<div class="field" id="passwordField">
<span class="icon">🔒</span>
<input type="password" id="password" name="password" placeholder=" ">
<label>Password</label>
<div class="strength"><div class="strength-bar" id="strengthBar"></div></div>
<div class="message"></div>
</div>
<div class="field">
<span class="icon">🔐</span>
<input type="password" name="password_confirmation" placeholder=" ">
<label>Confirm</label>
</div>
</div>

<button type="submit">Create My Business</button>

</form>

<div class="footer">Already have account? <a href="/business">Login</a></div>

</div>

</div>

<script>
// Realtime validation
function setState(field, valid, msg) {
  field.classList.remove('error','success');
  const m = field.querySelector('.message');
  if(valid === null){ m.textContent=''; return; }
  if(valid){ field.classList.add('success'); m.textContent=msg; m.className='message success'; }
  else{ field.classList.add('error'); m.textContent=msg; m.className='message error'; }
}

// Email validation
const email = document.getElementById('email');
email.addEventListener('input', () => {
  const val = email.value;
  const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
  setState(document.getElementById('emailField'), valid, valid ? 'Looks good' : 'Invalid email');
});

// Name validation
const nameInput = document.querySelector('[name="name"]');
nameInput.addEventListener('input', () => {
  setState(document.getElementById('nameField'), nameInput.value.length > 3, 'Enter full name');
});

// Malaysia phone auto format
const phone = document.getElementById('phone');
phone.addEventListener('input', () => {
  let val = phone.value.replace(/[^0-9]/g,'');

  if(val.startsWith('0')) val = '6' + val;
  if(!val.startsWith('6')) val = '60' + val;

  if(val.length > 11) val = val.slice(0,11);

  phone.value = '+' + val;

  const valid = /^\+601[0-9]{7,8}$/.test(phone.value);
  setState(document.getElementById('phoneField'), valid, valid ? 'Valid Malaysia number' : 'Format: +60123456789');
});

// Password strength
const pwd=document.getElementById('password');
const bar=document.getElementById('strengthBar');
pwd.addEventListener('input',()=>{
 let val=pwd.value;let score=0;
 if(val.length>6)score++;
 if(/[A-Z]/.test(val))score++;
 if(/[0-9]/.test(val))score++;
 if(/[^A-Za-z0-9]/.test(val))score++;
 bar.style.width=[0,25,50,75,100][score]+'%';
 setState(document.getElementById('passwordField'), score>=2, 'Stronger is better');
});
</script>

</body>
</html>