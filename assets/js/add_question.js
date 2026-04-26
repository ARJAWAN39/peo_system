document.addEventListener("DOMContentLoaded", function () {

    const typeSelect = document.getElementById("questionType");
    const configArea = document.getElementById("configArea");
    const hiddenType = document.getElementById("questionTypeHidden");

    if (!typeSelect || !configArea || !hiddenType) return;

    // 🔥 EXISTING CONFIG FROM PHP (EDIT MODE)
    const existingConfig = window.existingQuestionConfig || {};

    // =========================
    // MAIN RENDER FUNCTION
    // =========================
    function renderConfig(type) {
        configArea.innerHTML = "";
        hiddenType.value = type;

        /* =========================
           SHORT ANSWER (PREVIEW ONLY)
        ========================= */
        if (type === "short") {
            configArea.innerHTML = `
                <div class="preview-box small">Short answer text</div>
                <div class="preview-hint">Respondent will answer this question</div>
            `;
        }

        /* =========================
           PARAGRAPH (PREVIEW ONLY)
        ========================= */
        if (type === "paragraph") {
            configArea.innerHTML = `
                <div class="preview-box large">Long answer text</div>
                <div class="preview-hint">Respondent will answer this question</div>
            `;
        }

        /* =========================
           MCQ / CHECKBOX / DROPDOWN
        ========================= */
        if (["mcq", "checkbox", "dropdown"].includes(type)) {
            renderOptions(type);
        }

        /* =========================
           LINEAR SCALE
        ========================= */
        if (type === "scale") {
            const max = existingConfig.scale_max || 5;
            const minLabel = existingConfig.label_min || "";
            const maxLabel = existingConfig.label_max || "";

            configArea.innerHTML = `
                <label>Scale Range</label>
                <select name="scale_max">
                    <option value="5" ${max == 5 ? "selected" : ""}>1 to 5</option>
                    <option value="7" ${max == 7 ? "selected" : ""}>1 to 7</option>
                </select>

                <label>Label for 1 (optional)</label>
                <input type="text" name="scale_label_min" value="${minLabel}">

                <label>Label for max (optional)</label>
                <input type="text" name="scale_label_max" value="${maxLabel}">
            `;
        }

        /* =========================
           RATING
        ========================= */
        if (type === "rating") {
        configArea.innerHTML = `
        <label>Number of stars</label>
        <input type="text" value="5 Stars" disabled>
        <input type="hidden" name="rating_max" value="5">
            `;
        }

        /* =========================
           GRID
        ========================= */
        if (type === "grid") {
        configArea.innerHTML = `
            <label>Rows</label>
            <div id="rows"></div>
            <button type="button" onclick="addRow()">+ Add row</button>

            <label>Columns</label>
            <div id="cols"></div>
            <button type="button" onclick="addCol()">+ Add column</button>
        `;

        const rows = existingConfig?.rows || ["", ""];
        const cols = existingConfig?.columns || ["", ""]; // 🔥 FIX HERE

        rows.forEach(r => addRow(r));
        cols.forEach(c => addCol(c));
    }
    }

    // =========================
    // OPTIONS (MCQ / CHECKBOX / DROPDOWN)
    // =========================
    window.renderOptions = function (type) {
        configArea.innerHTML = `
            <label>Options</label>
            <div id="options"></div>
            <button type="button" class="add-option"
                    onclick="addOption('${type}')">+ Add option</button>
        `;

        const opts = existingConfig.options && existingConfig.options.length
            ? existingConfig.options
            : ["", ""];

        opts.forEach(opt => addOption(type, opt));
    };

    window.addOption = function (type, value = "") {
        const options = document.getElementById("options");
        const count = options.children.length + 1;

        let symbol = "◯";
        if (type === "checkbox") symbol = "☐";
        if (type === "dropdown") symbol = count + ".";

        const div = document.createElement("div");
        div.className = "option-row";
        div.innerHTML = `
            <span class="symbol">${symbol}</span>
            <input type="text" name="options[]" value="${value}">
            <button type="button" onclick="this.parentElement.remove()">✖</button>
        `;
        options.appendChild(div);
    };

    // =========================
    // GRID HELPERS
    // =========================
    window.addRow = function (value = "") {
        const rows = document.getElementById("rows");
        const div = document.createElement("div");
        div.className = "option-row";
        div.innerHTML = `
            <input type="text" name="grid_rows[]" value="${value}">
            <button type="button" onclick="this.parentElement.remove()">✖</button>
        `;
        rows.appendChild(div);
    };

    window.addCol = function (value = "") {
        const cols = document.getElementById("cols");

        const div = document.createElement("div");
        div.className = "option-row";

        div.innerHTML = `
            <input type="text" name="grid_columns[]" value="${value}">
            <button type="button" onclick="this.parentElement.remove()">✖</button>
        `;

        cols.appendChild(div);
    };

    // =========================
    // EVENT LISTENERS
    // =========================
    typeSelect.addEventListener("change", function () {
        window.existingQuestionConfig = {};
        renderConfig(this.value);
    });

    // =========================
    // AUTO LOAD (EDIT MODE)
    // =========================
    if (typeSelect.value) {
        renderConfig(typeSelect.value);
    }

    // =========================
    // FINAL SAFETY NET
    // =========================
    document.querySelector("form").addEventListener("submit", function () {
        if (!hiddenType.value) {
            hiddenType.value = typeSelect.value;
        }
    });

});
