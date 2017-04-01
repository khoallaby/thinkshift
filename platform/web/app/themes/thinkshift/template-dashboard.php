<?php
/* Template Name: Dashboard */

get_template_part('templates/page', 'header');

?>

<section class="container pt-4">
  <div class="row">
    <div class="col-lg-3">
      <div class="card card-profile mb-4">
        <div class="card-header" style="background-image: url(assets/img/iceland.jpg);"></div>
        <div class="card-block text-center">
          <a href="profile/index.html">
            <img
              class="card-profile-img"
              src="assets/img/avatar-dhg.png">
          </a>

          <h6 class="card-title">
            <a class="text-inherit" href="profile/index.html">Andrew Clarkwest</a>
          </h6>

          <p class="mb-4">I dream of a job I enjoy, being debt free , and empowering others because I value achievement, faith , and self-respect.</p>
        </div>
      </div>

      <div class="card visible-md-block visible-lg-block mb-4">
        <div class="card-block">
          <h6 class="mb-3">My Strength's</h6>
          <ul class="list-unstyled list-spaced">
            <li><span class="text-muted icon icon-calendar mr-3"></span>Innovating</li>
            <li><span class="text-muted icon icon-users mr-3"></span>Generating Insight</li>
            <li><span class="text-muted icon icon-github mr-3"></span>Leading</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="card mb-4 hidden-md-down">
        <div class="card-block">
          <h6 class="mb-3">Career Card: Here is an opportunity suited just for you. <small><a href="#">Explore All Careers</a></small></h6>
          <div data-grid="images" data-target-height="150">
            <img class="media-object" data-width="640" data-height="356" data-action="zoom" src="assets/img/instagram_2.jpg">
          </div>
          <h3>Chief Executive Officer</h3>
          <p>This career suits your strengths of Leadership and Innovating.</p>
          <button class="btn btn-outline-primary btn-sm">Explore This Opportunity</button>
        </div>
      </div>
      <div class="card mb-4 hidden-md-down">
        <div class="card-block">
          <h2 class="mb-3">Congradulations! You have completed your assessments.</h2>
          <div class="row">
            <div class="col-lg-6">
              <h6>Here are a few recommendations:</h6>
              <ul>
                <li><a href="#">Chief Executive Office</a></li>
                <li><a href="#">Public Speaker</a></li>
                <li><a href="#">Team Leader</a></li>
              </ul>
            </div>
            <div class="col-lg-6">
              <h6>Not interested?</h6>
              <button class="btn btn-outline-primary btn-sm">Explore more careers</button>
            </div>
          </div>
        </div>
      </div>
      <div class="card mb-4 hidden-md-down">
        <?php while (have_posts()) : the_post(); ?>
          <?php get_template_part('templates/content', 'page'); ?>
        <?php endwhile; ?>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="alert alert-warning alert-dismissible hidden-md-down" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        Congradulations, you have completed your assessments. <a class="alert-link" href="profile/index.html">Visit Career Cards!</a>
      </div>

      <div class="card mb-4 hidden-md-down">
        <div class="card-block">
          <h6 class="mb-3">Job Bank Video</h6>
          <div data-grid="images" data-target-height="150">
            <img class="media-object" data-width="640" data-height="640" data-action="zoom" src="assets/img/instagram_2.jpg">
          </div>
          <p><strong>CEO Jacob Johnson says</strong> "everyday I get to make decisions people for I don't even know. Imagine the power."</p>
          <button class="btn btn-outline-primary btn-sm">Watch the video</button>
        </div>
      </div>

      <div class="card mb-4 hidden-md-down">
        <div class="card-block">
          <h6 class="mb-3">Your Circle <small>· <a href="#">View the Discussion Guide</a></small></h6>
          <ul class="media-list media-list-stream">
            <li class="media mb-2">
              <img
                class="media-object d-flex align-self-start mr-3"
                src="assets/img/avatar-fat.jpg">
              <div class="media-body">
                <strong>Jacob Thornton</strong>
                <div class="media-body-actions">
                  <button class="btn btn-outline-primary btn-sm">
                    <span class="icon icon-add-user"></span> Send Message</button>
                </div>
              </div>
            </li>
            <li class="media">
              <a class="media-left" href="#">
                <img
                  class="media-object d-flex align-self-start mr-3"
                  src="assets/img/avatar-mdo.png">
              </a>
              <div class="media-body">
                <strong>Mark Otto</strong>
                <div class="media-body-actions">
                  <button class="btn btn-outline-primary btn-sm">
                    <span class="icon icon-add-user"></span> Send Message</button></button>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>

      <div class="card card-link-list">
        <div class="card-block">
          © 2017 ThinkShift
          <a href="#">About</a>
          <a href="#">Help</a>
          <a href="#">Partner with us</a>
          <a href="#">Terms</a>
          <a href="#">Privacy</a>
        </div>
      </div>
    </div>
  </div>
</section>

