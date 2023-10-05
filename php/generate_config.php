<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the selected configuration options from the form
    $enableQuitButton = isset($_POST['enableQuitButton']) ? 'false' : 'true';
    $enableBackButton = isset($_POST['enableBackButton']) ? 'false' : 'true';
    $enableReloadButton = isset($_POST['enableReloadButton']) ? 'false' : 'true';
    $copyPasteBehavior = isset($_POST['copyPasteBehavior']) ? 'false' : 'true';

    // Generate the configuration file content
    $configContent = <<<EOT
<?xml version="1.0"?>
<config>
    <seb:config xmlns:seb="http://www.safeexambrowser.org/SebConfig.xsd">
        <!-- ... -->
        <seb:browserSettings>
            <seb:enableQuitButton>$enableQuitButton</seb:enableQuitButton>
            <seb:enableBackButton>$enableBackButton</seb:enableBackButton>
            <seb:enableReloadButton>$enableReloadButton</seb:enableReloadButton>
            <seb:copyPasteBehavior>$copyPasteBehavior</seb:copyPasteBehavior>
			<seb:enableURLFilter>true</seb:enableURLFilter>
            <seb:allowedURLs>
                <seb:allow>
                    <seb:url>http://localhost/exam_system/dashboard_students.php</seb:url>
                </seb:allow>
            </seb:allowedURLs>
            <!-- Add other browser settings as per your requirements -->
        </seb:browserSettings>
    </seb:config>
</config>
EOT;

    // Generate the configuration file
    $configFile = "seb_config.xml";
    file_put_contents($configFile, $configContent);

    // Provide the configuration file as a download
    header("Content-Type: application/xml");
    header("Content-Disposition: attachment; filename=\"$configFile\"");
    readfile($configFile);

    // Delete the configuration file from the server
    unlink($configFile);
}
?>
