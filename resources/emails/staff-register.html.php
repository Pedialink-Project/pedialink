<?php
$emailTitle = 'Register your account';
$preheader = 'Your PediaLink staff account is ready. Complete your registration.';

ob_start();
?>
<h1>Register your account</h1>

<p>Hi,</p>

<p>Your staff account has been created by PediaLink Admin. Continue to account creation by clicking on
    the link given below.</p>

<?php if (isset($message) && trim($message) !== '') { ?>
<p>
    Message from admin: "<?= htmlspecialchars($message) ?>"
</p>
<?php } ?>

<p class="button-wrap">
    <a href="<?= htmlspecialchars($register_link) ?>" class="button" target="_blank" rel="noopener noreferrer">
        Register your account
    </a>
</p>

<p>If the button above does not work, copy and paste the following URL into your browser:</p>

<p class="muted">
    <a href="<?= htmlspecialchars($register_link) ?>" target="_blank" rel="noopener noreferrer">
        <?= htmlspecialchars($register_link) ?>
    </a>
</p>

<hr class="divider" />

<p class="muted">If you did not request this, you can ignore this email or contact us at
    <a href="mailto:pedialink@gmail.com">pedialink@gmail.com</a>.
</p>
<?php
$emailBodyHtml = ob_get_clean();
require __DIR__ . '/layouts/base.html.php';