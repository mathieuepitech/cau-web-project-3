let working = false;
let lastQuery = null;

let displayContacts = (contacts) => {
    let tbody = $("table tbody");

    for (let contact of contacts) {
        tbody.append(`
            <tr data-id="${contact["id"]}">
                <td>${(contact["first_name"] ? contact["first_name"] : (contact["firstName"] ? contact["firstName"] : ""))}</td>
                <td>${(contact["last_name"] ? contact["last_name"] : (contact["lastName"] ? contact["lastName"] : ""))}</td>
                <td>${(contact["surname"] ? contact["surname"] : "")}</td>
                <td>${(contact["email"] ? contact["email"] : "")}</td>
                <td>${(contact["address"] ? contact["address"] : "")}</td>
                <td>${(contact["phone_number"] ? contact["phone_number"] : (contact["phoneNumber"] ? contact["phoneNumber"] : ""))}</td>
                <td>${(contact["birthday"] ? new Date(contact["birthday"]).toLocaleDateString() : "")}</td>
                <td><i class="material-icons left modify">create</i></td>
                <td><i class="material-icons left delete">delete</i></td>
            </tr>
        `);
    }
};

$(document).ready(() => {


    let search = (query) => {
        if (query.length >= 2) {
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
            lastQuery = query;
        } else {
            let i = setInterval(() => {
                let newQuery = $("#search").val();

                if (newQuery !== query) {
                    clearInterval(i);
                } else {
                    if (!working) {
                        search(query);
                        lastQuery = query;
                        clearInterval(i);
                    }
                }
            }, 50);
        }
    });

    $(document).on("click", ".add-contacts", () => {
        let modal = $("#modal");

        modal.find("input").each((i, e) => {
            let $e = $(e);

            $e.val("");
            $e.removeClass("valid");
            $e.removeClass("invalid");
            $e.parent().find("label").removeClass("active");
        });

        modal.find(".md-title").text("Add Contact");
        modal.removeAttr("data-id");
        modal.addClass("md-show");
    });

    $(document).on("click", ".modify", function () {
        let modal = $("#modal");
        let $contact = $(this).closest("tr");
        let id = parseInt($contact.attr("data-id"), 10);
        let inputs = modal.find("input");
        let td = $contact.find("td");

        modal.find(".md-title").text("Modify Contact");
        modal.attr("data-id", id);
        modal.addClass("md-show");

        for (let i = 0; i < 7; i++) {
            let input = $(inputs[i]);
            let value = $(td[i]).text();

            input.val(value);
            if (value !== "") {
                input.addClass("valid");
                input.parent().find("label").addClass("active");
            }
        }
    });

    $(document).on("click", "#md-cancel", () => {
        $("#modal").removeClass("md-show");
    });

    $(document).on("click", "#md-validate", () => {
        let modal = $("#modal");
        let firstName = $("#first_name").val();
        let lastName = $("#last_name").val();
        let surname = $("#surname").val();
        let email = $("#email").val();
        let address = $("#address").val();
        let phoneNumber = $("#phone_number").val();
        let birthday = $("#birthday").val();

        let id = modal.attr("data-id");

        console.log(modal, id);

        if (firstName !== "" && lastName !== "") {
            let data = {
                firstName,
                lastName,
                surname: (surname !== "" ? surname : null),
                email: (email !== "" ? email : null),
                address: (address !== "" ? address : null),
                phoneNumber: (phoneNumber !== "" ? phoneNumber : null),
                birthday: (birthday !== "" ? birthday : null),
            };

            if (id && parseInt(id, 10) > 0) {
                id = parseInt(id, 10);
                data["id"] = id;
                $.ajax({
                    url: "/api/contact/update",
                    method: "POST",
                    data,
                    success: function (result) {
                        result = JSON.parse(result);

                        for (let i = 0; i < contacts.length; i++) {
                            if (contacts[i].id === id) {
                                contacts[i] = result;
                            }
                        }

                        $("table tbody").empty();
                        displayContacts(contacts);
                        $("#modal").removeClass("md-show");
                    }
                })
            } else {
                $.ajax({
                    url: "/api/contact/insert",
                    method: "POST",
                    data,
                    success: function (result) {
                        result = JSON.parse(result);
                        data["id"] = parseInt(result.data["id"], 10);
                        contacts.push(data);

                        $("table tbody").empty();
                        displayContacts(contacts);
                        $("#modal").removeClass("md-show");
                    }, error: function () {
                    }
                });
            }
        }
    });

    $(document).on("click", ".delete", function () {
        let $contact = $(this).closest("tr");
        let id = parseInt($contact.attr("data-id"), 10);

        $.ajax({
            url: "/api/contact/delete",
            method: "POST",
            data: {
                id
            },
            success: function () {
                contacts = contacts.filter((c) => c.id !== id);
                $contact.remove();
            }
        })
    });

    setInterval(() => {
        $.ajax({
            url: "/api/contact/get-contacts",
            method: "GET",
            success: function (data) {
                contacts = JSON.parse(data).contacts;

                if ($("#search").val().length <= 0) {
                    $("table tbody").empty();
                    displayContacts(contacts);
                }
            }
        })
    }, 10000);

});
