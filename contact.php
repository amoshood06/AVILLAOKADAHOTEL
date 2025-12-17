<?php
$title = "Contact Us";
require_once 'header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // A simple mail function, for a real application use a library like PHPMailer
    $to = $email;
    $from = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $name = htmlspecialchars($_POST['name']);
    $subject = "Contact Form Submission from " . $name;
    $body = htmlspecialchars($_POST['message']);
    $headers = "From: " . $from;

    if (mail($to, $subject, $body, $headers)) {
        $message = "Your message has been sent successfully!";
    } else {
        $message = "Failed to send message. Please try again later.";
    }
}
?>

    <main class="py-16">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-serif">Get in Touch</h1>
                <p class="text-gray-600 mt-2">We'd love to hear from you. Here's how you can reach us.</p>
            </div>

            <?php if ($message): ?>
                <div class="mb-8 p-4 text-center <?php echo strpos($message, 'success') !== false ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?> rounded-lg">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div class="flex flex-wrap -mx-4">
                <div class="w-full lg:w-1/2 px-4 mb-8 lg:mb-0">
                    <div class="bg-white p-8 rounded-lg shadow-lg">
                        <h2 class="text-2xl font-semibold mb-6">Contact Form</h2>
                        <form action="contact.php" method="POST">
                            <div class="mb-4">
                                <label for="name" class="block text-gray-700 font-medium mb-2">Your Name</label>
                                <input type="text" id="name" name="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div class="mb-4">
                                <label for="email" class="block text-gray-700 font-medium mb-2">Your Email</label>
                                <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div class="mb-6">
                                <label for="message" class="block text-gray-700 font-medium mb-2">Message</label>
                                <textarea id="message" name="message" rows="5" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                            </div>
                            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition font-semibold">Send Message</button>
                        </form>
                    </div>
                </div>
                <div class="w-full lg:w-1/2 px-4">
                     <div class="bg-white p-8 rounded-lg shadow-lg h-full">
                        <h2 class="text-2xl font-semibold mb-6">Contact Information</h2>
                        <ul class="space-y-4 text-gray-700">
                            <li class="flex items-start">
                                <i class="fas fa-map-marker-alt fa-fw text-blue-500 mt-1 mr-3"></i>
                                <span><?php echo htmlspecialchars($address); ?></span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-phone-alt fa-fw text-blue-500 mr-3"></i>
                                <span><?php echo htmlspecialchars($phone); ?></span>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-envelope fa-fw text-blue-500 mr-3"></i>
                                <a href="mailto:<?php echo htmlspecialchars($email); ?>" class="hover:text-blue-600"><?php echo htmlspecialchars($email); ?></a>
                            </li>
                        </ul>
                         <div class="mt-8">
                             <h3 class="text-lg font-semibold mb-4">Follow Us</h3>
                             <div class="flex space-x-4 text-2xl text-gray-600">
                                 <?php if (!empty($facebook_link)): ?>
                                    <a href="<?php echo htmlspecialchars($facebook_link); ?>" target="_blank" class="hover:text-blue-600"><i class="fab fa-facebook-f"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($instagram_link)): ?>
                                    <a href="<?php echo htmlspecialchars($instagram_link); ?>" target="_blank" class="hover:text-pink-600"><i class="fab fa-instagram"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($twitter_link)): ?>
                                    <a href="<?php echo htmlspecialchars($twitter_link); ?>" target="_blank" class="hover:text-sky-500"><i class="fab fa-twitter"></i></a>
                                <?php endif; ?>
                                 <?php if (!empty($whatsapp_link)): ?>
                                    <a href="<?php echo htmlspecialchars($whatsapp_link); ?>" target="_blank" class="hover:text-green-500"><i class="fab fa-whatsapp"></i></a>
                                <?php endif; ?>
                             </div>
                         </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php require_once 'footer.php'; ?>