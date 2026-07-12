<?php
session_start();
require_once __DIR__ . '/db.php';

$e = fn(string $s): string => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');

// CSRF token
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf'];

function csrf_check(): void {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(403);
        exit('Invalid token.');
    }
}

// Auth
$auth_error = '';
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    csrf_check();
    if ($_POST['user'] === ADMIN_USER && password_verify($_POST['pass'], ADMIN_PASS_HASH)) {
        $_SESSION['auth'] = true;
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    $auth_error = 'Invalid credentials.';
}

if (isset($_POST['action']) && $_POST['action'] === 'logout') {
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

$authed = !empty($_SESSION['auth']);

// CRUD actions (require auth)
if ($authed) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_check();
        $action = $_POST['action'] ?? '';

        if ($action === 'add') {
            insert_donation($_POST);
            header('Location: ' . $_SERVER['PHP_SELF'] . '?ok=added');
            exit;
        }

        if ($action === 'edit') {
            update_donation((int)$_POST['id'], $_POST);
            header('Location: ' . $_SERVER['PHP_SELF'] . '?ok=saved');
            exit;
        }

        if ($action === 'toggle') {
            toggle_public((int)$_POST['id']);
            header('Location: ' . $_SERVER['PHP_SELF'] . '?ok=toggled');
            exit;
        }

        if ($action === 'delete') {
            delete_donation((int)$_POST['id']);
            header('Location: ' . $_SERVER['PHP_SELF'] . '?ok=deleted');
            exit;
        }
    }
}

$edit_row   = null;
$flash      = '';

if ($authed) {
    if (isset($_GET['edit'])) {
        $edit_row = get_donation((int)$_GET['edit']);
    }
    $ok_msgs = ['added' => 'Donation added.', 'saved' => 'Donation updated.', 'toggled' => 'Visibility changed.', 'deleted' => 'Donation deleted.'];
    if (isset($_GET['ok']) && isset($ok_msgs[$_GET['ok']])) {
        $flash = $ok_msgs[$_GET['ok']];
    }
    $donations = get_all_donations();
}

$currencies = ['EUR', 'USD', 'GBP'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Donations Admin — Soplos Linux</title>
<link rel="icon" href="../../images/logo/soplos-logo.png" type="image/png">
<link rel="stylesheet" href="../../styles/styles.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    /* Admin-specific styles */
    .flash {
        padding: 0.65rem 1rem;
        border-radius: 6px;
        margin-bottom: 1.25rem;
        background: #1e3a1e;
        border: 1px solid #2d5a2d;
        color: #7ed87e;
        font-size: 0.85rem;
    }

    .admin-topbar {
        background: var(--card-bg, #1a1a1a);
        border-bottom: 1px solid var(--border-color, #2a2a2a);
        padding: 0.6rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        font-size: 0.85rem;
    }
    .admin-topbar span { color: var(--text-muted, #aaa); }

    /* Login */
    .login-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4rem 1rem;
    }
    .login-box {
        background: var(--card-bg, #1a1a1a);
        border: 1px solid var(--border-color, #2a2a2a);
        border-radius: 10px;
        padding: 2rem;
        width: 320px;
    }
    .login-box h2 { font-size: 1.1rem; margin-bottom: 1.25rem; color: var(--accent-color, #f5a623); text-align: center; }
    .login-error { color: #f87171; font-size: 0.82rem; margin-bottom: 0.75rem; }

    /* Form elements */
    .form-group { margin-bottom: 0.85rem; }
    .form-group label { display: block; font-size: 0.8rem; color: var(--text-muted, #aaa); margin-bottom: 0.3rem; }
    .admin-input, .admin-select, .admin-textarea {
        width: 100%;
        background: #111;
        border: 1px solid var(--border-color, #2a2a2a);
        color: #e0e0e0;
        border-radius: 6px;
        padding: 0.5rem 0.7rem;
        font-size: 0.88rem;
        font-family: inherit;
    }
    .admin-input:focus, .admin-select:focus, .admin-textarea:focus {
        outline: none;
        border-color: var(--accent-color, #f5a623);
    }
    .admin-textarea { resize: vertical; min-height: 60px; }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.45rem 1rem;
        border: none;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
        transition: opacity 0.15s;
        text-decoration: none;
    }
    .btn:hover { opacity: 0.85; }
    .btn-primary { background: var(--accent-color, #f5a623); color: #000; }
    .btn-sm { padding: 0.3rem 0.6rem; font-size: 0.78rem; }
    .btn-danger  { background: #7f1d1d; color: #fca5a5; }
    .btn-muted   { background: #2a2a2a; color: #aaa; }
    .btn-success { background: #14532d; color: #86efac; }

    /* Section card */
    .admin-card {
        background: var(--card-bg, #1a1a1a);
        border: 1px solid var(--border-color, #2a2a2a);
        border-radius: 10px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
    }
    .admin-card h2 {
        font-size: 0.95rem;
        margin-bottom: 1rem;
        color: var(--accent-color, #f5a623);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Form grid */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 0.75rem;
    }
    .form-grid .span2 { grid-column: span 2; }
    .form-actions { margin-top: 0.85rem; display: flex; gap: 0.5rem; flex-wrap: wrap; }

    /* Table */
    .table-wrap { overflow-x: auto; }
    .admin-table { width: 100%; border-collapse: collapse; }
    .admin-table th, .admin-table td { padding: 0.55rem 0.75rem; text-align: left; border-bottom: 1px solid #222; }
    .admin-table th { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.04em; color: #888; }
    .admin-table tbody tr:hover td { background: rgba(245,166,35,0.05); }
    .badge-pub  { display: inline-block; padding: 0.15rem 0.5rem; border-radius: 4px; font-size: 0.7rem; font-weight: 700; background: #14532d; color: #86efac; }
    .badge-priv { display: inline-block; padding: 0.15rem 0.5rem; border-radius: 4px; font-size: 0.7rem; font-weight: 700; background: #3a1a1a; color: #f87171; }
    .actions-col { display: flex; gap: 0.35rem; flex-wrap: wrap; }
    .flag { width: 20px; height: 14px; vertical-align: middle; margin-right: 4px; }
    .amount-cell { font-weight: 700; color: var(--accent-color, #f5a623); white-space: nowrap; }
    .notes-cell { color: #666; font-style: italic; max-width: 180px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .admin-page { max-width: 1100px; margin: 0 auto; padding: 1.5rem; }
</style>
</head>
<body>

<header>
    <div class="container">
        <div class="logo">
            <a href="../../">
                <img src="../../images/logo/web-logo.png" alt="Soplos Linux Logo">
            </a>
            <img class="mourning-ribbon" src="../../images/ribbon/Black_Ribbon.png" alt="En memoria de Marc Miralles">
        </div>
        <div class="header-right">
            <button class="mobile-menu-toggle" aria-label="Menu">
                <i class="fas fa-bars"></i>
            </button>
            <nav>
                <ul>
                    <li><a href="../../" id="menuHome">Home</a></li>
                    <li class="menu-dropdown">
                        <a href="#" class="menu-dropdown-toggle" id="menuDiscover">Discover <i class="fas fa-chevron-down"></i></a>
                        <ul class="submenu">
                            <li><a href="../../index.html#features" id="menuFeatures">Features</a></li>
                            <li><a href="../../index.html#galleries" id="menuGalleries">Galleries</a></li>
                            <li><a href="../../index.html#apps" id="menuApps">Soplos Apps</a></li>
                            <li><a href="../../index.html#requirements" id="menuRequirements">System Requirements</a></li>
                            <li><a href="../../index.html#download" id="menuDownload">Download</a></li>
                        </ul>
                    </li>
                    <li class="menu-dropdown">
                        <a href="#" class="menu-dropdown-toggle" id="menuResources">Resources <i class="fas fa-chevron-down"></i></a>
                        <ul class="submenu">
                            <li><a href="/wiki/" id="menuWiki">Wiki</a></li>
                            <li><a href="/wiki/releases/" id="menuReleases">Releases</a></li>
                            <li><a href="../../index.html#docs" id="menuDocs">Documentation</a></li>
                            <li><a href="../../forums/" id="menuForum">Forum</a></li>
                        </ul>
                    </li>
                    <li class="menu-dropdown">
                        <a href="#" class="menu-dropdown-toggle" id="menuCommunityDropdown">Community <i class="fas fa-chevron-down"></i></a>
                        <ul class="submenu">
                            <li><a href="../../index.html#community" id="menuCommunity">Join</a></li>
                            <li><a href="/community/contributors/" id="menuContributors">Contributors</a></li>
                            <li><a href="/community/videos/" id="menuVideos">Videos</a></li>
                            <li><a href="/community/branding/" id="menuBranding">Branding</a></li>
                            <li><a href="https://github.com/SoplosLinux" id="menuGitHub" target="_blank">GitHub</a></li>
                            <li><a href="https://x.com/soploslinux" id="menuTwitter" target="_blank">X (Twitter)</a></li>
                            <li><a href="/community/donate/" id="menuDonate">Donate</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <div class="language-selector" id="language-selector">
                <div class="selected-language">
                    <img src="https://flagcdn.com/w20/gb.png" alt="English">
                    <span>EN</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="language-dropdown">
                    <a href="#" data-lang="en"><img src="https://flagcdn.com/w20/gb.png" alt="English"> EN</a>
                    <a href="#" data-lang="es"><img src="https://flagcdn.com/w20/es.png" alt="Español"> ES</a>
                    <a href="#" data-lang="fr"><img src="https://flagcdn.com/w20/fr.png" alt="Français"> FR</a>
                    <a href="#" data-lang="pt"><img src="https://flagcdn.com/w20/pt.png" alt="Português"> PT</a>
                    <a href="#" data-lang="de"><img src="https://flagcdn.com/w20/de.png" alt="Deutsch"> DE</a>
                    <a href="#" data-lang="it"><img src="https://flagcdn.com/w20/it.png" alt="Italiano"> IT</a>
                    <a href="#" data-lang="ro"><img src="https://flagcdn.com/w20/ro.png" alt="Română"> RO</a>
                    <a href="#" data-lang="ru"><img src="https://flagcdn.com/w20/ru.png" alt="Русский"> RU</a>
                </div>
            </div>
        </div>
    </div>
</header>

<main>

<?php if (!$authed): ?>

<div class="login-wrap">
    <div class="login-box">
        <h2><i class="fas fa-lock"></i> <span id="admin-title">Donations Admin</span></h2>
        <?php if ($auth_error): ?>
            <p class="login-error" id="admin-error-credentials">Invalid credentials.</p>
        <?php endif; ?>
        <form method="post" action="">
            <input type="hidden" name="action" value="login">
            <input type="hidden" name="csrf" value="<?= $e($csrf) ?>">
            <div class="form-group">
                <label id="admin-label-user">Username</label>
                <input class="admin-input" type="text" name="user" required autocomplete="username">
            </div>
            <div class="form-group">
                <label id="admin-label-pass">Password</label>
                <input class="admin-input" type="password" name="pass" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:0.5rem;" id="admin-btn-login">Log in</button>
        </form>
    </div>
</div>

<?php else: ?>

<div class="admin-topbar">
    <span><i class="fas fa-chart-bar" style="color:#f5a623;margin-right:0.4rem;"></i> <span id="admin-topbar-title">Donations Admin</span></span>
    <div style="display:flex;align-items:center;gap:1rem;">
        <a href="/community/donate/" target="_blank" style="font-size:0.82rem;color:#f5a623;">
            <i class="fas fa-external-link-alt"></i> <span id="admin-link-public">Public page</span>
        </a>
        <form method="post" action="" style="margin:0;">
            <input type="hidden" name="action" value="logout">
            <input type="hidden" name="csrf" value="<?= $e($csrf) ?>">
            <button type="submit" class="btn btn-muted btn-sm" id="admin-btn-logout">Log out</button>
        </form>
    </div>
</div>

<div class="admin-page">

    <?php if ($flash): ?>
        <div class="flash"><?= $e($flash) ?></div>
    <?php endif; ?>

    <!-- Add / Edit form -->
    <div class="admin-card">
        <h2>
            <i class="fas <?= $edit_row ? 'fa-pen' : 'fa-plus' ?>"></i>
            <?= $edit_row ? 'Edit donation #' . $edit_row['id'] : 'Add donation' ?>
        </h2>
        <form method="post" action="">
            <input type="hidden" name="csrf" value="<?= $e($csrf) ?>">
            <input type="hidden" name="action" value="<?= $edit_row ? 'edit' : 'add' ?>">
            <?php if ($edit_row): ?>
                <input type="hidden" name="id" value="<?= (int)$edit_row['id'] ?>">
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group">
                    <label id="admin-label-name">Name</label>
                    <input class="admin-input" type="text" name="name" maxlength="100" required
                           value="<?= $e($edit_row['name'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label id="admin-label-country">Country code (ISO 2)</label>
                    <input class="admin-input" type="text" name="country_code" maxlength="2" placeholder="es"
                           value="<?= $e($edit_row['country_code'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label id="admin-label-amount">Amount</label>
                    <input class="admin-input" type="number" name="amount" step="0.01" min="0.01" required
                           value="<?= $e($edit_row['amount'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label id="admin-label-currency">Currency</label>
                    <select class="admin-select" name="currency">
                        <?php foreach ($currencies as $c): ?>
                            <option value="<?= $c ?>" <?= ($edit_row['currency'] ?? 'EUR') === $c ? 'selected' : '' ?>><?= $c ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label id="admin-label-date">Date</label>
                    <input class="admin-input" type="date" name="donated_at" required
                           value="<?= $e($edit_row['donated_at'] ?? date('Y-m-d')) ?>">
                </div>
                <div class="form-group">
                    <label id="admin-label-public">Public</label>
                    <select class="admin-select" name="public">
                        <option value="1" id="admin-opt-yes" <?= ($edit_row['public'] ?? 1) == 1 ? 'selected' : '' ?>>Yes</option>
                        <option value="0" id="admin-opt-hidden" <?= ($edit_row['public'] ?? 1) == 0 ? 'selected' : '' ?>>No (hidden)</option>
                    </select>
                </div>
                <div class="form-group span2">
                    <label id="admin-label-link">Website link (optional)</label>
                    <input class="admin-input" type="url" name="link" maxlength="255" placeholder="https://..."
                           value="<?= $e($edit_row['link'] ?? '') ?>">
                </div>
                <div class="form-group span2">
                    <label id="admin-label-notes">Admin notes (not public)</label>
                    <textarea class="admin-textarea" name="notes" maxlength="500"><?= $e($edit_row['notes'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="form-actions">
                <?php if ($edit_row): ?>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> <span id="admin-btn-save">Save changes</span>
                </button>
                <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-muted" id="admin-btn-cancel">Cancel</a>
                <?php else: ?>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> <span id="admin-btn-add">Add donation</span>
                </button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Donations list -->
    <div class="admin-card">
        <h2><i class="fas fa-list"></i> All donations (<?= count($donations) ?>)</h2>
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th id="admin-th-date">Date</th>
                        <th id="admin-th-name">Name</th>
                        <th id="admin-th-amount">Amount</th>
                        <th id="admin-th-visible">Visible</th>
                        <th id="admin-th-notes">Notes</th>
                        <th id="admin-th-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($donations)): ?>
                    <tr><td colspan="7" style="color:#555;text-align:center;padding:2rem;" id="admin-empty">No donations yet.</td></tr>
                <?php endif; ?>
                <?php foreach ($donations as $row): ?>
                    <tr>
                        <td style="color:#555;"><?= (int)$row['id'] ?></td>
                        <td><?= $e($row['donated_at']) ?></td>
                        <td>
                            <?php if ($row['country_code']): ?>
                                <img class="flag" src="https://flagcdn.com/w20/<?= $e(strtolower($row['country_code'])) ?>.png" alt="<?= $e($row['country_code']) ?>">
                            <?php endif; ?>
                            <?php if ($row['link']): ?>
                                <a href="<?= $e($row['link']) ?>" target="_blank" rel="noopener"><?= $e($row['name']) ?></a>
                            <?php else: ?>
                                <?= $e($row['name']) ?>
                            <?php endif; ?>
                        </td>
                        <td class="amount-cell"><?= number_format((float)$row['amount'], 2) ?> <?= $e($row['currency']) ?></td>
                        <td>
                            <?php if ($row['public']): ?>
                                <span class="badge-pub" data-i18n="admin-badge-pub">Public</span>
                            <?php else: ?>
                                <span class="badge-priv" data-i18n="admin-badge-priv">Hidden</span>
                            <?php endif; ?>
                        </td>
                        <td class="notes-cell" title="<?= $e($row['notes'] ?? '') ?>"><?= $e($row['notes'] ?? '') ?></td>
                        <td>
                            <div class="actions-col">
                                <a href="?edit=<?= (int)$row['id'] ?>" class="btn btn-muted btn-sm">
                                    <i class="fas fa-pen"></i> <span data-i18n="admin-btn-edit">Edit</span>
                                </a>
                                <form method="post" action="" style="margin:0;">
                                    <input type="hidden" name="csrf" value="<?= $e($csrf) ?>">
                                    <input type="hidden" name="action" value="toggle">
                                    <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                                    <button type="submit" class="btn btn-sm <?= $row['public'] ? 'btn-muted' : 'btn-success' ?>">
                                        <i class="fas <?= $row['public'] ? 'fa-eye-slash' : 'fa-eye' ?>"></i>
                                        <span data-i18n="<?= $row['public'] ? 'admin-btn-hide' : 'admin-btn-show' ?>"><?= $row['public'] ? 'Hide' : 'Show' ?></span>
                                    </button>
                                </form>
                                <form method="post" action="" style="margin:0;"
                                      onsubmit="return confirm(window.getTranslatedText('admin-confirm-delete'))">
                                    <input type="hidden" name="csrf" value="<?= $e($csrf) ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> <span data-i18n="admin-btn-delete">Delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php endif; ?>

</main>

<footer>
    <div class="container">
        <div class="container">
            <p>&copy; 2026 Sergi Perich</p>
            <p class="legal-links">
                <a href="../../legal/privacy.html" id="footer-privacy">Privacy Policy</a> |
                <a href="../../legal/terms.html" id="footer-terms">Terms of Use</a>
            </p>
        </div>
    </div>
</footer>

<script src="../../js/logger.js"></script>
<script src="../../js/language-loader.js"></script>
<script src="../../js/language-switcher.js"></script>
<script src="../../js/soplos-ui.js"></script>
<script src="../../js/main.js"></script>
</body>
</html>
