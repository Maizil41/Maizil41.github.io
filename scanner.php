<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <script src="js/qrcodejs"></script>
    <style>
        #reader {
            width: 300px;
            margin: auto;
        }
    </style>
</head>
<body>
    <h1><cnter>Scan QR Code</h1>
    <div id="reader"></div>
      
    <script>
        function onScanSuccess(decodedText, decodedResult) {

            const username = decodedText;

            const redirectUrl = `http://10.10.10.1:3990/login?username=${encodeURIComponent(username)}&password=Accept`;

            window.location.href = redirectUrl;
        }

        function onScanFailure(error) {
            console.warn(`Kode gagal dipindai: ${error}`);
        }

        const html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", 
            { 
                fps: 10, 
                qrbox: 250, 
                supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
            },
            false
        );

        html5QrcodeScanner.render(onScanSuccess, onScanFailure)
            .then(() => {

                Html5Qrcode.getCameras()
                    .then(cameras => {
                        if (cameras && cameras.length > 1) {

                            html5QrcodeScanner.getCameras()
                                .then(cameras => {
                                    if (cameras && cameras.length > 1) {

                                        html5QrcodeScanner.start(
                                            { facingMode: { exact: "environment" } }, 
                                            { fps: 10, qrbox: 250 },
                                            onScanSuccess,
                                            onScanFailure
                                        );
                                    } else {
                                        console.error("Kamera belakang tidak ditemukan.");
                                    }
                                })
                                .catch(err => console.error("Gagal mendapatkan daftar kamera: ", err));
                        }
                    })
                    .catch(err => console.error("Gagal mendapatkan daftar kamera: ", err));
            })
            .catch(err => console.error("Gagal memulai scanner: ", err));
    </script>
</body>
</html>
