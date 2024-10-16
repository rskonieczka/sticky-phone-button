<?php
/*
Plugin Name: Sticky Phone Button
Description: Wyświetla przyklejoną ikonę telefonu po bokach ekranu na urządzeniach mobilnych, umożliwiającą natychmiastowe połączenie. Dostosowanie miejsca, numeru telefonu, dni i godzin wyświetlania oraz kolorów.
Version: 1.3
Author: Wirtualny Handlowiec
Author URI: http://wirtualnyhandlowiec.pl/
*/

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue style and script for frontend
 */
function sticky_phone_button_enqueue_scripts()
{
    /**
     * Load CSS file with styles for the sticky phone button
     */
    wp_enqueue_style(
        'sticky-phone-button-style',
        plugin_dir_url(__FILE__) . 'style.css'
    );

    /**
     * Load JavaScript file with the script for the sticky phone button
     */
    wp_enqueue_script(
        'sticky-phone-button-script',
        plugin_dir_url(__FILE__) . 'script.js',
        array(),
        false,
        true
    );
}
add_action('wp_enqueue_scripts', 'sticky_phone_button_enqueue_scripts');

/**
 * Add a page to the WordPress admin menu for the sticky phone button settings
 */
function sticky_phone_button_add_admin_menu()
{
    /**
     * Add an options page to the WordPress admin menu
     * @param string $page_title The title of the page
     * @param string $menu_title The title of the menu item
     * @param string $capability The capability required to access the page
     * @param string $menu_slug The slug of the menu item
     * @param callable $function The function to call when the page is accessed
     */
    add_options_page(
        __('Sticky Phone Button Settings', 'sticky-phone-button'),
        __('Sticky Phone Button', 'sticky-phone-button'),
        'manage_options',
        'sticky-phone-button',
        'sticky_phone_button_options_page'
    );
}
add_action('admin_menu', 'sticky_phone_button_add_admin_menu');

/**
 * Register the settings for the sticky phone button plugin.
 */
function sticky_phone_button_settings_init()
{
    // Register the settings for the plugin
    register_setting('stickyPhoneButtonSettings', 'sticky_phone_button_settings');

    // Add a section for the phone button settings
    add_settings_section(
        'sticky_phone_button_settings_section',
        __('Ustawienia przycisku telefonu', 'sticky-phone-button'),
        /**
         * Callback function for displaying a description of the settings section.
         * This function is optional and can be used to provide additional information
         * to the user about this section.
         */
        function () {
            // Do nothing
        },
        'stickyPhoneButtonSettings'
    );

    // Add a field for selecting the display device
    add_settings_field(
        'sticky_phone_button_display_device',
        __('Wyświetlaj na', 'sticky-phone-button'),
        /**
         * Callback function to render the select field for the device type.
         * This field allows users to choose on which devices the button will be displayed.
         * @return void
         */
        'sticky_phone_button_display_device_render',
        'stickyPhoneButtonSettings',
        'sticky_phone_button_settings_section'
    );

    // Add a field for entering the phone number
    add_settings_field(
        'sticky_phone_button_phone_number',
        __('Numer telefonu', 'sticky-phone-button'),
        /**
         * Callback function to render the text field for the phone number.
         * This field allows users to specify the phone number that will be called.
         * @return void
         */
        'sticky_phone_button_phone_number_render',
        'stickyPhoneButtonSettings',
        'sticky_phone_button_settings_section'
    );

    // Add a field for selecting the button position
    add_settings_field(
        'sticky_phone_button_position',
        __('Pozycja przycisku (left/right)', 'sticky-phone-button'),
        /**
         * Callback function to render the select field for the button position.
         * This field allows users to specify where the button will appear on the screen.
         * @return void
         */
        'sticky_phone_button_position_render',
        'stickyPhoneButtonSettings',
        'sticky_phone_button_settings_section'
    );

    // Add fields for selecting display days and hours
    add_settings_field(
        'sticky_phone_button_display_days',
        __('Dni wyświetlania i godziny', 'sticky-phone-button'),
        /**
         * Callback function to render the checkboxes and text fields for display days and hours.
         * This field allows users to specify on which days and during which hours the button will be displayed.
         * @return void
         */
        'sticky_phone_button_display_days_render',
        'stickyPhoneButtonSettings',
        'sticky_phone_button_settings_section'
    );

    // Add a field for selecting the icon color
    add_settings_field(
        'sticky_phone_button_icon_color',
        __('Kolor ikony słuchawki', 'sticky-phone-button'),
        /**
         * Callback function to render the color picker for the icon color.
         * This field allows users to choose the color of the phone icon.
         * @return void
         */
        'sticky_phone_button_icon_color_render',
        'stickyPhoneButtonSettings',
        'sticky_phone_button_settings_section'
    );

    // Add a field for selecting the background color
    add_settings_field(
        'sticky_phone_button_background_color',
        __('Kolor tła koła', 'sticky-phone-button'),
        /**
         * Callback function to render the color picker for the background color.
         * This field allows users to choose the background color of the button.
         * @return void
         */
        'sticky_phone_button_background_color_render',
        'stickyPhoneButtonSettings',
        'sticky_phone_button_settings_section'
    );

    // Add a field for setting the blink time
    add_settings_field(
        'sticky_phone_button_blink_time',
        __('Czas między mrugnięciami', 'sticky-phone-button'),
        /**
         * Callback function to render the text field for the blink time.
         * This field allows users to specify the time interval between blinks of the button.
         * @return void
         */
        'sticky_phone_button_blink_time_render',
        'stickyPhoneButtonSettings',
        'sticky_phone_button_settings_section'
    );
}
add_action('admin_init', 'sticky_phone_button_settings_init');

/**
 * Render a select field for the device type.
 * This field allows users to choose on which devices the button will be displayed.
 * @return void
 */
function sticky_phone_button_display_device_render()
{
    $options = get_option('sticky_phone_button_settings');
    // Get the current value of the display device setting
    $display_device = isset($options['sticky_phone_button_display_device']) ? $options['sticky_phone_button_display_device'] : 'both';
    // Render the select field with options for phones, desktops and both
?>
    <select name='sticky_phone_button_settings[sticky_phone_button_display_device]'>
        <option value='phones' <?php selected($display_device, 'phones'); ?>>Tylko na telefonach</option>
        <option value='desktops' <?php selected($display_device, 'desktops'); ?>>Tylko na komputerach</option>
        <option value='both' <?php selected($display_device, 'both'); ?>>Na telefonach i komputerach</option>
    </select>
<?php
}

/**
 * Render a text field for the phone number.
 * This field allows users to enter a phone number to be displayed on the button.
 * @return void
 */
function sticky_phone_button_phone_number_render()
{
    $options = get_option('sticky_phone_button_settings');
?>
    <input type='text' name='sticky_phone_button_settings[sticky_phone_button_phone_number]'
        value='<?php echo esc_attr($options['sticky_phone_button_phone_number']); ?>'>
    <p class="description">Numer telefonu, np. 501 501 501</p>
<?php
}
/**
 * Render a text field for the blink time.
 * This field allows users to enter the time (in seconds) of the blink animation for the button.
 * @return void
 */
function sticky_phone_button_blink_time_render()
{
    $options = get_option('sticky_phone_button_settings');
    // Default blink time is 4 seconds
    $blink_time = isset($options['sticky_phone_button_blink_time']) ? $options['sticky_phone_button_blink_time'] : '4';
?>
    <input type="number" step="0.1" name="sticky_phone_button_settings[sticky_phone_button_blink_time]" value="<?php echo esc_attr($blink_time); ?>" min="0.5" max="10">
    <p class="description">
        Czas (w sekundach) co ile ma mrugać przycisk.
        <br>
        Wartość musi być z zakresu 0,5-10 sekund.
    </p>
<?php
}

/**
 * Render the dropdown field for selecting the button position.
 * This field allows users to choose where the sticky phone button will be displayed on the screen.
 * 
 * @return void
 */
function sticky_phone_button_position_render()
{
    // Retrieve the plugin options from the database
    $options = get_option('sticky_phone_button_settings');

    // Define the available positions for the sticky phone button
    $positions = [
        'bottom-right' => 'Prawy dolny róg',
        'bottom-left' => 'Lewy dolny róg',
        'bottom-center' => 'Dół środek',
        'top-right' => 'Góra prawy róg',
        'top-left' => 'Góra lewy róg',
        'top-center' => 'Góra środek',
        'middle-right' => 'Prawy środek',
        'middle-left' => 'Lewy środek'
    ];
?>
    <select name='sticky_phone_button_settings[sticky_phone_button_position]'>
        <?php
        // Iterate over each position and render it as an option in the dropdown
        foreach ($positions as $value => $label) : ?>
            <option value='<?php echo esc_attr($value); ?>' <?php selected($options['sticky_phone_button_position'], $value); ?>>
                <?php echo esc_html($label); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php
}

// Renderowanie checkboxów
$days_of_week = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

/**
 * Renderowanie checkboxów dla dni i godzin
 *
 * This function renders checkboxes for each day of the week and a text field for specifying the hours of the day.
 * The function also retrieves the plugin options from the database and checks if the day is selected.
 * If the day is selected, the checkbox is checked and the text field is populated with the hours.
 * If the day is not selected, the checkbox is unchecked and the text field is empty.
 *
 * @return void
 */
function sticky_phone_button_display_days_render()
{
    global $days_of_week;
    $options = get_option('sticky_phone_button_settings'); // Pobierz opcje

    // Dla każdego dnia wyświetl checkbox i pole godziny
    foreach ($days_of_week as $day) {
        // Sprawdzamy, czy dzień jest wybrany
        $checked = isset($options['display_days'][$day]['enabled']) ? 'checked' : '';

        // Sprawdzamy, czy godziny dla danego dnia są ustawione, jeśli nie, ustawiamy pustą wartość
        $hours = isset($options['display_days'][$day]['hours']) ? $options['display_days'][$day]['hours'] : '';
    ?>
        <label>
            <input type='checkbox' name='sticky_phone_button_settings[display_days][<?php echo $day; ?>][enabled]' <?php echo $checked; ?>>
            <?php echo $day; ?>
        </label>
        <input type='text' name='sticky_phone_button_settings[display_days][<?php echo $day; ?>][hours]' placeholder='09:00-17:00' value='<?php echo esc_attr($hours); ?>'>
        <br>
    <?php
    }
}


/**
 * Renderowanie pola wyboru koloru ikony słuchawki.
 *
 * This function renders a color picker field for the icon color.
 * The field is populated with the current value from the database.
 * If no value is set, the field is set to white (#FFF).
 *
 * @return void
 */
function sticky_phone_button_icon_color_render()
{
    $options = get_option('sticky_phone_button_settings');
    ?>
    <input type='color' name='sticky_phone_button_settings[sticky_phone_button_icon_color]' value='<?php echo esc_attr(isset($options['sticky_phone_button_icon_color']) ? $options['sticky_phone_button_icon_color'] : '#FFF'); ?>'>
<?php
}

/**
 * Renderowanie pola wyboru koloru tła koła.
 *
 * This function renders a color picker field for the background color of the circle.
 * The field is populated with the current value from the database.
 * If no value is set, the field is set to a default green color (#10941f).
 *
 * @return void
 */
function sticky_phone_button_background_color_render()
{
    $options = get_option('sticky_phone_button_settings');
?>
    <input type='color' name='sticky_phone_button_settings[sticky_phone_button_background_color]' value='<?php echo esc_attr(isset($options['sticky_phone_button_background_color']) ? $options['sticky_phone_button_background_color'] : '#10941f'); ?>'>
<?php
}


/**
 * Displays the options page for the sticky phone button plugin in the WordPress admin area.
 * This function generates the HTML form with the plugin settings.
 *
 * @return void
 */
function sticky_phone_button_options_page()
{
    // Form with the plugin settings
?>
    <form action='options.php' method='post'>
        <h2>Sticky Phone Button</h2>
        <?php
        // Display the settings sections
        settings_fields('stickyPhoneButtonSettings');
        do_settings_sections('stickyPhoneButtonSettings');
        // Display the submit button
        submit_button();
        ?>
    </form>
<?php
}

// Dodawanie dynamicznego stylu CSS na podstawie ustawień czasu mrugania
/**
 * Generuje dynamiczny styl CSS, aby ustawić animację mrugania dla przycisku telefonu.
 * Animacja jest ustawiona na mruganie co X sekund, w zależności od wartości w ustawieniach.
 * Animacja jest nieograniczona i będzie mrugać przyciskiem w i out of view.
 *
 * @return void
 */
function sticky_phone_button_dynamic_css()
{
    $options = get_option('sticky_phone_button_settings');
    $blink_time = isset($options['sticky_phone_button_blink_time']) ? $options['sticky_phone_button_blink_time'] : '4'; // domyślna wartość 4s

    // Wygeneruj kod CSS z ustawionym czasem mrugania
?>
    <style type="text/css">
        /**
         * Ustawia animację mrugania dla przycisku telefonu.
         * Animacja jest ustawiona na mruganie co X sekund, w zależności od wartości w ustawieniach.
         * Animacja jest nieograniczona i będzie mrugać przyciskiem w i out of view.
         */
        .sticky-phone-button {
            animation: blink <?php echo esc_attr($blink_time); ?>s infinite;
        }

        /**
         * Animacja mrugania przycisku telefonu.
         * Animacja jest ustawiona na mruganie co X sekund, w zależności od wartości w ustawieniach.
         * Animacja jest nieograniczona i będzie mrugać przyciskiem w i out of view.
         *
         * 0% - 97%: The button is visible
         * 98% - 99%: The button is hidden (quick blink)
         * 100%: The button is visible again
         */
        @keyframes blink {

            /**
             * 0% - 97%: The button is visible
             */
            0%,
            97%,
            100% {
                opacity: 1;
                /* Przycisk widoczny */
            }

            /**
             * 98% - 99%: The button is hidden (quick blink)
             */
            98%,
            99% {
                opacity: 0;
                /* Krótkie mrugnięcie */
            }
        }
    </style>
<?php
}
add_action('wp_head', 'sticky_phone_button_dynamic_css');

/**
 * Display the sticky phone button on the frontend with the options.
 * This function renders the button with the specified settings and ensures it only displays
 * during the configured days and hours. The button is styled and positioned based on user preferences.
 *
 * @return void
 */
function sticky_phone_button_html()
{
    // Retrieve the plugin options from the database
    $options = get_option('sticky_phone_button_settings');

    // Retrieve the phone number, position, icon color, background color, and display device settings
    $phone_number = isset($options['sticky_phone_button_phone_number']) ? $options['sticky_phone_button_phone_number'] : '';
    $position = isset($options['sticky_phone_button_position']) ? $options['sticky_phone_button_position'] : 'bottom-right';
    $icon_color = isset($options['sticky_phone_button_icon_color']) ? $options['sticky_phone_button_icon_color'] : '#ffffff';
    $background_color = isset($options['sticky_phone_button_background_color']) ? $options['sticky_phone_button_background_color'] : '#000000';
    $display_device = isset($options['sticky_phone_button_display_device']) ? $options['sticky_phone_button_display_device'] : 'both';

    // Get the current day and time
    $current_day = date('l');
    $current_time = date('H:i');

    // Check if the current day is selected and if the current time is within the specified range
    if (isset($options['display_days'][$current_day]['enabled'])) {
        $hours_range = explode('-', $options['display_days'][$current_day]['hours']);
        if (!empty($hours_range) && count($hours_range) == 2) {
            if ($current_time < $hours_range[0] || $current_time > $hours_range[1]) {
                return; // Do not display the button if the current time is not within the range
            }
        }
    } else {
        return; // Do not display the button if the current day is not selected
    }

    // Determine the CSS class based on the selected display device
    $device_class = '';
    if ($display_device == 'phones') {
        $device_class = 'sticky-phone-only'; // Class for phone-only display
    } elseif ($display_device == 'desktops') {
        $device_class = 'sticky-desktop-only'; // Class for desktop-only display
    }

    // Render the phone button with the specified options and styles
    echo '<a href="tel:' . esc_attr($phone_number) . '" id="sticky-phone-button" class="sticky-phone-button sticky-' . esc_attr($position) . ' ' . esc_attr($device_class) . '" style="background-color: ' . esc_attr($background_color) . ';">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="' . esc_attr($icon_color) . '">
                <path d="M6.62 10.79a15.091 15.091 0 006.59 6.59l2.2-2.2a1 1 0 011.11-.27c1.21.49 2.54.75 3.91.75a1 1 0 011 1v3.5a1 1 0 01-1 1A17.918 17.918 0 013 3a1 1 0 011-1h3.5a1 1 0 011 1c0 1.37.26 2.7.75 3.91a1 1 0 01-.27 1.11l-2.2 2.2z"/>
            </svg>
          </a>';
}
add_action('wp_footer', 'sticky_phone_button_html');
