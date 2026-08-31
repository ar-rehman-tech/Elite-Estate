<?php
$base = '../';
require_once '../includes/config.php';
include '../includes/db.php';
include '../includes/auth.php';

$db = DB::connect();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Try to get real property
$p = null;
if ($id) {
    $stmt = $db->prepare("SELECT * FROM properties WHERE id = ?");
    $stmt->execute([$id]);
    $p = $stmt->fetch();
}

// Handle inquiry form
$inquirySent = false;
$inquiryError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['inquiry'])) {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $msg   = trim($_POST['message'] ?? '');

    if ($name && $email && $msg) {
        $db->prepare("INSERT INTO inquiries (property_id, name, email, phone, message, created_at) VALUES (?,?,?,?,?,NOW())")
           ->execute([$id, $name, $email, $phone, $msg]);
        $inquirySent = true;
    } else {
        $inquiryError = 'Please fill in all required fields.';
    }
}

// Add to favorites
if (isset($_GET['fav'])) {
    if (!isset($_SESSION['fav'])) $_SESSION['fav'] = [];
    $fid = (int)$_GET['fav'];
    if (!in_array($fid, $_SESSION['fav'])) $_SESSION['fav'][] = $fid;
    header("Location: property-details.php?id=$id");
    exit;
}

// Fallback data
$fallbackProps = [
  1 => ['id'=>1,'title'=>'Villa Serenita','location'=>'Amalfi Coast, Italy','price'=>12500000,'type'=>'Villa','beds'=>6,'baths'=>7,'sqft'=>8400,'description'=>'An extraordinary clifftop villa perched above the crystalline waters of the Amalfi Coast. Designed by award-winning Italian architect Marco Bernini, this masterpiece seamlessly blends traditional Mediterranean craftsmanship with contemporary luxury living. Panoramic sea views from every room, a 25-meter infinity pool, private helipad, and a wine cellar carved directly into the ancient rock face.','img'=>'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=1920&q=90','year_built'=>2019,'garage'=>3,'status'=>'active'],
  2 => ['id'=>2,'title'=>'Sky Penthouse 88','location'=>'Manhattan, New York','price'=>28000000,'type'=>'Penthouse','beds'=>5,'baths'=>6,'sqft'=>6200,'description'=>'Occupying the entire 88th floor of One Billionaires\' Row, this extraordinary penthouse redefines urban luxury. Floor-to-ceiling glass panels offer 360-degree views of Manhattan\'s iconic skyline. Features include a private rooftop terrace, climate-controlled art storage, 24-karat gold leaf ceiling details in the principal salon, and a smart home system controlling every environment parameter.','img'=>'https://images.unsplash.com/photo-1567767292278-a4f21aa2d36e?w=1920&q=90','year_built'=>2022,'garage'=>4,'status'=>'active'],
  3 => ['id'=>3,'title'=>'Palm Crest Mansion','location'=>'Palm Beach, Florida','price'=>9750000,'type'=>'Mansion','beds'=>8,'baths'=>10,'sqft'=>11200,'description'=>'Set behind private gates on one of Palm Beach\'s most coveted addresses, Palm Crest Mansion is the pinnacle of Old Florida grandeur. The estate features a main residence, two guest cottages, a full tennis court, resort-style pool complex, and 250 feet of direct intracoastal waterfront with a private dock.','img'=>'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=1920&q=90','year_built'=>2016,'garage'=>6,'status'=>'active'],
];
if (!$p) $p = $fallbackProps[$id] ?? $fallbackProps[1];

$pageTitle = htmlspecialchars($p['title']) . ' — Elite Estates';

$galleryImgs = [
  'https://images.unsplash.com/photo-1600210492493-0946911123ea?w=800&q=80',
  'https://images.unsplash.com/photo-1560185007-cde436f6a4d0?w=800&q=80',
  'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=800&q=80',
  'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800&q=80',
  'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=800&q=80',
  'https://images.unsplash.com/photo-1502005229762-cf1b2da7c5d6?w=800&q=80',
];
?>
<?php include '../includes/header.php'; ?>

<!-- PROPERTY HERO -->
<div class="property-detail-hero" style="margin-top:0">
  <img src="<?= $p['img'] ?? 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=1920&q=90' ?>" alt="<?= htmlspecialchars($p['title']) ?>">
  <div style="position:absolute;inset:0;background:linear-gradient(to bottom,transparent 20%,rgba(6,14,28,.98) 100%)"></div>
  <nav style="position:absolute;top:100px;left:4rem;z-index:2;display:flex;gap:.5rem;align-items:center;font-size:.7rem;letter-spacing:.15em;text-transform:uppercase;color:var(--text-muted)">
    <a href="../index.php" style="color:var(--text-muted);transition:color .3s" onmouseover="this.style.color='var(--platinum)'" onmouseout="this.style.color='var(--text-muted)'">Home</a>
    <span style="color:rgba(184,196,212,.4)">›</span>
    <a href="search.php" style="color:var(--text-muted);transition:color .3s" onmouseover="this.style.color='var(--platinum)'" onmouseout="this.style.color='var(--text-muted)'">Properties</a>
    <span style="color:rgba(184,196,212,.4)">›</span>
    <span style="color:var(--platinum)"><?= htmlspecialchars($p['title']) ?></span>
  </nav>
  <div class="hero-info" style="position:absolute;bottom:3rem;left:4rem;z-index:2">
    <div style="display:inline-block;background:var(--platinum);color:var(--midnight);font-size:.55rem;letter-spacing:.25em;text-transform:uppercase;padding:.3rem .8rem;font-weight:700;margin-bottom:1rem"><?= ucfirst($p['type'] ?? 'Property') ?></div>
    <h1 style="font-size:clamp(2rem,5vw,4rem);margin-bottom:.5rem;opacity:1"><?= htmlspecialchars($p['title']) ?></h1>
    <div style="font-size:.8rem;letter-spacing:.15em;text-transform:uppercase;color:var(--text-light)">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;vertical-align:middle;margin-right:4px"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
      <?= htmlspecialchars($p['location'] ?? 'Prime Location') ?>
    </div>
  </div>
</div>

<!-- BODY -->
<div style="background:var(--midnight)">
<div class="property-detail-body">
  <!-- LEFT: Details -->
  <div>
    <!-- Price row -->
    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:2rem">
      <div>
        <div style="font-size:.65rem;letter-spacing:.25em;text-transform:uppercase;color:var(--platinum);margin-bottom:.3rem">Asking Price</div>
        <div style="font-family:'Playfair Display',serif;font-size:3rem;color:var(--warm-white);line-height:1">
          $<?= number_format($p['price']) ?>
        </div>
      </div>
      <div style="display:flex;gap:1rem">
        <a href="?id=<?= $p['id'] ?>&fav=<?= $p['id'] ?>" style="display:flex;align-items:center;gap:.5rem;border:1px solid rgba(184,196,212,.3);padding:.7rem 1.2rem;font-size:.65rem;letter-spacing:.15em;text-transform:uppercase;color:var(--platinum);transition:all .3s" onmouseover="this.style.background='rgba(184,196,212,.1)'" onmouseout="this.style.background='transparent'">
          ♡ Save
        </a>
        <button onclick="shareProperty('<?= addslashes($p['title']) ?>', location.href)" style="display:flex;align-items:center;gap:.5rem;border:1px solid rgba(255,255,255,.15);padding:.7rem 1.2rem;font-size:.65rem;letter-spacing:.15em;text-transform:uppercase;color:var(--text-light);background:none;cursor:pointer;transition:all .3s" onmouseover="this.style.borderColor='rgba(184,196,212,.3)';this.style.color='var(--platinum)'" onmouseout="this.style.borderColor='rgba(255,255,255,.15)';this.style.color='var(--text-muted)'">
          ↗ Share
        </button>
      </div>
    </div>

    <!-- Features bar -->
    <div class="property-features">
      <div style="text-align:center">
        <svg class="feature-icon" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 22V8l9-6 9 6v14"/><path d="M9 22V12h6v10"/></svg>
        <div class="feature-val"><?= $p['beds'] ?? '—' ?></div>
        <div class="feature-lbl">Bedrooms</div>
      </div>
      <div style="text-align:center">
        <svg class="feature-icon" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 6L4 11v7h16v-7L15 6"/><path d="M4 11h16"/></svg>
        <div class="feature-val"><?= $p['baths'] ?? '—' ?></div>
        <div class="feature-lbl">Bathrooms</div>
      </div>
      <div style="text-align:center">
        <svg class="feature-icon" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18"/></svg>
        <div class="feature-val"><?= is_numeric($p['sqft'] ?? null) ? number_format($p['sqft']) : ($p['sqft'] ?? '—') ?></div>
        <div class="feature-lbl">Sq Ft</div>
      </div>
      <div style="text-align:center">
        <svg class="feature-icon" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="3" width="22" height="13"/><path d="M1 16l2 4h18l2-4"/></svg>
        <div class="feature-val"><?= $p['garage'] ?? '—' ?></div>
        <div class="feature-lbl">Garage</div>
      </div>
      <div style="text-align:center">
        <svg class="feature-icon" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        <div class="feature-val"><?= $p['year_built'] ?? '—' ?></div>
        <div class="feature-lbl">Year Built</div>
      </div>
    </div>

    <!-- Description -->
    <div class="section-label" style="margin-bottom:.75rem">About This Property</div>
    <div class="gold-line"></div>
    <p style="font-size:.9rem;line-height:1.9;color:var(--text-light);margin-top:1.5rem">
      <?= nl2br(htmlspecialchars($p['description'] ?? 'A truly extraordinary property in a prime location, offering unparalleled luxury and sophistication. Contact us for a private viewing.')) ?>
    </p>

    <!-- Amenities -->
    <div style="margin-top:3rem">
      <div class="section-label" style="margin-bottom:.75rem">Amenities & Features</div>
      <div class="gold-line"></div>
      <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:.75rem;margin-top:1.5rem">
        <?php
        $amenities = [
          ['icon'=>'🏊','label'=>'Infinity Pool'],['icon'=>'🧖','label'=>'Spa & Wellness'],
          ['icon'=>'🍷','label'=>'Wine Cellar'],['icon'=>'🎭','label'=>'Home Cinema'],
          ['icon'=>'🏋️','label'=>'Private Gym'],['icon'=>'🌿','label'=>'Landscaped Gardens'],
          ['icon'=>'🔒','label'=>'24/7 Security'],['icon'=>'🚁','label'=>'Helipad'],
          ['icon'=>'🚗','label'=>'Private Garage'],['icon'=>'🌊','label'=>'Waterfront Access'],
          ['icon'=>'♟️','label'=>'Games Room'],['icon'=>'📚','label'=>'Library'],
        ];
        foreach($amenities as $a):
        ?>
        <div style="display:flex;align-items:center;gap:.75rem;font-size:.8rem;color:var(--text-light);padding:.6rem 0;border-bottom:1px solid rgba(255,255,255,.04)">
          <span><?= $a['icon'] ?></span><?= $a['label'] ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Gallery -->
    <div style="margin-top:3rem">
      <div class="section-label" style="margin-bottom:.75rem">Photo Gallery</div>
      <div class="gold-line"></div>
      <div class="gallery-grid" style="margin-top:1.5rem">
        <?php foreach($galleryImgs as $gi): ?>
        <img src="<?= $gi ?>" alt="Gallery" loading="lazy">
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- RIGHT: Inquiry Card -->
  <div>
    <div class="inquiry-card">
      <div style="font-size:.65rem;letter-spacing:.25em;text-transform:uppercase;color:var(--platinum);margin-bottom:.4rem">Asking Price</div>
      <div class="inquiry-price">$<?= number_format($p['price']) ?></div>

      <h3>Request a Private Viewing</h3>
      <p style="font-size:.78rem;color:var(--text-muted);margin-bottom:2rem;line-height:1.7">Speak with a dedicated advisor about this property. All inquiries are handled with complete discretion.</p>

      <?php if ($inquirySent): ?>
        <div class="success-msg">Thank you. Your inquiry has been received. An advisor will contact you within 24 hours.</div>
      <?php else: ?>
        <?php if ($inquiryError): ?>
          <div class="error-msg"><?= htmlspecialchars($inquiryError) ?></div>
        <?php endif; ?>
        <form method="POST">
          <input type="hidden" name="inquiry" value="1">
          <div class="form-group">
            <label>Full Name *</label>
            <input type="text" name="name" placeholder="Your name" required>
          </div>
          <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" placeholder="your@email.com" required>
          </div>
          <div class="form-group">
            <label>Phone</label>
            <input type="tel" name="phone" placeholder="+1 (000) 000-0000">
          </div>
          <div class="form-group">
            <label>Message *</label>
            <textarea name="message" placeholder="I would like to arrange a private viewing...">I'm interested in <?= htmlspecialchars($p['title']) ?> and would like to arrange a private viewing.</textarea>
          </div>
          <button type="submit" class="btn-luxury" style="width:100%;justify-content:center">
            <span>Send Inquiry</span>
          </button>
        </form>
      <?php endif; ?>

      <!-- Agent Card -->
      <div style="margin-top:2rem;padding-top:2rem;border-top:1px solid rgba(184,196,212,.15)">
        <div style="display:flex;align-items:center;gap:1rem">
          <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?w=100&q=80" style="width:50px;height:50px;border-radius:50%;object-fit:cover;border:2px solid var(--platinum)" alt="Agent">
          <div>
            <div style="font-size:.85rem;font-weight:600;color:var(--warm-white)">Alexander Reed</div>
            <div style="font-size:.65rem;letter-spacing:.1em;text-transform:uppercase;color:var(--platinum)">Senior Property Advisor</div>
            <div style="font-size:.72rem;color:var(--text-muted);margin-top:.2rem">+1 (212) 555-0188</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<?php include '../includes/footer.php'; ?>
