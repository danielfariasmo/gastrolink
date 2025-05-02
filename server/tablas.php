<?php
/*---------------------------------------------------------------
Función para generar el hash de la contraseña
---------------------------------------------------------------*/
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/*---------------------------------------------------------------
USUARIOS
---------------------------------------------------------------*/
$usuario = "CREATE TABLE IF NOT EXISTS usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    contraseña VARCHAR(255) NOT NULL,
    img_usuario VARCHAR(255),
    tipo_usuario ENUM('restaurante', 'cocinero', 'camarero') NOT NULL
);";
mysqli_query($connection, $usuario) or die('ERROR: No se puede crear la tabla usuario: ' . mysqli_error($connection));

/*---------------------------------------------------------------
RESTAURANTE
---------------------------------------------------------------*/
$restaurante = "CREATE TABLE IF NOT EXISTS restaurante (
    id_restaurante INT PRIMARY KEY,
    nombre_empresa VARCHAR(100) NOT NULL,
    descripcion TEXT,
    direccion VARCHAR(255),
    web VARCHAR(100),
    telefono VARCHAR(20),
    img VARCHAR(255),
    FOREIGN KEY (id_restaurante) REFERENCES usuario(id_usuario)
);";
mysqli_query($connection, $restaurante) or die('ERROR: No se puede crear la tabla restaurante: ' . mysqli_error($connection));

/*---------------------------------------------------------------
COCINERO
---------------------------------------------------------------*/
$cocinero = "CREATE TABLE IF NOT EXISTS cocinero (
    id_cocinero INT PRIMARY KEY,
    especialidad VARCHAR(100),
    cv_pdf VARCHAR(255),
    experiencia TEXT,
    FOREIGN KEY (id_cocinero) REFERENCES usuario(id_usuario)
);";
mysqli_query($connection, $cocinero) or die('ERROR: No se puede crear la tabla cocinero: ' . mysqli_error($connection));

/*---------------------------------------------------------------
CAMARERO
---------------------------------------------------------------*/
$camarero = "CREATE TABLE IF NOT EXISTS camarero (
    id_camarero INT PRIMARY KEY,
    cv_pdf VARCHAR(255),
    FOREIGN KEY (id_camarero) REFERENCES usuario(id_usuario)
);";
mysqli_query($connection, $camarero) or die('ERROR: No se puede crear la tabla camarero: ' . mysqli_error($connection));

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
CV_ENVIADO
---------------------------------------------------------------*/
$cv = "CREATE TABLE IF NOT EXISTS cv_enviado (
    id_envio_cv INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_restaurante INT NOT NULL,
    mensaje TEXT,
    fecha_envio DATE,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_restaurante) REFERENCES restaurante(id_restaurante)
);";
mysqli_query($connection, $cv) or die('ERROR: No se puede crear la tabla cv_enviado: ' . mysqli_error($connection));

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

/*---------------------------------------------------------------
RECETA
---------------------------------------------------------------*/
mysqli_query($connection, $evento) or die('ERROR: No se puede crear la tabla evento: ' . mysqli_error($connection));
$receta = "CREATE TABLE IF NOT EXISTS receta (
    id_receta INT AUTO_INCREMENT PRIMARY KEY,
    id_cocinero INT NOT NULL,
    titulo VARCHAR(100),
    introduccion TEXT,
    ingredientes TEXT,
    pasos TEXT,
    fecha_publicacion DATE,
    FOREIGN KEY (id_cocinero) REFERENCES cocinero(id_cocinero)
);";
mysqli_query($connection, $receta) or die('ERROR: No se puede crear la tabla receta: ' . mysqli_error($connection));

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
