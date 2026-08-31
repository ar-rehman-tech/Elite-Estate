  </div> <!-- /.admin-content -->
</main> <!-- /.admin-main -->

<script>
  // Initialize Lucide Icons
  lucide.createIcons();

  // Mobile Sidebar Toggle
  const mobileToggle = document.getElementById('mobileToggle');
  const sidebar = document.getElementById('sidebar');

  if (mobileToggle && sidebar) {
    mobileToggle.addEventListener('click', () => {
      sidebar.classList.toggle('open');
    });
  }

  // Close sidebar when clicking outside on mobile
  document.addEventListener('click', (e) => {
    if (window.innerWidth <= 1024 && !sidebar.contains(e.target) && !mobileToggle.contains(e.target)) {
      sidebar.classList.remove('open');
    }
  });

  // Page Entry Animation
  gsap.from('.admin-content', {
    y: 20,
    opacity: 0,
    duration: 0.6,
    ease: 'power2.out',
    delay: 0.1
  });
</script>
</body>
</html>
