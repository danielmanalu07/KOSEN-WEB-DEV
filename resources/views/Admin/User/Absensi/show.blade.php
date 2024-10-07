@extends('components.layout')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
    crossorigin="anonymous">

<div class="container py-5">
    <div class="row">
        <div class="col-md-6 mb-3 mb-md-0">
            <h1 class="fs-2">{{ $attendance->title }}</h1>
            <p class="text-muted">{{ $attendance->description }}</p>

            <div class="mb-4">
                <span class="badge text-bg-light border shadow-sm">
                    Masuk: {{ substr($attendance->start_time, 0, -3) }} - {{ substr($attendance->batas_start_time ?? '',
                    0, -3) }}
                </span>
                <span class="badge text-bg-light border shadow-sm">
                    Pulang: {{ substr($attendance->end_time, 0 , -3) }} - {{ substr($attendance->batas_end_time ?? '',
                    0, -3) }}
                </span>
            </div>
        </div>

        <div class="col-md-6">
            <h4>Scan QR Code</h4>

            {{-- Button to open camera --}}
            <button id="openCamera" class="btn btn-primary mb-3">Open Camera</button>
            {{-- Button to close camera --}}
            <button id="closeCamera" class="btn btn-danger mb-3" style="display: none;">Close Camera</button>

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

    // Send the QR code and attendance_id to the server
    fetch('/admin/attendances/scan', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            qr_code: decodedText,
            attendance_id: {{ $attendance->id }}
        })
    })
    .then(response => {
        if (!response.ok) {
            // Check if response is not OK and throw an error with a specific message
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            document.getElementById('scanFeedback').innerText = `Welcome, ${data.user.nama}`;
            document.getElementById('scanFeedback').classList.replace('text-danger', 'text-success');
            // Display user's photo or additional data if needed
        } else {
            document.getElementById('scanFeedback').innerText = data.message;
            document.getElementById('scanFeedback').classList.replace('text-success', 'text-danger');
        }
    })
    .catch(error => {
        // Handle the specific fetch errors here
        console.error('Fetch error:', error);
        document.getElementById('scanFeedback').innerText = `Error processing the QR code: ${error.message}`;
        document.getElementById('scanFeedback').classList.replace('text-success', 'text-danger');
    });
}

function onScanFailure(error) {
    // If scanning fails, give more specific error messages
    console.warn(`QR scan failed: ${error}`);
    document.getElementById('cameraError').innerText = `QR scan failed: ${error}`;
}


    document.getElementById('openCamera').addEventListener('click', function() {
        document.getElementById('openCamera').disabled = true;
        document.getElementById('closeCamera').style.display = "block";
        document.getElementById('reader').style.display = "block";
        document.getElementById('cameraError').innerText = '';  

        html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start(
            { facingMode: "environment" }, 
            {
                fps: 10,
                qrbox: 250
            },
            onScanSuccess,
            onScanFailure
        ).catch(err => {
            console.error(`Unable to start scanning, error: ${err}`);
            document.getElementById('cameraError').innerText = 'Camera failed to start. Please check if your browser allows camera access or try again.';
            document.getElementById('openCamera').disabled = false;
            document.getElementById('closeCamera').style.display = "none";
        });
    });

    document.getElementById('closeCamera').addEventListener('click', function() {
        html5QrCode.stop().then(() => {
            console.log("Camera stopped manually.");
            document.getElementById('reader').style.display = "none";
            document.getElementById('openCamera').disabled = false;
            document.getElementById('closeCamera').style.display = "none";
        }).catch(err => {
            console.error('Error stopping the camera manually', err);
        });
    });
</script>
@endsection