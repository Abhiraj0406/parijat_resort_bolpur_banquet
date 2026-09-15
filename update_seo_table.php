<?php
require 'includes/config.php';
try {
    $sql = "
    ALTER TABLE seo_settings 
    CHANGE page_url page_name VARCHAR(100) NOT NULL,
    CHANGE title meta_title VARCHAR(200) NOT NULL,
    CHANGE description meta_description TEXT,
    CHANGE keywords meta_keywords TEXT,
    ADD COLUMN canonical_url VARCHAR(255) NULL AFTER meta_keywords,
    ADD COLUMN og_title VARCHAR(200) NULL AFTER canonical_url,
    ADD COLUMN og_description TEXT NULL AFTER og_title,
    ADD COLUMN og_image VARCHAR(255) NULL AFTER og_description;
    ";
    $pdo->exec($sql);
    echo "Table updated!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
