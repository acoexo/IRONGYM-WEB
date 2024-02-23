$(document).ready(function(){
    // Función para cargar usuarios al cargar la página
    loadUsers("");

    // Función para cargar usuarios al escribir en el campo de búsqueda
    $("#search").keyup(function(){
        var searchText = $(this).val();
        loadUsers(searchText);
    });

    // Función para cargar usuarios mediante AJAX
    function loadUsers(query){
        $.ajax({
            url: "buscar_usuarios.php",
            method: "GET",
            data: { q: query },
            dataType: "json",
            success: function(data){
                var tableBody = $("#userTable tbody");
                tableBody.empty();
                $.each(data, function(index, user){
                    var row = "<tr>" +
                        "<td>" + user.id + "</td>" +
                        "<td>" + user.nombre + "</td>" +
                        "<td><button onclick='viewUser(" + user.id + ")'>Ver</button></td>" +
                        "</tr>";
                    tableBody.append(row);
                });
            }
        });
    }

    // Función para mostrar la información de un usuario
    window.viewUser = function(userId){
        // Aquí puedes redirigir a una página específica para mostrar la información detallada del usuario
        alert("Acceder a la información del usuario con ID: " + userId);
    };
});