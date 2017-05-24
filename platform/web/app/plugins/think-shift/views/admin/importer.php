<?php 
use \ThinkShift\Plugin\Contacts;

#@todo: add nonce 
?>
<div class="wrap">
    <h1><?php echo get_admin_page_title(); ?></h1>

    <form method="post" action="<?php echo admin_url( 'admin.php' ); ?>" enctype="multipart/form-data">
        <input type="hidden" name="action" value="reignite-importer" />
        <input type="hidden" name="page" value="reignite-importer" />

        <h2 class="title">Contacts</h2>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row"><label for="contacts-import-file">File to import</label></th>
                <td><input type="file" name="contacts-import-file" id="contacts-import-file"></td>
            </tr>
            <!--
            <tr>
                <th scope="row"><label for="regions-import-replace">Replace data (@todo)</label></th>
                <td><input type="checkbox" name="regions-import-replace" id="regions-import-replace"></td>
            </tr>
            -->
            </tbody>
        </table>

        <p class="submit"><input name="submit" id="submit" class="button button-primary" value="Import" type="submit"></p>
    </form>


</div>