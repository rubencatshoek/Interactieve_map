<?php

/**
 *
 * File documentation
 *
 * @author: Ruben Catshoek <rcatshoek@student.scalda.nl>
 * @since: 15-11-2018
 * @version 0.1 15-11-2018
 */
require_once INTERACTIEVE_MAP_PLUGIN_MODEL_DIR . '/imageClass.php';

class checkpointClass
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $icon;

    /**
     * @var imageClass
     */
    private $imageClass;

    public function __construct()
    {
        $this->imageClass = new imageClass();
    }

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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    public function getList() {
        global $wpdb;
        $return_array = array();
        $result_array = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "im_checkpoint ORDER BY checkpoint_id", ARRAY_A );
        // For all database results:
        foreach ( $result_array as $idx => $array ) {
            // New object
            $checkpoint = new checkpointClass();
            // Set all info
            $checkpoint->setId( $array['checkpoint_id'] );
            $checkpoint->setTitle( $array['title'] );
            $checkpoint->setDescription( $array['description'] );
            $checkpoint->setIcon( $array['icon_path'] );
            // Add new object toe return array.
            $return_array[] = $checkpoint;
        }

        return $return_array;
    }

//Insert into database
    public function create($input_array) {
        //Exception handeling
        try {
            //Calling $wpdb
            global $wpdb;

            // Insert query
            $wpdb->insert(
                $wpdb->prefix . 'im_checkpoint',
                array(
                    'title' => $input_array['title'],
                    'description' => $input_array['description'],
                    'icon_path' => $input_array['icon']
                ),
                array(
                    '%s',
                    '%s',
                    '%s'
                )
            );

            $getLastId = $wpdb->insert_id;

            // Insert query
            $wpdb->insert(
                $wpdb->prefix . 'im_image',
                array(
                    'fk_checkpoint_id' => $getLastId ,
                    'image_path' => $input_array['image']
                ),
                array(
                    '%d',
                    '%s'
                )
            );


            // Error ? It's in there:
            if ( ! empty( $wpdb->last_error ) ) {
                $this->last_error = $wpdb->last_error;

                return false;
            }

        } //If there are errors catch them and echo them
        catch ( Exception $exc ) {
            echo '<pre>' . $exc->getTraceAsString() . '</pre>';
        }

        //Return true if there are no errors
        return true;

    }

    public function update() {
        echo ("update");
        die;
    }

    public function delete($id) {
        global $wpdb;
        $table = 'wp_im_checkpoint';
        $where = ['checkpoint_id' => $id];
        $format = ['%d'];

        return $wpdb->delete($table, $where, $format);
    }

}