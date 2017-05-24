<?php 
use \ThinkShift\Plugin\Contacts;

#@todo: add nonce 
?>
<div class="wrap">
    <h1><?php echo get_admin_page_title(); ?></h1>

    <form method="post" action="<?php echo admin_url( 'admin.php' ); ?>" enctype="multipart/form-data">
        <input type="hidden" name="action" value="thinkshift-importer" />
        <input type="hidden" name="page" value="thinkshift-importer" />

        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="contacts-import-file">Import Contacts</label></th>
                    <td><input type="file" name="contacts-import-file" id="contacts-import-file"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="tag-categories-import-file">Import Tag Categories</label></th>
                    <td><input type="file" name="contacts-import-file" id="contacts-import-file"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="tags-import-file">Import Tags</label></th>
                    <td><input type="file" name="contacts-import-file" id="contacts-import-file"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="tag-assign-import-file">Import Tag Assignments</label></th>
                    <td><input type="file" name="contacts-import-file" id="contacts-import-file"></td>
                </tr>
            </tbody>
        </table>

        <p class="submit"><input name="submit" id="submit" class="button button-primary" value="Import" type="submit"></p>
    </form>


</div>