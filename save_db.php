<?php


function esp_save_date_db(){
    global $wpdb;

    if(isset($_POST['esp_btn_save_db'])){
        
        $table = $_POST['esp_table_name'];
        
        $data = array(
            'url' => $_POST['esp_img_url_box_1'],
            'title' => $_POST['esp_title_box_1'],
            'text' => $_POST['esp_text_box_1']
        );

        $result = $wpdb->insert($table, $data, $format=NULL);

        if($result == 1){
            echo '<script>alert("Zapisano w bazie danych.");</script>';
        } else {
            echo '<script>alert("Wystąpił błąd. Dane nie zostały zapisane.");</script>';
        }
    }
}

esp_save_date_db();