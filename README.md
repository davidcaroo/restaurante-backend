🍽️ Restaurante Backend API
API RESTful para la gestión de un sistema de reservas de restaurante, clientes y administración, desarrollada en PHP + MySQL, con autenticación JWT.

📂 Estructura del Proyecto
api/ │ ├── auth/ │ ├── login.php # Autenticación de usuario │ ├── register.php # Registro de usuario │ ├── logout.php # Cierre de sesión (invalida el token) │ └── user.php # Devuelve datos del usuario autenticado │ ├── mesas/ │ ├── read.php # Lista todas las mesas │ ├── reservas/ │ ├── read.php # Lista reservas (por usuario/admin) │ ├── create.php # Crea una nueva reserva │ ├── testimonios/ │ ├── read.php # Obtiene testimonios │ ├── create.php # Crea un testimonio │ ├── cupones/ │ ├── validar.php # Valida cupones (opcional) │ ├── config/ │ ├── database.php # Configuración y conexión MySQL │ ├── jwt_helper.php # Utilidades para manejo de JWT │ ├── .env # Variables de entorno (NO subir a producción) ├── .htaccess # CORS / Seguridad └── ...

🚀 Endpoints principales
🔐 Autenticación
POST /auth/login.php
Inicia sesión y devuelve token JWT
Body:
{ "email": "usuario@mail.com", "password": "xxxxxx" }

**Respuesta:**

```json
{ "access_token": "JWT...", "user": { ... } }
```

* **POST `/auth/register.php`**
  Registra un nuevo usuario
  **Body:**

  ```json
  { "name": "Nombre", "email": "usuario@mail.com", "password": "xxxxxx", "phone": "xxxx" }
  ```

* **POST `/auth/logout.php`**
  Cierra sesión (opcional: puede ser dummy, ya que JWT es stateless)

* **GET `/auth/user.php`**
  Retorna los datos del usuario autenticado
  **Headers:**
  `Authorization: Bearer {TOKEN}`

---

### 🪑 **Mesas**

* **GET `/mesas/read.php`**
  Lista todas las mesas disponibles

---

### 📅 **Reservas**

* **GET `/reservas/read.php`**
  Lista reservas (puede filtrar por usuario)
  **Headers:**
  `Authorization: Bearer {TOKEN}`

* **POST `/reservas/create.php`**
  Crea una reserva
  **Body:**

  ```json
  {
    "mesa_id": 2,
    "fecha": "2025-07-15",
    "hora_inicio": "19:00",
    "hora_fin": "21:00",
    "numero_personas": 4,
    "detalles_reserva": "Mesa para cumpleaños"
  }
  ```

---

### 💬 **Testimonios**

* **GET `/testimonios/read.php`**
  Lista testimonios de clientes

* **POST `/testimonios/create.php`**
  Crear nuevo testimonio
  **Body:**

  ```json
  {
    "nombre_cliente": "Juan",
    "comentario": "Excelente atención!"
  }
  ```

---

### 🎟️ **Cupones**

* **POST `/cupones/validar.php`**
  Valida un cupón de descuento
  **Body:**

  ```json
  { "codigo": "PROMO2025" }
  ```

---

## 🔒 **Autenticación**

Todos los endpoints que modifican datos o consultan información privada requieren token JWT en el header:

```
Authorization: Bearer {TOKEN}
```

---

## 🛠️ **Variables de entorno (.env ejemplo):**

```
DB_HOST=localhost
DB_NAME=restaurante
DB_USER=root
DB_PASS=
JWT_SECRET=pon_aqui_tu_clave_secreta
```


## 🧑‍💻 **Requisitos**

* PHP 8.x
* MySQL/MariaDB
* Composer (`composer install`)


## 📝 **Notas**

* Recuerda proteger tu `.env` y nunca subirlo a GitHub.
* Todas las respuestas son en formato JSON.
* Puedes extender la API agregando más endpoints a cada módulo según tus necesidades.

**Proyecto creado por [@davidcaroo](https://github.com/davidcaroo) para la gestión eficiente de restaurantes.**
