<form method="GET" class="catalog-filter">

    <?php 
    //print_r($_GET); 
    ?>

    <section class="aanbod-filter-sec">
        <div class="container-fluid">
            <div class="common-wrapper">
                <div class="aanbod-filter-wrapper">
                    <div class="row">
                        <div class="col-xl-9">
                            <div class="row justify-content-end">
                                <div class="col-lg-3 col-md-6 col-6 pl-2 pr-1">
                                    <div class="select-box">
                                        <div class="input select">
                                            <select name="merk" class="select-style" id="merk">
                                                <option value="">Merk</option>
                                                <?php foreach( $filters['merk'] as $val ) { ?>
                                                    <option 
                                                        value="<?php echo sanitize_title($val); ?>"
                                                        <?php 
                                                        if( sanitize_title($val) == sanitize_title($_GET['merk']) ) { echo "selected"; } 
                                                        ?>
                                                        >
                                                        <?php echo $val; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>                 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-6 pl-2 pr-1">
                                    <div class="select-box">
                                        <div class="input select">
                                            <select name="model" class="select-style" id="model">
                                                <option value="">Model</option>
                                                <?php foreach( $filters['model'] as $val ) { ?>
                                                    <option 
                                                        value="<?php echo sanitize_title($val); ?>"
                                                        <?php 
                                                        if( sanitize_title($val) == sanitize_title($_GET['model']) ) { echo "selected"; } 
                                                        ?>
                                                        >
                                                        <?php echo $val; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>                 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-6 pl-2 pr-1">
                                    <div class="select-box">
                                        <div class="input select">
                                            <select name="brandstof" class="select-style" id="brandstof">
                                                <option value="">Brandstof</option>
                                                <?php foreach( $filters['brandstof'] as $val ) { ?>
                                                    <option 
                                                        value="<?php echo $val; ?>"
                                                        <?php 
                                                        if( $val == $_GET['brandstof'] ) { echo "selected"; } 
                                                        ?>
                                                        >
                                                        <?php echo $val; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>                 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-6 pl-2 pr-1">
                                    <div class="select-box">
                                        <div class="input select">
                                            <select name="transmission" class="select-style" id="transmission">
                                                <option value="">Transmissie</option>
                                                <?php foreach( $filters['transmission'] as $val ) { ?>
                                                    <option 
                                                        value="<?php echo $val; ?>"
                                                        <?php 
                                                        if( $val == $_GET['transmission'] ) { echo "selected"; } 
                                                        ?>
                                                        >
                                                        <?php echo $val; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>                 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-12 pl-2 pr-1">

                                    <?php
                                    $sorting_options = DV_catalog::get_filter_options();
                                    if( isset($_GET['sorting']) ) { $chosen_sorting = $_GET['sorting']; }
                                    ?>

                                    <div class="select-box">
                                        <div class="input select">
                                            <select name="sorting" class="select-style" id="sorting" style="margin-top: 5px;">
                                                <option value="">Sorteren op</option>
                                                <?php foreach( $sorting_options as $key=>$title ) { ?>
                                                    <option 
                                                        value="<?php echo $key; ?>"
                                                        <?php if( $key == $chosen_sorting ) { echo "selected"; } ?>
                                                        ><?php echo $title; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>                    
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 pl-2 pr-1">
                            <input type="submit" value="ZOEKEN" class="common-btn filter-submit-button" />
                            <!--<button class="common-btn">ZOEKEN</button>-->
                            <a class="text-right reset_btn" href="/occasions/">
                                Reset filter(s)
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</form>