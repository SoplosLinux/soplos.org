<?php
require_once __DIR__ . '/config.php';

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO(DB_DSN, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    return $pdo;
}

function get_all_donations(): array {
    return db()->query('SELECT * FROM donations ORDER BY donated_at DESC, id DESC')->fetchAll();
}

function get_public_donations(): array {
    return db()->query(
        'SELECT *,
            ROW_NUMBER() OVER (PARTITION BY name ORDER BY donated_at ASC, id ASC) AS donation_num
         FROM donations WHERE public = 1 ORDER BY donated_at DESC, id DESC'
    )->fetchAll();
}

function get_stats(): array {
    $month_start = date('Y-m-01');
    $month_end   = date('Y-m-t');

    $total = db()->query(
        'SELECT COUNT(*) AS donors, COALESCE(SUM(amount),0) AS total FROM donations WHERE public = 1'
    )->fetch();

    $month = db()->prepare(
        'SELECT COUNT(*) AS donors, COALESCE(SUM(amount),0) AS total
         FROM donations WHERE public = 1 AND donated_at BETWEEN ? AND ?'
    );
    $month->execute([$month_start, $month_end]);

    return ['all' => $total, 'month' => $month->fetch()];
}

function get_top_countries(int $limit = 10): array {
    $stmt = db()->prepare(
        'SELECT country_code,
            COUNT(*) AS donations_count,
            ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM donations WHERE public = 1), 0) AS pct,
            ROUND(AVG(amount), 2) AS avg_amount,
            MAX(amount) AS max_amount
         FROM donations WHERE public = 1 AND country_code IS NOT NULL
         GROUP BY country_code ORDER BY donations_count DESC
         LIMIT ' . (int)$limit
    );
    $stmt->execute([]);
    return $stmt->fetchAll();
}

function get_monthly_stats(): array {
    return db()->query(
        'SELECT YEAR(donated_at) AS year, MONTH(donated_at) AS month,
                COALESCE(SUM(amount), 0) AS total
         FROM donations WHERE public = 1
         GROUP BY YEAR(donated_at), MONTH(donated_at)
         ORDER BY year ASC, month ASC'
    )->fetchAll();
}

function get_top_donors(int $limit = 100): array {
    $stmt = db()->prepare(
        'SELECT name, country_code, link, COUNT(*) AS times, SUM(amount) AS total
         FROM donations WHERE public = 1
         GROUP BY name, country_code, link
         ORDER BY total DESC
         LIMIT ' . (int)$limit
    );
    $stmt->execute([]);
    return $stmt->fetchAll();
}

function insert_donation(array $d): void {
    $stmt = db()->prepare(
        'INSERT INTO donations (name, country_code, amount, currency, donated_at, public, link, notes)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->execute([
        $d['name'], $d['country_code'] ?: null, $d['amount'], $d['currency'],
        $d['donated_at'], (int)$d['public'], $d['link'] ?: null, $d['notes'] ?: null,
    ]);
}

function update_donation(int $id, array $d): void {
    $stmt = db()->prepare(
        'UPDATE donations SET name=?, country_code=?, amount=?, currency=?, donated_at=?, public=?, link=?, notes=?
         WHERE id=?'
    );
    $stmt->execute([
        $d['name'], $d['country_code'] ?: null, $d['amount'], $d['currency'],
        $d['donated_at'], (int)$d['public'], $d['link'] ?: null, $d['notes'] ?: null,
        $id,
    ]);
}

function toggle_public(int $id): void {
    db()->prepare('UPDATE donations SET public = 1 - public WHERE id = ?')->execute([$id]);
}

function delete_donation(int $id): void {
    db()->prepare('DELETE FROM donations WHERE id = ?')->execute([$id]);
}

function get_donation(int $id): ?array {
    $stmt = db()->prepare('SELECT * FROM donations WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row ?: null;
}
