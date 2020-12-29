<?php

/**
 * Plugin Name:     Extended Single Product
 * Plugin URI:      https://www.webite.pl/plugins/extended-single-product
 * Description:     Wtyczka rozszerza stronę pojedyńczego produktu o interaktywny panel.
 * Version:         0.1.0
 * Author:          Tomasz Oszkiel - WEBite.pl
 */

/* ===== DEACTIVATION PLUGIN - START ===== */

function esp_deactivate_plugin(){
    global $wpdb;
    $sql = "
        DROP TABLE IF EXISTS 
            wp_extended_single_product_boxes,
            wp_extended_single_product_panel
        ;";
    $wpdb->query($sql);
}

register_deactivation_hook(__FILE__, 'esp_deactivate_plugin');

/* ===== DEACTIVATION PLUGIN - END ===== */



/* ===== STYLE.CSS - START ===== */

function add_admin_style() {
    wp_enqueue_style('style-admin', plugins_url('style-admin.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'add_admin_style');

/* ===== STYLE.CSS - END ===== */



/* ===== DATABASE - CREATE TABLE BOXES - START ===== */

function esp_create_db_name_boxes(){
    global $wpdb;
    return $wpdb->prefix.'extended_single_product_boxes';
} 

function esp_create_db_table_boxes(){
    global $wpdb;
    $tableName = esp_create_db_name_boxes();
    $charset = $wpdb->get_charset_collate();

    $sql = "
    CREATE TABLE $tableName (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        url varchar(100) NOT NULL,
        title varchar(100) NOT NULL,
        text text NOT NULL,
        PRIMARY KEY  (id)
    ) $charset;";

    $wpdb->query($sql);
} 
esp_create_db_table_boxes();

function esp_create_db_boxes_rows(){
    global $wpdb;
    $tableName = esp_create_db_name_boxes();

    $sql = "
    INSERT INTO $tableName
        ( id, url, title, text) 
    VALUES 
        ('1','Type image url','Type box title','Type box text'),
        ('2','Type image url','Type box title','Type box text'),
        ('3','Type image url','Type box title','Type box text')";

    $wpdb->query($sql);
}
esp_create_db_boxes_rows();

/* ===== DATABASE - CREATE TABLE BOXES - END ===== */



/* ===== DATABASE - CREATE TABLE PANEL - START ===== */

function esp_create_db_name_panel(){
    global $wpdb;
    return $wpdb->prefix.'extended_single_product_panel';
} 

function esp_create_db_table_panel(){
    global $wpdb;
    $tableName = esp_create_db_name_panel();
    $charset = $wpdb->get_charset_collate();

    $sql = "
    CREATE TABLE $tableName (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        title varchar(100) NOT NULL,
        text text NOT NULL,
        PRIMARY KEY  (id)
    ) $charset;";

    $wpdb->query($sql);
} 
esp_create_db_table_panel();

function esp_create_db_panel_rows(){
    global $wpdb;
    $tableName = esp_create_db_name_panel();

    $sql = "
    INSERT INTO $tableName
        ( id, title, text) 
    VALUES 
        ('1', 'Type panel title','Type panel text'),
        ('2', 'Type panel title','Type panel text'),
        ('3', 'Type panel title','Type panel text')";

    $wpdb->query($sql);
}
esp_create_db_panel_rows();

/* ===== DATABASE - CREATE TABLE BOXES - END ===== */



/* ===== ADMIN PAGE - START ===== */

function esp_menu_option_page(){
    global $wpdb;
    ?>

        <div class="container">
            <h2>Extended Single Product</h2>
            <p>Dodaj opcje do strony pojedynczego produktu.</p>

            <h2>Boxy</h2>
            <form action="" method="post">
                <div class="row">

                    <?php 
                    for($i = 1; $i <= 3; $i++){

                        $box = $wpdb->get_row("SELECT * FROM wp_extended_single_product_boxes WHERE id=$i");
                        ?>

                        <div class="col">
                            <h3>Box <?php echo $i; ?></h3>
                            <label>
                                <h4>Image URL</h4>
                                <input class="esp" type="text" name="esp_url_box_<?php echo $i; ?>" value="<?php echo $box->url; ?>" />
                            </label>
                            <label>
                                <h4>Title box</h4>
                                <input class="esp" type="text" name="esp_title_box_<?php echo $i; ?>" value="<?php echo $box->title; ?>"/>
                            </label>
                            <label>
                                <h4>Text box</h4>
                                <textarea class="esp" name="esp_text_box_<?php echo $i; ?>"><?php echo $box->text; ?></textarea>
                            </label>
                        </div>

                    <?php
                    }
                    ?>
                    
                </div>
                <button class="esp" type="submit" name="esp_btn_save_db">Zapisz</button>
            </form>

            <h2>Panel</h2>
            <form action="" method="post">
                <div class="row">

                    <?php 
                    for($i = 1; $i <= 3; $i++){

                        $panel = $wpdb->get_row("SELECT * FROM wp_extended_single_product_panel WHERE id=$i");
                        ?>

                        <div class="col">
                            <h3>Panel <?php echo $i; ?></h3>
                            <label>
                                <h4>Title</h4>
                                <input class="esp" type="text" name="esp_title_panel_<?php echo $i; ?>" value="<?php echo $panel->title; ?>" />
                            </label>
                            <label>
                                <h4>Text</h4>
                                <textarea class="esp" name="esp_text_panel_<?php echo $i; ?>"><?php echo $panel->text; ?></textarea>
                            </label>
                        </div>

                    <?php
                    }
                    ?>
                    
                </div>
                <button class="esp" type="submit" name="esp_btn_save_db_panel">Zapisz</button>
            </form>
        </div>

        <?php
        /* Save data in DB Boxes*/
        function esp_update_data_db_boxes(){
            global $wpdb;

            if(isset($_POST['esp_btn_save_db'])){

                $url = $_POST['esp_url_box_1'];
                $title = $_POST['esp_title_box_1'];
                $text = $_POST['esp_text_box_1'];
            
                $result = $wpdb->update(
                        'wp_extended_single_product_boxes', 
                    array(
                        'url' => $url,
                        'title' => $title,
                        'text' => $text
                    ),
                    array(
                        'ID' => 1
                    )
                );

                $url2 = $_POST['esp_url_box_2'];
                $title2 = $_POST['esp_title_box_2'];
                $text2 = $_POST['esp_text_box_2'];
            
                $result2 = $wpdb->update(
                        'wp_extended_single_product_boxes', 
                    array(
                        'url' => $url2,
                        'title' => $title2,
                        'text' => $text2
                    ),
                    array(
                        'ID' => 2
                    )
                );

                $url3 = $_POST['esp_url_box_3'];
                $title3 = $_POST['esp_title_box_3'];
                $text3 = $_POST['esp_text_box_3'];
            
                $result3 = $wpdb->update(
                        'wp_extended_single_product_boxes', 
                    array(
                        'url' => $url3,
                        'title' => $title3,
                        'text' => $text3
                    ),
                    array(
                        'ID' => 3
                    )
                );
                

                if($result == 1 || $result2 == 1 || $result3 == 1){
                    echo '<script>alert("Zapisano w bazie danych.");</script>';
                } else {
                    echo '<script>alert("Wystąpił błąd. Dane nie zostały zapisane.");</script>';
                }
            }
        }
        esp_update_data_db_boxes(); 


        /* Save data in DB Panel */
        function esp_update_data_db_panel(){
            global $wpdb;

            if(isset($_POST['esp_btn_save_db_panel'])){

                $title = $_POST['esp_title_panel_1'];
                $text = $_POST['esp_text_panel_1'];
            
                $result = $wpdb->update(
                        'wp_extended_single_product_panel', 
                    array(
                        'title' => $title,
                        'text' => $text
                    ),
                    array(
                        'ID' => 1
                    )
                );

                $title2 = $_POST['esp_title_panel_2'];
                $text2 = $_POST['esp_text_panel_2'];
            
                $result2 = $wpdb->update(
                        'wp_extended_single_product_panel', 
                    array(
                        'title' => $title2,
                        'text' => $text2
                    ),
                    array(
                        'ID' => 2
                    )
                );

                $title3 = $_POST['esp_title_panel_3'];
                $text3 = $_POST['esp_text_panel_3'];
            
                $result3 = $wpdb->update(
                        'wp_extended_single_product_panel', 
                    array(
                        'title' => $title3,
                        'text' => $text3
                    ),
                    array(
                        'ID' => 3
                    )
                );
                

                if($result == 1 || $result2 == 1 || $result3 == 1){
                    echo '<script>alert("Zapisano w bazie danych.");</script>';
                } else {
                    echo '<script>alert("Wystąpił błąd. Dane nie zostały zapisane.");</script>';
                }
            }
        }
        esp_update_data_db_panel();  
}

/* ===== ADMIN PAGE - END ===== */




/* ===== ADMIN PANEL - START ===== */

function esp_create_menu(){
    add_submenu_page('edit.php?post_type=product', 'Extended Single Product', 'Extended Single Product', 'manage_options', 'esp-opts', 'esp_menu_option_page');
}
add_action ('admin_menu', 'esp_create_menu');

/* ===== ADMIN PANEL - END ===== */



/* ===== FRONTEND - START ===== */

function esp_add_boxes(){
?>

    <div class="details-product">
        <div class="row row__details-product">
    
            <?php
            function esp_get_data_db(){
                global $wpdb;
                $box_1 = $wpdb->get_row("SELECT * FROM wp_extended_single_product_boxes WHERE id=1");
                $box_2 = $wpdb->get_row("SELECT * FROM wp_extended_single_product_boxes WHERE id=2");
                $box_3 = $wpdb->get_row("SELECT * FROM wp_extended_single_product_boxes WHERE id=3");
                ?>

                    <div class="box box__details-product">
                        <img src="<?php echo $box_1->url; ?>" alt="icon" class="icon icon__details-product" />
                        <h4><?php echo $box_1->title; ?></h4>
                        <p><?php echo $box_1->text; ?></p>
                    </div>
                    <div class="box box__details-product">
                        <img src="<?php echo $box_2->url; ?>" alt="icon" class="icon icon__details-product" />
                        <h4><?php echo $box_2->title; ?></h4>
                        <p><?php echo $box_2->text; ?></p>
                    </div>
                    <div class="box box__details-product">
                        <img src="<?php echo $box_3->url; ?>" alt="icon" class="icon icon__details-product" />
                        <h4><?php echo $box_3->title; ?></h4>
                        <p><?php echo $box_3->text; ?></p>
                    </div>
                    
            
            <?php
            }
            esp_get_data_db();
            ?>
  
        </div>
    </div>

<?php
}
add_action( 'woocommerce_single_product_summary', 'esp_add_boxes', 70 );

/* ===== FRONTEND - END ===== */


function esp_add_panel(){

    function esp_get_data_db_panel(){
        global $wpdb;
        $panel_1 = $wpdb->get_row("SELECT * FROM wp_extended_single_product_panel WHERE id=1");
        $panel_2 = $wpdb->get_row("SELECT * FROM wp_extended_single_product_panel WHERE id=2");
        $panel_3 = $wpdb->get_row("SELECT * FROM wp_extended_single_product_panel WHERE id=3");
        ?>
    
            <div class="row row__singleProduct">
                <button class="button__option" onclick="changeOption(event, 'Ecquo')"><?php echo $panel_1->title; ?></button>
                <button class="button__option" onclick="changeOption(event, 'Ekologia')"><?php echo $panel_2->title; ?></button>
                <button class="button__option" onclick="changeOption(event, 'Szczegóły')"><?php echo $panel_3->title; ?></button>
            </div>

            <div class="row row__singleProduct">
                <div id="Ecquo" class="tabcontent">
                    <h3><?php echo $panel_1->title; ?></h3>
                    <p><?php echo $panel_1->text; ?></p>
                </div>

                <div id="Ekologia" class="tabcontent">
                    <h3><?php echo $panel_2->title; ?></h3>
                    <p><?php echo $panel_2->text; ?></p> 
                </div>

                <div id="Szczegóły" class="tabcontent">
                    <h3><?php echo $panel_3->title; ?></h3>
                    <p><?php echo $panel_3->text; ?></p>
                </div>
            </div>

    <?php
    }
    esp_get_data_db_panel();
}
add_action('woocommerce_after_single_product_summary', 'esp_add_panel' );

