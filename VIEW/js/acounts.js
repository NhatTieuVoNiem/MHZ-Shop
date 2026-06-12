const modal = document.getElementById("editModal");
const closeBtn = document.querySelector(".close-modal");

document.querySelectorAll(".openEditModal").forEach(btn => {

    btn.addEventListener("click", () => {

        const id = btn.dataset.id;

       fetch("../../CONTROLLER/controller_account.php?action=detail&id=" + id)
            .then(res => res.json())
            .then(data => {

                console.log(data);
                

                document.getElementById("account_id").value =
                    data.account.account_id;

                document.getElementById("username").value =
                    data.account.username || "";

                document.getElementById("email").value =
                    data.account.email || "";

                document.getElementById("role_id").value =
                    data.account.role_id || "";

                document.getElementById("profile_id").value =
                    data.profile?.profile_id || "";

                document.getElementById("last_name").value =
                    data.profile?.last_name || "";

                document.getElementById("middle_name").value =
                    data.profile?.middle_name || "";

                document.getElementById("first_name").value =
                    data.profile?.first_name || "";

                document.getElementById("gender_id").value =
                    data.profile?.gender_id || "";

                document.getElementById("date_of_birth").value =
                    data.profile?.date_of_birth || "";

                document.getElementById("phone").value =
                    data.profile?.phone || "";

                document.getElementById("bio").value =
                    data.profile?.bio || "";

                modal.style.display = "flex";
            })
            .catch(err => {
                console.error(err);
            });

    });

});

closeBtn.addEventListener("click", () => {
    modal.style.display = "none";
});

window.addEventListener("click", (e) => {
    if (e.target === modal) {
        modal.style.display = "none";
    }
});