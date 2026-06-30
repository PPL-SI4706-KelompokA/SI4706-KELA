<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1;port=3306", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check foodshare_test (test)
    $pdo->exec("USE foodshare_test");
    
    $donasis = $pdo->query("SELECT id_donasi, nama_makanan, status_donasi, status_verifikasi, updated_at FROM donasis ORDER BY id_donasi ASC")->fetchAll(PDO::FETCH_ASSOC);
    
    echo "TEST DATABASE (foodshare_test) ALL DONATIONS:" . PHP_EOL;
    foreach ($donasis as $d) {
        echo "  - ID: {$d['id_donasi']} | Name: {$d['nama_makanan']} | Status: {$d['status_donasi']} | Verification: {$d['status_verifikasi']} | Updated: {$d['updated_at']}" . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . PHP_EOL;
}
