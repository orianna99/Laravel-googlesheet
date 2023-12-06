const tablaBody = document.getElementById('tabla');

function obtenerEstudiantes() {
    fetch('/api/student')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener estudiantes');
            }
            return response.json();
        })
        .then(data => {
            actualizarTabla(data);
        })
        .catch(error => {
            console.error('Error:', error.message);
        });
}

function actualizarTabla(estudiantes) {
    let html = ''
    tablaBody.innerHTML = '';
    estudiantes.forEach(estudiante => {
        html+=`
        <tr onclick='getStudentData(${(estudiante.id)})'>
            <td>${estudiante.name}</td>
            <td>${estudiante.email}</td>
            <td>${estudiante.phone}</td>
        </tr>
        `
    });
    tablaBody.innerHTML = html;
}
function getStudentData (id) {
    console.log(id);
    $('#studentModal').modal('show')
}

document.addEventListener('DOMContentLoaded', function () {
    var closeBtn = document.querySelector('#studentModal .close');
    closeBtn.addEventListener('click', function () {
      $('#studentModal').modal('hide');
    });
  });

obtenerEstudiantes();

