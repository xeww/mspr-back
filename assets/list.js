export default function handleList() {
    const listContainer = document.querySelector(".list-container");

    if(listContainer) {
        const adminUrl = listContainer.getAttribute("data-url");

        document.querySelectorAll(".entity-edit-button")
            .forEach(button => {
                button.addEventListener("click", () => {
                    const entityId = button.parentElement.getAttribute("data-id");
                    if(entityId) {
                        window.location.href = `${adminUrl}edit/${entityId}`;
                    }
                });
            });

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

        const entityCreateButton = document.querySelector(".entity-create-button");
        if(entityCreateButton) {
            entityCreateButton.addEventListener("click", () => {
                window.location.href = `${adminUrl}create`;
            });
        }
    }
}