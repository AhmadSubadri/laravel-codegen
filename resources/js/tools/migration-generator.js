document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("migrationForm");
    const sqlInput = document.getElementById("sqlInput");
    const resultContainer = document.getElementById("resultContainer");

    // Add null checks to prevent potential errors
    if (!form || !sqlInput || !resultContainer) {
        console.error("Required DOM elements not found");
        return;
    }

    // Set Prism to manual mode
    Prism.manual = true;

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const sql = sqlInput.value.trim();
        if (sql.length < 10) {
            showError("Please enter valid SQL statements");
            return;
        }

        generateMigrations(sql);
    });

    function generateMigrations(sql) {
        resultContainer.innerHTML = `
        <div class="loading-state">
            <div class="spinner-border text-primary"></div>
            <p>Generating migrations...</p>
        </div>`;

        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (!csrfMeta) {
            showError("CSRF token not found");
            return;
        }

        fetch("/tools/migration-generator/generate", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfMeta.content,
            },
            body: JSON.stringify({ sql: sql }),
        })
            .then((response) => {
                if (!response.ok)
                    throw new Error("Network response was not ok");
                return response.json();
            })
            .then((data) => {
                if (data.success) {
                    displayResults(data.migrations);
                } else {
                    showError(data.error || "Failed to generate migrations");
                }
            })
            .catch((error) => {
                showError("Request failed: " + error.message);
            });
    }

    function displayResults(migrations) {
        if (!migrations || migrations.length === 0) {
            resultContainer.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-circle"></i>
                    <p>No valid CREATE TABLE statements found</p>
                </div>`;
            return;
        }

        let html = '<div class="migration-list">';

        migrations.forEach((migration) => {
            html += `
            <div class="migration-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="m-0">${escapeHtml(
                        migration.table || "Unknown Table"
                    )}</h5>
                    <div class="actions">
                        <button class="btn btn-sm btn-outline-secondary copy-btn me-2" 
                            data-code="${escapeHtml(migration.code || "")}">
                            <i class="far fa-copy"></i> Copy
                        </button>
                        <button class="btn btn-sm btn-primary download-btn"
                            data-filename="${escapeHtml(
                                migration.filename || "migration.php"
                            )}"
                            data-code="${escapeHtml(migration.code || "")}">
                            <i class="fas fa-download"></i> Download
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <pre><code class="language-php">${escapeHtml(
                        migration.code || ""
                    )}</code></pre>
                </div>
            </div>`;
        });

        html += "</div>";
        resultContainer.innerHTML = html;

        // Add event listeners
        document.querySelectorAll(".copy-btn").forEach((btn) => {
            btn.addEventListener("click", function () {
                copyToClipboard(this.dataset.code);
                showToast("Copied to clipboard!");
            });
        });

        document.querySelectorAll(".download-btn").forEach((btn) => {
            btn.addEventListener("click", function () {
                downloadFile(this.dataset.filename, this.dataset.code);
            });
        });

        safeHighlightCode();
    }

    function safeHighlightCode() {
        if (window.Prism && Prism.highlightElement) {
            try {
                const codeElements = resultContainer.querySelectorAll(
                    "pre code.language-php"
                );
                codeElements.forEach((element) => {
                    if (!element.classList.contains("token")) {
                        Prism.highlightElement(element);
                    }
                });
            } catch (error) {
                console.warn("Prism highlighting warning:", error);
            }
        } else {
            console.warn("Prism or highlightElement not available");
        }
    }

    function escapeHtml(unsafe) {
        if (typeof unsafe !== "string") return "";
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function copyToClipboard(text) {
        if (!text) return;
        navigator.clipboard.writeText(text).catch((err) => {
            showError("Failed to copy text: " + err.message);
        });
    }

    function downloadFile(filename, content) {
        if (!filename || !content) return;
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
        toast.className = "toast-message";
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.add("show");
        }, 100);

        setTimeout(() => {
            toast.classList.remove("show");
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    function showError(message) {
        const errorContainer = document.createElement("div");
        errorContainer.className = "alert alert-danger";
        errorContainer.textContent = message;

        resultContainer.innerHTML = "";
        resultContainer.appendChild(errorContainer);

        setTimeout(() => {
            errorContainer.remove();
        }, 5000);
    }
});
