<?php
$base = '../';
require_once '../includes/config.php';
include '../includes/db.php';
include '../includes/auth.php';

// Handle toggle from form POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle'])) {
    if (!isset($_SESSION['fav'])) $_SESSION['fav'] = [];
    $fid = (int)$_POST['toggle'];
    if (in_array($fid, $_SESSION['fav'])) {
        $_SESSION['fav'] = array_values(array_filter($_SESSION['fav'], fn($v) => $v !== $fid));
    } else {
        $_SESSION['fav'][] = $fid;
    }
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'favorites.php'));
    exit;
}

// Remove single fav
if (isset($_GET['remove'])) {
    $rid = (int)$_GET['remove'];
    $_SESSION['fav'] = array_values(array_filter($_SESSION['fav'] ?? [], fn($v) => $v !== $rid));
    header('Location: favorites.php');
    exit;
}

// Clear all
if (isset($_GET['clear'])) {
    $_SESSION['fav'] = [];
    header('Location: favorites.php');
    exit;
}

$favIds = $_SESSION['fav'] ?? [];
$db = DB::connect();
$props = [];
if (!empty($favIds)) {
    $placeholders = implode(',', array_fill(0, count($favIds), '?'));
    $stmt = $db->prepare("SELECT * FROM properties WHERE id IN ($placeholders)");
    $stmt->execute($favIds);
    $props = $stmt->fetchAll();
}

$pageTitle = 'Saved Properties — Elite Estates';
include '../includes/header.php';
?>

<div class="page-header" style="padding-top:9rem;padding-bottom:3rem">
  <div class="section-label" style="display:block;margin-bottom:.5rem">Your Collection</div>
  <h1 style="font-size:clamp(2rem,5vw,3.5rem)">Saved <em style="color:var(--platinum);font-style:italic">Properties</em></h1>
  <div class="gold-line" style="margin:1.5rem 0"></div>
  <p style="color:var(--text-muted);font-size:.82rem">
    <?= count($favIds) ?> <?= count($favIds) === 1 ? 'property' : 'properties' ?> saved to your collection
    <?php if (!empty($favIds)): ?>
      · <a href="?clear=1" style="color:var(--platinum)" onclick="return confirm('Clear all saved properties?')">Clear all</a>
    <?php endif; ?>
  </p>
</div>

<div style="background:var(--midnight);min-height:50vh;padding:3rem 4rem">
  <?php if (empty($favIds)): ?>
    <div style="text-align:center;padding:6rem 0">
      <div style="font-size:4rem;margin-bottom:1.5rem;opacity:.3">♡</div>
      <h3 style="font-size:1.5rem;color:var(--text-muted);font-weight:400">Your saved collection is empty</h3>
      <p style="color:var(--text-muted);margin:.75rem 0 2rem;font-size:.85rem">Browse our portfolio and save properties that captivate you</p>
      <a href="search.php" class="btn-luxury"><span>Browse Properties</span></a>
    </div>
  <?php else: ?>
    <?php
    // Fallback if DB returns empty
    if (empty($props)) {
      $allFallback = [
        1 => ['id'=>1,'title'=>'Villa Serenita','location'=>'Amalfi Coast, Italy','price'=>12500000,'type'=>'Villa','beds'=>6,'baths'=>7,'sqft'=>8400,'img'=>'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=800&q=80'],
        2 => ['id'=>2,'title'=>'Sky Penthouse 88','location'=>'Manhattan, New York','price'=>28000000,'type'=>'Penthouse','beds'=>5,'baths'=>6,'sqft'=>6200,'img'=>'https://images.unsplash.com/photo-1567767292278-a4f21aa2d36e?w=800&q=80'],
        3 => ['id'=>3,'title'=>'Palm Crest Mansion','location'=>'Palm Beach, Florida','price'=>9750000,'type'=>'Mansion','beds'=>8,'baths'=>10,'sqft'=>11200,'img'=>'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&q=80'],
      ];
      $props = array_values(array_filter($allFallback, fn($p) => in_array($p['id'], $favIds)));
    }
    ?>
    <div class="search-results-grid">
      <?php
      $imgFallbacks = ['https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=800&q=80','https://images.unsplash.com/photo-1567767292278-a4f21aa2d36e?w=800&q=80','https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&q=80'];
      foreach($props as $i => $p):
        $img = $p['img'] ?? $imgFallbacks[$i % count($imgFallbacks)];
      ?>
      <div class="result-card">
        <div style="overflow:hidden;position:relative">
          <a href="property-details.php?id=<?= $p['id'] ?>">
            <img src="<?= $img ?>" alt="<?= htmlspecialchars($p['title']) ?>" loading="lazy">
          </a>
          <a href="?remove=<?= $p['id'] ?>" title="Remove from saved" style="position:absolute;top:.9rem;right:.9rem;background:rgba(6,14,28,.7);border:1px solid rgba(200,80,80,.4);width:36px;height:36px;display:flex;align-items:center;justify-content:center;color:#ff6b6b;font-size:1.1rem;backdrop-filter:blur(8px)" onmouseover="this.style.background='rgba(200,80,80,.2)'" onmouseout="this.style.background='rgba(6,14,28,.7)'">♥</a>
          <div style="position:absolute;top:1rem;left:1rem;background:var(--platinum);color:var(--midnight);font-size:.55rem;letter-spacing:.2em;text-transform:uppercase;padding:.25rem .7rem;font-weight:700"><?= ucfirst($p['type'] ?? 'Property') ?></div>
        </div>
        <div class="result-card-body">
          <a href="property-details.php?id=<?= $p['id'] ?>" style="color:inherit">
            <div class="result-card-title"><?= htmlspecialchars($p['title']) ?></div>
            <div style="font-size:.7rem;color:var(--text-muted);letter-spacing:.1em;text-transform:uppercase;margin:.2rem 0 .8rem"><?= htmlspecialchars($p['location'] ?? '') ?></div>
            <div class="result-card-price">$<?= number_format($p['price']) ?></div>
            <div class="result-card-meta">
              <span><?= $p['beds'] ?? '—' ?> Beds</span>
              <span>·</span>
              <span><?= $p['baths'] ?? '—' ?> Baths</span>
              <span>·</span>
              <span><?= is_numeric($p['sqft'] ?? null) ? number_format($p['sqft']) : ($p['sqft'] ?? '—') ?> sqft</span>
            </div>
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center;margin-top:4rem">
      <a href="search.php" class="btn-outline">Continue Browsing</a>
    </div>
  <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
