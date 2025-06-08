// Form handling
document.getElementById("contactForm").addEventListener("submit", function (e) {
  e.preventDefault()

  // Get form data
  const formData = new FormData(this)
  const data = Object.fromEntries(formData)

  // Basic validation
  if (validateForm(data)) {
    // Simulate form submission
    setTimeout(() => {
      showSuccessMessage()
    }, 1000)
  }
})

function validateForm(data) {
  let isValid = true

  // Clear previous errors
  clearErrors()

  // Name validation
  if (!data.name.trim()) {
    showError("nameError", "El nombre es requerido")
    isValid = false
  }

  // Email validation
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  if (!data.email.trim()) {
    showError("emailError", "El email es requerido")
    isValid = false
  } else if (!emailRegex.test(data.email)) {
    showError("emailError", "El email no es válido")
    isValid = false
  }

  // Subject validation
  if (!data.subject) {
    showError("subjectError", "Selecciona un asunto")
    isValid = false
  }

  // Message validation
  if (!data.message.trim()) {
    showError("messageError", "El mensaje es requerido")
    isValid = false
  } else if (data.message.trim().length < 10) {
    showError("messageError", "El mensaje debe tener al menos 10 caracteres")
    isValid = false
  }

  return isValid
}

function showError(elementId, message) {
  const errorElement = document.getElementById(elementId)
  errorElement.textContent = message

  // Add error class to input
  const input = errorElement.previousElementSibling
  input.classList.add("error")
}

function clearErrors() {
  const errorMessages = document.querySelectorAll(".error-message")
  errorMessages.forEach((error) => {
    error.textContent = ""
  })

  const errorInputs = document.querySelectorAll(".error")
  errorInputs.forEach((input) => {
    input.classList.remove("error")
  })
}

function showSuccessMessage() {
  document.getElementById("contactForm").classList.add("hidden")
  document.getElementById("successMessage").classList.remove("hidden")
}

function resetForm() {
  document.getElementById("contactForm").classList.remove("hidden")
  document.getElementById("successMessage").classList.add("hidden")
  document.getElementById("contactForm").reset()
  clearErrors()
}

// FAQ functionality
function toggleFaq(index) {
  const faqItems = document.querySelectorAll(".faq-item")
  const currentItem = faqItems[index]

  // Close all other FAQ items
  faqItems.forEach((item, i) => {
    if (i !== index) {
      item.classList.remove("active")
    }
  })

  // Toggle current item
  currentItem.classList.toggle("active")
}

// Add smooth scrolling for better UX
document.addEventListener("DOMContentLoaded", () => {
  // Add loading animation
  document.body.style.opacity = "0"
  setTimeout(() => {
    document.body.style.transition = "opacity 0.5s ease"
    document.body.style.opacity = "1"
  }, 100)
})

// Form input animations
document.querySelectorAll("input, select, textarea").forEach((input) => {
  input.addEventListener("focus", function () {
    this.parentElement.classList.add("focused")
  })

  input.addEventListener("blur", function () {
    if (!this.value) {
      this.parentElement.classList.remove("focused")
    }
  })
})
