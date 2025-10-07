<?php
// public/contact.php
require_once '../config/database.php';

// Ensure $conn is initialized from database.php
if (!isset($conn)) {
    // Try initializing $conn if database.php returns a connection variable
    if (function_exists('getDbConnection')) {
        $conn = getDbConnection();
    } elseif (isset($db) && $db instanceof mysqli) {
        $conn = $db;
    } else {
        die('Database connection not established.');
    }
}

$success = '';
$error = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Save to database
        $stmt = $conn->prepare("INSERT INTO contacts (name, email, phone, subject, message, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);

        if ($stmt->execute()) {
            $success = "Thank you for your message! We'll get back to you soon.";
        } else {
            $error = "Sorry, there was an error sending your message. Please try again.";
        }
    }
}
?>

<?php include '../includes/header.php'; ?>

<!-- Contact Header -->
<section class="py-20 bg-gray-900 mt-16">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4" data-en="Contact Us" data-id="Hubungi Kami">Contact <span class="text-green-500">Us</span></h1>
        <p class="text-gray-400 max-w-2xl mx-auto" data-en="Get in touch with our team for any inquiries about our products or services" data-id="Hubungi tim kami untuk pertanyaan tentang produk atau layanan kami">
            Get in touch with our team for any inquiries about our products or services
        </p>
    </div>
</section>

<!-- Contact Section -->
<section class="py-20 bg-black">
    <div class="container mx-auto px-6">
        <div class="flex flex-col lg:flex-row">
            <div class="lg:w-1/2 mb-12 lg:mb-0 lg:pr-12 fade-in">
                <h2 class="text-4xl md:text-5xl font-bold mb-8" data-en="Let's Collaborate" data-id="Mari Berkolaborasi">Let's Collaborate</h2>
                <p class="text-gray-400 mb-8 max-w-lg" data-en="Blank." data-id="Kosong.">
                    Kosong.
                </p>
                
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="text-green-500 text-xl mr-4 mt-1">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h4 class="font-bold mb-1" data-en="Address" data-id="Alamat">Address</h4>
                            <p class="text-gray-400">Sepatan Timur, Tangerang</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="text-green-500 text-xl mr-4 mt-1">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div>
                            <h4 class="font-bold mb-1" data-en="Contact" data-id="Kontak">Contact</h4>
                            <p class="text-gray-400">021-123-4567<br>shellanonym@gmail.com</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="text-green-500 text-xl mr-4 mt-1">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h4 class="font-bold mb-1" data-en="Business Hours" data-id="Jam Operasional">Business Hours</h4>
                            <p class="text-gray-400" data-en="Monday - Friday: 8:00 AM - 5:00 PM" data-id="Senin - Jumat: 08:00 - 17:00">Monday - Friday: 8:00 AM - 5:00 PM</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 flex space-x-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-green-500 hover:bg-green-500 hover:text-black transition duration-300">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-green-500 hover:bg-green-500 hover:text-black transition duration-300">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-green-500 hover:bg-green-500 hover:text-black transition duration-300">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-green-500 hover:bg-green-500 hover:text-black transition duration-300">
                        <i class="fab fa-tiktok"></i>
                    </a>
                </div>
            </div>
            
            <div class="lg:w-1/2 fade-in">
                <?php if ($success): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="" class="bg-gray-800 p-8 rounded-xl">
                    <div class="mb-6">
                        <label for="name" class="block text-gray-300 mb-2" data-en="Full Name" data-id="Nama Lengkap">Full Name *</label>
                        <input type="text" id="name" name="name" required 
                               value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>"
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-white">
                    </div>
                    <div class="mb-6">
                        <label for="email" class="block text-gray-300 mb-2" data-en="Email Address" data-id="Alamat Email">Email Address *</label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-white">
                    </div>
                    <div class="mb-6">
                        <label for="phone" class="block text-gray-300 mb-2" data-en="Phone Number" data-id="Nomor Telepon">Phone Number</label>
                        <input type="tel" id="phone" name="phone" 
                               value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-white">
                    </div>
                    <div class="mb-6">
                        <label for="subject" class="block text-gray-300 mb-2" data-en="Subject" data-id="Subjek">Subject</label>
                        <select id="subject" name="subject" class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-white">
                            <option value="" data-en="Select a subject" data-id="Pilih subjek">Select a subject</option>
                            <option value="product" <?php echo ($_POST['subject'] ?? '') == 'product' ? 'selected' : ''; ?> data-en="Product Inquiry" data-id="Pertanyaan Produk">Product Inquiry</option>
                            <option value="wholesale" <?php echo ($_POST['subject'] ?? '') == 'wholesale' ? 'selected' : ''; ?> data-en="Wholesale Inquiry" data-id="Pertanyaan Grosir">Wholesale Inquiry</option>
                            <option value="custom" <?php echo ($_POST['subject'] ?? '') == 'custom' ? 'selected' : ''; ?> data-en="Custom Order" data-id="Pesanan Kustom">Custom Order</option>
                            <option value="other" <?php echo ($_POST['subject'] ?? '') == 'other' ? 'selected' : ''; ?> data-en="Other" data-id="Lainnya">Other</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label for="message" class="block text-gray-300 mb-2" data-en="Your Message" data-id="Pesan Anda">Your Message *</label>
                        <textarea id="message" name="message" rows="4" required class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-white"><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                    </div>
                    <button type="submit" class="w-full px-6 py-3 bg-green-600 text-black font-medium rounded-lg hover:bg-green-500 transition duration-300 flex items-center justify-center">
                        <span id="submit-text" data-en="Send Message" data-id="Kirim Pesan">Send Message</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-20 bg-gray-900">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold mb-4" data-en="Find Us" data-id="Temukan Kami">Find <span class="text-green-500">Us</span></h2>
        </div>
        <div class="bg-gray-800 rounded-xl p-8">
            <div class="h-96 bg-gray-700 rounded-lg flex items-center justify-center">
                <div class="text-center">
                    <i class="fas fa-map-marked-alt text-green-500 text-6xl mb-4"></i>
                    <p class="text-gray-400" data-en="Interactive map coming soon" data-id="Peta interaktif segera hadir">Interactive map coming soon</p>
                    <p class="text-gray-500 mt-2">Sepatan Timur, Tangerang</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>