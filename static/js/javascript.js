let working = false;
let lastQuery = null;

let displayContacts = (contacts) => {
    let tbody = $("table tbody");

    for (let contact of contacts) {
        tbody.append(`
            <tr>
                <td>${(contact["first_name"] ? contact["first_name"] : (contact["firstName"] ? contact["firstName"] : ""))}</td>
                <td>${(contact["last_name"] ? contact["last_name"] : (contact["lastName"] ? contact["lastName"] : ""))}</td>
                <td>${(contact["surname"] ? contact["surname"] : "")}</td>
                <td>${(contact["email"] ? contact["email"] : "")}</td>
                <td>${(contact["address"] ? contact["address"] : "")}</td>
                <td>${(contact["phone_number"] ? contact["phone_number"] : (contact["phoneNumber"] ? contact["phoneNumber"] : ""))}</td>
                <td>${(contact["birthday"] ? new Date(contact["birthday"]).toLocaleDateString() : "")}</td>
            </tr>
        `);
    }
};

$(document).ready(() => {

    let modal = $('.modal');

    modal.modal();

    let search = (query) => {
        if (query.length >= 3) {
            working = true;
            $.ajax({
                url: "/api/contact/search",
                method: "POST",
                data: {
                    query: query
                },
                success: function (data) {
                    $("table tbody").empty();

                    for (let i = 0; i < 4; i++) {
                        if (data && data.result && data.result[i.toString()] && data.result[i.toString()].length > 0) {
                            displayContacts(data.result[i.toString()]);
                        }
                    }

                    working = false;
                },
                error: function (e) {
                    working = false;
                }
            });
        } else {
            $("table tbody").empty();
            displayContacts(contacts);
        }
    };

    $(document).on("input", "#search", () => {
        let query = $("#search").val();

        if (!working) {
            search(query);
        } else {
            lastQuery = query;
        }
    });

    $(document).on("click", ".add-contacts", () => {
        modal.open();
    });

    $("[data-tooltip]").tooltip();

});
