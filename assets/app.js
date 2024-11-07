import "./styles/app.css";
import "./styles/list.css";

document.addEventListener("DOMContentLoaded", () => {
    const listContainer = document.getElementById("list-container");

    if(listContainer) {
        const adminUrl = listContainer.getAttribute("data-admin-url");
        const apiUrl = listContainer.getAttribute("data-api-url");

        /* Edit button */
        document.querySelectorAll(".entity-edit-button")
            .forEach(button => {
                button.addEventListener("click", () => {
                    const entityId = button.parentElement.getAttribute("data-id");
                    if(entityId) {
                        window.location.href = `${adminUrl}edit/${entityId}`;
                    }
                });
            });

        /* Delete button */
        document.querySelectorAll(".entity-delete-button")
            .forEach(button => {
                button.addEventListener("click", () => {
                    const entityId = button.parentElement.getAttribute("data-id");
                    if(entityId) {
                        if(confirm("Confirmation de la suppression")) {
                            fetch(`${apiUrl}${entityId}`, {
                                method: "DELETE",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-Requested-With": "XMLHttpRequest"
                                }
                            }).then(response => {
                                if(response.ok) {
                                    location.reload();
                                }
                            })
                        }
                    }
                });
            });

        /* Create button */
        document.getElementById("create-button").addEventListener("click", () => {
           window.location.href = `${adminUrl}create`;
        });
    }
});
