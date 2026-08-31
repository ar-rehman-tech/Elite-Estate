<?php
$pageTitle = 'Contact Us — Elite Estates';
$pageDesc  = 'Get in touch with Elite Estates. Our advisors are available to assist with all your luxury property needs.';
require_once dirname(__DIR__) . '/includes/config.php';
include dirname(__DIR__) . '/includes/db.php';
include dirname(__DIR__) . '/includes/auth.php';
?>
<?php include dirname(__DIR__) . '/includes/header.php'; ?>

<!-- PAGE HERO -->
<section class="hero" style="min-height:55vh" id="home">
  <div class="hero-bg" style="background-image:url('https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=1800&q=80')"></div>
  <div class="hero-badge">We're Here to Help</div>
  <h1>Contact <em>Our Team</em></h1>
  <p class="hero-sub">Reach out to our advisors for personalised assistance with any luxury property enquiry</p>
</section>

<!-- CONTACT SECTION -->
<section style="padding:6rem 2rem;max-width:1200px;margin:0 auto">
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:start" class="contact-layout">

    <!-- CONTACT INFO -->
    <div>
      <div class="section-label">Get In Touch</div>
      <h2 style="margin:.75rem 0 1rem">We'd Love to <em style="font-style:italic;color:var(--platinum)">Hear From You</em></h2>
      <div class="section-divider" style="margin:0 0 2rem"></div>
      <p style="color:var(--silver);line-height:1.8;margin-bottom:2.5rem;font-size:.9rem">
        Whether you're buying, selling, or simply exploring the possibilities, our team of expert advisors is ready to provide the guidance you deserve. Every enquiry is handled with complete discretion.
      </p>

      <!-- INFO CARDS -->
      <div style="display:flex;flex-direction:column;gap:1.5rem">

        <div style="display:flex;align-items:flex-start;gap:1.25rem;padding:1.5rem;background:var(--panel);border:1px solid var(--border);border-radius:4px">
          <div style="flex-shrink:0;width:44px;height:44px;background:rgba(184,196,212,0.08);border:1px solid var(--border-bright);border-radius:4px;display:flex;align-items:center;justify-content:center">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--platinum)" stroke-width="1.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.6 1.21h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.8A16 16 0 0 0 16 16.91l.94-.94a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          </div>
          <div>
            <div style="font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;color:var(--platinum-muted);margin-bottom:.3rem">Phone</div>
            <a href="tel:+12345678900" style="color:var(--warm-white);font-size:.95rem;text-decoration:none">+1 (234) 567-8900</a>
          </div>
        </div>

        <div style="display:flex;align-items:flex-start;gap:1.25rem;padding:1.5rem;background:var(--panel);border:1px solid var(--border);border-radius:4px">
          <div style="flex-shrink:0;width:44px;height:44px;background:rgba(184,196,212,0.08);border:1px solid var(--border-bright);border-radius:4px;display:flex;align-items:center;justify-content:center">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--platinum)" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          </div>
          <div>
            <div style="font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;color:var(--platinum-muted);margin-bottom:.3rem">Email</div>
            <a href="mailto:hello@eliteestates.com" style="color:var(--warm-white);font-size:.95rem;text-decoration:none">hello@eliteestates.com</a>
          </div>
        </div>

        <div style="display:flex;align-items:flex-start;gap:1.25rem;padding:1.5rem;background:var(--panel);border:1px solid var(--border);border-radius:4px">
          <div style="flex-shrink:0;width:44px;height:44px;background:rgba(184,196,212,0.08);border:1px solid var(--border-bright);border-radius:4px;display:flex;align-items:center;justify-content:center">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--platinum)" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          </div>
          <div>
            <div style="font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;color:var(--platinum-muted);margin-bottom:.3rem">Office Hours</div>
            <div style="color:var(--warm-white);font-size:.95rem">Monday – Friday, 9am – 7pm</div>
            <div style="color:var(--silver);font-size:.82rem;margin-top:.2rem">Saturday by appointment</div>
          </div>
        </div>

      </div>
    </div>

    <!-- CONTACT FORM -->
    <div style="background:var(--panel);border:1px solid var(--border);padding:2.5rem;border-radius:4px">
      <div style="font-size:.7rem;letter-spacing:.18em;text-transform:uppercase;color:var(--platinum);margin-bottom:1.75rem">Send Us a Message</div>

      <form action="#" method="POST" id="contactForm">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem">
          <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" placeholder="James" required>
          </div>
          <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" placeholder="Harrington" required>
          </div>
        </div>

        <div class="form-group" style="margin-bottom:1.25rem">
          <label>Email Address</label>
          <input type="email" name="email" placeholder="james@example.com" required>
        </div>

        <div class="form-group" style="margin-bottom:1.25rem">
          <label>Phone Number</label>
          <input type="tel" name="phone" placeholder="+1 (234) 567-8900">
        </div>

        <div class="form-group" style="margin-bottom:1.25rem">
          <label>Enquiry Type</label>
          <select name="enquiry_type">
            <option value="">Select enquiry type</option>
            <option value="buying">Buying a Property</option>
            <option value="selling">Selling a Property</option>
            <option value="investment">Investment Advisory</option>
            <option value="valuation">Property Valuation</option>
            <option value="other">General Enquiry</option>
          </select>
        </div>

        <div class="form-group" style="margin-bottom:2rem">
          <label>Message</label>
          <textarea name="message" rows="5" placeholder="Tell us about your property requirements, budget, preferred locations..." style="width:100%;resize:vertical" required></textarea>
        </div>

        <button type="submit" class="btn-luxury" style="width:100%;justify-content:center" onclick="handleContactSubmit(event)">
          <span>Send Message</span>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </button>

        <p style="font-size:.72rem;color:var(--silver);text-align:center;margin-top:1rem;line-height:1.5">
          Your information is handled with complete discretion. We will respond within 24 hours.
        </p>
      </form>
    </div>

  </div>
</section>

<!-- PARALLAX BANNER -->
<div class="parallax-banner">
  <div class="parallax-banner-bg"></div>
  <div class="parallax-banner-content">
    <div class="section-label" style="text-align:center;display:block;margin-bottom:.5rem">Begin Your Journey</div>
    <h2>Explore Our <em style="color:var(--platinum);font-style:italic">Curated</em><br>Property Collection</h2>
    <div class="section-divider" style="margin:1.5rem auto"></div>
    <a href="<?= siteUrl('pages/search.php') ?>" class="btn-luxury" style="margin-top:.5rem">
      <span>Browse Properties</span>
    </a>
  </div>
</div>

<style>
.contact-layout { grid-template-columns: 1fr 1fr; }
@media (max-width: 768px) {
  .contact-layout { grid-template-columns: 1fr !important; gap: 2.5rem !important; }
}
.form-group { display: flex; flex-direction: column; gap: .5rem; }
.form-group label { font-size: .72rem; letter-spacing: .12em; text-transform: uppercase; color: var(--platinum-muted, var(--platinum)); }
.form-group input,
.form-group select,
.form-group textarea {
  background: rgba(255,255,255,0.04);
  border: 1px solid var(--border);
  color: var(--warm-white);
  padding: .75rem 1rem;
  font-family: 'DM Sans', sans-serif;
  font-size: .88rem;
  border-radius: 2px;
  outline: none;
  transition: border-color .2s;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus { border-color: rgba(184,196,212,0.7); }
.form-group select option { background: var(--midnight, #060e1c); color: var(--warm-white); }
</style>

<script>
function handleContactSubmit(e) {
  e.preventDefault();
  const btn = e.currentTarget;
  btn.innerHTML = '<span>Message Sent!</span>';
  btn.style.opacity = '0.7';
  btn.disabled = true;
  setTimeout(() => {
    btn.innerHTML = '<span>Send Message</span><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>';
    btn.style.opacity = '';
    btn.disabled = false;
    document.getElementById('contactForm').reset();
  }, 3000);
}
</script>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
