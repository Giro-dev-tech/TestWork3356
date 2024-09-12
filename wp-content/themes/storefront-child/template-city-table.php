<?php
/*
Template Name: City Table
*/

// Load the header template
get_header(); 

global $wpdb; // Access the global WordPress database object

// SQL query to fetch city data (title, latitude, longitude) from the database
$cities = $wpdb->get_results("
    SELECT p.ID, p.post_title, pm1.meta_value as latitude, pm2.meta_value as longitude
    FROM {$wpdb->prefix}posts p
    LEFT JOIN {$wpdb->prefix}postmeta pm1 ON p.ID = pm1.post_id AND pm1.meta_key = 'latitude'
    LEFT JOIN {$wpdb->prefix}postmeta pm2 ON p.ID = pm2.post_id AND pm2.meta_key = 'longitude'
    WHERE p.post_type = 'cities'
    AND p.post_status = 'publish' -- Only fetch published cities
    AND pm1.meta_value IS NOT NULL AND pm1.meta_value != '' -- Only include rows with non-empty latitude
    AND pm2.meta_value IS NOT NULL AND pm2.meta_value != '' -- Only include rows with non-empty longitude
");

// Custom function to fetch the current temperature from OpenWeatherMap API
function get_temperature($lat, $lon) {
    $api_key = '62aa3fca97f85effc9700ddd276f2e50'; // Replace with your actual OpenWeatherMap API key
    $api_url = "https://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&appid=$api_key&units=metric";
    $response = wp_remote_get($api_url); // Get the API response
    $data = json_decode(wp_remote_retrieve_body($response), true); // Decode the JSON response

    // Check if temperature data is available in the API response
    if (isset($data['main']['temp'])) {
        return $data['main']['temp']; // Return the temperature if available
    } else {
        return null; // Return null if temperature is not available
    }
}

?>

<!-- HTML structure for the City Table -->
<div class="city-table">
    <h2>City Table with Temperatures</h2>
    <!-- Search input to filter cities -->
    <input type="text" id="city-search" placeholder="Search for cities...">
    
    <!-- City table with header -->
    <table>
        <thead>
            <tr>
                <th>City</th>
                <th>Country</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Temperature (°C)</th>
            </tr>
        </thead>
        <tbody id="city-list">
            <?php foreach ($cities as $city) : ?>
                <?php
                $latitude = $city->latitude;
                $longitude = $city->longitude;

                // Check if both latitude and longitude exist
                if (!empty($latitude) && !empty($longitude)) {
                    // Get the temperature for the city
                    $temperature = get_temperature($latitude, $longitude);

                    // Only display the row if temperature data is successfully retrieved
                    if ($temperature !== null) { 
                        ?>
                        <!-- Output the city information in a table row -->
                        <tr>
                            <td><?php echo $city->post_title; ?></td>
                            <td><?php echo get_the_terms($city->ID, 'countries')[0]->name; ?></td> <!-- Display country name -->
                            <td><?php echo $latitude; ?></td>
                            <td><?php echo $longitude; ?></td>
                            <td><?php echo $temperature; ?>°C</td> <!-- Display the temperature -->
                        </tr>
                        <?php
                    }
                }
                ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- JavaScript/jQuery to enable search functionality in the table -->
<script type="text/javascript">
    jQuery(document).ready(function($) {
        // Attach keyup event to the search input to filter table rows
        $('#city-search').on('keyup', function() {
            var searchValue = $(this).val().toLowerCase(); // Get the search input value
            // Filter table rows based on the search value
            $('#city-list tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(searchValue) > -1); // Show or hide rows that match
            });
        });
    });
</script>

<?php 
// Load the footer template
get_footer(); 
?>
