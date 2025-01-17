<!DOCTYPE html>
<html>

<head>
    <title>Status Pengajuan Pertanyaan Anda</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Reset styles */
        body,
        p,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            padding: 0;
            margin: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background: #55ceec;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .email-header h1 {
            font-size: 24px;
        }

        .email-body {
            padding: 20px;
        }

        .email-body p {
            margin-bottom: 15px;
        }

        .email-footer {
            background: #f4f4f4;
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
        }

        /* Responsive styles */
        @media (max-width: 600px) {
            .email-header h1 {
                font-size: 20px;
            }

            .email-body {
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>Status Pengajuan Pertanyaan Anda</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p>Halo <strong>{{ $nama }}</strong>,</p>
            <p>Status pemohon: <strong>{{ $status_pemohon }}</strong></p>
            <p>Kategori: <strong>{{ $kategori }}</strong></p>
            <p>Pengajuan Pertanyaan Anda saat ini berstatus: <strong>{{ $status }}</strong>.</p>

            @if($status === 'Disetujui' && !empty($detailPertanyaan))
                <p><strong>Berikut adalah pertanyaan dan jawaban terkait:</strong></p>
                <ul style="list-style-type: none; padding-left: 0;">
                    @foreach($detailPertanyaan as $detail)
                        <li style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                            <p><strong>Pertanyaan:</strong><br>
                                {{ $detail['pertanyaan'] }}</p>
                            <p><strong>Jawaban:</strong><br>
                                {{ $detail['jawaban'] }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>Terima kasih atas perhatian Anda.</p>
            <p>&copy; 2025 PPDI-Polinema</p>
        </div>
    </div>
</body>

</html>