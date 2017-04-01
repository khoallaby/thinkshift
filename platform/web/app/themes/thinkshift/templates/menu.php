    <nav class="navbar navbar-toggleable-sm fixed-top navbar-inverse bg-primary app-navbar">

        <a class="navbar-brand" href="<?php echo esc_url( home_url() ); ?>">
            <h4>ThinkShift's Marketplace</h4>
        </a>


        <div class="collapse navbar-collapse" id="navbarResponsive">
	        <?php
	        if ( has_nav_menu( 'primary_navigation' ) ) :
		        wp_nav_menu( [
			        'theme_location' => 'primary_navigation',
			        'menu_class'     => 'nav navbar-nav mr-auto',
			        'container'      => ''
		        ] );
	        endif;
	        ?>

            <ul id="#js-popoverContent" class="nav navbar-nav float-right mr-0 hidden-sm-down">
                <li class="nav-item ml-2">
                    <button class="btn btn-default navbar-btn navbar-btn-avatar" data-toggle="popover">
                        <img class="rounded-circle" src="assets/img/avatar-dhg.png">
                    </button>
                </li>
            </ul>

            <ul class="nav navbar-nav hidden-xs-up" id="js-popoverContent">
                <li class="nav-item"><a class="nav-link" href="#" data-action="growl">My Account</a></li>
                <li class="nav-item"><a class="nav-link" href="login/index.html">Logout</a></li>
            </ul>
        </div>
    </nav>
