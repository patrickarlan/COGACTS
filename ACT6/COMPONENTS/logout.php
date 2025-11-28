<?php
// Show a floating confirmation before logging out.
// If the request is POST (confirmed) or ?confirm=1 is present, perform logout immediately.
session_start();

// If user confirmed (POST) or explicitly requested via query param, destroy session and redirect.
if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['confirm'])) {
		session_unset();
		session_destroy();

		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0, private");
		header("Pragma: no-cache");
		header("Expires: 0");

		header("Location: /COG3BACK/ACT6/logsign.php");
		exit();
}

// Otherwise render a small page that shows a floating confirmation modal.
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0, private");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Sign Out — Confirm</title>
	<style>
		html,body{height:100%;margin:0;font-family:Arial,Helvetica,sans-serif;background:#f6f8fb}
		/* Use a translucent overlay with backdrop-filter to blur page content underneath */
		.overlay{position:fixed;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,0.06);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);z-index:9999}
		/* Slightly translucent modal so the blurred site shows through subtly */
		.modal{background:rgba(255,255,255,0.88);padding:20px;border-radius:10px;max-width:420px;width:94%;box-shadow:0 20px 60px rgba(2,6,23,0.18);text-align:left}
		.modal h3{margin:0 0 8px 0;font-size:1.05rem}
		.modal p{margin:0 0 14px 0;color:#444}
		.modal .actions{display:flex;gap:8px;justify-content:flex-end}
		.btn{padding:8px 12px;border-radius:6px;border:1px solid #d0d5dd;background:#fff;cursor:pointer}
		.btn.primary{background:#dc3545;border-color:#dc3545;color:#fff}
		.btn.ghost{background:transparent}
	</style>
</head>
<body>
	<div class="overlay" role="dialog" aria-modal="true" aria-labelledby="logoutTitle">
		<div class="modal">
			<h3 id="logoutTitle">Sign out from the site?</h3>
			<p>You are about to sign out. This will end your current session and return you to the login page.</p>
			<div class="actions">
				<button id="cancelBtn" class="btn ghost" type="button">Cancel</button>
				<form method="post" style="display:inline;margin:0;padding:0">
					<button type="submit" class="btn primary">Sign out</button>
				</form>
			</div>
		</div>
	</div>

	<script>
		(function(){
			var cancel = document.getElementById('cancelBtn');
			cancel && cancel.addEventListener('click', function(){
				// If there is a referrer, go back; otherwise return to site index
				try {
					if (document.referrer && document.referrer.indexOf(location.origin) === 0) {
						history.back();
						return;
					}
				} catch(e) {}
				window.location.href = '/COG3BACK/ACT6/index.php';
			});
			// allow Escape to cancel
			document.addEventListener('keydown', function(e){ if (e.key === 'Escape') cancel && cancel.click(); });
		})();
	</script>
</body>
</html>
