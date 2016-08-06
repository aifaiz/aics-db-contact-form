<?php
$contacted = getAllContactEnquiry();
?>
<div class="wrap">
    <h1>Contact Form Data</h1><hr>
    <table class="widefat">
        <thead>
            <tr>
                <th>Date</th><th>Name</th><th>Phone</th><th>Email</th><th>Enquiry</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($contacted as $c): ?>
            <tr>
                <td><?php echo date('d/m/Y', strtotime($c->created_at)); ?></td>
                <td><?php echo $c->name; ?></td>
                <td><?php echo $c->phone; ?></td>
                <td><?php echo $c->email; ?></td>
                <td>
                    <div><b><?php echo $c->subject; ?></b></div>
                    <div><?php echo $c->enquiry; ?></div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div><!-- wrap -->