class QRScanner {
  constructor() {
    this.video = null
    this.canvas = null
    this.context = null
    this.scanning = false
    this.stream = null
  }

  async startScanning(videoElement, onScanSuccess, onScanError) {
    try {
      this.video = videoElement
      this.canvas = document.createElement("canvas")
      this.context = this.canvas.getContext("2d")

      const constraints = {
        video: {
          facingMode: "environment", // Use back camera
          width: { ideal: 1920, min: 640 },
          height: { ideal: 1080, min: 480 },
          focusMode: "continuous",
          advanced: [{ focusMode: "continuous" }, { exposureMode: "continuous" }, { whiteBalanceMode: "continuous" }],
        },
      }

      // Request camera access with fallback options
      try {
        this.stream = await navigator.mediaDevices.getUserMedia(constraints)
      } catch (error) {
        console.log("[v0] High-quality camera failed, trying basic constraints")
        // Fallback to basic constraints
        this.stream = await navigator.mediaDevices.getUserMedia({
          video: { facingMode: "environment" },
        })
      }

      this.video.srcObject = this.stream

      return new Promise((resolve, reject) => {
        this.video.onloadedmetadata = () => {
          this.video.play()
          this.scanning = true
          console.log("[v0] Camera started successfully")

          // Start scanning loop after video is ready
          setTimeout(() => {
            this.scanLoop(onScanSuccess, onScanError)
            resolve()
          }, 500)
        }

        this.video.onerror = (error) => {
          console.error("[v0] Video error:", error)
          reject("Video loading failed")
        }

        // Timeout fallback
        setTimeout(() => {
          if (!this.scanning) {
            reject("Camera initialization timeout")
          }
        }, 10000)
      })
    } catch (error) {
      console.error("[v0] Camera access error:", error)
      let errorMessage = "Camera access denied or not available"

      if (error.name === "NotAllowedError") {
        errorMessage = "Camera permission denied. Please allow camera access and try again."
      } else if (error.name === "NotFoundError") {
        errorMessage = "No camera found on this device."
      } else if (error.name === "NotReadableError") {
        errorMessage = "Camera is already in use by another application."
      }

      onScanError(errorMessage)
    }
  }

  scanLoop(onScanSuccess, onScanError) {
    if (!this.scanning || !this.video) return

    if (this.video.readyState === this.video.HAVE_ENOUGH_DATA) {
      const videoWidth = this.video.videoWidth
      const videoHeight = this.video.videoHeight

      if (videoWidth > 0 && videoHeight > 0) {
        this.canvas.width = videoWidth
        this.canvas.height = videoHeight
        this.context.drawImage(this.video, 0, 0, videoWidth, videoHeight)

        const imageData = this.context.getImageData(0, 0, videoWidth, videoHeight)

        const jsQR = window.jsQR // Declare jsQR variable
        if (typeof jsQR !== "undefined") {
          try {
            const code = jsQR(imageData.data, imageData.width, imageData.height, {
              inversionAttempts: "dontInvert", // Faster scanning
            })

            if (code && code.data) {
              console.log("[v0] QR Code detected:", code.data)

              if (this.validateQRData(code.data)) {
                this.stopScanning()
                onScanSuccess(code.data)
                return
              } else {
                console.log("[v0] Invalid QR code format, continuing scan...")
              }
            }
          } catch (error) {
            console.error("[v0] QR scanning error:", error)
            // Continue scanning even if there's an error
          }
        } else {
          console.error("[v0] jsQR library not loaded")
          onScanError("QR scanner library not available")
          return
        }
      }
    }

    // Continue scanning with optimized frame rate
    requestAnimationFrame(() => this.scanLoop(onScanSuccess, onScanError))
  }

  validateQRData(data) {
    if (!data || typeof data !== "string") return false

    // Check if it's a valid URL for our system
    if (data.includes("mobile_view.php") && data.includes("serial=")) {
      return true
    }

    // Check if it's a general URL
    try {
      new URL(data)
      return true
    } catch {
      return false
    }
  }

  stopScanning() {
    console.log("[v0] Stopping QR scanner")
    this.scanning = false

    if (this.stream) {
      const tracks = this.stream.getTracks()
      tracks.forEach((track) => {
        track.stop()
        console.log("[v0] Stopped camera track:", track.kind)
      })
      this.stream = null
    }

    if (this.video) {
      this.video.srcObject = null
    }
  }
}

function createQRScannerModal() {
  const modal = document.createElement("div")
  modal.id = "qrScannerModal"
  modal.className = "qr-scanner-modal"
  modal.innerHTML = `
        <div class="qr-scanner-content">
            <div class="qr-scanner-header">
                <h3>Scan QR Code</h3>
                <button class="close-scanner" onclick="closeQRScanner()">&times;</button>
            </div>
            <div class="qr-scanner-body">
                <video id="qrVideo" autoplay muted playsinline></video>
                <div class="qr-scanner-overlay">
                    <div class="qr-scanner-frame"></div>
                    <div class="qr-scanner-corners">
                        <div class="corner top-left"></div>
                        <div class="corner top-right"></div>
                        <div class="corner bottom-left"></div>
                        <div class="corner bottom-right"></div>
                    </div>
                </div>
                <div class="qr-scanner-status">
                    <p class="qr-scanner-instruction">Position QR code within the frame</p>
                    <div class="qr-scanner-loading" style="display: none;">
                        <div class="loading-spinner"></div>
                        <p>Initializing camera...</p>
                    </div>
                </div>
            </div>
            <div class="qr-scanner-actions">
                <button onclick="toggleFlashlight()" class="btn-flash" id="flashBtn" style="display: none;">💡 Flash</button>
                <button onclick="switchCamera()" class="btn-switch" id="switchBtn" style="display: none;">🔄 Switch</button>
                <button onclick="closeQRScanner()" class="btn-cancel">Cancel</button>
            </div>
        </div>
    `
  document.body.appendChild(modal)
  return modal
}

function openQRScanner() {
  const modal = document.getElementById("qrScannerModal") || createQRScannerModal()
  modal.style.display = "block"

  // Show loading state
  const loading = modal.querySelector(".qr-scanner-loading")
  const instruction = modal.querySelector(".qr-scanner-instruction")
  loading.style.display = "block"
  instruction.style.display = "none"

  const video = document.getElementById("qrVideo")
  const scanner = new QRScanner()

  scanner
    .startScanning(
      video,
      (data) => {
        console.log("[v0] QR Code scanned:", data)

        try {
          let serial = null

          if (data.includes("serial=")) {
            const urlParams = new URLSearchParams(data.split("?")[1])
            serial = urlParams.get("serial")
          }

          if (serial && serial.match(/^[A-Z0-9]+$/)) {
            // Show success feedback
            showScanSuccess()
            setTimeout(() => {
              window.location.href = `mobile_view.php?serial=${serial}&verify=1`
            }, 1000)
          } else {
            throw new Error("Invalid QR code format or missing serial number")
          }
        } catch (error) {
          console.error("[v0] QR parsing error:", error)
          showScanError("Invalid QR code format. Please scan a valid gold testing report QR code.")
        }
      },
      (error) => {
        console.error("[v0] QR Scanner error:", error)
        loading.style.display = "none"
        showScanError(error)
      },
    )
    .then(() => {
      // Hide loading, show instruction
      loading.style.display = "none"
      instruction.style.display = "block"

      // Show additional controls if available
      checkCameraFeatures()
    })
    .catch((error) => {
      loading.style.display = "none"
      showScanError(error)
    })

  // Store scanner instance for cleanup
  window.currentQRScanner = scanner
}

function showScanSuccess() {
  const modal = document.getElementById("qrScannerModal")
  const overlay = modal.querySelector(".qr-scanner-overlay")
  overlay.innerHTML += '<div class="scan-success">✓ QR Code Detected!</div>'
}

function showScanError(message) {
  const modal = document.getElementById("qrScannerModal")
  const instruction = modal.querySelector(".qr-scanner-instruction")
  instruction.innerHTML = `<span style="color: #ff4444;">Error: ${message}</span>`

  // Reset after 3 seconds
  setTimeout(() => {
    instruction.innerHTML = "Position QR code within the frame"
  }, 3000)
}

function checkCameraFeatures() {
  // Check if device supports flashlight
  if (navigator.mediaDevices && navigator.mediaDevices.getSupportedConstraints) {
    const constraints = navigator.mediaDevices.getSupportedConstraints()
    if (constraints.torch) {
      document.getElementById("flashBtn").style.display = "inline-block"
    }
  }
}

function toggleFlashlight() {
  if (window.currentQRScanner && window.currentQRScanner.stream) {
    const track = window.currentQRScanner.stream.getVideoTracks()[0]
    if (track && track.getCapabilities && track.getCapabilities().torch) {
      const settings = track.getSettings()
      track
        .applyConstraints({
          advanced: [{ torch: !settings.torch }],
        })
        .catch(console.error)
    }
  }
}

function closeQRScanner() {
  const modal = document.getElementById("qrScannerModal")
  if (modal) {
    modal.style.display = "none"
  }

  if (window.currentQRScanner) {
    window.currentQRScanner.stopScanning()
    window.currentQRScanner = null
  }
}

document.addEventListener("keydown", (event) => {
  if (event.key === "Escape") {
    closeQRScanner()
  }
})

const qrScannerStyles = `
<style>
.qr-scanner-modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.9);
  z-index: 10000;
  display: none;
}

.qr-scanner-content {
  position: relative;
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
}

.qr-scanner-header {
  padding: 20px;
  background: rgba(0, 0, 0, 0.8);
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.qr-scanner-body {
  flex: 1;
  position: relative;
  overflow: hidden;
}

#qrVideo {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.qr-scanner-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.qr-scanner-frame {
  width: 250px;
  height: 250px;
  border: 2px solid rgba(255, 255, 255, 0.5);
  position: relative;
}

.qr-scanner-corners {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
}

.corner {
  position: absolute;
  width: 30px;
  height: 30px;
  border: 3px solid #00ff00;
}

.corner.top-left {
  top: -15px;
  left: -15px;
  border-right: none;
  border-bottom: none;
}

.corner.top-right {
  top: -15px;
  right: -15px;
  border-left: none;
  border-bottom: none;
}

.corner.bottom-left {
  bottom: -15px;
  left: -15px;
  border-right: none;
  border-top: none;
}

.corner.bottom-right {
  bottom: -15px;
  right: -15px;
  border-left: none;
  border-top: none;
}

.qr-scanner-status {
  position: absolute;
  bottom: 120px;
  left: 0;
  right: 0;
  text-align: center;
  color: white;
}

.qr-scanner-loading {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
}

.loading-spinner {
  width: 30px;
  height: 30px;
  border: 3px solid rgba(255, 255, 255, 0.3);
  border-top: 3px solid white;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.scan-success {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: rgba(0, 255, 0, 0.9);
  color: white;
  padding: 15px 25px;
  border-radius: 10px;
  font-size: 18px;
  font-weight: bold;
}

.qr-scanner-actions {
  padding: 20px;
  background: rgba(0, 0, 0, 0.8);
  display: flex;
  gap: 10px;
  justify-content: center;
}

.qr-scanner-actions button {
  padding: 12px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
}

.btn-cancel {
  background: #ff4444;
  color: white;
}

.btn-flash, .btn-switch {
  background: #4444ff;
  color: white;
}
</style>
`

// Inject styles if not already present
if (!document.getElementById("qr-scanner-styles")) {
  const styleElement = document.createElement("div")
  styleElement.id = "qr-scanner-styles"
  styleElement.innerHTML = qrScannerStyles
  document.head.appendChild(styleElement)
}
