<?php

namespace Tests\Unit\Infra\WordPress;

use PHPUnit\Framework\TestCase;
use WJM\Infra\WordPress\Helpers\UrlHelper;

class UrlHelperTest extends TestCase
{
    private string $pluginFile;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pluginFile = '/wp-content/plugins/wjm-contact-form/wjm-contact-form.php';

        // Mock das funções WordPress
        if (!function_exists('plugins_url')) {
            eval('function plugins_url($path = "", $plugin_file = "") {
                return "https://example.com" . dirname($plugin_file) . "/" . ltrim($path, "/");
            }');
        }

        if (!function_exists('admin_url')) {
            eval('function admin_url($path = "") {
                return "https://example.com/wp-admin/" . ltrim($path, "/");
            }');
        }
    }

    public function testGetBannerUrlReturnsCorrectUrl(): void
    {
        $helper = new UrlHelper($this->pluginFile);

        $expected = 'https://example.com/wp-content/plugins/wjm-contact-form/assets/img/banner-470x152.png';
        $this->assertSame($expected, $helper->getBannerUrl());
    }

    public function testGetFormEditorUrl(): void
    {
        $helper = new UrlHelper($this->pluginFile);

        $expected = 'https://example.com/wp-admin/admin.php?page=wjm_form_editor';
        $this->assertSame($expected, $helper->getFormEditorUrl());
    }

    public function testGetFormMessagesUrl(): void
    {
        $helper = new UrlHelper($this->pluginFile);

        $expected = 'https://example.com/wp-admin/admin.php?page=wjm_form_messages';
        $this->assertSame($expected, $helper->getFormMessagesUrl());
    }
}
