<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Verification Receipt - <?php echo html_escape($cadet['cadet_number']); ?></title>
    <style>
        body{font-family:Arial,sans-serif;color:#18251d;margin:36px}
        .head{display:flex;align-items:center;justify-content:space-between;border-bottom:3px solid #00a63e;padding-bottom:18px}
        .status{color:#00752c;font-weight:700;text-transform:uppercase}
        .profile{display:grid;grid-template-columns:140px 1fr;gap:24px;margin:28px 0}
        .profile img{width:140px;height:175px;object-fit:cover;border:1px solid #ccd7cf}
        table{width:100%;border-collapse:collapse;margin-top:18px}
        th,td{text-align:left;border:1px solid #ccd7cf;padding:10px}
        th{background:#f1f6f3}
        .footer{margin-top:34px;border-top:1px solid #ccd7cf;padding-top:12px;font-size:12px;color:#536158}
        @media print{body{margin:18mm}}
    </style>
</head>
<body onload="window.print()">
    <div class="head">
        <div><strong>Bangladesh Marine Academy Sylhet</strong><br>Certificate Verification System</div>
        <div class="status"><?php echo $cadet['status'] === 'suspended' ? 'Suspended' : 'Verified'; ?></div>
    </div>
    <div class="profile">
        <img src="<?php echo site_url('verify/photo/' . $cadet['uuid']); ?>" alt="">
        <div>
            <h1><?php echo html_escape($cadet['full_name']); ?></h1>
            <p><strong>Cadet Number:</strong> <?php echo html_escape($cadet['cadet_number']); ?></p>
            <p><strong>Department:</strong> <?php echo html_escape($cadet['department_name']); ?></p>
            <p><strong>Date of Birth:</strong> <?php echo html_escape(date('d F Y', strtotime($cadet['date_of_birth']))); ?></p>
            <p><strong>Batch:</strong> <?php echo (int) $cadet['batch_number']; ?> &nbsp; <strong>Session:</strong> <?php echo (int) $cadet['session_start_year']; ?>-<?php echo (int) $cadet['session_end_year']; ?></p>
        </div>
    </div>
    <table>
        <thead><tr><th>Certificate</th><th>Status</th></tr></thead>
        <tbody>
            <?php foreach ($cadet['documents'] as $document): ?>
                <tr><td><?php echo html_escape(
                    $document['type_code'] === 'pre_sea_course_certificate' && $cadet['department_code'] === 'NAUTICAL'
                        ? 'Pre-Sea Marine Nautical Course Certificate'
                        : $document['type_name']
                ); ?></td><td><?php echo ! empty($document['id']) ? 'Verified' : 'Unavailable'; ?></td></tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="footer">Verified <?php echo html_escape(date('d M Y, h:i A', strtotime($verified_at))); ?> · Record reference <?php echo html_escape($cadet['uuid']); ?></div>
</body>
</html>
