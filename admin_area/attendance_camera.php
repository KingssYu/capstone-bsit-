<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance with Face Recognition</title>
    <style>
        .camera-button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            color: white;
            background-color: #004d99;
            text-align: center;
            text-decoration: none;
        }

        .camera-button:hover {
            background-color: #003366;
        }

        .camera-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .camera-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .camera-content video {
            width: 100%;
            max-width: 500px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .close-button {
            margin-top: 10px;
            padding: 10px;
            background: #004d99;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .close-button:hover {
            background: #003366;
        }
    </style>
</head>
<body>
    <a href="attendance.php" class="camera-button" id="openCamera">
        <i class="camera-icon">📸</i> Attendance
    </a>

    <div class="camera-modal" id="cameraModal">
        <div class="camera-content">
            <video id="video" autoplay></video>
            <button class="close-button" id="closeCamera">Close</button>
        </div>
    </div>

    <script>
        const openCameraButton = document.getElementById('openCamera');
        const cameraModal = document.getElementById('cameraModal');
        const closeCameraButton = document.getElementById('closeCamera');
        const video = document.getElementById('video');

        // Open camera modal
        openCameraButton.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default link behavior
            cameraModal.style.display = 'flex';
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(stream => {
                    video.srcObject = stream;
                })
                .catch(err => {
                    console.error('Error accessing the camera: ', err);
                });
        });

        // Close camera modal
        closeCameraButton.addEventListener('click', function() {
            cameraModal.style.display = 'none';
            // Stop all video streams
            const stream = video.srcObject;
            if (stream) {
                const tracks = stream.getTracks();
                tracks.forEach(track => track.stop());
            }
        });
    </script>
</body>
</html>
