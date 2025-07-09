document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("supplierForm");
  const tbody = document.getElementById("proveedoresTableBody");
  const cancelEdit = document.getElementById("cancelEdit");
  const formTitle = document.getElementById("formTitle");
  const submitButton = document.getElementById("submitButton");
  const hiddenId = document.getElementById("supplierId");

  function cargarProveedores() {
    fetch("proveedores_crud.php?accion=listar")
      .then(res => res.json())
      .then(data => {
        tbody.innerHTML = "";
        data.forEach(p => {
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td>${p.idProveedores}</td>
            <td>${p.Nombre}</td>
            <td>${p.Direccion}</td>
            <td>${p.TipoProveedor}</td>
            <td>
              <button class="editar" onclick="editarProveedor(${p.idProveedores}, '${p.Nombre}', '${p.Direccion}', '${p.TipoProveedor}')">Editar</button>
              <button class="eliminar" onclick="eliminarProveedor(${p.idProveedores})">Eliminar</button>
            </td>
          `;
          tbody.appendChild(tr);
        });
      });
  }

  window.editarProveedor = function (id, nombre, direccion, tipo) {
    hiddenId.value = id;
    document.getElementById("nombre").value = nombre;
    document.getElementById("direccion").value = direccion;
    document.getElementById("tipoProveedor").value = tipo;
    formTitle.innerText = "Editar";
    submitButton.innerText = "Actualizar Proveedor";
    cancelEdit.style.display = "inline-block";
  };

  window.eliminarProveedor = function (id) {
    if (confirm("¿Deseas eliminar este proveedor?")) {
      const formData = new FormData();
      formData.append("accion", "eliminar");
      formData.append("id", id);
      fetch("proveedores_crud.php", {
        method: "POST",
        body: formData
      }).then(() => cargarProveedores());
    }
  };

  cancelEdit.addEventListener("click", () => {
    form.reset();
    hiddenId.value = ''; // Limpia el ID
    formTitle.innerText = "Agregar";
    submitButton.innerText = "Agregar Proveedor";
    cancelEdit.style.display = "none";
  });

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(form);
    const accion = hiddenId.value ? "editar" : "agregar";
    formData.append("accion", accion);
    if (accion === "editar") {
      formData.append("id", hiddenId.value);
    }

    fetch("proveedores_crud.php", {
      method: "POST",
      body: formData
    })
    .then(res => res.text())
    .then(() => {
      form.reset();
      hiddenId.value = '';
      cancelEdit.click();
      cargarProveedores();
    });
  });

  cargarProveedores();
});
