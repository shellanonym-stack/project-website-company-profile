<?php
/**
 * Frontend Contact Page
 * PT Komodo Industrial Indonesia
 */

define('APP_ACCESS', true);

// Load configurations
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

// Handle form submission
if (isPost()) {
    $full_name = post('full_name');
    $email = post('email');
    $phone = post('phone', '');
    $subject = post('subject');
    $message = post('message');

    $errors = [];

    // Validation
    if (empty($full_name)) {
        $errors[] = 'Full name is required';
    }

    if (empty($email) || !isValidEmail($email)) {
        $errors[] = 'Valid email is required';
    }

    if (empty($subject)) {
        $errors[] = 'Subject is required';
    }

    if (empty($message)) {
        $errors[] = 'Message is required';
    }

    if (empty($errors)) {
        $sql = "INSERT INTO contacts (full_name, email, phone, subject, message, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        $params = [$full_name, $email, $phone, $subject, $message];

        if ($db->query($sql, $params)) {
            setFlash('success', 'Your message has been sent successfully! We will get back to you soon.');
            redirect('contact.php');
        } else {
            $errors[] = 'Failed to send message. Please try again.';
        }
    }
}

$pageTitle = 'Contact Us';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> | <?php echo $pageTitle; ?></title>
    <meta name="description" content="Get in touch with PT Komodo Industrial Indonesia for inquiries and support.">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #000000;
            color: #ffffff;
        }
    </style>
</head>
<body class="antialiased">
    <!-- Navigation -->
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <!-- Main Content -->
    <div class="pt-20 min-h-screen">
        <!-- Hero Section -->
        <section class="bg-gray-900 py-20">
            <div class="container mx-auto px-6">
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-bold mb-4">Contact <span class="text-green-500">Us</span></h1>
                    <p class="text-gray-400 max-w-2xl mx-auto text-lg">
                        Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.
                    </p>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="py-20">
            <div class="container mx-auto px-6">
                <div class="max-w-6xl mx-auto">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        <!-- Contact Form -->
                        <div class="bg-gray-800 rounded-2xl p-8">
                            <h2 class="text-2xl font-bold mb-6">Send us a Message</h2>

                            <?php displayFlash(); ?>

                            <?php if (!empty($errors)): ?>
                                <div class="bg-red-500 bg-opacity-10 border border-red-500 text-red-500 rounded-lg p-4 mb-6">
                                    <div class="flex items-start">
                                        <i class="fas fa-exclamation-circle mt-0.5 mr-3"></i>
                                        <div>
                                            <?php foreach ($errors as $error): ?>
                                                <p><?php echo htmlspecialchars($error); ?></p>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="full_name" class="block text-sm font-medium text-gray-300 mb-2">Full Name *</label>
                                        <input type="text" 
                                               id="full_name" 
                                               name="full_name" 
                                               value="<?php echo htmlspecialchars(post('full_name', '')); ?>"
                                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-white"
                                               required>
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email *</label>
                                        <input type="email" 
                                               id="email" 
                                               name="email" 
                                               value="<?php echo htmlspecialchars(post('email', '')); ?>"
                                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-white"
                                               required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">Phone Number</label>
                                    <input type="tel" 
                                           id="phone" 
                                           name="phone" 
                                           value="<?php echo htmlspecialchars(post('phone', '')); ?>"
                                           class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-white">
                                </div>

                                <div class="mb-4">
                                    <label for="subject" class="block text-sm font-medium text-gray-300 mb-2">Subject *</label>
                                    <select id="subject" 
                                            name="subject" 
                                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-white"
                                            required>
                                        <option value="">Select Subject</option>
                                        <?php foreach (CONTACT_SUBJECTS as $key => $value): ?>
                                            <option value="<?php echo $key; ?>" <?php echo post('subject') === $key ? 'selected' : ''; ?>>
                                                <?php echo $value['en']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-6">
                                    <label for="message" class="block text-sm font-medium text-gray-300 mb-2">Message *</label>
                                    <textarea id="message" 
                                              name="message" 
                                              rows="5"
                                              class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-white"
                                              required><?php echo htmlspecialchars(post('message', '')); ?></textarea>
                                </div>

                                <button type="submit" 
                                        class="w-full px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition duration-200">
                                    Send Message
                                </button>
                            </form>
                        </div>

                        <!-- Contact Information -->
                        <div class="bg-gray-800 rounded-2xl p-8">
                            <h2 class="text-2xl font-bold mb-6">Get in Touch</h2>
                            
                            <div class="space-y-6">
                                <div class="flex items-start">
                                    <div class="bg-green-600 p-3 rounded-lg mr-4">
                                        <i class="fas fa-map-marker-alt text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold mb-1">Address</h3>
                                        <p class="text-gray-400">Serang, Banten, Indonesia</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="bg-green-600 p-3 rounded-lg mr-4">
                                        <i class="fas fa-phone text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold mb-1">Phone</h3>
                                        <p class="text-gray-400">+62 812 3456 7890</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="bg-green-600 p-3 rounded-lg mr-4">
                                        <i class="fas fa-envelope text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold mb-1">Email</h3>
                                        <p class="text-gray-400">info@komodoindustrial.com</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="bg-green-600 p-3 rounded-lg mr-4">
                                        <i class="fas fa-clock text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold mb-1">Business Hours</h3>
                                        <p class="text-gray-400">Monday - Friday: 8:00 AM - 5:00 PM</p>
                                        <p class="text-gray-400">Saturday: 8:00 AM - 12:00 PM</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>