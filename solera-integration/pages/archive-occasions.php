<?php
get_header();

$brands = DV_rewrite::get_all_brands();
$models = DV_rewrite::get_all_models();

$_GET['merk'] = $brands[ get_query_var('merk') ]; // Normalize brand name
$_GET['model'] = $models[ get_query_var('model') ]; // Normilize model name



// Brand SEO
if( isset($_GET['merk']) ) {
    $SEO = DV_catalog::get_brand_seo( get_query_var('merk'), get_query_var('model') );
}


$filter['merk'] = $_GET['merk'];
$filter['model'] = $_GET['model'];
$filter['brandstof'] = $_GET['brandstof'];
$filter['transmission'] = $_GET['transmission'];

$catalog = new DV_catalog();
$posts = $catalog->get_catalog_list( $filter, $sorting=$_GET['sorting'] );

// Filters
$catalog = new DV_catalog();
//$posts_all = $catalog->get_catalog_list( $filter=array(), $sorting=array() );

// Brand
$merks = $catalog->collect_meta_values( $key="merk", $filters );
sort($merks);
$filters['merk'] = $merks;

if( $_GET['merk'] ) {

    // Model
    $models = $catalog->collect_meta_values( $key="model", $filter=array("merk" => $_GET['merk']) );
    sort($models);
    $filters['model'] = $models;

    // Brandstof
    $filters['brandstof'] = $catalog->collect_meta_values( $key="brandstof", $filter=array("merk" => $_GET['merk']) );

    // Transmission
    $filters['transmission'] = $catalog->collect_meta_values( $key="transmission", $filter=array("merk" => $_GET['merk']) );

}
?>


    <?php
    include( 'template-parts/occasions-filter.php' );
    ?>

    <section class="product-sec verkocht-product aanbod-product aanbod-list2">
        <div class="container-fluid">

            

            <div class="common-wrapper">

                <?php
                if( $SEO ) {
                ?>

                    <h1><?php echo $SEO['H1']; ?></h1>
                    <br/>
                <?php } ?>

                <div class="row">

                <?php foreach( $posts as $post ) { ?>

                    <?php
                    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' )[0]; 
                    $meta = get_post_meta($post->ID, 'api_data', true);

                    if( !isset( $meta['model'] ) ) { continue; }
                    if( !$image ) { continue; }

                    $i++;

                    // Price
                    if( !isset($meta['verkoopprijs_particulier']['prijs']['bedrag']) ) {
                        $price = $meta['verkoopprijs_particulier']['prijs'][0]['bedrag'];
                    } else {
                        $price = $meta['verkoopprijs_particulier']['prijs']['bedrag'];
                    }

                    if( is_array($meta['model']) ) { $meta['model'] = ''; }
                    $title = $meta['merk'].' '.$meta['model'];

                    if( is_array($meta['type']) ) { $meta['type'] = ''; }

                    $lease_payment = get_post_meta( $post->ID, 'lease_monthly_payment', true);
                    ?>

                    <div class="col-xl-4 col-lg-6! pl-2 pr-2 each-product-wrapper">
                        <a href="<?php echo get_post_permalink( $post->ID ); ?>">
                            <div class="each-product">
                                <img src="<?php echo $image; ?>" class="w-100">                            
                                    <div class="product-dtl">
                                    <h4><?php echo $title; ?></h4>
                                    <table>
                                        <tbody><tr>
                                            <td><?php echo $meta['bouwjaar']; ?></td>
                                            <td><?php echo number_format($meta['tellerstand'], 0, '', '.'); ?> km</td>
                                            <td>
                                                <?php 
                                                if( is_array($meta['autotelex']['brandstof']) ) { 
                                                    //print_r($meta['autotelex']['brandstof']);
                                                } else {
                                                    echo $meta['autotelex']['brandstof'];
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody></table>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <h5><?php echo $meta['type']; ?></h5>
                                        </div>
                                        <div class="col-lg-6 text-right">
                                            <h5>
                                                € <?php echo number_format($price, 0, '', '.'); ?>,-   
                                                <?php if( $lease_payment ) { ?>
                                                    <br>
                                                    <span>
                                                        € <?php echo number_format($lease_payment, 0, '', '.'); ?>,- p.m. ?
                                                    </span>
                                                <?php } ?>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                <?php } ?>    

                </div>
            </div>
        </div>
    </section>

    <?php
    if( $SEO ) {
    ?>

        <div class="container-fluid footer-seo">
            <div class="common-wrapper">
                <div class="row">
                    <div class="col-xl-12">
                        <h2><?php echo $SEO['text_header']; ?></h2>
                        <p><?php echo $SEO['text']; ?></p>
                        <br/><br/>
                    </div>
                </div>
            </div>
        </div>

    <?php } ?>

    <script>

        jQuery(document).ready(function() {

            jQuery('select#merk').change(function() {
                
                jQuery('select#model').val('');
                jQuery('select#brandstof').val('');
                jQuery('select#transmission').val('');
                jQuery('select#sorting').val('');

            });

            jQuery('select#model').change(function() {
                
                jQuery('select#brandstof').val('');
                jQuery('select#transmission').val('');
                jQuery('select#sorting').val('');

            });

            jQuery('select#merk, select#model').change(function() {

                var merk = jQuery('select#merk').val();
                var model = jQuery('select#model').val();
                var brandstof = jQuery('select#brandstof').val();
                var transmission = jQuery('select#transmission').val();

                if (merk) {
                    var url = '/occasions/' + merk + '/';
                    if (model) {
                        url += model + '/';
                    }
                    url += '?brandstof=' + (brandstof || '') + '&transmission=' + (transmission || '');
                    if (sorting) {
                        //url += '&sorting=' + sorting;
                    }
                    window.location.href = url;
                }

            });

        });

    </script>

<?php
get_footer();