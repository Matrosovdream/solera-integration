<?php
get_header();

global $post;

// Gallery, can be up to 100 images
$gallery = get_post_meta($post->ID, 'gallery', true);

// All meta fields
$meta = get_post_meta($post->ID, 'api_data', true);

// Price
if( !isset($meta['verkoopprijs_particulier']['prijs']['bedrag']) ) {
	$price = $meta['verkoopprijs_particulier']['prijs'][0]['bedrag'];
} else {
	$price = $meta['verkoopprijs_particulier']['prijs']['bedrag'];
}

$lease_payment = get_post_meta( $post->ID, 'lease_monthly_payment', true);

// Title
if( is_array($meta['model']) ) { $meta['model'] = ''; }
$title = $meta['merk'].' '.$meta['model'];

if( is_array($meta['type']) ) { $meta['type'] = ''; }
/*
echo "<pre>";
print_r($meta);
echo "</pre>";
*/


?>

	<main>

		<section class="aanbod-dtl-bk-btn">
			<div class="container-fluid">
				<div class="common-wrapper">
					<div class="row no-gutters align-items-center">
						<div class="col-lg-7">
							<h3><?php echo $title; ?></h3>
							<h4><?php echo $meta['type']; ?></h4> 
						</div>
					</div>
				</div>
			</div>
		</section>

		<section class="aanbod-dtl-top-sec">

        	<!-- Swiper -->
			<div class="swiper mySwiper">
				<div class="swiper-wrapper">

					<?php foreach( $gallery as $attachment_id ) { ?>

						<div class="swiper-slide">
							<img src="<?php echo wp_get_attachment_image_src( $attachment_id, 'full' )[0]; ?>" />
						</div>

					<?php } ?>

				</div>
				<div class="swiper-button-next"></div>
				<div class="swiper-button-prev"></div>
			</div>

		</section>

		<section class="dtl-tab-sec">
			<div class="container-fluid">
				<div class="common-wrapper">
					<div class="row no-gutters">
						<div class="col-xl-8">
							<div class="tab-wrapper">
								<div class="tab-sec">
									<ul class="nav nav-tabs" role="tablist">
										<li class="nav-item "> <a class="nav-link active show" data-toggle="tab" href="#home">Kenmerken</a> </li>
										<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#menu1">Opties</a> </li>
										<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#menu2">Omschrijving</a> </li>
										<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#menu4">Financiering / lease</a> </li>
									</ul>
								</div>
								<div class="tab-content">
									<div id="home" class="tab-pane active show">
										<div class="row">
											<div class="col-lg-6 col-md-12 kenmerken-tab">
												<ul>
													<li>Merk</li>
													<li><?php echo $meta['merk']; ?></li>
													<li>Model</li>
													<li><?php echo $meta['model']; ?></li>
													<li>Type</li>
													<li><?php echo $meta['type']; ?></li>
													<li>Aantal deuren</li>
													<li><?php echo $meta['aantal_deuren']; ?></li>
													<li>Aantal zitplaatsen</li>
													<li><?php echo $meta['aantal_zitplaatsen']; ?></li>
													<li>Aantal sleutels</li>
													<li><?php echo $meta['aantal_sleutels']; ?></li>
													<li>Transmissie</li>
													<li><?php echo $meta['transmissie']; ?></li>
													<li>Tellerstand</li>
													<li><?php echo number_format($meta['tellerstand'], 0, '', '.'); ?> km</li>
													<li>Aantal versnellingen</li>
													<li><?php echo $meta['aantal_versnellingen']; ?></li>
													<li>Bouwjaar</li>
													<li><?php echo $meta['bouwjaar']; ?></li>
													<li>Brandstof</li>
													<li>
														<?php 
														if( is_array($meta['autotelex']['brandstof']) ) { 
															//print_r($meta['autotelex']['brandstof']);
														} else {
															echo $meta['autotelex']['brandstof'];
														}
														?>	
													</li>
													<li>Prijs</li>
													<li> € <?php echo number_format($price, 0, '', '.'); ?>,-  </li>
													<li>Kleur</li>
													<li><?php echo $meta['basiskleur']; ?> <?php echo $meta['laksoort']; ?></li>
													<li>Interieurkleur</li>
													<li><?php echo $meta['interieurkleur']; ?></li>
													<li>Acceleratie 0-100</li>
													<li><?php echo $meta['acceleratie']; ?> sec</li>
													<li>Bekleding</li>
													<li><?php echo $meta['bekleding']; ?></li>
													<li>CO2-emissie</li>
													<li> <?php echo $meta['emissieklasse'][0]; ?> g/km </li>
												</ul>
												<div class="clearfix"></div>
											</div>
											<div class="col-lg-6 col-md-12 kenmerken-tab">
												<ul>
													<li>BTW/Marge</li>
													<li><?php echo $meta['btw_marge']; ?></li>
													<li>Aantal cilinders</li>
													<li><?php echo $meta['cilinder_aantal']; ?></li>
													<li>Cilinderinhoud</li>
													<li><?php echo $meta['cilinder_inhoud']; ?></li>
													<li>Vermogen</li>
													<li><?php echo $meta['vermogen_motor_pk']; ?> PK</li>
													<li>Topsnelheid</li>
													<li><?php echo $meta['topsnelheid']; ?> km/h</li>
													<li>Carrosserie</li>
													<li><?php echo $meta['carrosserie']; ?></li>
													<li>Tankinhoud</li>
													<li><?php echo $meta['tankinhoud']; ?> Liter</li>
													<li>Gewicht</li>
													<li><?php echo $meta['massa']; ?> KG</li>
													<li>Onderhoudsboekje aanwezig?</li>
													<li><?php echo $meta['onderhoudsboekjes']; ?></li>
													<li>Energielabel</li>
													<li> <img src="<?php echo $meta['energielabel']; ?>" alt=""> </li>
													<li>Gemiddeld verbruik</li>
													<li><?php echo $meta['gemiddeld_verbruik']; ?> L/100KM</li>
												</ul>
											</div>
										</div>
									</div>
									<div id="menu1" class="tab-pane">
										<div class="row">
											<div class="col-md-12 star_contnt">
												<div class="star_icon">
													<h3>Onderscheidende opties</h3>
													<?php foreach( $meta['zoekaccessoires']['accessoire'] as $val ) { ?>
														<p><?php echo $val; ?></p>
													<?php } ?>
												</div>
											</div>
											<div class="col-md-12 photo_contnt d-none">
												<h3>&nbsp;</h3>
												<ul> </ul>
											</div>

											<?php foreach( $meta['accessoiregroepen']['accessoiregroep'] as $group ) { ?>

												<div class="col-lg-6 col-md-6 bullet-panel">
													<div class="tab_hedaing">
														<h4><?php echo $group['@attributes']['naam']; ?></h4> 
													</div>
													<ul>
														<?php if( isset( $group['accessoire']['description'] ) ) { ?>
															<li><?php echo $group['accessoire']['description']; ?></li>
														<?php } else { ?>
															<?php foreach( $group['accessoire'] as $item ) { ?>
																<li><?php echo $item['description'];; ?></li>
															<?php } ?>
														<?php } ?>
													</ul>
												</div>

											<?php } ?>


										</div>
									</div>
									<div id="menu2" class="tab-pane">
										<p>
											<?php echo $meta['opmerkingen']; ?>
										</p>
									</div>
									<div id="menu4" class="tab-pane">
										<?php echo do_shortcode('[lease_calculator product_id="'.$post->ID.'"]'); ?>
									</div>
									<div id="menu3" class="tab-pane"> </div>
									</div>
							</div>
						</div>
						<div class="col-xl-4 price-block-wrapper">
							<div class="price-block "> 
								<img src="https://nextmovecustomizing.nl/upload/page/40_other_image_16675699072003640349.jpg" border="0" class="w-100" alt="Aanbod Details Top Image">
								<div class="price-dtl">
									<h2>
									€ <?php echo number_format($price, 0, '', '.'); ?>,-  
									</h2>
									<table>
										<tbody>
											<tr>
												<?php if( $lease_payment ) { ?>
													<td>
														<h4>
															<span> Lease deze <?php echo $meta['merk']; ?></span><br>
															€ <?php echo number_format($lease_payment, 0, '', '.'); ?>,-   p.m.				                                											
														</h4> 
													</td>
												<?php } ?>
												<td class="">
													<h4>
														<span>Marge / BTW</span><br>
														<?php echo $meta['btw_marge']; ?>
													</h4> 
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<!--
								<div class="price-btn">
									<div class="row">
										<div class="col-md-6 pr-2">
											<button class="common-btn Mybtn1">Interesse? </button>
										</div>
										<div class="col-md-6 text-right pl-2">
											<button class="common-btn Mybtn2">Auto inruilen? </button>
										</div>
										<div class="col-xl-12">

										</div>
									</div>
								</div>
								-->
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<?php /* ?>
		<section class="aanbod-dtl-gallery-sec">
			<div class="less-gallery-images">
				<div class="row no-gutters">
					<div class="col-sm-4">
						<div class="gallery-img">
							<a data-fancybox="gallery" href="https://nextmovecustomizing.nl/upload/page/40_other_image_16675699072003640349.jpg" class="fancybox">
								<img src="https://nextmovecustomizing.nl/upload/page/40_other_image_16675699072003640349.jpg" border="0" class="w-100" alt="36146615-0.jpg">
							</a>
						</div>
					</div>

				</div>
			</div>
			<div class="more-gallery-images">
				<div class="row no-gutters">
					<div class="col-sm-4">
						<div class="gallery-img">
							<a data-fancybox="gallery" href="https://nextmovecustomizing.nl/upload/page/40_other_image_16675699072003640349.jpg" class="fancybox">
								<img src="https://nextmovecustomizing.nl/upload/page/40_other_image_16675699072003640349.jpg" border="0" class="w-100" alt="36146615-0.jpg">
							</a>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php */ ?>

		<section class="vacature-dtl-top-sec aanbod-dtl-contact-sec">
			<div class="vacature-dtl-top-wrapper">
				<div class="row no-gutters">
					<div class="col-lg-6 vacature-dtl-block">
						<div class="vacature-dtl-top-img"> <img src="https://nextmovecustomizing.nl/upload/page/40_content_image_16675699072096336833.jpg" border="0" class="w-100" alt="Content Image 2"> </div>
					</div>
					<div class="col-lg-6 vacature-dtl-block">
						<div class="vacature-dtl-top-text">
							<h2>“Persoonlijk contact heeft<br>
				onze voorkeur.”</h2>
							<p> De Hoogt 12
								<br> 5175 AX Loon op zand
								<br> <a href="tel:078-2034046">
											Telefoon: 078-2034046</a>
								<br> <a href="mailto:info@next-move.nl">
										E-mail: info@next-move.nl</a> </p>
							<div class="cont-social">
								<ul>
									<li>
										<a href="tel:078-2034046">
											<img src="https://nextmovecustomizing.nl/images/phone-call.png" border="0" class="" alt="phone-call.png">
										</a>
									</li>
									<li>
										<a href="https://wa.me/31649510067" target="_blank">
											<img src="https://nextmovecustomizing.nl/images/whatsapp-c.png" border="0" class="" alt="whatsapp-c.png">
										</a>
									</li>
									<li>
										<a href="https://www.instagram.com/accounts/login/?next=/nextmove_autoonline/" target="_blank">
											<img src="https://nextmovecustomizing.nl/images/instagram.png" border="0" class="" alt="instagram.png"> 
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

	</main>




	<!-- Swiper JS -->
	<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

	<!-- Initialize Swiper -->
	<script>
		var swiper = new Swiper('.mySwiper', {
			slidesPerView: 1.46,
			centeredSlides: true,
			spaceBetween: 0,
			loop: true,
			navigation: {
				nextEl: ".swiper-button-next",
				prevEl: ".swiper-button-prev",
			},
			breakpoints: {
				320: {
				slidesPerView: 1,
				},
				992: {
				slidesPerView: 1.4,
				centeredSlides: true,
				spaceBetween: 0,
				},
				1600: {
				slidesPerView: 1.46,
				centeredSlides: true,
				spaceBetween: 0,
				},
			},
		});

		jQuery(document).ready(function() {

			jQuery('.nav-link').click(function() {

				var href = jQuery(this).attr('href');

				jQuery('.nav-link').removeClass('active').removeClass('show');
				jQuery(this).addClass('active').addClass('show');

				jQuery('.tab-content .tab-pane').removeClass('active').removeClass('show');
				jQuery(href).addClass('active').addClass('show');

				return false;

			});

		});

	</script>


    <style>

        .row {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }

        @media (min-width: 1200px) {

            .col-xl-8 {
                -ms-flex: 0 0 66.666667%;
                flex: 0 0 66.666667%;
                max-width: 66.666667%;
            }

            .col-xl-4 {
                -ms-flex: 0 0 33.333333%;
                flex: 0 0 33.333333%;
                max-width: 33.333333%;
            }

        }

        .nav {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            padding-left: 0;
            margin-bottom: 0;
            list-style: none;
			border-bottom: none!important;
        }

        .dtl-tab-sec .tab-sec .nav-tabs .nav-item {
            padding-right: 25px;
        }

        .dtl-tab-sec .tab-sec .nav-tabs .nav-item {
            border-bottom: none;
            text-align: center;
            padding-right: 75px;
            background-color: transparent;
            font-size: 24px;
            font-family: 'nunito_sansbold';
        }

        .dtl-tab-sec .tab-sec .nav-tabs .nav-link.active {
            opacity: 100%;
            color: #bab744;
        }

        .dtl-tab-sec .tab-sec .nav-tabs .nav-link {
            border-radius: 0;
            border: none;
            padding: 0 0 3px;
            color: #212121;
            opacity: 0.5;
            position: relative;
            background-color: transparent;
        }


    </style>


<?php
get_footer();