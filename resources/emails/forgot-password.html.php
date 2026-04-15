<?php
$emailTitle = 'Reset Password';
$preheader = 'Reset your PediaLink password using the secure link.';

ob_start();
?>
<h1>Reset Password</h1>

<p>Hi, <?= htmlspecialchars($username) ?></p>

<p>
    We received a request to reset the password for your account. If you made this request, please use the link below to set a new password. This link is valid for a limited time for security reasons. If you did not request a password reset, you can safely ignore this email and no changes will be made to your account.
</p>

<p class="button-wrap">
    <a href="<?= htmlspecialchars($forgot_link) ?>" class="button" target="_blank" rel="noopener noreferrer">
        Reset your password
    </a>
</p>

<p>If the button above does not work, copy and paste the following URL into your browser:</p>

<p class="muted">
    <a href="<?= htmlspecialchars($forgot_link) ?>" target="_blank" rel="noopener noreferrer">
        <?= htmlspecialchars($forgot_link) ?>
    </a>
</p>

<hr class="divider" />

<p class="muted">If you did not request this, you can ignore this email or contact us at
    <a href="mailto:pedialink@gmail.com">pedialink@gmail.com</a>.
</p>
<?php
$emailBodyHtml = ob_get_clean();
require __DIR__ . '/layouts/base.html.php';