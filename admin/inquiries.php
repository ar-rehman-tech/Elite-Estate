<?php
$pageTitle = 'Inquiries — Elite Estates Admin';
require_once 'includes/header.php';

$db = DB::connect();
$msg = '';

if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM inquiries WHERE id = ?")->execute([(int)$_GET['delete']]);
    $msg = 'Inquiry deleted successfully.';
}

if (isset($_GET['read'])) {
    $db->prepare("UPDATE inquiries SET status = 'read' WHERE id = ?")->execute([(int)$_GET['read']]);
    header('Location: inquiries.php');
    exit;
}

$inquiries = $db->query("SELECT i.*, p.title as property_title FROM inquiries i LEFT JOIN properties p ON i.property_id = p.id ORDER BY i.id DESC")->fetchAll();
?>

<style>
  .glass-card {
    background: var(--panel);
    border: 1px solid var(--border);
    padding: 2.5rem;
    margin-bottom: 1.5rem;
    position: relative;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
  }
  
  .glass-card::after {
    content: '';
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    box-shadow: inset 0 0 0 1px rgba(255,255,255,0.02);
    pointer-events: none;
  }
  
  .glass-card:hover {
    border-color: rgba(184, 196, 212, 0.25);
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.4);
  }

  .glass-card.unread {
    border: 1px solid rgba(251, 191, 36, 0.3);
    background: linear-gradient(145deg, rgba(30, 41, 59, 0.8), rgba(15, 23, 42, 0.8));
    /* Need to change this to match elite estate */
    border: 1px solid rgba(30, 107, 85, 0.3);
    background: linear-gradient(145deg, rgba(10, 22, 40, 0.8), var(--panel));
  }

  .badge-new {
    position: absolute;
    top: -10px;
    right: 2.5rem;
    background: var(--emerald);
    color: var(--warm-white);
    padding: 0.3rem 1rem;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    box-shadow: 0 4px 15px rgba(30, 107, 85, 0.3);
    border: 1px solid rgba(255,255,255,0.1);
  }

  .inquiry-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
  }

  @media (max-width: 768px) {
    .inquiry-grid {
      grid-template-columns: 1fr;
    }
  }

  .meta-label {
    font-size: 0.7rem;
    color: var(--platinum);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 0.5rem;
    font-family: var(--font-body);
  }

  .meta-value {
    color: var(--warm-white);
    font-family: var(--font-heading);
    font-size: 1.4rem;
    margin-bottom: 0.25rem;
  }

  .meta-sub {
    font-size: 0.85rem;
    color: var(--platinum);
    margin-top: 0.2rem;
    font-family: var(--font-body);
  }
  
  .meta-sub a {
    color: var(--emerald);
    text-decoration: none;
    transition: color 0.3s;
  }

  .meta-sub a:hover {
    color: var(--emerald-hover);
  }

  .message-box {
    background: rgba(6, 14, 28, 0.4);
    border: 1px solid var(--border);
    padding: 2rem;
    font-size: 1rem;
    line-height: 1.8;
    color: var(--bright-platinum);
    margin-bottom: 2rem;
    font-family: var(--font-body);
  }

  .action-bar {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    border-top: 1px solid var(--border);
    padding-top: 2rem;
  }

  .action-link {
    color: var(--platinum);
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: color 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.1em;
  }

  .action-link:hover {
    color: var(--emerald);
  }

  .action-link.delete:hover {
    color: var(--admin-danger);
  }

  .alert {
    padding: 1.25rem;
    margin-bottom: 2rem;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: rgba(30, 107, 85, 0.1);
    border: 1px solid rgba(30, 107, 85, 0.3);
    color: var(--emerald);
  }
</style>

<div class="page-header">
  <div>
    <div class="section-label">Client Communications</div>
    <h1 class="page-title">Inquiries</h1>
  </div>
  <div style="font-family: var(--font-heading); font-size: 1.4rem; color: var(--platinum);">
    <?= count($inquiries) ?> Total
  </div>
</div>

<?php if ($msg): ?>
  <div class="alert">
    <i data-lucide="check-circle" style="width: 20px; height: 20px;"></i>
    <?= htmlspecialchars($msg) ?>
  </div>
<?php endif; ?>

<?php if (empty($inquiries)): ?>
  <div style="text-align:center; padding: 5rem; background: var(--panel); border: 1px solid var(--border); color: var(--platinum);">
    <i data-lucide="inbox" style="width: 48px; height: 48px; margin-bottom: 1.5rem; opacity: 0.5;"></i>
    <h3 style="font-family: var(--font-heading); font-size: 1.8rem; font-weight: 400;">No inquiries received yet.</h3>
  </div>
<?php else: ?>
  <div style="display: flex; flex-direction: column;">
    <?php foreach($inquiries as $inq): 
      $isNew = ($inq['status'] ?? 'new') === 'new';
    ?>
      <div class="glass-card <?= $isNew ? 'unread' : '' ?>">
        <?php if ($isNew): ?>
          <div class="badge-new">UNREAD</div>
        <?php endif; ?>
        
        <div class="inquiry-grid">
          <div>
            <div class="meta-label">Client Details</div>
            <div class="meta-value"><?= htmlspecialchars($inq['name']) ?></div>
            <div class="meta-sub">
              <a href="mailto:<?= htmlspecialchars($inq['email']) ?>"><?= htmlspecialchars($inq['email']) ?></a>
            </div>
            <?php if (!empty($inq['phone'])): ?>
              <div class="meta-sub"><?= htmlspecialchars($inq['phone']) ?></div>
            <?php endif; ?>
          </div>
          
          <div>
            <div class="meta-label">Interested Property</div>
            <div class="meta-value" style="display: flex; align-items: center; gap: 0.75rem; font-size: 1.2rem;">
              <i data-lucide="home" style="width: 20px; height: 20px; color: var(--emerald);"></i>
              <?= htmlspecialchars($inq['property_title'] ?? 'General Inquiry') ?>
            </div>
          </div>
          
          <div>
            <div class="meta-label">Received On</div>
            <div class="meta-value" style="font-size: 1.2rem;">
              <?= isset($inq['created_at']) ? date('M j, Y', strtotime($inq['created_at'])) : '—' ?>
            </div>
            <div class="meta-sub" style="font-size: 0.8rem; letter-spacing: 0.05em; text-transform: uppercase;">
              <?= isset($inq['created_at']) ? date('g:i A', strtotime($inq['created_at'])) : '—' ?>
            </div>
          </div>
        </div>
        
        <div class="message-box">
          <div class="meta-label" style="color: var(--emerald); margin-bottom: 1rem;">Message Content</div>
          <?= nl2br(htmlspecialchars($inq['message'])) ?>
        </div>
        
        <div class="action-bar">
          <a href="mailto:<?= htmlspecialchars($inq['email']) ?>" class="btn-luxury" style="padding: 0.8rem 2rem;">
            <i data-lucide="mail" style="width: 18px; height: 18px;"></i> Reply via Email
          </a>
          
          <?php if ($isNew): ?>
            <a href="?read=<?= $inq['id'] ?>" class="action-link" style="margin-left: 1.5rem;">
              <i data-lucide="check" style="width: 18px; height: 18px;"></i> Mark as Read
            </a>
          <?php endif; ?>
          
          <a href="?delete=<?= $inq['id'] ?>" class="action-link delete" style="margin-left: auto;" onclick="return confirm('Permanently delete this inquiry?');">
            <i data-lucide="trash-2" style="width: 18px; height: 18px;"></i> Delete
          </a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
