document.addEventListener('DOMContentLoaded', function() {
    const tareaForm = document.getElementById('tareaForm');
    const nuevaTarea = document.getElementById('nuevaTarea');
    const listaTareas = document.getElementById('listaTareas');
    
    // Cargar tareas al inicio
    cargarTareas();
    
    // Agregar nueva tarea
    tareaForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      if (nuevaTarea.value.trim() === '') return;
      
      const formData = new FormData();
      formData.append('accion', 'agregar');
      formData.append('texto', nuevaTarea.value);
      
      fetch('tareas.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.exito) {
          nuevaTarea.value = '';
          cargarTareas();
        }
      });
    });
    
    // Cargar tareas desde el servidor
    function cargarTareas() {
      fetch('tareas.php?accion=listar')
        .then(response => response.json())
        .then(data => {
          listaTareas.innerHTML = '';
          
          data.tareas.forEach(tarea => {
            const li = document.createElement('li');
            li.textContent = tarea.texto;
            li.dataset.id = tarea.id;
            
            const btnEliminar = document.createElement('button');
            btnEliminar.textContent = 'Eliminar';
            btnEliminar.addEventListener('click', function() {
              eliminarTarea(tarea.id);
            });
            
            li.appendChild(btnEliminar);
            listaTareas.appendChild(li);
          });
        });
    }
    
    // Eliminar tarea
    function eliminarTarea(id) {
      const formData = new FormData();
      formData.append('accion', 'eliminar');
      formData.append('id', id);
      
      fetch('tareas.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.exito) {
          cargarTareas();
        }
      });
    }
  });