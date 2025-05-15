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

$insertar_cocinero = "INSERT INTO cocinero (id_cocinero, descripcion, especialidad, experiencia) VALUES
    (4, 'Cocinero con 5 años de experiencia en cocina mediterránea.', 'Mediterránea', '5 años'),
    (5, 'Chef especializado en repostería y postres.', 'Repostería', '3 años'),
    (6, 'Cocinero con experiencia en cocina internacional.', 'Internacional', '4 años'),
    (7, 'Chef con especialidad en cocina asiática.', 'Asiática', '6 años');";
mysqli_query($connection, $insertar_cocinero) or die('ERROR: No se pueden insertar los cocineros: ' . mysqli_error($connection));

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
    tipo_receta ENUM('Entrante', 'Plato Principal', 'Postre', 'Vegetariano', 'Sin Gluten') NOT NULL,
    introduccion TEXT,
    ingredientes TEXT,
    pasos TEXT,
    fecha_publicacion DATE,
    img_receta VARCHAR(255),
    FOREIGN KEY (id_cocinero) REFERENCES cocinero(id_cocinero)
);";
mysqli_query($connection, $receta) or die('ERROR: No se puede crear la tabla receta: ' . mysqli_error($connection));

$insertar_receta = "INSERT INTO receta (id_cocinero, titulo, tipo_receta, introduccion, ingredientes, pasos, fecha_publicacion, img_receta) VALUES
    (4, 'Ensalada Mediterránea', 'Entrante', 'Una ensalada fresca y ligera.', 'Lechuga, tomate, cebolla, aceitunas, queso feta, aceite de oliva, sal.', '1. Lavar y cortar los ingredientes. 2. Mezclar y aliñar con aceite y sal.', '2025-05-01', '/gastrolink/app/img/recetas/ensalada-mediterranea.jpg'),
    (5, 'Paella Valenciana', 'Plato Principal', 'Un clásico plato español.', 'Arroz, pollo, conejo, judías verdes, garrofón, azafrán.', '1. Sofreír la carne. 2. Añadir el arroz y el caldo. 3. Cocinar a fuego medio.', '2025-05-02', '/gastrolink/app/img/recetas/paella.jpg'),
    (6, 'Brownie de Chocolate', 'Postre', 'Un postre esponjoso y delicioso.', 'Chocolate, mantequilla, azúcar, huevos, harina, nueces.', '1. Derretir el chocolate y la mantequilla. 2. Mezclar con los demás ingredientes. 3. Hornear a 180ºC durante 25 minutos.', '2025-05-03', '/gastrolink/app/img/recetas/brownie.jpg'),
    (7, 'Sopa de Verduras', 'Vegetariano', 'Una sopa nutritiva y saludable.', 'Zanahoria, calabacín, patata, cebolla, caldo de verduras.', '1. Trocear las verduras. 2. Cocinar en caldo hasta ablandar. 3. Triturar y servir.', '2025-05-04', '/gastrolink/app/img/recetas/sopa_verduras.jpg'),
    (4, 'Pizza Margarita', 'Plato Principal', 'La clásica pizza italiana.', 'Masa de pizza, tomate, queso mozzarella, albahaca.', '1. Extender la masa. 2. Añadir tomate y queso. 3. Hornear a 200ºC durante 15 minutos.', '2025-05-05', '/gastrolink/app/img/recetas/pizza_margarita.jpeg'),
    (5, 'Tarta de Queso', 'Postre', 'Un postre cremoso y delicioso.', 'Queso crema, huevos, azúcar, galletas, mantequilla.', '1. Triturar las galletas y mezclar con mantequilla. 2. Hornear la base. 3. Preparar la mezcla de queso y hornear.', '2025-05-06', '/gastrolink/app/img/recetas/tarta_queso.jpg'),
    (6, 'Gazpacho Andaluz', 'Vegetariano', 'Una sopa fría refrescante.', 'Tomate, pepino, pimiento, ajo, aceite, vinagre.', '1. Trocear los ingredientes. 2. Triturar hasta obtener una mezcla homogénea. 3. Refrigerar y servir frío.', '2025-05-07', '/gastrolink/app/img/recetas/gazpacho.jpg'),
    (7, 'Risotto de Setas', 'Plato Principal', 'Un plato cremoso y sabroso.', 'Arroz arborio, setas, cebolla, caldo de verduras, parmesano.', '1. Sofreír las setas. 2. Añadir el arroz y el caldo poco a poco. 3. Remover hasta que el arroz esté cremoso.', '2025-05-08', '/gastrolink/app/img/recetas/risotto_setas.jpg'),
    (4, 'Bacalao a la Vizcaína', 'Plato Principal', 'Un plato tradicional del norte.', 'Bacalao, cebolla, pimientos, tomate, ajo.', '1. Desalar el bacalao. 2. Sofreír las verduras. 3. Cocinar el bacalao en la salsa.', '2025-05-09', '/gastrolink/app/img/recetas/bacalao_vizcaina.jpg'),
    (5, 'Croquetas de Jamón', 'Entrante', 'Un aperitivo clásico y cremoso.', 'Jamón, harina, leche, mantequilla, huevo, pan rallado.', '1. Preparar la bechamel con jamón. 2. Enfriar y dar forma. 3. Empanar y freír.', '2025-05-10', '/gastrolink/app/img/recetas/croquetas.jpg'),
    (6, 'Flan de Vainilla', 'Postre', 'Un postre suave y dulce.', 'Leche, huevos, azúcar, esencia de vainilla.', '1. Mezclar los ingredientes. 2. Verter en moldes y hornear al baño maría.', '2025-05-11', '/gastrolink/app/img/recetas/flan.jpg'),
    (7, 'Hamburguesa Vegana', 'Vegetariano', 'Una opción deliciosa y saludable.', 'Pan de hamburguesa, hamburguesa vegetal, lechuga, tomate, cebolla.', '1. Cocinar la hamburguesa. 2. Montar con los ingredientes. 3. Servir con salsa vegana.', '2025-05-12', '/gastrolink/app/img/recetas/hamburguesa_vegana.jpg'),
    (4, 'Arroz con Pollo', 'Plato Principal', 'Un plato tradicional y completo.', 'Arroz, pollo, pimientos, guisantes, azafrán.', '1. Sofreír el pollo y las verduras. 2. Añadir el arroz y el caldo. 3. Cocinar hasta que el arroz esté en su punto.', '2025-05-13', '/gastrolink/app/img/recetas/arroz_con_pollo.jpg'),
    (5, 'Mousse de Chocolate', 'Postre', 'Un postre esponjoso y cremoso.', 'Chocolate, nata, huevos, azúcar.', '1. Derretir el chocolate. 2. Mezclar con nata montada y huevo. 3. Refrigerar hasta que cuaje.', '2025-05-14', '/gastrolink/app/img/recetas/mousse.jpg'),
    (6, 'Ensalada de Quinoa', 'Vegetariano', 'Una opción ligera y nutritiva.', 'Quinoa, tomate, pepino, cebolla, aceite de oliva, limón.', '1. Cocinar la quinoa. 2. Mezclar con los ingredientes y aliñar.', '2025-05-15', '/gastrolink/app/img/recetas/ensalada_quinoa.jpeg'),
    (7, 'Empanadas de Carne', 'Plato Principal', 'Un clásico argentino.', 'Masa de empanada, carne picada, cebolla, huevo duro, aceitunas.', '1. Preparar el relleno. 2. Rellenar y cerrar las empanadas. 3. Hornear hasta dorar.', '2025-05-16', '/gastrolink/app/img/recetas/empanadas.jpg');
";

mysqli_query($connection, $insertar_receta) or die('ERROR: No se pueden insertar las recetas: ' . mysqli_error($connection));

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
