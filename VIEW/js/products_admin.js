const modal = document.getElementById("editModal");
const closeBtn = document.querySelector(".close-modal");

document.querySelectorAll(".openEditModal").forEach(button => {

    button.addEventListener("click", () => {

        const productId = button.dataset.id;

        fetch(
            "../../CONTROLLER/controller_product_admin.php?action=detail&id=" +
            productId
        )
        .then(response => response.json())
        .then(data => {

            console.log(data);  

            document.getElementById("product_id").value =
                data.product.product_id || "";

            document.getElementById("product_name").value =
                data.product.product_name || "";

            document.getElementById("price").value =
                data.product.price || "";

            document.getElementById("category_id").value =
                data.product.category_id || "";

            document.getElementById("thumbnail_url").value =
                data.product.thumbnail_url || "";

            document.getElementById("preview_url").value =
                data.product.preview_url || "";

            document.getElementById("description").value =
                data.product.description || "";

            modal.style.display = "block";

        })
        .catch(error => {

            console.error(error);

            alert("Không thể tải thông tin sản phẩm!");

        });

    });

});

closeBtn.addEventListener("click", () => {

    modal.style.display = "none";

});

window.addEventListener("click", e => {

    if (e.target === modal) {
        modal.style.display = "none";
    }

});