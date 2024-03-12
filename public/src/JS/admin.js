$(document).ready(function() {
    // Función para cargar los primeros 10 usuarios al cargar la página
    function loadInitialUsers() {
        $.ajax({
            type: 'GET',
            url: '../JSON/load.json', // Ruta al script PHP que carga los usuarios
            dataType: 'json',
            success: function(response) {
                // Limpiamos la tabla antes de agregar los nuevos resultados
                $('#userTable tbody').empty();
                // Iteramos sobre los resultados y los agregamos a la tabla
                $.each(response, function(index, user) {
                    $('#userTable tbody').append('<tr><td>' + user.id + '</td><td>' + user.username + '</td></tr>');
                });
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar los primeros usuarios:', xhr.responseText, error, status);
                // Manejar errores aquí
            }
        });
    }

    // Llamar a la función para cargar los primeros 10 usuarios al cargar la página
    loadInitialUsers();

    // Manejar la búsqueda cuando se escriba en el campo de búsqueda
    $('#search').keyup(function() {
        var query = $(this).val();

        $.ajax({
            url: '../JSON/search.json', // Ruta al script PHP que maneja la búsqueda
            type: 'GET', // Método de solicitud
            data: {q: query}, // Datos a enviar, en este caso la consulta de búsqueda
            dataType: 'json', // Tipo de datos esperados en la respuesta
            success: function(response) {
                // Limpiamos la tabla antes de agregar los nuevos resultados
                $('#userTable tbody').empty();
                // Iteramos sobre los resultados y los agregamos a la tabla
                $.each(response, function(index, user) {
                    $('#userTable tbody').append('<tr><td>' + user.id + '</td><td>' + user.username + '</td></tr>');
                });
            },
            error: function(xhr, status, error) {
                console.error('Error en la búsqueda:', xhr.responseText);
                // Manejar errores aquí
            }
        });
    });
});
