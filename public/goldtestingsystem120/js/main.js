document.addEventListener("DOMContentLoaded", () => {
  // Auto-fill current date and time
  const dateTimeInput = document.getElementById("date_time")
  if (dateTimeInput) {
    const now = new Date()
    const localDateTime = new Date(now.getTime() - now.getTimezoneOffset() * 60000).toISOString().slice(0, 16)
    dateTimeInput.value = localDateTime
  }

  // Image preview functionality
  const imageInput = document.getElementById("image")
  const imagePreview = document.getElementById("imagePreview")

  if (imageInput && imagePreview) {
    imageInput.addEventListener("change", (e) => {
      const file = e.target.files[0]
      if (file) {
        const reader = new FileReader()
        reader.onload = (e) => {
          imagePreview.innerHTML = `<img src="${e.target.result}" alt="Sample preview" class="img-fluid rounded">`
        }
        reader.readAsDataURL(file)
      } else {
        imagePreview.innerHTML = '<small class="text-muted">No image selected</small>'
      }
    })
  }

  // Form submission with Bootstrap integration
  const reportForm = document.getElementById("reportForm")
  const submitBtn = document.getElementById("submitBtn")
  const loadingSpinner = document.getElementById("loadingSpinner")
  const successMessage = document.getElementById("successMessage")

  if (reportForm && submitBtn) {
    const btnText = submitBtn.querySelector(".btn-text")

    reportForm.addEventListener("submit", async (e) => {
      e.preventDefault()

      // Show loading state with Bootstrap classes
      submitBtn.disabled = true
      submitBtn.classList.add("disabled")
      if (loadingSpinner) {
        loadingSpinner.classList.remove("d-none")
        loadingSpinner.classList.add("d-inline-block")
      }
      if (btnText) btnText.textContent = "Generating..."

      try {
        const formData = new FormData(reportForm)

        console.log("[v0] Submitting form data to API")
        const response = await fetch("api/save_report.php", {
          method: "POST",
          body: formData,
        })

        const result = await response.json()
        console.log("[v0] API response:", result)

        if (result.success) {
          const successMsg = `Report generated successfully! Serial Number: ${result.serial_number}`
          const qrMsg = result.qr_generated
            ? " QR code created successfully!"
            : " (QR code generation failed - check debug tool)"
          showSuccessPopup(successMsg + qrMsg)

          const generatedSerial = document.getElementById("generatedSerial")
          if (generatedSerial) generatedSerial.textContent = result.serial_number

          // Show success message with Bootstrap classes
          if (successMessage) {
            successMessage.classList.remove("d-none")
            successMessage.classList.add("d-block")
          }

          localStorage.setItem("lastGeneratedSerial", result.serial_number)
          localStorage.setItem("lastQRGenerated", result.qr_generated ? "true" : "false")
          localStorage.setItem("lastQRPath", result.qr_path || "")

          setupSuccessActions(result)

          try {
            window.open(`print_report.php?serial=${result.serial_number}`, "_blank")
          } catch (err) {
            console.log("[v0] Auto-open print failed:", err)
          }
        } else {
          throw new Error(result.error || "Failed to generate report")
        }
      } catch (error) {
        console.error("[v0] Error generating report:", error)
        showErrorPopup("Error generating report: " + error.message)
      } finally {
        // Reset button state
        submitBtn.disabled = false
        submitBtn.classList.remove("disabled")
        if (loadingSpinner) {
          loadingSpinner.classList.add("d-none")
          loadingSpinner.classList.remove("d-inline-block")
        }
        if (btnText) btnText.textContent = "Generate"
      }
    })
  }

  // Form validation with Bootstrap styling
  const requiredFields = ["customer_name", "sample_description"]
  requiredFields.forEach((fieldId) => {
    const field = document.getElementById(fieldId)
    if (field) {
      field.addEventListener("blur", validateField)
      field.addEventListener("input", validateField)
    }
  })

  function validateField(e) {
    const field = e.target
    if (field.hasAttribute("required") && !field.value.trim()) {
      field.classList.add("is-invalid")
      field.classList.remove("is-valid")
    } else if (field.value.trim()) {
      field.classList.add("is-valid")
      field.classList.remove("is-invalid")
    } else {
      field.classList.remove("is-invalid", "is-valid")
    }
  }

  // Element concentration validation with Bootstrap styling
  const elementInputs = document.querySelectorAll(
    'input[type="number"][id$="_au"], input[type="number"][id$="_ag"], input[type="number"][id$="_cu"], input[type="number"][id$="_zn"], input[type="number"][id$="_cd"], input[type="number"][id$="_ir"], input[type="number"][id$="_sn"], input[type="number"][id$="_ni"], input[type="number"][id$="_pb"], input[type="number"][id$="_ru"], input[type="number"][id$="_pt"], input[type="number"][id$="_co"], input[type="number"][id$="_pd"], input[type="number"][id$="_os"], input[type="number"][id$="_fe"]',
  )
  elementInputs.forEach((input) => {
    input.addEventListener("input", validateTotalConcentration)
  })

  // Gold formula calculation functionality
  const caratInput = document.getElementById("carat")
  const goldFormulaSection = document.getElementById("goldFormulaSection")
  const goldPercentageDisplay = document.getElementById("goldPercentage")
  const alloyPercentageDisplay = document.getElementById("alloyPercentage")
  const alloyTypesDisplay = document.getElementById("alloyTypes")

  if (caratInput) {
    caratInput.addEventListener("input", calculateGoldFormula)
  }

  function calculateGoldFormula() {
    const caratValue = Number.parseFloat(caratInput.value)

    if (!caratValue || isNaN(caratValue) || caratValue <= 0 || caratValue > 24) {
      if (goldFormulaSection) {
        goldFormulaSection.style.display = "none"
        goldFormulaSection.classList.remove("fade-in")
      }

      // Clear all related fields when carat is invalid
      const goldAuInput = document.getElementById("gold_au")
      const silverInput = document.getElementById("silver_ag")
      const copperInput = document.getElementById("copper_cu")

      if (goldAuInput) {
        goldAuInput.value = ""
        goldAuInput.classList.remove("is-valid", "is-invalid")
      }
      if (silverInput) {
        silverInput.value = ""
        silverInput.classList.remove("is-valid", "is-invalid")
      }
      if (copperInput) {
        copperInput.value = ""
        copperInput.classList.remove("is-valid", "is-invalid")
      }
      return
    }

    const goldPercentage = Number.parseFloat(((caratValue / 24) * 100).toFixed(2))
    const alloyPercentage = Number.parseFloat((100 - goldPercentage).toFixed(2))

    if (goldFormulaSection) {
      goldFormulaSection.style.display = "block"
      goldFormulaSection.classList.add("fade-in")
    }

    if (goldPercentageDisplay) {
      goldPercentageDisplay.textContent = goldPercentage.toFixed(2) + "%"
    }
    if (alloyPercentageDisplay) {
      alloyPercentageDisplay.textContent = alloyPercentage.toFixed(2) + "%"
    }

    const goldAuInput = document.getElementById("gold_au")
    if (goldAuInput) {
      goldAuInput.value = goldPercentage.toFixed(2)
      goldAuInput.classList.add("is-valid")
      goldAuInput.classList.remove("is-invalid")
    }

    autoFillAlloyMetals(caratValue, alloyPercentage)
    updateAlloyTypesDisplay(caratValue)

    console.log(
      `[v0] Gold formula calculated: ${caratValue}K = ${goldPercentage.toFixed(2)}% gold + ${alloyPercentage.toFixed(2)}% alloy (Silver: ${(alloyPercentage / 2).toFixed(2)}%, Copper: ${(alloyPercentage / 2).toFixed(2)}%)`,
    )
  }

  function autoFillAlloyMetals(carat, alloyPercentage) {
    const silverInput = document.getElementById("silver_ag")
    const copperInput = document.getElementById("copper_cu")

    if (alloyPercentage > 0) {
      const silverPercentage = Number.parseFloat((alloyPercentage / 2).toFixed(2))
      const copperPercentage = Number.parseFloat((alloyPercentage / 2).toFixed(2))

      if (silverInput) {
        silverInput.value = silverPercentage.toFixed(2)
        silverInput.classList.add("is-valid")
        silverInput.classList.remove("is-invalid")
      }

      if (copperInput) {
        copperInput.value = copperPercentage.toFixed(2)
        copperInput.classList.add("is-valid")
        copperInput.classList.remove("is-invalid")
      }

      console.log(
        `[v0] Auto-filled alloy metals: Silver ${silverPercentage.toFixed(2)}%, Copper ${copperPercentage.toFixed(2)}%`,
      )
    } else {
      if (silverInput) {
        silverInput.value = "0.00"
        silverInput.classList.add("is-valid")
        silverInput.classList.remove("is-invalid")
      }
      if (copperInput) {
        copperInput.value = "0.00"
        copperInput.classList.add("is-valid")
        copperInput.classList.remove("is-invalid")
      }
    }
  }

  function updateAlloyTypesDisplay(carat) {
    if (alloyTypesDisplay) {
      alloyTypesDisplay.textContent = "Silver, Copper"
    }
  }

  function validateTotalConcentration() {
    let total = 0
    const validInputs = []

    elementInputs.forEach((input) => {
      const value = Number.parseFloat(input.value) || 0
      if (value > 0) {
        total += value
        validInputs.push(input)
      }
    })

    const caratValue = Number.parseFloat(caratInput?.value || 0)
    const expectedGold = caratValue > 0 ? (caratValue / 24) * 100 : 0
    const goldAuValue = Number.parseFloat(document.getElementById("gold_au")?.value || 0)

    validInputs.forEach((input) => {
      if (total > 100.1) {
        input.classList.add("is-invalid")
        input.classList.remove("is-valid")
      } else {
        input.classList.add("is-valid")
        input.classList.remove("is-invalid")
      }
    })

    if (total > 100.1) {
      console.log("[v0] Warning: Total concentration exceeds 100%")
      showErrorPopup(`Warning: Total concentration is ${total.toFixed(2)}% (exceeds 100%)`)
    }

    if (expectedGold > 0 && goldAuValue > 0 && Math.abs(expectedGold - goldAuValue) > 1) {
      console.log(
        `[v0] Warning: Gold percentage (${goldAuValue}%) doesn't match carat calculation (${expectedGold.toFixed(2)}%)`,
      )
    }
  }

  function setupSuccessActions(result) {
    const viewReportBtn = document.getElementById("viewReportBtn")

    if (viewReportBtn) {
      viewReportBtn.replaceWith(viewReportBtn.cloneNode(true))
      const newViewBtn = document.getElementById("viewReportBtn")
      newViewBtn.addEventListener("click", () => {
        window.open(`view_report.php?serial=${result.serial_number}`, "_blank")
      })
    }
  }

  // Reset form functionality with Bootstrap classes
  if (reportForm) {
    reportForm.addEventListener("reset", () => {
      if (dateTimeInput) {
        const now = new Date()
        const localDateTime = new Date(now.getTime() - now.getTimezoneOffset() * 60000).toISOString().slice(0, 16)
        dateTimeInput.value = localDateTime
      }

      if (imagePreview) {
        imagePreview.innerHTML = '<small class="text-muted">No image selected</small>'
      }

      if (successMessage) {
        successMessage.classList.add("d-none")
        successMessage.classList.remove("d-block")
      }

      if (goldFormulaSection) {
        goldFormulaSection.style.display = "none"
      }

      const allInputs = reportForm.querySelectorAll("input, textarea")
      allInputs.forEach((input) => {
        input.classList.remove("is-valid", "is-invalid")
      })

      clearAutoSaveData()
    })
  }

  function showSuccessPopup(message) {
    const popup = createPopup(message, "success")
    document.body.appendChild(popup)

    setTimeout(() => {
      popup.classList.add("show")
    }, 100)

    setTimeout(() => {
      popup.classList.remove("show")
      setTimeout(() => {
        if (document.body.contains(popup)) {
          document.body.removeChild(popup)
        }
      }, 300)
    }, 4000)
  }

  function showErrorPopup(message) {
    const popup = createPopup(message, "error")
    document.body.appendChild(popup)

    setTimeout(() => {
      popup.classList.add("show")
    }, 100)

    setTimeout(() => {
      popup.classList.remove("show")
      setTimeout(() => {
        if (document.body.contains(popup)) {
          document.body.removeChild(popup)
        }
      }, 300)
    }, 5000)
  }

  function createPopup(message, type) {
    const popup = document.createElement("div")
    popup.className = `popup-message ${type}`
    popup.innerHTML = `
      <div class="popup-content">
        <div class="popup-icon">
          ${type === "success" ? "✓" : "✕"}
        </div>
        <div class="popup-text">${message}</div>
        <button class="popup-close" onclick="this.parentElement.parentElement.remove()">×</button>
      </div>
    `
    return popup
  }

  // Utility functions for QR code functionality
  function testQRCode(serial) {
    if (!serial) {
      serial = localStorage.getItem("lastGeneratedSerial")
    }

    if (serial) {
      const testUrl = `mobile_view.php?serial=${serial}&verify=1`
      window.open(testUrl, "_blank")
    } else {
      showErrorPopup("No serial number available for testing")
    }
  }

  window.testQRCode = testQRCode

  window.regenerateQR = (serial) => {
    if (!serial) {
      serial = localStorage.getItem("lastGeneratedSerial")
    }

    if (serial) {
      window.open(`debug_qr.php`, "_blank")
    } else {
      showErrorPopup("No serial number available")
    }
  }

  window.downloadQR = (serial) => {
    if (!serial) {
      serial = localStorage.getItem("lastGeneratedSerial")
    }

    const qrPath = localStorage.getItem("lastQRPath")
    if (qrPath) {
      const link = document.createElement("a")
      link.download = `QR_${serial}.png`
      link.href = qrPath
      link.click()
    } else {
      showErrorPopup("QR code not available for download")
    }
  }

  document.addEventListener("keydown", (e) => {
    if (e.ctrlKey && e.key === "Enter" && reportForm && !reportForm.classList.contains("d-none")) {
      e.preventDefault()
      reportForm.dispatchEvent(new Event("submit"))
    }

    if (e.ctrlKey && e.key === "r" && reportForm) {
      e.preventDefault()
      reportForm.reset()
    }

    if (e.ctrlKey && e.key === "t") {
      e.preventDefault()
      testQRCode()
    }

    if (e.ctrlKey && e.key === "s") {
      e.preventDefault()
      saveFormData()
    }

    if (e.ctrlKey && e.key === "l") {
      e.preventDefault()
      loadFormData()
    }
  })

  function saveFormData() {
    const autoSaveFields = ["customer_name", "sample_description", "weight", "carat"]
    autoSaveFields.forEach((fieldId) => {
      const field = document.getElementById(fieldId)
      if (field && field.value) {
        localStorage.setItem(`manual_save_${fieldId}`, field.value)
      }
    })
    showSuccessPopup("Form data saved manually")
  }

  function loadFormData() {
    const autoSaveFields = ["customer_name", "sample_description", "weight", "carat"]
    let hasData = false

    autoSaveFields.forEach((fieldId) => {
      const field = document.getElementById(fieldId)
      const savedValue = localStorage.getItem(`manual_save_${fieldId}`)
      if (field && savedValue && !field.value) {
        field.value = savedValue
        hasData = true

        if (fieldId === "carat") {
          setTimeout(() => {
            calculateGoldFormula()
          }, 100)
        }
      }
    })

    if (hasData) {
      showSuccessPopup("Previously saved data loaded")
    }
  }

  window.saveFormData = saveFormData
  window.loadFormData = loadFormData

  // History iframe styling adjustments
  const historyFrame = document.getElementById("historyFrame")
  if (historyFrame) {
    historyFrame.addEventListener("load", () => {
      try {
        const doc = historyFrame.contentDocument || historyFrame.contentWindow.document
        const header = doc && doc.querySelector(".header")
        if (header) header.style.display = "none"
        const container = doc && doc.querySelector(".container")
        if (container) {
          container.style.paddingTop = "0"
        }
        const main = doc && doc.querySelector(".main-content, .history-container")
        if (main) {
          main.style.boxShadow = "none"
          main.style.border = "0"
        }
      } catch (err) {
        console.log("[v0] Could not adjust history iframe styles:", err)
      }
    })
  }

  function clearAutoSaveData() {
    const autoSaveFields = ["customer_name", "sample_description", "weight", "carat"]
    autoSaveFields.forEach((fieldId) => {
      localStorage.removeItem(`manual_save_${fieldId}`)
    })
    console.log("[v0] Auto-save data cleared")
  }

  window.clearAutoSaveData = clearAutoSaveData
})
