<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Masuk Sistem — {{ config('app.name','SIAKAD') }}</title>
@vite(['resources/css/app.css','resources/js/app.js'])
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html,body{height:100%;font-family:'Plus Jakarta Sans',sans-serif}

/* ── Layout ── */
.login-wrap{display:flex;min-height:100vh}

/* ── Panel kiri (branding) ── */
.panel-l{
  width:44%;flex-shrink:0;
  background:#050d1f;
  position:relative;overflow:hidden;
  display:flex;flex-direction:column;justify-content:space-between;
  padding:40px 48px
}
@media(max-width:900px){.panel-l{display:none}}

.pl-mesh{
  position:absolute;inset:0;pointer-events:none;
  background:
    radial-gradient(ellipse 70% 50% at 20% 30%,rgba(29,78,216,.2) 0%,transparent 65%),
    radial-gradient(ellipse 50% 40% at 75% 70%,rgba(67,56,202,.12) 0%,transparent 60%)
}

.pl-brand{display:flex;align-items:center;gap:14px;position:relative;z-index:1}
.pl-logo{
  width:48px;height:48px;border-radius:12px;overflow:hidden;
  background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.14);
  padding:5px;flex-shrink:0
}
.pl-logo img{width:100%;height:100%;object-fit:contain}
.pl-brand-name{font-size:13px;font-weight:800;color:#fff;letter-spacing:.025em}
.pl-brand-sub{font-size:10px;color:rgba(147,197,253,.65);font-weight:500;margin-top:2px}

.pl-middle{position:relative;z-index:1}
.pl-pill{
  display:inline-flex;align-items:center;gap:7px;
  background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);
  color:rgba(196,219,255,.75);font-size:10px;font-weight:700;letter-spacing:.12em;
  text-transform:uppercase;padding:5px 12px;border-radius:999px;margin-bottom:24px
}
.pl-pill-dot{width:5px;height:5px;border-radius:50%;background:#10b981}

/* Judul panel kiri: putih solid */
.pl-title{font-size:clamp(22px,2.8vw,32px);font-weight:900;color:#fff;line-height:1.12;letter-spacing:-.02em;margin-bottom:8px}
.pl-subtitle{font-size:15px;font-weight:600;color:rgba(147,197,253,.8);margin-bottom:20px}
.pl-desc{font-size:13.5px;line-height:1.7;color:rgba(148,163,184,.7);max-width:320px;margin-bottom:32px}

.pl-features{display:flex;flex-direction:column;gap:11px}
.pl-feat{display:flex;align-items:center;gap:10px}
.pl-feat-ico{
  width:22px;height:22px;border-radius:50%;flex-shrink:0;
  background:rgba(37,99,235,.2);border:1px solid rgba(99,130,255,.3);
  display:flex;align-items:center;justify-content:center
}
.pl-feat-ico svg{width:11px;height:11px}
.pl-feat-text{font-size:13px;color:rgba(203,213,225,.8);font-weight:500}

.pl-footer{position:relative;z-index:1}
.pl-footer p{font-size:11px;color:rgba(71,85,105,.6);line-height:1.7}

/* ── Panel kanan (form) ── */
.panel-r{
  flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;
  background:#fff;position:relative;overflow:hidden;padding:48px 32px
}
.pr-glow{
  position:absolute;top:-100px;right:-100px;width:400px;height:400px;
  border-radius:50%;pointer-events:none;
  background:radial-gradient(circle,rgba(219,234,254,.6) 0%,transparent 70%)
}

/* Mobile brand */
.mobile-brand{
  display:none;align-items:center;gap:12px;margin-bottom:32px
}
@media(max-width:900px){.mobile-brand{display:flex}}
.mb-logo{width:40px;height:40px;border-radius:10px;background:#eff6ff;border:1px solid #bfdbfe;overflow:hidden;padding:4px}
.mb-logo img{width:100%;height:100%;object-fit:contain}
.mb-name{font-size:13px;font-weight:800;color:#1e293b}
.mb-sub{font-size:10px;color:#94a3b8;font-weight:500}

.form-box{width:100%;max-width:380px;position:relative;z-index:1}

.form-eyebrow{font-size:10px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:#94a3b8;margin-bottom:10px}
.form-title{font-size:26px;font-weight:900;color:#0f172a;letter-spacing:-.025em;margin-bottom:6px}
.form-sub{font-size:13.5px;color:#64748b;font-weight:400;line-height:1.5;margin-bottom:28px}

/* Alert */
.alert{display:flex;align-items:flex-start;gap:10px;border-radius:11px;padding:12px 14px;font-size:13px;font-weight:500;margin-bottom:20px}
.alert svg{width:16px;height:16px;flex-shrink:0;margin-top:1px}
.alert-success{background:#f0fdf4;border:1px solid #bbf7d0;color:#166534}
.alert-error{background:#fff1f2;border:1px solid #fecdd3;color:#9f1239}

/* Field */
.field{margin-bottom:18px}
.field-label{display:block;font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#64748b;margin-bottom:7px}
.field-wrap{position:relative}
.field-ico{
  position:absolute;left:14px;top:50%;transform:translateY(-50%);
  color:#94a3b8;pointer-events:none
}
.field-ico svg{width:16px;height:16px}
.field-input{
  width:100%;padding:13px 14px 13px 40px;
  border-radius:11px;border:1.5px solid #e2e8f0;
  background:#f8fafc;color:#0f172a;
  font-size:14px;font-weight:500;font-family:'Plus Jakarta Sans',sans-serif;
  outline:none;transition:border-color .15s,box-shadow .15s,background .15s
}
.field-input:focus{border-color:#2563eb;background:#fff;box-shadow:0 0 0 3px rgba(37,99,235,.12)}
.field-input::placeholder{color:#cbd5e1;font-weight:400}

/* Remember + forgot */
.form-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:22px}
.remember{display:flex;align-items:center;gap:7px;cursor:pointer}
.remember input{width:15px;height:15px;border-radius:4px;accent-color:#2563eb;cursor:pointer}
.remember span{font-size:13px;color:#475569;font-weight:500}
.forgot{font-size:13px;color:#2563eb;font-weight:600;text-decoration:none;transition:color .15s}
.forgot:hover{color:#1d4ed8}

/* Submit */
.btn-submit{
  width:100%;padding:14px 20px;border-radius:11px;border:none;cursor:pointer;
  background:linear-gradient(135deg,#1d4ed8 0%,#4338ca 100%);
  color:#fff;font-size:15px;font-weight:700;font-family:'Plus Jakarta Sans',sans-serif;
  letter-spacing:.01em;
  display:flex;align-items:center;justify-content:center;gap:9px;
  box-shadow:0 4px 20px rgba(37,99,235,.38);
  transition:transform .15s,box-shadow .15s
}
.btn-submit:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(37,99,235,.5)}
.btn-submit:active{transform:translateY(0)}
.btn-submit svg{width:17px;height:17px}

/* Back link */
.back-link{
  display:flex;align-items:center;justify-content:center;gap:6px;
  margin-top:24px;padding-top:22px;border-top:1px solid #f1f5f9;
  font-size:13px;color:#94a3b8;font-weight:500;text-decoration:none;
  transition:color .15s
}
.back-link:hover{color:#475569}
.back-link svg{width:15px;height:15px}

/* Mobile footer */
.mob-footer{margin-top:auto;padding-top:24px;font-size:11px;color:#cbd5e1;text-align:center;display:none}
@media(max-width:900px){.mob-footer{display:block}}
</style>
</head>
<body>
@php
  $profil     = \App\Models\ProfileSekolah::first();
  $nama       = $profil->nama_sekolah ?? 'SMP DIANTO LANDONG';
  $npsn       = $profil->npsn ?? '';
  $kepsek     = $profil->kepala_sekolah ?? '';
  $logo       = ($profil && $profil->logo_sekolah) ? asset('storage/'.$profil->logo_sekolah) : asset('images/logo-dilan.png');
@endphp

<div class="login-wrap">

  {{-- ══════════════════════════════════════
       PANEL KIRI — BRANDING
  ══════════════════════════════════════ --}}
  <div class="panel-l">
    <div class="pl-mesh"></div>

    {{-- Brand --}}
    <div class="pl-brand">
      <div class="pl-logo"><img src="{{ $logo }}" alt="Logo" onerror="this.style.opacity=0"></div>
      <div>
        <div class="pl-brand-name">{{ strtoupper($nama) }}</div>
        <div class="pl-brand-sub">Sistem Informasi Akademik</div>
      </div>
    </div>

    {{-- Konten tengah --}}
    <div class="pl-middle">
      <div class="pl-pill"><div class="pl-pill-dot"></div>Platform Resmi</div>
      <div class="pl-title">{{ strtoupper($nama) }}</div>
      <div class="pl-subtitle">Sistem Informasi Akademik</div>
      <p class="pl-desc">Kelola data akademik, absensi, nilai, dan evaluasi kinerja siswa dalam satu platform yang terintegrasi dan mudah digunakan.</p>
      <div class="pl-features">
        @foreach(['Manajemen nilai & absensi real-time','Monitoring perkembangan oleh orang tua','Evaluasi fuzzy logic otomatis','Laporan akademik komprehensif'] as $f)
        <div class="pl-feat">
          <div class="pl-feat-ico">
            <svg fill="none" stroke="#93c5fd" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          </div>
          <div class="pl-feat-text">{{ $f }}</div>
        </div>
        @endforeach
      </div>
    </div>

    {{-- Footer kiri --}}
    <div class="pl-footer">
      @if($kepsek)<p>Kepala Sekolah: <strong style="color:rgba(148,163,184,.6)">{{ $kepsek }}</strong></p>@endif
      <p>© {{ date('Y') }} {{ $nama }}@if($npsn) · NPSN {{ $npsn }}@endif</p>
    </div>
  </div>

  {{-- ══════════════════════════════════════
       PANEL KANAN — FORM
  ══════════════════════════════════════ --}}
  <div class="panel-r">
    <div class="pr-glow"></div>

    {{-- Logo mobile --}}
    <div class="mobile-brand">
      <div class="mb-logo"><img src="{{ $logo }}" alt="Logo" onerror="this.style.opacity=0"></div>
      <div>
        <div class="mb-name">{{ strtoupper($nama) }}</div>
        <div class="mb-sub">Sistem Informasi Akademik</div>
      </div>
    </div>

    <div class="form-box">

      <div class="form-eyebrow">Sistem Akademik &nbsp;·&nbsp; {{ date('Y') }}</div>
      <div class="form-title">Masuk ke Sistem</div>
      <p class="form-sub">Gunakan email dan kata sandi yang telah diberikan oleh sekolah.</p>

      {{-- Alert sukses --}}
      @if(session('status'))
      <div class="alert alert-success">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        <span>{{ session('status') }}</span>
      </div>
      @endif

      {{-- Alert error --}}
      @if($errors->any())
      <div class="alert alert-error">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
        <div>@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
      </div>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="field">
          <label class="field-label" for="email">Email</label>
          <div class="field-wrap">
            <div class="field-ico"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="field-input" placeholder="nama@email.com"
                   required autofocus autocomplete="username">
          </div>
        </div>

        {{-- Password --}}
        <div class="field">
          <label class="field-label" for="password">Kata Sandi</label>
          <div class="field-wrap">
            <div class="field-ico"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></div>
            <input id="password" type="password" name="password"
                   class="field-input" placeholder="Masukkan kata sandi"
                   required autocomplete="current-password">
          </div>
        </div>

        {{-- Remember + Forgot --}}
        <div class="form-row">
          <label class="remember">
            <input type="checkbox" name="remember">
            <span>Ingat saya</span>
          </label>
          @if(Route::has('password.request'))
          <a href="{{ route('password.request') }}" class="forgot">Lupa kata sandi?</a>
          @endif
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-submit">
          <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
          Masuk ke Sistem
        </button>

      </form>

      {{-- Kembali --}}
      <a href="{{ url('/') }}" class="back-link">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Halaman Utama
      </a>

    </div>

    <div class="mob-footer">© {{ date('Y') }} {{ $nama }}</div>
  </div>

</div>
</body>
</html>
