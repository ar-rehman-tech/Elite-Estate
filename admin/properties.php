<?php
$pageTitle = 'Manage Properties — Elite Estates Admin';
require_once 'includes/header.php';

$db = DB::connect();

$msg = htmlspecialchars($_GET['msg'] ?? '');
$err = '';
if (isset($_GET['err'])) {
    $err = $_GET['err'] === 'csrf' ? 'Invalid request token. Please try again.' :
          ($_GET['err'] === 'notfound' ? 'Property not found.' : 'An error occurred.');
}

$props = $db->query("SELECT * FROM properties ORDER BY id DESC")->fetchAll();
?>

<style>
  .table-container {
    background: var(--panel);
    border: 1px solid var(--border);
    padding: 2.5rem;
    overflow-x: auto;
    position: relative;
    transition: all 0.4s ease;
  }
  
  .table-container::after {
    content: '';
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    box-shadow: inset 0 0 0 1px rgba(255,255,255,0.02);
    pointer-events: none;
  }
  
  .admin-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
  }
  
  .admin-table th {
    text-align: left;
    padding: 1.25rem 1rem;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--platinum);
    border-bottom: 1px solid var(--border);
  }
  
  .admin-table td {
    padding: 1.25rem 1rem;
    border-bottom: 1px solid rgba(255,255,255,0.03);
    font-size: 0.95rem;
    vertical-align: middle;
    color: var(--bright-platinum);
  }
  
  .admin-table tr:hover td {
    background: rgba(255,255,255,0.02);
  }

  .status-badge {
    padding: 0.35rem 0.85rem;
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
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
  
  .featured-badge {
    background: var(--sapphire);
    color: var(--bright-platinum);
    border: 1px solid var(--border);
    font-size: 0.6rem;
    padding: 0.2rem 0.6rem;
    letter-spacing: 0.1em;
    margin-left: 0.75rem;
    vertical-align: middle;
  }

  .action-links a {
    color: var(--platinum);
    margin-right: 1rem;
    transition: color 0.3s;
  }
  .action-links a:hover {
    color: var(--emerald);
  }
  .action-links a.delete-btn:hover {
    color: var(--admin-danger);
  }

  .prop-img-thumb {
    width: 80px;
    height: 60px;
    object-fit: cover;
    margin-right: 15px;
    vertical-align: middle;
    border: 1px solid rgba(255,255,255,0.1);
  }

  .alert {
    padding: 1.25rem;
    margin-bottom: 2rem;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    border: 1px solid transparent;
  }

  .alert-success {
    background: rgba(30, 107, 85, 0.1);
    border-color: rgba(30, 107, 85, 0.3);
    color: var(--emerald);
  }

  .alert-error {
    background: rgba(230, 57, 70, 0.1);
    border-color: rgba(230, 57, 70, 0.3);
    color: #e63946;
  }
  
  .table-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
  }
  
  .search-input {
    background: rgba(6, 14, 28, 0.5);
    border: 1px solid var(--border);
    padding: 0.75rem 1.25rem;
    color: var(--warm-white);
    font-family: var(--font-body);
    width: 300px;
    transition: all 0.3s ease;
    outline: none;
  }

  .search-input:focus {
    border-color: var(--emerald);
    background: rgba(6, 14, 28, 0.8);
  }
</style>

<div class="page-header">
  <div>
    <div class="section-label">Portfolio</div>
    <h1 class="page-title">Manage Properties</h1>
  </div>
  <a href="add-property.php" class="btn-luxury">
    <i data-lucide="plus" style="width: 18px; height: 18px;"></i> Add Property
  </a>
</div>

<?php if ($msg): ?>
  <div class="alert alert-success">
    <i data-lucide="check-circle" style="width: 20px; height: 20px;"></i>
    <?= $msg ?>
  </div>
<?php endif; ?>

<?php if ($err): ?>
  <div class="alert alert-error">
    <i data-lucide="alert-circle" style="width: 20px; height: 20px;"></i>
    <?= htmlspecialchars($err) ?>
  </div>
<?php endif; ?>

<div class="table-container">
  <div class="table-controls">
    <div style="color: var(--platinum); font-size: 0.85rem; letter-spacing: 0.05em;">
      SHOWING <?= count($props) ?> PROPERTIES
    </div>
    <div>
      <input type="text" class="search-input" placeholder="Search properties..." id="propertySearch">
    </div>
  </div>

  <table class="admin-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Property Details</th>
        <th>Location</th>
        <th>Price</th>
        <th>Beds/Baths</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody id="propertyTableBody">
      <?php if (empty($props)): ?>
        <tr><td colspan="7" style="text-align:center;color:var(--platinum);padding:4rem;">No properties found. <a href="add-property.php" style="color: var(--emerald); text-decoration: none; border-bottom: 1px solid var(--emerald);">Add one</a></td></tr>
      <?php else: ?>
        <?php foreach ($props as $p): 
          $delToken = hash_hmac('sha256', 'delete-' . $p['id'], session_id());
        ?>
        <tr class="property-row">
          <td style="color: var(--platinum); font-family: var(--font-heading); font-size: 1.1rem; font-style: italic;">#<?= $p['id'] ?></td>
          <td>
            <div style="display: flex; align-items: center;">
              <?php if($p['img']): ?>
                <img src="<?= htmlspecialchars($p['img']) ?>" class="prop-img-thumb" alt="Thumb">
              <?php else: ?>
                <div class="prop-img-thumb" style="background: rgba(255,255,255,0.02); display: flex; align-items: center; justify-content: center;">
                  <i data-lucide="image" style="color: var(--platinum);"></i>
                </div>
              <?php endif; ?>
              <div>
                <div style="font-family: var(--font-heading); font-size: 1.1rem; color: var(--warm-white); margin-bottom: 0.3rem;" class="prop-title">
                  <?= htmlspecialchars($p['title']) ?>
                  <?php if($p['featured']): ?>
                    <span class="featured-badge">FEATURED</span>
                  <?php endif; ?>
                </div>
                <div style="font-size: 0.8rem; color: var(--platinum); text-transform: uppercase; letter-spacing: 0.05em;">
                  <?= ucfirst($p['type'] ?? 'Property') ?> • <?= number_format($p['sqft'] ?? 0) ?> SQFT
                </div>
              </div>
            </div>
          </td>
          <td class="prop-location" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($p['location'] ?? '') ?>">
            <?= htmlspecialchars($p['location'] ?? '—') ?>
          </td>
          <td style="font-family: var(--font-heading); font-size: 1.2rem; color: var(--warm-white);">$<?= number_format($p['price']) ?></td>
          <td style="color: var(--platinum);">
            <i data-lucide="bed" style="width: 16px; height: 16px; vertical-align: text-bottom; margin-right: 6px;"></i><?= $p['beds'] ?? 0 ?>
            <span style="margin: 0 0.75rem; opacity: 0.3;">|</span>
            <i data-lucide="bath" style="width: 16px; height: 16px; vertical-align: text-bottom; margin-right: 6px;"></i><?= $p['baths'] ?? 0 ?>
          </td>
          <td>
            <span class="status-badge status-<?= ($p['status'] ?? 'active') === 'active' ? 'active' : 'pending' ?>">
              <?= ucfirst($p['status'] ?? 'active') ?>
            </span>
          </td>
          <td class="action-links" style="white-space: nowrap;">
            <a href="../pages/property-details.php?id=<?= $p['id'] ?>" target="_blank" title="View Property"><i data-lucide="eye" style="width: 20px; height: 20px;"></i></a>
            <a href="edit-property.php?id=<?= $p['id'] ?>" title="Edit Property"><i data-lucide="edit" style="width: 20px; height: 20px;"></i></a>
            <a href="delete-property.php?id=<?= $p['id'] ?>&token=<?= $delToken ?>" class="delete-btn" title="Delete Property" onclick="return confirm('Delete &quot;<?= addslashes(htmlspecialchars($p['title'])) ?>&quot; permanently?')">
              <i data-lucide="trash-2" style="width: 20px; height: 20px;"></i>
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<script>
  const searchInput = document.getElementById('propertySearch');
  if (searchInput) {
    searchInput.addEventListener('input', function() {
      const term = this.value.toLowerCase();
      const rows = document.querySelectorAll('.property-row');
      
      rows.forEach(row => {
        const title = row.querySelector('.prop-title').textContent.toLowerCase();
        const loc = row.querySelector('.prop-location').textContent.toLowerCase();
        
        if (title.includes(term) || loc.includes(term)) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });
  }
</script>

<?php require_once 'includes/footer.php'; ?>
