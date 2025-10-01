# Guía de Autenticación JWT con Laravel Sanctum

## Configuración

La autenticación JWT está configurada usando Laravel Sanctum. El sistema está listo para usar.

### Variables de Entorno Necesarias

Agrega estas variables a tu archivo `.env`:

```env
# Sanctum Configuration
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1
SANCTUM_TOKEN_PREFIX=
```

## Endpoints de Autenticación

### 1. Registro de Usuario
```http
POST /api/auth/register
Content-Type: application/json

{
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Respuesta exitosa (201):**
```json
{
    "success": true,
    "message": "Usuario registrado exitosamente",
    "data": {
        "user": {
            "id": 1,
            "name": "Juan Pérez",
            "email": "juan@example.com",
            "created_at": "2025-01-01T00:00:00.000000Z",
            "updated_at": "2025-01-01T00:00:00.000000Z"
        },
        "token": "1|abcdef123456..."
    }
}
```

### 2. Login
```http
POST /api/auth/login
Content-Type: application/json

{
    "email": "juan@example.com",
    "password": "password123"
}
```

**Respuesta exitosa (200):**
```json
{
    "success": true,
    "message": "Login exitoso",
    "data": {
        "user": {
            "id": 1,
            "name": "Juan Pérez",
            "email": "juan@example.com"
        },
        "token": "1|abcdef123456..."
    }
}
```

### 3. Obtener Perfil del Usuario
```http
GET /api/auth/me
Authorization: Bearer 1|abcdef123456...
```

**Respuesta exitosa (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Juan Pérez",
        "email": "juan@example.com",
        "phone": null,
        "position": null,
        "bio": null
    }
}
```

### 4. Actualizar Perfil
```http
PUT /api/auth/profile
Authorization: Bearer 1|abcdef123456...
Content-Type: application/json

{
    "name": "Juan Carlos Pérez",
    "phone": "+1234567890",
    "position": "Desarrollador",
    "bio": "Desarrollador full-stack"
}
```

### 5. Cambiar Contraseña
```http
PUT /api/auth/password
Authorization: Bearer 1|abcdef123456...
Content-Type: application/json

{
    "current_password": "password123",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

### 6. Logout
```http
POST /api/auth/logout
Authorization: Bearer 1|abcdef123456...
```

**Respuesta exitosa (200):**
```json
{
    "success": true,
    "message": "Logout exitoso"
}
```

## Uso del Token

### En el Frontend (JavaScript/Fetch)
```javascript
// Almacenar el token después del login
localStorage.setItem('token', response.data.token);

// Usar el token en las peticiones
const token = localStorage.getItem('token');

fetch('/api/auth/me', {
    headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
})
.then(response => response.json())
.then(data => console.log(data));
```

### En el Frontend (Axios)
```javascript
// Configurar Axios con el token
axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
axios.defaults.headers.common['Accept'] = 'application/json';

// O configurar un interceptor
axios.interceptors.request.use(config => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});
```

## Rutas Protegidas

Todas las rutas bajo el prefijo `/api/` (excepto las de autenticación) requieren el token de autorización:

- `/api/users/*` - Gestión de usuarios
- `/api/customers/*` - Gestión de clientes
- `/api/invoices/*` - Gestión de facturas
- `/api/items/*` - Gestión de items
- `/api/attached-files/*` - Gestión de archivos
- `/api/dashboard/stats` - Estadísticas del dashboard

## Manejo de Errores

### Token Inválido o Expirado (401)
```json
{
    "success": false,
    "message": "Token de autenticación inválido o expirado",
    "error": "Unauthenticated"
}
```

### Credenciales Incorrectas (401)
```json
{
    "success": false,
    "message": "Credenciales incorrectas"
}
```

### Errores de Validación (422)
```json
{
    "success": false,
    "message": "Datos de validación incorrectos",
    "errors": {
        "email": ["El campo email es obligatorio."],
        "password": ["El campo password debe tener al menos 6 caracteres."]
    }
}
```

## Configuración del Token

- **Expiración**: 7 días (configurable en `config/sanctum.php`)
- **Prefijo**: Opcional (configurable con `SANCTUM_TOKEN_PREFIX`)
- **Formato**: `{id}|{token}` (ejemplo: `1|abcdef123456...`)

## Seguridad

1. **HTTPS**: Usa HTTPS en producción
2. **Token Storage**: Almacena el token de forma segura (no en localStorage para aplicaciones sensibles)
3. **Expiración**: Los tokens expiran automáticamente
4. **Logout**: Siempre elimina el token del cliente al hacer logout
5. **Refresh**: Considera implementar refresh tokens para aplicaciones de larga duración

## Testing

Puedes probar la API usando herramientas como:
- Postman
- Insomnia
- curl
- Thunder Client (VS Code)

### Ejemplo con curl:
```bash
# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"test@example.com","password":"password123"}'

# Usar el token
curl -X GET http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer 1|abcdef123456..." \
  -H "Accept: application/json"
```
