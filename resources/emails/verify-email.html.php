<?php
$emailTitle = 'Verify your email';
$preheader = 'Verify your email address to activate your PediaLink account.';

ob_start();
?>
<h1>Verify your email address</h1>

<p>Hi <?= htmlspecialchars($username) ?>,</p>

<p>Thank you for creating an account. Click the button below to verify your email address. This link will
    expire in 1 hour.</p>

<p class="button-wrap">
    <a href="<?= htmlspecialchars($verify_link) ?>" class="button" target="_blank" rel="noopener noreferrer">
        Verify my email
    </a>
</p>

<p>If the button above does not work, copy and paste the following URL into your browser:</p>

<p class="muted">
    <a href="<?= htmlspecialchars($verify_link) ?>" target="_blank" rel="noopener noreferrer">
        <?= htmlspecialchars($verify_link) ?>
    </a>
</p>

<hr class="divider" />

<p class="muted">If you did not request this, you can ignore this email or contact us at
    <a href="mailto:pedialink@gmail.com">pedialink@gmail.com</a>.
</p>
<?php
$emailBodyHtml = ob_get_clean();
require __DIR__ . '/layouts/base.html.php';