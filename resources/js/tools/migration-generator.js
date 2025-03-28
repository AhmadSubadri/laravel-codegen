document.addEventListener("DOMContentLoaded", function () {
    // DOM Elements
    const form = document.getElementById("migrationForm");
    const sqlInput = document.getElementById("sqlInput");
    const resultContainer = document.getElementById("resultContainer");
    const generateModelCheckbox = document.getElementById("generateModel");

    // Validate required elements
    if (!form || !sqlInput || !resultContainer || !generateModelCheckbox) {
        console.error("Required elements not found");
        return;
    }

    form.addEventListener("submit", async function (e) {
        e.preventDefault();

        const sql = sqlInput.value.trim();
        const generateModel = generateModelCheckbox.checked;

        // Basic validation
        if (!sql || sql.length < 10) {
            showError(
                "Please enter valid SQL statements (minimum 10 characters)"
            );
            return;
        }

        // Show loading state
        showLoading();

        try {
            const response = await fetch(
                "/tools/migration-generator/generate",
                {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                        Accept: "application/json",
                    },
                    body: JSON.stringify({
                        sql: sql,
                        generate_model: generateModel,
                    }),
                }
            );

            // Enhanced error handling
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                console.error("Server Error Details:", errorData);

                // Check for specific error cases
                if (
                    errorData.error &&
                    errorData.error.includes("buildPlaceholders")
                ) {
                    throw new Error(
                        "Invalid SQL syntax. Please check your CREATE TABLE statements."
                    );
                }
                throw new Error(
                    errorData.message ||
                        errorData.error ||
                        "Server processing failed"
                );
            }

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error || "Migration generation failed");
            }

            displayResults(data.files || []);
        } catch (error) {
            console.error("Full Error Details:", {
                error: error,
                message: error.message,
                stack: error.stack,
            });

            let userMessage = error.message;
            if (error.message.includes("buildPlaceholders")) {
                userMessage =
                    "Invalid SQL syntax detected. Please check: \n" +
                    "1. Your CREATE TABLE statements are properly formatted\n" +
                    "2. All parentheses are properly closed\n" +
                    "3. No special characters are breaking the parser";
            }

            showError(userMessage);
        }
    });

    function showLoading() {
        resultContainer.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Processing your SQL...</p>
            </div>`;
    }

    function displayResults(files) {
        if (!files || files.length === 0) {
            resultContainer.innerHTML = `
                <div class="alert alert-warning">
                    No valid migrations were generated.
                </div>`;
            return;
        }

        let html = '<div class="row g-3">';

        files.forEach((file) => {
            const badgeClass =
                file.type === "migration" ? "bg-primary" : "bg-success";

            // Create a temporary div to properly escape HTML
            const tempDiv = document.createElement("div");
            tempDiv.textContent = file.code;
            const escapedCode = tempDiv.innerHTML;

            html += `
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge ${badgeClass} me-2">${
                file.type
            }</span>
                            <strong>${escapeHtml(file.filename)}</strong>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-outline-secondary copy-btn me-2" 
                                data-code="${escapeHtml(file.code)}">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                            <button class="btn btn-sm btn-primary download-btn"
                                data-filename="${escapeHtml(file.filename)}"
                                data-code="${escapeHtml(file.code)}">
                                <i class="fas fa-download"></i> Download
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <pre><code class="language-php">${escapedCode}</code></pre>
                    </div>
                </div>
            </div>`;
        });

        html += "</div>";
        resultContainer.innerHTML = html;

        // Add event listeners
        addEventListeners();

        // Safe Prism highlighting
        safeHighlightCode();
    }

    function addEventListeners() {
        document.querySelectorAll(".copy-btn").forEach((btn) => {
            btn.addEventListener("click", () => {
                copyToClipboard(btn.dataset.code)
                    .then(() => showToast("Copied to clipboard!"))
                    .catch((err) => console.error("Copy failed:", err));
            });
        });

        document.querySelectorAll(".download-btn").forEach((btn) => {
            btn.addEventListener("click", () => {
                downloadFile(btn.dataset.filename, btn.dataset.code);
            });
        });
    }
    function safeHighlightCode() {
        // Wait for Prism to be fully loaded
        const checkPrism = setInterval(() => {
            if (window.Prism && Prism.languages && Prism.languages.php) {
                clearInterval(checkPrism);
                try {
                    // Highlight each code block individually
                    document
                        .querySelectorAll("code.language-php")
                        .forEach((codeBlock) => {
                            try {
                                // Ensure the code block has content
                                if (codeBlock.textContent.trim() === "") {
                                    codeBlock.classList.add("no-highlight");
                                    return;
                                }

                                // Perform the highlighting
                                Prism.highlightElement(codeBlock);
                            } catch (e) {
                                console.error("Error highlighting element:", e);
                                codeBlock.classList.add("no-highlight");
                            }
                        });
                } catch (e) {
                    console.error("Prism highlighting failed:", e);
                }
            }
        }, 100);

        // Timeout if Prism doesn't load
        setTimeout(() => {
            clearInterval(checkPrism);
            if (!window.Prism) {
                console.warn("Prism.js not loaded after timeout");
                document
                    .querySelectorAll("code.language-php")
                    .forEach((codeBlock) => {
                        codeBlock.classList.add("no-highlight");
                    });
            }
        }, 2000);
    }

    // Utility functions
    function escapeHtml(unsafe) {
        return unsafe
            .toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    async function copyToClipboard(text) {
        try {
            await navigator.clipboard.writeText(text);
        } catch (err) {
            console.error("Failed to copy:", err);
            throw err;
        }
    }

    function downloadFile(filename, content) {
        const blob = new Blob([content], { type: "text/plain" });
        const url = URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = filename;
        a.click();
        URL.revokeObjectURL(url);
    }

    function showToast(message) {
        const toast = document.createElement("div");
        toast.className = "toast-message show";
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    function showError(message) {
        resultContainer.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                ${escapeHtml(message)}
            </div>`;
    }
});
