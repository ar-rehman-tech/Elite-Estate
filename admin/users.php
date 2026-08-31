<?php
$pageTitle = 'Users — Elite Estates Admin';
require_once 'includes/header.php';

$db  = DB::connect();
$msg = $err = '';

// Delete user (not self)
if (isset($_GET['delete'])) {
    $uid = (int)$_GET['delete'];
    if ($uid !== (int)(currentUser()['id'] ?? 0)) {
        $db->prepare("DELETE FROM users WHERE id = ?")->execute([$uid]);
        $msg = 'User account has been removed successfully.';
    } else {
        $err = 'You cannot delete your own administrator account.';
    }
}

// Toggle role
if (isset($_GET['toggle_role'])) {
    $uid = (int)$_GET['toggle_role'];
    $row = $db->prepare("SELECT role FROM users WHERE id = ?");
    $row->execute([$uid]);
    $r = $row->fetch();
    if ($r) {
        $newRole = $r['role'] === 'admin' ? 'user' : 'admin';
        $db->prepare("UPDATE users SET role = ? WHERE id = ?")->execute([$newRole, $uid]);
    }
    header('Location: users.php');
    exit;
}

$users = $db->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
?>

<style>
  .table-container {
    background: var(--panel);
    border: 1px solid var(--border);
    padding: 2.5rem;
    overflow-x: auto;
    position: relative;
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
    padding: 0.35rem 1rem;
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    border: 1px solid transparent;
  }
  
  .status-badge:hover {
    transform: translateY(-2px);
  }
  
  .status-admin {
    background: rgba(30, 107, 85, 0.1);
    color: var(--emerald);
    border-color: rgba(30, 107, 85, 0.3);
  }
  
  .status-user {
    background: rgba(6, 14, 28, 0.5);
    color: var(--platinum);
    border-color: var(--border);
  }

  .action-links a {
    color: var(--admin-danger);
    margin-right: 0.75rem;
    transition: color 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    text-decoration: none;
  }
  
  .action-links a:hover {
    color: #ff4d4d;
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

  .alert-success { background: rgba(30, 107, 85, 0.1); border-color: rgba(30, 107, 85, 0.3); color: var(--emerald); }
  .alert-error { background: rgba(230, 57, 70, 0.1); border-color: rgba(230, 57, 70, 0.3); color: #e63946; }

  .user-avatar {
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 500;
    font-family: var(--font-logo);
    margin-right: 1.25rem;
    font-size: 1.2rem;
    border: 1px solid rgba(255,255,255,0.1);
  }
</style>

<div class="page-header">
  <div>
    <div class="section-label">Management</div>
    <h1 class="page-title">Users</h1>
  </div>
  <div style="font-family: var(--font-heading); font-size: 1.4rem; color: var(--platinum);">
    <?= count($users) ?> Registered
  </div>
</div>

<?php if ($msg): ?>
  <div class="alert alert-success">
    <i data-lucide="check-circle" style="width: 20px; height: 20px;"></i>
    <?= htmlspecialchars($msg) ?>
  </div>
<?php endif; ?>
<?php if ($err): ?>
  <div class="alert alert-error">
    <i data-lucide="alert-circle" style="width: 20px; height: 20px;"></i>
    <?= htmlspecialchars($err) ?>
  </div>
<?php endif; ?>

<div class="table-container">
  <table class="admin-table">
    <thead>
      <tr>
        <th>User Details</th>
        <th>Contact Information</th>
        <th>Registered On</th>
        <th>Role Level</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($users)): ?>
        <tr><td colspan="5" style="text-align:center;color:var(--platinum);padding:4rem;">No users found.</td></tr>
      <?php else: ?>
        <?php foreach($users as $u): 
          $isCurrentUser = ((int)$u['id'] === (int)(currentUser()['id'] ?? 0));
          $isAdmin = ($u['role'] ?? 'user') === 'admin';
          // Use Elite Estate themes for avatars
          $colors = ['#1E6B55', '#0F2A4A', '#0A1628', '#27896C', '#111D2E'];
          $colorIdx = strlen($u['name']) % count($colors);
          $avatarBg = $isAdmin ? 'var(--emerald)' : $colors[$colorIdx];
        ?>
        <tr>
          <td>
            <div style="display: flex; align-items: center;">
              <div class="user-avatar" style="background: <?= $avatarBg ?>; color: var(--warm-white);">
                <?= strtoupper(substr($u['name'], 0, 1)) ?>
              </div>
              <div>
                <div style="font-weight: 400; font-family: var(--font-heading); font-size: 1.1rem; color: var(--warm-white); margin-bottom: 0.25rem;">
                  <?= htmlspecialchars($u['name']) ?>
                  <?php if ($isCurrentUser): ?>
                    <span style="font-size: 0.6rem; letter-spacing: 0.1em; background: rgba(255,255,255,0.1); padding: 0.2rem 0.5rem; margin-left: 0.5rem; vertical-align: middle;">YOU</span>
                  <?php endif; ?>
                </div>
                <div style="font-size: 0.75rem; color: var(--platinum); font-family: var(--font-body); letter-spacing: 0.05em; text-transform: uppercase;">
                  ID: #<?= str_pad($u['id'], 4, '0', STR_PAD_LEFT) ?>
                </div>
              </div>
            </div>
          </td>
          
          <td style="color: var(--platinum);">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.3rem;">
              <i data-lucide="mail" style="width: 14px; height: 14px; color: var(--emerald);"></i> <?= htmlspecialchars($u['email']) ?>
            </div>
            <?php if (!empty($u['phone'])): ?>
              <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem;">
                <i data-lucide="phone" style="width: 14px; height: 14px; color: var(--emerald);"></i> <?= htmlspecialchars($u['phone']) ?>
              </div>
            <?php endif; ?>
          </td>
          
          <td style="color: var(--platinum); font-size: 0.95rem;">
            <?= isset($u['created_at']) ? date('M j, Y', strtotime($u['created_at'])) : '—' ?>
          </td>
          
          <td>
            <a href="?toggle_role=<?= $u['id'] ?>" class="status-badge <?= $isAdmin ? 'status-admin' : 'status-user' ?>" title="Click to toggle role">
              <?php if ($isAdmin): ?>
                <i data-lucide="shield" style="width: 14px; height: 14px;"></i> Admin
              <?php else: ?>
                <i data-lucide="user" style="width: 14px; height: 14px;"></i> User
              <?php endif; ?>
            </a>
          </td>
          
          <td class="action-links">
            <?php if (!$isCurrentUser): ?>
              <a href="?delete=<?= $u['id'] ?>" onclick="return confirm('Are you sure you want to permanently delete this user?');">
                <i data-lucide="user-minus" style="width: 16px; height: 16px;"></i> Remove
              </a>
            <?php else: ?>
              <span style="color: var(--platinum); opacity: 0.5; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; cursor: not-allowed; display: inline-flex; align-items: center; gap: 0.4rem;">
                <i data-lucide="lock" style="width: 14px; height: 14px;"></i> Protected
              </span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require_once 'includes/footer.php'; ?>
