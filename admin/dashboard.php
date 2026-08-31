<?php
$pageTitle = 'Dashboard — Elite Estates Admin';
require_once 'includes/header.php';

$db = DB::connect();
$propCount = $db->query("SELECT COUNT(*) FROM properties")->fetchColumn() ?? 0;
$userCount = $db->query("SELECT COUNT(*) FROM users")->fetchColumn() ?? 0;
$inqCount  = $db->query("SELECT COUNT(*) FROM inquiries")->fetchColumn() ?? 0;
$totalVal  = $db->query("SELECT SUM(price) FROM properties WHERE status='active'")->fetchColumn() ?? 0;

$recentProps = $db->query("SELECT * FROM properties ORDER BY id DESC LIMIT 5")->fetchAll();
$recentInqs  = $db->query("SELECT i.*, p.title as property_title FROM inquiries i LEFT JOIN properties p ON i.property_id = p.id ORDER BY i.id DESC LIMIT 5")->fetchAll();
?>

<style>
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
  }
  
  .stat-card {
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }
  
  .stat-icon {
    position: absolute;
    right: -10px;
    bottom: -10px;
    width: 100px;
    height: 100px;
    opacity: 0.03;
    color: var(--warm-white);
  }
  
  .stat-label {
    font-size: 0.75rem;
    color: var(--platinum);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 0.5rem;
  }
  
  .stat-value {
    font-size: 2.2rem;
    font-family: var(--font-heading);
    font-weight: 400;
    color: var(--warm-white);
  }

  .stat-value span {
    font-size: 1rem;
    color: var(--emerald);
    margin-left: 0.5rem;
    font-family: var(--font-body);
  }

  .dashboard-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
  }

  @media (max-width: 1200px) {
    .dashboard-grid {
      grid-template-columns: 1fr;
    }
  }

  .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border);
  }
  
  .card-title {
    font-family: var(--font-heading);
    font-size: 1.4rem;
    font-weight: 400;
    color: var(--warm-white);
  }

  .view-all-link {
    color: var(--emerald);
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    text-decoration: none;
    transition: color 0.3s;
  }

  .view-all-link:hover {
    color: var(--emerald-hover);
  }

  .table-responsive {
    overflow-x: auto;
  }

  .admin-table {
    width: 100%;
    border-collapse: collapse;
  }
  
  .admin-table th {
    text-align: left;
    padding: 1rem;
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--platinum);
    border-bottom: 1px solid var(--border);
  }
  
  .admin-table td {
    padding: 1rem;
    border-bottom: 1px solid rgba(255,255,255,0.03);
    font-size: 0.95rem;
    vertical-align: middle;
  }
  
  .admin-table tr:hover td {
    background: rgba(255,255,255,0.01);
  }

  .status-badge {
    padding: 0.25rem 0.75rem;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    border: 1px solid transparent;
  }
  .status-active {
    background: rgba(30, 107, 85, 0.1);
    color: var(--emerald);
    border-color: rgba(30, 107, 85, 0.3);
  }
  .status-pending {
    background: rgba(216, 228, 240, 0.1);
    color: var(--bright-platinum);
    border-color: rgba(216, 228, 240, 0.3);
  }

  .action-links a {
    color: var(--platinum);
    margin-right: 0.75rem;
    transition: color 0.3s;
  }
  .action-links a:hover {
    color: var(--emerald);
  }

  .prop-img-thumb {
    width: 60px;
    height: 45px;
    object-fit: cover;
    margin-right: 15px;
    vertical-align: middle;
    border: 1px solid rgba(255,255,255,0.1);
  }
  
  .inquiry-item {
    padding: 1.5rem;
    background: rgba(10, 22, 40, 0.5);
    border: 1px solid var(--border);
    margin-bottom: 1rem;
    transition: all 0.3s ease;
  }
  
  .inquiry-item:hover {
    background: rgba(10, 22, 40, 0.8);
    border-color: rgba(184, 196, 212, 0.2);
  }
</style>

<div class="page-header">
  <div>
    <div class="section-label">Overview</div>
    <h1 class="page-title">Dashboard</h1>
  </div>
  <a href="add-property.php" class="btn-luxury">
    <i data-lucide="plus" style="width: 18px; height: 18px;"></i> Add Property
  </a>
</div>

<!-- Stats -->
<div class="stats-grid">
  <div class="glass-card stat-card">
    <i data-lucide="building" class="stat-icon"></i>
    <div class="stat-label">Total Properties</div>
    <div class="stat-value counter" data-target="<?= $propCount ?>">0</div>
  </div>
  
  <div class="glass-card stat-card">
    <i data-lucide="users" class="stat-icon"></i>
    <div class="stat-label">Total Users</div>
    <div class="stat-value counter" data-target="<?= $userCount ?>">0</div>
  </div>
  
  <div class="glass-card stat-card">
    <i data-lucide="message-square" class="stat-icon"></i>
    <div class="stat-label">Active Inquiries</div>
    <div class="stat-value counter" data-target="<?= $inqCount ?>">0</div>
  </div>
  
  <div class="glass-card stat-card">
    <i data-lucide="dollar-sign" class="stat-icon"></i>
    <div class="stat-label">Portfolio Value</div>
    <div class="stat-value">$<span class="counter" style="font-size:inherit;color:inherit;margin:0;font-family:inherit" data-target="<?= $totalVal > 0 ? ($totalVal / 1000000) : 0 ?>">0</span><span>M</span></div>
  </div>
</div>

<div class="dashboard-grid">
  <!-- Recent Properties -->
  <div class="glass-card">
    <div class="card-header">
      <h2 class="card-title">Recent Properties</h2>
      <a href="properties.php" class="view-all-link">View All</a>
    </div>
    <div class="table-responsive">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Property</th>
            <th>Location</th>
            <th>Price</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($recentProps)): ?>
            <tr><td colspan="5" style="text-align:center;color:var(--platinum);padding:3rem;">No properties found.</td></tr>
          <?php else: ?>
            <?php foreach($recentProps as $p): ?>
              <tr>
                <td>
                  <?php if($p['img']): ?>
                    <img src="<?= htmlspecialchars($p['img']) ?>" class="prop-img-thumb" alt="thumb">
                  <?php endif; ?>
                  <span style="font-weight: 500; font-family: var(--font-heading); color: var(--warm-white);"><?= htmlspecialchars($p['title']) ?></span>
                </td>
                <td style="color: var(--platinum);"><?= htmlspecialchars($p['location'] ?? '—') ?></td>
                <td style="font-family: var(--font-heading); font-size: 1.1rem; color: var(--bright-platinum);">$<?= number_format($p['price']) ?></td>
                <td>
                  <span class="status-badge status-<?= $p['status'] === 'active' ? 'active' : 'pending' ?>">
                    <?= ucfirst($p['status'] ?? 'active') ?>
                  </span>
                </td>
                <td class="action-links">
                  <a href="../pages/property-details.php?id=<?= $p['id'] ?>" target="_blank" title="View"><i data-lucide="eye" style="width:18px;height:18px;"></i></a>
                  <a href="edit-property.php?id=<?= $p['id'] ?>" title="Edit"><i data-lucide="edit" style="width:18px;height:18px;"></i></a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Recent Inquiries -->
  <div class="glass-card">
    <div class="card-header">
      <h2 class="card-title">Latest Inquiries</h2>
      <a href="inquiries.php" class="view-all-link">View All</a>
    </div>
    
    <div>
      <?php if(empty($recentInqs)): ?>
        <p style="color: var(--platinum); text-align: center; padding: 3rem 0;">No inquiries found.</p>
      <?php else: ?>
        <?php foreach($recentInqs as $inq): ?>
          <div class="inquiry-item">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; align-items: baseline;">
              <strong style="font-family: var(--font-heading); font-size: 1.1rem; color: var(--warm-white);"><?= htmlspecialchars($inq['name']) ?></strong>
              <span style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--platinum);"><?= date('M j', strtotime($inq['created_at'])) ?></span>
            </div>
            <p style="font-size: 0.9rem; color: var(--platinum); margin-bottom: 1rem; line-height: 1.6;">
              <?= htmlspecialchars(strlen($inq['message']) > 60 ? substr($inq['message'], 0, 60) . '...' : $inq['message']) ?>
            </p>
            <div style="font-size: 0.75rem; color: var(--emerald); text-transform: uppercase; letter-spacing: 0.05em;">
              <i data-lucide="home" style="width:12px;height:12px;vertical-align:middle;margin-right:4px;"></i>
              <?= htmlspecialchars($inq['property_title'] ?? 'General Inquiry') ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
  // Counter Animation
  const counters = document.querySelectorAll('.counter');
  counters.forEach(counter => {
    const target = +counter.getAttribute('data-target');
    const duration = 2000;
    const increment = target / (duration / 16);
    
    let current = 0;
    const updateCounter = () => {
      current += increment;
      if (current < target) {
        // If it has decimals, format appropriately, else ceil
        counter.innerText = target % 1 !== 0 ? current.toFixed(1) : Math.ceil(current);
        requestAnimationFrame(updateCounter);
      } else {
        counter.innerText = target % 1 !== 0 ? target.toFixed(1) : target;
      }
    };
    updateCounter();
  });
</script>

<?php require_once 'includes/footer.php'; ?>
