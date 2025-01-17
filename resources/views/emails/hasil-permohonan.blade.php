<!DOCTYPE html>
<html>
<head>
    <title>Status Permohonan Anda</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Reset styles */
        body, p, h1, h2, h3, h4, h5, h6 {
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
            <h1>Status Permohonan Anda</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p>Halo <strong>{{ $nama }}</strong>,</p>
            <p>Status pemohon: <strong>{{ $status_pemohon }}</strong></p>
            <p>Kategori: <strong>{{ $kategori }}</strong></p>
            <p>Permohonan Anda saat ini berstatus: <strong>{{ $status }}</strong>.</p>
        
            @if($status === 'Ditolak' && $reason)
                <p><strong>Alasan penolakan:</strong> {{ $reason }}</p>
            @endif
        
            @if($status === 'Disetujui')
                <p>Dokumen jawaban telah dilampirkan pada email ini.</p>
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
