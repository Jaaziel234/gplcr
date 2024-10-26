 // Función para establecer la fecha mínima en el campo de fecha
 function setMinDate() {
    var today = new Date();
    var yyyy = today.getFullYear();
    var mm = today.getMonth() + 1; // Enero es 0!
    var dd = today.getDate();

    if (dd < 10) dd = '0' + dd;
    if (mm < 10) mm = '0' + mm;

    var minDate = yyyy + '-' + mm + '-' + dd;
    document.getElementById('fecha').setAttribute('min', minDate);
    document.getElementById('fecha2').setAttribute('min', minDate);
    document.getElementById('fecha3').setAttribute('min', minDate);

  }

  // Llamar a la función cuando el documento esté listo
  document.addEventListener('DOMContentLoaded', function() {
    setMinDate();

    // Restablecer la fecha mínima cada vez que se muestre el modal
    $('#asignadoModal').on('show.bs.modal', function() {
      setMinDate();
    });

     // Restablecer la fecha mínima cada vez que se muestre el modal
     $('#reparacionModal').on('show.bs.modal', function() {
      setMinDate();
    });

     // Restablecer la fecha mínima cada vez que se muestre el modal
    $('#pruebasModal').on('show.bs.modal', function() {
      setMinDate();
    });


  });

/*   <!-- Nos permite convertir automaticamente a MAYUSCULA todos lo que se escriba en el INPUT --> */
    function mayus(element) {
        element.value = element.value.toUpperCase();
    }
