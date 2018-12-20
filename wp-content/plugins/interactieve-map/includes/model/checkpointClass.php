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
     * @var string
     */
    private $imageClass;

    /**
     * @var float
     */
    private $latitude;

    /**
     * @var float
     */
    private $longitude;

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
     * @param float $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
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
        $checkpoint->setLatitude( $result_array['latitude']);
        $checkpoint->setLongitude( $result_array['longitude']);
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
            $checkpoint->setLatitude($array['latitude']);
            $checkpoint->setLongitude($array['longitude']);
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
                    '%f',
                    '%f',
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
    public function update($input_array, $fileName, $imageFileName, $id) {
        $getIconById = $this->getById($id);

        $getIcon = $getIconById->getIcon();

        $getIconList = $this->getList();

        // Shows where to remove the uploaded icon file
        $iconUploadDirectory = INTERACTIEVE_MAP_PLUGIN_ADMIN_DIR . "/uploaded_images/icons/";

        //Exception handeling
        try {
            //Calling $wpdb
            global $wpdb;

            // Insert query if not empty
            if (isset($fileName) && !empty($fileName)) {

                // Set variable for later usage
                $keepIcon = false;

                // Check if icon has other usage. If not, delete it from the map
                foreach ($getIconList as $value) {
                    $usageIcon [] = $getIcon === $value->getIcon();
                    $countUsageIcon = count(array_filter($usageIcon));
                    if ($countUsageIcon > 1) {
                        $keepIcon = true;
                    }
                }

                // Check if icon has other usage. If not, delete it from the map
                if (!empty($getIcon) && $keepIcon == false) {
                    (!unlink($iconUploadDirectory . $getIcon));
                }

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
                        '%f',
                        '%f',
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
                    '%f',
                    '%f',
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

        $getIconById = $this->getById($id);

        $getIcon = $getIconById->getIcon();

        // Shows where to remove the uploaded image file
        $imageUploadDirectory = INTERACTIEVE_MAP_PLUGIN_ADMIN_DIR . "/uploaded_images/images/";

        // Shows where to remove the uploaded icon file
        $iconUploadDirectory = INTERACTIEVE_MAP_PLUGIN_ADMIN_DIR . "/uploaded_images/icons/";

        // Get the Checkpoint list
        $getIconList = $this->getList();

        // Get the Checkpoint list
        $getImageList = $this->imageClass->getList();

        // Set false for later usage
        $keepIcon = false;

        // Keep the icon if there are more fields with the same icon
        foreach ($getIconList as $array) {
            $useageIcon [] = $getIcon === $array->getIcon();
            $countUsageIcon = count(array_filter($useageIcon));
            if ($countUsageIcon > 1) {
                $keepIcon = true;
            }
        }

        // Set variable for later usage
        $imageListArray = [];
        $singleImageArray = [];

        // Loop through the list and fill the variable
        foreach ($getImageList as $value) {
            $imageListArray[] = $value->getImage();
        }

        // Loop through the list and fill the variable
        foreach ($getImageById as $value) {
            $singleImageArray[] = $value->getImage();
        }

        // Check if array has usage in other array
        $result = array_intersect($imageListArray, $singleImageArray);
        $countUsageImage = array_count_values($result);

        // Check if image has other usage. If not, delete it from the map
        foreach ($countUsageImage as $key => $value) {
            if ($value < 2) {
                (!unlink($imageUploadDirectory . $key));
            }
        }

        // Check if icon has other usage. If not, delete it from the map
        if (!empty($getIcon) && $keepIcon == false) {
            (!unlink($iconUploadDirectory . $getIcon));
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

        $imageClass = new imageClass();

        foreach ($checkpoints as $item) {
            $imageList = array();

            $getImages = $imageClass->getById($item->getId());

            foreach ($getImages as $singleImage) {
                $imageList[] = $singleImage->getImage();
            }

            $jsonData[] = [
                'id' => $item->getId(),
                'title' => $item->getTitle(),
                'description' => $item->getDescription(),
                'icon' => $item->getIcon(),
                'latitude' => $item->getLatitude(),
                'longitude' => $item->getLongitude(),
                'images' => $imageList
            ];
        }
        return json_encode($jsonData);
    }
}