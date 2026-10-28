<?php
require_once __DIR__ . '/data/products.php';

$pageTitle = "My Account & Member Mini Dashboard — Feather Fits";
$pageDescription = "Feather Fits Member Mini Dashboard — Track live orders, manage saved delivery addresses, check Feather Coins wallet balance, and update smart size profile.";
$currentPage = 'account';
$currentDept = 'all';

require_once __DIR__ . '/includes/header.php';
?>

  <!-- Breadcrumbs Bar -->
  <nav class="breadcrumbs-container" aria-label="Breadcrumb">
    <div class="breadcrumbs-wrap">
      <a href="index.php">Home</a>
      <span>/</span>
      <span style="color: var(--color-primary); font-weight: 700;">VIP Member Dashboard</span>
    </div>
  </nav>

  <!-- Dedicated Executive Dashboard & Auth Container -->
  <main class="account-page-main">
    <div class="account-page-wrap" id="account-page-root">
      <!-- Injected by account-page.js (Header, KPI Cards, Tab Controls, and Active Tab Views) -->
    </div>
  </main>

  <!-- GST Tax Invoice Modal -->
  <div class="modal-overlay" id="invoice-modal">
    <div class="modal-container" id="invoice-modal-content" style="max-width: 620px;">
      <!-- Dynamic via app.js -->
    </div>
  </div>

  <div id="toast-container" class="toast-container"></div>

<?php
require_once __DIR__ . '/includes/footer.php';
require_once __DIR__ . '/includes/modals.php';
?>

  <!-- Scripts -->
  <script src="data/products.js"></script>
  <script src="js/app.js"></script>
  <script src="js/account-page.js"></script>
</body>
</html>
