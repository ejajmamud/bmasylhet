<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verification Receipt | <?php echo html_escape($official_name); ?></title>
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
        <h1>Certificate Verification Receipt</h1>
        <span class="status"><?php echo html_escape($certificate['status']); ?></span>
        <table>
            <tr><td>Certificate number</td><td><?php echo html_escape($certificate['certificate_number']); ?></td></tr>
            <tr><td>Student ID</td><td><?php echo html_escape($certificate['student_identifier_snapshot']); ?></td></tr>
            <tr><td>Student name</td><td><?php echo html_escape($certificate['student_name_snapshot']); ?></td></tr>
            <tr><td>Institution</td><td><?php echo html_escape($certificate['institution_name']); ?></td></tr>
            <tr><td>Program</td><td><?php echo html_escape($certificate['program_name_snapshot'] ?: $certificate['program_name']); ?></td></tr>
            <tr><td>Certificate type</td><td><?php echo html_escape($certificate['certificate_type_name']); ?></td></tr>
            <tr><td>Issue date</td><td><?php echo html_escape($certificate['issue_date']); ?></td></tr>
            <tr><td>Expiry date</td><td><?php echo html_escape($certificate['expiry_date'] ?: 'Not applicable'); ?></td></tr>
            <tr><td>Receipt generated</td><td><?php echo date('Y-m-d H:i:s'); ?></td></tr>
        </table>
    </div>
    <p><button onclick="window.print()">Print</button></p>
</body>
</html>
