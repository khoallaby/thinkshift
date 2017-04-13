<?php

use Roots\Sage\Setup;
use Roots\Sage\Wrapper;

?>


    <div class="wrap">
        <div class="content row">
            <main class="main">
                <?php include Wrapper\template_path(); ?>
            </main><!-- /.main -->
            <?php if ( Setup\display_sidebar() ) : ?>
                <aside class="sidebar">
                    <?php include Wrapper\sidebar_path(); ?>
                </aside><!-- /.sidebar -->
            <?php endif; ?>
        </div><!-- /.content -->
    </div>