document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("migrationForm");
    const sqlInput = document.getElementById("sqlInput");
    const resultContainer = document.getElementById("resultContainer");
    const generateModelCheckbox = document.getElementById("generateModel");

    if (!form || !sqlInput || !resultContainer || !generateModelCheckbox) {
        console.error("Required elements not found");
        return;
    }

    form.addEventListener("submit", async function (e) {
        e.preventDefault();

        const sql = sqlInput.value.trim();
        const generateModel = generateModelCheckbox.checked;

        if (!sql || sql.length < 10) {
            showError(
                "Please enter valid SQL statements (minimum 10 characters)"
            );
            return;
        }

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

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                console.error("Server Error Details:", errorData);

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
            <div class="inline-block w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
            <p class="mt-2 text-gray-500">Processing your SQL...</p>
        </div>`;
    }

    function displayResults(files) {
        const resultContainer = document.getElementById("resultContainer");

        if (!files || files.length === 0) {
            resultContainer.innerHTML = `
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 dark:border-yellow-500 text-yellow-700 dark:text-yellow-200 p-4 rounded-lg shadow-sm mb-4">
            <p class="font-medium">No valid migrations were generated.</p>
        </div>`;
            return;
        }

        let html = '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">';

        files.forEach((file) => {
            const badgeClass =
                file.type === "migration"
                    ? "bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border border-blue-300 dark:border-blue-700"
                    : "bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 border border-green-300 dark:border-green-700";

            const tempDiv = document.createElement("div");
            tempDiv.textContent = file.code;
            const escapedCode = tempDiv.innerHTML;

            html += `
        <div class="border border-gray-200 dark:border-gray-600 rounded-lg shadow-md bg-white dark:bg-gray-700 overflow-hidden transition duration-300 hover:shadow-lg dark:hover:shadow-gray-600/30">
            <!-- Header -->
            <div class="bg-gray-50 dark:bg-gray-600/30 px-4 py-3 border-b border-gray-200 dark:border-gray-600">
                <div class="flex items-center space-x-2 mb-2">
                    <span class="inline-block ${badgeClass} text-xs font-semibold rounded-full px-3 py-1">
                        ${file.type}
                    </span>
                    <strong class="text-gray-800 dark:text-gray-100 truncate">${escapeHtml(
                        file.filename
                    )}</strong>
                </div>
                <!-- Button di bawah nama -->
                <div class="flex space-x-2">
                    <button 
                        class="flex items-center text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-100 border border-gray-300 dark:border-gray-500 rounded-lg px-3 py-1 text-sm transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500/50 copy-btn" 
                        data-code="${escapeHtml(file.code)}">
                        <i class="fas fa-copy mr-1"></i> Copy
                    </button>
                    <button 
                        class="flex items-center bg-blue-500 dark:bg-blue-600 hover:bg-blue-600 dark:hover:bg-blue-700 text-white rounded-lg px-3 py-1 text-sm transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500/50 download-btn" 
                        data-filename="${escapeHtml(file.filename)}"
                        data-code="${escapeHtml(file.code)}">
                        <i class="fas fa-download mr-1"></i> Download
                    </button>
                </div>
            </div>
            <!-- Code Output -->
            <div class="p-4 bg-gray-50 dark:bg-gray-300 overflow-x-auto">
                <pre class="rounded-lg text-sm leading-relaxed bg-white dark:bg-gray-300 p-4">
                    <code class="language-php text-gray-800 dark:text-black-800">${escapedCode}</code>
                </pre>
            </div>
        </div>`;
        });

        html += "</div>";
        resultContainer.innerHTML = html;

        addEventListeners();
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
        const maxAttempts = 10;
        let attempts = 0;

        const checkInterval = setInterval(() => {
            attempts++;

            if (
                window.Prism &&
                Prism.languages &&
                Prism.languages.php &&
                typeof Prism.highlightElement === "function"
            ) {
                clearInterval(checkInterval);

                document
                    .querySelectorAll("code.language-php")
                    .forEach((codeBlock) => {
                        try {
                            if (!codeBlock.textContent.trim()) {
                                codeBlock.classList.add("no-highlight");
                                return;
                            }
                            if (Prism.languages.php && Prism.plugins.PHP) {
                                Prism.highlightElement(codeBlock);
                            } else {
                                codeBlock.classList.add("no-highlight");
                            }
                        } catch (e) {
                            console.error("Error highlighting element:", e);
                            codeBlock.classList.add("no-highlight");
                            codeBlock.innerHTML = codeBlock.textContent;
                        }
                    });
            } else if (attempts >= maxAttempts) {
                clearInterval(checkInterval);
                console.warn("Prism.js not fully loaded after max attempts");
                document
                    .querySelectorAll("code.language-php")
                    .forEach((codeBlock) => {
                        codeBlock.classList.add("no-highlight");
                        codeBlock.innerHTML = codeBlock.textContent;
                    });
            }
        }, 100);
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
        <div class="flex items-center bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <span>${escapeHtml(message)}</span>
        </div>`;
    }
});
