<section class="bma-hero py-5">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-5">
                <div class="mb-3">
                    <span class="bma-status valid">Secure Public Lookup</span>
                </div>
                <h1 class="fw-bold mb-3">Verify a certificate instantly.</h1>
                <p class="text-muted mb-0">Search by certificate number, student ID, student name with filters, or scan the QR code printed on a certificate.</p>
            </div>
            <div class="col-lg-7">
                <div class="bma-panel p-3 p-md-4">
                    <ul class="nav nav-pills bma-tab gap-2 mb-4" id="verificationTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#certificate-number" type="button">Certificate No.</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#student-id" type="button">Student ID</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#student-name" type="button">Student Name</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#scan-qr" type="button">Scan QR</button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="certificate-number">
                            <form method="post" action="<?php echo site_url('verify/certificate'); ?>">
                                <label class="bma-label" for="certificate_number">Certificate number</label>
                                <input class="form-control bma-input text-uppercase" id="certificate_number" name="certificate_number" placeholder="BMA-SYL-2026-0001" required>
                                <div class="row g-3 mt-2 align-items-end">
                                    <div class="col-md-7">
                                        <label class="bma-label" for="captcha_certificate">Verification code</label>
                                        <input class="form-control bma-input" id="captcha_certificate" name="captcha" inputmode="numeric" required>
                                    </div>
                                    <div class="col-md-5">
                                        <img class="border rounded w-100" src="<?php echo site_url('verify/captcha'); ?>?t=<?php echo time(); ?>" alt="Verification code" style="height:46px; object-fit:contain;">
                                    </div>
                                </div>
                                <button class="btn bma-btn w-100 mt-4" type="submit">Verify Certificate</button>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="student-id">
                            <form method="post" action="<?php echo site_url('verify/student-id'); ?>">
                                <label class="bma-label" for="student_id">Student ID</label>
                                <input class="form-control bma-input text-uppercase" id="student_id" name="student_id" placeholder="CADET-2026-001" required>
                                <label class="bma-label mt-3" for="student_id_institution">Institution</label>
                                <select class="form-select bma-input" id="student_id_institution" name="institution_id">
                                    <option value="">Any institution</option>
                                    <?php foreach ($institutions as $institution): ?>
                                        <option value="<?php echo (int) $institution['id']; ?>"><?php echo html_escape($institution['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button class="btn bma-btn w-100 mt-4" type="submit">Verify Student ID</button>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="student-name">
                            <form method="post" action="<?php echo site_url('verify/student-name'); ?>">
                                <label class="bma-label" for="student_name">Student name</label>
                                <input class="form-control bma-input" id="student_name" name="student_name" placeholder="Full or prefix name" required>
                                <div class="row g-3 mt-1">
                                    <div class="col-md-6">
                                        <label class="bma-label" for="name_institution">Institution</label>
                                        <select class="form-select bma-input" id="name_institution" name="institution_id">
                                            <option value="">Select if known</option>
                                            <?php foreach ($institutions as $institution): ?>
                                                <option value="<?php echo (int) $institution['id']; ?>"><?php echo html_escape($institution['name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="bma-label" for="issue_year">Issue year</label>
                                        <input class="form-control bma-input" id="issue_year" name="issue_year" inputmode="numeric" placeholder="2026">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="bma-label" for="certificate_type">Type</label>
                                        <input class="form-control bma-input" id="certificate_type" name="certificate_type" placeholder="Course">
                                    </div>
                                </div>
                                <div class="row g-3 mt-2 align-items-end">
                                    <div class="col-md-7">
                                        <label class="bma-label" for="captcha_name">Verification code</label>
                                        <input class="form-control bma-input" id="captcha_name" name="captcha" inputmode="numeric" required>
                                    </div>
                                    <div class="col-md-5">
                                        <img class="border rounded w-100" src="<?php echo site_url('verify/captcha'); ?>?n=<?php echo time(); ?>" alt="Verification code" style="height:46px; object-fit:contain;">
                                    </div>
                                </div>
                                <button class="btn bma-btn w-100 mt-4" type="submit">Search Name</button>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="scan-qr">
                            <div id="qr-reader" class="bma-qr-box d-flex align-items-center justify-content-center text-muted mb-3">
                                <span>Camera scanner will open here.</span>
                            </div>
                            <button class="btn bma-btn w-100 mb-3" id="start-qr" type="button">Start QR Scanner</button>
                            <form id="qr-token-form" method="get">
                                <label class="bma-label" for="qr_token">Paste QR URL or token</label>
                                <div class="input-group">
                                    <input class="form-control bma-input" id="qr_token" placeholder="https://bma-sylhet.test/verify/qr/...">
                                    <button class="btn bma-outline" type="submit">Open</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-4">
    <div class="container">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="bma-card p-4 h-100">
                    <h2 class="h6 fw-bold">QR tokens are private</h2>
                    <p class="text-muted mb-0">The QR code opens a signed public verification URL and does not expose raw student data.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bma-card p-4 h-100">
                    <h2 class="h6 fw-bold">Name search is protected</h2>
                    <p class="text-muted mb-0">Name lookup requires narrowing details to reduce privacy leakage and bulk scraping.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bma-card p-4 h-100">
                    <h2 class="h6 fw-bold">Every lookup is logged</h2>
                    <p class="text-muted mb-0">Successful, failed, and QR verification attempts are stored for security review.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
<script>
document.getElementById('qr-token-form').addEventListener('submit', function (event) {
    event.preventDefault();
    var value = document.getElementById('qr_token').value.trim();
    if (!value) return;
    var token = value.split('/verify/qr/').pop().trim();
    window.location.href = '<?php echo site_url('verify/qr'); ?>/' + encodeURIComponent(token);
});

document.getElementById('start-qr').addEventListener('click', function () {
    if (typeof Html5Qrcode === 'undefined') {
        document.getElementById('qr-reader').innerHTML = '<span>QR scanner library could not load. Paste the QR URL instead.</span>';
        return;
    }
    var qr = new Html5Qrcode('qr-reader');
    qr.start({ facingMode: 'environment' }, { fps: 10, qrbox: 220 }, function (decodedText) {
        qr.stop();
        var token = decodedText.split('/verify/qr/').pop().trim();
        window.location.href = '<?php echo site_url('verify/qr'); ?>/' + encodeURIComponent(token);
    });
});
</script>
