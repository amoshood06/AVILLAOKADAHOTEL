<?php
require_once '../config/functions.php';
initSessionConfig();
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$user = $_SESSION['user'];
$settings = getSiteSettings();
$settings = is_array($settings) ? $settings : [];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $params = [
        'site_name' => $_POST['site_name'],
        'site_description' => $_POST['site_description'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'address' => $_POST['address'],
        'city' => $_POST['city'],
        'state' => $_POST['state'],
        'country' => $_POST['country'],
        'facebook_link' => $_POST['facebook_link'],
        'instagram_link' => $_POST['instagram_link'],
        'twitter_link' => $_POST['twitter_link'],
        'whatsapp_link' => $_POST['whatsapp_link'],
        'currency' => $_POST['currency'],
        'flutterwave_public_key' => $_POST['flutterwave_public_key'],
        'flutterwave_secret_key' => $_POST['flutterwave_secret_key'],
        'flutterwave_encryption_key' => $_POST['flutterwave_encryption_key'],
        'flutterwave_mode' => $_POST['flutterwave_mode'],
        'maintenance_mode' => isset($_POST['maintenance_mode']) ? 1 : 0,
        'smtp_host' => $_POST['smtp_host'],
        'smtp_port' => $_POST['smtp_port'],
        'smtp_user' => $_POST['smtp_user'],
        'smtp_password' => $_POST['smtp_password'],
        'smtp_encryption' => $_POST['smtp_encryption'],
    ];

    // Handle logo upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK && !empty($_FILES['logo']['name'])) {
        if (isset($settings['logo']) && $settings['logo']) {
            deleteOldFile('../asset/image/' . $settings['logo']);
        }
        $logoName = time() . '_logo_' . $_FILES['logo']['name'];
        $logoPath = '../asset/image/' . $logoName;
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $logoPath)) {
            $params['logo'] = $logoName;
        }
    }

    // Handle favicon upload
    if (isset($_FILES['favicon']) && $_FILES['favicon']['error'] === UPLOAD_ERR_OK && !empty($_FILES['favicon']['name'])) {
        if (isset($settings['favicon']) && $settings['favicon']) {
            deleteOldFile('../asset/image/' . $settings['favicon']);
        }
        $faviconName = time() . '_favicon_' . $_FILES['favicon']['name'];
        $faviconPath = '../asset/image/' . $faviconName;
        if (move_uploaded_file($_FILES['favicon']['tmp_name'], $faviconPath)) {
            $params['favicon'] = $faviconName;
        }
    }

    $sql = "UPDATE site_settings SET 
            site_name = :site_name, site_description = :site_description, email = :email, phone = :phone, 
            address = :address, city = :city, state = :state, country = :country, facebook_link = :facebook_link, 
            instagram_link = :instagram_link, twitter_link = :twitter_link, whatsapp_link = :whatsapp_link, 
            currency = :currency, flutterwave_public_key = :flutterwave_public_key, flutterwave_secret_key = :flutterwave_secret_key, 
            flutterwave_encryption_key = :flutterwave_encryption_key, flutterwave_mode = :flutterwave_mode, 
            maintenance_mode = :maintenance_mode, smtp_host = :smtp_host, smtp_port = :smtp_port, 
            smtp_user = :smtp_user, smtp_password = :smtp_password, smtp_encryption = :smtp_encryption";
    
    if (isset($params['logo'])) {
        $sql .= ", logo = :logo";
    }
    if (isset($params['favicon'])) {
        $sql .= ", favicon = :favicon";
    }

    $sql .= " WHERE id = 1";

    if(execute($sql, $params) !== false) {
        $message = 'Settings updated successfully!';
        $settings = getSiteSettings(); // Refresh settings
    } else {
        $message = 'Failed to update settings.';
    }
}

$pageTitle = "Site Settings";
?>

<?php include 'header.php'; ?>

            <div class="max-w-7xl mx-auto">
            <div class="max-w-4xl mx-auto">
                 <?php if ($message): ?>
                    <div class="mb-4 p-4 <?php echo strpos($message, 'success') !== false ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?> rounded-lg">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <form action="site-setting.php" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-lg shadow-md">
                    <!-- General Settings -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800">General</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="site_name" class="block text-sm font-medium text-gray-700">Site Name</label>
                                <input type="text" name="site_name" id="site_name" value="<?php echo htmlspecialchars($settings['site_name'] ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Contact Email</label>
                                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($settings['email'] ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                             <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Contact Phone</label>
                                <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($settings['phone'] ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($settings['address'] ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                <input type="text" name="city" id="city" value="<?php echo htmlspecialchars($settings['city'] ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                                <input type="text" name="state" id="state" value="<?php echo htmlspecialchars($settings['state'] ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                                <input type="text" name="country" id="country" value="<?php echo htmlspecialchars($settings['country'] ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="md:col-span-2">
                                <label for="site_description" class="block text-sm font-medium text-gray-700">Site Description</label>
                                <textarea name="site_description" id="site_description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"><?php echo htmlspecialchars($settings['site_description'] ?? ''); ?></textarea>
                            </div>
                            <div>
                                <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                                <input type="file" name="logo" id="logo" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <?php if($settings['logo'] ?? false): ?><img src="../asset/image/<?php echo $settings['logo']; ?>" class="h-16 mt-2"><?php endif; ?>
                            </div>
                            <div>
                                <label for="favicon" class="block text-sm font-medium text-gray-700">Favicon</label>
                                <input type="file" name="favicon" id="favicon" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <?php if($settings['favicon'] ?? false): ?><img src="../asset/image/<?php echo $settings['favicon']; ?>" class="h-16 mt-2"><?php endif; ?>
                            </div>
                             <div class="flex items-center">
                                <input type="checkbox" name="maintenance_mode" id="maintenance_mode" <?php if($settings['maintenance_mode'] ?? false) echo 'checked'; ?> class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="maintenance_mode" class="ml-2 block text-sm text-gray-900">Enable Maintenance Mode</label>
                            </div>
                        </div>
                    </div>

                    <!-- Social Links -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800">Social Media</h3>
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Social link fields here -->
                             <div>
                                <label for="facebook_link" class="block text-sm font-medium text-gray-700">Facebook URL</label>
                                <input type="url" name="facebook_link" id="facebook_link" value="<?php echo htmlspecialchars($settings['facebook_link'] ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                             <div>
                                <label for="instagram_link" class="block text-sm font-medium text-gray-700">Instagram URL</label>
                                <input type="url" name="instagram_link" id="instagram_link" value="<?php echo htmlspecialchars($settings['instagram_link'] ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                             <div>
                                <label for="twitter_link" class="block text-sm font-medium text-gray-700">Twitter URL</label>
                                <input type="url" name="twitter_link" id="twitter_link" value="<?php echo htmlspecialchars($settings['twitter_link'] ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                             <div>
                                <label for="whatsapp_link" aclass="block text-sm font-medium text-gray-700">WhatsApp Link</label>
                                <input type="text" name="whatsapp_link" id="whatsapp_link" value="<?php echo htmlspecialchars($settings['whatsapp_link'] ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Payment Gateway -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800">Payment Gateway (Flutterwave)</h3>
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                           <div>
                                <label for="currency" class="block text-sm font-medium text-gray-700">Currency</label>
                                <input type="text" name="currency" id="currency" value="<?php echo htmlspecialchars($settings['currency'] ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                             <div>
                                <label for="flutterwave_mode" class="block text-sm font-medium text-gray-700">Mode</label>
                                <select name="flutterwave_mode" id="flutterwave_mode" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="test" <?php if(($settings['flutterwave_mode'] ?? '') == 'test') echo 'selected'; ?>>Test</option>
                                    <option value="live" <?php if(($settings['flutterwave_mode'] ?? '') == 'live') echo 'selected'; ?>>Live</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label for="flutterwave_public_key" class="block text-sm font-medium text-gray-700">Public Key</label>
                                <input type="text" name="flutterwave_public_key" id="flutterwave_public_key" value="<?php echo htmlspecialchars($settings['flutterwave_public_key'] ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                             <div class="md:col-span-2">
                                <label for="flutterwave_secret_key" class="block text-sm font-medium text-gray-700">Secret Key</label>
                                <input type="password" name="flutterwave_secret_key" id="flutterwave_secret_key" value="<?php echo htmlspecialchars($settings['flutterwave_secret_key'] ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                              <div class="md:col-span-2">
                                <label for="flutterwave_encryption_key" class="block text-sm font-medium text-gray-700">Encryption Key</label>
                                <input type="password" name="flutterwave_encryption_key" id="flutterwave_encryption_key" value="<?php echo htmlspecialchars($settings['flutterwave_encryption_key'] ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>
                    </div>

                    <!-- SMTP Settings -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800">SMTP Settings</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="smtp_host" class="block text-sm font-medium text-gray-700">SMTP Host</label>
                                <input type="text" name="smtp_host" id="smtp_host" value="<?php echo htmlspecialchars($settings['smtp_host'] ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="smtp_port" class="block text-sm font-medium text-gray-700">SMTP Port</label>
                                <input type="text" name="smtp_port" id="smtp_port" value="<?php echo htmlspecialchars($settings['smtp_port'] ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="smtp_user" class="block text-sm font-medium text-gray-700">SMTP Username</label>
                                <input type="text" name="smtp_user" id="smtp_user" value="<?php echo htmlspecialchars($settings['smtp_user'] ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="smtp_password" class="block text-sm font-medium text-gray-700">SMTP Password</label>
                                <input type="password" name="smtp_password" id="smtp_password" value="<?php echo htmlspecialchars($settings['smtp_password'] ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="smtp_encryption" class="block text-sm font-medium text-gray-700">SMTP Encryption</label>
                                <input type="text" name="smtp_encryption" id="smtp_encryption" value="<?php echo htmlspecialchars($settings['smtp_encryption'] ?? ''); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-md focus:outline-none focus:shadow-outline">
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<?php include 'footer.php'; ?>
