<?php
/**
 * pulled from wp_login_form()
 */


$args = [
    'redirect'       => get_bloginfo( 'url' ),
    'label_username' => __( 'Email', 'thinkshift' ),
    'label_password' => __( 'Password', 'thinkshift' ),
    'label_remember' => __( 'Remember Me', 'thinkshift' ),
    'label_log_in'   => __( 'Log In', 'thinkshift' ),
    'remember'       => true
];


#wp_login_form( $args );


$defaults = array(
    'echo' => true,
    // Default 'redirect' value takes the user back to the request URI.
    'redirect' => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
    'form_id' => 'loginform',
    'label_username' => __( 'Username or Email Address' ),
    'label_password' => __( 'Password' ),
    'label_remember' => __( 'Remember Me' ),
    'label_log_in' => __( 'Log In' ),
    'id_username' => 'user_login',
    'id_password' => 'user_pass',
    'id_remember' => 'rememberme',
    'id_submit' => 'wp-submit',
    'remember' => true,
    'value_username' => '',
    // Set 'value_remember' to true to default the "Remember me" checkbox to checked.
    'value_remember' => false,
);
$args = wp_parse_args( $args, apply_filters( 'login_form_defaults', $defaults ) );
$login_form_top = apply_filters( 'login_form_top', '', $args );
$login_form_middle = apply_filters( 'login_form_middle', '', $args );
$login_form_bottom = apply_filters( 'login_form_bottom', '', $args );

?>

<div class="form-header">
    <div class="app-brand"><span class="highlight"><?php bloginfo( 'title' ); ?></span>
    </div>
</div>
<form name="<?php echo $args['form_id']; ?>" id="<?php echo $args['form_id']; ?>" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
    <?php echo $login_form_top; ?>
    <div class="input-group">
        <span class="input-group-addon" id="basic-addon1">
            <i class="fa fa-user" aria-hidden="true"></i>
        </span>
        <input type="text" name="log" id="<?php echo esc_attr( $args['id_username'] ); ?>" class="form-control input" value="<?php echo esc_attr( $args['value_username'] ); ?>" size="20" placeholder="<?php echo esc_attr( $args['label_username'] ); ?>" />

    </div>
    <div class="input-group">
        <span class="input-group-addon" id="basic-addon2">
            <i class="fa fa-key" aria-hidden="true"></i>
        </span>
        <input type="password" name="pwd" id="<?php echo esc_attr( $args['id_password'] ); ?>" class="form-control input" value="" size="20" placeholder="<?php echo esc_attr( $args['label_password'] ); ?>" />
    </div>
    <?php echo $login_form_middle; ?>
    <div class="login-remember">
        <?php if($args['remember']) { ?>
            <p class="login-remember"><label><input name="rememberme" type="checkbox" id="<?php echo esc_attr( $args['id_remember'] ); ?>" value="forever" <?php echo ( $args['value_remember'] ? ' checked="checked"' : '' ); ?> /> <?php echo esc_html( $args['label_remember'] ); ?></label></p>
        <?php } ?>
    </div>
    <div class="text-center">
        <input type="submit" name="wp-submit" id="<?php echo esc_attr( $args['id_submit'] ); ?>" class="btn btn-success btn-submit" value="<?php echo esc_attr( $args['label_log_in'] ); ?>" />
        <input type="hidden" name="redirect_to" value="<?php echo esc_url( $args['redirect'] ); ?>" />
    </div>
    <?php echo $login_form_bottom; ?>
</form>

<div class="form-line">
    <div class="title">OR</div>
</div>
<div class="form-footer">
    <?php do_action( 'wordpress_social_login' ); ?>
    <button type="button" class="btn btn-default btn-sm btn-social __facebook">
        <div class="info">
            <i class="icon fa fa-facebook-official" aria-hidden="true"></i>
            <span class="title">Login with Facebook</span>
        </div>
    </button>
</div>