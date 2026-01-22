<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Help Desk</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.45;
            color: #1f2937;
            padding: 20px 26px 40px;
            background: #f8fafc;
        }

        h1,
        h2,
        h3,
        h4 {
            color: #0f172a;
        }

        .brand {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            color: #4338ca;
        }

        .logo-mark {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: linear-gradient(135deg, #6366f1, #c084fc);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 14px;
        }

        .meta {
            text-align: right;
            color: #475569;
            font-size: 10px;
        }

        .header {
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #ffffff;
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.06);
            margin-bottom: 18px;
        }

        .header h1 {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .header p {
            font-size: 11px;
            color: #475569;
        }

        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title:before {
            content: '';
            width: 8px;
            height: 8px;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            border-radius: 50%;
            display: inline-block;
        }

        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 8px;
            border-spacing: 10px 0;
        }

        .stat-box {
            display: table-cell;
            width: 25%;
            padding: 12px 10px;
            text-align: left;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
        }

        .stat-label {
            font-size: 10px;
            color: #64748b;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .stat-number {
            font-size: 18px;
            font-weight: 800;
            color: #4338ca;
        }

        .stat-desc {
            font-size: 10px;
            color: #94a3b8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }

        thead th {
            background: #1e293b;
            color: #e2e8f0;
            padding: 8px 6px;
            text-align: left;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        tbody td {
            padding: 7px 6px;
            border-bottom: 1px solid #e2e8f0;
            color: #1f2937;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 9px;
            font-weight: 700;
        }

        .badge-open {
            background: #e0e7ff;
            color: #4338ca;
        }

        .badge-in-progress {
            background: #fef3c7;
            color: #b45309;
        }

        .badge-resolved {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-closed {
            background: #e2e8f0;
            color: #475569;
        }

        .badge-low {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-medium {
            background: #fef3c7;
            color: #b45309;
        }

        .badge-high {
            background: #ffe4e6;
            color: #be123c;
        }

        .badge-critical {
            background: #fecdd3;
            color: #b91c1c;
        }

        .muted {
            color: #94a3b8;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .page-break {
            page-break-before: always;
        }

        .footer {
            position: fixed;
            bottom: 10px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
        }

        .footer .page:after {
            content: 'Halaman ' counter(page) ' dari ' counter(pages);
        }

        .pill {
            padding: 2px 8px;
            border-radius: 6px;
            background: #eef2ff;
            color: #4338ca;
            font-size: 10px;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <div class="brand">
        <div class="logo">
            <span class="logo-mark">HD</span>
            <span>IT Help Desk</span>
        </div>
        <div class="meta">
            <div>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} –
                {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</div>
            <div>Dibuat: {{ now()->format('d M Y, H:i') }}</div>
        </div>
    </div>

    <div class="header">
        <h1>Laporan Kinerja Help Desk</h1>
        <p>Ringkasan operasional tiket, kategori, teknisi, dan aktivitas pengguna dalam periode terpilih.</p>
    </div>

    <!-- Ringkasan Utama -->
    <div class="section">
        <div class="section-title">Ringkasan Statistik</div>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-label">Total Tiket</div>
                <div class="stat-number">{{ number_format($totalTickets) }}</div>
                <div class="stat-desc">Semua tiket tercatat</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Total Pengguna</div>
                <div class="stat-number">{{ number_format($totalUsers) }}</div>
                <div class="stat-desc">Pengguna non-teknisi</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Total Teknisi</div>
                <div class="stat-number">{{ number_format($totalTechnicians) }}</div>
                <div class="stat-desc">Petugas penanganan</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Rata-rata Respon</div>
                <div class="stat-number">{{ $avgResponseTime }} mnt</div>
                <div class="stat-desc">Waktu respon pertama</div>
            </div>
        </div>
    </div>

    <!-- Status dan Prioritas -->
    <div class="section">
        <div class="section-title">Distribusi Status & Prioritas</div>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-label">Open</div>
                <div class="stat-number" style="color:#4338ca;">{{ $statusCounts['open'] }}</div>
                <div class="stat-desc">Menunggu penanganan</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">In Progress</div>
                <div class="stat-number" style="color:#b45309;">{{ $statusCounts['in_progress'] }}</div>
                <div class="stat-desc">Sedang dikerjakan</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Resolved</div>
                <div class="stat-number" style="color:#15803d;">{{ $statusCounts['resolved'] }}</div>
                <div class="stat-desc">Selesai, menunggu konfirmasi</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Closed</div>
                <div class="stat-number" style="color:#475569;">{{ $statusCounts['closed'] }}</div>
                <div class="stat-desc">Tiket ditutup</div>
            </div>
        </div>
        <div class="stats-grid" style="margin-top:4px;">
            <div class="stat-box">
                <div class="stat-label">Prioritas Rendah</div>
                <div class="stat-number" style="color:#15803d;">{{ $priorityCounts['low'] }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Prioritas Sedang</div>
                <div class="stat-number" style="color:#b45309;">{{ $priorityCounts['medium'] }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Prioritas Tinggi</div>
                <div class="stat-number" style="color:#be123c;">{{ $priorityCounts['high'] }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Prioritas Kritis</div>
                <div class="stat-number" style="color:#b91c1c;">{{ $priorityCounts['critical'] }}</div>
            </div>
        </div>
    </div>

    <!-- Daftar Tiket -->
    <div class="section">
        <div class="section-title">Daftar Tiket ({{ $tickets->count() }} tiket)</div>
        @if($tickets->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width:5%;">ID</th>
                        <th style="width:25%;">Judul</th>
                        <th style="width:15%;">Pengguna</th>
                        <th style="width:15%;">Kategori</th>
                        <th style="width:12%;">Status</th>
                        <th style="width:12%;">Prioritas</th>
                        <th style="width:16%;">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets->take(60) as $ticket)
                        <tr>
                            <td>#{{ $ticket->id }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($ticket->title, 32) }}</td>
                            <td>{{ $ticket->user->name ?? '-' }}</td>
                            <td>{{ $ticket->category->name ?? '-' }}</td>
                            <td>
                                <span class="badge badge-{{ str_replace('_', '-', $ticket->status) }}">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $ticket->priority }}">{{ ucfirst($ticket->priority) }}</span>
                            </td>
                            <td>{{ $ticket->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($tickets->count() > 60)
                <p class="muted" style="text-align:center; font-style:italic;">Menampilkan 60 dari {{ $tickets->count() }} tiket
                </p>
            @endif
        @else
            <p class="muted" style="text-align:center; padding: 16px;">Tidak ada tiket dalam periode ini</p>
        @endif
    </div>

    <div class="page-break"></div>

    <!-- Performa Kategori -->
    <div class="section">
        <div class="section-title">Performa per Kategori</div>
        @if($categoryStats->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Open</th>
                        <th class="text-center">In Progress</th>
                        <th class="text-center">Resolved</th>
                        <th class="text-center">Closed</th>
                        <th class="text-center">Rata-rata Resolusi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categoryStats as $stat)
                        <tr>
                            <td>{{ $stat['category']->name }}</td>
                            <td class="text-center"><strong>{{ $stat['total'] }}</strong></td>
                            <td class="text-center">{{ $stat['open'] }}</td>
                            <td class="text-center">{{ $stat['in_progress'] }}</td>
                            <td class="text-center">{{ $stat['resolved'] }}</td>
                            <td class="text-center">{{ $stat['closed'] }}</td>
                            <td class="text-center">{{ $stat['avg_resolution_time'] }} jam</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="muted" style="text-align:center; padding: 16px;">Tidak ada data kategori</p>
        @endif
    </div>

    <!-- Performa Teknisi -->
    <div class="section">
        <div class="section-title">Performa Teknisi</div>
        @if($technicianStats->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Teknisi</th>
                        <th class="text-center">Total Tiket</th>
                        <th class="text-center">Terselesaikan</th>
                        <th class="text-center">Dalam Proses</th>
                        <th class="text-center">Total Respon</th>
                        <th class="text-center">Rata-rata Resolusi</th>
                        <th class="text-center">Tingkat Penyelesaian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($technicianStats as $stat)
                        <tr>
                            <td>{{ $stat['technician']->name }}</td>
                            <td class="text-center">{{ $stat['total_assigned'] }}</td>
                            <td class="text-center">{{ $stat['resolved'] }}</td>
                            <td class="text-center">{{ $stat['in_progress'] }}</td>
                            <td class="text-center">{{ $stat['response_count'] }}</td>
                            <td class="text-center">{{ $stat['avg_resolution_time'] }} jam</td>
                            <td class="text-center">
                                {{ $stat['total_assigned'] > 0 ? round(($stat['resolved'] / $stat['total_assigned']) * 100) : 0 }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="muted" style="text-align:center; padding: 16px;">Tidak ada data teknisi</p>
        @endif
    </div>

    <!-- Aktivitas Pengguna -->
    <div class="section">
        <div class="section-title">Aktivitas Pengguna</div>
        @if($userStats->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width:5%;">#</th>
                        <th style="width:30%;">Pengguna</th>
                        <th class="text-center" style="width:10%;">Total</th>
                        <th class="text-center" style="width:10%;">Open</th>
                        <th class="text-center" style="width:10%;">Resolved</th>
                        <th class="text-center" style="width:10%;">Closed</th>
                        <th class="text-center" style="width:15%;">Tingkat Penyelesaian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userStats->take(25) as $index => $stat)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $stat['user']->name }}</td>
                            <td class="text-center"><strong>{{ $stat['total_tickets'] }}</strong></td>
                            <td class="text-center">{{ $stat['open'] }}</td>
                            <td class="text-center">{{ $stat['resolved'] }}</td>
                            <td class="text-center">{{ $stat['closed'] }}</td>
                            <td class="text-center">
                                @php
                                    $resolved = $stat['resolved'] + $stat['closed'];
                                    $rate = $stat['total_tickets'] > 0 ? round(($resolved / $stat['total_tickets']) * 100) : 0;
                                @endphp
                                {{ $rate }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($userStats->count() > 25)
                <p class="muted" style="text-align:center; font-style:italic;">Menampilkan 25 dari {{ $userStats->count() }}
                    pengguna</p>
            @endif
        @else
            <p class="muted" style="text-align:center; padding: 16px;">Tidak ada data aktivitas pengguna</p>
        @endif
    </div>

    <div class="footer">
        <span class="page"></span> • Laporan otomatis Sistem IT Help Desk
    </div>
</body>

</html>