<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAHUA: ADMIN DASHBOARD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="../PICS/DAHUAfavi.png">
    <link rel="stylesheet" href="../style.css?v=20251118-1">
    <link rel="stylesheet" href="../CSS/admin.css">
    <style>
      /* Prevent Postal ID and Actions columns from wrapping and give them space */
      .postal-id-col, th.postal-id-col { min-width: 100px; white-space: nowrap; }
      .admin-actions-col, th.admin-actions-col { min-width: 220px; white-space: nowrap; }
      @media (max-width: 768px) {
        .postal-id-col, th.postal-id-col { min-width: 120px; }
        .admin-actions-col, th.admin-actions-col { min-width: 160px; }
      }
    </style>
    <script>
    // BFCache / back button protection: if page is restored from cache, redirect to login
    window.addEventListener('pageshow', function(event) {
      try {
        var navEntries = (performance.getEntriesByType && performance.getEntriesByType('navigation')) || [];
        var navType = (navEntries[0] && navEntries[0].type) || '';
        if (event.persisted || navType === 'back_forward') {
          window.location.replace('../logsign.php');
        }
      } catch (e) {
        // fallback: always redirect on pageshow if persisted
        if (event.persisted) window.location.replace('../logsign.php');
      }
    });
    </script>
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
// Require an explicit admin session. Do NOT allow a localhost bypass —
// accessing admin.php must require prior admin authentication.
$is_admin_session = !empty($_SESSION['is_admin']);
if (!$is_admin_session) {
  // Redirect unauthenticated visitors to the login page.
  header('Location: ../logsign.php');
  exit;
}

// Simple CSRF token
if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(16));

// Prevent caching so browser Back won't show protected pages after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0, private");
header("Pragma: no-cache");
header("Expires: 0");

$mysqli = new mysqli('localhost','root','','cogact');
if ($mysqli->connect_error) {
    die('DB connection error: ' . $mysqli->connect_error);
}

function esc($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }

$action = $_REQUEST['action'] ?? '';
// optional filter for table (active | deactivated)
$filter = $_GET['filter'] ?? null;

// Regions list (used for create/edit region select)
$regions = [
  'Africa', 'Asia', 'Europe', 'North America', 'South America', 'Oceania', 'Antarctica',
  'Middle East', 'Central America', 'Caribbean'
];

// detect if `role` column exists in users table
$roleColumnExists = false;
$colRes = $mysqli->query("SHOW COLUMNS FROM users LIKE 'role'");
if ($colRes && $colRes->num_rows > 0) $roleColumnExists = true;

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
  // Normalize contact: digits only
  if (!empty($contact_number)) {
    $contact_number = preg_replace('/\D+/', '', $contact_number);
    if ($contact_number === '') $contact_number = null;
    elseif (!preg_match('/^\d{11}$/', $contact_number)) {
      $err = 'Contact number must be exactly 11 digits';
    }
  }
  $region = $_POST['region'] ?? null;
  $country = $_POST['country'] ?? null;
  $postal_id = $_POST['postal_id'] ?? null;
  $address = $_POST['address'] ?? null;
  $role = $_POST['role'] ?? 'user';
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $err = 'Invalid email'; }
  if (empty($err)) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    if ($roleColumnExists) {
      $stmt = $mysqli->prepare('INSERT INTO users (username,email,password,first_name,last_name,contact_number,region,country,postal_id,address,role) VALUES (?,?,?,?,?,?,?,?,?,?,?)');
      $stmt->bind_param('sssssssssss',$username,$email,$hash,$first_name,$last_name,$contact_number,$region,$country,$postal_id,$address,$role);
    } else {
      $stmt = $mysqli->prepare('INSERT INTO users (username,email,password,first_name,last_name,contact_number,region,country,postal_id,address) VALUES (?,?,?,?,?,?,?,?,?,?)');
      $stmt->bind_param('ssssssssss',$username,$email,$hash,$first_name,$last_name,$contact_number,$region,$country,$postal_id,$address);
    }
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
  // Normalize contact: digits only
  if (!empty($contact_number)) {
    $contact_number = preg_replace('/\D+/', '', $contact_number);
    if ($contact_number === '') $contact_number = null;
    elseif (!preg_match('/^\d{11}$/', $contact_number)) {
      $err = 'Contact number must be exactly 11 digits';
    }
  }
  $region = $_POST['region'] ?? null;
  $country = $_POST['country'] ?? null;
  $postal_id = $_POST['postal_id'] ?? null;
  $address = $_POST['address'] ?? null;
  $role = $_POST['role'] ?? null;
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $err = 'Invalid email'; }
  if (empty($err)) {
    $passwordProvided = !empty($password);
    if ($passwordProvided) {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      if ($roleColumnExists && $role !== null) {
        $stmt = $mysqli->prepare('UPDATE users SET username=?, email=?, password=?, first_name=?, last_name=?, contact_number=?, region=?, country=?, postal_id=?, address=?, role=? WHERE id=?');
        $stmt->bind_param('sssssssssssi',$username,$email,$hash,$first_name,$last_name,$contact_number,$region,$country,$postal_id,$address,$role,$id);
      } else {
        $stmt = $mysqli->prepare('UPDATE users SET username=?, email=?, password=?, first_name=?, last_name=?, contact_number=?, region=?, country=?, postal_id=?, address=? WHERE id=?');
        $stmt->bind_param('ssssssssssi',$username,$email,$hash,$first_name,$last_name,$contact_number,$region,$country,$postal_id,$address,$id);
      }
    } else {
      if ($roleColumnExists && $role !== null) {
        $stmt = $mysqli->prepare('UPDATE users SET username=?, email=?, first_name=?, last_name=?, contact_number=?, region=?, country=?, postal_id=?, address=?, role=? WHERE id=?');
        $stmt->bind_param('ssssssssssi',$username,$email,$first_name,$last_name,$contact_number,$region,$country,$postal_id,$address,$role,$id);
      } else {
        $stmt = $mysqli->prepare('UPDATE users SET username=?, email=?, first_name=?, last_name=?, contact_number=?, region=?, country=?, postal_id=?, address=? WHERE id=?');
        $stmt->bind_param('sssssssssi',$username,$email,$first_name,$last_name,$contact_number,$region,$country,$postal_id,$address,$id);
      }
    }
    if (!$stmt->execute()) $err = $stmt->error;
    else {
      // If admin changed the password, set a flash message to show a floating notice
      if (!empty($passwordProvided)) {
        $_SESSION['admin_flash'] = [
          'password_changed' => true,
          'username' => $username,
          'email' => $email
        ];
      }
      header('Location: admin.php');
    }
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
      <a href="../index.php" class="btn btn-outline-light btn-sm">Open site</a>
      <a href="admin_logout.php" id="adminLogoutBtn" class="btn btn-sm btn-outline-danger ms-2">Logout</a>
    </div>
  </div>

    <!-- Decorative panels placed underneath the table (side-by-side) -->
    <div class="panels-row" aria-hidden="true">
      <div class="panels-inner" aria-hidden="true">
        <button type="button" class="panel-active panel-btn" data-panel="active" aria-label="Refresh active users">
          <span class="panel-label">Active Users</span>
        </button>
        <button type="button" class="panel-deactivate panel-btn" data-panel="deactivated" aria-label="Refresh deactivated users">
          <span class="panel-label">Deactivated Users</span>
        </button>
      </div>
    </div>
  <div class="admin-panel" id="adminPanel">
  <h2 class="mb-3 visually-hidden">Admin — Users</h2>
  <?php if (!empty($err)): ?><div class="alert alert-danger"><?php echo esc($err); ?></div><?php endif; ?>

  <div class="d-flex align-items-center justify-content-between mb-3">
    <div class="me-auto">
      <h4 class="m-0">Existing users</h4>
    </div>
    <div class="input-group" style="max-width:420px;">
      <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
      <input id="adminSearchInput" type="search" class="form-control" placeholder="Search ID, username, email, name, contact..." aria-label="Search users">
      <button id="adminSearchClear" class="btn btn-outline-secondary" type="button" title="Clear">✕</button>
    </div>
  </div>
  <div class="admin-table-wrap">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Name</th>
        <th>Contact</th>
        <th>Region</th>
        <th>Country</th>
        <th class="postal-id-col">Postal ID</th>
        <th>Status</th>
        <?php if ($roleColumnExists): ?><th>Role</th><?php endif; ?>
        <th class="admin-actions-col">Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php
      $cols = 'id,username,email,first_name,last_name,contact_number,region,country,postal_id,status';
      if ($roleColumnExists) $cols .= ',role';
      // build optional WHERE clause when a filter is provided
      $where = '';
      if ($filter === 'active') {
        $where = "WHERE status = 'active'";
      } elseif ($filter === 'deactivated') {
        $where = "WHERE status = 'deactivated'";
      }
      $res = $mysqli->query('SELECT ' . $cols . ' FROM users ' . $where . ' ORDER BY id DESC LIMIT 200');
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
        <td class="postal-id-col"><?php echo esc($row['postal_id']); ?></td>
        <td class="user-status"><?php echo esc($row['status'] ?? 'active'); ?></td>
        <?php if ($roleColumnExists): ?><td><?php echo esc($row['role'] ?? ''); ?></td><?php endif; ?>
        <td class="admin-actions-col">
          <a class="btn btn-sm btn-outline-primary" href="admin.php?action=edit&id=<?php echo (int)$row['id']; ?>">Edit</a>
          <?php
            $isDeactivated = (($row['status'] ?? '') === 'deactivated');
            $btnLabel = $isDeactivated ? 'Activate' : 'Deactivate';
            $btnClass = $isDeactivated ? 'btn-outline-success' : 'btn-outline-warning';
          ?>
          <button class="btn btn-sm <?php echo $btnClass; ?> toggle-status" data-user-id="<?php echo (int)$row['id']; ?>" data-username="<?php echo esc($row['username']); ?>" data-action="<?php echo $isDeactivated ? 'activate' : 'deactivate'; ?>"><?php echo $btnLabel; ?></button>
        </td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
  </div>

    <?php if (($action === 'edit' || isset($_GET['id'])) && !empty($_GET['id'])):
      $uid = (int)$_GET['id'];
      // include role when available
      $selCols = 'id,username,email,first_name,last_name,contact_number,region,country,postal_id,address';
      if ($roleColumnExists) $selCols .= ',role';
      $stmt = $mysqli->prepare('SELECT ' . $selCols . ' FROM users WHERE id=?');
      $stmt->bind_param('i',$uid); $stmt->execute(); $u = $stmt->get_result()->fetch_assoc();
      if ($u): ?>
      <hr>
      <h4>Edit user #<?php echo (int)$u['id']; ?></h4>
      <form method="post" action="admin.php?action=update" class="row g-2">
        <input type="hidden" name="csrf_token" value="<?php echo esc($_SESSION['csrf_token']); ?>">
        <input type="hidden" name="id" value="<?php echo (int)$u['id']; ?>">
        <div class="col-md-2"><input name="username" placeholder="Enter username" value="<?php echo esc($u['username']); ?>" class="form-control"></div>
        <div class="col-md-2"><input name="email" placeholder="user@example.com" value="<?php echo esc($u['email']); ?>" class="form-control" required></div>
        <div class="col-md-2"><input name="password" placeholder="Password" class="form-control"></div>
        <div class="col-md-2"><input name="first_name" placeholder="First name" value="<?php echo esc($u['first_name']); ?>" class="form-control"></div>
        <div class="col-md-2"><input name="last_name" placeholder="Last name" value="<?php echo esc($u['last_name']); ?>" class="form-control"></div>
        <div class="col-md-2"><input name="contact_number" placeholder="09171234567" value="<?php echo esc($u['contact_number']); ?>" class="form-control contact-number" inputmode="numeric" pattern="\d{11}" maxlength="11"></div>
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
        <div class="col-md-2"><input name="country" placeholder="Country" value="<?php echo esc($u['country']); ?>" class="form-control"></div>
        <div class="col-md-2"><input name="postal_id" placeholder="Postal ID" value="<?php echo esc($u['postal_id']); ?>" class="form-control postal-id" inputmode="numeric" pattern="\d*" maxlength="12"></div>
        <?php if ($roleColumnExists): ?>
        <div class="col-md-2">
          <label class="form-label visually-hidden">Role</label>
          <select name="role" class="form-control">
            <option value="user" <?php echo (($u['role'] ?? '') === 'user') ? 'selected' : ''; ?>>user</option>
            <option value="admin" <?php echo (($u['role'] ?? '') === 'admin') ? 'selected' : ''; ?>>admin</option>
          </select>
        </div>
        <?php endif; ?>
        <div class="col-md-6"><input name="address" placeholder="Street, City, etc." value="<?php echo esc($u['address']); ?>" class="form-control"></div>
        <div class="col-md-1"><button class="btn btn-success">Save</button></div>
      </form>
    <?php else: echo '<div class="alert alert-warning">User not found</div>'; endif; endif; ?>

</div>
</div>

<?php
// Render floating notification modal if password was changed by admin
if (!empty($_SESSION['admin_flash']) && !empty($_SESSION['admin_flash']['password_changed'])):
  $af = $_SESSION['admin_flash'];
  // clear flash so it shows only once
  unset($_SESSION['admin_flash']);
?>
<div id="adminNotifyOverlay" class="modal-overlay" aria-hidden="false">
  <div class="modal-panel" role="dialog" aria-modal="true" aria-labelledby="notifyTitle">
    <h5 id="notifyTitle">Inform user — <?php echo esc($af['username']); ?></h5>
    <div class="mb-2 text-muted small"><?php echo esc($af['email']); ?></div>
    <p class="mb-3">Password has been changed by an administrator. You may want to notify the user that their password was updated.</p>
    <div class="text-end">
      <button id="dismissNotify" class="btn btn-secondary">Dismiss</button>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function(){
  var overlay = document.getElementById('adminNotifyOverlay');
  if (overlay) {
    overlay.classList.add('show');
    // close handler
    document.getElementById('dismissNotify').addEventListener('click', function(){ overlay.classList.remove('show'); overlay.remove(); });
  }
});
</script>

<!-- Action confirmation overlay (reused for Activate/Deactivate) -->
<div id="actionConfirmOverlay" style="display:none;position:fixed;inset:0;z-index:17000;align-items:center;justify-content:center;">
  <div style="position:absolute;inset:0;background:rgba(0,0,0,0.22);backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px);"></div>
  <div id="actionConfirmPanel" role="dialog" aria-modal="true" style="position:relative;max-width:520px;width:92%;background:linear-gradient(90deg,#0d6efd,#6610f2);color:#fff;padding:18px;border-radius:10px;box-shadow:0 20px 60px rgba(2,6,23,0.18);">
    <h3 id="actionConfirmTitle" style="margin:0 0 8px 0;font-size:1.05rem;font-weight:600">Confirm action</h3>
    <p id="actionConfirmBody" style="margin:0 0 14px 0;color:rgba(255,255,255,0.95)"></p>
    <div style="display:flex;gap:8px;justify-content:flex-end">
      <button id="actionConfirmCancel" class="btn" type="button" style="padding:8px 12px;border-radius:6px;border:1px solid rgba(255,255,255,0.22);background:rgba(255,255,255,0.12);color:#fff">Cancel</button>
      <button id="actionConfirmOk" class="btn" type="button" style="padding:8px 12px;border-radius:6px;border:1px solid rgba(255,255,255,0.22);background:rgba(255,255,255,0.18);color:#fff">Confirm</button>
    </div>
  </div>
</div>
<?php endif; ?>

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
          <input name="contact_number" class="form-control contact-number" placeholder="09171234567" inputmode="numeric" pattern="\d{11}" maxlength="11">
        </div>
        <?php if ($roleColumnExists): ?>
        <div class="col-md-4">
          <label class="form-label">Role</label>
          <select name="role" class="form-control">
            <option value="user">user</option>
            <option value="admin">admin</option>
          </select>
        </div>
        <?php endif; ?>

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
              <input name="postal_id" class="form-control postal-id" placeholder="Postal ID" inputmode="numeric" pattern="\d*" maxlength="12">
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

  <script>
  // Initialize row actions (toggle status) so they can be re-attached after partial reloads
  function initRowActions(){
    var ADMIN_CSRF = '<?php echo esc($_SESSION['csrf_token']); ?>';
    // pendingAction holds the button and details for the current confirmation
    var pendingAction = null;

    // ensure overlay elements exist (create on-demand if missing)
    function ensureActionOverlay(){
      var overlay = document.getElementById('actionConfirmOverlay');
      if (overlay) return overlay;
      // create overlay DOM
      overlay = document.createElement('div'); overlay.id = 'actionConfirmOverlay';
      overlay.style.display = 'none'; overlay.style.position = 'fixed'; overlay.style.inset = '0'; overlay.style.zIndex = '17000'; overlay.style.alignItems = 'center'; overlay.style.justifyContent = 'center';
      // backdrop
      var backdrop = document.createElement('div'); backdrop.style.position='absolute'; backdrop.style.inset='0'; backdrop.style.background='rgba(0,0,0,0.22)'; backdrop.style.backdropFilter='blur(4px)'; backdrop.style.webkitBackdropFilter='blur(4px)';
      // panel
      var panel = document.createElement('div'); panel.id = 'actionConfirmPanel'; panel.setAttribute('role','dialog'); panel.style.position='relative'; panel.style.maxWidth='520px'; panel.style.width='92%'; panel.style.background='linear-gradient(90deg,#0d6efd,#6610f2)'; panel.style.color='#fff'; panel.style.padding='18px'; panel.style.borderRadius='10px'; panel.style.boxShadow='0 20px 60px rgba(2,6,23,0.18)';
      var title = document.createElement('h3'); title.id = 'actionConfirmTitle'; title.style.margin='0 0 8px 0'; title.style.fontSize='1.05rem'; title.style.fontWeight='600'; title.textContent = 'Confirm action';
      var body = document.createElement('p'); body.id = 'actionConfirmBody'; body.style.margin='0 0 14px 0'; body.style.color = 'rgba(255,255,255,0.95)';
      var actions = document.createElement('div'); actions.style.display='flex'; actions.style.gap='8px'; actions.style.justifyContent='flex-end';
      var cancel = document.createElement('button'); cancel.id='actionConfirmCancel'; cancel.className='btn'; cancel.type='button'; cancel.style.padding='8px 12px'; cancel.style.borderRadius='6px'; cancel.style.border='1px solid rgba(255,255,255,0.22)'; cancel.style.background='rgba(255,255,255,0.12)'; cancel.style.color='#fff'; cancel.textContent='Cancel';
      var ok = document.createElement('button'); ok.id='actionConfirmOk'; ok.className='btn'; ok.type='button'; ok.style.padding='8px 12px'; ok.style.borderRadius='6px'; ok.style.border='1px solid rgba(255,255,255,0.22)'; ok.style.background='rgba(255,255,255,0.18)'; ok.style.color='#fff'; ok.textContent='Confirm';
      actions.appendChild(cancel); actions.appendChild(ok);
      panel.appendChild(title); panel.appendChild(body); panel.appendChild(actions);
      overlay.appendChild(backdrop); overlay.appendChild(panel);
      document.body.appendChild(overlay);
      // wire handlers
      ok.addEventListener('click', performPendingAction);
      cancel.addEventListener('click', function(){ overlay.style.display='none'; pendingAction = null; });
      document.addEventListener('keydown', function(e){ if (e.key === 'Escape' && overlay.style.display === 'flex') { overlay.style.display = 'none'; pendingAction = null; } });
      overlay.addEventListener('click', function(e){ if (e.target === overlay) { overlay.style.display = 'none'; pendingAction = null; } });
      return overlay;
    }

    // helper: show the confirmation overlay with message and store pending action
    function showActionConfirm(message, btn, userId, action){
      var overlay = ensureActionOverlay();
      var title = document.getElementById('actionConfirmTitle');
      var body = document.getElementById('actionConfirmBody');
      title.textContent = (action === 'deactivate') ? 'Confirm Deactivate' : 'Confirm Activate';
      body.textContent = message;
      pendingAction = { button: btn, userId: userId, action: action };
      overlay.style.display = 'flex';
      var cancel = document.getElementById('actionConfirmCancel');
      setTimeout(function(){ cancel && cancel.focus(); }, 60);
      return false;
    }

    // perform the pending action (called when user confirms)
    function performPendingAction(){
      if (!pendingAction) return;
      var button = pendingAction.button;
      var userId = pendingAction.userId;
      var action = pendingAction.action;
      // hide overlay immediately
      var overlay = document.getElementById('actionConfirmOverlay'); if (overlay) overlay.style.display = 'none';
      if (!button || !userId || !action) return;
      button.disabled = true;
      fetch('toggle_user_status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': ADMIN_CSRF },
        body: JSON.stringify({ user_id: userId, action: action })
      }).then(function(r){ return r.json(); }).then(function(json){
        if (json && json.status === 'ok') {
          var row = button.closest('tr');
          var statusCell = row && row.querySelector('.user-status');
          if (statusCell) statusCell.textContent = json.new_status;
          if (action === 'deactivate') {
            button.textContent = 'Activate';
            button.setAttribute('data-action','activate');
            button.classList.remove('btn-outline-warning');
            button.classList.add('btn-outline-success');
          } else {
            button.textContent = 'Deactivate';
            button.setAttribute('data-action','deactivate');
            button.classList.remove('btn-outline-success');
            button.classList.add('btn-outline-warning');
          }
        } else {
          // show a simple alert fallback
          alert('Action failed: ' + (json && json.message ? json.message : 'unknown'));
        }
      }).catch(function(){ alert('Network error'); })
      .finally(function(){ if (button) button.disabled = false; pendingAction = null; });
    }

    // wire confirm overlay buttons
    (function wireConfirmOverlay(){
      var overlay = document.getElementById('actionConfirmOverlay');
      if (!overlay) return;
      var ok = document.getElementById('actionConfirmOk');
      var cancel = document.getElementById('actionConfirmCancel');
      if (ok) ok.addEventListener('click', performPendingAction);
      if (cancel) cancel.addEventListener('click', function(){ overlay.style.display = 'none'; pendingAction = null; });
      // Escape and click outside
      document.addEventListener('keydown', function(e){ if (e.key === 'Escape' && overlay.style.display === 'flex') { overlay.style.display = 'none'; pendingAction = null; } });
      overlay.addEventListener('click', function(e){ if (e.target === overlay) { overlay.style.display = 'none'; pendingAction = null; } });
    })();

    document.querySelectorAll('.toggle-status').forEach(function(btn){
      btn.addEventListener('click', function(){
        var userId = this.getAttribute('data-user-id');
        var action = this.getAttribute('data-action');
        var username = this.getAttribute('data-username') || '';
        if (!userId || !action) return;
        // show floating confirmation (blue background) instead of native confirm
        var label = (action === 'deactivate') ? 'Deactivate' : 'Activate';
        var message = label + ' user #' + userId + (username ? (' ("' + username + '")') : '') + '?';
        showActionConfirm(message, this, userId, action);
      });
    });
  }
  // run once on initial load
  document.addEventListener('DOMContentLoaded', function(){ initRowActions(); });
  </script>

  <script>
  // Admin table search: client-side filter over currently loaded rows (debounced)
  function initAdminSearch(){
    var input = document.getElementById('adminSearchInput');
    var clearBtn = document.getElementById('adminSearchClear');
    if (!input) return;
    var timer = null;

    function normalize(s){ return String(s||'').toLowerCase(); }

    function doFilter(){
      var q = normalize(input.value.trim());
      var tbody = document.querySelector('.admin-table-wrap table tbody');
      if (!tbody) return;
      var rows = Array.from(tbody.querySelectorAll('tr'));
      var matched = 0;
      rows.forEach(function(r){
        var text = normalize(r.textContent || '');
        var idCell = r.querySelector('td');
        if (idCell) text += ' ' + normalize(idCell.textContent || '');
        var visible = true;
        if (q) visible = text.indexOf(q) !== -1;
        r.style.display = visible ? '' : 'none';
        if (visible) matched++;
      });
      // show a no-results row when nothing matches
      var existing = tbody.querySelector('.no-results-row');
      if (matched === 0) {
        if (!existing) {
          var nr = document.createElement('tr'); nr.className = 'no-results-row';
          var c = document.createElement('td'); c.colSpan = tbody.closest('table').querySelectorAll('th').length; c.className='text-center text-muted';
          c.textContent = 'No users match your search.';
          nr.appendChild(c);
          tbody.appendChild(nr);
        }
      } else {
        if (existing) existing.remove();
      }
    }

    // attach handlers (avoid duplicate handlers by removing first)
    input.removeEventListener('input', input._adminSearchHandler);
    input._adminSearchHandler = function(){ clearTimeout(timer); timer = setTimeout(doFilter, 180); };
    input.addEventListener('input', input._adminSearchHandler);

    clearBtn && clearBtn.addEventListener('click', function(){ input.value = ''; input.dispatchEvent(new Event('input')); input.focus(); });
  }

  // initialize on first load
  document.addEventListener('DOMContentLoaded', function(){ initAdminSearch(); });
  </script>

  <!-- Inline admin logout overlay (shows on current admin page) -->
  <div id="adminLogoutOverlay" style="display:none;position:fixed;inset:0;z-index:16000;align-items:center;justify-content:center;">
    <div style="position:absolute;inset:0;background:rgba(255,255,255,0.06);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);"></div>
    <div role="dialog" aria-modal="true" aria-labelledby="adminLogoutTitle" style="position:relative;max-width:420px;width:92%;background:rgba(255,255,255,0.94);padding:18px;border-radius:10px;box-shadow:0 20px 60px rgba(2,6,23,0.18);">
      <h3 id="adminLogoutTitle" style="margin:0 0 8px 0;font-size:1.05rem;color:#111">Sign out from the admin?</h3>
      <p style="margin:0 0 14px 0;color:#444">This will sign out the current session from the site and return you to the login page.</p>
      <div style="display:flex;gap:8px;justify-content:flex-end">
        <button id="adminLogoutCancel" class="btn" type="button" style="padding:8px 12px;border-radius:6px;border:1px solid #d0d5dd;background:transparent;cursor:pointer">Cancel</button>
        <form id="adminLogoutForm" method="post" action="admin_logout.php" style="display:inline;margin:0;padding:0;">
          <button type="submit" class="btn primary" style="padding:8px 12px;border-radius:6px;border:1px solid #dc3545;background:#dc3545;color:#fff">Sign out</button>
        </form>
      </div>
    </div>
  </div>

  <script>
  // Intercept admin logout and show inline overlay
  document.addEventListener('DOMContentLoaded', function(){
    try {
      var adminLogout = document.getElementById('adminLogoutBtn');
      var overlay = document.getElementById('adminLogoutOverlay');
      var cancel = document.getElementById('adminLogoutCancel');
      if (adminLogout && overlay) {
        adminLogout.addEventListener('click', function(e){
          e.preventDefault(); e.stopPropagation();
          overlay.style.display = 'flex';
          setTimeout(function(){ cancel && cancel.focus(); }, 80);
        });
        cancel && cancel.addEventListener('click', function(){ overlay.style.display = 'none'; });
        document.addEventListener('keydown', function(ev){ if (ev.key === 'Escape' && overlay.style.display === 'flex') overlay.style.display = 'none'; });
        overlay.addEventListener('click', function(ev){ if (ev.target === overlay) overlay.style.display = 'none'; });
      }
    } catch(e){}
  });
  </script>

  <script>
  // Attach numeric-only handler to contact and postal inputs
  document.addEventListener('DOMContentLoaded', function(){
    function enforceNumeric(el){
      if (!el) return;
      var isContact = el.classList.contains('contact-number') || el.name === 'contact_number' || el.id === 'contact_number';
      var isPostal = el.classList.contains('postal-id') || el.name === 'postal_id' || el.id === 'postal_id';
      // set numeric inputmode
      el.setAttribute('inputmode','numeric');
      var maxLen = el.getAttribute('maxlength') || (isContact ? 11 : 12);
      el.setAttribute('maxlength', maxLen);

      el.addEventListener('keydown', function(e){
        // Allow: backspace, delete, tab, escape, enter, arrows
        var allowed = [8,9,13,27,46,37,38,39,40];
        if (allowed.indexOf(e.keyCode) !== -1) return;
        // Allow ctrl/cmd+A/C/V/X
        if ((e.ctrlKey || e.metaKey) && ['a','c','v','x'].indexOf(e.key.toLowerCase()) !== -1) return;
        // Prevent if not a digit
        if (!/^[0-9]$/.test(e.key)) {
          e.preventDefault();
          return;
        }
        // block input if max length reached
        if (el.value && el.value.length >= parseInt(maxLen,10) && !['Backspace','Delete'].includes(e.key)) {
          e.preventDefault();
        }
      });

      // Prevent non-digit paste
      el.addEventListener('paste', function(e){
        var paste = (e.clipboardData || window.clipboardData).getData('text');
        var digits = paste.replace(/\D+/g,'').slice(0, parseInt(maxLen,10));
        e.preventDefault();
        // insert sanitized digits at cursor
        var start = el.selectionStart || 0;
        var end = el.selectionEnd || 0;
        var newVal = (el.value.slice(0,start) + digits + el.value.slice(end)).replace(/\D+/g,'').slice(0, parseInt(maxLen,10));
        el.value = newVal;
      });

      // ensure on input only digits remain
      el.addEventListener('input', function(){
        var v = el.value.replace(/\D+/g,'').slice(0, parseInt(maxLen,10));
        if (el.value !== v) el.value = v;
      });
    }
    document.querySelectorAll('input.contact-number, input[name="contact_number"], #contact_number, input.postal-id, input[name="postal_id"], #postal_id').forEach(enforceNumeric);
  });
  </script>

  <script>
  // Sync panels width to match the admin table width so they align exactly
  (function(){
    function syncPanels(){
      var table = document.querySelector('.admin-table-wrap .table');
      var panelsInner = document.querySelector('.panels-row .panels-inner');
      if (!table || !panelsInner) return;
      // use the table's scrollWidth (full content width) so panels match the table including overflow
      var w = table.scrollWidth || table.offsetWidth || table.clientWidth;
      // apply width to panelsInner (and limit max-width) so the two panels align under the table
      panelsInner.style.width = w + 'px';
      panelsInner.style.maxWidth = w + 'px';
    }
    var resizeTimer = null;
    window.addEventListener('resize', function(){ clearTimeout(resizeTimer); resizeTimer = setTimeout(syncPanels, 120); });
    // run on DOMContentLoaded and a short timeout to catch late layout changes
    document.addEventListener('DOMContentLoaded', function(){ syncPanels(); setTimeout(syncPanels, 200); setTimeout(syncPanels, 800); });
  })();
  </script>

  <script>
  // Panels click handler: fetch current page and replace the table wrapper (partial reload)
  document.addEventListener('DOMContentLoaded', function(){
    function reloadTablePartial(filter){
      var wrap = document.querySelector('.admin-table-wrap');
      var panelsInner = document.querySelector('.panels-row .panels-inner');
      if (!wrap) return window.location.reload();
      // preserve current panels width so they don't shrink immediately during reload
      var preservedW = null;
      if (panelsInner) {
        try { preservedW = panelsInner.clientWidth || panelsInner.offsetWidth; panelsInner.style.minWidth = preservedW + 'px'; } catch(e){}
      }
      // build URL and include optional filter param and a cache-buster
      var base = window.location.href.split('#')[0].replace(/([?&])_=[^&]*/,'');
      try {
        var urlObj = new URL(base, window.location.origin);
      } catch (e) {
        // fallback to string building
        var url = base + (base.indexOf('?') === -1 ? '?' : '&') + '_=' + Date.now();
        if (filter) url += '&filter=' + encodeURIComponent(filter);
        fetch(url, { credentials: 'same-origin' }).then(function(r){ return r.text(); }).then(processHtml).catch(function(){ window.location.reload(); });
        return;
      }
      urlObj.searchParams.set('_', Date.now());
      if (filter) urlObj.searchParams.set('filter', filter);
      fetch(urlObj.toString(), { credentials: 'same-origin' }).then(function(r){ return r.text(); }).then(processHtml).catch(function(){ window.location.reload(); });

      function processHtml(html){
        var parser = new DOMParser();
        var doc = parser.parseFromString(html, 'text/html');
        var newWrap = doc.querySelector('.admin-table-wrap');
        if (newWrap) {
          // compute new table width so we can decide whether to animate panels
          var newTable = newWrap.querySelector('.table');
          var newW = (newTable && newTable.scrollWidth) ? newTable.scrollWidth : null;
          wrap.parentNode.replaceChild(newWrap, wrap);
          // re-run interactive bindings (toggle buttons etc.)
          if (typeof initRowActions === 'function') initRowActions();
          if (typeof initAdminSearch === 'function') initAdminSearch();
          // If we preserved a width, only animate if new width is larger (avoid shrinking)
          if (panelsInner && preservedW != null) {
            if (newW && newW > preservedW) {
              panelsInner.style.transition = 'width .25s ease';
              panelsInner.style.width = newW + 'px';
              // after animation, clear temporary values and set maxWidth to newW
              setTimeout(function(){
                try { panelsInner.style.transition = ''; panelsInner.style.minWidth = ''; panelsInner.style.maxWidth = newW + 'px'; } catch(e){}
              }, 320);
            } else {
              // keep preserved width (don't shrink), then clear minWidth after a short delay
              panelsInner.style.width = preservedW + 'px';
              panelsInner.style.maxWidth = preservedW + 'px';
              setTimeout(function(){ try { panelsInner.style.minWidth = ''; } catch(e){} }, 320);
            }
          } else if (panelsInner && newW) {
            // no preserved width, but we have a new width -> set and sync
            panelsInner.style.transition = 'width .25s ease';
            panelsInner.style.width = newW + 'px';
            setTimeout(function(){ try { panelsInner.style.transition = ''; panelsInner.style.minWidth = ''; panelsInner.style.maxWidth = newW + 'px'; } catch(e){} }, 320);
          } else {
            // fallback: trigger a resize to let syncPanels update sizing
            window.dispatchEvent(new Event('resize'));
            if (panelsInner) { panelsInner.style.minWidth = ''; }
          }
        } else {
          window.location.reload();
        }
      }
    }

    document.querySelectorAll('.panels-row .panel-btn').forEach(function(btn){
      btn.addEventListener('click', function(){
        var panel = btn.getAttribute('data-panel');
        var wasSelected = btn.classList.contains('selected');
        // ensure only one selected at a time
        document.querySelectorAll('.panels-row .panel-btn.selected').forEach(function(other){ if (other !== btn) { other.classList.remove('selected'); other.setAttribute('aria-pressed','false'); } });

        // toggle selection on this button
        if (wasSelected) {
          btn.classList.remove('selected');
          btn.setAttribute('aria-pressed','false');
        } else {
          btn.classList.add('selected');
          btn.setAttribute('aria-pressed','true');
        }

        // visual pressed feedback and disable while loading
        btn.classList.add('pressed');
        btn.disabled = true;

        // If we were selected and now untoggled, clear the filter (reload all). Otherwise apply the chosen filter.
        var filterToApply = wasSelected ? null : panel;
        reloadTablePartial(filterToApply);

        setTimeout(function(){ btn.disabled = false; btn.classList.remove('pressed'); }, 800);
      });
    });

    // Initialize panel selection from URL filter param (if present)
    try {
      var params = new URLSearchParams(window.location.search);
      var initFilter = params.get('filter');
      if (initFilter) {
        var startBtn = document.querySelector('.panels-row .panel-btn[data-panel="' + initFilter + '"]');
        if (startBtn) { startBtn.classList.add('selected'); startBtn.setAttribute('aria-pressed','true'); }
      }
    } catch (e) {}
  });
  </script>
