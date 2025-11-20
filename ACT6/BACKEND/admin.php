<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAHUA: TERMS OF USE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="../PICS/DAHUAfavi.png">
    <link rel="stylesheet" href="../CSS/admin.css">
</head>
<body>

<!-- Second Header / Banner
<div class="container-fluid p-0 bg-dark">
  <div class="banner">
    <img src="../PICS/DAHUAcontact.png" alt="Bannerdash" class="banner-dash" style="width:100%; max-height:200px; object-fit:cover;">
  </div>
</div>-->
<?php
// Simple admin CRUD for `users` table.
// Access control: allowed from localhost OR when session is marked admin.
session_start();
// Basic allow: localhost or session is_admin
$remote = $_SERVER['REMOTE_ADDR'] ?? '';
$allow_local = in_array($remote, ['127.0.0.1', '::1']);
$is_admin_session = !empty($_SESSION['is_admin']);
if (!($allow_local || $is_admin_session)) {
    http_response_code(403);
    echo "<h2>Forbidden</h2><p>Admin access restricted. Run locally or set <code>\$_SESSION['is_admin']=1</code>.</p>";
    exit;
}

// Simple CSRF token
if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(16));

$mysqli = new mysqli('localhost','root','','cogact');
if ($mysqli->connect_error) {
    die('DB connection error: ' . $mysqli->connect_error);
}

function esc($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }

$action = $_REQUEST['action'] ?? '';

// Regions list (used for create/edit region select)
$regions = [
  'Africa', 'Asia', 'Europe', 'North America', 'South America', 'Oceania', 'Antarctica',
  'Middle East', 'Central America', 'Caribbean'
];

// Helper: validate CSRF for POST modifying actions
function check_csrf(){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(400);
            die(json_encode(['status'=>'error','message'=>'Invalid CSRF token']));
        }
    }
}

// Handle actions
if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  check_csrf();
  $username = trim($_POST['username'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $first_name = $_POST['first_name'] ?? '';
  $last_name = $_POST['last_name'] ?? '';
  $contact_number = $_POST['contact_number'] ?? null;
  $region = $_POST['region'] ?? null;
  $country = $_POST['country'] ?? null;
  $postal_id = $_POST['postal_id'] ?? null;
  $address = $_POST['address'] ?? null;
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $err = 'Invalid email'; }
  if (empty($err)) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $mysqli->prepare('INSERT INTO users (username,email,password,first_name,last_name,contact_number,region,country,postal_id,address) VALUES (?,?,?,?,?,?,?,?,?,?)');
    $stmt->bind_param('ssssssssss',$username,$email,$hash,$first_name,$last_name,$contact_number,$region,$country,$postal_id,$address);
    if (!$stmt->execute()) $err = $stmt->error;
    else header('Location: admin.php');
    exit;
  }
}

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  check_csrf();
  $id = (int)($_POST['id'] ?? 0);
  $username = trim($_POST['username'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $first_name = $_POST['first_name'] ?? '';
  $last_name = $_POST['last_name'] ?? '';
  $password = $_POST['password'] ?? '';
  $contact_number = $_POST['contact_number'] ?? null;
  $region = $_POST['region'] ?? null;
  $country = $_POST['country'] ?? null;
  $postal_id = $_POST['postal_id'] ?? null;
  $address = $_POST['address'] ?? null;
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $err = 'Invalid email'; }
  if (empty($err)) {
    if (!empty($password)) {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $mysqli->prepare('UPDATE users SET username=?, email=?, password=?, first_name=?, last_name=?, contact_number=?, region=?, country=?, postal_id=?, address=? WHERE id=?');
      $stmt->bind_param('ssssssssssi',$username,$email,$hash,$first_name,$last_name,$contact_number,$region,$country,$postal_id,$address,$id);
    } else {
      $stmt = $mysqli->prepare('UPDATE users SET username=?, email=?, first_name=?, last_name=?, contact_number=?, region=?, country=?, postal_id=?, address=? WHERE id=?');
      $stmt->bind_param('sssssssssi',$username,$email,$first_name,$last_name,$contact_number,$region,$country,$postal_id,$address,$id);
    }
    if (!$stmt->execute()) $err = $stmt->error;
    else header('Location: admin.php');
    exit;
  }
}

if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $id = (int)($_POST['id'] ?? 0);
    $stmt = $mysqli->prepare('DELETE FROM users WHERE id=?');
    $stmt->bind_param('i',$id);
    if (!$stmt->execute()) $err = $stmt->error;
    else header('Location: admin.php');
    exit;
}

// Page output (list and simple forms)
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin — Users CRUD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../CSS/admin.css">
</head>
<body class="p-3 admin-page">
<div class="container">
  <div class="admin-topbar">
    <div>
      <h1>Admin — Users</h1>
      <div class="lead">Manage application user accounts (create, edit, delete)</div>
    </div>
    <div>
      <button id="openCreateBtn" class="btn btn-light btn-sm">Create account</button>
      <a href="admin.php" class="btn btn-light btn-sm">Refresh</a>
      <a href="../index.html" class="btn btn-outline-light btn-sm">Open site</a>
    </div>
  </div>
  <div class="admin-panel" id="adminPanel">
  <h2 class="mb-3 visually-hidden">Admin — Users</h2>
  <?php if (!empty($err)): ?><div class="alert alert-danger"><?php echo esc($err); ?></div><?php endif; ?>

  <h4>Existing users</h4>
  <table class="table table-striped">
    <thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Name</th><th>Contact</th><th>Region</th><th>Country</th><th>Postal ID</th><th>Actions</th></tr></thead>
    <tbody>
    <?php
      $res = $mysqli->query('SELECT id,username,email,first_name,last_name,contact_number,region,country,postal_id FROM users ORDER BY id DESC LIMIT 200');
      while ($row = $res->fetch_assoc()):
    ?>
      <tr>
        <td><?php echo (int)$row['id']; ?></td>
        <td><?php echo esc($row['username']); ?></td>
        <td><?php echo esc($row['email']); ?></td>
        <td><?php echo esc(trim($row['first_name'].' '.$row['last_name'])); ?></td>
        <td><?php echo esc($row['contact_number']); ?></td>
        <td><?php echo esc($row['region']); ?></td>
        <td><?php echo esc($row['country']); ?></td>
        <td><?php echo esc($row['postal_id']); ?></td>
        <td>
          <a class="btn btn-sm btn-outline-primary" href="admin.php?action=edit&id=<?php echo (int)$row['id']; ?>">Edit</a>
          <form method="post" action="admin.php?action=delete" style="display:inline-block;" onsubmit="return confirm('Delete this user?');">
            <input type="hidden" name="csrf_token" value="<?php echo esc($_SESSION['csrf_token']); ?>">
            <input type="hidden" name="id" value="<?php echo (int)$row['id']; ?>">
            <button class="btn btn-sm btn-outline-danger">Delete</button>
          </form>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>

    <?php if (($action === 'edit' || isset($_GET['id'])) && !empty($_GET['id'])):
      $uid = (int)$_GET['id'];
      $stmt = $mysqli->prepare('SELECT id,username,email,first_name,last_name,contact_number,region,country,postal_id,address FROM users WHERE id=?');
      $stmt->bind_param('i',$uid); $stmt->execute(); $u = $stmt->get_result()->fetch_assoc();
      if ($u): ?>
      <hr>
      <h4>Edit user #<?php echo (int)$u['id']; ?></h4>
      <form method="post" action="admin.php?action=update" class="row g-2">
        <input type="hidden" name="csrf_token" value="<?php echo esc($_SESSION['csrf_token']); ?>">
        <input type="hidden" name="id" value="<?php echo (int)$u['id']; ?>">
        <div class="col-md-2"><input name="username" value="<?php echo esc($u['username']); ?>" class="form-control"></div>
        <div class="col-md-2"><input name="email" value="<?php echo esc($u['email']); ?>" class="form-control" required></div>
        <div class="col-md-2"><input name="password" placeholder="Leave blank to keep" class="form-control"></div>
        <div class="col-md-2"><input name="first_name" value="<?php echo esc($u['first_name']); ?>" class="form-control"></div>
        <div class="col-md-2"><input name="last_name" value="<?php echo esc($u['last_name']); ?>" class="form-control"></div>
        <div class="col-md-2"><input name="contact_number" value="<?php echo esc($u['contact_number']); ?>" class="form-control"></div>
        <div class="col-md-2 region-picker-anchor">
          <label class="form-label visually-hidden">Region</label>
          <div class="region-display">
            <input id="regionDisplayEdit" class="form-control" placeholder="Select region" readonly aria-haspopup="listbox" value="<?php echo esc($u['region']); ?>">
            <button type="button" id="openRegionEdit" class="region-btn" title="Choose region">▾</button>
          </div>
          <input type="hidden" name="region" id="regionHiddenEdit" value="<?php echo esc($u['region']); ?>">
          <div id="regionDropdownEdit" class="region-dropdown" role="listbox" aria-label="Regions">
            <div class="region-list">
              <?php foreach($regions as $r): ?>
                <div class="region-item <?php echo ($u['region'] === $r)?'selected':''; ?>" data-val="<?php echo esc($r); ?>"><?php echo esc($r); ?></div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <div class="col-md-2"><input name="country" value="<?php echo esc($u['country']); ?>" class="form-control"></div>
        <div class="col-md-2"><input name="postal_id" value="<?php echo esc($u['postal_id']); ?>" class="form-control"></div>
        <div class="col-md-6"><input name="address" value="<?php echo esc($u['address']); ?>" class="form-control"></div>
        <div class="col-md-1"><button class="btn btn-success">Save</button></div>
      </form>
    <?php else: echo '<div class="alert alert-warning">User not found</div>'; endif; endif; ?>

</div>
</div>

<!-- Floating Create Modal -->
<div id="createOverlay" class="modal-overlay" aria-hidden="true">
  <div class="modal-panel" role="dialog" aria-modal="true" aria-labelledby="createTitle">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 id="createTitle" class="m-0">Create user account</h5>
      <button id="closeCreateBtn" class="btn btn-sm btn-light">Close</button>
    </div>
    <form method="post" action="admin.php?action=create">
      <input type="hidden" name="csrf_token" value="<?php echo esc($_SESSION['csrf_token']); ?>">
      <div class="row g-2">
        <div class="col-md-6">
          <label class="form-label">Username</label>
          <input name="username" class="form-control" placeholder="Enter username">
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input name="email" class="form-control" placeholder="user@example.com" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Password</label>
          <input name="password" type="password" class="form-control" placeholder="Choose a password" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">First name</label>
          <input name="first_name" class="form-control" placeholder="First name">
        </div>

        <div class="col-md-6">
          <label class="form-label">Last name</label>
          <input name="last_name" class="form-control" placeholder="Last name">
        </div>
        <div class="col-md-6">
          <label class="form-label">Contact number</label>
          <input name="contact_number" class="form-control" placeholder="0917xxxxxxx">
        </div>

        <div class="col-12">
          <div class="row row-short">
            <div class="col-md-4 col input-short">
              <label class="form-label">Country</label>
              <input name="country" class="form-control" placeholder="Country">
            </div>
            <div class="col-md-4 col input-short region-picker-anchor">
              <label class="form-label">Region</label>
              <div class="region-display">
                <input id="regionDisplayCreate" class="form-control" placeholder="Select region" readonly aria-haspopup="listbox">
                <button type="button" id="openRegionCreate" class="region-btn" title="Choose region">▾</button>
              </div>
              <input type="hidden" name="region" id="regionHiddenCreate" value="">

              <div id="regionDropdownCreate" class="region-dropdown" role="listbox" aria-label="Regions">
                <div class="region-list">
                  <?php foreach($regions as $r): ?>
                    <div class="region-item" data-val="<?php echo esc($r); ?>"><?php echo esc($r); ?></div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
            <div class="col-md-4 col input-short">
              <label class="form-label">Postal ID</label>
              <input name="postal_id" class="form-control" placeholder="Postal ID">
            </div>
          </div>
        </div>

        <div class="col-12">
          <label class="form-label">Address</label>
          <input name="address" class="form-control" placeholder="Street, City, etc.">
        </div>

        <div class="col-12 text-end mt-2">
          <button type="button" id="cancelCreateBtn" class="btn btn-secondary me-2">Cancel</button>
          <button class="btn btn-primary">Create account</button>
        </div>
      </div>
    </form>
  </div>
</div>
</body>
</html>

<?php $mysqli->close(); ?>

<script>
// Modal open/close and blur handling
document.addEventListener('DOMContentLoaded', function(){
  var openBtn = document.getElementById('openCreateBtn');
  var overlay = document.getElementById('createOverlay');
  var closeBtn = document.getElementById('closeCreateBtn');
  var cancelBtn = document.getElementById('cancelCreateBtn');
  var adminPanel = document.getElementById('adminPanel');
  function openModal(){ overlay.classList.add('show'); adminPanel.classList.add('blurred'); document.body.style.overflow = 'hidden'; }
  function closeModal(){ overlay.classList.remove('show'); adminPanel.classList.remove('blurred'); document.body.style.overflow = ''; }
  if(openBtn) openBtn.addEventListener('click', openModal);
  if(closeBtn) closeBtn.addEventListener('click', closeModal);
  if(cancelBtn) cancelBtn.addEventListener('click', closeModal);
  if(overlay) overlay.addEventListener('click', function(e){ if(e.target===overlay) closeModal(); });
  document.addEventListener('keydown', function(e){ if(e.key==='Escape') closeModal(); });
  
  // Region picker (Create) — use the display input as the search field when opened
  var openRegionCreate = document.getElementById('openRegionCreate');
  var regionDropdownCreate = document.getElementById('regionDropdownCreate');
  var regionHiddenCreate = document.getElementById('regionHiddenCreate');
  var regionDisplayCreate = document.getElementById('regionDisplayCreate');
  if (openRegionCreate) {
    openRegionCreate.addEventListener('click', function(e){
      e.stopPropagation();
      var opening = !regionDropdownCreate.classList.contains('show');
      regionDropdownCreate.classList.toggle('show');
      regionDisplayCreate.readOnly = !opening ? true : false;
      if (opening) { regionDisplayCreate.focus(); }
    });
    regionDisplayCreate.addEventListener('input', function(){
      var q = this.value.toLowerCase();
      regionDropdownCreate.querySelectorAll('.region-item').forEach(function(it){ it.style.display = it.textContent.toLowerCase().includes(q) ? 'block' : 'none'; });
    });
    regionDropdownCreate.querySelectorAll('.region-item').forEach(function(it){ it.addEventListener('click', function(){ var v=this.getAttribute('data-val'); regionHiddenCreate.value=v; regionDisplayCreate.value=v; regionDisplayCreate.readOnly = true; regionDropdownCreate.classList.remove('show'); }); });
  }

  // Region picker (Edit) — display input used for searching
  var openRegionEdit = document.getElementById('openRegionEdit');
  var regionDropdownEdit = document.getElementById('regionDropdownEdit');
  var regionHiddenEdit = document.getElementById('regionHiddenEdit');
  var regionDisplayEdit = document.getElementById('regionDisplayEdit');
  if (openRegionEdit) {
    openRegionEdit.addEventListener('click', function(e){
      e.stopPropagation();
      var opening = !regionDropdownEdit.classList.contains('show');
      regionDropdownEdit.classList.toggle('show');
      regionDisplayEdit.readOnly = !opening ? true : false;
      if (opening) { regionDisplayEdit.focus(); }
    });
    regionDisplayEdit.addEventListener('input', function(){
      var q = this.value.toLowerCase();
      regionDropdownEdit.querySelectorAll('.region-item').forEach(function(it){ it.style.display = it.textContent.toLowerCase().includes(q) ? 'block' : 'none'; });
    });
    regionDropdownEdit.querySelectorAll('.region-item').forEach(function(it){ it.addEventListener('click', function(){ var v=this.getAttribute('data-val'); regionHiddenEdit.value=v; regionDisplayEdit.value=v; regionDisplayEdit.readOnly = true; regionDropdownEdit.classList.remove('show'); }); });
  }

  // Close pickers when clicking outside — also restore readonly on display inputs
  document.addEventListener('click', function(e){
    if(regionDropdownCreate && !regionDropdownCreate.contains(e.target) && e.target !== openRegionCreate) { regionDropdownCreate.classList.remove('show'); if (regionDisplayCreate) regionDisplayCreate.readOnly = true; }
    if(regionDropdownEdit && !regionDropdownEdit.contains(e.target) && e.target !== openRegionEdit) { regionDropdownEdit.classList.remove('show'); if (regionDisplayEdit) regionDisplayEdit.readOnly = true; }
  });
});
</script>
