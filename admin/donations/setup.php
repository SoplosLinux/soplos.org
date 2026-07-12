<?php
// DELETE THIS FILE after use
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['pass']) && !empty($_POST['user'])) {
    $hash = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    $user = htmlspecialchars($_POST['user'], ENT_QUOTES, 'UTF-8');
    ?>
    <!DOCTYPE html>
    <html><head><meta charset="UTF-8"><title>Setup</title></head>
    <body style="background:#111;color:#eee;font-family:sans-serif;padding:2rem;max-width:700px;">
    <h2 style="color:#f5a623;">Copia estos valores en config.php</h2>

    <p><strong>ADMIN_USER</strong> (<?= strlen($user) ?> chars):</p>
    <textarea rows="1" onclick="this.select()" style="width:100%;background:#1a1a1a;color:#0f0;padding:0.75rem;border:1px solid #333;border-radius:4px;font-family:monospace;font-size:0.95rem;"><?= $user ?></textarea>

    <p style="margin-top:1.5rem;"><strong>ADMIN_PASS_HASH</strong> (<?= strlen($hash) ?> chars — debe ser 60):</p>
    <textarea rows="2" onclick="this.select()" style="width:100%;background:#1a1a1a;color:#0f0;padding:0.75rem;border:1px solid #333;border-radius:4px;font-family:monospace;font-size:0.95rem;"><?= htmlspecialchars($hash) ?></textarea>

    <p style="color:#f5a623;margin-top:1.5rem;">Borra este archivo del servidor cuando hayas terminado.</p>
    </body></html>
    <?php
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Admin setup</title></head>
<body style="background:#111;color:#eee;font-family:sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;">
<form method="post" style="background:#1a1a1a;padding:2rem;border-radius:8px;min-width:320px;">
    <h2 style="color:#f5a623;margin-top:0;">Admin setup</h2>
    <label>Username</label><br>
    <input type="text" name="user" required style="width:100%;padding:0.5rem;margin:0.5rem 0 1rem;background:#111;border:1px solid #333;color:#eee;border-radius:4px;font-size:1rem;">
    <label>Password</label><br>
    <input type="password" name="pass" required style="width:100%;padding:0.5rem;margin:0.5rem 0 1rem;background:#111;border:1px solid #333;color:#eee;border-radius:4px;font-size:1rem;">
    <button type="submit" style="width:100%;padding:0.6rem;background:#f5a623;border:none;border-radius:4px;font-weight:bold;cursor:pointer;font-size:1rem;">Generate</button>
</form>
</body>
</html>
