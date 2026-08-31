<!-- FOOTER -->
<footer class="footer">
  <div class="footer-grid">
    <div class="footer-brand">
      <div class="nav-logo" style="font-size:1.7rem">Elite <span style="color:var(--warm-white)">Estates</span></div>
      <p>Curating the world's most exceptional properties for discerning clients since 2005. Where luxury meets legacy.</p>
      <div class="footer-social">
        <!-- LinkedIn -->
        <a href="https://linkedin.com" target="_blank" rel="noopener" class="social-link" title="LinkedIn">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
        </a>
        <!-- Instagram -->
        <a href="https://instagram.com" target="_blank" rel="noopener" class="social-link" title="Instagram">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
        </a>
        <!-- Facebook -->
        <a href="https://facebook.com" target="_blank" rel="noopener" class="social-link" title="Facebook">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
        </a>
        <!-- YouTube -->
        <a href="https://youtube.com" target="_blank" rel="noopener" class="social-link" title="YouTube">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 0 0 1.46 6.42 29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.96-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="var(--deep)"/></svg>
        </a>
      </div>
    </div>

    <div class="footer-col">
      <h5>Properties</h5>
      <ul>
        <li><a href="<?= siteUrl('pages/search.php?type=villa') ?>">Villas</a></li>
        <li><a href="<?= siteUrl('pages/search.php?type=penthouse') ?>">Penthouses</a></li>
        <li><a href="<?= siteUrl('pages/search.php?type=mansion') ?>">Mansions</a></li>
        <li><a href="<?= siteUrl('pages/search.php?type=estate') ?>">Estates</a></li>
        <li><a href="<?= siteUrl('pages/search.php?type=apartment') ?>">Apartments</a></li>
      </ul>
    </div>

    <div class="footer-col">
      <h5>Company</h5>
      <ul>
        <li><a href="<?= siteUrl('pages/about.php') ?>">About Us</a></li>
        <li><a href="<?= siteUrl('pages/search.php') ?>">Browse All</a></li>
        <li><a href="<?= siteUrl('register.php') ?>">Join Us</a></li>
        <li><a href="<?= siteUrl('pages/contact.php') ?>">Contact</a></li>
      </ul>
    </div>

    <div class="footer-col">
      <h5>Contact</h5>
      <ul>
        <li><a href="tel:+12345678900">+1 (234) 567-8900</a></li>
        <li><a href="mailto:hello@eliteestates.com">hello@eliteestates.com</a></li>
      </ul>
    </div>
  </div>

  <div class="footer-bottom">
    <p>© <?= date('Y') ?> Elite Estates. All rights reserved.</p>
  </div>
</footer>

<script src="<?= asset('js/luxury.js') ?>"></script>
<?= $extraJs ?? '' ?>
</body>
</html>
