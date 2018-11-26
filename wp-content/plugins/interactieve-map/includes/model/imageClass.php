<?php

/**
 *
 * File documentation
 *
 * @author: Ruben Catshoek <rcatshoek@student.scalda.nl>
 * @since: 15-11-2018
 * @version 0.1 15-11-2018
 */
require_once INTERACTIEVE_MAP_PLUGIN_MODEL_DIR . '/checkpointClass.php';

class imageClass
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $image;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    // Get images by foreign key id
    public function getById($id) {
        //Calling wpdb
        global $wpdb;
        //Setting var as an array
        $return_array = array();
        //Database query
        $result_array = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "im_image WHERE fk_checkpoint_id = $id", ARRAY_A );

        // Loop through images
        foreach ($result_array as $idx => $array) {
            // New object
            $image = new imageClass();

            // Set all info
            $image->setId( $array['image_id'] );
            $image->setImage( $array['image_path'] );

            // Add new object to return array.
            $return_array[] = $image;
        }
        // Return array
        return $return_array;
    }

    // Delete function
    public function delete($input_array) {
        // Shows where to remove the uploaded file
        $uploadDirectory = INTERACTIEVE_MAP_PLUGIN_ADMIN_DIR . "/uploaded_images/images/";

        // Get single image to delete
        $image = $input_array['single_image'];

        // Check if not empty image name
        if (!empty($image)) {
            (!unlink($uploadDirectory . $image));
        } else {
            // Echo error
            echo("Er iets fout gegaan met het verwijderen van het bestand");
        }

        // Calling wpdb
        global $wpdb;

        // Setting data into variables
        $table = 'wp_im_image';
        $where = ['image_id' => $input_array['image_id']];
        $format = ['%d'];

        // Delete data
        $wpdb->delete($table, $where, $format);
    }
}