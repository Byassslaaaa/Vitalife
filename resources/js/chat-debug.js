// Debug script untuk chat
document.addEventListener("DOMContentLoaded", () => {
  // Override console.log untuk debugging
  const originalLog = console.log
  console.log = (...args) => {
    originalLog.apply(console, ["[CHAT DEBUG]", ...args])
  }

  // Debug fetch requests
  const originalFetch = window.fetch
  window.fetch = function (...args) {
    console.log("Fetch request:", args[0], args[1])
    return originalFetch
      .apply(this, args)
      .then((response) => {
        console.log("Fetch response:", response.status, response.statusText)
        return response
      })
      .catch((error) => {
        console.error("Fetch error:", error)
        throw error
      })
  }

  console.log("Chat debug script loaded")
})
