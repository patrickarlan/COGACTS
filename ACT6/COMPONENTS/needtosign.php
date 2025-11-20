<?php
// COMPONENTS/needtosign.php
// Usage: include this file where you want to show the sign-in required popup
// Call showNeedToSignPopup() if user is not logged in

function showNeedToSignPopup() {
  echo '<div id="needToSignOverlay" style="position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(33,37,41,0.7);backdrop-filter:blur(4px);z-index:9999;display:flex;align-items:center;justify-content:center;">';
  echo '  <div style="background:#fff;padding:2.5rem 2rem;border-radius:16px;box-shadow:0 8px 32px rgba(0,0,0,0.18);max-width:350px;text-align:center;font-family:Arial,sans-serif;">';
  echo '    <h4 style="color:#dc3545;font-weight:700;margin-bottom:1.2rem;font-family:Arial,sans-serif;">Sign In Required</h4>';
  echo '    <p style="color:#333;font-size:1.1rem;margin-bottom:2rem;font-family:Arial,sans-serif;">You need to sign in before redirecting to the page.</p>';
  echo '    <div style="display:flex;gap:1rem;justify-content:center;">';
  echo '      <a href="logsign.php" style="background:#dc3545;color:#fff;padding:0.7rem 1.5rem;border-radius:8px;font-weight:600;text-decoration:none;box-shadow:0 2px 8px rgba(220,53,69,0.12);transition:background 0.2s;font-family:Arial,sans-serif;">Login</a>';
  echo '      <button onclick="window.location.href=\'index.php\'" style="background:#6c757d;color:#fff;padding:0.7rem 1.5rem;border-radius:8px;font-weight:600;border:none;cursor:pointer;font-family:Arial,sans-serif;">Okay</button>';
  echo '    </div>';
  echo '  </div>';
  echo '</div>';
}
?>

<!doctype html>
<html lang="en">
<?php include 'COMPONENTS/head.php'; ?>
</html>
