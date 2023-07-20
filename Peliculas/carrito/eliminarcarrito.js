function eliminarPelicula(idPelicula) {
    // Realizar la llamada AJAX para eliminar la película del carrito
    // Puedes usar la función fetch() o la librería jQuery.ajax() para hacer la petición al servidor
  
    // Ejemplo de llamada AJAX utilizando fetch():
    fetch('../carrito/eliminar.php?id_peliculas=' + idPelicula, {
      method: 'POST'
    })
    .then(response => {
      if (response.ok) {
        // La película se eliminó correctamente, puedes recargar la página o actualizar el carrito de forma dinámica
        location.reload(); // Recargar la página
      } else {
        console.log('Error al eliminar la película');
      }
    })
    .catch(error => console.error(error));
  }
