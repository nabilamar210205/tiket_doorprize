<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    // Total slot tiket (e.g. 5000 tiket dari 0001 sampai 5000)
    protected $totalSlots = 5000;

    /**
     * Tampilan utama Dashboard Warga
     */
    public function index()
    {
        $totalSlots = $this->totalSlots;
        $bookedCount = Ticket::count();
        $approvedCount = Ticket::where('status', 'approved')->count();
        $pendingCount = Ticket::where('status', 'pending')->count();
        $availableCount = $totalSlots - $bookedCount;

        // Ambil semua nomor tiket yang terbooking beserta statusnya
        $bookedTickets = Ticket::pluck('status', 'ticket_number')->toArray();

        // Daftar pemenang undian (untuk ditampilkan di homepage)
        // Kita bisa ambil dari session atau file jika disimpan secara permanen. 
        // Untuk saat ini, pemenang bisa di-manage di view admin atau dikirim ke view warga.

        return view('dashboard', compact(
            'totalSlots',
            'bookedCount',
            'approvedCount',
            'pendingCount',
            'availableCount',
            'bookedTickets'
        ));
    }

    /**
     * Memproses booking tiket (Manual / Acak)
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
            'rt_rw' => 'required|string|max:20',
            'booking_type' => 'required|in:manual,random',
        ];

        if ($request->input('booking_type') === 'manual') {
            $rules['selected_tickets'] = 'required|array|min:1|max:20';
            $rules['selected_tickets.*'] = 'string|min:3|max:4';
        } else {
            $rules['ticket_qty'] = 'required|integer|min:1|max:20';
        }

        $validator = Validator::make($request->all(), $rules, [
            'name.required' => 'Nama lengkap wajib diisi!',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi!',
            'rt_rw.required' => 'RT/RW wajib diisi!',
            'selected_tickets.required' => 'Silakan pilih minimal 1 nomor tiket!',
            'selected_tickets.min' => 'Silakan pilih minimal 1 nomor tiket!',
            'selected_tickets.max' => 'Maksimal booking adalah 20 tiket sekali pesan.',
            'ticket_qty.required' => 'Silakan tentukan jumlah tiket!',
            'ticket_qty.max' => 'Maksimal booking adalah 20 tiket sekali pesan.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $name = trim($request->input('name'));
        $whatsapp = trim($request->input('whatsapp'));
        $rtRw = trim($request->input('rt_rw'));
        $bookingType = $request->input('booking_type');
        $ticketsToBook = [];

        try {
            DB::beginTransaction();

            if ($bookingType === 'manual') {
                $selectedTickets = $request->input('selected_tickets');
                
                // Cek apakah nomor tiket berada dalam range valid (001 - totalSlots)
                foreach ($selectedTickets as $ticketNum) {
                    $numInt = (int)$ticketNum;
                    if ($numInt < 1 || $numInt > $this->totalSlots) {
                        return response()->json([
                            'success' => false,
                            'message' => "Nomor tiket {$ticketNum} tidak valid! Range tiket adalah " . str_pad('1', strlen((string)$this->totalSlots), '0', STR_PAD_LEFT) . " sampai " . str_pad($this->totalSlots, strlen((string)$this->totalSlots), '0', STR_PAD_LEFT)
                        ], 422);
                    }
                }

                // Cek apakah tiket sudah dibooking orang lain
                $existing = Ticket::whereIn('ticket_number', $selectedTickets)->pluck('ticket_number')->toArray();
                if (!empty($existing)) {
                    $takenList = implode(', ', $existing);
                    return response()->json([
                        'success' => false,
                        'message' => "Nomor tiket ({$takenList}) sudah dipesan oleh warga lain. Silakan pilih nomor lainnya!"
                    ], 422);
                }

                $ticketsToBook = $selectedTickets;
            } else {
                // Booking Acak
                $qty = (int)$request->input('ticket_qty');
                
                // Ambil semua tiket yang sudah terbooking
                $alreadyBooked = Ticket::pluck('ticket_number')->toArray();
                
                // Buat pool tiket yang tersedia
                $availablePool = [];
                for ($i = 1; $i <= $this->totalSlots; $i++) {
                    $formattedNum = str_pad($i, strlen((string)$this->totalSlots), '0', STR_PAD_LEFT);
                    if (!in_array($formattedNum, $alreadyBooked)) {
                        $availablePool[] = $formattedNum;
                    }
                }

                if (count($availablePool) < $qty) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tiket yang tersedia sudah habis atau tidak mencukupi untuk jumlah yang diminta!'
                    ], 422);
                }

                // Ambil acak dari pool
                shuffle($availablePool);
                $ticketsToBook = array_slice($availablePool, 0, $qty);
            }

            // Simpan tiket ke database
            $bookedResults = [];
            foreach ($ticketsToBook as $ticketNumber) {
                $ticket = Ticket::create([
                    'ticket_number' => $ticketNumber,
                    'name' => $name,
                    'whatsapp' => $whatsapp,
                    'rt_rw' => $rtRw,
                    'status' => 'pending' // default pending, harus dikonfirmasi admin
                ]);
                $bookedResults[] = $ticket;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil diajukan! Harap hubungi panitia untuk konfirmasi pembayaran/tiket.',
                'tickets' => $bookedResults
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API untuk pencarian tiket warga (live search)
     */
    public function search(Request $request)
    {
        $query = trim($request->input('query'));

        if (empty($query)) {
            return response()->json([]);
        }

        $tickets = Ticket::where('name', 'like', "%{$query}%")
            ->orWhere('whatsapp', 'like', "%{$query}%")
            ->orWhere('ticket_number', 'like', "%{$query}%")
            ->orWhere('rt_rw', 'like', "%{$query}%")
            ->orderBy('ticket_number', 'asc')
            ->get();

        return response()->json($tickets);
    }

    /**
     * Halaman Dashboard Admin & Undian Doorprize
     */
    public function adminIndex(Request $request)
    {
        // Proteksi password sederhana bertema agustusan
        if (!session('admin_logged_in')) {
            return view('admin_login');
        }

        $totalSlots = $this->totalSlots;
        $tickets = Ticket::orderBy('ticket_number', 'asc')->get();
        
        $bookedCount = $tickets->count();
        $approvedCount = $tickets->where('status', 'approved')->count();
        $pendingCount = $tickets->where('status', 'pending')->count();
        $availableCount = $totalSlots - $bookedCount;

        // Ambil semua tiket yang approved untuk roda undian
        $approvedTickets = $tickets->where('status', 'approved')->values()->toArray();

        return view('admin', compact(
            'tickets',
            'totalSlots',
            'bookedCount',
            'approvedCount',
            'pendingCount',
            'availableCount',
            'approvedTickets'
        ));
    }

    /**
     * Proses Login Admin
     */
    public function adminLogin(Request $request)
    {
        $passcode = $request->input('passcode');
        
        // Passcode default: admin17
        if ($passcode === 'admin17' || $passcode === 'merdeka79') {
            session(['admin_logged_in' => true]);
            return redirect()->route('admin.index')->with('success', 'Selamat datang, Panitia HUT RI!');
        }

        return redirect()->back()->with('error', 'Kode Akses Salah! Silakan coba lagi.');
    }

    /**
     * Proses Logout Admin
     */
    public function adminLogout()
    {
        session()->forget('admin_logged_in');
        return redirect()->route('home')->with('success', 'Berhasil logout dari mode admin.');
    }

    /**
     * Konfirmasi / Approve Tiket (Admin Only)
     */
    public function approve($id)
    {
        if (!session('admin_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $ticket = Ticket::findOrFail($id);
            $ticket->status = 'approved';
            $ticket->save();

            return response()->json([
                'success' => true,
                'message' => "Tiket Nomor #{$ticket->ticket_number} berhasil dikonfirmasi!"
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Membatalkan / Menghapus Tiket (Admin Only)
     */
    public function cancel($id)
    {
        if (!session('admin_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $ticket = Ticket::findOrFail($id);
            $number = $ticket->ticket_number;
            $ticket->delete();

            return response()->json([
                'success' => true,
                'message' => "Pemesanan tiket nomor #{$number} berhasil dibatalkan!"
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Download seluruh tiket berkategori Approved dalam format PDF
     */
    public function downloadPdf()
    {
        // Proteksi admin
        if (!session('admin_logged_in')) {
            return redirect()->route('admin.index')->with('error', 'Silakan login terlebih dahulu.');
        }

        $tickets = Ticket::where('status', 'approved')->orderBy('ticket_number', 'asc')->get();

        if ($tickets->isEmpty()) {
            return redirect()->route('admin.index')->with('error', 'Belum ada tiket berkategori Approved yang dapat diunduh!');
        }

        // Susun HTML untuk PDF (Menggunakan tabel sederhana yang didukung penuh oleh Dompdf)
        $html = '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kupon Doorprize HUT RI Ke-81</title>
    <style>
        @page {
            margin: 20px;
        }
        body {
            font-family: sans-serif;
            font-size: 11px;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        .page-header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px dashed #cbd5e1;
            padding-bottom: 10px;
        }
        .page-header h1 {
            margin: 0;
            color: #be123c;
            font-size: 20px;
        }
        .page-header p {
            margin: 3px 0 0;
            font-size: 11px;
            color: #64748b;
        }
        .grid-table {
            width: 100%;
        }
        .grid-table td {
            width: 50%;
            padding: 8px;
            vertical-align: top;
        }
        .coupon-card {
            border: 2px dashed #be123c;
            border-radius: 12px;
            background: white;
            overflow: hidden;
            position: relative;
        }
        .card-header {
            background: #be123c;
            color: white;
            padding: 10px;
            text-align: center;
        }
        .card-header h2 {
            margin: 0;
            font-size: 13px;
            letter-spacing: 1px;
        }
        .card-header p {
            margin: 1px 0 0;
            font-size: 8px;
            opacity: 0.9;
        }
        .card-body {
            padding: 12px;
        }
        .body-table {
            width: 100%;
        }
        .ticket-number {
            background: #fff5f5;
            border: 2px dashed #fca5a5;
            border-radius: 8px;
            padding: 8px 5px;
            text-align: center;
            width: 80px;
        }
        .ticket-number span {
            display: block;
            font-size: 7px;
            color: #991b1b;
            font-weight: bold;
            text-transform: uppercase;
        }
        .ticket-number strong {
            display: block;
            font-size: 22px;
            color: #be123c;
            font-family: monospace;
            margin-top: 1px;
        }
        .ticket-details {
            padding-left: 10px;
            vertical-align: middle;
        }
        .rt-badge {
            display: inline-block;
            background: #fef3c7;
            color: #d97706;
            font-size: 8px;
            font-weight: bold;
            padding: 1px 4px;
            border-radius: 4px;
        }
        .ticket-details h3 {
            margin: 3px 0 0;
            font-size: 13px;
            color: #1e293b;
        }
        .ticket-details p {
            margin: 2px 0 0;
            font-size: 9px;
            color: #64748b;
        }
        .card-footer {
            background: #f8fafc;
            border-top: 1px dashed #e2e8f0;
            padding: 6px;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            font-weight: bold;
            text-transform: uppercase;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    <div class="page-header">
        <h1>Kupon Doorprize Kemerdekaan</h1>
        <p>Total Kupon Terkonfirmasi: ' . $tickets->count() . ' Pcs · Cetak via PDF</p>
    </div>

    <table class="grid-table">';

        $chunks = $tickets->chunk(2);
        $rowCount = 0;
        
        foreach ($chunks as $pair) {
            if ($rowCount > 0 && $rowCount % 3 == 0) {
                // Halaman baru setelah 3 baris (6 kupon)
                $html .= '</table>
                <div class="page-break"></div>
                <table class="grid-table" style="margin-top: 20px;">';
            }

            $html .= '<tr>';
            foreach ($pair as $ticket) {
                $html .= '
                <td>
                    <div class="coupon-card">
                        <div class="card-header">
                            <h2>KUPON DOORPRIZE</h2>
                            <p>SEMARAK KEMERDEKAAN HUT RI KE-81</p>
                        </div>
                        <div class="card-body">
                            <table class="body-table">
                                <tr>
                                    <td style="width: 80px; padding: 0;">
                                        <div class="ticket-number">
                                            <span>No Kupon</span>
                                            <strong>' . $ticket->ticket_number . '</strong>
                                        </div>
                                    </td>
                                    <td class="ticket-details" style="padding: 0 0 0 10px;">
                                        <span class="rt-badge">' . htmlspecialchars(str_replace('RW 02', 'RW 004', $ticket->rt_rw)) . '</span>
                                        <h3>' . htmlspecialchars($ticket->name) . '</h3>
                                        <p>WA: ' . htmlspecialchars($ticket->whatsapp) . '</p>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="card-footer">
                            Karang Taruna RW 004
                        </div>
                    </div>
                </td>';
            }
            if ($pair->count() < 2) {
                $html .= '<td></td>';
            }
            $html .= '</tr>';
            $rowCount++;
        }

        $html .= '
    </table>

</body>
</html>';

        $dompdf = new \Dompdf\Dompdf();
        
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('a4', 'portrait');
        $dompdf->render();
        
        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="Kupon_Doorprize_HUT_RI_81_Approved.pdf"',
        ]);
    }

    /**
     * Reset Semua Data Tiket (Admin Only)
     */
    public function reset()
    {
        if (!session('admin_logged_in')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            Ticket::truncate();
            return response()->json([
                'success' => true,
                'message' => 'Seluruh data tiket berhasil di-reset!'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
