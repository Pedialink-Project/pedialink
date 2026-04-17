<?php
$emailTitle = 'New Admin Account';
$preheader = 'Your admin account is ready. Sign in and update your temporary password.';

ob_start();
?>
<h1>New Admin Account</h1>

<p>Hi <?= htmlspecialchars($username) ?>,</p>

<p>
    We have created a <?= htmlspecialchars($adminType) ?> admin account under this email. A temporary password has been generated for you. Change the password after you login.
</p>

<p>Your temporary password:</p>
<p class="credential"><?= htmlspecialchars($generatedPassword) ?></p>

<p>Click on the link to access the site:</p>

<p class="muted">
    <a href="<?= htmlspecialchars($appUrl) ?>" target="_blank" rel="noopener noreferrer">
        <?= htmlspecialchars($appUrl) ?>
    </a>
</p>

<hr class="divider" />

<p class="muted">If you did not request this, you can ignore this email or contact us at
    <a href="mailto:pedialink@gmail.com">pedialink@gmail.com</a>.
</p>
<?php
$emailBodyHtml = ob_get_clean();
require __DIR__ . '/layouts/base.html.php';