<?php
// public/about.php
require_once '../config/database.php';

// Get company stats if available
$stats_sql = "SELECT COUNT(*) as total_products FROM products WHERE status = 'active'";
$stats_result = $conn->query($stats_sql);
if ($stats_result) {
    $total_products = $stats_result->fetch_assoc()['total_products'];
} else {
    error_log("Database error in about.php: " . $conn->error);
    $total_products = 50; // fallback default
}
?>

<?php include '../includes/header.php'; ?>

<!-- About Header -->
<section class="py-20 bg-gray-900 mt-16">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4" data-en="About BARRA AGENCY DIGITAL" data-id="Tentang BARRA AGENCY DIGITAL">
            About <span class="text-green-500">BARRA</span> AGENCY DIGITAL
        </h1>
        <p class="text-gray-400 max-w-2xl mx-auto" data-en="Project website since 2025" data-id="Projek website sejak tahun 2025">
            Project website since 2025
        </p>
    </div>
</section>

<!-- About Content -->
<section class="py-20 bg-black">
    <div class="container mx-auto px-6">
                <p class="text-gray-400 mb-6" data-en="BARRA AGENCY DIGITAL was founded to deliver innovative digital solutions and exceptional service to our clients." data-id="BARRA AGENCY DIGITAL didirikan untuk memberikan solusi digital inovatif dan layanan terbaik kepada klien kami.">
                    BARRA AGENCY DIGITAL was founded to deliver innovative digital solutions and exceptional service to our clients.
                <h2 class="text-4xl md:text-5xl font-bold mb-8">
                    <span class="text-green-500">Our</span> Story
                </h2>
                <p class="text-gray-400 mb-6" data-en="blank." data-id="Kosong.">
                    blank.
                <div class="flex flex-wrap gap-4 mt-8">
                    <div class="bg-gray-900 px-6 py-4 rounded-lg">
                        <div class="text-green-500 text-3xl font-bold">300+</div>
                        <div class="text-gray-400" data-en="Professional Clients" data-id="Klien Profesional">Professional Clients</div>
                    </div>
                    <div class="bg-gray-900 px-6 py-4 rounded-lg">
                        <div class="text-green-500 text-3xl font-bold">100+</div>
                        <div class="text-gray-400" data-en="Regional Coverage" data-id="Jangkauan Wilayah">Regional Coverage</div>
                    </div>
                    <div class="bg-gray-900 px-6 py-4 rounded-lg">
                        <div class="text-green-500 text-3xl font-bold"><?php echo $total_products; ?>+</div>
                        <div class="text-gray-400" data-en="Products" data-id="Produk">Products</div>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/2 fade-in">
                <div class="grid grid-cols-2 gap-4">
                    <div class="relative h-64 rounded-xl overflow-hidden">
                        <img src="https://lh3.googleusercontent.com/geougc-cs/AB3l90D14OOH49P0eIFI8RCpQDCPRZLfz987Gjb5KhZcBoNS9OoKQWLZdBdKanszkBrhJh6YIQ5eVh0RcapLaDNmh_1xc8tRRqebugUILkY0tZ7rL-t6pKTMNrLS4FZyPFmDkVflpyuoj90VoeDn=w600-h450-p" 
                             alt="Our Factory" 
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-70"></div>
                        <div class="absolute bottom-4 left-4 text-white font-medium" data-en="Our Manufacturing Warehouse" data-id="Gudang Manufaktur Kami">Our Manufacturing Warehouse</div>
                    </div>
                    <div class="relative h-64 rounded-xl overflow-hidden">
                        <img src="https://indonesian.stainlessmetalsheet.com/photo/ps112807814-sus_4x8ft_stainless_steel_plate_316l_321_310s_for_roofing_materials.jpg" 
                             alt="Quality Control" 
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-70"></div>
                        <div class="absolute bottom-4 left-4 text-white font-medium" data-en="Precision Quality" data-id="Kualitas Presisi">Precision Quality</div>
                    </div>
                    <div class="relative h-64 rounded-xl overflow-hidden">
                        <img src="https://www.nagakomodo.co.id/uploads/2025/07/ac9dc3e809a20365fc2c93df3129a26b_ce7163e015450ca0c14e0701f821147a.jpg" 
                             alt="Craftsmanship" 
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-70"></div>
                        <div class="absolute bottom-4 left-4 text-white font-medium" data-en="Innovation" data-id="Inovasi">Innovation</div>
                    </div>
                    <div class="relative h-64 rounded-xl overflow-hidden">
                        <img src="https://www.nagakomodo.co.id/uploads/2025/07/daf07a3b9491fe20ad93cbf80fd08ab0_617bfcf7ae0b41e3c8898738d131dabc.jpg" 
                             alt="Showroom" 
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-70"></div>
                        <div class="absolute bottom-4 left-4 text-white font-medium" data-en="Product Variants" data-id="Varian Produk">Product Variants</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Vision Section -->
<section class="py-20 bg-gray-900">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold mb-4" data-en="Our Vision" data-id="Visi Kami">Our <span class="text-green-500">Vision</span></h2>
        </div>
        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-gray-800 p-8 rounded-xl fade-in">
                <div class="text-green-500 text-4xl font-bold mb-4">01</div>
                <h3 class="text-xl font-bold mb-3" data-en="Being a solution for everyone" data-id="Menjadi solusi untuk semua orang">Being a solution for everyone</h3>
                <p class="text-gray-400" data-en="Empowering businesses and individuals through innovative digital solutions." data-id="Memberdayakan bisnis dan individu melalui solusi digital inovatif.">
                    Empowering businesses and individuals through innovative digital solutions.
                </p>
            </div>
            <div class="bg-gray-800 p-8 rounded-xl fade-in">
                <div class="text-green-500 text-4xl font-bold mb-4">02</div>
                <h3 class="text-xl font-bold mb-3" data-en="Providing uniqueness" data-id="Memberikan keunikan">Providing uniqueness</h3>
                <p class="text-gray-400" data-en="Providing uniqueness with a customer-centric foundation" data-id="Memberikan keunikan dengan pondasi yang berpusat pada pelanggan">
                    Providing uniqueness with a customer-centric foundation
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section class="py-20 bg-black">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold mb-4">Our <span class="text-green-500">Mission</span></h2>
        </div>
        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-gray-800 p-8 rounded-xl fade-in">
                <div class="text-green-500 text-4xl font-bold mb-4">01</div>
                <h3 class="text-xl font-bold mb-3" data-en="Providing the best quality for customers" data-id="Memberikan kualitas terbaik bagi pelanggan">Providing the best quality for customers</h3>
                <p class="text-gray-400" data-en="Providing the best quality for customers with a wide range of the best products available, affordable prices, and maximum convenience." data-id="Memberikan kualitas terbaik bagi pelanggan dengan berbagai macam produk pilihan terbaik yang tersedia, harga yang terjangkau, serta kenyamanan maksimal">
                    Providing the best quality for customers with a wide range of the best products available, affordable prices, and maximum convenience.
                </p>
            </div>
            <div class="bg-gray-800 p-8 rounded-xl fade-in">
                <div class="text-green-500 text-4xl font-bold mb-4">02</div>
                <h3 class="text-xl font-bold mb-3" data-en="Continuous Improvement" data-id="Perbaikan Berkelanjutan">Continuous Improvement</h3>
                <p class="text-gray-400" data-en="We strive to continuously improve our services and products to exceed customer expectations." data-id="Kami berupaya terus meningkatkan layanan dan produk kami untuk melampaui harapan pelanggan.">
                    We strive to continuously improve our services and products to exceed customer expectations.
                </p>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>