<?php
use PHPUnit\Framework\TestCase;

class SiteSettingsTest extends TestCase
{
    public function testSiteSettingsPageLoadsWithoutWarnings()
    {
        // Start output buffering to capture any output, including warnings
        ob_start();

        // Include the file to be tested.
        // This will execute the PHP code in the file.
        include __DIR__ . '/../admin/site-setting.php';

        // Get the contents of the output buffer
        $output = ob_get_clean();

        // Assert that the output does not contain the string "Warning: Undefined array key"
        $this->assertStringNotContainsString('Warning: Undefined array key', $output);
    }
}
