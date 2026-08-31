<?php
$pageTitle = 'Edit Property — Elite Estates Admin';
require_once 'includes/header.php';

$db  = DB::connect();
$msg = $err = '';

// Load property
$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: properties.php'); exit; }
$stmt = $db->prepare("SELECT * FROM properties WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) { header('Location: properties.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']       ?? '');
    $location    = trim($_POST['location']    ?? '');
    $price       = (float)($_POST['price']    ?? 0);
    $type        = trim($_POST['type']        ?? 'villa');
    $beds        = (int)($_POST['beds']       ?? 0);
    $baths       = (int)($_POST['baths']      ?? 0);
    $sqft        = (int)($_POST['sqft']       ?? 0);
    $garage      = (int)($_POST['garage']     ?? 0);
    $year_built  = (int)($_POST['year_built'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $status      = in_array($_POST['status'] ?? '', ['active','pending','sold','inactive']) ? $_POST['status'] : 'active';
    $featured    = isset($_POST['featured']) ? 1 : 0;
    $img         = trim($_POST['img_url']     ?? $p['img'] ?? '');

    if (!empty($_FILES['img_file']['name'])) {
        $allowed   = ['image/jpeg','image/png','image/webp','image/gif'];
        $maxSize   = 4 * 1024 * 1024;
        $uploadErr = $_FILES['img_file']['error'] ?? UPLOAD_ERR_NO_FILE;

        // Guard: if the upload failed at the PHP/server level (e.g. the file exceeded
        // upload_max_filesize or post_max_size), $_FILES['img_file']['name'] is still
        // populated but 'tmp_name' is empty. Calling mime_content_type('') on PHP 8
        // throws an uncaught ValueError and crashes the whole request (blank page).
        // We must check the upload error code BEFORE touching tmp_name.
        if ($uploadErr !== UPLOAD_ERR_OK) {
            $uploadErrorMessages = [
                UPLOAD_ERR_INI_SIZE   => 'Image exceeds the server upload size limit (upload_max_filesize). Please use a smaller image or provide an Image URL instead.',
                UPLOAD_ERR_FORM_SIZE  => 'Image exceeds the maximum size allowed by the form.',
                UPLOAD_ERR_PARTIAL    => 'The image was only partially uploaded. Please try again.',
                UPLOAD_ERR_NO_TMP_DIR => 'Server is missing a temporary folder for uploads.',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write the uploaded image to disk.',
                UPLOAD_ERR_EXTENSION  => 'A server extension blocked the image upload.',
            ];
            $err = $uploadErrorMessages[$uploadErr] ?? 'Image upload failed. Please try again or provide an Image URL instead.';
        } elseif (!is_uploaded_file($_FILES['img_file']['tmp_name'])) {
            $err = 'Image upload failed. Please try again.';
        } else {
            $fType     = function_exists('mime_content_type') ? mime_content_type($_FILES['img_file']['tmp_name']) : $_FILES['img_file']['type'];
            $fSize     = $_FILES['img_file']['size'];
            $uploadDir = dirname(__DIR__) . '/assets/uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            if (!in_array($fType, $allowed)) {
                $err = 'Only JPEG, PNG, WebP, or GIF images are allowed.';
            } elseif ($fSize > $maxSize) {
                $err = 'Image must be under 4 MB.';
            } else {
                $ext      = pathinfo($_FILES['img_file']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('prop_', true) . '.' . strtolower($ext);
                if (move_uploaded_file($_FILES['img_file']['tmp_name'], $uploadDir . $filename)) {
                    $img = '../assets/uploads/' . $filename;
                } else {
                    $err = 'Failed to upload image. Check folder permissions.';
                }
            }
        }
    }

    if (!$err) {
        if (!$title || !$price) {
            $err = 'Title and price are required.';
        } else {
            $db->prepare(
                "UPDATE properties SET
                 title=?,location=?,price=?,type=?,beds=?,baths=?,sqft=?,
                 garage=?,year_built=?,description=?,img=?,status=?,featured=?
                 WHERE id=?"
            )->execute([$title,$location,$price,$type,$beds,$baths,$sqft,
                        $garage,$year_built,$description,$img,$status,$featured,$id]);
            $msg = 'Property updated successfully!';
            $stmt->execute([$id]);
            $p = $stmt->fetch();
        }
    }
}
?>

<style>
  .glass-form-card {
    background: var(--panel);
    border: 1px solid var(--border);
    padding: 3.5rem;
    position: relative;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
  }
  
  .glass-form-card::after {
    content: '';
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    box-shadow: inset 0 0 0 1px rgba(255,255,255,0.02);
    pointer-events: none;
  }

  .form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
  }

  @media (max-width: 768px) {
    .form-grid {
      grid-template-columns: 1fr;
    }
  }

  .form-group {
    position: relative;
    margin-bottom: 2rem;
  }

  /* Elegant Floating Labels */
  .form-floating {
    position: relative;
  }

  .form-control {
    width: 100%;
    padding: 1.5rem 1rem 0.5rem;
    background: rgba(6, 14, 28, 0.4);
    border: 1px solid var(--border);
    border-radius: 0;
    color: var(--warm-white);
    font-family: var(--font-body);
    font-size: 1.1rem;
    outline: none;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
  }

  .form-control:focus {
    border-color: var(--emerald);
    background: rgba(6, 14, 28, 0.8);
    box-shadow: inset 4px 0 0 0 var(--emerald);
  }

  .form-floating label {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--platinum);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    pointer-events: none;
  }

  .form-control:focus ~ label,
  .form-control:not(:placeholder-shown) ~ label {
    top: 0.5rem;
    transform: translateY(0);
    font-size: 0.65rem;
    color: var(--emerald);
  }

  select.form-control {
    padding-top: 1.25rem;
    appearance: none;
  }
  
  textarea.form-control {
    min-height: 150px;
    resize: vertical;
    padding-top: 1.75rem;
  }

  /* Upload Zone */
  .upload-zone {
    border: 1px dashed var(--border);
    padding: 3rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    background: rgba(6, 14, 28, 0.2);
    position: relative;
    overflow: hidden;
  }
  
  .upload-zone::before {
    content: '';
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background: radial-gradient(circle at center, rgba(30, 107, 85, 0.1), transparent 70%);
    opacity: 0;
    transition: opacity 0.4s ease;
  }

  .upload-zone:hover, .upload-zone.dragover {
    border-color: var(--emerald);
  }
  
  .upload-zone:hover::before, .upload-zone.dragover::before {
    opacity: 1;
  }

  .upload-icon {
    color: var(--platinum);
    margin-bottom: 1.5rem;
    transition: color 0.4s;
    position: relative;
    z-index: 2;
  }

  .upload-zone:hover .upload-icon {
    color: var(--emerald);
  }
  
  .upload-text {
    position: relative;
    z-index: 2;
  }

  .img-preview {
    max-width: 100%;
    max-height: 250px;
    margin-top: 1.5rem;
    display: none;
    object-fit: cover;
    border: 1px solid var(--border);
  }
  
  .img-preview.show {
    display: block;
  }

  /* Checkbox Custom Toggle */
  .custom-checkbox {
    display: flex;
    align-items: center;
    gap: 1rem;
    cursor: pointer;
    user-select: none;
  }
  
  .custom-checkbox input {
    display: none;
  }
  
  .checkbox-box {
    width: 24px;
    height: 24px;
    border: 1px solid var(--border);
    background: rgba(6, 14, 28, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
  }
  
  .custom-checkbox input:checked + .checkbox-box {
    background: var(--emerald);
    border-color: var(--emerald);
  }
  
  .checkbox-box i {
    color: transparent;
    width: 14px;
    height: 14px;
    transition: color 0.3s ease;
  }
  
  .custom-checkbox input:checked + .checkbox-box i {
    color: #fff;
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

  .form-section-title {
    font-family: var(--font-logo);
    font-size: 1.4rem;
    color: var(--warm-white);
    margin: 3rem 0 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border);
    letter-spacing: 0.05em;
  }
</style>

<div class="page-header">
  <div>
    <div class="section-label">Portfolio</div>
    <h1 class="page-title">Edit Property</h1>
  </div>
  <a href="properties.php" class="btn-outline">
    <i data-lucide="arrow-left" style="width: 18px; height: 18px;"></i> Back to List
  </a>
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

<div class="glass-form-card">
  <form method="POST" enctype="multipart/form-data">
    <div class="form-section-title" style="margin-top: 0;">Basic Information</div>
    
    <div class="form-grid">
      <div class="form-group form-floating" style="grid-column: span 2;">
        <input type="text" name="title" id="title" class="form-control" required placeholder=" " value="<?= htmlspecialchars($p['title']) ?>">
        <label for="title">Property Title *</label>
      </div>

      <div class="form-group form-floating">
        <input type="number" name="price" id="price" class="form-control" required min="0" step="100" placeholder=" " value="<?= $p['price'] ?>">
        <label for="price">Price (USD) *</label>
      </div>

      <div class="form-group form-floating">
        <select name="type" id="type" class="form-control">
          <?php foreach(['Villa','Penthouse','Mansion','Estate','Apartment','Townhouse'] as $t): ?>
            <option value="<?= strtolower($t) ?>" <?= (strtolower($p['type'] ?? '') === strtolower($t)) ? 'selected' : '' ?>><?= $t ?></option>
          <?php endforeach; ?>
        </select>
        <label for="type" style="top: 0.5rem; transform: translateY(0); font-size: 0.65rem; color: var(--emerald);">Property Type</label>
      </div>

      <div class="form-group form-floating" style="grid-column: span 2;">
        <input type="text" name="location" id="location" class="form-control" placeholder=" " value="<?= htmlspecialchars($p['location'] ?? '') ?>">
        <label for="location">Location Address</label>
      </div>
    </div>

    <div class="form-section-title">Property Specifications</div>

    <div class="form-grid">
      <div class="form-group form-floating">
        <input type="number" name="beds" id="beds" class="form-control" min="0" placeholder=" " value="<?= $p['beds'] ?? '' ?>">
        <label for="beds">Bedrooms</label>
      </div>
      
      <div class="form-group form-floating">
        <input type="number" name="baths" id="baths" class="form-control" min="0" placeholder=" " value="<?= $p['baths'] ?? '' ?>">
        <label for="baths">Bathrooms</label>
      </div>

      <div class="form-group form-floating">
        <input type="number" name="sqft" id="sqft" class="form-control" min="0" placeholder=" " value="<?= $p['sqft'] ?? '' ?>">
        <label for="sqft">Square Footage</label>
      </div>

      <div class="form-group form-floating">
        <input type="number" name="garage" id="garage" class="form-control" min="0" placeholder=" " value="<?= $p['garage'] ?? '' ?>">
        <label for="garage">Garage Spaces</label>
      </div>

      <div class="form-group form-floating">
        <input type="number" name="year_built" id="year_built" class="form-control" min="1800" max="2030" placeholder=" " value="<?= $p['year_built'] ?: '' ?>">
        <label for="year_built">Year Built</label>
      </div>

      <div class="form-group form-floating">
        <select name="status" id="status" class="form-control">
          <?php foreach(['active'=>'Active Listing','pending'=>'Under Offer','sold'=>'Sold','inactive'=>'Off Market'] as $val=>$lbl): ?>
            <option value="<?= $val ?>" <?= ($p['status'] ?? 'active') === $val ? 'selected' : '' ?>><?= $lbl ?></option>
          <?php endforeach; ?>
        </select>
        <label for="status" style="top: 0.5rem; transform: translateY(0); font-size: 0.65rem; color: var(--emerald);">Listing Status</label>
      </div>
    </div>

    <div class="form-group" style="margin-top: 1rem;">
      <label class="custom-checkbox">
        <input type="checkbox" name="featured" value="1" <?= (!empty($p['featured'])) ? 'checked' : '' ?>>
        <div class="checkbox-box"><i data-lucide="check"></i></div>
        <span style="font-family: var(--font-body); color: var(--warm-white); letter-spacing: 0.05em; text-transform: uppercase; font-size: 0.9rem;">Mark as Featured Property</span>
      </label>
    </div>

    <div class="form-section-title">Media & Description</div>

    <?php if (!empty($p['img'])): ?>
      <div style="margin-bottom: 2rem; padding: 1.5rem; background: rgba(6, 14, 28, 0.3); border: 1px solid var(--border);">
        <div style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--platinum); margin-bottom: 1rem;">Current Primary Image</div>
        <img src="<?= htmlspecialchars($p['img']) ?>" alt="Current" style="max-height: 250px; object-fit: cover; border: 1px solid rgba(255,255,255,0.1);">
      </div>
    <?php endif; ?>

    <div class="form-group">
      <label style="display: block; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em; color: var(--platinum); margin-bottom: 1rem;">Replace Image (Upload)</label>
      
      <div class="upload-zone" id="dropZone" onclick="document.getElementById('img_file').click()">
        <i data-lucide="upload-cloud" class="upload-icon" style="width: 48px; height: 48px;"></i>
        <div class="upload-text">
          <div style="font-family: var(--font-heading); font-size: 1.4rem; color: var(--warm-white); margin-bottom: 0.5rem;">Drop new image here</div>
          <div style="font-size: 0.9rem; color: var(--platinum); text-transform: uppercase; letter-spacing: 0.1em;">or click to browse</div>
          <div style="font-size: 0.75rem; color: var(--platinum); opacity: 0.7; margin-top: 1rem;">JPEG, PNG, WebP (Max 4MB)</div>
        </div>
      </div>
      <input type="file" name="img_file" id="img_file" accept="image/*" style="display: none;">
      <img id="imgPreview" class="img-preview" src="" alt="Preview">
      
      <div style="text-align: center; margin: 2rem 0; color: var(--platinum); font-size: 0.8rem; letter-spacing: 0.2em;">— OR —</div>
      
      <div class="form-floating">
        <input type="url" name="img_url" id="imgUrl" class="form-control" placeholder=" " value="<?= htmlspecialchars($p['img'] ?? '') ?>">
        <label for="imgUrl">Provide Image URL</label>
      </div>
      <img id="imgUrlPreview" class="img-preview" src="" alt="URL Preview">
    </div>

    <div class="form-group form-floating" style="margin-top: 2rem;">
      <textarea name="description" id="description" class="form-control" placeholder=" "><?= htmlspecialchars($p['description'] ?? '') ?></textarea>
      <label for="description">Comprehensive Description</label>
    </div>

    <div style="display: flex; gap: 1.5rem; margin-top: 4rem;">
      <button type="submit" class="btn-luxury" style="padding: 1.2rem 3rem;">
        Update Portfolio Entry <i data-lucide="arrow-right" style="width: 18px; height: 18px;"></i>
      </button>
    </div>
  </form>
</div>

<script>
  const fileInput  = document.getElementById('img_file');
  const imgPreview = document.getElementById('imgPreview');
  const dropZone   = document.getElementById('dropZone');
  
  if (fileInput) {
    fileInput.addEventListener('change', () => {
      if (fileInput.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { 
          imgPreview.src = e.target.result; 
          imgPreview.classList.add('show'); 
        };
        reader.readAsDataURL(fileInput.files[0]);
      }
    });
    
    dropZone.addEventListener('dragover', e => { 
      e.preventDefault(); 
      dropZone.classList.add('dragover'); 
    });
    
    dropZone.addEventListener('dragleave', () => { 
      dropZone.classList.remove('dragover'); 
    });
    
    dropZone.addEventListener('drop', e => {
      e.preventDefault(); 
      dropZone.classList.remove('dragover');
      if (e.dataTransfer.files[0]) { 
        fileInput.files = e.dataTransfer.files; 
        fileInput.dispatchEvent(new Event('change')); 
      }
    });
  }

  const imgUrl        = document.getElementById('imgUrl');
  const imgUrlPreview = document.getElementById('imgUrlPreview');
  
  if (imgUrl) {
    imgUrl.addEventListener('input', () => {
      const val = imgUrl.value.trim();
      if (val) { 
        imgUrlPreview.src = val; 
        imgUrlPreview.classList.add('show'); 
      } else { 
        imgUrlPreview.classList.remove('show'); 
      }
    });
    
    if (imgUrl.value.trim()) { 
      imgUrlPreview.src = imgUrl.value.trim(); 
      imgUrlPreview.classList.add('show'); 
    }
  }
</script>

<?php require_once 'includes/footer.php'; ?>
