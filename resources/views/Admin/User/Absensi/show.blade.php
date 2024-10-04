@extends('components.layout')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" crossorigin="anonymous">

<div class="container py-5">
    <div class="row">
        <div class="col-md-6 mb-3 mb-md-0">
            <h1 class="fs-2">{{ $attendance->title }}</h1>
            <p class="text-muted">{{ $attendance->description }}</p>

            <div class="mb-4">
                <span class="badge text-bg-light border shadow-sm">
                    Masuk: {{ substr($attendance->start_time, 0, -3) }} - {{ substr($attendance->batas_start_time ?? '', 0, -3) }}
                </span>
                <span class="badge text-bg-light border shadow-sm">
                    Pulang: {{ substr($attendance->end_time, 0 , -3) }} - {{ substr($attendance->batas_end_time ?? '', 0, -3) }}
                </span>
            </div>
        </div>

        <div class="col-md-6">
            <h4>Scan QR Code</h4>

            {{-- Button to open camera --}}
            <button id="openCamera" class="btn btn-primary mb-3">Open Camera</button>

            {{-- Camera feed and scan result elements --}}
            <div id="reader" style="width:500px; height: 500px; display: none;"></div>
            <p id="qr-result" class="font-weight-bold">QR Code Result: <span id="result"></span></p>
            <p id="cameraError" class="text-danger"></p> <!-- For displaying camera errors -->
            <p id="scanFeedback" class="text-success"></p> <!-- For displaying success feedback -->
        </div>
    </div>
</div>

{{-- Include JS libraries --}}
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/html5-qrcode/html5-qrcode.min.js"></script>

<script>
    let html5QrCode;

    function onScanSuccess(decodedText, decodedResult) {
        console.log(`Scan result: ${decodedText}`);
        document.getElementById('result').innerText = decodedText;
        document.getElementById('scanFeedback').innerText = ''; // Clear feedback message

        // Stop the camera after a successful scan
        html5QrCode.stop().then(() => {
            console.log("Camera stopped successfully.");
            document.getElementById('reader').style.display = "none";
            document.getElementById('openCamera').disabled = false; // Re-enable the button
        }).catch(err => {
            console.error('Error stopping the camera', err);
        });

        // Send scan result to Laravel backend
        fetch('/presences/scan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ qr_code: decodedText, attendance_id: {{ $attendance->id }} })
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  document.getElementById('scanFeedback').innerText = `Scan successful! Welcome, ${data.user.nama}`;
              } else {
                  document.getElementById('scanFeedback').innerText = 'Error: Unable to validate QR code.';
                  document.getElementById('scanFeedback').classList.replace('text-success', 'text-danger');
              }
              console.log('QR Code scanned successfully:', data);
          }).catch(error => {
              console.error('Error scanning QR Code:', error);
              document.getElementById('scanFeedback').innerText = 'Error processing the QR code.';
              document.getElementById('scanFeedback').classList.replace('text-success', 'text-danger');
          });
    }

    function onScanFailure(error) {
        // Handle scan failure
        console.warn(`QR scan failed: ${error}`);
    }

    document.getElementById('openCamera').addEventListener('click', function() {
        // Disable the button to prevent multiple clicks
        document.getElementById('openCamera').disabled = true;
        
        // Show the camera feed
        document.getElementById('reader').style.display = "block";
        document.getElementById('cameraError').innerText = '';  // Clear any previous error message

        // Start scanning
        html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start(
            { facingMode: "environment" }, // Use rear camera
            {
                fps: 10,    // Frame-per-second rate
                qrbox: 250  // QR scan box size
            },
            onScanSuccess,
            onScanFailure
        ).catch(err => {
            console.error(`Unable to start scanning, error: ${err}`);
            document.getElementById('cameraError').innerText = 'Camera failed to start. Please check if your browser allows camera access or try again.';
            document.getElementById('openCamera').disabled = false; // Re-enable button if there's an error
        });
    });
</script>
@endsection
