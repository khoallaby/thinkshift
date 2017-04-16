<div class="form-header">
    <div class="app-brand"><span class="highlight"><?php echo bloginfo( 'title' ); ?></span>
    </div>
</div>

<?php #get_template_part( 'templates/form', 'login' ); ?>
<form action="/" method="POST">
    <div class="input-group">
        <span class="input-group-addon" id="basic-addon1">
            <i class="fa fa-user" aria-hidden="true"></i>
        </span>
        <input type="text" class="form-control" placeholder="Username" aria-describedby="basic-addon1"/>
    </div>
    <div class="input-group">
        <span class="input-group-addon" id="basic-addon2">
            <i class="fa fa-key" aria-hidden="true"></i>
        </span>
        <input type="text" class="form-control" placeholder="Password" aria-describedby="basic-addon2"/>
    </div>
    <div class="text-center">
        <input type="submit" class="btn btn-success btn-submit" value="Login">
    </div>
</form>

<div class="form-line">
    <div class="title">OR</div>
</div>
<div class="form-footer">
    <button type="button" class="btn btn-default btn-sm btn-social __facebook">
        <div class="info">
            <i class="icon fa fa-facebook-official" aria-hidden="true"></i>
            <span class="title">Login with Facebook</span>
        </div>
    </button>
</div>
<?php the_content(); ?>