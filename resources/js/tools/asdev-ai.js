document.addEventListener("DOMContentLoaded", function () {
    // Inisialisasi Highlight.js
    hljs.highlightAll();

    // Elemen DOM
    const form = document.getElementById("aiAsistenForm");
    const promptInput = document.getElementById("prompt");
    const chatContainer = document.getElementById("chatContainer");
    const submitBtn = document.getElementById("submitBtn");
    const sendIcon = document.getElementById("sendIcon");
    const loadingSpinner = document.getElementById("loadingSpinner");

    // Event listeners
    promptInput.addEventListener("input", autoResizeTextarea);
    form.addEventListener("submit", handleFormSubmit);

    function autoResizeTextarea() {
        this.style.height = "auto";
        this.style.height = this.scrollHeight + "px";
    }

    async function handleFormSubmit(e) {
        e.preventDefault();
        const prompt = promptInput.value.trim();
        if (!prompt) return;

        displayMessage(prompt, "user");
        promptInput.value = "";
        promptInput.style.height = "auto";
        setLoadingState(true);

        try {
            const loadingId = displayLoadingIndicator();
            const response = await fetchAIResponse(prompt);
            removeLoadingIndicator(loadingId);
            displayMessage(response, "ai");
        } catch (error) {
            displayMessage(`Error: ${error.message}`, "error");
        } finally {
            setLoadingState(false);
        }
    }

    function setLoadingState(isLoading) {
        submitBtn.disabled = isLoading;
        sendIcon.classList.toggle("hidden", isLoading);
        loadingSpinner.classList.toggle("hidden", !isLoading);
    }

    function displayLoadingIndicator() {
        const id = "loading-" + Date.now();
        const timeString = new Date().toLocaleTimeString([], {
            hour: "2-digit",
            minute: "2-digit",
        });

        const loadingDiv = document.createElement("div");
        loadingDiv.className = "flex justify-start mb-4";
        loadingDiv.id = id;
        loadingDiv.innerHTML = `
            <div class="flex">
                <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white font-bold mr-3">AI</div>
                <div class="flex-1">
                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Asdev AI • ${timeString}</div>
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3 text-gray-800 dark:text-gray-200">
                        <div class="typing-indicator"><span></span><span></span><span></span></div>
                    </div>
                </div>
            </div>`;
        chatContainer.appendChild(loadingDiv);
        scrollToBottom();
        return id;
    }

    function removeLoadingIndicator(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    async function fetchAIResponse(prompt) {
        try {
            const response = await fetch("/ai-asisten/llama", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    Accept: "application/json",
                },
                body: JSON.stringify({ prompt }),
            });

            if (!response.ok)
                throw new Error(`HTTP error! status: ${response.status}`);

            const data = await response.json();
            if (data.error)
                throw new Error(
                    data.message || "Terjadi kesalahan pada server"
                );

            return (
                data.response ||
                "Maaf, saya tidak bisa memproses permintaan ini"
            );
        } catch (error) {
            console.error("Fetch error:", error);
            throw new Error(
                "Terjadi masalah saat menghubungi server. Silakan coba lagi nanti."
            );
        }
    }

    function displayMessage(content, type) {
        const safeContent =
            type === "user" || type === "error" ? escapeHtml(content) : content;
        const messageDiv = createMessageElement(safeContent, type);
        chatContainer.appendChild(messageDiv);

        if (type === "ai") {
            highlightCodeBlocks(messageDiv);
            addCopyButtons(messageDiv);
        }

        scrollToBottom();
    }

    function createMessageElement(content, type) {
        const div = document.createElement("div");
        div.className = `flex ${
            type === "user" ? "justify-end" : "justify-start"
        } mb-4`;
        const timeString = new Date().toLocaleTimeString([], {
            hour: "2-digit",
            minute: "2-digit",
        });

        let html = "";
        if (type === "user") {
            html = `<div class="flex flex-col items-end max-w-3/4">
                <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Anda • ${timeString}</div>
                <div class="bg-blue-600 text-white rounded-lg p-3">
                    <p class="whitespace-pre-wrap">${content}</p>
                </div>
            </div>`;
        } else if (type === "ai") {
            html = `<div class="flex">
                <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white font-bold mr-3">AI</div>
                <div class="flex-1">
                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Asdev AI • ${timeString}</div>
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3 text-gray-800 dark:text-gray-200 overflow-x-auto ai-message-content">
                        ${formatCodeBlocks(content)}
                    </div>
                </div>
            </div>`;
        } else {
            html = `<div class="flex justify-start">
                <div class="flex-1">
                    <div class="bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded-lg p-3 max-w-full">
                        <p class="whitespace-pre-wrap">${content}</p>
                    </div>
                </div>
            </div>`;
        }

        div.innerHTML = html;
        return div;
    }

    function escapeHtml(unsafe) {
        if (!unsafe) return "";
        return unsafe
            .toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function formatCodeBlocks(text) {
        // Proteksi blok kode
        let protectedText = text.replace(
            /```([\w-]+)?\s*([\s\S]*?)```/gs,
            (match, lang, code) =>
                `%%CODEBLOCK%%${lang || "plaintext"}%%${code}%%CODEBLOCK%%`
        );

        // Escape HTML
        let escaped = escapeHtml(protectedText)
            .replace(/&lt;br&gt;/g, "<br>")
            .replace(/&amp;nbsp;/g, " ")
            .replace(/&quot;/g, '"')
            .replace(/&amp;/g, "&");

        // Restore blok kode
        escaped = escaped
            .replace(
                /%%CODEBLOCK%%([^%]+)%%([\s\S]*?)%%CODEBLOCK%%/g,
                (match, lang, code) => {
                    return `<div class="code-block">
                    <div class="code-header">
                        <span>${lang}</span>
                        <button class="copy-btn">Salin</button>
                    </div>
                    <pre><code class="language-${lang}">${code.trim()}</code></pre>
                </div>`;
                }
            )
            .replace(/`([^`]+)`/g, '<code class="inline-code">$1</code>')
            .replace(/\*\*(.*?)\*\*/g, "<strong>$1</strong>")
            .replace(/\*(.*?)\*/g, "<em>$1</em>");

        return escaped;
    }

    function highlightCodeBlocks(container) {
        container.querySelectorAll("pre code").forEach((block) => {
            block.className = "";
            const lang =
                block.closest(".code-block")?.querySelector(".code-header span")
                    ?.textContent || "plaintext";
            block.classList.add(`language-${lang}`);

            if (lang !== "plaintext") {
                hljs.highlightElement(block);
            }
        });
    }

    function addCopyButtons(container) {
        container.querySelectorAll(".copy-btn").forEach((btn) => {
            btn.addEventListener("click", function () {
                const code =
                    this.closest(".code-block").querySelector(
                        "code"
                    ).textContent;
                navigator.clipboard.writeText(code).then(() => {
                    const original = this.textContent;
                    this.textContent = "✓ Tersalin";
                    setTimeout(() => (this.textContent = original), 2000);
                });
            });
        });
    }

    function scrollToBottom() {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
});
