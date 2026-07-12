<?php
require_once __DIR__ . '/../../admin/donations/db.php';

$e = fn(string $s): string => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');

function ordinal(int $n): string {
    if ($n % 100 >= 11 && $n % 100 <= 13) return $n . 'th';
    switch ($n % 10) {
        case 1: return $n . 'st';
        case 2: return $n . 'nd';
        case 3: return $n . 'rd';
        default: return $n . 'th';
    }
}

try {
    $stats         = get_stats();
    $donations     = get_public_donations();
    $top_donors    = get_top_donors(100);
    $top_countries = get_top_countries(10);
    $monthly_raw   = get_monthly_stats();
} catch (Throwable $t) {
    $stats         = ['all' => ['total' => 0, 'donors' => 0], 'month' => ['total' => 0, 'donors' => 0]];
    $donations     = [];
    $top_donors    = [];
    $top_countries = [];
    $monthly_raw   = [];
}

$month_name = date('F Y');

// Build chart data: [year => [1..12 => total]]
$chart_years = [];
$chart_data  = [];
foreach ($monthly_raw as $row) {
    $y = (int)$row['year'];
    $m = (int)$row['month'];
    if (!isset($chart_data[$y])) {
        $chart_data[$y] = array_fill(1, 12, 0);
        $chart_years[]  = $y;
    }
    $chart_data[$y][$m] = (float)$row['total'];
}
sort($chart_years);
$chart_json = json_encode($chart_data);
$years_json = json_encode($chart_years);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate - Soplos Linux</title>
    <meta name="description" content="Support Soplos Linux development through donations. Every contribution helps keep the project independent and alive.">
    <meta name="keywords" content="soplos, linux, donate, support, patreon, paypal">

    <meta property="og:title" content="Donate - Soplos Linux">
    <meta property="og:description" content="Support Soplos Linux development through donations.">

    <link rel="icon" href="../../images/logo/soplos-logo.png" type="image/png">
    <link rel="stylesheet" href="../../styles/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>

    <style>
        /* Donation method cards */
        .donate-methods-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .donate-card {
            background: var(--card-bg, #1e1e1e);
            border: 1px solid var(--border-color, #2a2a2a);
            border-radius: 12px;
            padding: 2rem 1.75rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 0.75rem;
            transition: border-color 0.2s, transform 0.2s;
        }

        .donate-card:hover {
            border-color: var(--accent-color, #f5a623);
            transform: translateY(-2px);
        }

        .donate-card.inactive {
            opacity: 0.65;
            pointer-events: none;
        }

        .donate-card-icon {
            font-size: 2.4rem;
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .donate-card-icon.paypal  { color: #009cde; }
        .donate-card-icon.patreon { color: #ff424d; }
        .donate-card-icon.stripe  { color: #635bff; }

        .donate-card h3 {
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0;
        }

        .donate-card p {
            font-size: 0.9rem;
            color: var(--text-muted, #aaa);
            margin: 0;
            line-height: 1.5;
        }

        /* Amount selector */
        .amounts-row {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            justify-content: center;
            margin: 0.5rem 0 1rem;
        }

        .amount-btn {
            background: transparent;
            border: 2px solid var(--accent-color, #f5a623);
            color: var(--accent-color, #f5a623);
            border-radius: 8px;
            padding: 0.45rem 1.1rem;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s, color 0.15s;
        }

        .amount-btn:hover {
            background: var(--accent-color, #f5a623);
            color: #000;
        }

        .amount-btn.custom { border-style: dashed; }

        /* Donate CTA button */
        .donate-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #009cde;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 0.65rem 1.5rem;
            font-size: 1rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: opacity 0.15s, transform 0.15s;
            margin-top: 0.5rem;
        }

        .donate-btn:hover { opacity: 0.88; transform: translateY(-1px); }
        .donate-btn.patreon-btn { background: #ff424d; }
        .donate-btn.stripe-btn  { background: #635bff; }

        /* Coming soon badge */
        .badge-soon {
            display: inline-block;
            background: #333;
            color: #aaa;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 0.2rem 0.6rem;
            border-radius: 4px;
            margin-top: 0.25rem;
        }

        /* Intro block */
        .donate-intro {
            max-width: 680px;
            margin: 0 auto 0.5rem;
            text-align: center;
            line-height: 1.7;
            color: var(--text-muted, #ccc);
            font-size: 1rem;
        }

        /* Stats row */
        .donate-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            justify-content: center;
            margin: 1.25rem 0 0.25rem;
        }

        .donate-stat { text-align: center; }

        .donate-stat strong {
            display: block;
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--accent-color, #f5a623);
            line-height: 1.1;
        }

        .donate-stat span {
            font-size: 0.8rem;
            color: var(--text-muted, #aaa);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Donors stats row */
        .donors-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            justify-content: center;
            margin: 1.25rem 0 0.5rem;
        }

        .donors-stat { text-align: center; min-width: 110px; }

        .donors-stat strong {
            display: block;
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--accent-color, #f5a623);
            line-height: 1.1;
        }

        .donors-stat span {
            font-size: 0.78rem;
            color: var(--text-muted, #aaa);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-divider {
            width: 1px;
            background: var(--border-color, #2a2a2a);
            align-self: stretch;
        }

        /* Donations table */
        .donors-table-wrap { overflow-x: auto; margin-top: 1rem; }

        .donors-table { width: 100%; border-collapse: collapse; }

        .donors-table th {
            text-align: left;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-muted, #aaa);
            border-bottom: 1px solid var(--border-color, #2a2a2a);
            padding: 0.5rem 0.75rem;
        }

        .donors-table td {
            padding: 0.55rem 0.75rem;
            border-bottom: 1px solid var(--border-color, #1e1e1e);
            font-size: 0.9rem;
            vertical-align: middle;
        }

        .donors-table tr:last-child td { border-bottom: none; }

        .donors-table .amount-cell {
            font-weight: 700;
            color: var(--accent-color, #f5a623);
            white-space: nowrap;
        }

        .donors-table .flag {
            width: 20px;
            height: 14px;
            vertical-align: middle;
            margin-right: 6px;
        }

        .donors-table .donor-name a { color: inherit; text-decoration: underline; text-underline-offset: 2px; }

        /* Top donors */
        .top-donors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .top-donor-card {
            background: var(--card-bg, #1e1e1e);
            border: 1px solid var(--border-color, #2a2a2a);
            border-radius: 8px;
            padding: 0.85rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }

        .top-donor-card .donor-rank {
            font-size: 0.7rem;
            color: var(--text-muted, #666);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .top-donor-card .donor-name-top { font-weight: 700; font-size: 0.95rem; }
        .top-donor-card .donor-total { font-size: 0.85rem; color: var(--accent-color, #f5a623); font-weight: 600; }
        .top-donor-card .donor-times { font-size: 0.75rem; color: var(--text-muted, #888); }

        .donors-empty {
            text-align: center;
            padding: 2rem;
            color: var(--text-muted, #888);
            font-size: 0.9rem;
        }

        /* Chart */
        .chart-wrap { position: relative; margin-top: 1rem; height: 280px; }

        /* Data tables (countries, top donors) */
        .data-table-wrap { overflow-x: auto; margin-top: 1rem; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            text-align: left;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted, #aaa);
            border-bottom: 2px solid var(--border-color, #2a2a2a);
            padding: 0.5rem 0.75rem;
        }
        .data-table td {
            padding: 0.55rem 0.75rem;
            border-bottom: 1px solid var(--border-color, #1e1e1e);
            font-size: 0.88rem;
            vertical-align: middle;
        }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tbody tr:nth-child(even) td { background: rgba(255,255,255,0.025); }
        .data-table tbody tr:hover td { background: rgba(245,166,35,0.07); }
        .data-table .num-cell { font-weight: 700; color: var(--accent-color, #f5a623); }
        .data-table .rank-cell { color: var(--text-muted, #666); font-size: 0.8rem; width: 3rem; }
        .data-table .times-badge { font-size: 0.75rem; color: var(--text-muted, #888); margin-left: 0.4rem; }
        .data-table a { color: inherit; text-decoration: underline; text-underline-offset: 2px; }
        .nth-tag { font-size: 0.75rem; color: var(--text-muted, #888); margin-left: 0.35rem; }

        .donors-table tbody tr:nth-child(even) td { background: rgba(255,255,255,0.025); }
        .donors-table tbody tr:hover td { background: rgba(245,166,35,0.07); }

        /* Section cards */
        .content-section {
            background: var(--card-bg, #1a1a1a);
            border: 1px solid var(--border-color, #2a2a2a);
            border-radius: 12px;
            padding: 1.75rem 2rem;
            margin-bottom: 1.25rem;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .donate-methods-grid { grid-template-columns: 1fr; }
            .stat-divider { display: none; }
        }
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
                                <li><a href="https://distrowatch.com/table.php?distribution=soplos" id="menuDistroWatch" target="_blank">DistroWatch</a></li>
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

    <div class="breadcrumb-container">
        <div class="container">
            <nav class="breadcrumb">
                <a href="../../"><i class="fas fa-home"></i> <span id="donate-breadcrumb-home">Home</span></a>
                <span class="breadcrumb-separator">›</span>
                <span class="breadcrumb-current" id="donate-breadcrumb">Donate</span>
            </nav>
        </div>
    </div>

    <main>
        <section class="transition-hero">
            <div class="container">
                <div class="hero-content">
                    <h1><i class="fas fa-heart"></i> <span id="donate-hero-title">Support Soplos Linux</span></h1>
                    <p id="donate-hero-desc">Soplos Linux is an independent project with no corporate backing. Your support keeps it alive.</p>
                </div>
            </div>
        </section>

        <section class="contributors-section">
            <div class="container">
                <div class="contributors-page">

                    <!-- Intro -->
                    <section class="content-section">
                        <p class="donate-intro" id="donate-intro-text">
                            Soplos Linux is developed by a single person in their free time, without external funding or sponsorship. Every donation — regardless of the amount — helps cover server costs, hardware, development tools and the time invested in building a better Linux experience for everyone.
                        </p>

                        <div class="donate-stats">
                            <div class="donate-stat">
                                <strong>3</strong>
                                <span id="donate-stat-distros">Distributions</span>
                            </div>
                            <div class="donate-stat">
                                <strong>12+</strong>
                                <span id="donate-stat-apps">Soplos Apps</span>
                            </div>
                            <div class="donate-stat">
                                <strong>8</strong>
                                <span id="donate-stat-langs">Languages</span>
                            </div>
                            <div class="donate-stat">
                                <strong>100%</strong>
                                <span id="donate-stat-free">Free and open source</span>
                            </div>
                        </div>
                    </section>

                    <!-- Chart + stats -->
                    <section class="content-section" style="margin-top: 0;">
                        <div class="gold-section-title">
                            <i class="fas fa-chart-bar"></i>
                            <h2 style="margin: 0;" id="donate-live-title">Donations</h2>
                        </div>

                        <?php if (!empty($chart_years)): ?>
                        <div class="chart-wrap">
                            <canvas id="donationsChart"></canvas>
                        </div>
                        <?php endif; ?>

                        <div class="donors-stats" style="margin-top:1.25rem;">
                            <div class="donors-stat">
                                <strong><?= number_format((float)$stats['month']['total'], 2) ?> EUR</strong>
                                <span id="donate-stat-month">This month (<?= $e($month_name) ?>)</span>
                            </div>
                            <div class="stat-divider"></div>
                            <div class="donors-stat">
                                <strong><?= (int)$stats['month']['donors'] ?></strong>
                                <span id="donate-stat-month-count">Donations this month</span>
                            </div>
                            <div class="stat-divider"></div>
                            <div class="donors-stat">
                                <strong><?= number_format((float)$stats['all']['total'], 2) ?></strong>
                                <span id="donate-stat-total">Total raised (EUR)</span>
                            </div>
                            <div class="stat-divider"></div>
                            <div class="donors-stat">
                                <strong><?= (int)$stats['all']['donors'] ?></strong>
                                <span id="donate-stat-total-donors">Total donors</span>
                            </div>
                        </div>
                    </section>

                    <!-- Recent donations -->
                    <section class="content-section" style="margin-top: 0;">
                        <div class="gold-section-title">
                            <i class="fas fa-list"></i>
                            <h2 style="margin: 0;" id="donate-recent-title">Recent donations</h2>
                        </div>

                        <?php if (empty($donations)): ?>
                            <p class="donors-empty" id="donate-empty">No donations recorded yet. Be the first.</p>
                        <?php else: ?>
                        <div class="donors-table-wrap">
                            <table class="donors-table">
                                <thead>
                                    <tr>
                                        <th id="donate-th-date">Date</th>
                                        <th id="donate-th-name">Donor</th>
                                        <th id="donate-th-amount">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($donations as $row): ?>
                                    <tr>
                                        <td><?= $e($row['donated_at']) ?></td>
                                        <td class="donor-name">
                                            <?php if ($row['country_code']): ?>
                                                <img class="flag"
                                                     src="https://flagcdn.com/w20/<?= $e(strtolower($row['country_code'])) ?>.png"
                                                     alt="<?= $e($row['country_code']) ?>">
                                            <?php endif; ?>
                                            <?php if ($row['link']): ?>
                                                <a href="<?= $e($row['link']) ?>" target="_blank" rel="noopener"><?= $e($row['name']) ?></a>
                                            <?php else: ?>
                                                <?= $e($row['name']) ?>
                                            <?php endif; ?>
                                            <?php if ((int)$row['donation_num'] > 1): ?>
                                                <span class="nth-tag">(<?= ordinal((int)$row['donation_num']) ?> donation)</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="amount-cell">
                                            <?= number_format((float)$row['amount'], 2) ?> <?= $e($row['currency']) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                    </section>

                    <!-- Top countries -->
                    <?php if (!empty($top_countries)): ?>
                    <section class="content-section" style="margin-top: 0;">
                        <div class="gold-section-title">
                            <i class="fas fa-globe"></i>
                            <h2 style="margin: 0;" id="donate-countries-title">Top countries</h2>
                        </div>
                        <div class="data-table-wrap">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th id="donate-th-country">Country</th>
                                        <th id="donate-th-contrib">Contribution</th>
                                        <th id="donate-th-avg">Average</th>
                                        <th id="donate-th-highest">Highest</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($top_countries as $c): ?>
                                    <tr>
                                        <td>
                                            <?php if ($c['country_code']): ?>
                                                <img class="flag donors-table"
                                                     src="https://flagcdn.com/w20/<?= $e(strtolower($c['country_code'])) ?>.png"
                                                     alt="<?= $e($c['country_code']) ?>"
                                                     style="width:20px;height:14px;vertical-align:middle;margin-right:6px;">
                                            <?php endif; ?>
                                            <?= $e(strtoupper($c['country_code'])) ?>
                                        </td>
                                        <td class="num-cell"><?= (int)$c['pct'] ?>%</td>
                                        <td><?= number_format((float)$c['avg_amount'], 2) ?> EUR</td>
                                        <td><?= number_format((float)$c['max_amount'], 2) ?> EUR</td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                    <?php endif; ?>

                    <!-- Top donors -->
                    <?php if (!empty($top_donors)): ?>
                    <section class="content-section" style="margin-top: 0;">
                        <div class="gold-section-title">
                            <i class="fas fa-trophy"></i>
                            <h2 style="margin: 0;" id="donate-top-title">Top donors</h2>
                        </div>
                        <div class="data-table-wrap">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th id="donate-th-donor">Donor</th>
                                        <th id="donate-th-times">Donations</th>
                                        <th id="donate-th-total">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($top_donors as $i => $d): ?>
                                    <tr>
                                        <td class="rank-cell">#<?= $i + 1 ?></td>
                                        <td>
                                            <?php if ($d['country_code']): ?>
                                                <img src="https://flagcdn.com/w20/<?= $e(strtolower($d['country_code'])) ?>.png"
                                                     alt="<?= $e($d['country_code']) ?>"
                                                     style="width:20px;height:14px;vertical-align:middle;margin-right:6px;">
                                            <?php endif; ?>
                                            <?php if ($d['link']): ?>
                                                <a href="<?= $e($d['link']) ?>" target="_blank" rel="noopener"><?= $e($d['name']) ?></a>
                                            <?php else: ?>
                                                <?= $e($d['name']) ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= (int)$d['times'] ?></td>
                                        <td class="num-cell"><?= number_format((float)$d['total'], 2) ?> EUR</td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                    <?php endif; ?>

                    <!-- SlimBook One supporters -->
                    <section class="content-section" style="margin-top: 0;">
                        <div class="gold-section-title">
                            <i class="fas fa-laptop"></i>
                            <h2 style="margin: 0;" id="donate-supporters-title">SlimBook One supporters</h2>
                        </div>
                        <p class="slimbook-desc" id="donate-supporters-desc">People who donated to help purchase the SlimBook One used as the main development machine for Soplos Linux. Thank you.</p>
                        <div class="supporter-chips">
                            <span class="supporter-chip">Usuario anónimo</span>
                            <span class="supporter-chip">Ismael de León</span>
                            <span class="supporter-chip">Javier Dimas</span>
                            <span class="supporter-chip">Carlosrainbow</span>
                            <span class="supporter-chip">Tienda Online</span>
                            <span class="supporter-chip">Baifitoazul</span>
                            <span class="supporter-chip">Xavier Montserrat</span>
                            <span class="supporter-chip">Marc Miralles</span>
                            <span class="supporter-chip">Héctor Gascón</span>
                            <span class="supporter-chip">José Ángel Garcia</span>
                            <span class="supporter-chip">Ricardo Filipe Borges</span>
                            <span class="supporter-chip">Rubén Glez</span>
                            <span class="supporter-chip">Sergio Caballero</span>
                            <span class="supporter-chip">Raúl Burriel</span>
                            <span class="supporter-chip">Henry Alcalay</span>
                            <span class="supporter-chip">Jose Ramón</span>
                            <span class="supporter-chip">Fernando</span>
                            <span class="supporter-chip">René</span>
                        </div>
                    </section>

                    <!-- Donation methods -->
                    <section class="content-section" style="margin-top: 0;">
                        <div class="gold-section-title">
                            <i class="fas fa-hand-holding-heart"></i>
                            <h2 style="margin: 0;" id="donate-methods-title">Ways to donate</h2>
                        </div>

                        <div class="donate-methods-grid">

                            <!-- PayPal -->
                            <div class="donate-card">
                                <div class="donate-card-icon paypal">
                                    <i class="fab fa-paypal"></i>
                                </div>
                                <h3 id="donate-paypal-title">PayPal</h3>
                                <p id="donate-paypal-desc">One-time donation. Fast, secure and no account required.</p>

                                <div class="amounts-row">
                                    <a class="amount-btn" href="https://paypal.me/isubdes/2EUR" target="_blank" rel="noopener">2€</a>
                                    <a class="amount-btn" href="https://paypal.me/isubdes/5EUR" target="_blank" rel="noopener">5€</a>
                                    <a class="amount-btn" href="https://paypal.me/isubdes/10EUR" target="_blank" rel="noopener">10€</a>
                                    <a class="amount-btn" href="https://paypal.me/isubdes/25EUR" target="_blank" rel="noopener">25€</a>
                                    <a class="amount-btn custom" href="https://paypal.me/isubdes" target="_blank" rel="noopener" id="donate-custom-label">Custom</a>
                                </div>

                                <a class="donate-btn" href="https://paypal.me/isubdes" target="_blank" rel="noopener">
                                    <i class="fab fa-paypal"></i>
                                    <span id="donate-paypal-btn">Donate via PayPal</span>
                                </a>
                            </div>

                            <!-- Patreon -->
                            <div class="donate-card inactive">
                                <div class="donate-card-icon patreon">
                                    <i class="fab fa-patreon"></i>
                                </div>
                                <h3 id="donate-patreon-title">Patreon</h3>
                                <p id="donate-patreon-desc">Monthly membership. Get early access to news and a badge on the contributors page.</p>
                                <span class="badge-soon" id="donate-patreon-soon">Coming soon</span>
                            </div>

                            <!-- Stripe -->
                            <div class="donate-card inactive">
                                <div class="donate-card-icon stripe">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <h3 id="donate-stripe-title">Card payment</h3>
                                <p id="donate-stripe-desc">Direct card payment without leaving the site. Powered by Stripe.</p>
                                <span class="badge-soon" id="donate-stripe-soon">Coming soon</span>
                            </div>

                            <!-- Ko-fi -->
                            <div class="donate-card inactive">
                                <div class="donate-card-icon" style="color:#ff5e5b;">
                                    <i class="fas fa-mug-hot"></i>
                                </div>
                                <h3 id="donate-kofi-title">Ko-fi</h3>
                                <p id="donate-kofi-desc">Buy me a coffee. Simple one-time or recurring support with no fees.</p>
                                <span class="badge-soon" id="donate-kofi-soon">Coming soon</span>
                            </div>

                        </div>
                    </section>

                </div>
            </div>
        </section>
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

    <a href="#" class="back-to-top"><i class="fas fa-arrow-up"></i></a>

    <script src="../../js/logger.js"></script>
    <script src="../../js/language-loader.js"></script>
    <script src="../../js/language-switcher.js"></script>
    <script src="../../js/soplos-ui.js"></script>
    <script src="../../js/main.js"></script>

    <?php if (!empty($chart_years)): ?>
    <script>
    (function () {
        var chartData  = <?= $chart_json ?>;
        var chartYears = <?= $years_json ?>;
        var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        var palette = [
            '#f5a623','#e8833a','#d4624e','#bc4e6a','#9a4283','#6e3d8e',
            '#4a3a8e','#3070a0','#2499b0','#1dbfa0','#28c97a','#5ad453'
        ];

        var canvas = document.getElementById('donationsChart');
        if (!canvas) return;

        var datasets = chartYears.map(function (y, i) {
            var values = [];
            for (var m = 1; m <= 12; m++) {
                values.push(chartData[y] ? (chartData[y][m] || 0) : 0);
            }
            return {
                label: String(y),
                data: values,
                backgroundColor: palette[i % palette.length],
                borderRadius: 4,
                borderSkipped: false
            };
        });

        new Chart(canvas, {
            type: 'bar',
            data: { labels: months, datasets: datasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: '#ccc', font: { size: 13 } }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                return ctx.dataset.label + ': ' + ctx.parsed.y.toFixed(2) + ' EUR';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#aaa' },
                        grid:  { color: 'rgba(255,255,255,0.05)' }
                    },
                    y: {
                        ticks: {
                            color: '#aaa',
                            callback: function (v) { return v + ' EUR'; }
                        },
                        grid: { color: 'rgba(255,255,255,0.05)' }
                    }
                }
            }
        });
    })();
    </script>
    <?php endif; ?>
</body>
</html>
