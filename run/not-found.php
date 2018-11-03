<?php
ob_clean();
header("HTTP/1.1 404 Not Found");
require_once 'run/header.php';
?>

<section>
<main>
<h3>Oops! 404 error.</h3>
<p>What you're looking for does not exist.</p>
  </main>
</section>

<?php
require_once 'run/footer.php';

exit;
