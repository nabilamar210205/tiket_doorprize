<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel Admin HUT RI Ke-81 · Karang Taruna RW 004</title>

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

    <!-- Canvas Confetti for celebrations -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <style>
        body {
            background: #f8fafc;
        }
        .sidebar {
            background: linear-gradient(180deg, #be123c 0%, #991b1b 100%);
        }
        .card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,.04);
            border: 1px solid #f1f5f9;
        }
        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans text-slate-800 antialiased min-h-screen" x-data="adminApp()">

    <!-- TOAST NOTIFICATION CONTAINER -->
    <div class="fixed top-5 right-5 z-50 space-y-3 pointer-events-none">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-transition:enter="transition ease-out duration-300 transform translate-x-10 opacity-0"
                 x-transition:enter-start="translate-x-10 opacity-0"
                 x-transition:enter-end="translate-x-0 opacity-100"
                 x-transition:leave="transition ease-in duration-200 transform translate-x-10 opacity-0"
                 class="pointer-events-auto flex items-center gap-3 px-5 py-4 rounded-2xl shadow-xl border text-sm font-semibold max-w-md bg-white border-slate-100"
                 :class="toast.type === 'success' ? 'text-emerald-800 border-l-4 border-l-emerald-500' : 'text-rose-800 border-l-4 border-l-rose-500'">
                <span class="text-lg" :class="toast.type === 'success' ? 'text-emerald-500' : 'text-rose-500'">
                    <i class="fa-solid" :class="toast.type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'"></i>
                </span>
                <span x-text="toast.message"></span>
            </div>
        </template>
    </div>

    <!-- MAIN WRAPPER -->
    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        <aside class="sidebar w-72 text-white p-6 hidden lg:flex flex-col justify-between shrink-0 shadow-2xl relative overflow-hidden">
            <!-- Decorative Flag Ribbon -->
            <div class="absolute inset-0 opacity-5 pointer-events-none flex justify-between items-center px-10">
                <i class="fa-solid fa-flag text-9xl rotate-12"></i>
            </div>

            <div class="relative z-10">
                <!-- Header -->
                <div class="mb-10 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white/20 border border-white/30 backdrop-blur flex items-center justify-center shadow overflow-hidden p-0.5 bg-slate-900">
                        <img src="{{ asset('logo.jpg') }}" alt="Logo Karang Taruna" class="w-full h-full object-contain rounded-lg">
                    </div>
                    <div>
                        <h1 class="text-xl font-bold font-outfit">Panel Panitia</h1>
                        <p class="text-rose-200 text-[10px] font-semibold tracking-widest uppercase mt-0.5">HUT RI Ke-81</p>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="space-y-2.5">
                    <button @click="currentSection = 'dashboard'" 
                            :class="currentSection === 'dashboard' ? 'bg-white/10 text-white border-l-4 border-white' : 'text-rose-100/75 hover:bg-white/5'"
                            class="w-full flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition text-left cursor-pointer">
                        <i class="fa-solid fa-chart-line w-5"></i> Dashboard Utama
                    </button>
                    
                    <button @click="currentSection = 'lucky_draw'" 
                            :class="currentSection === 'lucky_draw' ? 'bg-white/10 text-white border-l-4 border-white' : 'text-rose-100/75 hover:bg-white/5'"
                            class="w-full flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold transition text-left cursor-pointer">
                        <i class="fa-solid fa-circle-play w-5"></i> Roda Undian Doorprize
                    </button>

                    <a href="{{ route('home') }}" 
                       class="w-full flex items-center gap-3.5 px-4 py-3 rounded-xl text-sm font-semibold text-rose-100/75 hover:bg-white/5 transition text-left">
                        <i class="fa-solid fa-arrow-left w-5"></i> Halaman Warga
                    </a>
                </nav>
            </div>

            <!-- Footer Section & Logout -->
            <div class="relative z-10 border-t border-white/10 pt-5 mt-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-full bg-rose-800 flex items-center justify-center text-xs font-bold text-rose-200">
                        P
                    </div>
                    <div>
                        <p class="text-xs font-bold">Panitia HUT RW 004</p>
                        <p class="text-[10px] text-rose-300">Level Akses: Admin</p>
                    </div>
                </div>

                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-2.5 bg-rose-950/40 hover:bg-rose-950/60 border border-white/15 text-xs font-bold rounded-xl flex items-center justify-center gap-2 transition cursor-pointer">
                        <i class="fa-solid fa-right-from-bracket"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- CONTENT AREA -->
        <main class="flex-1 p-6 md:p-8 min-w-0 max-h-screen overflow-y-auto">

            <!-- MOBILE TOP NAVIGATION -->
            <div class="lg:hidden bg-white border border-slate-100 rounded-2xl p-4 mb-6 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('logo.jpg') }}" alt="Logo Karang Taruna" class="w-6 h-6 object-contain rounded-full bg-slate-900">
                    <span class="font-bold text-sm">Panel Panitia</span>
                </div>
                <div class="flex gap-2">
                    <button @click="currentSection = 'dashboard'" :class="currentSection === 'dashboard' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-600'" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition">Dash</button>
                    <button @click="currentSection = 'lucky_draw'" :class="currentSection === 'lucky_draw' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-600'" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition">Undian</button>
                    <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-rose-50 text-rose-600 p-2 rounded-xl text-xs transition"><i class="fa-solid fa-power-off"></i></button>
                    </form>
                </div>
            </div>

            <!-- VIEW: DASHBOARD SECTION -->
            <div x-show="currentSection === 'dashboard'" x-transition class="space-y-6">
                <!-- TOP HEADER -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-3xl font-black font-outfit text-slate-800">Dasbor Panitia 🎉</h2>
                        <p class="text-slate-400 text-sm mt-1">Monitoring pendaftaran tiket doorprize jalan sehat warga</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-3 self-start md:self-auto">
                        <!-- Download PDF Button -->
                        <a href="{{ route('admin.tickets.pdf') }}" 
                           class="bg-emerald-600 border border-emerald-700 text-white hover:bg-emerald-700 px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 shadow-sm hover:shadow">
                            <i class="fa-solid fa-file-pdf"></i> Cetak Semua Kupon (PDF)
                        </a>

                        <!-- Reset Action Button -->
                        <button type="button" @click="showResetModal = true" 
                                class="bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-100 hover:border-rose-300 px-4 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-trash-can"></i> Reset Semua Data Booking
                        </button>
                    </div>
                </div>

                <!-- STATISTIK STRIP -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Card 1 -->
                    <div class="card p-5 flex items-center gap-4 relative overflow-hidden group">
                        <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-slate-500/5 rounded-full group-hover:scale-110 transition duration-300"></div>
                        <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-500 text-xl"><i class="fa-solid fa-ticket"></i></div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Slot</p>
                            <h3 class="text-3xl font-black font-outfit mt-1 text-slate-800" x-text="stats.total">500</h3>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="card p-5 flex items-center gap-4 relative overflow-hidden group">
                        <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-amber-500/5 rounded-full group-hover:scale-110 transition duration-300"></div>
                        <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-500 text-xl"><i class="fa-solid fa-clock-rotate-left"></i></div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pending</p>
                            <h3 class="text-3xl font-black font-outfit mt-1 text-amber-500" x-text="stats.pending">0</h3>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="card p-5 flex items-center gap-4 relative overflow-hidden group">
                        <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-emerald-500/5 rounded-full group-hover:scale-110 transition duration-300"></div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-500 text-xl"><i class="fa-solid fa-circle-check"></i></div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Approved</p>
                            <h3 class="text-3xl font-black font-outfit mt-1 text-emerald-500" x-text="stats.approved">0</h3>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="card p-5 flex items-center gap-4 relative overflow-hidden group">
                        <div class="absolute -right-4 -bottom-4 w-16 h-16 bg-rose-500/5 rounded-full group-hover:scale-110 transition duration-300"></div>
                        <div class="w-12 h-12 rounded-xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-500 text-xl"><i class="fa-solid fa-circle-info"></i></div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tersedia</p>
                            <h3 class="text-3xl font-black font-outfit mt-1 text-rose-600" x-text="stats.available">500</h3>
                        </div>
                    </div>
                </div>

                <!-- MAIN WORKSPACE CARD (TABLE) -->
                <div class="card p-6 flex flex-col min-h-[400px]">
                    <!-- Table Actions & Filters -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <!-- Left Tab Filters -->
                        <div class="bg-slate-100 p-1 rounded-xl inline-flex self-start">
                            <button type="button" @click="filterTab = 'all'" 
                                    :class="filterTab === 'all' ? 'bg-white text-slate-800 shadow-md font-bold' : 'text-slate-500 hover:text-slate-800'"
                                    class="px-4 py-2 rounded-lg text-xs transition duration-200 cursor-pointer">
                                Semua Tiket (<span x-text="tickets.length"></span>)
                            </button>
                            <button type="button" @click="filterTab = 'pending'" 
                                    :class="filterTab === 'pending' ? 'bg-white text-amber-600 shadow-md font-bold' : 'text-slate-500 hover:text-slate-800'"
                                    class="px-4 py-2 rounded-lg text-xs transition duration-200 cursor-pointer">
                                Pending (<span x-text="tickets.filter(t => t.status === 'pending').length"></span>)
                            </button>
                            <button type="button" @click="filterTab = 'approved'" 
                                    :class="filterTab === 'approved' ? 'bg-white text-emerald-600 shadow-md font-bold' : 'text-slate-500 hover:text-slate-800'"
                                    class="px-4 py-2 rounded-lg text-xs transition duration-200 cursor-pointer">
                                Approved (<span x-text="tickets.filter(t => t.status === 'approved').length"></span>)
                            </button>
                        </div>

                        <!-- Right Search input -->
                        <div class="relative w-full sm:max-w-xs">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            </span>
                            <input type="text" x-model="searchQuery"
                                   class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 outline-none text-xs transition bg-slate-50"
                                   placeholder="Cari kupon, nama, RT, WA...">
                        </div>
                    </div>

                    <!-- Scrollable Table Wrapper -->
                    <div class="overflow-x-auto rounded-2xl border border-slate-100 flex-1">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50/70 text-slate-500 uppercase tracking-wider text-xs font-bold border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-4">Kupon #</th>
                                    <th class="px-6 py-4">Nama Pemesan</th>
                                    <th class="px-6 py-4">WhatsApp</th>
                                    <th class="px-6 py-4">RT / RW</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                    <th class="px-6 py-4 text-right">Aksi Manajemen</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 text-slate-700">
                                <!-- Loading/Empty State -->
                                <template x-if="filteredTickets().length === 0">
                                    <tr>
                                        <td colspan="6" class="px-6 py-16 text-center text-slate-400">
                                            <div class="w-16 h-16 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-xl mx-auto mb-3">
                                                <i class="fa-solid fa-folder-open text-slate-300"></i>
                                            </div>
                                            <p class="font-semibold text-slate-500">Tidak ada data pendaftaran</p>
                                            <p class="text-xs text-slate-400 mt-1">Data pendaftaran warga masih kosong atau tidak sesuai pencarian.</p>
                                        </td>
                                    </tr>
                                </template>

                                <!-- Table Rows -->
                                <template x-for="ticket in filteredTickets()" :key="ticket.id">
                                    <tr class="hover:bg-slate-50/50 transition duration-150 group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="bg-rose-50 text-rose-700 font-extrabold font-mono px-3 py-1 rounded-xl text-xs border border-rose-100/50" x-text="ticket.ticket_number"></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-bold text-slate-800" x-text="ticket.name"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-slate-500 font-medium">
                                            <a :href="'https://wa.me/' + ticket.whatsapp.replace(/[^0-9]/g, '')" target="_blank" class="hover:text-emerald-600 transition flex items-center gap-1.5">
                                                <i class="fa-brands fa-whatsapp text-emerald-500 text-base"></i>
                                                <span x-text="ticket.whatsapp"></span>
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-slate-500" x-text="ticket.rt_rw"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span :class="ticket.status === 'approved' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-amber-50 text-amber-700 border-amber-100'"
                                                  class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold border"
                                                  x-text="ticket.status === 'approved' ? 'Approved' : 'Pending' ">
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                                            <!-- Approve button -->
                                            <template x-if="ticket.status === 'pending'">
                                                <button type="button" @click="approveTicket(ticket.id)" 
                                                        class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-3.5 py-1.5 rounded-xl text-xs transition cursor-pointer shadow-sm hover:shadow">
                                                    <i class="fa-solid fa-check mr-1"></i> Setujui
                                                </button>
                                            </template>
                                            
                                            <!-- Cancel button -->
                                            <button type="button" @click="cancelTicket(ticket)" 
                                                    class="bg-slate-50 hover:bg-rose-50 border border-slate-200 hover:border-rose-200 text-slate-500 hover:text-rose-600 font-bold px-3.5 py-1.5 rounded-xl text-xs transition cursor-pointer shadow-sm">
                                                <i class="fa-solid fa-trash-can mr-1"></i> Batal
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- VIEW: LUCKY DRAW SECTION -->
            <div x-show="currentSection === 'lucky_draw'" x-transition class="space-y-6">
                <!-- TOP HEADER -->
                <div>
                    <h2 class="text-3xl font-black font-outfit text-slate-800">🎡 Undian Doorprize Kemerdekaan</h2>
                    <p class="text-slate-400 text-sm mt-1">Undi hadiah menarik secara adil di antara warga yang kuponnya telah disetujui (*Approved*)</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- LEFT CONTAINER: LUCKY DRAW WHEEL ENGINE -->
                    <div class="lg:col-span-8 card p-6 md:p-8 flex flex-col items-center justify-between text-center relative overflow-hidden bg-gradient-to-b from-white to-slate-50/50">
                        
                        <!-- Header Pool Indicator -->
                        <div class="flex items-center gap-2 bg-rose-50 border border-rose-100 rounded-full px-4 py-1.5 text-xs font-bold text-rose-700 mb-4 z-10">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Pool Peserta Aktif: <strong class="ml-1" x-text="approvedTickets.length + ' Kupon Approved'">0 Kupon</strong>
                        </div>

                        <!-- Main Spin Visual Display -->
                        <div class="my-8 relative">
                            <!-- Spinning Background Circle Decoration -->
                            <div :class="isDrawing ? 'animate-spin border-dashed duration-500' : 'border-solid'" 
                                 class="w-64 h-64 sm:w-72 sm:h-72 rounded-full border-[12px] border-slate-100 border-t-rose-500 border-r-yellow-400 border-b-emerald-500 border-l-rose-500 flex items-center justify-center mx-auto transition-all duration-300 shadow-lg relative">
                                
                                <!-- Core Card -->
                                <div class="w-48 h-48 sm:w-56 sm:h-56 rounded-full bg-white shadow-inner flex flex-col items-center justify-center p-4 border border-slate-100">
                                    
                                    <!-- Rotating Number display -->
                                    <h4 class="text-slate-400 text-[10px] font-extrabold uppercase tracking-widest mb-1">KUPON UNDIAN</h4>
                                    <div class="text-5xl font-black font-outfit text-slate-800 tracking-tight font-mono transition-transform" 
                                         x-text="currentDrawNumber">
                                        000
                                    </div>
                                    <p class="text-xs text-rose-600 font-bold mt-2 truncate w-full px-2" x-text="currentDrawName">
                                        Siap Diundi
                                    </p>
                                </div>
                            </div>

                            <!-- Pointer Arrow Indicator -->
                            <div class="absolute -top-3 left-1/2 -translate-x-1/2 w-8 h-8 bg-rose-600 rotate-45 rounded-bl-full shadow-lg border-2 border-white"></div>
                        </div>

                        <!-- Control Buttons -->
                        <div class="w-full max-w-sm space-y-3 z-10">
                            <button type="button" 
                                    @click="startDrawing()" 
                                    :disabled="isDrawing || approvedTickets.length === 0"
                                    class="w-full py-4 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 disabled:from-slate-200 disabled:to-slate-200 disabled:text-slate-400 disabled:shadow-none text-white font-black font-outfit rounded-2xl shadow-xl hover:shadow-2xl transition duration-200 transform active:scale-95 flex items-center justify-center gap-2 cursor-pointer disabled:cursor-not-allowed text-base tracking-wide">
                                <i class="fa-solid" :class="isDrawing ? 'fa-circle-notch animate-spin' : 'fa-circle-play'"></i>
                                <span x-text="isDrawing ? 'Mengundi Pemenang...' : 'Putar Undian Sekarang! 🎡'"></span>
                            </button>

                            <!-- Pools alert warnings -->
                            <template x-if="approvedTickets.length === 0">
                                <div class="bg-amber-50 border border-amber-100 rounded-xl px-4 py-3 flex items-center gap-2 text-left text-xs text-amber-800">
                                    <i class="fa-solid fa-circle-exclamation text-base shrink-0"></i>
                                    <span>Tidak ada kupon berkategori <strong>Approved</strong>. Harap lakukan *Approve* terlebih dahulu di menu dashboard!</span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- RIGHT CONTAINER: WINNERS ROLL -->
                    <div class="lg:col-span-4 card p-6 flex flex-col justify-between h-full min-h-[500px]">
                        <div class="space-y-4 flex-1 flex flex-col">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                                <h3 class="font-bold text-lg font-outfit text-slate-800 flex items-center gap-2">
                                    <span class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 text-sm"><i class="fa-solid fa-trophy"></i></span>
                                    Daftar Pemenang Doorprize
                                </h3>
                                <button type="button" @click="clearWinners()" x-show="winnerHistory.length > 0" class="text-[10px] font-bold text-rose-500 hover:text-rose-700 hover:underline">
                                    Hapus Semua
                                </button>
                            </div>

                            <!-- List -->
                            <div class="overflow-y-auto custom-scroll flex-1 max-h-[360px] space-y-3 pr-1 pt-1">
                                <template x-if="winnerHistory.length === 0">
                                    <div class="text-center py-16 text-slate-400 italic">
                                        <i class="fa-solid fa-hourglass-empty text-3xl mb-2 text-slate-200 block"></i>
                                        Pemenang belum diundi.
                                    </div>
                                </template>

                                <template x-for="(w, idx) in winnerHistory" :key="idx">
                                    <div x-transition class="bg-gradient-to-r from-emerald-50/50 to-white border border-slate-100 rounded-2xl p-3.5 flex items-center justify-between gap-3 shadow-sm hover:shadow transition duration-200 relative overflow-hidden group">
                                        <!-- Decorative Ribbons -->
                                        <div class="absolute top-0 right-0 w-8 h-8 bg-emerald-500/5 rounded-full -mr-3 -mt-3"></div>

                                        <div class="flex items-center gap-3">
                                            <span class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 font-extrabold font-mono text-sm shadow-sm" x-text="w.ticket_number"></span>
                                            <div>
                                                <h4 class="font-bold text-slate-800 text-xs sm:text-sm truncate max-w-[130px]" x-text="w.name"></h4>
                                                <p class="text-[10px] text-slate-400 font-semibold" x-text="w.rt_rw"></p>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-1.5 shrink-0">
                                            <!-- WA Contact winner -->
                                            <a :href="'https://wa.me/' + w.whatsapp.replace(/[^0-9]/g, '') + '?text=' + encodeURIComponent('Selamat Warga!\n\nNama Anda telah memenangkan undian doorprize Semarak HUT RI Ke-81 Karang Taruna RW 004!\n*Nomor Kupon:* ' + w.ticket_number + '\n*Nama:* ' + w.name + '\n*RT/RW:* ' + w.rt_rw + '\n\nSilakan konfirmasi ke sekretariat panitia untuk pengambilan hadiah. Merdeka! 🇮🇩')" 
                                               target="_blank" 
                                               class="w-7 h-7 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center hover:bg-emerald-600 hover:text-white hover:border-emerald-600 transition text-xs shadow-sm">
                                                <i class="fa-solid fa-paper-plane"></i>
                                            </a>
                                            <!-- Delete Single Winner -->
                                            <button type="button" @click="deleteWinner(idx)" class="w-7 h-7 rounded-lg bg-slate-50 border border-slate-100 text-slate-400 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-100 flex items-center justify-center transition text-xs cursor-pointer shadow-sm">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Total Winner Footer -->
                        <div class="border-t border-slate-100 pt-4 mt-4 flex justify-between items-center text-xs font-semibold text-slate-400">
                            <span>Total Pemenang Tergambar</span>
                            <span class="bg-slate-100 text-slate-700 rounded-full px-2 py-0.5" x-text="winnerHistory.length + ' Warga'">0 Warga</span>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <!-- MODAL: DETAILED RESET DATA CONFIRMATION -->
    <div x-show="showResetModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-md" x-cloak x-transition>
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 transform transition-all text-center space-y-6" @click.away="showResetModal = false">
            <div class="w-16 h-16 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center text-3xl mx-auto shadow-inner">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            
            <div>
                <h3 class="text-xl font-bold font-outfit text-slate-800">Reset Seluruh Data Tiket?</h3>
                <p class="text-xs text-slate-400 mt-2 leading-relaxed">Tindakan ini akan <strong>MENGHAPUS SEMUA</strong> data pendaftaran tiket warga dari database secara permanen! Ini tidak bisa dibatalkan.</p>
            </div>

            <!-- Double Confirmation input -->
            <div class="bg-slate-50 border border-slate-150 rounded-2xl p-4 text-xs text-left space-y-2.5">
                <label class="block font-semibold text-slate-600">Untuk mengonfirmasi, ketik <strong class="text-rose-600 select-none">RESET17</strong> di bawah ini:</label>
                <input type="text" x-model="resetConfirmInput"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 outline-none text-center font-bold tracking-widest bg-white"
                       placeholder="Ketik kata kunci...">
            </div>

            <div class="flex gap-3">
                <button type="button" @click="showResetModal = false" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-2xl text-xs transition cursor-pointer">
                    Batal
                </button>
                <button type="button" @click="resetAll()" 
                        :disabled="resetConfirmInput !== 'RESET17' || isSubmitting"
                        class="flex-1 py-3 bg-rose-600 hover:bg-rose-700 disabled:bg-rose-200 text-white font-bold rounded-2xl text-xs transition cursor-pointer shadow-md disabled:cursor-not-allowed">
                    Ya, Reset Sekarang 🧨
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL: WINNER CELEBRATION OVERLAY -->
    <div x-show="showDrawModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-md" x-cloak x-transition>
        <div class="bg-white rounded-3xl max-w-md w-full overflow-hidden shadow-2xl border border-white/20 transform transition-all" @click.away="showDrawModal = false">
            
            <!-- Modal Header Red-White Gradient -->
            <div class="bg-gradient-to-r from-red-700 to-rose-600 text-white text-center py-8 px-6 relative">
                <!-- Close Button -->
                <button type="button" @click="showDrawModal = false" class="absolute top-4 right-4 text-white/70 hover:text-white bg-black/10 hover:bg-black/20 w-8 h-8 rounded-full flex items-center justify-center cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                
                <!-- Trophy Icon with glow -->
                <div class="w-20 h-20 rounded-full bg-yellow-400 text-rose-950 flex items-center justify-center text-4xl mx-auto mb-4 shadow-lg ring-4 ring-yellow-300/30 animate-bounce">
                    🏆
                </div>
                <h3 class="text-3xl font-outfit font-black tracking-tight">Pemenang Doorprize!</h3>
                <p class="text-xs text-rose-100/90 mt-1 uppercase tracking-wider font-semibold">Selamat kepada warga terpilih!</p>
            </div>

            <!-- Winner Ticket Body -->
            <div class="p-6 bg-slate-50 relative overflow-hidden">
                <!-- Dashed coupon mockup -->
                <div class="bg-white rounded-2xl border-2 border-dashed border-rose-200 shadow-md relative overflow-hidden">
                    <!-- Cut-out Circles left and right -->
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 w-4 h-8 bg-slate-50 border-r-2 border-dashed border-rose-200 rounded-r-full -ml-px"></div>
                    <div class="absolute right-0 top-1/2 -translate-y-1/2 w-4 h-8 bg-slate-50 border-l-2 border-dashed border-rose-200 rounded-l-full -mr-px"></div>
                    
                    <div class="p-6 flex flex-col items-center text-center space-y-4">
                        <span class="text-[9px] font-bold text-rose-600 bg-rose-50 border border-rose-100 px-3 py-1 rounded-full uppercase tracking-wider">Kupon Doorprize Merdeka</span>
                        
                        <!-- Huge Winner Number -->
                        <div class="text-5xl font-black font-outfit text-slate-800 tracking-tight font-mono bg-slate-50/50 border border-slate-100 rounded-2xl px-6 py-3 w-full shadow-inner">
                            <span class="text-rose-600">#</span><span x-text="latestWinner?.ticket_number"></span>
                        </div>

                        <!-- Details -->
                        <div class="w-full">
                            <h4 class="text-2xl font-black text-slate-800" x-text="latestWinner?.name"></h4>
                            <p class="text-sm font-bold text-slate-500 mt-1" x-text="latestWinner?.rt_rw"></p>
                            <p class="text-xs text-slate-400 font-medium mt-0.5" x-text="'No WA: ' + latestWinner?.whatsapp"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Action buttons -->
            <div class="p-6 bg-white border-t border-slate-100 flex flex-col gap-2.5">
                <a :href="getWhatsAppWinnerLink()" target="_blank"
                   class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow text-center flex items-center justify-center gap-2 text-sm transition">
                    <i class="fa-brands fa-whatsapp text-lg"></i>
                    Hubungi & Kirim Selamat via WA
                </a>
                
                <button type="button" @click="showDrawModal = false"
                        class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-xs transition cursor-pointer">
                    Selesai & Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Alpine.js Application Logic -->
    <script>
        function adminApp() {
            return {
                tickets: @json($tickets),
                stats: {
                    total: {{ $totalSlots }},
                    booked: {{ $bookedCount }},
                    approved: {{ $approvedCount }},
                    pending: {{ $pendingCount }},
                    available: {{ $availableCount }}
                },
                approvedTickets: @json($approvedTickets),

                toasts: [],
                toastId: 0,

                currentSection: 'dashboard', // dashboard, lucky_draw
                filterTab: 'all', // all, pending, approved
                searchQuery: '',
                isSubmitting: false,

                // Reset Modal
                showResetModal: false,
                resetConfirmInput: '',

                // Lucky Draw
                winnerHistory: [],
                showDrawModal: false,
                isDrawing: false,
                currentDrawNumber: '000',
                currentDrawName: 'Siap Diundi',
                latestWinner: null,
                audioCtx: null,

                getAudioContext() {
                    if (!this.audioCtx) {
                        this.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                    }
                    if (this.audioCtx.state === 'suspended') {
                        this.audioCtx.resume();
                    }
                    return this.audioCtx;
                },

                init() {
                    // Load winner history from localStorage
                    const localHistory = localStorage.getItem('ri_doorprize_winners');
                    if (localHistory) {
                        try {
                            this.winnerHistory = JSON.parse(localHistory);
                        } catch (e) {
                            console.error("Gagal membaca riwayat pemenang", e);
                        }
                    }
                },

                // Push beautiful toast notifications
                showToast(message, type = 'success') {
                    const id = this.toastId++;
                    this.toasts.push({ id, message, type });
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== id);
                    }, 4000);
                },

                // Filters ticket list
                filteredTickets() {
                    let filtered = this.tickets;

                    // Tab filter
                    if (this.filterTab === 'pending') {
                        filtered = filtered.filter(t => t.status === 'pending');
                    } else if (this.filterTab === 'approved') {
                        filtered = filtered.filter(t => t.status === 'approved');
                    }

                    // Search query filter
                    const query = this.searchQuery.trim().toLowerCase();
                    if (query !== '') {
                        filtered = filtered.filter(t => 
                            t.ticket_number.includes(query) ||
                            t.name.toLowerCase().includes(query) ||
                            t.whatsapp.includes(query) ||
                            t.rt_rw.toLowerCase().includes(query)
                        );
                    }

                    return filtered;
                },

                // Re-calculates statistics and draws lists
                recalculateStats() {
                    const booked = this.tickets.length;
                    const approved = this.tickets.filter(t => t.status === 'approved').length;
                    const pending = booked - approved;
                    const available = this.stats.total - booked;

                    this.stats.booked = booked;
                    this.stats.approved = approved;
                    this.stats.pending = pending;
                    this.stats.available = available;

                    // Update approved tickets for lucky draw, filtering out current session winners
                    const currentWinnerNumbers = this.winnerHistory.map(w => w.ticket_number);
                    this.approvedTickets = this.tickets.filter(t => 
                        t.status === 'approved' && !currentWinnerNumbers.includes(t.ticket_number)
                    );
                },

                // Synthesize beeps using Web Audio API
                playBeep(frequency, duration) {
                    try {
                        const audioCtx = this.getAudioContext();
                        const oscillator = audioCtx.createOscillator();
                        const gainNode = audioCtx.createGain();

                        oscillator.type = 'sine';
                        oscillator.frequency.value = frequency;
                        
                        gainNode.gain.setValueAtTime(0.08, audioCtx.currentTime);
                        gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + duration);

                        oscillator.connect(gainNode);
                        gainNode.connect(audioCtx.destination);

                        oscillator.start();
                        oscillator.stop(audioCtx.currentTime + duration);
                    } catch (e) {
                        console.error("Audio Context failed", e);
                    }
                },

                 playDrawTick(progress) {
                     try {
                         const audioCtx = this.getAudioContext();
                         const osc = audioCtx.createOscillator();
                         const gain = audioCtx.createGain();
                         
                         // Pitch drops dynamically to mimic deceleration (starts high, ends deep)
                         const freq = 900 - (progress * 500); 
                         osc.type = 'triangle'; // Woodblock/ticking feel
                         osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
                         
                         // Short envelope
                         gain.gain.setValueAtTime(0.12, audioCtx.currentTime);
                         gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.08);
                         
                         osc.connect(gain);
                         gain.connect(audioCtx.destination);
                         osc.start();
                         osc.stop(audioCtx.currentTime + 0.08);
                     } catch(e) {
                         console.error(e);
                     }
                 },

                 playVictorySound() {
                     try {
                         const audioCtx = this.getAudioContext();
                         
                         const playNote = (freq, startTime, duration, vol = 0.15) => {
                             const osc = audioCtx.createOscillator();
                             const gain = audioCtx.createGain();
                             
                             osc.type = 'sawtooth'; // Brassy fanfare sound
                             osc.frequency.setValueAtTime(freq, audioCtx.currentTime + startTime);
                             
                             const filter = audioCtx.createBiquadFilter();
                             filter.type = 'lowpass';
                             filter.frequency.setValueAtTime(1400, audioCtx.currentTime + startTime);
                             
                             gain.gain.setValueAtTime(0, audioCtx.currentTime + startTime);
                             gain.gain.linearRampToValueAtTime(vol, audioCtx.currentTime + startTime + 0.05);
                             gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + startTime + duration);
                             
                             osc.connect(filter);
                             filter.connect(gain);
                             gain.connect(audioCtx.destination);
                             
                             osc.start(audioCtx.currentTime + startTime);
                             osc.stop(audioCtx.currentTime + startTime + duration);
                         };

                         // Triumphant brass fanfare chord progression
                         playNote(261.63, 0.0, 0.4);   // C4
                         playNote(329.63, 0.15, 0.4);  // E4
                         playNote(392.00, 0.3, 0.4);   // G4
                         playNote(523.25, 0.45, 0.6);  // C5
                         playNote(659.25, 0.6, 0.8);   // E5
                         playNote(783.99, 0.75, 1.4, 0.12); // G5 (chord base)
                         playNote(1046.50, 0.75, 1.7, 0.1); // C6 (chord top)
                     } catch (e) {
                         console.error(e);
                     }
                 },

                 speakWinner(ticketNumber) {
                     try {
                         if ('speechSynthesis' in window) {
                             // Cancel any active speech synthesis to avoid overlapping audio
                             window.speechSynthesis.cancel();

                             // Read digits separately so the number is announced extremely clearly (e.g. nol lima dua tiga)
                             const digitNames = {
                                 '0': 'nol', '1': 'satu', '2': 'dua', '3': 'tiga', '4': 'empat',
                                 '5': 'lima', '6': 'enam', '7': 'tujuh', '8': 'delapan', '9': 'sembilan'
                             };
                             const digitsArray = ticketNumber.split('');
                             const ticketDigits = digitsArray.map(d => digitNames[d] || d).join(' ');
                             
                             const message = `Selamat kepada nomor kupon ${ticketDigits}! Silakan maju ke depan!`;
                             const utterance = new SpeechSynthesisUtterance(message);
                             
                             utterance.lang = 'id-ID';
                             utterance.pitch = 1.0;
                             utterance.rate = 0.85; // Slightly slower, authoritative voice rate for official announcements
                             
                             // Select Indonesian voice if available
                             const voices = window.speechSynthesis.getVoices();
                             const indonesianVoice = voices.find(v => v.lang.includes('id') || v.lang.includes('ID'));
                             if (indonesianVoice) {
                                 utterance.voice = indonesianVoice;
                             }
                             
                             window.speechSynthesis.speak(utterance);
                         }
                     } catch (e) {
                         console.error("Text to speech failed", e);
                     }
                 },

                // AJAX: Setujui tiket pending
                async approveTicket(id) {
                    if (this.isSubmitting) return;
                    this.isSubmitting = true;

                    try {
                        const response = await fetch(`/admin/tickets/${id}/approve`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            // Update lokal
                            const index = this.tickets.findIndex(t => t.id === id);
                            if (index > -1) {
                                this.tickets[index].status = 'approved';
                            }
                            this.recalculateStats();
                            this.showToast(data.message, 'success');
                            this.playBeep(523.25, 0.15); // C5 victory ping
                        } else {
                            this.showToast(data.message || "Gagal menyetujui tiket.", 'error');
                        }
                    } catch (e) {
                        console.error("Approve failed", e);
                        this.showToast("Terjadi kesalahan koneksi server.", 'error');
                    } finally {
                        this.isSubmitting = false;
                    }
                },

                // AJAX: Batalkan/Hapus tiket
                async cancelTicket(ticket) {
                    const confirmed = confirm(`Apakah Anda yakin ingin membatalkan pemesanan kupon #${ticket.ticket_number} atas nama "${ticket.name}"?`);
                    if (!confirmed) return;

                    if (this.isSubmitting) return;
                    this.isSubmitting = true;

                    try {
                        const response = await fetch(`/admin/tickets/${ticket.id}/cancel`, {
                            method: "DELETE",
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            // Hapus lokal
                            this.tickets = this.tickets.filter(t => t.id !== ticket.id);
                            this.recalculateStats();
                            this.showToast(data.message, 'success');
                            this.playBeep(220.00, 0.2); // Low A buzzer
                        } else {
                            this.showToast(data.message || "Gagal membatalkan tiket.", 'error');
                        }
                    } catch (e) {
                        console.error("Cancel failed", e);
                        this.showToast("Terjadi kesalahan koneksi server.", 'error');
                    } finally {
                        this.isSubmitting = false;
                    }
                },

                // AJAX: Reset semua data
                async resetAll() {
                    if (this.resetConfirmInput !== 'RESET17') return;
                    if (this.isSubmitting) return;

                    this.isSubmitting = true;

                    try {
                        const response = await fetch("/admin/reset", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.tickets = [];
                            this.winnerHistory = [];
                            localStorage.removeItem('ri_doorprize_winners');
                            this.recalculateStats();
                            this.showToast(data.message, 'success');
                            this.showResetModal = false;
                            this.resetConfirmInput = '';
                            this.playBeep(150.00, 0.4); // Deep rumble
                        } else {
                            this.showToast(data.message || "Gagal mereset data.", 'error');
                        }
                    } catch (e) {
                        console.error("Reset failed", e);
                        this.showToast("Terjadi kesalahan koneksi server.", 'error');
                    } finally {
                        this.isSubmitting = false;
                    }
                },

                // LUCKY DRAW: Memulai pengundian pemenang
                startDrawing() {
                    // Update pool kupon yang valid terlebih dahulu
                    const currentWinnerNumbers = this.winnerHistory.map(w => w.ticket_number);
                    const pool = this.tickets.filter(t => 
                        t.status === 'approved' && !currentWinnerNumbers.includes(t.ticket_number)
                    );

                    if (pool.length === 0) {
                        alert("Tidak ada kupon berkategori Approved yang tersedia untuk diundi! Pastikan sudah menyetujui pemesanan warga.");
                        return;
                    }

                    this.isDrawing = true;
                    
                    const duration = 10000; // 10 seconds total duration
                    const startTime = Date.now();
                    
                    const cycle = () => {
                        const now = Date.now();
                        const elapsed = now - startTime;
                        
                        // Calculate progress from 0 to 1
                        const progress = Math.min(elapsed / duration, 1);
                        
                        const randomIndex = Math.floor(Math.random() * pool.length);
                        const ticketObj = pool[randomIndex];

                        // Tampilkan visual acak
                        this.currentDrawNumber = ticketObj.ticket_number;
                        this.currentDrawName = ticketObj.name;
                        
                        // Play tension-building tick
                        this.playDrawTick(progress);

                        if (progress < 1) {
                            // Calculate deceleration speed: starts fast (30ms), ends slow (600ms)
                            // Ease-out cubic curve: delay = min_delay + (max_delay - min_delay) * progress^3.5
                            const speed = 30 + Math.pow(progress, 3.5) * 570;
                            setTimeout(cycle, speed);
                        } else {
                            // Final winner chosen!
                            this.latestWinner = ticketObj;
                            this.currentDrawNumber = ticketObj.ticket_number;
                            this.currentDrawName = ticketObj.name;
                            
                            // Save to winnerHistory locally
                            this.winnerHistory.unshift({
                                name: ticketObj.name,
                                ticket_number: ticketObj.ticket_number,
                                whatsapp: ticketObj.whatsapp,
                                rt_rw: ticketObj.rt_rw,
                                drawn_at: new Date().toLocaleTimeString('id-ID')
                            });
                            localStorage.setItem('ri_doorprize_winners', JSON.stringify(this.winnerHistory));

                            // Refresh active drawing pool
                            this.recalculateStats();

                            // Audio & Visual celebratory effects
                            this.playVictorySound();
                            this.triggerConfetti();

                            // Speak winner digits in Indonesian after the brass fanfare completes (2.2 seconds)
                            setTimeout(() => {
                                this.speakWinner(ticketObj.ticket_number);
                            }, 2200);

                            setTimeout(() => {
                                this.showDrawModal = true;
                                this.isDrawing = false;
                            }, 500);
                        }
                    };

                    cycle();
                },

                // Confetti explosion
                triggerConfetti() {
                    const duration = 2 * 1000;
                    const end = Date.now() + duration;

                    const frame = () => {
                        confetti({
                            particleCount: 5,
                            angle: 60,
                            spread: 55,
                            origin: { x: 0 },
                            colors: ['#BE123C', '#E11D48', '#FFFFFF', '#F59E0B']
                        });
                        confetti({
                            particleCount: 5,
                            angle: 120,
                            spread: 55,
                            origin: { x: 1 },
                            colors: ['#BE123C', '#E11D48', '#FFFFFF', '#F59E0B']
                        });

                        if (Date.now() < end) {
                            requestAnimationFrame(frame);
                        }
                    };
                    frame();
                },

                // Lucky draw winner WhatsApp contact link
                getWhatsAppWinnerLink() {
                    if (!this.latestWinner) return '#';
                    const nums = this.latestWinner.ticket_number;
                    const text = `Selamat Warga!\n\nNama Anda telah memenangkan undian doorprize Semarak Kemerdekaan HUT RI Ke-81 Karang Taruna RW 004!\n\n*Nomor Kupon:* Kupon #${nums}\n*Nama Pemenang:* ${this.latestWinner.name}\n*RT/RW:* ${this.latestWinner.rt_rw}\n\nSilakan tunjukkan kupon ini kepada panitia untuk menukarkan hadiah Anda! Merdeka! 🇮🇩`;
                    return `https://wa.me/${this.latestWinner.whatsapp.replace(/[^0-9]/g, '')}?text=${encodeURIComponent(text)}`;
                },

                // Delete Single Winner
                deleteWinner(idx) {
                    const confirmed = confirm("Hapus pemenang ini dari daftar riwayat pemenang? (Dia akan dimasukkan kembali ke dalam pool undian).");
                    if (!confirmed) return;

                    this.winnerHistory.splice(idx, 1);
                    localStorage.setItem('ri_doorprize_winners', JSON.stringify(this.winnerHistory));
                    this.recalculateStats();
                    this.showToast("Pemenang berhasil dihapus dan dikembalikan ke pool undian.", 'success');
                    this.playBeep(280.00, 0.15);
                },

                // Clear all winners
                clearWinners() {
                    const confirmed = confirm("Apakah Anda yakin ingin menghapus seluruh riwayat pemenang undian? Pool undian akan disegarkan sepenuhnya.");
                    if (!confirmed) return;

                    this.winnerHistory = [];
                    localStorage.removeItem('ri_doorprize_winners');
                    this.recalculateStats();
                    this.showToast("Seluruh riwayat pemenang telah dibersihkan.", 'success');
                    this.playBeep(180.00, 0.3);
                }
            }
        }
    </script>
</body>
</html>