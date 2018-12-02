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

    /**
     * checkpointClass constructor.
     */
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

    // Retrieve Checkpoint by id
    public function getById($id) {
        //Calling wpdb
        global $wpdb;
        //Database query
        $result_array = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "im_checkpoint WHERE checkpoint_id = $id", ARRAY_A );
        // New object
        $checkpoint = new checkpointClass();
        // Set all info
        $checkpoint->setId( $result_array['checkpoint_id'] );
        $checkpoint->setTitle( $result_array['title'] );
        $checkpoint->setDescription( $result_array['description'] );
        $checkpoint->setIcon( $result_array['icon_path'] );
        // Add new object toe return array.
        $return_object = $checkpoint;
        //Return the object
        return $return_object;
    }

    // Retrieve all checkpoint information
    public function getList() {
        //Calling wpdb
        global $wpdb;
        //Setting var as an array
        $return_array = array();
        //Database query
        $result_array = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "im_checkpoint ORDER BY checkpoint_id", ARRAY_A);
        // For all database results:
        foreach ($result_array as $idx => $array) {
            // New object
            $checkpoint = new checkpointClass();
            // Set all info
            $checkpoint->setId($array['checkpoint_id']);
            $checkpoint->setTitle($array['title']);
            $checkpoint->setDescription($array['description']);
            $checkpoint->setIcon($array['icon_path']);
            // Add new object to return array.
            $return_array[] = $checkpoint;
        }
        // Return the array
        return $return_array;
    }

    //Insert into database
    public function create($input_array, $fileName, $imageFileName) {
        //Exception handeling
        try {
            //Calling $wpdb
            global $wpdb;

            // Insert query
            $wpdb->insert(
                $wpdb->prefix . 'im_checkpoint',
                array(
                    'latitude' => $input_array['latitude'],
                    'longitude' => $input_array['longitude'],
                    'title' => $input_array['title'],
                    'description' => $input_array['description'],
                    'icon_path' => $fileName
                ),
                array(
                    '%d',
                    '%d',
                    '%s',
                    '%s',
                    '%s'
                )
            );

            // Get last id for foreign key
            $getLastId = $wpdb->insert_id;

            // Count total images
            $imageAmount = count($imageFileName);

            // Set empty var for later
            $updateImageFileName = '';

            // Loop through imageFileName array to check for empty values
            foreach ($imageFileName as $key => $value) {
                $value = trim($value);
                if (!empty($value)) {
                    // If no empty values are found, set true to use later
                    $updateImageFileName = true;
                }
            }

            // Loop for inserting images
            if ($updateImageFileName === true) {
                for ($i = 0; $i < $imageAmount; $i++) {
                    // Insert query
                    $wpdb->insert(
                        $wpdb->prefix . 'im_image',
                        array(
                            'fk_checkpoint_id' => $getLastId,
                            'image_path' => $imageFileName[$i]
                        ),
                        array(
                            '%d',
                            '%s'
                        )
                    );
                }
            }

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

    // Update into database
    public function update($input_array, $fileName, $imageFileName) {
        //Exception handeling
        try {
            //Calling $wpdb
            global $wpdb;

            // Insert query if not empty
            if (isset($fileName) && !empty($fileName)) {
                $wpdb->update(
                    $wpdb->prefix . 'im_checkpoint',
                    array(
                        'latitude' => $input_array['latitude'],
                        'longitude' => $input_array['longitude'],
                        'title' => $input_array['title'],
                        'description' => $input_array['description'],
                        'icon_path' => $fileName
                    ),
                    array(
                        'checkpoint_id' => $input_array['id']),
                    array(
                        '%d',
                        '%d',
                        '%s',
                        '%s',
                        '%s',
                        '%s'
                    )
                );
            } else {
            // Skip icon path update if empty
            $wpdb->update(
                $wpdb->prefix . 'im_checkpoint',
                array(
                    'latitude' => $input_array['latitude'],
                    'longitude' => $input_array['longitude'],
                    'title' => $input_array['title'],
                    'description' => $input_array['description']
                ),
                array(
                    'checkpoint_id' => $input_array['id']),
                array(
                    '%d',
                    '%d',
                    '%s',
                    '%s',
                    '%s'
                )
            );
            }

            // If not empty image
            if (isset($input_array['image']) && !empty($input_array['image'])) {
                $wpdb->update(
                    $wpdb->prefix . 'im_image',
                    array(
                        'image_path' => $input_array['image']
                    ),
                    array(
                        'fk_checkpoint_id' => $input_array['id']),
                    array(
                        '%s',
                        '%d'
                    )
                );
            }

            // Set empty var for later
            $updateImageFileName = '';

            // Loop through imageFileName array to check for empty values
            foreach ($imageFileName as $key => $value) {
                $value = trim($value);
                if (!empty($value)) {
                    // If no empty values are found, set true to use later
                    $updateImageFileName = true;
                }
            }

            // Count images
            $imageAmount = count($imageFileName);

            // Loop image amount
            if ($updateImageFileName === true) {
                for ($i = 0; $i < $imageAmount; $i++) {

                    // Insert query
                    $wpdb->insert(
                        $wpdb->prefix . 'im_image',
                        array(
                            'fk_checkpoint_id' => $input_array['id'],
                            'image_path' => $imageFileName[$i]
                        ),
                        array(
                            '%d',
                            '%s'
                        )
                    );
                }
            }

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

    // Delete function
    public function delete($id) {
        $getImageById = $this->imageClass->getById($id);

        // Shows where to remove the uploaded file
        $uploadDirectory = INTERACTIEVE_MAP_PLUGIN_ADMIN_DIR . "/uploaded_images/images/";

        // Remove files if not empty
        foreach ($getImageById as $array) {
            $getImage = $array->getImage();
            if (!empty($getImage))
            {
                (!unlink($uploadDirectory . $getImage));
            }
            else
            {
                echo ("Er iets fout gegaan met het verwijderen van het bestand");
            }
        }

        // Calling wpdb
        global $wpdb;
        // Setting data into variables
        $table = 'wp_im_checkpoint';
        $where = ['checkpoint_id' => $id];
        $format = ['%d'];

        // Delete foreign key attachments
        $wpdb->delete( 'wp_im_image', array( 'fk_checkpoint_id' => $id), array( '%d' ) );

        // Delete data
        $wpdb->delete($table, $where, $format);
    }

    public function convertToJson ($checkpoints) {
        $jsonData = [];

        foreach ($checkpoints as $item) {
            $jsonData[] = [
                'id' => $item->getId(),
                'title' => $item->getTitle(),
                'description' => $item->getDescription(),
                'icon' => $item->getIcon()
            ];
        }
        return json_encode($jsonData);
    }

}