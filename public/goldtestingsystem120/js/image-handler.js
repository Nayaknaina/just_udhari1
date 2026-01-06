class ImageHandler {
  constructor(inputElement, previewElement) {
    this.input = inputElement
    this.preview = previewElement
    this.maxSize = 5 * 1024 * 1024 // 5MB
    this.allowedTypes = ["image/jpeg", "image/jpg", "image/png", "image/gif", "image/webp"]

    this.init()
  }

  init() {
    // File input change handler
    this.input.addEventListener("change", (e) => this.handleFileSelect(e))

    // Drag and drop handlers
    this.setupDragAndDrop()

    // Preview click handler for full-size view
    this.preview.addEventListener("click", (e) => this.handlePreviewClick(e))
  }

  setupDragAndDrop() {
    const dropZone = this.preview
    ;["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
      dropZone.addEventListener(eventName, this.preventDefaults, false)
    })
    ;["dragenter", "dragover"].forEach((eventName) => {
      dropZone.addEventListener(eventName, () => this.highlight(dropZone), false)
    })
    ;["dragleave", "drop"].forEach((eventName) => {
      dropZone.addEventListener(eventName, () => this.unhighlight(dropZone), false)
    })

    dropZone.addEventListener("drop", (e) => this.handleDrop(e), false)
  }

  preventDefaults(e) {
    e.preventDefault()
    e.stopPropagation()
  }

  highlight(element) {
    element.classList.add("drag-over")
  }

  unhighlight(element) {
    element.classList.remove("drag-over")
  }

  handleDrop(e) {
    const files = e.dataTransfer.files
    if (files.length > 0) {
      this.processFile(files[0])
    }
  }

  handleFileSelect(e) {
    const files = e.target.files
    if (files.length > 0) {
      this.processFile(files[0])
    }
  }

  processFile(file) {
    console.log("[v0] Processing file:", file.name)

    // Validate file
    if (!this.validateFile(file)) {
      return
    }

    // Show preview
    this.showPreview(file)

    // Upload file
    this.uploadFile(file)
  }

  validateFile(file) {
    // Check file type
    if (!this.allowedTypes.includes(file.type)) {
      this.showError("Invalid file type. Please select a JPEG, PNG, GIF, or WebP image.")
      return false
    }

    // Check file size
    if (file.size > this.maxSize) {
      this.showError("File size too large. Maximum size is 5MB.")
      return false
    }

    return true
  }

  showPreview(file) {
    const reader = new FileReader()
    reader.onload = (e) => {
      this.preview.innerHTML = `
                <div class="image-preview-container">
                    <img src="${e.target.result}" alt="Preview" class="preview-image">
                    <div class="image-info">
                        <p class="file-name">${file.name}</p>
                        <p class="file-size">${this.formatFileSize(file.size)}</p>
                    </div>
                    <button type="button" class="remove-image" onclick="removeImage()">×</button>
                </div>
            `
      this.preview.classList.add("has-image")
    }
    reader.readAsDataURL(file)
  }

  async uploadFile(file) {
    const formData = new FormData()
    formData.append("image", file)

    try {
      this.showUploadProgress()

      const response = await fetch("api/upload_image.php", {
        method: "POST",
        body: formData,
      })

      const result = await response.json()
      console.log("[v0] Upload response:", result)

      if (result.success) {
        this.showUploadSuccess(result)
        // Store the uploaded file path for form submission
        this.input.dataset.uploadedPath = result.path
      } else {
        throw new Error(result.error || "Upload failed")
      }
    } catch (error) {
      console.error("[v0] Upload error:", error)
      this.showError("Upload failed: " + error.message)
    } finally {
      this.hideUploadProgress()
    }
  }

  showUploadProgress() {
    const progressElement = document.createElement("div")
    progressElement.className = "upload-progress"
    progressElement.innerHTML = `
            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>
            <p>Uploading image...</p>
        `
    this.preview.appendChild(progressElement)
  }

  hideUploadProgress() {
    const progressElement = this.preview.querySelector(".upload-progress")
    if (progressElement) {
      progressElement.remove()
    }
  }

  showUploadSuccess(result) {
    const successElement = document.createElement("div")
    successElement.className = "upload-success"
    successElement.innerHTML = `
            <div class="success-icon">✓</div>
            <p>Image uploaded successfully!</p>
        `
    this.preview.appendChild(successElement)

    // Remove success message after 3 seconds
    setTimeout(() => {
      if (successElement.parentNode) {
        successElement.remove()
      }
    }, 3000)
  }

  showError(message) {
    const errorElement = document.createElement("div")
    errorElement.className = "upload-error"
    errorElement.innerHTML = `
            <div class="error-icon">⚠</div>
            <p>${message}</p>
        `
    this.preview.appendChild(errorElement)

    // Remove error message after 5 seconds
    setTimeout(() => {
      if (errorElement.parentNode) {
        errorElement.remove()
      }
    }, 5000)
  }

  handlePreviewClick(e) {
    const img = e.target.closest(".preview-image")
    if (img) {
      this.openImageModal(img.src)
    }
  }

  openImageModal(src) {
    const modal = document.createElement("div")
    modal.className = "image-modal"
    modal.innerHTML = `
            <div class="modal-backdrop" onclick="this.parentElement.remove()">
                <div class="modal-content" onclick="event.stopPropagation()">
                    <img src="${src}" alt="Full size preview">
                    <button class="modal-close" onclick="this.closest('.image-modal').remove()">×</button>
                </div>
            </div>
        `
    document.body.appendChild(modal)
  }

  formatFileSize(bytes) {
    if (bytes === 0) return "0 Bytes"
    const k = 1024
    const sizes = ["Bytes", "KB", "MB", "GB"]
    const i = Math.floor(Math.log(bytes) / Math.log(k))
    return Number.parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i]
  }
}

// Global function to remove image
function removeImage() {
  const preview = document.getElementById("imagePreview")
  const input = document.getElementById("image")

  preview.innerHTML = '<p class="no-image-text">Click to select an image or drag and drop here</p>'
  preview.classList.remove("has-image")
  input.value = ""
  input.dataset.uploadedPath = ""

  console.log("[v0] Image removed")
}

// Initialize image handler when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  const imageInput = document.getElementById("image")
  const imagePreview = document.getElementById("imagePreview")

  if (imageInput && imagePreview) {
    new ImageHandler(imageInput, imagePreview)
    console.log("[v0] Image handler initialized")
  }
})
