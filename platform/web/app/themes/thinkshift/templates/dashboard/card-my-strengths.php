<?php
use ThinkShift\Plugin\Users;

$tags = Users::getUserStrengths();
?>
<div class="card mb-4" id="dashboard-strengths">
  <div class="card-header bg-themec-yellow">
    <i class="icon fa fa-tasks fa-1x"></i> <span>My Strengths</span>
  </div>
  <div class="card-body">
    <div class="section">
          <div class="section-body">
            <div role="tabpanel">
              <!-- Nav tabs -->
              <ul class="nav nav-tabs" role="tablist">
                <!-- <?php
                if( $tags ) {
                    ?>
                    <ul class="list-unstyled list-spaced user-tags">
                        <?php foreach( $tags as $tagId => $tag ) { ?>
                            <li><span class="text-muted icon mr-3"></span> <?= $tag; ?></li>
                        <?php } ?>
                    </ul>
                    <?php
                }
                ?> -->
                <li class="nav-item">
                  <a class="nav-link active" href="#profile" role="tab" data-toggle="tab">Generating Insight</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#buzz" role="tab" data-toggle="tab">Collaborating</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#references" role="tab" data-toggle="tab">Innovating</a>
                </li>
              </ul>
              <!-- Tab panes -->
              <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="profile">
                  <div class="row">
                    <div class="col-lg-12">
                      <p>Aenean ut augue dictum, rutrum risus eget, tristique sapien. Suspendisse accumsan nunc libero, ac vulputate risus vestibulum et. Pellentesque a justo cursus, luctus tortor quis, sollicitudin magna. Cras tempus lorem id scelerisque fringilla. Integer sed pellentesque leo. Ut porttitor blandit risus vel auctor. Nam sed ornare nunc, id congue arcu. Proin euismod lorem sed felis dapibus elementum. Mauris rhoncus luctus convallis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed suscipit metus vitae ex feugiat, et feugiat justo gravida. Sed eget nisl nisl. Sed hendrerit orci eu nulla fermentum elementum.

                      Sed id vulputate nunc. Ut dolor arcu, pharetra nec interdum id, eleifend molestie metus. Etiam eget dictum nibh, eget faucibus eros. Sed venenatis nisl et sem tempus elementum. Ut sit amet mi in nulla tristique ultrices. Fusce sit amet efficitur ipsum. Nam quis nibh ac felis venenatis condimentum id sed nulla. Nullam faucibus quam massa, sit amet tempor arcu pulvinar eget. Aenean libero tortor, elementum et consectetur at, laoreet quis neque. Nullam sit amet euismod sapien, at volutpat lorem.</p>
                    </div>
                  </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="buzz">
                  <p>Aenean ut augue dictum, rutrum risus eget, tristique sapien. Suspendisse accumsan nunc libero, ac vulputate risus vestibulum et. Pellentesque a justo cursus, luctus tortor quis, sollicitudin magna. Cras tempus lorem id scelerisque fringilla. Integer sed pellentesque leo. Ut porttitor blandit risus vel auctor. Nam sed ornare nunc, id congue arcu. Proin euismod lorem sed felis dapibus elementum. Mauris rhoncus luctus convallis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed suscipit metus vitae ex feugiat, et feugiat justo gravida. Sed eget nisl nisl. Sed hendrerit orci eu nulla fermentum elementum.

                  Sed id vulputate nunc. Ut dolor arcu, pharetra nec interdum id, eleifend molestie metus. Etiam eget dictum nibh, eget faucibus eros. Sed venenatis nisl et sem tempus elementum. Ut sit amet mi in nulla tristique ultrices. Fusce sit amet efficitur ipsum. Nam quis nibh ac felis venenatis condimentum id sed nulla. Nullam faucibus quam massa, sit amet tempor arcu pulvinar eget. Aenean libero tortor, elementum et consectetur at, laoreet quis neque. Nullam sit amet euismod sapien, at volutpat lorem.</p>
                </div>
                <div role="tabpanel" class="tab-pane" id="references">
                  <p>Aenean ut augue dictum, rutrum risus eget, tristique sapien. Suspendisse accumsan nunc libero, ac vulputate risus vestibulum et. Pellentesque a justo cursus, luctus tortor quis, sollicitudin magna. Cras tempus lorem id scelerisque fringilla. Integer sed pellentesque leo. Ut porttitor blandit risus vel auctor. Nam sed ornare nunc, id congue arcu. Proin euismod lorem sed felis dapibus elementum. Mauris rhoncus luctus convallis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed suscipit metus vitae ex feugiat, et feugiat justo gravida. Sed eget nisl nisl. Sed hendrerit orci eu nulla fermentum elementum.

                  Sed id vulputate nunc. Ut dolor arcu, pharetra nec interdum id, eleifend molestie metus. Etiam eget dictum nibh, eget faucibus eros. Sed venenatis nisl et sem tempus elementum. Ut sit amet mi in nulla tristique ultrices. Fusce sit amet efficitur ipsum. Nam quis nibh ac felis venenatis condimentum id sed nulla. Nullam faucibus quam massa, sit amet tempor arcu pulvinar eget. Aenean libero tortor, elementum et consectetur at, laoreet quis neque. Nullam sit amet euismod sapien, at volutpat lorem.</p>
                </div>
              </div>
          </div>
        </div>
    </div>
  </div>
</div>
