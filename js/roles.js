document.addEventListener('DOMContentLoaded', function() {
    const supplierForm = document.getElementById('supplierForm');
    const proveedoresTableBody = document.getElementById('proveedoresTableBody');
    const supplierIdInput = document.getElementById('supplierId');
    const formTitle = document.getElementById('formTitle');
    const submitButton = document.getElementById('submitButton');
    const cancelEditButton = document.getElementById('cancelEdit');

    function fetchProveedores() {
        fetch('provedores_crud.php?action=fetch')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                proveedoresTableBody.innerHTML = '';
                data.forEach(proveedor => {
                    const row = proveedoresTableBody.insertRow();
                    row.innerHTML = `
                        <td>${proveedor.IdProvedores}</td> <td>${proveedor.Nombre}</td>
                        <td>${proveedor.Direccion}</td>
                        <td>${proveedor.TipoProveedor}</td>
                        <td>
                            <button class="editar" data-id="${proveedor.IdProvedores}">Editar</button> <button class="eliminar" data-id="${proveedor.IdProvedores}">Eliminar</button> </td>
                    `;
                });
            })
            .catch(error => {
                console.error('Error al obtener proveedores:', error);
                alert('Hubo un error al cargar los proveedores. Revisa la consola para más detalles.');
            });
    }

    supplierForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData();
        formData.append('nombre', document.getElementById('nombre').value);
        formData.append('direccion', document.getElementById('direccion').value);
        formData.append('tipoProveedor', document.getElementById('tipoProveedor').value);

        const action = supplierIdInput.value ? 'edit' : 'add';
        formData.append('action', action);

        if (action === 'edit') {
            formData.append('id', supplierIdInput.value); // El ID que se envía al PHP
        }

        if (document.getElementById('nombre').value.trim() === '') {
            alert('El nombre del proveedor es obligatorio.');
            return;
        }
        if (document.getElementById('direccion').value.trim() === '') {
            alert('La dirección es obligatoria.');
            return;
        }
        if (document.getElementById('tipoProveedor').value === '') {
            alert('Debe seleccionar un tipo de proveedor.');
            return;
        }

        fetch('provedores_crud.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                supplierForm.reset();
                supplierIdInput.value = '';
                formTitle.textContent = 'Agregar';
                submitButton.textContent = 'Agregar Proveedor';
                cancelEditButton.style.display = 'none';
                fetchProveedores();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error al enviar el formulario:', error);
            alert('Hubo un error de comunicación con el servidor. Revisa la consola para más detalles.');
        });
    });

    proveedoresTableBody.addEventListener('click', function(event) {
        if (event.target.classList.contains('editar')) {
            const id = event.target.dataset.id;
            fetch(`provedores_crud.php?action=fetch`) 
                .then(response => response.json())
                .then(data => {
                    const proveedorToEdit = data.find(p => p.IdProvedores == id); // CAMBIO AQUÍ: IdProvedores
                    if (proveedorToEdit) {
                        supplierIdInput.value = proveedorToEdit.IdProvedores; // CAMBIO AQUÍ: IdProvedores
                        document.getElementById('nombre').value = proveedorToEdit.Nombre;
                        document.getElementById('direccion').value = proveedorToEdit.Direccion;
                        document.getElementById('tipoProveedor').value = proveedorToEdit.TipoProveedor;

                        formTitle.textContent = 'Editar';
                        submitButton.textContent = 'Actualizar Proveedor';
                        cancelEditButton.style.display = 'inline-block';
                    } else {
                        alert('Proveedor no encontrado para edición.');
                    }
                })
                .catch(error => console.error('Error al obtener proveedor para edición:', error));
        } else if (event.target.classList.contains('eliminar')) {
            const id = event.target.dataset.id;
            if (confirm('¿Está seguro de que desea eliminar este proveedor?')) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', id);

                fetch('provedores_crud.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        fetchProveedores();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error al eliminar proveedor:', error);
                    alert('Hubo un error de comunicación al intentar eliminar el proveedor. Revisa la consola.');
                });
            }
        }
    });

    cancelEditButton.addEventListener('click', function() {
        supplierForm.reset();
        supplierIdInput.value = '';
        formTitle.textContent = 'Agregar';
        submitButton.textContent = 'Agregar Proveedor';
        cancelEditButton.style.display = 'none';
    });

    fetchProveedores();
});