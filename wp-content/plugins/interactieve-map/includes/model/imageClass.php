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

    public function getById($id) {
        //Calling wpdb
        global $wpdb;
        //Setting var as an array
        $return_array = array();
        //Database query
        $result_array = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "im_image WHERE fk_checkpoint_id = $id", ARRAY_A );

        foreach ($result_array as $idx => $array) {
            // New object
            $image = new imageClass();

            // Set all info
            $image->setId( $array['image_id'] );
            $image->setImage( $array['image_path'] );

            // Add new object to return array.
            $return_array[] = $image;
        }
        return $return_array;
    }

    public function delete($input_array) {
        global $wpdb;

        $table = 'wp_im_image';
        $where = ['image_id' => $input_array['image_id']];
        $format = ['%d'];

        $wpdb->delete($table, $where, $format);
    }
}