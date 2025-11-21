<footer class="bg-gradient-to-r from-green-800 via-green-900 to-emerald-900 text-white">
    <div class="container mx-auto px-6 py-14">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

            <!-- Logo & Deskripsi -->
            <div class="space-y-5">
                <div class="flex items-center space-x-3">
                    <div class="bg-white text-green-800 w-12 h-12 rounded-full flex items-center justify-center font-extrabold text-2xl shadow-md">SZ</div>
                    <span class="text-2xl font-extrabold tracking-wide">SIPZIS</span>
                </div>
                <p class="text-green-100 leading-relaxed text-sm">
                    Platform digital zakat, infaq, dan sedekah yang transparan, aman, dan sesuai syariat Islam.
                </p>
                <div class="flex space-x-4 pt-2">
                    <a href="#" class="text-green-300 hover:text-white transition-colors" aria-label="Facebook"><i class="fab fa-facebook-f text-lg"></i></a>
                    <a href="#" class="text-green-300 hover:text-white transition-colors" aria-label="Twitter"><i class="fab fa-twitter text-lg"></i></a>
                    <a href="#" class="text-green-300 hover:text-white transition-colors" aria-label="Instagram"><i class="fab fa-instagram text-lg"></i></a>
                    <a href="#" class="text-green-300 hover:text-white transition-colors" aria-label="YouTube"><i class="fab fa-youtube text-lg"></i></a>
                </div>
            </div>

            <!-- Tautan Cepat -->

            <div>
                <h3 class="text-lg font-bold mb-5 border-b border-green-700 pb-2 uppercase tracking-wide">Tautan Cepat</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-green-200 hover:text-white transition-colors">Beranda</a></li>
                    <li><a href="{{ route('program', ['tab' => 'infaq']) }}" class="text-green-200 hover:text-white transition-colors">Program Infaq</a></li>
                    <li><a href="{{ route('program', ['tab' => 'zakat']) }}" class="text-green-200 hover:text-white transition-colors">Program Zakat</a></li>
                    <li><a href="{{ route('program', ['tab' => 'shadaqah']) }}" class="text-green-200 hover:text-white transition-colors">Program Shadaqah</a></li>
                    <li><a href="{{ route('program', ['tab' => 'pilar']) }}" class="text-green-200 hover:text-white transition-colors">Program Pilar</a></li>
                    <li><a href="{{ route('news.all') }}" class="text-green-200 hover:text-white transition-colors">Berita</a></li>
                    <li><a href="{{ route('artikel.index') }}" class="text-green-200 hover:text-white transition-colors">Artikel</a></li>
                    <li><a href="{{ route('calculator.index') }}" class="text-green-200 hover:text-white transition-colors">Kalkulator Zakat</a></li>
                    <!-- <li><a href="{{ route('guest.payment.create') }}" class="text-green-200 hover:text-white transition-colors">Bayar Zakat</a></li> -->
                </ul>
            </div>

            <!-- Kontak Kami -->
            <div>
                <h3 class="text-lg font-bold mb-5 border-b border-green-700 pb-2 uppercase tracking-wide">Kontak Kami</h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start">
                        <i class="fas fa-map-marker-alt mt-1 mr-3 text-green-300"></i>
                        <span class="text-green-100">Jl. Kiai Jurum No. 002, Cipocok Jaya, Komplek Depag, Serang, Banten 42121, Indonesia</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-phone-alt mr-3 text-green-300"></i>
                        <span class="text-green-100">(0254) 221179</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-envelope mr-3 text-green-300"></i>
                        <span class="text-green-100">info@sipzis.or.id</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-clock mr-3 text-green-300"></i>
                        <span class="text-green-100">Senin - Jumat: 08.00 - 17.00 WIB</span>
                    </li>
                </ul>
            </div>

            <!-- Tentang -->
            <div>
                <h3 class="text-lg font-bold mb-5 border-b border-green-700 pb-2 uppercase tracking-wide">Tentang</h3>
                <p class="text-green-100 text-sm leading-relaxed">
                    SIPZIS berkomitmen untuk memudahkan umat dalam berzakat, berinfaq, dan bersedekah secara digital dengan penuh tanggung jawab dan transparansi.
                </p>
            </div>
        </div>

        <div class="border-t border-green-700 mt-12 pt-6 text-center">
            <p class="text-green-300 text-sm">
                &copy; {{ date('Y') }} <span class="font-semibold text-white">Created By : </span> — Kurniawan Dwi Prasetyo<br>
                <span class="text-green-400">Hak Cipta Dilindungi.</span> 
            </p>
        </div>
    </div>
</footer>