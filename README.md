👟 CROSS-KICKS | E-Commerce de Calzado Especializado
📖 Descripción del Proyecto
CROSS-KICKS es una plataforma de comercio electrónico desarrollada como proyecto para el módulo de Desarrollo Web en Entorno Servidor (DWES). La aplicación simula una tienda de zapatillas de ediciones limitadas inspiradas en elementos de la cultura pop y videojuegos, permitiendo la gestión integral de productos, usuarios y pedidos.

El proyecto se enfoca en la implementación de una arquitectura robusta en PHP, gestión de sesiones seguras y una base de datos relacional normalizada.

🚀 Funcionalidades Principales
Para Usuarios (Clientes)
Catálogo Dinámico: Visualización de productos con información detallada de stock por talla.

Gestión de Inventario: Sistema de "Loot" (carrito de compras) con validación de stock en tiempo real mediante selectores dinámicos.

Historial de Misiones: Consulta detallada de pedidos anteriores.

Registro y Perfil: Gestión de datos personales y seguridad de acceso.

Para Administradores
Panel de Control: Gestión centralizada de usuarios (CRUD completo).

Control de Acceso: Middleware de autenticación que protege las rutas sensibles según el rol del usuario (Admin / Cliente).

🛠️ Stack Tecnológico
Backend: PHP (Programación procedimental orientada a servicios).

Frontend: HTML5, CSS3 (Custom Variables), JavaScript (ES6) y Bootstrap 5 para el diseño responsive.

Base de Datos: MariaDB/MySQL.

Seguridad: - Hasheo de contraseñas mediante password_hash().

Prevención de ataques XSS mediante filtrado de inputs y sanitización de salidas (htmlspecialchars).

🗄️ Estructura de la Base de Datos
El sistema utiliza una base de datos llamada if0_40734835_cross_kicks compuesta por las siguientes tablas clave:

articulos: Información técnica y descriptiva de los productos.

articulo_talla: Gestión de stock detallada por variantes de tamaño.

usuarios: Almacenamiento de credenciales y roles.

pedido & detalle_pedido: Relación 1:N para el registro histórico de ventas.

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
Clonar el repositorio:

Bash

git clone https://github.com/tu-usuario/cross-kicks.git
Configurar la Base de Datos:

Importar el archivo .sql incluido en la carpeta raíz en tu gestor de DB (phpMyAdmin/MySQL Workbench).

Ajustar la Conexión:

El archivo datos/db_connection.php detecta automáticamente si el entorno es localhost o remoto para facilitar el despliegue.

Servidor:

Requiere un servidor compatible con PHP 7.4 o superior (XAMPP, Laragon, etc.).

🎓 Notas de la Desarrolladora
Este proyecto hace especial hincapié en la separación de responsabilidades. Aunque no es un MVC estricto, se ha organizado el código en servicios y datos para mejorar la mantenibilidad y escalabilidad del software. Se han implementado validaciones tanto en cliente (HTML5/JS) como en servidor (PHP) para garantizar la integridad de los datos.
