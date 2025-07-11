<?php
/**
 * Plugin Name: WJM Contact Form
 * Description: Plugin de formulário de contato com arquitetura DDD, Clean Architecture e Shortcode.
 * Version: 1.1.3
 * Author: Welington Jose Miyazato
 */

defined('ABSPATH') || exit;

// 1. Define a constante principal do plugin
if (!defined('WJM_PLUGIN_FILE')) {
    define('WJM_PLUGIN_FILE', __FILE__);
}

// 2. Autoload das classes (Composer ou PSR-4 manual)
require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';