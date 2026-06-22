// Modal thêm sản phẩm
function openCreateModal() {
    document.getElementById("createModal").style.display = "flex";
}

function closeCreateModal() {
    document.getElementById("createModal").style.display = "none";
}

// Modal sửa sản phẩm
function openEditModal(id, name, description, price) {

    document.getElementById("edit_product_id").value = id;
    document.getElementById("edit_product_name").value = name;
    document.getElementById("edit_description").value = description;
    document.getElementById("edit_price").value = price;

    document.getElementById("editModal").style.display = "flex";
}

function closeEditModal() {
    document.getElementById("editModal").style.display = "none";
}

// click ngoài modal để đóng
window.onclick = function(event) {

    let createModal = document.getElementById("createModal");
    let editModal = document.getElementById("editModal");

    if (event.target == createModal) {
        createModal.style.display = "none";
    }

    if (event.target == editModal) {
        editModal.style.display = "none";
    }
}