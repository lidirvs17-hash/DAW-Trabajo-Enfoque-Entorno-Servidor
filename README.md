# CROSS-KICKS 👟 - E-commerce de Calzado Especializado

Este proyecto es una aplicación web de comercio electrónico desarrollada para el **Grado Superior de DAW**. La plataforma permite la gestión integral de productos (zapatillas), usuarios, carritos de compra y un historial detallado de pedidos con persistencia de datos.

## 🏗️ Arquitectura del Sistema

La aplicación sigue un patrón de **Arquitectura Multicapa (Tres Capas)** para garantizar el desacoplamiento y la mantenibilidad del código:

1.  **Capa de Presentación (Vista):** Archivos PHP en la raíz (`index.php`, `catalogo.php`, `historial_pedidos.php`) que gestionan la interfaz de usuario mediante HTML5, CSS3 y Bootstrap.
2.  **Capa de Lógica de Negocio (Servicios):** Ubicada en `/servicios/`. Contiene la inteligencia de la aplicación:
    * `order_service.php`: Procesa el checkout y validaciones de compra.
    * `cart_service.php`: Gestiona el estado del carrito en la sesión.
    * `user_service.php`: Controla la autenticación y perfiles.
3.  **Capa de Acceso a Datos (DAO):** Ubicada en `/datos/`. Interactúa directamente con la base de datos MySQL mediante sentencias preparadas para prevenir SQL Injection:
    * `order_dao.php`: Gestiona transacciones SQL complejas.
    * `pedido_dao.php`: Recupera información histórica de ventas.

## 🚀 Características Destacadas

* **Gestión de Transacciones:** El proceso de compra utiliza transacciones SQL (`begin_transaction`, `commit`, `rollback`) para asegurar la integridad entre la creación del pedido y la actualización del stock.
* **Persistencia Histórica:** Se implementó una lógica de "Snapshot" en los pedidos. Al realizar una compra, la talla y el precio se guardan directamente en la tabla `detalle_pedido`, garantizando que el historial sea inalterable aunque el producto cambie en el catálogo.
* **Seguridad:** Uso de `password_hash` para el almacenamiento de credenciales y `bind_param` en todas las consultas a la base de datos.
* **Panel de Administración:** Gestión de inventario (CRUD) y control de usuarios según roles (`Admin` / `Cliente`).

## 🛠️ Tecnologías Utilizadas

* **Backend:** PHP 7.4+
* **Base de Datos:** MySQL / MariaDB
* **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5
* **Iconografía:** FontAwesome

## 📋 Instalación

1. Clona el repositorio.
2. Importa el archivo `if0_40734835_cross_kicks.sql` en tu servidor MySQL.
3. Configura las credenciales de conexión en `datos/db_connection.php`.
4. Asegúrate de tener habilitadas las sesiones en tu servidor PHP.

---
**Desarrollado como proyecto para el ciclo de Desarrollo de Aplicaciones Web (DAW).**

📁 Estructura del Proyecto
Plaintext

├── assets/             # Recursos estáticos (CSS, JS, Imágenes)
├── datos/              # Capa de acceso a datos (DAOs y conexión)
├── servicios/          # Lógica de negocio y servicios auxiliares
├── index.php           # Punto de entrada principal
├── catalogo.php        # Visualización de productos
├── gestion_usuarios.php # Panel de administración
└── if0_40734835_cross_kicks.sql # Script de creación de la BD
🔧 Instalación y Configuración

Servidor:

Requiere un servidor compatible con PHP 7.4 o superior (XAMPP, Laragon, etc.).

🎓 Notas de la Desarrolladora
Este proyecto hace especial hincapié en la separación de responsabilidades. Aunque no es un MVC estricto, se ha organizado el código en servicios y datos para mejorar la mantenibilidad y escalabilidad del software. Se han implementado validaciones tanto en cliente (HTML5/JS) como en servidor (PHP) para garantizar la integridad de los datos.
