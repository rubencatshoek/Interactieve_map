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

}