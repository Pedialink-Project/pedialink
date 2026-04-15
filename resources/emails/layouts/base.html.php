<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title><?= htmlspecialchars($emailTitle ?? 'PediaLink Notification') ?></title>
    <?php require __DIR__ . '/../partials/styles.html.php'; ?>
</head>

<body>
    <span class="preheader"><?= htmlspecialchars($preheader ?? '') ?></span>

    <div class="wrap" role="article" aria-label="<?= htmlspecialchars($emailTitle ?? 'PediaLink Notification') ?>">
        <div class="header">
            <strong>PediaLink</strong>
        </div>

        <div class="content">
            <?= $emailBodyHtml ?? '' ?>
        </div>

        <div class="footer">
            <div>PediaLink</div>
            <div style="margin-top:6px;">© 2026 PediaLink. All rights reserved.</div>
        </div>
    </div>
</body>

</html>