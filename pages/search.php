<?php
$pageTitle = 'Properties — Elite Estates';
$base = '../';
require_once '../includes/config.php';
include '../includes/db.php';
include '../includes/auth.php';

$db = DB::connect();

// Build query
$where  = ["status = 'active'"];
$params = [];

$q        = trim($_GET['q'] ?? '');
$type     = trim($_GET['type'] ?? '');
$location = trim($_GET['location'] ?? '');
$price    = $_GET['price'] ?? '';
$beds     = $_GET['beds'] ?? '';
$sort     = $_GET['sort'] ?? 'newest';

if ($q) {
    $where[]  = "(title LIKE ? OR description LIKE ? OR location LIKE ?)";
    $params[] = "%$q%"; $params[] = "%$q%"; $params[] = "%$q%";
}
if ($type)     { $where[] = "type = ?";     $params[] = $type; }
if ($location) { $where[] = "location LIKE ?"; $params[] = "%$location%"; }
if ($beds)     { $where[] = "beds >= ?";    $params[] = (int)$beds; }
if ($price) {
    if ($price === '30000000+') {
        $where[] = "price >= 30000000";
    } elseif (strpos($price, '-') !== false) {
        [$min, $max] = explode('-', $price);
        $where[] = "price BETWEEN ? AND ?";
        $params[] = (int)$min; $params[] = (int)$max;
    }
}

$orderBy = match($sort) {
    'price_asc'  => 'price ASC',
    'price_desc' => 'price DESC',
    'oldest'     => 'created_at ASC',
    default      => 'created_at DESC',
};

$sql   = "SELECT * FROM properties WHERE " . implode(' AND ', $where) . " ORDER BY $orderBy";
$stmt  = $db->prepare($sql);
$stmt->execute($params);
$props = $stmt->fetchAll();

// Fallback properties
$fallback = [
  ['id'=>1,'title'=>'Villa Serenita','location'=>'Amalfi Coast, Italy','price'=>12500000,'type'=>'Villa','beds'=>6,'baths'=>7,'sqft'=>8400,'img'=>'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=800&q=80'],
  ['id'=>2,'title'=>'Sky Penthouse 88','location'=>'Manhattan, New York','price'=>28000000,'type'=>'Penthouse','beds'=>5,'baths'=>6,'sqft'=>6200,'img'=>'https://images.unsplash.com/photo-1567767292278-a4f21aa2d36e?w=800&q=80'],
  ['id'=>3,'title'=>'Palm Crest Mansion','location'=>'Palm Beach, Florida','price'=>9750000,'type'=>'Mansion','beds'=>8,'baths'=>10,'sqft'=>11200,'img'=>'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&q=80'],
  ['id'=>4,'title'=>'The Riviera Estate','location'=>'Nice, France','price'=>18200000,'type'=>'Estate','beds'=>7,'baths'=>8,'sqft'=>9600,'img'=>'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?w=800&q=80'],
  ['id'=>5,'title'=>'Malibu Bluffs Villa','location'=>'Malibu, California','price'=>14500000,'type'=>'Villa','beds'=>5,'baths'=>6,'sqft'=>7100,'img'=>'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&q=80'],
  ['id'=>6,'title'=>'Kensington Townhouse','location'=>'London, United Kingdom','price'=>8900000,'type'=>'Apartment','beds'=>4,'baths'=>5,'sqft'=>4800,'img'=>'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=800&q=80'],
  ['id'=>7,'title'=>'Desert Rose Compound','location'=>'Dubai, UAE','price'=>22000000,'type'=>'Villa','beds'=>9,'baths'=>11,'sqft'=>15000,'img'=>'https://images.unsplash.com/photo-1600566753376-12c8ab7fb75b?w=800&q=80'],
  ['id'=>8,'title'=>'Côte d\'Azur Retreat','location'=>'Cannes, France','price'=>16500000,'type'=>'Villa','beds'=>6,'baths'=>7,'sqft'=>7800,'img'=>'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&q=80'],
  ['id'=>9,'title'=>'Aspen Mountain Estate','location'=>'Aspen, Colorado','price'=>31000000,'type'=>'Estate','beds'=>10,'baths'=>12,'sqft'=>18200,'img'=>'https://images.unsplash.com/photo-1510798831971-661eb04b3739?w=800&q=80'],
];
$displayProps = !empty($props) ? $props : $fallback;
?>
<?php include '../includes/header.php'; ?>

<!-- SEARCH HERO -->
<div class="search-hero" style="padding-top:8rem">
  <div class="section-label" style="text-align:center;display:block">Property Collection</div>
  <h1 style="text-align:center;font-size:clamp(2rem,5vw,4rem)">Find Your <em style="color:var(--platinum);font-style:italic">Dream Home</em></h1>
  <div class="section-divider" style="margin:1.5rem auto"></div>

  <!-- Search Form -->
  <form method="GET" action="" style="max-width:1000px;margin:3rem auto 0">
    <div class="search-bar" style="grid-template-columns:1fr 1fr 1fr 1fr auto">
      <div class="search-field">
        <label>Keyword</label>
        <input type="text" name="q" placeholder="Search..." value="<?= htmlspecialchars($q) ?>">
      </div>
      <div class="search-field">
        <label>Type</label>
        <select name="type">
          <option value="">Any Type</option>
          <?php foreach(['Villa','Penthouse','Mansion','Estate','Apartment'] as $t): ?>
            <option value="<?= strtolower($t) ?>" <?= strtolower($type)==strtolower($t)?'selected':'' ?>><?= $t ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="search-field">
        <label>Price Range</label>
        <select name="price">
          <option value="">Any</option>
          <option value="0-5000000" <?= $price=='0-5000000'?'selected':'' ?>>Under $5M</option>
          <option value="5000000-15000000" <?= $price=='5000000-15000000'?'selected':'' ?>>$5M – $15M</option>
          <option value="15000000-30000000" <?= $price=='15000000-30000000'?'selected':'' ?>>$15M – $30M</option>
          <option value="30000000+" <?= $price=='30000000+'?'selected':'' ?>>$30M+</option>
        </select>
      </div>
      <div class="search-field">
        <label>Min Beds</label>
        <select name="beds">
          <option value="">Any</option>
          <?php foreach([2,3,4,5,6,7] as $b): ?>
            <option value="<?= $b ?>" <?= $beds==$b?'selected':'' ?>><?= $b ?>+</option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="search-btn">Search</button>
    </div>
  </form>
</div>

<!-- RESULTS -->
<div style="background:var(--midnight);padding:3rem 4rem">
  <!-- Sort & Count bar -->
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2.5rem;padding-bottom:1.5rem;border-bottom:1px solid rgba(184,196,212,.1)">
    <p style="font-size:.8rem;color:var(--text-muted)">
      <span style="color:var(--platinum);font-family:'Playfair Display',serif;font-size:1.3rem"><?= count($displayProps) ?></span> properties found
    </p>
    <form method="GET" id="sortForm" style="display:flex;align-items:center;gap:1rem">
      <?php foreach($_GET as $k=>$v): if($k!=='sort'): ?>
        <input type="hidden" name="<?= htmlspecialchars($k) ?>" value="<?= htmlspecialchars($v) ?>">
      <?php endif; endforeach; ?>
      <label style="font-size:.65rem;letter-spacing:.2em;text-transform:uppercase;color:var(--platinum)">Sort By</label>
      <select name="sort" onchange="document.getElementById('sortForm').submit()"
        style="background:var(--panel);border:1px solid rgba(184,196,212,.3);color:var(--warm-white);padding:.5rem .8rem;font-size:.8rem;font-family:'DM Sans',sans-serif;cursor:pointer">
        <option value="newest" <?= $sort=='newest'?'selected':'' ?>>Newest First</option>
        <option value="price_desc" <?= $sort=='price_desc'?'selected':'' ?>>Highest Price</option>
        <option value="price_asc" <?= $sort=='price_asc'?'selected':'' ?>>Lowest Price</option>
      </select>
    </form>
  </div>

  <?php if (empty($displayProps)): ?>
    <div style="text-align:center;padding:6rem 0">
      <p style="font-size:1.2rem;color:var(--text-muted)">No properties found matching your criteria.</p>
      <a href="search.php" class="btn-outline" style="margin-top:2rem;display:inline-flex">Clear Filters</a>
    </div>
  <?php else: ?>
  <div class="search-results-grid">
    <?php
    $imgFallbacks = [
      'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=800&q=80',
      'https://images.unsplash.com/photo-1567767292278-a4f21aa2d36e?w=800&q=80',
      'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&q=80',
      'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?w=800&q=80',
      'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&q=80',
      'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=800&q=80',
      'https://images.unsplash.com/photo-1600566753376-12c8ab7fb75b?w=800&q=80',
      'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&q=80',
      'https://images.unsplash.com/photo-1510798831971-661eb04b3739?w=800&q=80',
    ];
    foreach ($displayProps as $i => $p):
      $img  = $p['img'] ?? $imgFallbacks[$i % count($imgFallbacks)];
      $loc  = $p['location'] ?? 'Prime Location';
      $beds = $p['beds'] ?? '–';
      $baths= $p['baths'] ?? '–';
      $sqft = $p['sqft'] ?? '–';
      $ptype= $p['type'] ?? 'Property';
    ?>
    <a href="property-details.php?id=<?= $p['id'] ?>" class="result-card" style="display:block;color:inherit">
      <div style="overflow:hidden;position:relative">
        <img src="<?= $img ?>" alt="<?= htmlspecialchars($p['title']) ?>" loading="lazy">
        <div style="position:absolute;top:1rem;left:1rem;background:var(--platinum);color:var(--midnight);font-size:.55rem;letter-spacing:.2em;text-transform:uppercase;padding:.25rem .7rem;font-weight:700"><?= ucfirst($ptype) ?></div>
        <?php
        $isFav = isset($_SESSION['fav']) && in_array($p['id'], $_SESSION['fav']);
        ?>
        <form method="POST" action="favorites.php" style="position:absolute;top:.9rem;right:.9rem">
          <input type="hidden" name="toggle" value="<?= $p['id'] ?>">
          <button type="submit" class="fav-btn" style="background:rgba(6,14,28,.6);border:1px solid rgba(184,196,212,.4);width:36px;height:36px;cursor:pointer;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(8px)">
            <span class="heart-icon" style="color:<?= $isFav ? 'var(--platinum)' : 'rgba(255,255,255,.6)' ?>;font-size:1rem"><?= $isFav ? '♥' : '♡' ?></span>
          </button>
        </form>
      </div>
      <div class="result-card-body">
        <div class="result-card-title"><?= htmlspecialchars($p['title']) ?></div>
        <div style="font-size:.7rem;color:var(--text-muted);letter-spacing:.1em;text-transform:uppercase;margin:.2rem 0 .8rem">
          <?= htmlspecialchars($loc) ?>
        </div>
        <div class="result-card-price">$<?= number_format($p['price']) ?></div>
        <div class="result-card-meta">
          <span><?= $beds ?> Beds</span>
          <span>·</span>
          <span><?= $baths ?> Baths</span>
          <span>·</span>
          <span><?= is_numeric($sqft) ? number_format($sqft) : $sqft ?> sqft</span>
        </div>
        <div style="margin-top:1.2rem;padding-top:1rem;border-top:1px solid rgba(184,196,212,.08);display:flex;justify-content:space-between;align-items:center">
          <span style="font-size:.65rem;letter-spacing:.15em;text-transform:uppercase;color:var(--platinum)">View Details</span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--platinum)" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </div>
      </div>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
