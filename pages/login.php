


<div class="login-img">

    <!--<div id="global-loader">-->

    <!--    <img src="assets/images/loader.svg" class="loader-img" alt="Loader">-->

    <!--</div>-->

    <div class="page">

        <div class="">



            <div class="col col-login mx-auto mt-7">

                <div class="text-center">

                    <img src="assets/images/brand/logo-white.png" class="header-brand-img" alt="">

                </div>

            </div>



            <div class="container-login100">

                <div class="wrap-login100 p-6">

                    <form class="login100-form validate-form" method="post">

                        <span class="login100-form-title pb-5">

                            Login

                        </span>

                        <div class="panel panel-primary">

                            <div class="tab-menu-heading">

                                <div class="tabs-menu1">



                                </div>

                            </div>

                            <div class="panel-body tabs-menu-body p-0 pt-5">

                                <div class="tab-content">

                                    <div class="tab-pane active" id="tab5">

                                        <div class="wrap-input100 validate-input input-group" data-bs-validate="Valid email is required: ex@abc.xyz">

                                            <a href="javascript:void(0)" class="input-group-text bg-white text-muted">

                                                <i class="zmdi zmdi-email text-muted" aria-hidden="true"></i>

                                            </a>

                                            <input class="input100 border-start-0 form-control ms-0" type="text" name="user" placeholder="Username" required="required">

                                        </div>

                                        <div class="wrap-input100 validate-input input-group" id="Password-toggle">

                                            <a href="javascript:void(0)" class="input-group-text bg-white text-muted">

                                                <!-- <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i> -->
                                                <i toggle="password" class="fa fa-eye field-icon toggle-password"></i>

                                            </a>

                                            <input class="input100 border-start-0 form-control ms-0" id="enpass" name="pass" type="password" placeholder="Password" required="required">

                                        </div>

                                        <div class="text-end pt-4">

                                            <p class="mb-0"><a href="<?= $adminurl; ?>forgot" class="text-primary ms-1">Forgot Password?</a></p>

                                        </div>

                                        <div class="container-login100-form-btn">

                                            <button type="submit" class="login100-form-btn btn-primary" name="log_sub">Login</button>

                                            <!-- <a href="<?php echo $adminurl; ?>" class="login100-form-btn btn-primary">

                                                        Login

                                                </a>-->

                                        </div>

                                        <!--                 <div class="text-center pt-3">

                                                <p class="text-dark mb-0">Not a member?<a href="register" class="text-primary ms-1">Sign UP</a></p>

                                            </div>-->



                                    </div>



                                </div>

                            </div>

                        </div>



                    </form>

                </div>

            </div>

            <!-- CONTAINER CLOSED -->

        </div>

    </div>

    <!-- End PAGE -->



</div>


<script>
    $(".toggle-password").click(function() {

        $(this).toggleClass("fa-eye fa-eye-slash");



        if ($('#enpass').attr('type') === 'text') {

            $('#enpass').attr("type", "password");
        } else if ($('#enpass').attr('type') === 'password') {

            $('#enpass').attr("type", "text");
        }



    });
</script>