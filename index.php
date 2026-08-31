<?php
$pageTitle = 'Elite Estates — Luxury Real Estate';
$pageDesc  = 'Discover the world\'s most exceptional luxury properties.';
require_once 'includes/config.php';
include 'includes/db.php';
include 'includes/auth.php';

$db    = DB::connect();
$featured = $db->query("SELECT * FROM properties WHERE status = 'active' ORDER BY price DESC LIMIT 5")->fetchAll();
?>
<?php include 'includes/header.php'; ?>

<!-- HERO SECTION -->
<section class="hero" id="home">
  <div class="hero-bg"></div>

  <div class="hero-badge">Luxury Real Estate Collection 2025</div>
  <h1>Where Prestige<br><em>Meets Place</em></h1>
  <p class="hero-sub">Curated collection of the world's most extraordinary residential properties</p>
  <div class="hero-actions">
    <a href="<?= siteUrl('pages/search.php') ?>" class="btn-luxury">
      <span>Explore Properties</span>
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </a>
    <a href="#featured" class="btn-outline">
      <span>View Featured</span>
    </a>
  </div>

  <div class="scroll-indicator">
    <span>Scroll</span>
    <div class="scroll-line"></div>
  </div>
</section>

<!-- STATS BAR -->
<div class="stats-bar">
  <div class="stat-item reveal">
    <span class="stat-number" data-count="2400">0</span>
    <div class="stat-label">Exclusive Listings</div>
  </div>
  <div class="stat-item reveal">
    <span class="stat-number" data-count="850">0</span>
    <div class="stat-label">Properties Sold</div>
  </div>
  <div class="stat-item reveal">
    <span class="stat-number" data-count="40">0</span>
    <div class="stat-label">Global Markets</div>
  </div>
  <div class="stat-item reveal">
    <span class="stat-number" data-count="19">0</span>
    <div class="stat-label">Years of Excellence</div>
  </div>
</div>

<!-- FEATURED PROPERTIES -->
<section class="properties-section" id="featured">
  <div class="section-header">
    <div class="section-label">Handpicked for You</div>
    <h2>Featured <em style="font-style:italic;color:var(--platinum)">Properties</em></h2>
    <div class="section-divider"></div>
    <p style="max-width:560px;margin:0 auto;font-size:.85rem">An exclusive selection of extraordinary homes, each a masterpiece of architecture and design</p>
  </div>

  <div class="properties-grid">
    <?php
    // Fallback luxury properties if DB is empty
    $displayProps = !empty($featured) ? $featured : [
      ['id'=>1,'title'=>'Villa Serenita','location'=>'Amalfi Coast, Italy','price'=>'12,500,000','type'=>'Villa','beds'=>6,'baths'=>7,'sqft'=>8400,'img'=>'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=1200&q=80','tag'=>'New Listing'],
      ['id'=>2,'title'=>'Sky Penthouse 88','location'=>'Manhattan, New York','price'=>'28,000,000','type'=>'Penthouse','beds'=>5,'baths'=>6,'sqft'=>6200,'img'=>'https://images.unsplash.com/photo-1567767292278-a4f21aa2d36e?w=1200&q=80','tag'=>'Featured'],
      ['id'=>3,'title'=>'Palm Crest Mansion','location'=>'Palm Beach, Florida','price'=>'9,750,000','type'=>'Mansion','beds'=>8,'baths'=>10,'sqft'=>11200,'img'=>'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=1200&q=80','tag'=>'Exclusive'],
      ['id'=>4,'title'=>'The Riviera Estate','location'=>'Nice, France','price'=>'18,200,000','type'=>'Estate','beds'=>7,'baths'=>8,'sqft'=>9600,'img'=>'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?w=1200&q=80','tag'=>'Waterfront'],
      ['id'=>5,'title'=>'Malibu Bluffs Villa','location'=>'Malibu, California','price'=>'14,500,000','type'=>'Villa','beds'=>5,'baths'=>6,'sqft'=>7100,'img'=>'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1200&q=80','tag'=>'Ocean View'],
    ];
    $images = [
      'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=1200&q=80',
      'https://images.unsplash.com/photo-1567767292278-a4f21aa2d36e?w=1200&q=80',
      'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=1200&q=80',
      'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?w=1200&q=80',
      'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1200&q=80',
    ];
    $tags = ['New Listing','Featured','Exclusive','Waterfront','Ocean View'];
    foreach($displayProps as $i => $p):
      $imgSrc = $p['img'] ?? $images[$i % count($images)];
      $loc = $p['location'] ?? 'Prime Location';
      $beds = $p['beds'] ?? rand(4,8);
      $baths = $p['baths'] ?? rand(4,7);
      $sqft = $p['sqft'] ?? number_format(rand(4000,12000));
      $tag = $p['tag'] ?? $tags[$i % count($tags)];
      $featured_class = ($i === 0) ? 'featured' : '';
    ?>
    <a href="<?= siteUrl('pages/property-details.php?id=') ?><?= $p['id'] ?>" class="property-card <?= $featured_class ?>">
      <div style="overflow:hidden;position:relative">
        <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($p['title']) ?>" class="card-image" loading="lazy">
        <div class="card-view-btn">View Details</div>
      </div>
      <div class="card-content">
        <div class="card-badge"><?= $tag ?></div>
        <div class="card-title"><?= htmlspecialchars($p['title']) ?></div>
        <div class="card-location">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;vertical-align:middle;margin-right:4px"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          <?= htmlspecialchars($loc) ?>
        </div>
        <div class="card-price">
          $<?= number_format($p['price']) ?> <span>USD</span>
        </div>
        <div class="card-details">
          <div class="card-detail">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 22V8l9-6 9 6v14"/><path d="M9 22V12h6v10"/></svg>
            <?= $beds ?> Beds
          </div>
          <div class="card-detail">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 6L4 11v7h16v-7L15 6"/><path d="M4 11h16"/></svg>
            <?= $baths ?> Baths
          </div>
          <div class="card-detail">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18"/></svg>
            <?= is_numeric($sqft) ? number_format($sqft) : $sqft ?> sqft
          </div>
        </div>
      </div>
    </a>
    <?php endforeach; ?>
  </div>

  <div style="text-align:center;margin-top:4rem">
    <a href="<?= siteUrl('pages/search.php') ?>" class="btn-outline">View All Properties</a>
  </div>
</section>

<!-- SEARCH SECTION -->
<section class="search-section">
  <div class="search-container">
    <div class="section-header">
      <div class="section-label">Find Your Dream Home</div>
      <h2 style="color:var(--warm-white)">Search <em style="color:var(--platinum);font-style:italic">Properties</em></h2>
      <div class="section-divider" style="margin:1.5rem auto"></div>
    </div>
    <form action="pages/search.php" method="GET" style="display:contents">
      <div class="search-bar reveal">
        <div class="search-field">
          <label>Location</label>
          <input type="text" name="location" placeholder="City, Country...">
        </div>
        <div class="search-field">
          <label>Property Type</label>
          <select name="type">
            <option value="">Any Type</option>
            <option value="villa">Villa</option>
            <option value="penthouse">Penthouse</option>
            <option value="mansion">Mansion</option>
            <option value="estate">Estate</option>
            <option value="apartment">Apartment</option>
          </select>
        </div>
        <div class="search-field">
          <label>Price Range</label>
          <select name="price">
            <option value="">Any Budget</option>
            <option value="0-5000000">Under $5M</option>
            <option value="5000000-15000000">$5M – $15M</option>
            <option value="15000000-30000000">$15M – $30M</option>
            <option value="30000000+">$30M+</option>
          </select>
        </div>
        <button type="submit" class="search-btn">Search</button>
      </div>
    </form>
  </div>
</section>

<!-- PARALLAX BANNER -->
<div class="parallax-banner">
  <div class="parallax-banner-bg"></div>
  <div class="parallax-banner-content">
    <div class="section-label" style="text-align:center;display:block;margin-bottom:.5rem">Since 2005</div>
    <h2>Over <em style="color:var(--platinum);font-style:italic">$4.8 Billion</em><br>in Transactions Completed</h2>
    <div class="section-divider" style="margin:1.5rem auto"></div>
    <a href="<?= siteUrl('pages/search.php') ?>" class="btn-luxury" style="margin-top:.5rem">
      <span>Begin Your Journey</span>
    </a>
  </div>
</div>

<!-- TESTIMONIALS -->
<section class="testimonials-section">
  <div class="section-header">
    <div class="section-label">Client Stories</div>
    <h2>Voices of <em style="font-style:italic;color:var(--platinum)">Excellence</em></h2>
    <div class="section-divider"></div>
  </div>

  <div class="testimonials-wrapper">
    <div class="testimonial-card">
      <div class="testimonial-stars">
        <span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span>
      </div>
      <p class="testimonial-text">Elite Estates found us a palazzo in Tuscany beyond anything we imagined. The process was seamless, discreet, and impeccably handled at every turn.</p>
      <div class="testimonial-author">
        <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?w=100&q=80" alt="" class="author-avatar">
        <div>
          <div class="author-name">James Harrington</div>
          <div class="author-title">CEO, Harrington Capital</div>
        </div>
      </div>
    </div>

    <div class="testimonial-card">
      <div class="testimonial-stars">
        <span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span>
      </div>
      <p class="testimonial-text">The team's knowledge of the Dubai market is unparalleled. They secured our penthouse in the Palm before it ever reached the open market.</p>
      <div class="testimonial-author">
        <img src="https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?w=100&q=80" alt="" class="author-avatar">
        <div>
          <div class="author-name">Sofia Mendoza</div>
          <div class="author-title">Art Collector & Investor</div>
        </div>
      </div>
    </div>

    <div class="testimonial-card">
      <div class="testimonial-stars">
        <span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span><span class="star">★</span>
      </div>
      <p class="testimonial-text">From initial inquiry to keys in hand — six weeks. Their network and expertise made the purchase of our Manhattan townhouse effortless.</p>
      <div class="testimonial-author">
        <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=100&q=80" alt="" class="author-avatar">
        <div>
          <div class="author-name">Michael Chen</div>
          <div class="author-title">Managing Partner, Chen & Associates</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- NEWSLETTER -->
<div class="newsletter-section">
  <div class="newsletter-text">
    <div class="section-label">Stay Informed</div>
    <h2>Private <em style="font-style:italic;color:var(--platinum)">Collection</em><br>Notifications</h2>
    <p style="margin-top:.75rem;max-width:360px;font-size:.82rem">Be among the first to access new ultra-prime listings before they reach the market.</p>
  </div>
  <form class="newsletter-form" action="#" method="post">
    <input type="email" name="email" placeholder="Your email address">
    <button type="submit">Subscribe</button>
  </form>
</div>

<?php include 'includes/footer.php'; ?>
