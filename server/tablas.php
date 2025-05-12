<?php
/*---------------------------------------------------------------
Función para generar el hash de la contraseña
---------------------------------------------------------------*/
function hashPassword($password): string
{
    return password_hash($password, PASSWORD_DEFAULT);
}

/*---------------------------------------------------------------
USUARIOS
---------------------------------------------------------------*/
$usuario = "CREATE TABLE IF NOT EXISTS usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    clave VARCHAR(255) NOT NULL,
    img_usuario VARCHAR(255),
    tipo_usuario ENUM('restaurante', 'cocinero', 'camarero') NOT NULL
);";
mysqli_query($connection, $usuario) or die('ERROR: No se puede crear la tabla usuario: ' . mysqli_error($connection));

$insertar_usuario = "INSERT INTO usuario (id_usuario, nombre, correo, clave, img_usuario, tipo_usuario) VALUES
    (1, 'Daniel Farias Morales', 'danielf@correo.com', '" . hashPassword('Daniel123.') . "', 'img/usuario.png', 'camarero'),
    (2, 'Laura García Ruiz', 'laura@correo.com', '" . hashPassword('Laura123.') . "', 'img/usuario.png', 'camarero'),
    (3, 'Antonio Martínez Torres', 'antonio@correo.com', '" . hashPassword('Antonio123.') . "', 'img/usuario.png', 'camarero'),
    (4, 'Daniel González Garrote', 'danielg@correo.com', '" . hashPassword('Daniel123.') . "', 'img/usuario.png', 'cocinero'),
    (5, 'Candela Martínez Sánchez', 'candela@correo.com', '" . hashPassword('Candela123.') . "', 'img/usuario.png', 'cocinero'),
    (6, 'María Fernández López', 'maria@correo.com', '" . hashPassword('Maria123.') . "', 'img/usuario.png', 'cocinero'),
    (7, 'Pedro Sánchez Pérez', 'pedro@correo.com', '" . hashPassword('Pedro123.') . "', 'img/usuario.png', 'cocinero'),
    (8, 'Madrid Gourmet', 'madridgourmet@correo.com', '" . hashPassword('Madrid123.') . "', 'img/usuario.png', 'restaurante'),
    (9, 'Calle del Hambre', 'calledelhambre@correo.com', '" . hashPassword('Calle123.') . "', 'img/usuario.png', 'restaurante'),
    (10, 'Tierra Noble', 'tierranobre@correo.com', '" . hashPassword('Tierranoble123.') . "', 'img/usuario.png', 'restaurante');";
mysqli_query($connection, $insertar_usuario) or die('ERROR: No se puede insertar el usuario: ' . mysqli_error($connection));

/*---------------------------------------------------------------
RESTAURANTE
---------------------------------------------------------------*/
$restaurante = "CREATE TABLE IF NOT EXISTS restaurante (
    id_restaurante INT PRIMARY KEY,
    descripcion TEXT,
    direccion VARCHAR(255),
    web VARCHAR(100),
    telefono VARCHAR(20),
    
    FOREIGN KEY (id_restaurante) REFERENCES usuario(id_usuario)
);";
mysqli_query($connection, $restaurante) or die('ERROR: No se puede crear la tabla restaurante: ' . mysqli_error($connection));

/*---------------------------------------------------------------
COCINERO
---------------------------------------------------------------*/
$cocinero = "CREATE TABLE IF NOT EXISTS cocinero (
    id_cocinero INT PRIMARY KEY,
    descripcion TEXT,
    especialidad VARCHAR(100),
    experiencia TEXT,
    FOREIGN KEY (id_cocinero) REFERENCES usuario(id_usuario)
);";
mysqli_query($connection, $cocinero) or die('ERROR: No se puede crear la tabla cocinero: ' . mysqli_error($connection));

/*---------------------------------------------------------------
CAMARERO
---------------------------------------------------------------*/
$camarero = "CREATE TABLE IF NOT EXISTS camarero (
    id_camarero INT PRIMARY KEY,
    descripcion TEXT,
    experiencia TEXT,
    idiomas VARCHAR(100),
    FOREIGN KEY (id_camarero) REFERENCES usuario(id_usuario)
);";
mysqli_query($connection, $camarero) or die('ERROR: No se puede crear la tabla camarero: ' . mysqli_error($connection));

/*---------------------------------------------------------------
EVENTO
---------------------------------------------------------------*/
$evento = "CREATE TABLE IF NOT EXISTS evento (
    id_evento INT AUTO_INCREMENT PRIMARY KEY,
    id_restaurante INT NOT NULL,
    nombre_evento VARCHAR(100),
    fecha_inicio DATE,
    fecha_fin DATE,
    descripcion TEXT,
    FOREIGN KEY (id_restaurante) REFERENCES restaurante(id_restaurante)
);";
mysqli_query($connection, $evento) or die('ERROR: No se puede crear la tabla evento: ' . mysqli_error($connection));

/*---------------------------------------------------------------
OFERTA
---------------------------------------------------------------*/
$oferta = "CREATE TABLE IF NOT EXISTS oferta (
    id_oferta INT AUTO_INCREMENT PRIMARY KEY,
    id_restaurante INT NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    tipo_puesto ENUM('cocinero', 'camarero') NOT NULL,
    fecha_publicacion DATE,
    estado ENUM('abierta', 'cerrada') DEFAULT 'abierta',
    id_evento INT DEFAULT NULL,
    FOREIGN KEY (id_restaurante) REFERENCES restaurante(id_restaurante),
    FOREIGN KEY (id_evento) REFERENCES evento(id_evento)
);";
mysqli_query($connection, $oferta) or die('ERROR: No se puede crear la tabla oferta: ' . mysqli_error($connection));

/*---------------------------------------------------------------
RECETA
---------------------------------------------------------------*/
$receta = "CREATE TABLE IF NOT EXISTS receta (
    id_receta INT AUTO_INCREMENT PRIMARY KEY,
    id_cocinero INT NOT NULL,
    titulo VARCHAR(100),
    tipo_receta ENUM('entrante', 'plato_principal', 'postre', 'vegetariano', 'sin_gluten') NOT NULL,
    introduccion TEXT,
    ingredientes TEXT,
    pasos TEXT,
    fecha_publicacion DATE,
    img_receta VARCHAR(255),
    FOREIGN KEY (id_cocinero) REFERENCES cocinero(id_cocinero)
);";
mysqli_query($connection, $receta) or die('ERROR: No se puede crear la tabla receta: ' . mysqli_error($connection));

/*---------------------------------------------------------------
CANDIDATURA
---------------------------------------------------------------*/
$candidatura = "CREATE TABLE IF NOT EXISTS candidatura (
    id_candidatura INT AUTO_INCREMENT PRIMARY KEY,
    id_oferta INT NOT NULL,
    id_usuario INT NOT NULL,
    mensaje TEXT,
    fecha_envio DATE,
    FOREIGN KEY (id_oferta) REFERENCES oferta(id_oferta),
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);";
mysqli_query($connection, $candidatura) or die('ERROR: No se puede crear la tabla candidatura: ' . mysqli_error($connection));

/*---------------------------------------------------------------
FAVORITO
---------------------------------------------------------------*/
$favoritos = "CREATE TABLE IF NOT EXISTS favorito (
    id_favorito INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_receta INT DEFAULT NULL,
    id_cocinero INT DEFAULT NULL,
    id_restaurante INT DEFAULT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_receta) REFERENCES receta(id_receta),
    FOREIGN KEY (id_cocinero) REFERENCES cocinero(id_cocinero),
    FOREIGN KEY (id_restaurante) REFERENCES restaurante(id_restaurante)
);";
mysqli_query($connection, $favoritos) or die('ERROR: No se puede crear la tabla favorito: ' . mysqli_error($connection));
