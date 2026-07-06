(function () {
    const basePath = window.APP_BASE_PATH || "";
    const state = {
        token: "",
        editingId: null,
    };

    const dom = {};

    document.addEventListener("DOMContentLoaded", init);

    async function init() {
        dom.jwtStatus = document.getElementById("jwt-status");
        dom.resultsCount = document.getElementById("results-count");
        dom.tableBody = document.getElementById("athletes-table-body");
        dom.filterForm = document.getElementById("filter-form");
        dom.filterReset = document.getElementById("filter-reset");
        dom.form = document.getElementById("athlete-form");
        dom.formTitle = document.getElementById("form-title");
        dom.athleteId = document.getElementById("athlete-id");
        dom.deleteBtn = document.getElementById("delete-athlete-btn");
        dom.newAthleteBtn = document.getElementById("new-athlete-btn");
        dom.addAwardBtn = document.getElementById("add-award-btn");
        dom.awardsList = document.getElementById("awards-list");
        dom.awardTemplate = document.getElementById("award-template");
        dom.toastRoot = document.getElementById("toast-root");
        dom.jsonImportForm = document.getElementById("json-import-form");

        bindEvents();
        resetAthleteForm();

        try {
            await ensureToken();
            await loadAthletes();
        } catch (error) {
            showToast(error.message || "JWT token sa nepodarilo získať.", "err");
            dom.tableBody.innerHTML = '<tr><td colspan="4" class="muted">JWT token sa nepodarilo získať.</td></tr>';
        }
    }

    function bindEvents() {
        dom.filterForm.addEventListener("submit", async function (event) {
            event.preventDefault();
            await loadAthletes();
        });

        dom.filterReset.addEventListener("click", async function () {
            dom.filterForm.reset();
            await loadAthletes();
        });

        dom.addAwardBtn.addEventListener("click", function () {
            addAwardRow();
        });

        dom.newAthleteBtn.addEventListener("click", function () {
            resetAthleteForm();
        });

        dom.form.addEventListener("submit", async function (event) {
            event.preventDefault();
            await saveAthlete();
        });

        dom.deleteBtn.addEventListener("click", async function () {
            if (!state.editingId) {
                return;
            }

            try {
                await apiFetch("/api/olympians.php?id=" + encodeURIComponent(state.editingId), {
                    method: "DELETE",
                });
                showToast("Olympionik bol vymazaný.", "ok");
                resetAthleteForm();
                await loadAthletes();
            } catch (error) {
                showToast(error.message, "err");
            }
        });

        dom.awardsList.addEventListener("click", function (event) {
            const removeButton = event.target.closest(".remove-award-btn");
            if (!removeButton) {
                return;
            }

            const row = removeButton.closest(".award-row");
            if (row) {
                row.remove();
            }

            if (!dom.awardsList.children.length) {
                addAwardRow();
            }
        });

        dom.tableBody.addEventListener("click", async function (event) {
            const editButton = event.target.closest("[data-edit-id]");
            if (!editButton) {
                return;
            }

            const athleteId = editButton.getAttribute("data-edit-id");
            if (!athleteId) {
                return;
            }

            try {
                const response = await apiFetch("/api/olympians.php?id=" + encodeURIComponent(athleteId));
                fillAthleteForm(response.data);
                showToast("Formulár je predvyplnený údajmi z API.", "info");
            } catch (error) {
                showToast(error.message, "err");
            }
        });

        dom.jsonImportForm.addEventListener("submit", async function (event) {
            event.preventDefault();

            const fileInput = document.getElementById("json-file");
            if (!fileInput.files.length) {
                showToast("Vyberte JSON súbor.", "err");
                return;
            }

            const formData = new FormData();
            formData.append("json_file", fileInput.files[0]);

            try {
                const response = await apiFetch("/api/olympians-import.php", {
                    method: "POST",
                    body: formData,
                    isFormData: true,
                });

                const errors = Array.isArray(response.errors) ? response.errors : [];
                if (errors.length) {
                    showToast("Import vytvoril " + response.created + " položiek, ale niektoré zlyhali.", "info");
                    showToast(errors.join(" "), "err");
                } else {
                    showToast("JSON import úspešný. Vytvorených olympionikov: " + response.created + ".", "ok");
                }

                dom.jsonImportForm.reset();
                await loadAthletes();
            } catch (error) {
                showToast(error.message, "err");
            }
        });
    }

    async function ensureToken() {
        const response = await fetch(basePath + "/api/auth/token.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            credentials: "same-origin",
            body: "{}",
        });

        const payload = await response.json();
        if (!response.ok || !payload.ok || !payload.token) {
            throw new Error(payload.error || "JWT token sa nepodarilo získať.");
        }

        state.token = payload.token;
        dom.jwtStatus.textContent = "JWT: aktívny";
    }

    async function apiFetch(path, options = {}) {
        const headers = new Headers(options.headers || {});
        headers.set("Authorization", "Bearer " + state.token);

        if (!options.isFormData) {
            headers.set("Content-Type", "application/json");
        }

        const response = await fetch(basePath + path, {
            method: options.method || "GET",
            headers: headers,
            credentials: "same-origin",
            body: options.body
                ? options.isFormData
                    ? options.body
                    : JSON.stringify(options.body)
                : undefined,
        });

        const payload = await response.json();
        if (!response.ok || !payload.ok) {
            throw new Error(payload.error || "API požiadavka zlyhala.");
        }

        return payload;
    }

    function buildFilterQuery() {
        const formData = new FormData(dom.filterForm);
        const params = new URLSearchParams();

        formData.forEach(function (value, key) {
            const normalized = String(value).trim();
            if (normalized !== "") {
                params.set(key, normalized);
            }
        });

        return params.toString();
    }

    async function loadAthletes() {
        dom.tableBody.innerHTML = '<tr><td colspan="4" class="muted">Načítavam dáta z API…</td></tr>';

        try {
            const query = buildFilterQuery();
            const response = await apiFetch("/api/olympians.php" + (query ? "?" + query : ""));
            renderAthletes(response.data || []);
        } catch (error) {
            dom.tableBody.innerHTML = '<tr><td colspan="4" class="muted">Dáta sa nepodarilo načítať.</td></tr>';
            throw error;
        }
    }

    function renderAthletes(items) {
        dom.resultsCount.textContent = "Záznamy: " + items.length;

        if (!items.length) {
            dom.tableBody.innerHTML = '<tr><td colspan="4" class="muted">Žiadne výsledky pre zadané filtre.</td></tr>';
            return;
        }

        dom.tableBody.innerHTML = items.map(function (athlete) {
            const awards = Array.isArray(athlete.awards) ? athlete.awards : [];
            const awardsHtml = awards.map(function (award) {
                return '<div class="award-chip">' +
                    escapeHtml(award.type + " " + award.year + ", " + award.category + " / " + award.discipline + ", umiestnenie " + award.placing) +
                    '</div>';
            }).join("");

            const birthLine = [
                athlete.birth_date || "-",
                athlete.birth_place || "-",
                athlete.birth_country || "-"
            ].join(" • ");

            return '<tr>' +
                '<td><strong>' + escapeHtml(athlete.last_name + " " + athlete.first_name) + '</strong></td>' +
                '<td>' + escapeHtml(birthLine) + '</td>' +
                '<td>' + awardsHtml + '</td>' +
                '<td><button class="btn" type="button" data-edit-id="' + athlete.id + '">Upraviť</button></td>' +
                '</tr>';
        }).join("");
    }

    function resetAthleteForm() {
        state.editingId = null;
        dom.form.reset();
        dom.athleteId.value = "";
        dom.formTitle.textContent = "Pridať olympionika";
        dom.deleteBtn.classList.add("hidden");
        dom.awardsList.innerHTML = "";
        addAwardRow();
    }

    function fillAthleteForm(athlete) {
        state.editingId = athlete.id;
        dom.athleteId.value = athlete.id;
        dom.formTitle.textContent = "Upraviť olympionika #" + athlete.id;
        dom.deleteBtn.classList.remove("hidden");

        setInputValue("first-name", athlete.first_name);
        setInputValue("last-name", athlete.last_name);
        setInputValue("birth-date", athlete.birth_date);
        setInputValue("birth-place", athlete.birth_place);
        setInputValue("birth-country", athlete.birth_country);
        setInputValue("death-date", athlete.death_date);
        setInputValue("death-place", athlete.death_place);
        setInputValue("death-country", athlete.death_country);

        dom.awardsList.innerHTML = "";
        (athlete.awards || []).forEach(function (award) {
            addAwardRow(award);
        });

        if (!dom.awardsList.children.length) {
            addAwardRow();
        }
    }

    function setInputValue(id, value) {
        const input = document.getElementById(id);
        if (input) {
            input.value = value || "";
        }
    }

    function addAwardRow(award) {
        const fragment = dom.awardTemplate.content.cloneNode(true);
        const row = fragment.querySelector(".award-row");

        setRowValue(row, 'select[name="award_type"]', award && award.type ? award.type : "LOH");
        setRowValue(row, 'input[name="award_year"]', award && award.year ? award.year : "");
        setRowValue(row, 'input[name="award_placing"]', award && award.placing ? award.placing : "");
        setRowValue(row, 'input[name="award_category"]', award && award.category ? award.category : "");
        setRowValue(row, 'input[name="award_discipline"]', award && award.discipline ? award.discipline : "");
        setRowValue(row, 'input[name="award_city"]', award && award.city ? award.city : "");
        setRowValue(row, 'input[name="award_host_country"]', award && award.host_country ? award.host_country : "");
        setRowValue(row, 'input[name="award_represented_country"]', award && award.represented_country ? award.represented_country : "");

        dom.awardsList.appendChild(fragment);
    }

    function setRowValue(row, selector, value) {
        const element = row.querySelector(selector);
        if (element) {
            element.value = value;
        }
    }

    function collectPayload() {
        const awards = Array.from(dom.awardsList.querySelectorAll(".award-row")).map(function (row) {
            return {
                type: row.querySelector('[name="award_type"]').value,
                year: Number(row.querySelector('[name="award_year"]').value),
                placing: Number(row.querySelector('[name="award_placing"]').value),
                category: row.querySelector('[name="award_category"]').value.trim(),
                discipline: row.querySelector('[name="award_discipline"]').value.trim(),
                city: row.querySelector('[name="award_city"]').value.trim(),
                host_country: row.querySelector('[name="award_host_country"]').value.trim(),
                represented_country: row.querySelector('[name="award_represented_country"]').value.trim(),
            };
        });

        return {
            first_name: document.getElementById("first-name").value.trim(),
            last_name: document.getElementById("last-name").value.trim(),
            birth_date: document.getElementById("birth-date").value,
            birth_place: document.getElementById("birth-place").value.trim(),
            birth_country: document.getElementById("birth-country").value.trim(),
            death_date: document.getElementById("death-date").value,
            death_place: document.getElementById("death-place").value.trim(),
            death_country: document.getElementById("death-country").value.trim(),
            awards: awards,
        };
    }

    async function saveAthlete() {
        const payload = collectPayload();

        try {
            if (state.editingId) {
                await apiFetch("/api/olympians.php?id=" + encodeURIComponent(state.editingId), {
                    method: "PUT",
                    body: payload,
                });
                showToast("Olympionik bol upravený.", "ok");
            } else {
                await apiFetch("/api/olympians.php", {
                    method: "POST",
                    body: payload,
                });
                showToast("Olympionik bol pridaný.", "ok");
            }

            resetAthleteForm();
            await loadAthletes();
        } catch (error) {
            showToast(error.message, "err");
        }
    }

    function showToast(message, type) {
        const toast = document.createElement("div");
        toast.className = "toast " + (type || "info");
        toast.textContent = message;
        dom.toastRoot.appendChild(toast);

        window.setTimeout(function () {
            toast.remove();
        }, 4500);
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
})();
