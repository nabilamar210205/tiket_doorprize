<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Booking Tiket Doorprize HUT RI - Karang Taruna RW 004</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        agustus: {
                            red: '#E11D48',
                            redDark: '#9F1239',
                            white: '#FFFFFF',
                            gold: '#F59E0B',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- AlpineJS for instant reactive interactions -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .merah-putih-gradient {
            background: linear-gradient(135deg, #BE123C 0%, #E11D48 50%, #FFFFFF 100%);
        }
        .hero-pattern {
            background-color: #BE123C;
            background-image: radial-gradient(rgba(255, 255, 255, 0.15) 1px, transparent 0),
                              radial-gradient(rgba(255, 255, 255, 0.15) 1px, transparent 0);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
        }
        /* Custom scrollbar for ticket grid */
        .ticket-scroll::-webkit-scrollbar {
            width: 8px;
        }
        .ticket-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .ticket-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .ticket-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased min-h-screen pb-16" x-data="bookingApp()">

    <!-- TOP FLAG BANNER (MERAH PUTIH ELEGAN) -->
    <div class="h-2 w-full bg-gradient-to-r from-red-600 via-white to-red-600 sticky top-0 z-50 shadow-md"></div>

    <!-- MAIN HEADER & HERO -->
    <header class="hero-pattern text-white py-12 px-4 relative overflow-hidden shadow-lg">
        <!-- Background Decorative SVG Flags -->
        <div class="absolute inset-0 opacity-10 pointer-events-none flex justify-between items-center px-10">
            <i class="fa-solid fa-flag text-9xl rotate-12"></i>
            <i class="fa-solid fa-star text-9xl"></i>
            <i class="fa-solid fa-flag text-9xl -rotate-12"></i>
        </div>

        <div class="max-w-6xl mx-auto text-center relative z-10">
            <!-- Logo Karang Taruna -->
            <div class="flex justify-center mb-5">
                <div class="w-20 h-20 rounded-2xl bg-white/20 border-2 border-white/30 backdrop-blur flex items-center justify-center shadow-xl overflow-hidden p-1.5 bg-slate-900">
                    <img src="{{ asset('logo.jpg') }}" alt="Logo Karang Taruna" class="w-full h-full object-contain rounded-xl">
                </div>
            </div>

            <!-- Badge Karang Taruna -->
            <div class="inline-flex items-center gap-2 bg-rose-900/50 backdrop-blur-md px-4 py-1.5 rounded-full border border-rose-500/30 text-rose-100 text-xs font-semibold tracking-wider uppercase mb-4">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Karang Taruna RW 004
            </div>

            <!-- Title -->
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-outfit font-black tracking-tight mb-2">
                DIRGAHAYU <span class="text-yellow-400">REPUBLIK INDONESIA</span>
            </h1>
            <p class="text-lg md:text-xl text-rose-100 font-light max-w-2xl mx-auto mb-6">
                Sistem Booking Tiket Doorprize Jalan Sehat & Semarak Kemerdekaan RI ke-81
            </p>

            <!-- Countdown Timer to 17 Agustus -->
            <div class="inline-flex flex-wrap items-center justify-center gap-3 bg-black/30 backdrop-blur-md p-3 rounded-2xl border border-white/10" x-data="countdownTimer()">
                <span class="text-xs font-bold uppercase tracking-wider text-rose-200 px-2">HUT RI Ke-81 :</span>
                <div class="flex gap-2 text-sm font-mono font-bold">
                    <div class="bg-rose-600 px-2.5 py-1 rounded-lg text-white" x-text="days">00</div>
                    <span class="text-rose-300 py-1">:</span>
                    <div class="bg-rose-600 px-2.5 py-1 rounded-lg text-white" x-text="hours">00</div>
                    <span class="text-rose-300 py-1">:</span>
                    <div class="bg-rose-600 px-2.5 py-1 rounded-lg text-white" x-text="minutes">00</div>
                    <span class="text-rose-300 py-1">:</span>
                    <div class="bg-rose-600 px-2.5 py-1 rounded-lg text-white" x-text="seconds">00</div>
                </div>
            </div>

            <!-- Admin Shortcut Button -->
            <a href="{{ route('admin.index') }}" class="absolute top-2 right-2 text-white/50 hover:text-white transition bg-white/10 hover:bg-white/20 p-2 rounded-full text-xs">
                <i class="fa-solid fa-gear"></i> Panitia
            </a>
        </div>
    </header>

    <!-- LIVE STATS STRIP -->
    <section class="max-w-6xl mx-auto -mt-8 px-4 relative z-20">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Stat 1 -->
            <div class="bg-white rounded-2xl p-5 shadow-xl border border-slate-100 flex items-center gap-4 transform hover:scale-102 transition duration-300">
                <div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600 text-xl font-bold">
                    <i class="fa-solid fa-ticket"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Tiket</p>
                    <h3 class="text-2xl font-black font-outfit text-slate-800" x-text="stats.total">5000</h3>
                </div>
            </div>

            <!-- Stat 2 -->
            <div class="bg-white rounded-2xl p-5 shadow-xl border border-slate-100 flex items-center gap-4 transform hover:scale-102 transition duration-300">
                <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 text-xl font-bold">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Terbooking</p>
                    <h3 class="text-2xl font-black font-outfit text-slate-800" x-text="stats.booked">{{ $bookedCount }}</h3>
                </div>
            </div>

            <!-- Stat 3 -->
            <div class="bg-white rounded-2xl p-5 shadow-xl border border-slate-100 flex items-center gap-4 transform hover:scale-102 transition duration-300">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 text-xl font-bold">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Terkonfirmasi</p>
                    <h3 class="text-2xl font-black font-outfit text-slate-800" x-text="stats.approved">{{ $approvedCount }}</h3>
                </div>
            </div>

            <!-- Stat 4 -->
            <div class="bg-white rounded-2xl p-5 shadow-xl border border-slate-100 flex items-center gap-4 transform hover:scale-102 transition duration-300">
                <div class="w-12 h-12 rounded-xl bg-sky-50 flex items-center justify-center text-sky-600 text-xl font-bold">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Sisa Tiket</p>
                    <h3 class="text-2xl font-black font-outfit text-slate-800" x-text="stats.available">{{ $availableCount }}</h3>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN INTERACTIVE BOOKING CONTAINER -->
    <main class="max-w-6xl mx-auto mt-10 px-4">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- LEFT COLUMN: BOOKING FORM -->
            <div class="lg:col-span-5 bg-white rounded-3xl p-6 md:p-8 shadow-xl border border-slate-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-rose-500/5 rounded-full -mr-8 -mt-8"></div>
                
                <h2 class="text-2xl font-outfit font-extrabold text-slate-800 mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-rose-600 flex items-center justify-center text-white text-xs"><i class="fa-solid fa-clipboard-list"></i></span>
                    Formulir Pendaftaran
                </h2>

                <form @submit.prevent="submitBooking" id="bookingForm" class="space-y-5">
                    <!-- Name Input -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-600 mb-1.5">Nama Lengkap Pemesan <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <input type="text" id="name" x-model="form.name" required
                                   class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 outline-none transition"
                                   placeholder="Contoh: Budi Santoso">
                        </div>
                    </div>

                    <!-- WhatsApp Input -->
                    <div>
                        <label for="whatsapp" class="block text-sm font-semibold text-slate-600 mb-1.5">Nomor WhatsApp Aktif <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <i class="fa-brands fa-whatsapp font-bold text-lg"></i>
                            </span>
                            <input type="tel" id="whatsapp" x-model="form.whatsapp" required
                                   class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 outline-none transition"
                                   placeholder="Contoh: 08123456789">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1"><i class="fa-solid fa-circle-info mr-1"></i>Digunakan untuk konfirmasi dan pengiriman e-tiket.</p>
                    </div>

                    <!-- RT/RW Dropdown/Input -->
                    <div>
                        <label for="rt_rw" class="block text-sm font-semibold text-slate-600 mb-1.5">RT / RW Tinggal <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <i class="fa-solid fa-house-chimney"></i>
                            </span>
                            <select id="rt_rw" x-model="form.rt_rw" required
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 outline-none transition bg-white cursor-pointer">
                                <option value="" disabled selected>Pilih RT / RW</option>
                                <option value="RT 11 / RW 004">RT 11 / RW 004</option>
                                <option value="RT 12 / RW 004">RT 12 / RW 004</option>
                                <option value="RT 13 / RW 004">RT 13 / RW 004</option>
                                <option value="RT 14 / RW 004">RT 14 / RW 004</option>
                                <option value="RT 15 / RW 004">RT 5 / RW 004</option>
                                <option value="Warga Luar RW">Warga Luar RW</option>
                            </select>
                        </div>
                    </div>

                    <!-- Booking Summary Panel -->
                    <div class="bg-rose-50/50 border border-rose-100 rounded-2xl p-4 mt-6">
                        <h4 class="text-xs font-bold text-rose-800 uppercase tracking-wider mb-2">Ringkasan Pemesanan</h4>
                        <div class="space-y-1.5 text-sm text-slate-600">
                            <div class="flex justify-between">
                                <span>Metode Pemilihan:</span>
                                <span class="font-semibold text-slate-700" x-text="form.booking_type === 'manual' ? 'Pilih Manual' : 'Acak/Cepat'">Pilih Manual</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span>Nomor Tiket Terpilih:</span>
                                <div class="text-right">
                                    <template x-if="form.booking_type === 'manual'">
                                        <div class="flex flex-wrap gap-1 justify-end max-w-[200px]">
                                            <template x-for="t in selectedTickets" :key="t">
                                                <span class="bg-rose-600 text-white text-xs font-bold px-2 py-0.5 rounded-lg" x-text="t">001</span>
                                            </template>
                                            <template x-if="selectedTickets.length === 0">
                                                <span class="text-slate-400 italic">Belum ada</span>
                                            </template>
                                        </div>
                                    </template>
                                    <template x-if="form.booking_type === 'random'">
                                        <span class="font-bold text-rose-600" x-text="form.ticket_qty + ' Tiket Acak'">1 Tiket</span>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" :disabled="submitting || (form.booking_type === 'manual' && selectedTickets.length === 0)"
                            class="w-full py-4 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 disabled:from-slate-300 disabled:to-slate-300 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 flex items-center justify-center gap-2 cursor-pointer disabled:cursor-not-allowed">
                        <template x-if="submitting">
                            <i class="fa-solid fa-circle-notch animate-spin text-lg"></i>
                        </template>
                        <template x-if="!submitting">
                            <i class="fa-solid fa-paper-plane"></i>
                        </template>
                        <span x-text="submitting ? 'Memproses Booking...' : 'Ajukan Pendaftaran Tiket 🇮🇩'">Ajukan Pendaftaran Tiket 🇮🇩</span>
                    </button>
                </form>
            </div>

            <!-- RIGHT COLUMN: TICKET SELECTOR -->
            <div class="lg:col-span-7 bg-white rounded-3xl p-6 md:p-8 shadow-xl border border-slate-100 flex flex-col">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <h2 class="text-2xl font-outfit font-extrabold text-slate-800 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-rose-600 flex items-center justify-center text-white text-xs"><i class="fa-solid fa-gamepad"></i></span>
                        Pilih Nomor Tiket Doorprize
                    </h2>
                    
                    <!-- Booking Type Switcher -->
                    <div class="bg-slate-100 p-1 rounded-xl inline-flex">
                        <button type="button" @click="setBookingType('manual')" 
                                :class="form.booking_type === 'manual' ? 'bg-white text-rose-600 shadow-md font-bold' : 'text-slate-500'"
                                class="px-3.5 py-1.5 rounded-lg text-xs transition duration-200 cursor-pointer">
                            🎯 Pilih Manual
                        </button>
                        <button type="button" @click="setBookingType('random')" 
                                :class="form.booking_type === 'random' ? 'bg-white text-rose-600 shadow-md font-bold' : 'text-slate-500'"
                                class="px-3.5 py-1.5 rounded-lg text-xs transition duration-200 cursor-pointer">
                            🎲 Acak Cepat
                        </button>
                    </div>
                </div>

                <!-- MODE A: MANUAL TICKET GRID SELECTOR -->
                <div x-show="form.booking_type === 'manual'" class="flex-1 flex flex-col">
                    <!-- Grid Filters and Helpers -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                        <!-- Search Ticket -->
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            </span>
                            <input type="text" x-model="searchGridQuery"
                                   class="w-full pl-8 pr-3 py-2 rounded-xl border border-slate-200 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 outline-none text-xs transition"
                                   placeholder="Cari nomor tiket hoki kamu...">
                        </div>
                        
                        <!-- Info Colors Legend -->
                        <div class="flex items-center justify-end gap-3 text-[11px] font-semibold text-slate-500">
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-md bg-slate-100 border border-slate-200"></span> Tersedia
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-md bg-amber-500"></span> Pending
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-md bg-rose-600"></span> Approved
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-md bg-yellow-400"></span> Terpilih
                            </div>
                        </div>
                    </div>

                    <!-- Limit Indicator -->
                    <div class="flex justify-between items-center bg-rose-50 text-rose-800 text-xs px-4 py-2 rounded-xl mb-4 border border-rose-100/50">
                        <span><i class="fa-solid fa-circle-exclamation mr-1.5"></i>Maksimal pilih <strong>20 tiket</strong> per transaksi</span>
                        <span class="font-bold bg-rose-600 text-white rounded-full px-2 py-0.5" x-text="selectedTickets.length + ' / 20'">0 / 20</span>
                    </div>

                    <!-- Scrollable Grid Wrapper -->
                    <div class="border border-slate-150 rounded-2xl p-4 bg-slate-50/50 flex-1 max-h-[360px] overflow-y-auto ticket-scroll">
                        <div class="grid grid-cols-5 sm:grid-cols-8 md:grid-cols-10 gap-2">
                            <template x-for="num in filteredTickets()" :key="num">
                                <button type="button" 
                                        @click="toggleTicket(num)"
                                        :disabled="isBooked(num) && !isCurrentlySelected(num)"
                                        :class="getTicketClass(num)"
                                        class="aspect-square flex flex-col items-center justify-center rounded-xl text-sm font-bold border transition-all duration-200 transform active:scale-95 cursor-pointer disabled:cursor-not-allowed">
                                    <span x-text="num">001</span>
                                    <template x-if="isBooked(num)">
                                        <span class="text-[8px] opacity-80" x-text="bookedTickets[num] === 'approved' ? '✓' : '...' "></span>
                                    </template>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- MODE B: QUICK RANDOM SELECTOR -->
                <div x-show="form.booking_type === 'random'" class="flex-1 flex flex-col justify-center items-center py-8 text-center space-y-6">
                    <div class="w-24 h-24 bg-rose-50 rounded-full flex items-center justify-center text-rose-600 text-4xl animate-bounce">
                        <i class="fa-solid fa-dice"></i>
                    </div>
                    
                    <div class="max-w-md">
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Ingin Nomor Keberuntungan Acak?</h3>
                        <p class="text-sm text-slate-500">Tentukan berapa tiket yang ingin Anda pesan, dan sistem cerdas kami akan mencarikan nomor keberuntungan terbaik yang masih kosong untuk Anda.</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-sm font-semibold text-slate-600">Jumlah Tiket:</span>
                        <div class="flex gap-2">
                            <template x-for="qty in [1, 2, 5, 10, 15, 20]">
                                <button type="button" @click="form.ticket_qty = qty"
                                        :class="form.ticket_qty === qty ? 'bg-rose-600 text-white font-bold border-rose-600 shadow-md' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50'"
                                        class="w-10 h-10 rounded-xl border flex items-center justify-center text-sm font-semibold transition cursor-pointer"
                                        x-text="qty">
                                    1
                                </button>
                            </template>
                        </div>
                    </div>

                    <div class="bg-amber-50 border border-amber-100 rounded-xl px-4 py-3 max-w-sm flex items-center gap-2 text-left text-xs text-amber-800">
                        <i class="fa-solid fa-circle-info text-base shrink-0"></i>
                        <span>Sistem akan langsung menampilkan nomor tiket acak yang didapatkan setelah formulir dikirim.</span>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <!-- LIVE SEARCH / TICKET LOOKUP -->
    <section class="max-w-6xl mx-auto mt-16 px-4">
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-xl border border-slate-100">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-outfit font-black text-slate-800 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-rose-600 flex items-center justify-center text-white text-xs"><i class="fa-solid fa-search"></i></span>
                        Cari Tiket Pemesanan Warga
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">Cari berdasarkan Nama, RT/RW, No WA, atau Nomor Tiket untuk mengecek status konfirmasi kupon Anda.</p>
                </div>

                <div class="relative max-w-md w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" x-model="searchQuery" @input.debounce.300ms="searchTickets"
                           class="w-full pl-10 pr-4 py-3 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 outline-none transition text-sm"
                           placeholder="Ketik Nama Anda, RT, atau Nomor Tiket...">
                </div>
            </div>

            <!-- Search Results -->
            <div class="overflow-x-auto rounded-2xl border border-slate-100">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider text-xs font-bold">
                        <tr>
                            <th class="px-6 py-4">Nomor Tiket</th>
                            <th class="px-6 py-4">Nama Pemesan</th>
                            <th class="px-6 py-4">RT / RW</th>
                            <th class="px-6 py-4">Tanggal Booking</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        <!-- If loading/initial -->
                        <template x-if="searchResults.length === 0">
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-400 italic">
                                    <i class="fa-solid fa-search text-2xl mb-2 block"></i>
                                    Masukkan kata kunci di atas untuk mencari tiket.
                                </td>
                            </tr>
                        </template>
                        
                        <!-- Loop results -->
                        <template x-for="ticket in searchResults" :key="ticket.id">
                            <tr class="hover:bg-slate-50 transition duration-150">
                                <td class="px-6 py-4">
                                    <span class="bg-rose-100 text-rose-700 font-extrabold px-3 py-1 rounded-lg text-xs" x-text="'Kupon #' + ticket.ticket_number">#001</span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-800" x-text="ticket.name">Budi Santoso</td>
                                <td class="px-6 py-4 font-medium text-slate-500" x-text="ticket.rt_rw">RT 04 / RW 004</td>
                                <td class="px-6 py-4 text-slate-400 text-xs" x-text="formatDate(ticket.created_at)">26 Mei 2026</td>
                                <td class="px-6 py-4 text-center">
                                    <span :class="ticket.status === 'approved' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-amber-100 text-amber-800 border-amber-200'"
                                          class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border"
                                          x-text="ticket.status === 'approved' ? 'Terkonfirmasi ✓' : 'Pending (Butuh Konfirmasi)' ">
                                    </span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- PREMIUM DOORPRIZE SHOWCASE CAROUSEL -->
    <section class="max-w-6xl mx-auto mt-16 px-4">
        <div class="text-center mb-10">
            <span class="text-rose-600 font-bold tracking-widest text-xs uppercase bg-rose-50 border border-rose-100 px-3.5 py-1.5 rounded-full inline-block mb-3">
                <i class="fa-solid fa-gift mr-1"></i> Bertabur Hadiah Menarik
            </span>
            <h2 class="text-3xl md:text-4xl font-outfit font-black text-slate-800">
                Pilihan Doorprize Utama Acara HUT RI 🇮🇩
            </h2>
            <p class="text-sm text-slate-500 mt-2 max-w-xl mx-auto">
                Pesan tiket sebanyak-banyaknya (maksimal 5 per keluarga) untuk kesempatan memenangkan hadiah-hadiah spektakuler di bawah ini!
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Gift 1: Sepeda Gunung -->
            <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1.5 text-center flex flex-col justify-between">
                <div class="w-full aspect-video rounded-2xl bg-gradient-to-tr from-rose-500 to-amber-500 flex items-center justify-center text-white text-5xl mb-4 relative overflow-hidden">
                    <i class="fa-solid fa-bicycle relative z-10 drop-shadow-lg"></i>
                    <div class="absolute inset-0 bg-black/10"></div>
                    <span class="absolute top-2 right-2 bg-yellow-400 text-rose-950 text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded-lg shadow">GRAND PRIZE</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800 font-outfit">Sepeda Gunung MTB</h3>
                    <p class="text-xs text-slate-400 mt-1">1 Unit Sepeda Gunung Premium Keren dengan Shifter Shimano.</p>
                </div>
            </div>

            <!-- Gift 2: Rice Cooker Digital -->
            <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1.5 text-center flex flex-col justify-between">
                <div class="w-full aspect-video rounded-2xl bg-gradient-to-tr from-red-600 to-orange-500 flex items-center justify-center text-white text-5xl mb-4 relative overflow-hidden">
                    <i class="fa-solid fa-bowl-rice relative z-10 drop-shadow-lg"></i>
                    <div class="absolute inset-0 bg-black/10"></div>
                    <span class="absolute top-2 right-2 bg-slate-900 text-white text-[10px] font-semibold px-2 py-0.5 rounded-lg">Pilihan Terbaik</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800 font-outfit">Rice Cooker Digital</h3>
                    <p class="text-xs text-slate-400 mt-1">2 Unit Rice Cooker Digital Smart Multifungsi hemat listrik.</p>
                </div>
            </div>

            <!-- Gift 3: Kipas Angin Tornado -->
            <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1.5 text-center flex flex-col justify-between">
                <div class="w-full aspect-video rounded-2xl bg-gradient-to-tr from-rose-600 to-rose-400 flex items-center justify-center text-white text-5xl mb-4 relative overflow-hidden">
                    <i class="fa-solid fa-wind relative z-10 drop-shadow-lg"></i>
                    <div class="absolute inset-0 bg-black/10"></div>
                    <span class="absolute top-2 right-2 bg-slate-900 text-white text-[10px] font-semibold px-2 py-0.5 rounded-lg">Pilihan Utama</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800 font-outfit">Kipas Angin Stand</h3>
                    <p class="text-xs text-slate-400 mt-1">3 Unit Kipas Angin Tornado Stand 16 Inch hembusan angin super sejuk.</p>
                </div>
            </div>

            <!-- Gift 4: Kompor Gas Portable -->
            <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1.5 text-center flex flex-col justify-between">
                <div class="w-full aspect-video rounded-2xl bg-gradient-to-tr from-amber-600 to-amber-400 flex items-center justify-center text-white text-5xl mb-4 relative overflow-hidden">
                    <i class="fa-solid fa-fire relative z-10 drop-shadow-lg"></i>
                    <div class="absolute inset-0 bg-black/10"></div>
                    <span class="absolute top-2 right-2 bg-slate-900 text-white text-[10px] font-semibold px-2 py-0.5 rounded-lg">Pilihan Ibu-Ibu</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800 font-outfit">Kompor Gas Portable</h3>
                    <p class="text-xs text-slate-400 mt-1">2 Unit Kompor Gas Portable Praktis lengkap dengan koper penyimpanan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SUCCESS BOOKING MODAL (PREMIUM TICKET DRAWING) -->
    <div x-show="showSuccessModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md" x-cloak x-transition>
        <div class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl border border-white/20 transform transition-all" @click.away="closeModal">
            <!-- Modal Header Red-White Gradient -->
            <div class="bg-gradient-to-r from-red-700 to-rose-600 text-white text-center py-6 px-6 relative">
                <button type="button" @click="closeModal" class="absolute top-4 right-4 text-white/70 hover:text-white bg-black/10 hover:bg-black/20 w-8 h-8 rounded-full flex items-center justify-center cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <div class="w-16 h-16 rounded-full bg-white text-rose-600 flex items-center justify-center text-3xl mx-auto mb-3 shadow-lg">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <h3 class="text-2xl font-outfit font-black">Booking Kupon Berhasil!</h3>
                <p class="text-xs text-rose-100">Simpan e-kupon Anda di bawah ini dan lakukan konfirmasi.</p>
            </div>

            <!-- Ticket Area (Mockup Kupon Merah Putih) -->
            <div class="p-6 bg-slate-50">
                <!-- Golden Ticket Wrapper -->
                <div class="bg-white rounded-2xl border-2 border-dashed border-rose-200 shadow-md relative overflow-hidden">
                    <!-- Cut-out Circles left and right -->
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-4 h-8 bg-slate-50 border-r-2 border-dashed border-rose-200 rounded-r-full -ml-px"></div>
                    <div class="absolute right-0 top-1/2 -translate-y-1/2 w-4 h-8 bg-slate-50 border-l-2 border-dashed border-rose-200 rounded-l-full -mr-px"></div>
                    
                    <div class="p-5 flex flex-col md:flex-row gap-4 items-center">
                        <!-- QR Code Area -->
                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100 flex flex-col items-center">
                            <!-- Custom SVG QR code mock -->
                            <svg class="w-24 h-24 text-rose-800" viewBox="0 0 100 100">
                                <rect x="0" y="0" width="20" height="20" fill="currentColor"/>
                                <rect x="5" y="5" width="10" height="10" fill="white"/>
                                <rect x="80" y="0" width="20" height="20" fill="currentColor"/>
                                <rect x="85" y="5" width="10" height="10" fill="white"/>
                                <rect x="0" y="80" width="20" height="20" fill="currentColor"/>
                                <rect x="5" y="85" width="10" height="10" fill="white"/>
                                <rect x="30" y="30" width="40" height="40" fill="currentColor"/>
                                <rect x="35" y="35" width="30" height="30" fill="white"/>
                                <rect x="45" y="45" width="10" height="10" fill="currentColor"/>
                                <rect x="30" y="10" width="10" height="10" fill="currentColor"/>
                                <rect x="60" y="80" width="10" height="10" fill="currentColor"/>
                                <rect x="80" y="60" width="10" height="10" fill="currentColor"/>
                            </svg>
                            <span class="text-[8px] font-bold text-rose-800 tracking-wider mt-1 uppercase">KUPON DOORPRIZE</span>
                        </div>

                        <!-- Ticket details -->
                        <div class="flex-1 text-center md:text-left space-y-1.5">
                            <span class="text-[10px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded uppercase">HUT RI KE-81</span>
                            <h4 class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Nama Pemegang</h4>
                            <p class="text-base font-bold text-slate-800 -mt-1" x-text="form.name">Budi Santoso</p>
                            
                            <div class="grid grid-cols-2 gap-2 text-xs text-slate-500 pt-1">
                                <div>
                                    <span class="block text-[9px] font-semibold text-slate-400 uppercase">RT/RW</span>
                                    <span class="font-bold text-slate-700" x-text="form.rt_rw">RT 04 / RW 004</span>
                                </div>
                                <div>
                                    <span class="block text-[9px] font-semibold text-slate-400 uppercase">WhatsApp</span>
                                    <span class="font-bold text-slate-700" x-text="form.whatsapp">081234567</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom part of ticket with large numbers -->
                    <div class="bg-rose-900/5 py-3.5 px-5 border-t border-dashed border-rose-100 flex flex-col items-center justify-center">
                        <span class="text-[9px] font-bold text-rose-600 uppercase tracking-widest mb-1.5">Nomor Tiket Anda</span>
                        <div class="flex flex-wrap gap-2 justify-center">
                            <template x-for="t in newlyBookedTickets" :key="t.id">
                                <span class="bg-rose-600 text-white font-extrabold font-outfit text-xl px-4 py-1 rounded-xl shadow-md border border-rose-500" x-text="t.ticket_number">
                                    001
                                </span>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Action buttons -->
            <div class="p-6 bg-white border-t border-slate-100 flex flex-col gap-2.5">
                <a :href="getWhatsAppShareLink()" target="_blank"
                   class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow text-center flex items-center justify-center gap-2 text-sm transition">
                    <i class="fa-brands fa-whatsapp text-lg"></i>
                    Hubungi Panitia & Kirim Kupon ke WA
                </a>
                
                <button type="button" @click="closeModal"
                        class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-xs transition cursor-pointer">
                    Selesai & Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- FOOTER COPYRIGHT -->
    <footer class="text-center text-slate-400 text-xs mt-16 max-w-6xl mx-auto border-t border-slate-200/50 pt-8">
        <p>&copy; 2026 Seksi Kesekretariatan Panitia HUT RI 81 Karang Taruna RW 004. Made with <i class="fa-solid fa-heart text-rose-500 animate-pulse"></i> for Indonesia.</p>
    </footer>

    <!-- Alpine.js Application Logic -->
    <script>
        function bookingApp() {
            return {
                stats: {
                    total: {{ $totalSlots }},
                    booked: {{ $bookedCount }},
                    approved: {{ $approvedCount }},
                    available: {{ $availableCount }}
                },
                form: {
                    name: '',
                    whatsapp: '',
                    rt_rw: '',
                    booking_type: 'manual',
                    ticket_qty: 1
                },
                selectedTickets: [],
                newlyBookedTickets: [],
                bookedTickets: @json($bookedTickets), // mapping '001' => 'pending' or 'approved'
                searchGridQuery: '',
                searchQuery: '',
                searchResults: [],
                showSuccessModal: false,
                submitting: false,

                // Initialize
                init() {
                    // console.log("Booking App Initiated");
                },

                setBookingType(type) {

    this.form.booking_type = type;

    // pindah ke random → reset pilihan manual
    if(type === 'random'){
        this.selectedTickets = [];
    }

    // pindah ke manual → reset qty random
    if(type === 'manual'){
        this.form.ticket_qty = 1;
    }
},

                // Check if a ticket number is already booked
                isBooked(num) {
                    return this.bookedTickets.hasOwnProperty(num);
                },

                // Check if a ticket is currently selected by the user
                isCurrentlySelected(num) {
                    return this.selectedTickets.includes(num);
                },

                // Toggle ticket selection (Manual Mode)
                toggleTicket(num) {
                    if (this.isBooked(num)) return;

                    const idx = this.selectedTickets.indexOf(num);
                    if (idx > -1) {
                        // Deselect
                        this.selectedTickets.splice(idx, 1);
                    } else {
                        // Select (Limit to 5)
                        if (this.selectedTickets.length >= 20) {
                            alert("Anda hanya dapat memilih maksimal 20 tiket per transaksi!");
                            return;
                        }
                        this.selectedTickets.push(num);
                    }
                },

                // Return class for ticket buttons dynamically
                getTicketClass(num) {
                    if (this.isCurrentlySelected(num)) {
                        return 'bg-yellow-400 border-yellow-500 text-slate-800 scale-102 ring-4 ring-yellow-400/20 shadow-md';
                    }
                    if (this.isBooked(num)) {
                        const status = this.bookedTickets[num];
                        if (status === 'approved') {
                            return 'bg-rose-600 border-rose-700 text-white opacity-85 shadow-sm';
                        }
                        return 'bg-amber-500 border-amber-600 text-white opacity-85 shadow-sm animate-pulse';
                    }
                    return 'bg-slate-100 border-slate-200 text-slate-700 hover:bg-slate-200 hover:border-slate-300 hover:scale-105';
                },

                // Filtered tickets based on search inside Grid
                filteredTickets() {
                    let tickets = [];
                    const padLen = this.stats.total.toString().length;
                    for (let i = 1; i <= this.stats.total; i++) {
                        tickets.push(i.toString().padStart(padLen, '0'));
                    }

                    if (this.searchGridQuery.trim() !== '') {
                        return tickets.filter(t => t.includes(this.searchGridQuery.trim()));
                    }

                    return tickets;
                },

                // Submit Booking via AJAX
                async submitBooking() {
                    if (this.submitting) return;

                    // Manual validation check
                    if (!this.form.name.trim() || !this.form.whatsapp.trim() || !this.form.rt_rw) {
                        alert("Harap isi semua kolom formulir pendaftaran!");
                        return;
                    }

                    // validasi form
if (!this.form.name.trim() ||
    !this.form.whatsapp.trim() ||
    !this.form.rt_rw) {
    alert("Harap isi semua data!");
    return;
}

// manual
if (
    this.form.booking_type === 'manual' &&
    this.selectedTickets.length === 0
) {
    alert("Pilih minimal 1 nomor tiket!");
    return;
}

// random
if (
    this.form.booking_type === 'random' &&
    this.form.ticket_qty < 1
) {
    alert("Pilih jumlah tiket acak!");
    return;
}

                    this.submitting = true;

                    const payload = {
                        name: this.form.name,
                        whatsapp: this.form.whatsapp,
                        rt_rw: this.form.rt_rw,
                        booking_type: this.form.booking_type,
                        selected_tickets: this.selectedTickets,
                        ticket_qty: this.form.ticket_qty
                    };

                    try {
                        const response = await fetch("/booking", {
                            method: "POST",
                            headers: {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "X-CSRF-TOKEN": document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content')
},
                            body: JSON.stringify(payload)
                        });

                        const resData = await response.json();

                        if (resData.success) {
                            this.newlyBookedTickets = resData.tickets;
                            
                            // Tambahkan tiket-tiket baru ke daftar lokal bookedTickets
                            resData.tickets.forEach(ticket => {
                                this.bookedTickets[ticket.ticket_number] = ticket.status;
                            });

                            // Update stats
                            const countNew = resData.tickets.length;
                            this.stats.booked += countNew;
                            this.stats.available -= countNew;

                            this.showSuccessModal = true;
                        } else {
                            alert(resData.message || "Gagal melakukan booking tiket.");
                        }
                    } catch (error) {
                        console.error(error);
                        alert("Terjadi kesalahan koneksi sistem. Silakan coba beberapa saat lagi!");
                    } finally {
                        this.submitting = false;
                    }
                },

                // Search Warga Tickets
                async searchTickets() {
                    const q = this.searchQuery.trim();
                    if (q === '') {
                        this.searchResults = [];
                        return;
                    }

                    try {
                        const response = await fetch(`/tickets/search?query=${encodeURIComponent(q)}`);
                        const data = await response.json();
                        this.searchResults = data;
                    } catch (e) {
                        console.error("Gagal melakukan pencarian warga", e);
                    }
                },

                formatDate(dateString) {
                    if (!dateString) return '';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    }) + ' WIB';
                },

               closeModal() {

    this.showSuccessModal = false;

    this.selectedTickets = [];
    this.newlyBookedTickets = [];

    this.form.name = '';
    this.form.whatsapp = '';
    this.form.rt_rw = '';

    this.form.ticket_qty = 1;
    this.form.booking_type = 'manual';
},

                getWhatsAppShareLink() {
                    const nums = this.newlyBookedTickets.map(t => t.ticket_number).join(', ');
                    const text = `Halo Panitia Semarak HUT RI RW 004!\n\nSaya telah melakukan booking tiket doorprize secara online:\n*Nama:* ${encodeURIComponent(this.form.name)}\n*RT/RW:* ${encodeURIComponent(this.form.rt_rw)}\n*Nomor Tiket:* ${encodeURIComponent(nums)}\n\nMohon bantuannya untuk mengonfirmasi tiket doorprize saya. Terima kasih! 🇮🇩`;
                    return `https://wa.me/?text=${text}`;
                }
            }
        }

        // Countdown Timer Logic
        function countdownTimer() {
            return {
                days: '00',
                hours: '00',
                minutes: '00',
                seconds: '00',
                init() {
                    // Set target 29 Agustus 2026
                    const targetDate = new Date("Aug 29, 2026 10:00:00").getTime();
                    
                    const updateTime = () => {
                        const now = new Date().getTime();
                        const diff = targetDate - now;

                        if (diff < 0) {
                            // If passed, set to next year or zero
                            this.days = '00';
                            this.hours = '00';
                            this.minutes = '00';
                            this.seconds = '00';
                            return;
                        }

                        const d = Math.floor(diff / (1000 * 60 * 60 * 24));
                        const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        const s = Math.floor((diff % (1000 * 60)) / 1000);

                        this.days = d.toString().padStart(2, '0');
                        this.hours = h.toString().padStart(2, '0');
                        this.minutes = m.toString().padStart(2, '0');
                        this.seconds = s.toString().padStart(2, '0');
                    };

                    updateTime();
                    setInterval(updateTime, 1000);
                }
            }
        }
    </script>
</body>
</html>
