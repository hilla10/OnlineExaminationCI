<a href="<?=base_url('dashboard')?>" class="logo">
    <span class="logo-mini"><img src="<?=base_url()?>assets/dist/img/EPTC_logo.png" class="img-circle" alt="EPTC Logo"></span>
    <span class="logo-lg"><img src="<?=base_url()?>assets/dist/img/EPTC_logo.png" class="img-circle" alt="EPTC Logo"></span>
    <!-- <span class="logo-mini"><b>OES</b></span>
    <span class="logo-lg"><b>O</b>nline <b>E</b>xam <b>S</b>ystem </span> -->
</a>

<nav class="navbar navbar-static-top" role="navigation">
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
    </a>
    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <!-- The user image in the navbar-->
                    
                     <?php if (isset($google_login)) : ?>
                        <?php
                            // Debug output
                            $profile_picture_url = htmlspecialchars($google_login->profile_picture);
                            if (empty($profile_picture_url)) {
                                $profile_picture_url = base_url() . 'assets/dist/img/default-user.png'; // Fallback image URL
                            }
                        ?>
                        <img src="<?= $profile_picture_url ?>" class="user-image" alt="User Image">
                        <?php else :?>
                        <img src="<?=base_url()?>assets/dist/img/usersys-min.png" class="user-image" alt="User Image">
                        <?php endif;?>
                    <!-- hidden-xs hides the username on small devices so only the image appears. -->
                     
                <?php
                // Use user from the `users` table if available; otherwise, use data from `google_login`
                if (isset($user)) {
                    $first_name = explode(' ', trim($user->username))[0];
                    $display_name = isset($user->first_name) ? $user->first_name : $first_name;
                } elseif (isset($google_login)) {
                    $first_name = explode(' ', trim($google_login->username))[0];
                    $display_name = isset($google_login->username) ? $first_name : 'User';
                } else {
                    $display_name = 'Guest';
                }
                ?>

                <span class="hidden-xs">Hi, <?= $display_name ?></span>

                </a>
            <ul class="dropdown-menu">
                <!-- The user image in the menu -->
                <li class="user-header">
                    <?php if (isset($user)) : ?>
                        <img src="<?=base_url()?>assets/dist/img/usersys-min.png" class="img-circle" alt="User Image">
                        <p>
                            <?= htmlspecialchars($user->first_name . ' ' . $user->last_name) ?>
                            <small>Member Since <?= date('M, Y', $user->created_on) ?></small>
                        </p>
                    <?php elseif (isset($google_login)) : ?>
                        <?php
                            // Debug output
                            $profile_picture_url = htmlspecialchars($google_login->profile_picture);
                            if (empty($profile_picture_url)) {
                                $profile_picture_url = base_url() . 'assets/dist/img/default-user.png'; // Fallback image URL
                            }
                        ?>
                        <img src="<?= $profile_picture_url ?>" class="img-circle" alt="User Image">
                        <p>
                            <?= htmlspecialchars($google_login->username) ?>
                            <small>Member Since <?= date('M, Y', strtotime($google_login->created_on)) ?></small>
                        </p>
                    <?php else : ?>
                        <img src="<?=base_url()?>assets/dist/img/usersys-min.png" class="img-circle" alt="User Image">
                        <p>
                            User information not available
                            <small>Member Since N/A</small>
                        </p>
                    <?php endif; ?>
                </li>
                <!-- Menu Body -->
                <li class="user-footer">
                    <div class="pull-left">
                        <?php if (isset($user)) : ?>
                            <a href="<?= base_url() ?>users/edit/<?= $user->id ?>" class="btn btn-warning btn-flat">
                                <?= $this->ion_auth->is_admin() ? "Edit Profile" : "Change Password" ?>
                            </a>
                        <?php elseif (isset($google_login)) : ?>
                            <a href="<?= base_url() ?>users/edit/<?= $google_login->id ?>" class="btn btn-warning btn-flat disabled-link">
                                <?= $this->ion_auth->is_admin() ? "Edit Profile" : "Change Password" ?>
                            </a>
                        <?php else : ?>
                            <a href="#" class="btn btn-warning btn-flat" disabled>
                                Profile Edit Not Available
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="pull-right">
                        <a href="#" id="logout" class="btn btn-danger btn-flat">Logout</a>
                    </div>
                </li>
            </ul>


            </li>
        </ul>
    </div>
</nav>