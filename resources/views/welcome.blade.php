<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $profil->nama_sekolah ?? 'SMP DIANTO LANDONG' }}</title>
@vite(['resources/css/app.css','resources/js/app.js'])
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html,body{height:100%;font-family:'Plus Jakarta Sans',sans-serif}
body{background:#050d1f;color:#fff;overflow-x:hidden}

/* ── Noise texture overlay ── */
body::before{
  content:'';
  position:fixed;inset:0;
  background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='.03'/%3E%3C/svg%3E");
  pointer-events:none;z-index:0
}

/* ── Gradient mesh background ── */
.mesh{
  position:fixed;inset:0;pointer-events:none;z-index:0;
  background:
    radial-gradient(ellipse 80% 50% at 15% 40%,rgba(29,78,216,.18) 0%,transparent 60%),
    radial-gradient(ellipse 60% 40% at 80% 20%,rgba(67,56,202,.12) 0%,transparent 55%),
    radial-gradient(ellipse 50% 60% at 70% 80%,rgba(15,118,110,.08) 0%,transparent 60%),
    #050d1f
}

/* ── Layout wrapper ── */
.page{position:relative;z-index:1;min-height:100vh;display:flex;flex-direction:column}

/* ── Navbar ── */
nav{
  padding:20px 40px;
  display:flex;align-items:center;justify-content:space-between;
  border-bottom:1px solid rgba(255,255,255,.06)
}
@media(max-width:768px){nav{padding:16px 20px}}

.brand{display:flex;align-items:center;gap:12px}
.brand-logo{
  width:40px;height:40px;border-radius:10px;overflow:hidden;
  background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.14);
  padding:4px;flex-shrink:0
}
.brand-logo img{width:100%;height:100%;object-fit:contain}
.brand-name{font-weight:800;font-size:13px;color:#fff;letter-spacing:.02em;line-height:1.2}
.brand-sub{font-size:10px;color:rgba(147,197,253,.7);font-weight:500;margin-top:1px}

/* ── Nav right: hanya info, bukan tombol ── */
.nav-info{
  display:flex;align-items:center;gap:6px;
  font-size:11px;font-weight:600;color:rgba(148,163,184,.7);
  letter-spacing:.08em;text-transform:uppercase
}
.nav-dot{width:6px;height:6px;border-radius:50%;background:#10b981;flex-shrink:0;
  box-shadow:0 0 0 3px rgba(16,185,129,.2)}

/* ── Hero ── */
.hero{
  flex:1;display:flex;align-items:center;
  padding:60px 40px 40px
}
@media(max-width:768px){.hero{padding:40px 20px 32px}}

.hero-inner{
  max-width:1200px;width:100%;margin:0 auto;
  display:grid;grid-template-columns:1fr 420px;gap:80px;align-items:center
}
@media(max-width:1100px){.hero-inner{grid-template-columns:1fr;gap:48px}}
@media(max-width:1100px){.hero-right{display:none}}

/* ── Hero left ── */
.hero-badge{
  display:inline-flex;align-items:center;gap:8px;
  background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);
  color:rgba(196,219,255,.85);font-size:11px;font-weight:600;
  letter-spacing:.1em;text-transform:uppercase;
  padding:6px 14px;border-radius:999px;margin-bottom:28px
}
.badge-dot{width:6px;height:6px;border-radius:50%;background:#10b981;flex-shrink:0}

.hero-eyebrow{
  font-size:11px;font-weight:700;letter-spacing:.16em;text-transform:uppercase;
  color:rgba(96,165,250,.8);margin-bottom:12px
}

/* Nama sekolah: PUTIH SOLID — tidak pernah terhalang */
.hero-school{
  font-size:clamp(28px,4vw,48px);font-weight:900;
  color:#ffffff;line-height:1.06;letter-spacing:-.02em;
  margin-bottom:6px
}
.hero-tagline{
  font-size:clamp(16px,2.2vw,22px);font-weight:600;
  color:rgba(147,197,253,.85);margin-bottom:20px;
  line-height:1.3
}
.hero-desc{
  font-size:15px;line-height:1.7;color:rgba(148,163,184,.85);
  max-width:480px;margin-bottom:36px
}

/* ── CTA tunggal ── */
.cta-wrap{margin-bottom:48px}
.btn-main{
  display:inline-flex;align-items:center;gap:10px;
  background:linear-gradient(135deg,#1d4ed8 0%,#4338ca 100%);
  color:#fff;font-weight:700;font-size:15px;
  padding:14px 28px;border-radius:14px;
  text-decoration:none;letter-spacing:.01em;
  box-shadow:0 4px 24px rgba(37,99,235,.4);
  transition:transform .2s,box-shadow .2s
}
.btn-main:hover{transform:translateY(-2px);box-shadow:0 8px 32px rgba(37,99,235,.55)}
.btn-main:active{transform:translateY(0)}
.btn-main svg{width:18px;height:18px;flex-shrink:0}

/* ── Info strip ── */
.info-strip{
  display:grid;grid-template-columns:repeat(4,1fr);gap:12px
}
@media(max-width:560px){.info-strip{grid-template-columns:repeat(2,1fr)}}

.info-card{
  background:rgba(255,255,255,.04);
  border:1px solid rgba(255,255,255,.08);
  border-radius:14px;padding:16px 14px;text-align:center;
  transition:border-color .2s,transform .2s
}
.info-card:hover{border-color:rgba(255,255,255,.16);transform:translateY(-2px)}
.info-icon{
  width:36px;height:36px;border-radius:10px;
  display:flex;align-items:center;justify-content:center;
  margin:0 auto 10px
}
.info-icon svg{width:18px;height:18px}
.info-label{font-size:12px;font-weight:700;color:#fff;line-height:1.2}
.info-value{font-size:11px;font-weight:600;margin-top:3px}

/* ── Preview card kanan ── */
.preview-card{
  background:rgba(14,24,70,.7);
  backdrop-filter:blur(24px);
  border:1px solid rgba(255,255,255,.1);
  border-radius:24px;padding:28px;
  box-shadow:0 32px 80px rgba(0,0,20,.6);
  animation:float 7s ease-in-out infinite
}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-12px)}}

.pcard-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:22px}
.pcard-title{font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:rgba(148,163,184,.6)}
.pcard-name{font-size:17px;font-weight:800;color:#fff;margin-top:2px}
.pcard-badge{
  display:flex;align-items:center;gap:5px;
  background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.25);
  color:#6ee7b7;font-size:10px;font-weight:700;letter-spacing:.06em;
  padding:5px 10px;border-radius:999px;text-transform:uppercase
}
.pcard-dot{width:5px;height:5px;border-radius:50%;background:#10b981}

.stat-row{
  display:flex;align-items:center;gap:14px;
  background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);
  border-radius:14px;padding:14px 16px;
  transition:background .15s;margin-bottom:10px
}
.stat-row:last-child{margin-bottom:0}
.stat-row:hover{background:rgba(255,255,255,.07)}
.stat-ico{
  width:38px;height:38px;border-radius:10px;
  display:flex;align-items:center;justify-content:center;flex-shrink:0
}
.stat-ico svg{width:18px;height:18px}
.stat-lbl{flex:1;font-size:13px;font-weight:500;color:rgba(148,163,184,.85)}
.stat-num{font-size:22px;font-weight:900;color:#fff}

/* ── Footer ── */
footer{
  padding:18px 40px;
  border-top:1px solid rgba(255,255,255,.06);
  display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:10px
}
@media(max-width:768px){footer{padding:14px 20px}}
footer p{font-size:11px;color:rgba(100,116,139,.7)}
footer .tagline{display:flex;gap:8px;font-size:11px;color:rgba(100,116,139,.5);font-weight:600;text-transform:uppercase;letter-spacing:.06em}
</style>
</head>
<body>
@php
  $nama   = $profil->nama_sekolah ?? 'SMP DIANTO LANDONG';
  $npsn   = $profil->npsn ?? '';
  $kepsek = $profil->kepala_sekolah ?? '';
  $jenjang= $profil->jenjang ?? 'SMP';
  $akredit= ($profil && $profil->akreditasi && $profil->akreditasi !== '-') ? $profil->akreditasi : 'B';
  $kur    = $profil && $profil->kurikulum ? \Illuminate\Support\Str::limit($profil->kurikulum,11) : 'Merdeka';
  $logo   = ($profil && $profil->logo_sekolah) ? asset('storage/'.$profil->logo_sekolah) : asset('images/logo-dilan.png');
@endphp

<div class="mesh"></div>

<div class="page">

  {{-- ── NAVBAR ──────────────────────────────────────── --}}
  <nav>
    <div class="brand">
      <div class="brand-logo"><img src="{{ $logo }}" alt="Logo" onerror="this.style.opacity=0"></div>
      <div>
        <div class="brand-name">{{ strtoupper($nama) }}</div>
        <div class="brand-sub">Sistem Informasi Akademik</div>
      </div>
    </div>
    {{-- Hanya status — tidak ada tombol di navbar --}}
    <div class="nav-info">
      <div class="nav-dot"></div>
      Sistem Aktif
    </div>
  </nav>

  {{-- ── HERO ────────────────────────────────────────── --}}
  <div class="hero">
    <div class="hero-inner">

      {{-- KIRI --}}
      <div>
        <div class="hero-badge"><div class="badge-dot"></div>Platform Akademik Resmi</div>

        <div class="hero-eyebrow">
          {{ $jenjang }}@if($npsn) &nbsp;·&nbsp; NPSN {{ $npsn }}@endif
        </div>

        {{-- Nama sekolah: putih solid, tidak pakai gradient clip --}}
        <div class="hero-school">{{ strtoupper($nama) }}</div>
        <div class="hero-tagline">Sistem Informasi Akademik</div>

        <p class="hero-desc">
          Platform digital sekolah untuk pengelolaan data akademik, monitoring perkembangan siswa, absensi, nilai, jadwal pelajaran, dan evaluasi kinerja secara profesional dan terintegrasi.
        </p>

        {{-- SATU CTA — hanya ada satu di seluruh halaman --}}
        <div class="cta-wrap">
          @auth
          <a href="{{ route('dashboard') }}" class="btn-main">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Buka Dashboard
            <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
          </a>
          @else
          <a href="{{ route('login') }}" class="btn-main">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
            Masuk ke Sistem
            <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
          </a>
          @endauth
        </div>

        {{-- Info cards dari DB --}}
        <div class="info-strip">
          <div class="info-card">
            <div class="info-icon" style="background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.2)">
              <svg fill="none" stroke="#6ee7b7" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="info-label">Akreditasi</div>
            <div class="info-value" style="color:#6ee7b7">{{ $akredit }}</div>
          </div>
          <div class="info-card">
            <div class="info-icon" style="background:rgba(59,130,246,.12);border:1px solid rgba(59,130,246,.2)">
              <svg fill="none" stroke="#93c5fd" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/></svg>
            </div>
            <div class="info-label">Kurikulum</div>
            <div class="info-value" style="color:#93c5fd">{{ $kur }}</div>
          </div>
          <div class="info-card">
            <div class="info-icon" style="background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.2)">
              <svg fill="none" stroke="#fcd34d" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div class="info-label">Monitoring</div>
            <div class="info-value" style="color:#fcd34d">Real-time</div>
          </div>
          <div class="info-card">
            <div class="info-icon" style="background:rgba(167,139,250,.12);border:1px solid rgba(167,139,250,.2)">
              <svg fill="none" stroke="#c4b5fd" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
            </div>
            <div class="info-label">Jenjang</div>
            <div class="info-value" style="color:#c4b5fd">{{ $jenjang }}</div>
          </div>
        </div>
      </div>

      {{-- KANAN: preview card — tidak ada tombol di sini --}}
      <div class="hero-right">
        <div class="preview-card">
          <div class="pcard-header">
            <div>
              <div class="pcard-title">Dashboard Akademik</div>
              <div class="pcard-name">Statistik Sekolah</div>
            </div>
            <div class="pcard-badge"><div class="pcard-dot"></div>Online</div>
          </div>

          @php
          $statRows = [
            ['Siswa Aktif',     $stats['siswa'],  '#3b82f6','rgba(59,130,246,.12)','rgba(59,130,246,.2)','M17 20h5V4H2v16h5m10 0v-2a4 4 0 00-8 0v2m8 0H9m4-10a4 4 0 100-8 4 4 0 000 8z'],
            ['Tenaga Pengajar', $stats['guru'],   '#10b981','rgba(16,185,129,.12)','rgba(16,185,129,.2)','M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422A12.083 12.083 0 0112 20.055a12.083 12.083 0 01-6.16-9.477L12 14z'],
            ['Kelas Aktif',     $stats['kelas'],  '#f59e0b','rgba(245,158,11,.12)','rgba(245,158,11,.2)','M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z'],
            ['Mata Pelajaran',  $stats['mapel'],  '#a78bfa','rgba(167,139,250,.12)','rgba(167,139,250,.2)','M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13'],
          ];
          @endphp
          @foreach($statRows as [$lbl,$cnt,$clr,$bg,$bd,$path])
          <div class="stat-row">
            <div class="stat-ico" style="background:{{$bg}};border:1px solid {{$bd}}">
              <svg fill="none" stroke="{{$clr}}" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{$path}}"/></svg>
            </div>
            <div class="stat-lbl">{{$lbl}}</div>
            <div class="stat-num">{{$cnt}}</div>
          </div>
          @endforeach

        </div>
      </div>

    </div>
  </div>

  {{-- ── FOOTER ──────────────────────────────────────── --}}
  <footer>
    <p>© {{ date('Y') }} {{ $nama }}{{ $npsn ? ' &nbsp;·&nbsp; NPSN '.$npsn : '' }}{{ $kepsek ? ' &nbsp;·&nbsp; '.$kepsek : '' }}</p>
    <div class="tagline"><span>Disiplin</span><span>·</span><span>Berprestasi</span><span>·</span><span>Berkarakter</span></div>
  </footer>

</div>
</body>
</html>
