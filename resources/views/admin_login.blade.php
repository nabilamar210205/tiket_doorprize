<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Panitia - Booking Doorprize HUT RI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero-pattern {
            background-color: #BE123C;
            background-image: radial-gradient(rgba(255,255,255,0.12) 1px, transparent 0),
                              radial-gradient(rgba(255,255,255,0.12) 1px, transparent 0);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
        }
    </style>
</head>
<body class="hero-pattern min-h-screen flex items-center justify-center p-4 font-[Plus_Jakarta_Sans]">
    <div class="w-full max-w-md">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 rounded-2xl bg-white/20 border-2 border-white/30 backdrop-blur flex items-center justify-center mx-auto mb-4 shadow-xl overflow-hidden p-1.5 bg-slate-900">
                <img src="{{ asset('logo.jpg') }}" alt="Logo Karang Taruna" class="w-full h-full object-contain rounded-xl">
            </div>
            <h1 class="text-3xl font-[Outfit] font-black text-white">Panel Panitia</h1>
            <p class="text-rose-200 text-sm mt-1">HUT RI Ke-81 · Karang Taruna RW 004</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-3xl p-8 shadow-2xl border border-white/10">
            <h2 class="text-xl font-bold text-slate-800 mb-1">Masuk ke Dasbor Panitia</h2>
            <p class="text-sm text-slate-400 mb-6">Masukkan kode akses khusus yang diberikan oleh Ketua Panitia.</p>

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 mb-5 flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.login') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="passcode" class="block text-sm font-semibold text-slate-600 mb-1.5">Kode Akses Panitia</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <i class="fa-solid fa-shield-halved"></i>
                        </span>
                        <input type="password" id="passcode" name="passcode" required autofocus
                               class="w-full pl-10 pr-4 py-3.5 rounded-xl border border-slate-200 focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 outline-none transition text-slate-800 font-mono tracking-widest text-lg"
                               placeholder="••••••••" maxlength="20">
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1.5">
                        <i class="fa-solid fa-circle-info mr-1"></i>
                        Hubungi Ketua Panitia jika tidak mengetahui kode akses.
                    </p>
                </div>

                <button type="submit"
                        class="w-full py-4 bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-700 hover:to-rose-700 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 flex items-center justify-center gap-2 cursor-pointer text-sm">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Masuk ke Panel Panitia
                </button>
            </form>

            <div class="mt-5 pt-5 border-t border-slate-100 text-center">
                <a href="{{ route('home') }}" class="text-xs text-slate-400 hover:text-rose-600 transition flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali ke Halaman Booking Warga
                </a>
            </div>
        </div>

        <p class="text-center text-rose-200/60 text-xs mt-6">© 2026 Karang Taruna RW 004</p>
    </div>
</body>
</html>
