<?php
$bootstrap = get_option('aics_contact_form_bootstrap');
?>
<div class="wrap">
    <?php if(isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div class="updated"><p>Settings updated.</p></div>
    <?php endif; ?>
    <h1>Contact us form setting</h1><hr>
    <form action="admin-post.php" method="POST">
        <input type="hidden" name="action" value="process_save_setting_form_contact">
        <input type="hidden" name="save_aics_contact_form_setting" value="1">
        <table class="table-form">
            <tr>
                <td>Enable bootstrap?</td>
                <td>
                    <select name="enable_bootstrap">
                        <option value="1" <?php echo $bootstrap == 1 ? 'selected="selected"' : '';  ?>>Enable</option>
                        <option value="0" <?php echo $bootstrap == 0 ? 'selected="selected"' : '';  ?>>Disable</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <p><i>*only disable if you are certain that your theme already had bootstrap stylesheet loaded. or your contact layout become weird</i></p>
                </td>
            </tr>
            <tr>
                <td><input type="submit" class="button button-primary" value="Save Setting"></td>
            </tr>
        </table>
    </form>
</div><!-- wra p-->