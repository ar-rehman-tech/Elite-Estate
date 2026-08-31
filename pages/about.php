<?php
$pageTitle = 'About Us — Elite Estates';
$pageDesc  = 'Learn about Elite Estates — a legacy of luxury real estate excellence since 2005.';
require_once dirname(__DIR__) . '/includes/config.php';
include dirname(__DIR__) . '/includes/db.php';
include dirname(__DIR__) . '/includes/auth.php';
?>
<?php include dirname(__DIR__) . '/includes/header.php'; ?>

<!-- PAGE HERO -->
<section class="hero" style="min-height:55vh" id="home">
  <div class="hero-bg" style="background-image:url('https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?w=1800&q=80')"></div>
  <div class="hero-badge">Established 2005</div>
  <h1>About <em>Elite Estates</em></h1>
  <p class="hero-sub">A legacy of excellence in luxury real estate — crafted for the world's most discerning clients</p>
</section>

<!-- INTRO -->
<section style="padding:6rem 2rem;max-width:900px;margin:0 auto;text-align:center">
  <div class="section-label">Our Story</div>
  <h2 style="margin:.75rem 0 1.5rem">A Legacy of <em style="font-style:italic;color:var(--platinum)">Excellence</em></h2>
  <div class="section-divider" style="margin:0 auto 2rem"></div>
  <p style="font-size:1.05rem;line-height:1.9;color:var(--silver);max-width:720px;margin:0 auto 1.5rem">
    Founded in 2005, Elite Estates has grown from a boutique consultancy into one of the world's foremost luxury real estate agencies. With a singular focus on extraordinary properties and an uncompromising standard of service, we have guided thousands of clients to their perfect homes across five continents.
  </p>
  <p style="font-size:1rem;line-height:1.9;color:var(--silver);max-width:720px;margin:0 auto">
    Every transaction we undertake is built on discretion, trust, and a deep knowledge of the ultra-prime market. We believe that a home is not merely an address — it is an expression of identity, aspiration, and legacy.
  </p>
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

<!-- SERVICES / WHY US -->
<section class="services-section" id="services">
  <div class="section-header">
    <div class="section-label">Our Expertise</div>
    <h2>What We <em style="font-style:italic;color:var(--platinum)">Offer</em></h2>
    <div class="section-divider"></div>
    <p style="max-width:560px;margin:0 auto;font-size:.85rem">Six pillars of service that define the Elite Estates experience</p>
  </div>

  <div class="services-grid">
    <div class="service-card">
      <div class="service-num">01</div>
      <svg class="service-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 22V8l9-6 9 6v14"/><path d="M9 22V12h6v10"/></svg>
      <h4>Exclusive Listings</h4>
      <p>Access to properties not listed publicly — an exclusive portfolio available only to our privileged clientele worldwide.</p>
    </div>
    <div class="service-card">
      <div class="service-num">02</div>
      <svg class="service-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
      <h4>White Glove Service</h4>
      <p>Your dedicated advisor coordinates every detail from private viewings and legal review to concierge relocation support.</p>
    </div>
    <div class="service-card">
      <div class="service-num">03</div>
      <svg class="service-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      <h4>Verified & Secure</h4>
      <p>Every property undergoes rigorous due diligence. Your investment is protected by our team of legal and financial experts.</p>
    </div>
    <div class="service-card">
      <div class="service-num">04</div>
      <svg class="service-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
      <h4>Global Reach</h4>
      <p>Offices in 40+ markets across Europe, the Americas, Middle East and Asia, offering unrivalled local expertise everywhere.</p>
    </div>
    <div class="service-card">
      <div class="service-num">05</div>
      <svg class="service-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
      <h4>Investment Advisory</h4>
      <p>Strategic guidance on luxury real estate as a wealth-building asset class, with market analysis and portfolio management.</p>
    </div>
    <div class="service-card">
      <div class="service-num">06</div>
      <svg class="service-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
      <h4>Lifestyle Curation</h4>
      <p>Beyond the property — we curate the lifestyle. Interior design partners, private clubs, schools and community connections included.</p>
    </div>
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
      <span>Explore Properties</span>
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

<!-- CTA -->
<div class="newsletter-section" style="justify-content:center;flex-direction:column;text-align:center;gap:2rem">
  <div class="newsletter-text" style="text-align:center">
    <div class="section-label">Ready to Begin?</div>
    <h2>Find Your <em style="font-style:italic;color:var(--platinum)">Dream</em> Property</h2>
    <p style="margin-top:.75rem;font-size:.82rem">Browse our curated collection of the world's finest homes.</p>
  </div>
  <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap">
    <a href="<?= siteUrl('pages/search.php') ?>" class="btn-luxury"><span>Explore Properties</span></a>
    <a href="<?= siteUrl('pages/contact.php') ?>" class="btn-outline">Contact Us</a>
  </div>
</div>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
