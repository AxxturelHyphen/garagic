document.querySelectorAll(".tarot-card").forEach((card) => {
    card.addEventListener("click", () => {
        const isFlipped = card.getAttribute("aria-pressed") !== "true";

        card.setAttribute("aria-pressed", String(isFlipped));
        card.querySelector(".tarot-card-inner")?.classList.toggle(
            "is-flipped",
            isFlipped,
        );
    });
});
