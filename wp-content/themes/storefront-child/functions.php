<?php
// Enqueue the parent theme's stylesheet in the child theme.
function storefront_child_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'storefront_child_enqueue_styles');

// Create a custom post type called 'Cities'.
function create_cities_post_type() {
    $args = array(
        'public' => true, // Make the post type publicly accessible.
        'label'  => 'Cities', // Name of the post type.
        'supports' => array('title', 'editor'), // Fields that the post type supports (title, editor).
    );
    register_post_type('cities', $args); // Register the post type with these arguments.
}
add_action('init', 'create_cities_post_type');

// Add a meta box for Latitude and Longitude fields on the Cities post type.
function add_latitude_longitude_metabox() {
    add_meta_box(
        'city_lat_lon', // ID of the meta box.
        'City Latitude and Longitude', // Title of the meta box.
        'display_lat_lon_meta_box', // Callback function to display the fields.
        'cities', // Post type where the meta box will appear.
        'normal', // Context (where the meta box appears).
        'high' // Priority of the meta box.
    );
}
add_action('add_meta_boxes', 'add_latitude_longitude_metabox');

// Function to display the Latitude and Longitude fields in the meta box.
function display_lat_lon_meta_box($post) {
    // Retrieve stored values for latitude and longitude.
    $latitude = get_post_meta($post->ID, 'latitude', true);
    $longitude = get_post_meta($post->ID, 'longitude', true);
    ?>
    <!-- Input fields for Latitude and Longitude -->
    <label for="latitude">Latitude:</label>
    <input type="text" name="latitude" value="<?php echo $latitude; ?>">
    <label for="longitude">Longitude:</label>
    <input type="text" name="longitude" value="<?php echo $longitude; ?>">
    <?php
}

// Save the latitude and longitude values when the post is saved.
function save_lat_lon_meta_box($post_id) {
    // Save latitude value if it exists in POST data.
    if (array_key_exists('latitude', $_POST)) {
        update_post_meta($post_id, 'latitude', $_POST['latitude']);
    }
    // Save longitude value if it exists in POST data.
    if (array_key_exists('longitude', $_POST)) {
        update_post_meta($post_id, 'longitude', $_POST['longitude']);
    }
}
add_action('save_post', 'save_lat_lon_meta_box');

// Create a custom taxonomy called 'Countries' and attach it to the 'Cities' post type.
function create_countries_taxonomy() {
    $args = array(
        'label'        => 'Countries', // Label for the taxonomy.
        'rewrite'      => array('slug' => 'countries'), // URL slug for the taxonomy.
        'hierarchical' => true, // Make the taxonomy hierarchical like categories.
    );
    register_taxonomy('countries', 'cities', $args); // Register the taxonomy with the 'cities' post type.
}
add_action('init', 'create_countries_taxonomy');

// Define a custom widget class to display the city name and its current temperature.
class City_Temperature_Widget extends WP_Widget {
    
    // Constructor to initialize the widget.
    function __construct() {
        parent::__construct(
            'city_temperature_widget', // Widget ID.
            'City Temperature', // Widget name displayed in the admin.
            array('description' => 'Displays the city and its current temperature.') // Description of the widget.
        );
    }

    // The output of the widget on the frontend.
    function widget($args, $instance) {
        // Get the selected city post and its latitude/longitude.
        $city = get_post($instance['city_id']);
        $latitude = get_post_meta($city->ID, 'latitude', true);
        $longitude = get_post_meta($city->ID, 'longitude', true);

        // Call the OpenWeatherMap API to get the current temperature.
        $api_key = '62aa3fca97f85effc9700ddd276f2e50'; // Replace with your OpenWeatherMap API key.
        $api_url = "https://api.openweathermap.org/data/2.5/weather?lat=$latitude&lon=$longitude&appid=$api_key&units=metric";
        $response = wp_remote_get($api_url);
        $data = json_decode(wp_remote_retrieve_body($response), true);
        $temperature = $data['main']['temp']; // Extract the temperature from the API response.

        // Output the widget HTML.
        echo $args['before_widget'];
        echo "<p>City: {$city->post_title}</p>";
        echo "<p>Temperature: {$temperature}°C</p>";
        echo $args['after_widget'];
    }

    // The form displayed in the WordPress admin to select a city.
    function form($instance) {
        // Get the selected city ID if it exists.
        $city_id = !empty($instance['city_id']) ? $instance['city_id'] : '';
        ?>
        <!-- Dropdown to select a city from the 'Cities' post type. -->
        <p>
            <label for="<?php echo $this->get_field_id('city_id'); ?>">Select City:</label>
            <select name="<?php echo $this->get_field_name('city_id'); ?>" id="<?php echo $this->get_field_id('city_id'); ?>">
                <?php
                // Fetch all cities.
                $cities = get_posts(array('post_type' => 'cities', 'posts_per_page' => -1));
                foreach ($cities as $city) {
                    // Create options for each city.
                    echo "<option value='{$city->ID}' " . selected($city_id, $city->ID, false) . ">{$city->post_title}</option>";
                }
                ?>
            </select>
        </p>
        <?php
    }

    // Update the widget settings when saved.
    function update($new_instance, $old_instance) {
        $instance = array();
        // Strip tags to prevent code injection and save the selected city ID.
        $instance['city_id'] = (!empty($new_instance['city_id'])) ? strip_tags($new_instance['city_id']) : '';
        return $instance;
    }
}

// Register the custom City Temperature Widget.
function register_city_temperature_widget() {
    register_widget('City_Temperature_Widget');
}
add_action('widgets_init', 'register_city_temperature_widget');
?>
