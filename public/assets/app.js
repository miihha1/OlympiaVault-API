(function () {
    const name = "cookie_consent";
    const has = document.cookie.split(";").some(c => c.trim().startsWith(name + "="));
    if (has) return;

    const banner = document.createElement("div");
    banner.style.position = "fixed";
    banner.style.left = "0";
    banner.style.right = "0";
    banner.style.bottom = "0";
    banner.style.borderTop = "1px solid #e5e7eb";
    banner.style.background = "#fff";
    banner.style.padding = "12px 16px";
    banner.style.display = "flex";
    banner.style.gap = "12px";
    banner.style.alignItems = "center";
    banner.style.justifyContent = "space-between";
    banner.style.fontFamily = "system-ui, Arial";
    banner.style.zIndex = "9999";

    banner.innerHTML = `
    <div style="color:#111827;font-size:13px">
      Táto stránka používa cookies pre prihlásenie a základnú funkcionalitu.
    </div>
    <button id="cookieOk" style="border:1px solid #111827;background:#fff;border-radius:10px;padding:8px 10px;cursor:pointer">
      Rozumiem
    </button>
  `;
    document.body.appendChild(banner);

    document.getElementById("cookieOk").addEventListener("click", () => {
        document.cookie = name + "=1; Max-Age=" + (60*60*24*365) + "; Path=/";
        banner.remove();
    });
})();