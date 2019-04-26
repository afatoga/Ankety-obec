<?php

/**
 * CSV Exporter bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           CSV Export
 *
 * @wordpress-plugin
 * Plugin Name:       CSV Export
 * Plugin URI:        http://example.com/plugin-name-uri/
 * Description:       exports csvs derrr
 * Version:           1.0.0
 * Author:            Your Name or Your Company
 * Author URI:        http://example.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       csv-export
 * Domain Path:       /languages
 */
class CSVExport {

  /**
   * Constructor
   */
  public function __construct() {
    if (isset($_GET['report'])) {

      $csv = $this->generate_csv();
      $encoded_csv = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $csv);
      
      header('Content-Description: File Transfer');
      header("Cache-Control: private", false);
      header("Content-Type: application/octet-stream");
      header("Content-Disposition: attachment; filename=\"report.csv\";");
      header("Content-Transfer-Encoding: binary");
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Pragma: public");
      echo $encoded_csv;
      exit;
    }

// Add extra menu items for admins
    add_action('admin_menu', array($this, 'admin_menu'));

// Create end-points
    add_filter('query_vars', array($this, 'query_vars'));
    add_action('parse_request', array($this, 'parse_request'));
  }

  /**
   * Add extra menu items for admins
   */
  public function admin_menu() {
    add_menu_page('CSV export', 'CSV export', 'manage_options', 'download_report', array($this, 'download_report'));
  }

  /**
   * Allow for custom query variables
   */
  public function query_vars($query_vars) {
    $query_vars[] = 'download_report';
    return $query_vars;
  }

  /**
   * Parse the request
   */
  public function parse_request(&$wp) {
    if (array_key_exists('download_report', $wp->query_vars)) {
      $this->download_report();
      exit;
    }
  }

  /**
   * Download report
   */
  public function download_report() {
    echo '<div class="wrap">';
    echo '<div id="icon-tools" class="icon32">
</div>';
    echo '<h2>Stáhnout CSV</h2>';
    echo '<p><a href="?page=download_report&report=projects">Exportovat výsledky hlasování - projekty</a></p>';
  }

  /**
   * Converting data to CSV
   */
  public function generate_csv() {
    global $wpdb;
    $csv_output = '';
    $projectTable = $wpdb->prefix . 'project_votes';
    $postsTable = $wpdb->prefix . 'posts';
    $link = mysqli_connect("localhost", DB_USER, DB_PASSWORD, DB_NAME);

    /*
    $result = mysqli_query($link, "SHOW COLUMNS FROM " . $table . "");

    if (mysqli_num_rows($result) > 0) {
        foreach($result as $row) {
            $csv_output = $csv_output . $row["Field"] . ";";
        }
        $csv_output = substr($csv_output, 0, -1);               //Removing the last separator, because thats how CSVs work
    }
    */
    
    $sep = ';'; // new tab csv
    $csv_output .= 'id_projektu' . $sep . 'nazev_projektu' . $sep . 'id_uzivatele'. $sep . 'votePositive' . $sep . 'voteNegative';
    $csv_output .= "\n";

    $values = mysqli_query($link, "SELECT post_id, " . $postsTable . ".post_title, user_id, vote_positive, vote_negative 
    FROM " . $projectTable . "
    LEFT OUTER JOIN " . $postsTable . " ON post_id = " . $postsTable . ".ID");

    foreach ($values as $rowr) {
        $fields = array_values((array) $rowr);                  //Getting rid of the keys and using numeric array to get values
        $csv_output .= implode($sep, $fields);      //Generating string with field separator
        $csv_output .= "\n";    //Yeah...
    }

    return $csv_output;
  }

}

// Instantiate a singleton of this plugin
$csvExport = new CSVExport();