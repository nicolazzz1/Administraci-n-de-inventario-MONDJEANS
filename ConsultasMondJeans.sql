use moonjeans;

-- Ventas entre enero y junio
SELECT v.idVentas, p.Nombre AS producto, c.NombreCategoria AS categoria, MONTH(v.Fecha) AS mesVenta,
  LOWER(CONCAT(u.`Nombre 1`, ' ', u.`Apellido 1`)) AS usuario
FROM ventas v
INNER JOIN detalleVenta dv ON v.idVentas = dv.idVentas
INNER JOIN productos p ON dv.idProductos = p.idProductos
LEFT JOIN categoria c ON p.idCategoria = c.idCategoria
INNER JOIN usuario u ON v.idUsuario = u.idUsuario
WHERE MONTH(v.Fecha) BETWEEN 1 AND 6
ORDER BY mesVenta;

-- Todas las ventas con detalle
SELECT v.idVentas, p.Nombre AS producto, c.NombreCategoria AS categoria, MONTH(v.Fecha) AS mesVenta,
  LOWER(CONCAT(u.`Nombre 1`, ' ', u.`Apellido 1`)) AS usuario
FROM ventas v
INNER JOIN detalleVenta dv ON v.idVentas = dv.idVentas
INNER JOIN productos p ON dv.idProductos = p.idProductos
LEFT JOIN categoria c ON p.idCategoria = c.idCategoria
INNER JOIN usuario u ON v.idUsuario = u.idUsuario
ORDER BY mesVenta;

-- Costo total de cada producto comprado
SELECT LOWER(p.Nombre) AS producto, UPPER(pr.Nombre) AS proveedor, cp.Fecha, dc.Cantidad,
  ROUND(dc.Cantidad * dc.costoUnitario, 2) AS totalCosto
FROM detalleCompra dc
INNER JOIN productos p ON dc.idProductos = p.idProductos
INNER JOIN compras cp ON dc.idCompras = cp.idCompras
INNER JOIN proveedores pr ON cp.idProveedores = pr.idProveedores
ORDER BY totalCosto DESC;

-- Productos por proveedor
SELECT UPPER(pr.Nombre) AS proveedor, p.Nombre AS producto
FROM productos p
RIGHT JOIN detalleCompra dc ON p.idProductos = dc.idProductos
INNER JOIN compras cp ON dc.idCompras = cp.idCompras
RIGHT JOIN proveedores pr ON cp.idProveedores = pr.idProveedores
ORDER BY proveedor;

-- Subtotal por proveedor y categoría
SELECT pr.Nombre AS proveedor, p.Nombre AS producto, c.NombreCategoria AS categoria, dc.Cantidad,
  ROUND(dc.Cantidad * dc.costoUnitario, 2) AS subtotal
FROM detalleCompra dc
INNER JOIN productos p ON dc.idProductos = p.idProductos
LEFT JOIN categoria c ON p.idCategoria = c.idCategoria
INNER JOIN compras co ON dc.idCompras = co.idCompras
INNER JOIN proveedores pr ON co.idProveedores = pr.idProveedores;

-- Total de ventas agrupado
SELECT pr.Nombre AS proveedor, c.NombreCategoria AS categoria, SUM(dv.Subtotal) AS total_vendido
FROM detalleVenta dv
INNER JOIN productos p ON dv.idProductos = p.idProductos
INNER JOIN categoria c ON p.idCategoria = c.idCategoria
LEFT JOIN detalleCompra dc ON p.idProductos = dc.idProductos
LEFT JOIN compras co ON dc.idCompras = co.idCompras
LEFT JOIN proveedores pr ON co.idProveedores = pr.idProveedores
GROUP BY pr.Nombre, c.NombreCategoria;

-- Inventario detallado
SELECT p.Nombre AS producto, c.NombreCategoria AS categoria, p.Talla, p.Color, pr.Nombre AS proveedor
FROM productos p
LEFT JOIN categoria c ON p.idCategoria = c.idCategoria
LEFT JOIN detalleCompra dc ON p.idProductos = dc.idProductos
LEFT JOIN compras co ON dc.idCompras = co.idCompras
LEFT JOIN proveedores pr ON co.idProveedores = pr.idProveedores;

-- Compras detalladas
SELECT co.Fecha, p.Nombre AS producto, c.NombreCategoria, pr.Nombre AS proveedor, dc.Cantidad, dc.costoUnitario
FROM detalleCompra dc
INNER JOIN productos p ON dc.idProductos = p.idProductos
LEFT JOIN categoria c ON p.idCategoria = c.idCategoria
INNER JOIN compras co ON dc.idCompras = co.idCompras
INNER JOIN proveedores pr ON co.idProveedores = pr.idProveedores
ORDER BY co.Fecha DESC;

-- Valor inventario por proveedor y categoría
SELECT pr.Nombre AS proveedor, c.NombreCategoria AS categoria,
  SUM(CAST(p.Cantidad AS UNSIGNED) * CAST(p.Precio AS DECIMAL(10,2))) AS valor_inventario
FROM productos p
LEFT JOIN categoria c ON p.idCategoria = c.idCategoria
LEFT JOIN detalleCompra dc ON p.idProductos = dc.idProductos
LEFT JOIN compras co ON dc.idCompras = co.idCompras
LEFT JOIN proveedores pr ON co.idProveedores = pr.idProveedores
GROUP BY pr.Nombre, c.NombreCategoria;

-- Productos con valor inventario > 1000
SELECT p.Nombre AS producto, c.NombreCategoria, pr.Nombre AS proveedor,
  (CAST(p.Cantidad AS UNSIGNED) * CAST(p.Precio AS DECIMAL(10,2))) AS valor_inventario
FROM productos p
LEFT JOIN categoria c ON p.idCategoria = c.idCategoria
LEFT JOIN detalleCompra dc ON p.idProductos = dc.idProductos
LEFT JOIN compras co ON dc.idCompras = co.idCompras
LEFT JOIN proveedores pr ON co.idProveedores = pr.idProveedores
WHERE CAST(p.Cantidad AS UNSIGNED) > 0
GROUP BY p.Nombre, c.NombreCategoria, pr.Nombre, p.Cantidad, p.Precio
HAVING valor_inventario > 1000;

-- Categorías con precio promedio > 100
SELECT c.NombreCategoria AS categoria, ROUND(AVG(CAST(p.Precio AS DECIMAL(10,2))), 2) AS precioPromedio,
  COUNT(p.idProductos) AS cantidadProductos
FROM productos p
INNER JOIN categoria c ON p.idCategoria = c.idCategoria
GROUP BY c.NombreCategoria
HAVING precioPromedio > 100
ORDER BY precioPromedio DESC;

-- Compras mayores a 10,000
SELECT cp.idCompras, cp.Fecha, UPPER(pr.Nombre) AS proveedor, cp.total, MONTH(cp.Fecha) AS mesCompra
FROM compras cp
INNER JOIN proveedores pr ON cp.idProveedores = pr.idProveedores
WHERE cp.total > 10000
ORDER BY cp.total DESC;

-- Costo por año
SELECT p.Nombre AS producto, dc.Cantidad, dc.costoUnitario,
  ROUND(dc.Cantidad * dc.costoUnitario, 2) AS costoTotal, YEAR(cp.Fecha) AS anio
FROM detalleCompra dc
INNER JOIN productos p ON dc.idProductos = p.idProductos
INNER JOIN compras cp ON dc.idCompras = cp.idCompras
LEFT JOIN proveedores pr ON cp.idProveedores = pr.idProveedores
ORDER BY costoTotal DESC;
