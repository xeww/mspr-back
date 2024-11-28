export default function handleList() {
    const listContainer = document.getElementById("list-container");

    if(listContainer) {
        const adminUrl = listContainer.getAttribute("data-admin-url");

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
                            window.location.href = `${adminUrl}delete/${entityId}`;
                        }
                    }
                });
            });

        /* Create button */
        document.getElementById("create-button").addEventListener("click", () => {
            window.location.href = `${adminUrl}create`;
        });
    }
}