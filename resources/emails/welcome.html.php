<!-- resources/emails/welcome.html.php -->
<!doctype html>
<html>
  <head><meta charset="utf-8"><title>Welcome</title></head>
  <body>
    <h1>Welcome, <?= htmlspecialchars($name) ?>!</h1>
    <p>Thanks for joining <?= htmlspecialchars($appName) ?>.</p>
    <p>
        To get started, click 
        <a href="<?= htmlspecialchars($ctaUrl) ?>">
            <?= htmlspecialchars($ctaText ?? 'here') ?>
        </a>.
    </p>
  </body>
</html>