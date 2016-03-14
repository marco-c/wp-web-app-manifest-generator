<?php

require_once(plugin_dir_path(__FILE__) . '../../marco-c/wp_serve_file/class-wp-serve-file.php');

if (!class_exists('WebAppManifestGenerator')) {
  class WebAppManifestGenerator {
    private static $instance;
    private $fields = array(
      "start_url" => "/",
    );

    public function __construct() {
      add_action('wp_head', array($this, 'add_manifest'));

      $wpServeFile = WP_Serve_File::getInstance();
      $wpServeFile->add_file('manifest.json', array($this, 'manifestJSONGenerator'));
    }

    public static function getInstance() {
      if (!self::$instance) {
        self::$instance = new self();
      }

      return self::$instance;
    }

    public function add_manifest() {
      echo '<link rel="manifest" href="' . WP_Serve_File::get_relative_to_host_root_url('manifest.json') . '">';
    }

    public function set_field($key, $value) {
      $this->fields[$key] = $value;
    }

    public function manifestJSONGenerator($query) {
      return array(
        'content' => wp_json_encode($this->fields),
        'contentType' => 'application/json',
      );
    }
  }
}

?>
