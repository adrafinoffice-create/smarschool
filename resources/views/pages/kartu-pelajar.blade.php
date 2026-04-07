<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Pelajar – {{ $siswa->nama }}</title>
    <style>
        @page {
            margin: 15mm 15mm;
            size: A4;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f0f4ff;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 30px 20px;
        }

        /* ─── Outer wrapper ─── */
        .card-wrap {
            width: 100%;
            max-width: 680px;
            margin: 0 auto;
        }

        /* ─── Card ─── */
        .card {
            width: 100%;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 40px rgba(30, 58, 138, 0.18);
            background: #ffffff;
            position: relative;
        }

        /* ─── Header gradient ─── */
        .card-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 55%, #3b82f6 100%);
            padding: 22px 28px 80px 28px;
            position: relative;
            overflow: hidden;
        }

        /* Decorative circles in header */
        .card-header::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 160px; height: 160px;
            border-radius: 50%;
            background: rgba(255,255,255,0.07);
        }
        .card-header::after {
            content: '';
            position: absolute;
            bottom: -20px; left: 30%;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }

        /* ─── School row ─── */
        .school-row {
            display: table;
            width: 100%;
        }
        .school-logo-cell {
            display: table-cell;
            width: 72px;
            vertical-align: middle;
            padding-right: 16px;
        }
        .school-logo {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: white;
            overflow: hidden;
            border: 3px solid rgba(255,255,255,0.6);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .school-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .school-info-cell {
            display: table-cell;
            vertical-align: middle;
        }
        .school-name {
            font-size: 20px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.3px;
            line-height: 1.2;
        }
        .school-address {
            font-size: 10px;
            color: rgba(255,255,255,0.80);
            margin-top: 4px;
            line-height: 1.5;
        }

        /* ─── "KARTU TANDA SISWA" banner ─── */
        .kts-banner {
            margin-top: 16px;
            display: inline-block;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 6px;
            padding: 4px 14px;
            font-size: 11px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* ─── Main body (white) ─── */
        .card-body {
            background: #ffffff;
            margin-top: -48px;
            border-radius: 16px 16px 0 0;
            padding: 24px 28px 0 28px;
            position: relative;
            z-index: 2;
        }

        /* ─── Content layout ─── */
        .content-table {
            width: 100%;
            border-collapse: collapse;
        }
        .photo-td {
            width: 130px;
            vertical-align: top;
            padding-right: 24px;
        }
        .photo-box {
            width: 120px;
            height: 150px;
            border-radius: 10px;
            overflow: hidden;
            border: 3px solid #e0e7ff;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 6px;
        }
        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .photo-placeholder-icon {
            font-size: 36px;
            color: #c7d2fe;
            display: block;
        }
        .photo-placeholder-text {
            font-size: 9px;
            color: #a5b4fc;
            text-align: center;
            letter-spacing: 0.5px;
        }

        /* ─── Student info ─── */
        .info-td {
            vertical-align: top;
            padding-top: 4px;
        }
        .student-name {
            font-size: 17px;
            font-weight: 800;
            color: #1e3a8a;
            margin-bottom: 12px;
            line-height: 1.25;
        }
        .info-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .info-label {
            font-size: 9.5px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 4px 0;
            width: 90px;
            vertical-align: top;
        }
        .info-sep {
            font-size: 11px;
            color: #9ca3af;
            padding: 4px 8px;
            vertical-align: top;
        }
        .info-value {
            font-size: 11px;
            color: #1f2937;
            font-weight: 600;
            padding: 4px 0;
            vertical-align: top;
            line-height: 1.4;
        }

        /* ─── Divider stripe ─── */
        .stripe {
            height: 5px;
            background: linear-gradient(90deg, #1e3a8a, #3b82f6, #60a5fa, #93c5fd);
            margin-top: 22px;
        }

        /* ─── Footer ─── */
        .card-footer {
            padding: 16px 28px 22px 28px;
            background: #f8faff;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }
        .expiry-td {
            vertical-align: bottom;
            width: 38%;
        }
        .expiry-label {
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 600;
        }
        .expiry-date {
            font-size: 12px;
            font-weight: 800;
            color: #1e3a8a;
            margin-top: 3px;
        }
        .principal-td {
            text-align: center;
            vertical-align: bottom;
            width: 30%;
        }
        .principal-label {
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            font-weight: 600;
        }
        .principal-sig {
            height: 32px;
            margin: 6px 0;
            border-bottom: 1px solid #d1d5db;
        }
        .principal-name {
            font-size: 11px;
            font-weight: 700;
            color: #1e3a8a;
        }
        .qr-td {
            text-align: right;
            vertical-align: bottom;
            width: 32%;
        }
        .qr-label {
            font-size: 8px;
            color: #9ca3af;
            text-align: right;
            margin-bottom: 4px;
            letter-spacing: 0.4px;
        }
        .qr-img {
            width: 80px;
            height: 80px;
            border: 2px solid #e0e7ff;
            border-radius: 8px;
            padding: 3px;
            background: white;
            display: inline-block;
        }
        .qr-img img {
            width: 100%;
            height: 100%;
        }

        /* ─── Bottom accent bar ─── */
        .accent-bar {
            height: 8px;
            background: linear-gradient(90deg, #1e3a8a, #2563eb, #3b82f6);
        }
    </style>
</head>

<body>
    <div class="card-wrap">
        <div class="card">

            {{-- ── HEADER ── --}}
            <div class="card-header">
                <div class="school-row">
                    <div class="school-logo-cell">
                        <div class="school-logo">
                            @if($logo)
                                <img src="data:image/png;base64,{{ $logo }}" alt="Logo">
                            @endif
                        </div>
                    </div>
                    <div class="school-info-cell">
                        <div class="school-name">{{ $pengaturan->nama_sekolah ?? 'NAMA SEKOLAH' }}</div>
                        <div class="school-address">{{ $pengaturan->alamat ?? '' }}</div>
                    </div>
                </div>
                <div>
                    <span class="kts-banner">Kartu Tanda Siswa</span>
                </div>
            </div>

            {{-- ── BODY ── --}}
            <div class="card-body">
                <table class="content-table" border="0">
                    <tr>
                        {{-- Foto --}}
                        <td class="photo-td">
                            <div class="photo-box">
                                <span class="photo-placeholder-icon">&#128100;</span>
                                <span class="photo-placeholder-text">FOTO SISWA</span>
                            </div>
                        </td>

                        {{-- Info Siswa --}}
                        <td class="info-td">
                            <div class="student-name">{{ $siswa->nama }}</div>
                            <table class="info-grid" border="0">
                                <tr>
                                    <td class="info-label">NIS</td>
                                    <td class="info-sep">:</td>
                                    <td class="info-value">{{ $siswa->nis }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Kelas</td>
                                    <td class="info-sep">:</td>
                                    <td class="info-value">{{ $siswa->kelas?->nama_kelas ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Tgl Lahir</td>
                                    <td class="info-sep">:</td>
                                    <td class="info-value">
                                        {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="info-label">Tempat</td>
                                    <td class="info-sep">:</td>
                                    <td class="info-value">{{ $siswa->tempat_lahir }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Alamat</td>
                                    <td class="info-sep">:</td>
                                    <td class="info-value">{{ $siswa->alamat }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">Orang Tua</td>
                                    <td class="info-sep">:</td>
                                    <td class="info-value">{{ $siswa->nama_orang_tua }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>

            {{-- ── Stripe divider ── --}}
            <div class="stripe"></div>

            {{-- ── FOOTER ── --}}
            <div class="card-footer">
                <table class="footer-table" border="0">
                    <tr>
                        <td class="expiry-td">
                            <div class="expiry-label">Berlaku Sampai</div>
                            <div class="expiry-date">
                                {{ \Carbon\Carbon::now()->addYears(3)->translatedFormat('d F Y') }}
                            </div>
                        </td>
                        <td class="principal-td">
                            <div class="principal-label">Kepala Sekolah</div>
                            <div class="principal-sig"></div>
                            <div class="principal-name">{{ $pengaturan->kepala_sekolah ?? '' }}</div>
                        </td>
                        <td class="qr-td">
                            <div class="qr-label">SCAN UNTUK VERIFIKASI</div>
                            <div class="qr-img">
                                <img src="{{ $qrCode }}" alt="QR Code">
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            {{-- ── Bottom bar ── --}}
            <div class="accent-bar"></div>

        </div>
    </div>
</body>

</html>
