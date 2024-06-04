<?php
class DV_logs {

    public $log_filename;
    private $log_folder = DV_PLUGIN_DIR_ABS."/logs/";

    public function __construct() {}

    public function write_log( $data ) {

        if( !$this->log_filename ) { return; }

        // Log file path
        $log_file = $this->log_folder.$this->log_filename;

        // Add timestamp
        $data = date("Y-d-m H:i:s")." - ".$data;

        // Write into log file
        file_put_contents($log_file, $data.PHP_EOL, FILE_APPEND);

    }

}