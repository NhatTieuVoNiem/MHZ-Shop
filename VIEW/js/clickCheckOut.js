const modal = document.getElementById("checkoutModal");

document
    .getElementById("openCheckout")
    .addEventListener("click", () => {
        modal.classList.add("active");
    });

document
    .getElementById("closeCheckout")
    .addEventListener("click", () => {
        modal.classList.remove("active");
    });

modal.addEventListener("click", (e) => {

    if (e.target === modal) {
        modal.classList.remove("active");
    }

});
