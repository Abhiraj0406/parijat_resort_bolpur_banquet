<?php
// admin/site_settings.php
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/includes/header.php';

$successMsg = '';
$errorMsg = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $errorMsg = 'Invalid CSRF token.';
    } else {
        $settings = $_POST['settings'] ?? [];
        
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (:key, :val) 
                                  ON DUPLICATE KEY UPDATE setting_value = :val2");
            
            foreach ($settings as $key => $val) {
                $trimmedVal = trim($val);
                $stmt->execute([':key' => $key, ':val' => $trimmedVal, ':val2' => $trimmedVal]);
            }
            
            $pdo->commit();
            $successMsg = "Site settings and operational timings updated successfully.";
        } catch (PDOException $e) {
            $pdo->rollBack();
            $errorMsg = "Database error: " . $e->getMessage();
        }
    }
}

// Fetch current settings
$stmt = $pdo->query("SELECT * FROM site_settings");
$rawSettings = $stmt->fetchAll();
$s = [];
foreach ($rawSettings as $row) {
    $s[$row['setting_key']] = $row['setting_value'];
}

$csrfToken = generate_csrf_token();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">Resort &amp; Operational Settings</h2>
        <p class="text-muted mb-0">Configure contact info, swimming pool timings, and resort properties</p>
    </div>
</div>

<?php if ($errorMsg): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo htmlspecialchars($errorMsg); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<?php if ($successMsg): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?php echo htmlspecialchars($successMsg); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form method="POST" action="site_settings.php">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">
            
            <!-- SECTION: Swimming Pool Timings (Phase 8 Configurable) -->
            <div class="p-3 mb-4 rounded border bg-light">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-water text-primary fs-3 me-2"></i>
                    <div>
                        <h5 class="mb-0 fw-bold text-dark">Swimming Pool Operational Timing (Phase 8)</h5>
                        <small class="text-muted">Client noted possible conflict (9:00 PM vs 9:30 PM). Change and save here at any time to instantly update website displays.</small>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Pool Display Timings <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="settings[swimming_pool_timings]" value="<?php echo htmlspecialchars($s['swimming_pool_timings'] ?? '10:00 AM – 9:30 PM'); ?>" placeholder="e.g. 10:00 AM – 9:30 PM">
                        <small class="text-muted">Currently active on website: <strong><?php echo htmlspecialchars($s['swimming_pool_timings'] ?? '10:00 AM – 9:30 PM'); ?></strong></small>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Quick Presets</label>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-primary btn-sm flex-fill" onclick="document.querySelector('[name=\'settings[swimming_pool_timings]\']').value = '10:00 AM – 9:30 PM';">
                                9:30 PM Close
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm flex-fill" onclick="document.querySelector('[name=\'settings[swimming_pool_timings]\']').value = '10:00 AM – 9:00 PM';">
                                9:00 PM Close
                            </button>
                        </div>
                        <small class="text-muted">Click preset or type custom timings.</small>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Admin Status</label>
                        <span class="badge bg-success-subtle text-success border border-success-subtle d-block p-2 text-center">
                            <i class="bi bi-check-circle me-1"></i> Configurable &amp; Live
                        </span>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Pool Timing / Season Notice (Optional)</label>
                        <input type="text" class="form-control" name="settings[swimming_pool_timing_note]" value="<?php echo htmlspecialchars($s['swimming_pool_timing_note'] ?? 'Operating timings are subject to change during seasonal maintenance or private events.'); ?>" placeholder="Optional advisory note regarding pool timings...">
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Col 1: General & Location Info -->
                <div class="col-md-6 mb-4">
                    <h5 class="mb-3 border-bottom pb-2 fw-bold text-dark"><i class="bi bi-building me-2 text-primary"></i>Resort Information</h5>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Resort Name</label>
                        <input type="text" class="form-control" name="settings[resort_name]" value="<?php echo htmlspecialchars($s['resort_name'] ?? 'Parijat Resort Bolpur'); ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Restaurant Name (Phase 7)</label>
                        <input type="text" class="form-control" name="settings[restaurant_name]" value="<?php echo htmlspecialchars($s['restaurant_name'] ?? "Labanya's Heshel Ghar"); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Physical Address</label>
                        <textarea class="form-control" name="settings[address]" rows="3"><?php echo htmlspecialchars($s['address'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Google Maps Embed Iframe URL</label>
                        <textarea class="form-control" name="settings[google_maps_embed]" rows="3" placeholder="Paste embed code or URL here"><?php echo htmlspecialchars($s['google_maps_embed'] ?? ''); ?></textarea>
                    </div>
                    <!-- Management & Key Personnel (Phase 11) -->
                    <h5 class="mb-3 mt-4 border-bottom pb-2 fw-bold text-dark"><i class="bi bi-people me-2 text-primary"></i>Resort Management Team (Phase 11)</h5>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Proprietor Name</label>
                        <input type="text" class="form-control" name="settings[proprietor_name]" value="<?php echo htmlspecialchars($s['proprietor_name'] ?? 'Mr. Ananda Kumar Saha'); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Finance &amp; Administrative Head</label>
                        <input type="text" class="form-control" name="settings[finance_head_name]" value="<?php echo htmlspecialchars($s['finance_head_name'] ?? 'Mr. Arindam Saha'); ?>">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Manager Name</label>
                            <input type="text" class="form-control" name="settings[manager_name]" value="<?php echo htmlspecialchars($s['manager_name'] ?? 'Mr. Tonmay Guha'); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Manager Direct Phone</label>
                            <input type="text" class="form-control" name="settings[manager_phone]" value="<?php echo htmlspecialchars($s['manager_phone'] ?? '+91 87775 13554'); ?>">
                        </div>
                    </div>
                </div>
                
                <!-- Col 2: Contact & Social -->
                <div class="col-md-6 mb-4">
                    <h5 class="mb-3 border-bottom pb-2 fw-bold text-dark"><i class="bi bi-telephone me-2 text-primary"></i>Contact Details</h5>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Primary WhatsApp Number (Phase 6)</label>
                        <div class="input-group">
                            <span class="input-group-text text-success"><i class="bi bi-whatsapp"></i></span>
                            <input type="text" class="form-control" name="settings[whatsapp_number]" value="<?php echo htmlspecialchars($s['whatsapp_number'] ?? '+91 98309 09875'); ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Official Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control" name="settings[email_address]" value="<?php echo htmlspecialchars($s['email_address'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Primary Phone</label>
                            <input type="text" class="form-control" name="settings[phone_primary]" value="<?php echo htmlspecialchars($s['phone_primary'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Secondary Phone</label>
                            <input type="text" class="form-control" name="settings[phone_secondary]" value="<?php echo htmlspecialchars($s['phone_secondary'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <h5 class="mb-3 mt-4 border-bottom pb-2 fw-bold text-dark"><i class="bi bi-share me-2 text-primary"></i>Social Links</h5>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Facebook URL</label>
                        <input type="url" class="form-control" name="settings[facebook_link]" value="<?php echo htmlspecialchars($s['facebook_link'] ?? ''); ?>">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Instagram URL</label>
                        <input type="url" class="form-control" name="settings[instagram_link]" value="<?php echo htmlspecialchars($s['instagram_link'] ?? ''); ?>">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Twitter/X URL</label>
                        <input type="url" class="form-control" name="settings[twitter_link]" value="<?php echo htmlspecialchars($s['twitter_link'] ?? ''); ?>">
                    </div>
                </div>
            </div>
            
            <div class="mt-3 pt-3 border-top d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">
                    <i class="bi bi-save me-1"></i> Save All Settings
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__.'/includes/footer.php'; ?>
