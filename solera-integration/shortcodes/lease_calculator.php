<?php
add_shortcode("lease_calculator", "lease_calculator_func");
function lease_calculator_func( $atts, $content ) {

    $data = DV_product_helpers::prepare_for_lease_calc( $product_id=$atts['product_id'] );

    $base_url = (new Morgenlease_API)->base_url;
    $url = $base_url."?".http_build_query($data);

    ob_start();
    ?>

        <iframe src="<?php echo $url; ?>" class="calsty"></iframe>

        <style type="text/css">
            .calsty{ 
                border: 0; 
                width: 100%; 
                height: 1100px; 
                background: transparent; 
            }
            @media (max-width: 679.98px) {
                .calsty{ height: 1900px;}
            }
        </style>

    <?php
    return ob_get_clean();

}