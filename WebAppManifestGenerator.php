<?php

namespace Mozilla;

if (!class_exists('WebAppManifestGenerator')) {
  class WebAppManifestGenerator {
    private static $instance;
    private $fields;

    public function __construct() {
      $this->fields = get_option('webappmanifest_content', array(
        "start_url" => "/",
      ));

      add_action('wp_head', array($this, 'add_manifest'));

      WP_Serve_File::getInstance()->add_file('manifest.json', array($this, 'manifestJSONGenerator'));
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
      update_option('webappmanifest_content', $this->fields);
      WP_Serve_File::getInstance()->invalidate_files(array('manifest.json'));
    }

    public function manifestJSONGenerator() {
      return array(
        'content' => json_encode($this->fields),
        'contentType' => 'application/json',
      );
    }
  }
}

?>
