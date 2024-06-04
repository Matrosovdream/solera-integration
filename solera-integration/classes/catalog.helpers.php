<?php
class DV_catalog {

    public function get_catalog_list( $filter=array(), $sorting=array() ) {

        // Filter
        $meta_query = array('relation' => 'AND'); // Use AND relation to match all conditions
            
        foreach ($filter as $key => $value) {

            // Skip empty values
            if( trim( $value ) == '' ) { continue; }
            
            $meta_query[] = array(
                'key' => $key,
                'value' => $value,
                'compare' => '=',
            );
        }

        $args = array(
            "post_type" => "occasions",
            "posts_per_page" => 500,
            'meta_query' => $meta_query,
            'orderby' => 'title',
            'order' => 'ASC',
        );

        if( $_GET['sorting'] ) {

            $sort_key = explode('-', $_GET['sorting'])[0];
            $sort_order = explode('-', $_GET['sorting'])[1];

            $args['meta_key'] = $sort_key;
            $args['order'] = $sort_order;
            $args['orderby'] = 'meta_value_num';

        }

        $posts = get_posts( $args);

        return $posts;
    
    }

    // For filters
    public function collect_meta_values( $key, $filters=array() ) {

        if( is_iterable( $filters ) ) {
            $catalog = $this->get_catalog_list( $filters );
        } else {
            $catalog = $this->get_catalog_list();
        }

        $filters = $this->collect_all_filter_values( $catalog );
        return $filters[ $key ];

    }

    public function collect_all_filter_values( $posts ) {

        $filter_values = array();
        foreach( $posts as $post ) {

            // Merk
            $filter_values['merk'][] = get_post_meta( $post->ID, 'merk', true );

            // Model
            $filter_values['model'][] = get_post_meta( $post->ID, 'model', true );

            // Brandstof
            $filter_values['brandstof'][] = get_post_meta( $post->ID, 'brandstof', true );

            // Transmission
            $filter_values['transmission'][] = get_post_meta( $post->ID, 'transmission', true );

        }

        // Take just unique values
        foreach( $filter_values as $key=>$set ) {
            
            // Remove empty values and array values
            foreach( $set as $key_set=>$item ) {
                if( is_array( $item ) || $item == '' ) { unset( $set[$key_set] ); }
            }
            $filter_values[ $key ] = array_unique( $set );
        }

        return $filter_values;

    }

    public static function get_filter_options() {

        $sorting_options = array(
            "price-ASC" => "Prijs oplopend",
            "price-DESC" => "Prijs aflopend",
            "mileage-ASC" => "Km-stand oplopend",
            "mileage-DESC" => "Km-stand aflopend",
            "year-ASC" => "Bouwjaar oplopend",
            "year-DESC" => "Bouwjaar aflopend",

        );

        return $sorting_options;

    }

    public static function get_brand_seo( $brand, $model=false ) {

        // Slugs
        $brand_slug = sanitize_title($brand);
        $model_slug = sanitize_title($model);

        // Titles
        $brand_title = DV_rewrite::get_all_brands()[$brand];
        $model_title = DV_rewrite::get_all_models()[$model];

        // Get post if exists
        $post = DV_catalog::get_brand_post( $brand_slug, $model_slug );

        // Default params
        $default_params = array(
            'title' => get_option('occasion_archive_title'),
            'description' => get_option('occasion_archive_description'),
            'keywords' => get_option('occasion_archive_keywords'),
            'H1' => get_option('occasion_archive_h1'),
            //'text_header' => $brand_title,
            'text' => get_option('occasion_archive_text')
        );

        // If we have a post then we can override default params
        if( $post ) {

            $params = array(
                'title' => get_post_meta( $post->ID, 'seo_title', true ),
                'description' => get_post_meta( $post->ID, 'seo_description', true ),
                'keywords' => get_post_meta( $post->ID, 'seo_keywords', true ),
                'H1' => get_post_meta( $post->ID, 'seo_h1', true ),
                //'text_header' => implode(' ', $seo_fields),
                'text' => $post->post_content
            );

        } else {
            $params = $default_params;
        }

        // if some of the params is empty then we use default
        foreach( $params as $key=>$value ) {
            if( $value == '' ) {
                $params[$key] = $default_params[$key];
            }
        }

        // Replace variables in strings
        $seo_fields = array( "brand" => $brand_title, "model" => $model_title );
        foreach( $params as $key=>$value ) {
            $params[$key] = DV_catalog::prepare_seo_title( $value, $seo_fields );
        }

        return $params;

    }

    public static function get_brand_post( $brand_slug, $model_slug=false ) {

        if( isset($brand_slug) && isset($model_slug) ) {

            $metaquery = array(
                'relation' => 'AND',
                array(
                    'key' => 'brand_name_slug',
                    'value' => $brand_slug,
                    'compare' => '='
                ),
                array(
                    'key' => 'model_name_slug',
                    'value' => $model_slug,
                    'compare' => '='
                )
            ); 

        } else {
                
                $metaquery = array(
                    'relation' => 'AND',
                    array(
                        'key' => 'brand_name_slug',
                        'value' => $brand_slug,
                        'compare' => '='
                    )
                );
        }

        $posts = get_posts(array(
            'post_type' => 'brands',
            'meta_query' => $metaquery
        ));
        $post = $posts[0];

        return $post;

    }    



    public static function prepare_seo_title( $string, $data ) {

        foreach( $data as $slug=>$title ) {
            $string = str_replace( '{'.$slug.'}', $title, $string );
        }

        return $string;

    }

}