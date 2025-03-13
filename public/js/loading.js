document.addEventListener("DOMContentLoaded", function () {
    let loader = document.createElement("div");
    loader.id = "global-loader";
    loader.innerHTML = `
        <div style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; z-index: 9999;">
            <div style="width: 50px; height: 50px; border: 4px solid white; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div>
        </div>
        <style>
            @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        </style>
    `;
    loader.style.display = "none"; // Hide initially
    document.body.appendChild(loader);

    // Listen for Livewire requests
    document.addEventListener("livewire:load", function () {
        Livewire.hook("message.sent", () => {
            loader.style.display = "flex"; // Show loading
        });

        Livewire.hook("message.processed", () => {
            loader.style.display = "none"; // Hide loading
        });
    });
});
