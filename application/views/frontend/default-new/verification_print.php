<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo html_escape(portal_text('receipt_title', $language_code)); ?> | <?php echo html_escape($official_name); ?></title>
    <style>
        body { font-family: Arial, sans-serif; color: #102033; margin: 40px; }
        .brand { color: <?php echo html_escape($brand_color); ?>; font-weight: 800; }
        .box { border: 1px solid #dbe4ee; padding: 24px; border-radius: 8px; }
        .status { display: inline-block; padding: 8px 12px; border-radius: 999px; background: #e7f8ee; color: #087d35; font-weight: 800; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 24px; }
        td { border-bottom: 1px solid #e5edf5; padding: 10px 0; }
        td:first-child { color: #617083; width: 35%; }
        @media print { button { display: none; } body { margin: 20px; } }
    </style>
</head>
<body>
    <div class="box">
        <div class="brand"><?php echo html_escape($official_name); ?></div>
        <h1><?php echo html_escape(portal_text('receipt_title', $language_code)); ?></h1>
        <span class="status"><?php echo html_escape($certificate['status']); ?></span>
        <table>
            <tr><td><?php echo html_escape(portal_text('certificate_number', $language_code)); ?></td><td><?php echo html_escape($certificate['certificate_number']); ?></td></tr>
            <tr><td><?php echo html_escape(portal_text('student_id', $language_code)); ?></td><td><?php echo html_escape($certificate['student_identifier_snapshot']); ?></td></tr>
            <tr><td><?php echo html_escape(portal_text('student_name', $language_code)); ?></td><td><?php echo html_escape($certificate['student_name_snapshot']); ?></td></tr>
            <tr><td><?php echo html_escape(portal_text('institution', $language_code)); ?></td><td><?php echo html_escape($certificate['institution_name']); ?></td></tr>
            <tr><td><?php echo html_escape(portal_text('program', $language_code)); ?></td><td><?php echo html_escape($certificate['program_name_snapshot'] ?: $certificate['program_name']); ?></td></tr>
            <tr><td><?php echo html_escape(portal_text('certificate_type', $language_code)); ?></td><td><?php echo html_escape($certificate['certificate_type_name']); ?></td></tr>
            <tr><td><?php echo html_escape(portal_text('issue_date', $language_code)); ?></td><td><?php echo html_escape($certificate['issue_date']); ?></td></tr>
            <tr><td><?php echo html_escape(portal_text('expiry_date', $language_code)); ?></td><td><?php echo html_escape($certificate['expiry_date'] ?: portal_text('not_applicable', $language_code)); ?></td></tr>
            <tr><td><?php echo html_escape(portal_text('receipt_generated', $language_code)); ?></td><td><?php echo date('Y-m-d H:i:s'); ?></td></tr>
        </table>
    </div>
    <p><button onclick="window.print()"><?php echo html_escape(portal_text('print', $language_code)); ?></button></p>
</body>
</html>
