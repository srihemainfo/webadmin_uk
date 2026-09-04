


<div class="sticky">

	<div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>

	<div class="app-sidebar">

		<div class="side-header">

			<a class="header-brand1" href="<?= $adminurl; ?>">

				<img src="<?= $adminurl; ?>assets/images/brand/Go-Ride-fav-icon.webp"  class="header-brand-img light-logo mx-auto" alt="logo">

				<img src="<?= $adminurl; ?>assets/images/brand/go_ride_logo.png" style="width:66%;" class="header-brand-img light-logo1 mx-auto" alt="logo">

			</a>

			<!-- LOGO -->

		</div>







		<div class="main-sidemenu">

			<div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">

					<path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />

				</svg></div>





			<!-- Side Menu Start -->







			<ul class="side-menu">

<!--The search have lot off issues found so its disable 7-10-2023 -->


	<!--<li class="slide">-->
	<!--							<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0)">-->
 <!--               <form action="" method="post" id="search_menu">-->
 <!--                  <div class="input-group">-->
 <!--                      <div class="form-outline">-->
 <!--                          <input type="search" id="overall_input" placeholder="Search for results..." class="form-control" required name="overall_input" style="border-radius:0px;" />-->
 <!--                          </div>-->
 <!--                       <button type="submit" class="btn btn-primary" style="background: #1170e4 !important;border-color: #1170e4 !important; position: absolute;right: -13px;">-->
 <!--                           <i class="fa fa-search"></i>-->
 <!--                       </button>-->
 <!--                   </div>-->
 <!--               </form></a>-->
                
 <!--               </li>-->
                
                
                   
	
				<?php
			
				// $filter=$_POST['overall_input'];
				
    //             if($filter != ""){
                    
                    // The issues found 7-10-2023 !
                    // $allMain=select_query($con, "orm_menu", "", "`id`!='' and `status`='0' and `name` LIKE '%$filter%' order by `id` ASC", "", "");
                
                    
                // }else{
                //   $allMain = select_query($con, "orm_menu", "", "`main`='0' and `status`='0' order by `order` ASC", "", "");
                 
                // }


				$allMain = select_query($con, "orm_menu", "", "`main`='0' and `public` = '0' and `status`='0' order by `order` ASC", "", "");

				if ($allMain['nr'] > 0) {

					foreach ($allMain['result'] as $key => $value) {



						$checkMenuper = select_query($con, "menu_permission", "", "`userid`='$_SESSION[memid]' and `menu`='$value[id]' ORDER BY `id` DESC LIMIT 1", "", "");

						if (($checkMenuper['nr'] > 0) || ($value['public'] == "1")) {





							$getfChild = select_query($con, "orm_menu", "", "`main`='$value[id]' and `status`='0' order by `name` ASC", "", "");





							if ($subid == $value['tabid']) {

								$active = "active";
							} else {

								$active = "";
							}



							if ($getfChild['nr'] > 0) {

								$href = "javascript:void(0)";
							} else {

								$href = $adminurl . strtolower($value['tabid']) . "/" . strtolower($value['subid']);
							}

				?>





							<li class="slide">

								<a class="side-menu__item" data-bs-toggle="slide" id="<?= 'main' . $value['id'] ?>" onclick="openSideMenu($(this).attr('id'))" href="<?= $href; ?>">

									<i class="side-menu__icon <?= $value['image']; ?>"></i>

									<span class="side-menu__label"><?= $value['name']; ?></span>



									<?php if ($getfChild['nr'] > 0) { ?>

										<i class="angle fe fe-chevron-right"></i>

									<?php } ?>

								</a>







								<?php if ($getfChild['nr'] > 0) { ?>

									<ul class="slide-menu">







										<?php

										foreach ($getfChild['result'] as $key => $value) {





											$checkMenufper = select_query($con, "menu_permission", "", "`userid`='$_SESSION[memid]' and `menu`='$value[id]' ORDER BY `id` DESC LIMIT 1", "", "");

											$getsChild = select_query($con, "orm_menu", "", "`main`='$value[id]' and `status`='0' order by `name` ASC", "", "");





											if ($getsChild['nr'] > 0) {

												$href = "javascript:void(0)";
											} else {

												$href = $adminurl . strtolower($value['tabid']) . "/" . strtolower($value['subid']);
											}



											if (($checkMenufper['nr'] > 0) || ($value['public'] == "1")) {



										?>







												<?php if ($getsChild['nr'] > 0) { ?>

													<li class="slide">

														<a class="side-menu__item slide-item" data-bs-toggle="slide" id="<?= 'children' . $value['id'] ?>" href="<?= $href; ?>">





															<span class="side-menu__label"><?= $value['name']; ?></span>



															<!-- <i class="angle fe fe-chevron-right"></i> -->





															<?php if ($getsChild['nr'] > 0) { ?>

																<i class="angle fe fe-chevron-right"></i>

															<?php } ?>

														</a>



														<?php if ($getsChild['nr'] > 0) {







														?>

															<ul class="slide-menu">



																<?php foreach ($getsChild['result'] as $key => $value) {





																	$checkMenusper = select_query($con, "menu_permission", "", "`userid`='$_SESSION[memid]' and `menu`='$value[id]' ORDER BY `id` DESC LIMIT 1", "", "");

																	$href = $adminurl . strtolower($value['tabid']) . "/" . strtolower($value['subid']);

																	if (($checkMenusper['nr'] > 0) || ($value['public'] == "1")) {

																?>



																		<li>

																			<?php if ($checkMenusper > 0) { ?>
																				<a href="<?= $href; ?>" class="slide-item" id="<?= 'child' . $value['id'] ?>"> <?= $value['name']; ?></a>
																			<?php } else { ?>
																				<a href="<?= $href; ?>" class="slide-item" id="<?= 'child' . $value['id'] ?>"> <?= $value['name']; ?></a>
																			<?php } ?>

																		</li>

																<?php
																	}
																}
																?>



															</ul>

														<?php } ?>

													</li>
												<?php } else { ?>

													<li><a href="<?= $href; ?>" class="slide-item" id="<?= 'children' . $value['id'] ?>"> <?= $value['name']; ?></a></li>

												<?php } ?>











										<?php



											}
										}



										?>

									</ul>
								<?php } ?>
							</li>
				<?php
						}
					}
				}

				?>











			</ul>







			<!-- Side Menu End  -->















			<div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">

					<path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />

				</svg></div>

		</div>

	</div>



</div>