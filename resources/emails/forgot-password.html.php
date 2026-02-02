<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Reset Password</title>
    <style>
        /* Minimal, email-safe CSS */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f7fb;
            color: #111;
        }

        .wrap {
            max-width: 600px;
            margin: 32px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(16, 24, 40, 0.06);
        }

        .header {
            padding: 20px 24px;
            background: #0b66c3;
            color: #fff;
        }

        .content {
            padding: 24px;
        }

        h1 {
            margin: 0 0 8px 0;
            font-size: 20px;
            font-weight: 600;
        }

        p {
            margin: 0 0 12px 0;
            line-height: 1.45;
        }

        .button {
            display: inline-block;
            padding: 12px 20px;
            background: #0b66c3;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
        }

        .muted {
            color: #6b7280;
            font-size: 13px;
        }

        .footer {
            padding: 16px 24px;
            font-size: 12px;
            color: #6b7280;
            background: #fbfdff;
        }

        a {
            color: inherit;
        }

        @media (max-width:420px) {
            .wrap {
                margin: 16px;
            }

            .content {
                padding: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="wrap" role="article" aria-label="Reset Password">
        <div class="header">
            <strong>PediaLink</strong>
        </div>

        <div class="content">
            <h1>Reset Password</h1>

            <p>Hi, <?= htmlspecialchars($username) ?></p>

            <p>
                We received a request to reset the password for your account. If you made this request, please use the link below to set a new password. This link is valid for a limited time for security reasons. If you did not request a password reset, you can safely ignore this email and no changes will be made to your account.
            </p>

            <p style="text-align:center; margin:18px 0;">
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

            <hr style="border:none; border-top:1px solid #eef2f7; margin:20px 0;" />

            <p class="muted">If you did not request this, you can ignore this email or contact us at
                <a href="mailto:pedialink@gmail.com">pedialink@gmail.com</a>.
            </p>
        </div>

        <div class="footer">
            <div>PediaLink</div>
            <div style="margin-top:6px;">© 2026 PediaLink. All rights reserved.</div>
        </div>
    </div>
</body>

</html>