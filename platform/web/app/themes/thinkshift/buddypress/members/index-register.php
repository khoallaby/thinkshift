<?php
/**
 * BuddyPress - Members Register
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<div id="buddypress-container">

    <?php

    /**
     * Fires at the top of the BuddyPress member registration page template.
     *
     * @since 1.1.0
     */
    do_action( 'bp_before_register_page' ); ?>

    <div class="page" id="register-page">

        <form action="" name="signup_form" id="signup_form" class="standard-form" method="post" enctype="multipart/form-data">

            <?php if ( 'registration-disabled' == bp_get_current_signup_step() ) : ?>

                <div id="template-notices" role="alert" aria-atomic="true">
                    <?php

                    /** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
                    do_action( 'template_notices' ); ?>

                </div>

                <?php

                /**
                 * Fires before the display of the registration disabled message.
                 *
                 * @since 1.5.0
                 */
                do_action( 'bp_before_registration_disabled' ); ?>

                <p><?php _e( 'User registration is currently not allowed.', 'buddypress' ); ?></p>

                <?php

                /**
                 * Fires after the display of the registration disabled message.
                 *
                 * @since 1.5.0
                 */
                do_action( 'bp_after_registration_disabled' ); ?>
            <?php endif; // registration-disabled signup step ?>

            <?php if ( 'request-details' == bp_get_current_signup_step() ) : ?>

                <div id="template-notices" role="alert" aria-atomic="true">
                    <?php

                    /** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
                    do_action( 'template_notices' ); ?>

                </div>

                <p><?php _e( 'Create an account for free.', 'thinkshift' ); ?></p>


                <?php /***** Extra Profile Details ******/ ?>

                <?php if ( bp_is_active( 'xprofile' ) ) : ?>

                    <?php

                    /**
                     * Fires before the display of member registration xprofile fields.
                     *
                     * @since 1.2.4
                     */
                    do_action( 'bp_before_signup_profile_fields' ); ?>

                    <div class="register-section" id="profile-details-section">


                        <?php /* Use the profile field loop to render input fields for the 'base' profile field group */ ?>
                        <?php if ( bp_is_active( 'xprofile' ) ) : if ( bp_has_profile( array( 'profile_group_id' => 1, 'fetch_field_data' => false ) ) ) : while ( bp_profile_groups() ) : bp_the_profile_group(); ?>

                            <?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>

                                <div<?php bp_field_css_class( 'editfield' ); ?>>

                                    <?php
                                    $field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() );
                                    $field_type->edit_field_html();

                                    /**
                                     * Fires before the display of the visibility options for xprofile fields.
                                     *
                                     * @since 1.7.0
                                     */
                                    do_action( 'bp_custom_profile_edit_fields_pre_visibility' );

                                    if ( bp_current_user_can( 'bp_xprofile_change_field_visibility' ) ) : ?>
                                        <p class="field-visibility-settings-toggle" id="field-visibility-settings-toggle-<?php bp_the_profile_field_id() ?>">
                                            <?php
                                            printf(
                                                __( 'This field can be seen by: %s', 'buddypress' ),
                                                '<span class="current-visibility-level">' . bp_get_the_profile_field_visibility_level_label() . '</span>'
                                            );
                                            ?>
                                            <button type="button" class="visibility-toggle-link"><?php _ex( 'Change', 'Change profile field visibility level', 'buddypress' ); ?></button>
                                        </p>

                                        <div class="field-visibility-settings" id="field-visibility-settings-<?php bp_the_profile_field_id() ?>">
                                            <fieldset>
                                                <legend><?php _e( 'Who can see this field?', 'buddypress' ) ?></legend>

                                                <?php bp_profile_visibility_radio_buttons() ?>

                                            </fieldset>
                                            <button type="button" class="field-visibility-settings-close"><?php _e( 'Close', 'buddypress' ) ?></button>

                                        </div>
                                    <?php else : ?>
                                        <p class="field-visibility-settings-notoggle" id="field-visibility-settings-toggle-<?php bp_the_profile_field_id() ?>">
                                            <?php
                                            printf(
                                                __( 'This field can be seen by: %s', 'buddypress' ),
                                                '<span class="current-visibility-level">' . bp_get_the_profile_field_visibility_level_label() . '</span>'
                                            );
                                            ?>
                                        </p>
                                    <?php endif ?>

                                    <?php

                                    /**
                                     * Fires after the display of the visibility options for xprofile fields.
                                     *
                                     * @since 1.1.0
                                     */
                                    do_action( 'bp_custom_profile_edit_fields' ); ?>

                                    <p class="description"><?php bp_the_profile_field_description(); ?></p>

                                </div>

                            <?php endwhile; ?>

                            <input type="hidden" name="signup_profile_field_ids" id="signup_profile_field_ids" value="<?php bp_the_profile_field_ids(); ?>" />

                        <?php endwhile; endif; endif; ?>

                        <?php

                        /**
                         * Fires and displays any extra member registration xprofile fields.
                         *
                         * @since 1.9.0
                         */
                        do_action( 'bp_signup_profile_fields' ); ?>

                    </div><!-- #profile-details-section -->

                    <?php

                    /**
                     * Fires after the display of member registration xprofile fields.
                     *
                     * @since 1.1.0
                     */
                    do_action( 'bp_after_signup_profile_fields' ); ?>

                <?php endif; ?>


                <?php

                /**
                 * Fires before the display of member registration account details fields.
                 *
                 * @since 1.1.0
                 */
                #remove_action( 'bp_before_account_details_fields', 'wordpress_social_login' );
                do_action( 'bp_before_account_details_fields' ); ?>

                <div class="register-section" id="basic-details-section">

                    <?php /***** Basic Account Details ******/ ?>

                    <?php

                    /**
                     * Fires and displays any member registration username errors.
                     *
                     * @since 1.1.0
                     */
                    #do_action( 'bp_signup_username_errors' );

                    do_action( 'bp_signup_first_name_errors' );

                    ?>


                    <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i></span>
                        <input type="text" class="form-control" name="first_name" id=first_name" value="<?php echo isset( $_POST['first_name'] ) ? $_POST['first_name'] : ''; ?>" placeholder="<?php echo __( 'First Name', 'thinkshift' ); ?>" />
                    </div>


                    <?php do_action( 'bp_signup_last_name_errors' ); ?>


                    <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i></span>
                        <input type="text" class="form-control" name="last_name" id=last_name" value="<?php echo isset( $_POST['last_name'] ) ? $_POST['last_name'] : ''; ?>" placeholder="<?php echo __( 'Last Name', 'thinkshift' ); ?>" />
                    </div>





                    <!--<label for="signup_email"><?php _e( 'Email Address', 'buddypress' ); ?></label>-->
                    <?php

                    /**
                     * Fires and displays any member registration email errors.
                     *
                     * @since 1.1.0
                     */
                    do_action( 'bp_signup_email_errors' ); ?>

                    <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-user" aria-hidden="true"></i></span>
                        <input type="email" class="form-control" name="signup_email" id="signup_email" value="<?php bp_signup_email_value(); ?>" <?php bp_form_field_attributes( 'email' ); ?> placeholder="<?php _e( 'Email Address', 'buddypress' ); ?>"/>
                    </div>

                    <!--<label for="signup_password"><?php _e( 'Choose a Password', 'buddypress' ); ?></label>-->
                    <?php

                    /**
                     * Fires and displays any member registration password errors.
                     *
                     * @since 1.1.0
                     */
                    do_action( 'bp_signup_password_errors' ); ?>

                    <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-key" aria-hidden="true"></i></span>
                        <input type="password" name="signup_password" id="signup_password" value="" class="form-control password-entry" <?php bp_form_field_attributes( 'password' ); ?> placeholder="<?php _e( 'Choose a Password', 'buddypress' ); ?>"/>
                        <div id="pass-strength-result"></div>
                    </div>

                    <!--<label for="signup_password_confirm"><?php _e( 'Confirm Password', 'buddypress' ); ?></label>-->
                    <?php

                    /**
                     * Fires and displays any member registration password confirmation errors.
                     *
                     * @since 1.1.0
                     */
                    do_action( 'bp_signup_password_confirm_errors' ); ?>
                    <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-check" aria-hidden="true"></i></span>
                        <input type="password" name="signup_password_confirm" id="signup_password_confirm" value="" class="form-control password-entry-confirm" <?php bp_form_field_attributes( 'password' ); ?> placeholder="<?php _e( 'Confirm Password', 'buddypress' ); ?>"/>
                    </div>

                    <?php

                    /**
                     * Fires and displays any extra member registration details fields.
                     *
                     * @since 1.9.0
                     */
                    do_action( 'bp_account_details_fields' ); ?>

                </div><!-- #basic-details-section -->

                <?php

                /**
                 * Fires after the display of member registration account details fields.
                 *
                 * @since 1.1.0
                 */
                do_action( 'bp_after_account_details_fields' ); ?>

                <?php if ( bp_get_blog_signup_allowed() ) : ?>

                    <?php

                    /**
                     * Fires before the display of member registration blog details fields.
                     *
                     * @since 1.1.0
                     */
                    do_action( 'bp_before_blog_details_fields' ); ?>

                    <?php /***** Blog Creation Details ******/ ?>

                    <div class="register-section" id="blog-details-section">

                        <h2><?php _e( 'Blog Details', 'buddypress' ); ?></h2>

                        <p><label for="signup_with_blog"><input type="checkbox" name="signup_with_blog" id="signup_with_blog" value="1"<?php if ( (int) bp_get_signup_with_blog_value() ) : ?> checked="checked"<?php endif; ?> /> <?php _e( 'Yes, I\'d like to create a new site', 'buddypress' ); ?></label></p>

                        <div id="blog-details"<?php if ( (int) bp_get_signup_with_blog_value() ) : ?>class="show"<?php endif; ?>>

                            <label for="signup_blog_url"><?php _e( 'Blog URL', 'buddypress' ); ?></label>
                            <?php

                            /**
                             * Fires and displays any member registration blog URL errors.
                             *
                             * @since 1.1.0
                             */
                            do_action( 'bp_signup_blog_url_errors' ); ?>

                            <?php if ( is_subdomain_install() ) : ?>
                                http:// <input type="text" name="signup_blog_url" id="signup_blog_url" value="<?php bp_signup_blog_url_value(); ?>" /> .<?php bp_signup_subdomain_base(); ?>
                            <?php else : ?>
                                <?php echo home_url( '/' ); ?> <input type="text" name="signup_blog_url" id="signup_blog_url" value="<?php bp_signup_blog_url_value(); ?>" />
                            <?php endif; ?>

                            <label for="signup_blog_title"><?php _e( 'Site Title', 'buddypress' ); ?></label>
                            <?php

                            /**
                             * Fires and displays any member registration blog title errors.
                             *
                             * @since 1.1.0
                             */
                            do_action( 'bp_signup_blog_title_errors' ); ?>
                            <input type="text" name="signup_blog_title" id="signup_blog_title" value="<?php bp_signup_blog_title_value(); ?>" />

                            <fieldset class="register-site">
                                <legend class="label"><?php _e( 'Privacy: I would like my site to appear in search engines, and in public listings around this network.', 'buddypress' ); ?></legend>
                                <?php

                                /**
                                 * Fires and displays any member registration blog privacy errors.
                                 *
                                 * @since 1.1.0
                                 */
                                do_action( 'bp_signup_blog_privacy_errors' ); ?>

                                <label for="signup_blog_privacy_public"><input type="radio" name="signup_blog_privacy" id="signup_blog_privacy_public" value="public"<?php if ( 'public' == bp_get_signup_blog_privacy_value() || !bp_get_signup_blog_privacy_value() ) : ?> checked="checked"<?php endif; ?> /> <?php _e( 'Yes', 'buddypress' ); ?></label>
                                <label for="signup_blog_privacy_private"><input type="radio" name="signup_blog_privacy" id="signup_blog_privacy_private" value="private"<?php if ( 'private' == bp_get_signup_blog_privacy_value() ) : ?> checked="checked"<?php endif; ?> /> <?php _e( 'No', 'buddypress' ); ?></label>
                            </fieldset>

                            <?php

                            /**
                             * Fires and displays any extra member registration blog details fields.
                             *
                             * @since 1.9.0
                             */
                            do_action( 'bp_blog_details_fields' ); ?>

                        </div>

                    </div><!-- #blog-details-section -->

                    <?php

                    /**
                     * Fires after the display of member registration blog details fields.
                     *
                     * @since 1.1.0
                     */
                    do_action( 'bp_after_blog_details_fields' ); ?>

                <?php endif; ?>

                <?php

                /**
                 * Fires before the display of the registration submit buttons.
                 *
                 * @since 1.1.0
                 */
                do_action( 'bp_before_registration_submit_buttons' ); ?>
                
                <div class="text-center">
                    <input class="btn btn-success btn-submit" type="submit" name="signup_submit" id="signup_submit" value="<?php esc_attr_e( 'Register', 'buddypress' ); ?>" />
                </div>

                <div class="form-line">
                    <div class="title">OR</div>
                </div>
                <div class="form-footer">
                    <?php do_action( 'wordpress_social_login' ); ?>
                    <button type="button" class="btn btn-default btn-sm btn-social __facebook">
                        <div class="info">
                            <i class="icon fa fa-facebook-official" aria-hidden="true"></i>
                            <span class="title">Register w/ Facebook</span>
                        </div>
                    </button>
                </div>

                <?php

                /**
                 * Fires after the display of the registration submit buttons.
                 *
                 * @since 1.1.0
                 */
                do_action( 'bp_after_registration_submit_buttons' ); ?>

                <?php wp_nonce_field( 'bp_new_signup' ); ?>

            <?php endif; // request-details signup step ?>

            <?php if ( 'completed-confirmation' == bp_get_current_signup_step() ) : ?>

                <div id="template-notices" role="alert" aria-atomic="true">
                    <?php

                    /** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
                    do_action( 'template_notices' ); ?>

                </div>

                <?php

                /**
                 * Fires before the display of the registration confirmed messages.
                 *
                 * @since 1.5.0
                 */
                do_action( 'bp_before_registration_confirmed' ); ?>

                <div id="template-notices" role="alert" aria-atomic="true">
                    <?php if ( bp_registration_needs_activation() ) : ?>
                        <p><?php _e( 'You have successfully created your account! To begin using this site you will need to activate your account via the email we have just sent to your address.', 'buddypress' ); ?></p>
                    <?php else : ?>
                        <p><?php _e( 'You have successfully created your account! Please log in using the username and password you have just created.', 'buddypress' ); ?></p>
                    <?php endif; ?>
                </div>

                <?php

                /**
                 * Fires after the display of the registration confirmed messages.
                 *
                 * @since 1.5.0
                 */
                do_action( 'bp_after_registration_confirmed' ); ?>

            <?php endif; // completed-confirmation signup step ?>

            <?php

            /**
             * Fires and displays any custom signup steps.
             *
             * @since 1.1.0
             */
            do_action( 'bp_custom_signup_steps' ); ?>

        </form>

    </div>

    <?php

    /**
     * Fires at the bottom of the BuddyPress member registration page template.
     *
     * @since 1.1.0
     */
    do_action( 'bp_after_register_page' ); ?>

</div><!-- #buddypress -->
