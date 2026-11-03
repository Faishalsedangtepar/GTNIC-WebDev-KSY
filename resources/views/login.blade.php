<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title>Masuk - Aura Material</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

  @vite(['resources/css/login.css', 'resources/js/login.js'])
</head>
<body>
  <main class="page">

    <!-- Header merek -->
    <header class="brand">
      <div class="brand-left">
        <div class="brand-logo">
          <span class="material-symbols-outlined">hardware</span>
        </div>
        <span class="brand-name">Aura<span>Material</span></span>
      </div>
      <div class="status">
        <span class="status-dot"></span>
        <span>Online</span>
      </div>
    </header>

    <!-- Judul -->
    <section class="intro">
      <h1>Masuk ke Sistem Aura Material</h1>
      <p>Pilih peran operasional untuk mulai transaksi toko dan logistik gudang.</p>
    </section>

    <!-- Kartu form login -->
    <form class="card" action="{{ route('login') }}" method="post">
      @csrf

      <div class="field">
        <label class="field-label" for="emp-id">ID Karyawan / No. HP</label>
        <div class="input-wrap">
          <span class="material-symbols-outlined input-icon">badge</span>
          <input class="input" id="emp-id" name="emp_id" type="text"
                 placeholder="kasir.joko@tokobangunan.id"
                 value="kasir.joko@tokobangunan.id">
        </div>
      </div>

      <div class="field">
        <div class="field-row">
          <label class="field-label" for="pin-input">PIN Keamanan Toko / Sandi</label>
          <button class="toggle-pin" id="toggle-pin" type="button">
            <span class="material-symbols-outlined" id="eye-icon">visibility</span>
            <span id="eye-text">Lihat</span>
          </button>
        </div>
        <div class="input-wrap">
          <span class="material-symbols-outlined input-icon">lock</span>
          <input class="input input-pin" id="pin-input" name="pin" type="password"
                 maxlength="6" placeholder="••••••" value="782190">
          <span class="pin-badge">6-DIGIT</span>
        </div>
      </div>

      <button class="btn btn-secondary" id="quick-pin" type="button">
        <span class="material-symbols-outlined">fingerprint</span>
        <span>PIN Cepat &amp; Biometrik Gudang</span>
      </button>

      <button class="btn btn-primary" id="submit-btn" type="submit">
        <span id="submit-label">Masuk</span>
        <span class="material-symbols-outlined">arrow_forward</span>
      </button>

    </form>
  </main>
</body>
</html>