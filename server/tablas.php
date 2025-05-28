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
    (1, 'Daniel Farias Morales', 'danielf@correo.com', '" . hashPassword('Daniel123.') . "', '/gastrolink/app/img/usuarios/1.jpg', 'camarero'),
    (2, 'Laura García Ruiz', 'laura@correo.com', '" . hashPassword('Laura123.') . "', '/gastrolink/app/img/usuarios/2.webp', 'camarero'),
    (3, 'Antonio Martínez Torres', 'antonio@correo.com', '" . hashPassword('Antonio123.') . "', '/gastrolink/app/img/usuarios/3.jpg', 'camarero'),
    (4, 'Daniel González Garrote', 'danielg@correo.com', '" . hashPassword('Daniel123.') . "', '/gastrolink/app/img/usuarios/4.jpg', 'cocinero'),
    (5, 'Candela Martínez Sánchez', 'candela@correo.com', '" . hashPassword('Candela123.') . "', '/gastrolink/app/img/usuarios/5.webp', 'cocinero'),
    (6, 'María Fernández López', 'maria@correo.com', '" . hashPassword('Maria123.') . "', '/gastrolink/app/img/usuarios/6.png', 'cocinero'),
    (7, 'Pedro Sánchez Pérez', 'pedro@correo.com', '" . hashPassword('Pedro123.') . "', '/gastrolink/app/img/usuarios/7.jpg', 'cocinero'),
    (8, 'Madrid Gourmet', 'madridgourmet@correo.com', '" . hashPassword('Madrid123.') . "', '/gastrolink/app/img/usuarios/8.webp', 'restaurante'),
    (9, 'Calle del Hambre', 'calledelhambre@correo.com', '" . hashPassword('Calle123.') . "', '/gastrolink/app/img/usuarios/9.jpeg', 'restaurante'),
    (10, 'Tierra Noble', 'tierranobre@correo.com', '" . hashPassword('Tierranoble123.') . "', '/gastrolink/app/img/usuarios/10.png', 'restaurante'),
    (11, 'Alejandro Mendoza Ríos', 'alejandromr@correo.com', '" . hashPassword('Alejandro456.') . "', '/gastrolink/app/img/usuarios/11.jpeg', 'camarero'),
    (12, 'Isabel Ortega Silva', 'isabelos@correo.com', '" . hashPassword('Isabel789.') . "', '/gastrolink/app/img/usuarios/12.jpg', 'camarero'),
    (13, 'Ricardo Herrera Castro', 'ricardohc@correo.com', '" . hashPassword('Ricardo123.') . "', '/gastrolink/app/img/usuarios/13.jpeg', 'camarero'),
    (14, 'Patricia Vega Navarro', 'patriciavn@correo.com', '" . hashPassword('Patricia456.') . "', '/gastrolink/app/img/usuarios/14.png', 'camarero'),
    (15, 'Fernando Guzmán Paredes', 'fernandogp@correo.com', '" . hashPassword('Fernando789.') . "', '/gastrolink/app/img/usuarios/15.jpg', 'camarero'),
    (16, 'Beatriz Ríos Mendoza', 'beatrizrm@correo.com', '" . hashPassword('Beatriz123.') . "', '/gastrolink/app/img/usuarios/16.webp', 'cocinero'),
    (17, 'Hugo Silva Ortega', 'hugoso@correo.com', '" . hashPassword('Hugo456.') . "', '/gastrolink/app/img/usuarios/17.jpg', 'cocinero'),
    (18, 'Adriana Castro Herrera', 'adrianach@correo.com', '" . hashPassword('Adriana789.') . "', '/gastrolink/app/img/usuarios/18.avif', 'cocinero'),
    (19, 'Raúl Navarro Vega', 'raulnv@correo.com', '" . hashPassword('Raul123.') . "', '/gastrolink/app/img/usuarios/19.webp', 'cocinero'),
    (20, 'Carmen Paredes Guzmán', 'carmenpg@correo.com', '" . hashPassword('Carmen456.') . "', '/gastrolink/app/img/usuarios/20.jpg', 'cocinero'),
    (21, 'Asador Real', 'asadorreal@correo.com', '" . hashPassword('Asador123.') . "', '/gastrolink/app/img/usuarios/21.jpg', 'restaurante'),
    (22, 'Sushi Madrid', 'sushimadrid@correo.com', '" . hashPassword('Sushimadrid123.') . "', '/gastrolink/app/img/usuarios/22.jpg', 'restaurante'),
    (23, 'Trattoria Málaga', 'trattoria@correo.com', '" . hashPassword('Trattoria123.') . "', '/gastrolink/app/img/usuarios/23.png', 'restaurante'),
    (24, 'La Gaviota', 'marisqueriasantander@correo.com', '" . hashPassword('Marisqueria123.') . "', '/gastrolink/app/img/usuarios/24.jpeg', 'restaurante'),
    (25, 'Brasserie Barceloca', 'brasserie@correo.com', '" . hashPassword('Brasserie123.') . "', '/gastrolink/app/img/usuarios/25.png', 'restaurante'),
    (26, 'Mónica Ríos Mendoza', 'monicarm@correo.com', '" . hashPassword('Monica456.') . "', '/gastrolink/app/img/usuarios/26.jpg', 'camarero'),
    (27, 'Sergio Silva Ortega', 'sergioso@correo.com', '" . hashPassword('Sergio789.') . "', '/gastrolink/app/img/usuarios/27.jpeg', 'camarero'),
    (28, 'Diana Castro Herrera', 'dianach@correo.com', '" . hashPassword('Diana123.') . "', '/gastrolink/app/img/usuarios/28.webp', 'cocinero'),
    (29, 'Arturo Navarro Vega', 'arturonv@correo.com', '" . hashPassword('Arturo456.') . "', '/gastrolink/app/img/usuarios/29.jpg', 'cocinero'),
    (30, 'Café Bilbao', 'cafebilbao@correo.com', '" . hashPassword('Cafebilbao123.') . "', '/gastrolink/app/img/usuarios/30.jpg', 'restaurante'),
    (31, 'Emilio Mendoza Ríos', 'emiliomr@correo.com', '" . hashPassword('Emilio123.') . "', '/gastrolink/app/img/usuarios/31.avif', 'camarero'),
    (32, 'Rocío Ortega Silva', 'rocioos@correo.com', '" . hashPassword('Rocio456.') . "', '/gastrolink/app/img/usuarios/32.jpg', 'cocinero'),
    (33, 'Felipe Herrera Castro', 'felipehc@correo.com', '" . hashPassword('Felipe789.') . "', '/gastrolink/app/img/usuarios/33.jpg', 'cocinero'),
    (34, 'Restaurante Autor', 'restauranteautor@correo.com', '" . hashPassword('Restauranteautor123.') . "', '/gastrolink/app/img/usuarios/34.jpg', 'restaurante'),
    (35, 'Taberna Da Galera', 'tabernamoderna@correo.com', '" . hashPassword('Tabernamoderna123.') . "', '/gastrolink/app/img/usuarios/35.png', 'restaurante');";
mysqli_query($connection, $insertar_usuario) or die('ERROR: No se puede insertar el usuario: ' . mysqli_error($connection));

/*---------------------------------------------------------------
RESTAURANTE
---------------------------------------------------------------*/
$restaurante = "CREATE TABLE IF NOT EXISTS restaurante (
    id_restaurante INT PRIMARY KEY,
    tipo_restaurante ENUM('Mediterráneo', 'Carnes', 'Gourmet', 'Vegetariano', 'Asiático', 'Italiano', 'Mariscos', 'Tapas', 'Francés', 'Cafés') NOT NULL,
    descripcion TEXT,
    direccion VARCHAR(255),
    web VARCHAR(100),
    telefono VARCHAR(20),
    FOREIGN KEY (id_restaurante) REFERENCES usuario(id_usuario)
);";
mysqli_query($connection, $restaurante) or die('ERROR: No se puede crear la tabla restaurante: ' . mysqli_error($connection));

$insertar_restaurante = "INSERT INTO restaurante (id_restaurante, tipo_restaurante, descripcion, direccion, web, telefono) VALUES
    (8, 'Mediterráneo', 'Restaurante gourmet especializado en cocina mediterránea moderna.', 'Calle Mayor 15, Madrid', 'www.mediterraneomadrid.com', '+34 911 111 111'),
    (9, 'Carnes', 'Steakhouse premium con carnes maduradas y selección internacional.', 'Avenida Diagonal 123, Barcelona', 'www.steakhousebcn.com', '+34 922 222 222'),
    (10, 'Vegetariano', 'Restaurante vegano con propuestas innovadoras y sostenibles.', 'Calle Valencia 45, Valencia', 'www.verdevalencia.com', '+34 933 333 333'),
    (21, 'Carnes', 'Asador tradicional con horno de leña y productos locales.', 'Plaza del Sol 8, Sevilla', 'www.asadorsevilla.com', '+34 944 444 444'),
    (22, 'Asiático', 'Restaurante japonés con sushi bar y cocina teppanyaki.', 'Gran Vía 67, Madrid', 'www.sushimadrid.com', '+34 955 555 555'),
    (23, 'Italiano', 'Trattoria auténtica con pasta fresca y vinos italianos.', 'Calle Málaga 12, Málaga', 'www.trattoriamalaga.com', '+34 966 666 666'),
    (24, 'Mariscos', 'Restaurante de mariscos con pescado fresco diario.', 'Paseo Marítimo 3, Santander', 'www.marisqueriasantander.com', '+34 977 777 777'),
    (25, 'Francés', 'Brasserie francesa con ambiente parisino y cocina clásica.', 'Calle Rosellón 89, Barcelona', 'www.brasseriebarcelona.com', '+34 988 888 888'),
    (30, 'Cafés', 'Cafetería-restaurante con horario extendido y cocina internacional.', 'Plaza Central 5, Bilbao', 'www.cafebilbao.com', '+34 999 999 999'),
    (34, 'Gourmet', 'Restaurante de autor con menú degustación y cocina creativa.', 'Calle Michelín 1, San Sebastián', 'www.restauranteautor.com', '+34 910 101 010'),
    (35, 'Tapas', 'Taberna moderna con tapas reinventadas y ambiente contemporáneo.', 'Calle Nueva 23, Zaragoza', 'www.tabernamoderna.com', '+34 920 202 020');";
mysqli_query($connection, $insertar_restaurante) or die('ERROR: No se pueden insertar los restaurantes: ' . mysqli_error($connection));

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
    (7, 'Chef con especialidad en cocina asiática.', 'Asiática', '6 años'),
    (16, 'Especialista en cocina molecular y técnicas vanguardistas.', 'Cocina molecular', '7 años'),
    (17, 'Experto en cocina vegana y platos saludables.', 'Vegana', '4 años'),
    (18, 'Chef con formación en cocina francesa clásica.', 'Francesa', '8 años'),
    (19, 'Especialista en carnes y parrillas.', 'Parrilla', '5 años'),
    (20, 'Pastelera con experiencia en panadería artesanal.', 'Panadería', '6 años'),
    (28, 'Cocinero especializado en pescados y mariscos.', 'Pescados', '4 años'),
    (29, 'Chef con experiencia en cocina fusión latino-asiática.', 'Fusión', '5 años'),
    (32, 'Especialista en cocina mexicana auténtica.', 'Mexicana', '6 años'),
    (33, 'Chef con experiencia en cocina de autor y presentación gourmet.', 'Cocina de autor', '7 años');";
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

$insertar_camarero = "INSERT INTO camarero (id_camarero, descripcion, experiencia, idiomas) VALUES
    (1, 'Camarero con 3 años de experiencia en restaurantes de lujo.', '3 años', 'Español, Inglés'),
    (2, 'Camarera con experiencia en atención al cliente y servicio de mesa.', '2 años', 'Español, Francés'),
    (3, 'Camarero con habilidades en coctelería y servicio de barra.', '4 años', 'Español, Inglés'),
    (11, 'Camarero con experiencia en eventos corporativos.', '3 años', 'Español, Inglés'),
    (12, 'Camarera especializada en servicio de vinos.', '4 años', 'Español, Francés, Italiano'),
    (13, 'Camarero con experiencia en restaurantes temáticos.', '2 años', 'Español, Inglés'),
    (14, 'Camarera con habilidades en servicio rápido y eficiente.', '5 años', 'Español'),
    (15, 'Camarero especializado en atención a grandes grupos.', '3 años', 'Español, Inglés'),
    (26, 'Camarera con experiencia en hoteles 5 estrellas.', '6 años', 'Español, Inglés, Alemán'),
    (27, 'Camarero con conocimientos de mixología avanzada.', '4 años', 'Español, Inglés'),
    (31, 'Camarero jefe con experiencia en gestión de equipos.', '8 años', 'Español, Inglés, Francés');";
mysqli_query($connection, $insertar_camarero) or die('ERROR: No se pueden insertar los camareros: ' . mysqli_error($connection));

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
    FOREIGN KEY (id_restaurante) REFERENCES restaurante(id_restaurante)
);";
mysqli_query($connection, $oferta) or die('ERROR: No se puede crear la tabla oferta: ' . mysqli_error($connection));

$insertar_ofertas = "INSERT INTO oferta (id_restaurante, titulo, descripcion, tipo_puesto, fecha_publicacion) VALUES
    (8, 'Chef de cocina mediterránea', 'Buscamos chef creativo con experiencia en cocina mediterránea moderna.', 'cocinero', '2025-05-01'),
    (8, 'Camarero con inglés', 'Atención al cliente en sala. Imprescindible inglés fluido.', 'camarero', '2025-05-02'),
    (9, 'Parrillero experto en carnes', 'Responsable de la parrilla, dominio de cortes premium y maduración.', 'cocinero', '2025-05-01'),
    (9, 'Camarero de barra', 'Servicio ágil y conocimiento de vinos y carnes.', 'camarero', '2025-05-02'),
    (10, 'Chef vegano', 'Elaboración de platos innovadores y sostenibles sin productos animales.', 'cocinero', '2025-05-01'),
    (10, 'Camarero con empatía', 'Buen trato con clientes y conocimiento de cocina vegetal.', 'camarero', '2025-05-02'),
    (21, 'Asador tradicional', 'Cocinero especializado en horno de leña y recetas tradicionales.', 'cocinero', '2025-05-01'),
    (21, 'Camarero de terraza', 'Atención de mesas exteriores en horario nocturno.', 'camarero', '2025-05-02'),
    (22, 'Sushiman', 'Preparación de sushi, experiencia mínima 2 años.', 'cocinero', '2025-05-01'),
    (22, 'Camarero para teppanyaki', 'Atención en barra japonesa y servicio de showcooking.', 'camarero', '2025-05-02'),
    (23, 'Cocinero de pasta fresca', 'Elaboración artesanal de pasta y platos típicos italianos.', 'cocinero', '2025-05-01'),
    (23, 'Camarero bilingüe', 'Se requiere italiano básico y experiencia previa.', 'camarero', '2025-05-02'),
    (24, 'Chef de mariscos', 'Cocina especializada en mariscos y pescados frescos.', 'cocinero', '2025-05-01'),
    (24, 'Camarero con conocimientos de vinos blancos', 'Servicio en sala y asesoría en maridajes.', 'camarero', '2025-05-02'),
    (25, 'Cocinero de cocina francesa', 'Platos clásicos como boeuf bourguignon y quiche lorraine.', 'cocinero', '2025-05-01'),
    (25, 'Camarero elegante', 'Servicio en ambiente clásico, presentación impecable.', 'camarero', '2025-05-02'),
    (30, 'Cocinero brunch', 'Preparación de desayunos internacionales y platos ligeros.', 'cocinero', '2025-05-01'),
    (30, 'Camarero con experiencia en cafetería', 'Atención en barra, elaboración de cafés y repostería.', 'camarero', '2025-05-02'),
    (34, 'Chef creativo', 'Alta cocina con técnicas de vanguardia y menú degustación.', 'cocinero', '2025-05-01'),
    (34, 'Camarero profesional', 'Experiencia en restaurantes con estrella Michelin.', 'camarero', '2025-05-02'),
    (35, 'Cocinero de tapas modernas', 'Fusión de sabores tradicionales y técnicas modernas.', 'cocinero', '2025-05-01'),
    (35, 'Camarero dinámico', 'Ambiente joven, atención en barra y terraza.', 'camarero', '2025-05-02');";
mysqli_query($connection, $insertar_ofertas) or die('ERROR: No se pueden insertar las ofertas: ' . mysqli_error($connection));

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
    tiempo_preparacion INT,
    porciones INT,
    dificultad ENUM('Fácil', 'Media', 'Difícil'),
    calorias VARCHAR(10),
    proteinas VARCHAR(10),
    carbohidratos VARCHAR(10),
    grasas VARCHAR(10),
    fecha_publicacion DATE,
    img_receta VARCHAR(255),
    FOREIGN KEY (id_cocinero) REFERENCES cocinero(id_cocinero)
);";
mysqli_query($connection, $receta) or die('ERROR: No se puede crear la tabla receta: ' . mysqli_error($connection));

$insertar_receta = "INSERT INTO receta (id_cocinero, titulo, tipo_receta, introduccion, ingredientes, pasos, tiempo_preparacion, porciones, dificultad, calorias, proteinas, carbohidratos, grasas, fecha_publicacion, img_receta) VALUES
    (4, 'Ensalada Mediterránea', 'Entrante', 'Una ensalada fresca y ligera.', 'Lechuga, tomate, cebolla, aceitunas, queso feta, aceite de oliva, sal.', '1. Lavar y cortar los ingredientes. 2. Mezclar y aliñar con aceite y sal.', 10, 2, 'Fácil', '150', '4g', '10g', '7g', '2025-05-01', '/gastrolink/app/img/recetas/ensalada-mediterranea.jpg'),
    (5, 'Paella Valenciana', 'Plato Principal', 'Un clásico plato español.', 'Arroz, pollo, conejo, judías verdes, garrofón, azafrán.', '1. Sofreír la carne. 2. Añadir el arroz y el caldo. 3. Cocinar a fuego medio.', 60, 4, 'Media', '480', '35g', '45g', '20g', '2025-05-02', '/gastrolink/app/img/recetas/paella.jpg'),
    (6, 'Brownie de Chocolate', 'Postre', 'Un postre esponjoso y delicioso.', 'Chocolate, mantequilla, azúcar, huevos, harina, nueces.', '1. Derretir el chocolate y la mantequilla. 2. Mezclar con los demás ingredientes. 3. Hornear a 180ºC durante 25 minutos.', 40, 6, 'Fácil', '400', '5g', '50g', '25g', '2025-05-03', '/gastrolink/app/img/recetas/brownie.jpg'),
    (7, 'Sopa de Verduras', 'Vegetariano', 'Una sopa nutritiva y saludable.', 'Zanahoria, calabacín, patata, cebolla, caldo de verduras.', '1. Trocear las verduras. 2. Cocinar en caldo hasta ablandar. 3. Triturar y servir.', 35, 4, 'Fácil', '120', '3g', '20g', '4g', '2025-05-04', '/gastrolink/app/img/recetas/sopa_verduras.jpg'),
    (4, 'Pizza Margarita', 'Plato Principal', 'La clásica pizza italiana.', 'Masa de pizza, tomate, queso mozzarella, albahaca.', '1. Extender la masa. 2. Añadir tomate y queso. 3. Hornear a 200ºC durante 15 minutos.', 30, 2, 'Fácil', '550', '20g', '60g', '25g', '2025-05-05', '/gastrolink/app/img/recetas/pizza_margarita.jpeg'),
    (5, 'Tarta de Queso', 'Postre', 'Un postre cremoso y delicioso.', 'Queso crema, huevos, azúcar, galletas, mantequilla.', '1. Triturar las galletas y mezclar con mantequilla. 2. Hornear la base. 3. Preparar la mezcla de queso y hornear.', 50, 8, 'Media', '420', '8g', '35g', '28g', '2025-05-06', '/gastrolink/app/img/recetas/tarta_queso.jpg'),
    (6, 'Gazpacho Andaluz', 'Vegetariano', 'Una sopa fría refrescante.', 'Tomate, pepino, pimiento, ajo, aceite, vinagre.', '1. Trocear los ingredientes. 2. Triturar hasta obtener una mezcla homogénea. 3. Refrigerar y servir frío.', 15, 4, 'Fácil', '95', '2g', '12g', '3g', '2025-05-07', '/gastrolink/app/img/recetas/gazpacho.jpg'),
    (7, 'Risotto de Setas', 'Plato Principal', 'Un plato cremoso y sabroso.', 'Arroz arborio, setas, cebolla, caldo de verduras, parmesano.', '1. Sofreír las setas. 2. Añadir el arroz y el caldo poco a poco. 3. Remover hasta que el arroz esté cremoso.', 45, 4, 'Media', '470', '14g', '50g', '18g', '2025-05-08', '/gastrolink/app/img/recetas/risotto_setas.jpg'),
    (4, 'Bacalao a la Vizcaína', 'Plato Principal', 'Un plato tradicional del norte.', 'Bacalao, cebolla, pimientos, tomate, ajo.', '1. Desalar el bacalao. 2. Sofreír las verduras. 3. Cocinar el bacalao en la salsa.', 50, 4, 'Media', '360', '30g', '10g', '15g', '2025-05-09', '/gastrolink/app/img/recetas/bacalao_vizcaina.jpg'),
    (5, 'Croquetas de Jamón', 'Entrante', 'Un aperitivo clásico y cremoso.', 'Jamón, harina, leche, mantequilla, huevo, pan rallado.', '1. Preparar la bechamel con jamón. 2. Enfriar y dar forma. 3. Empanar y freír.', 60, 6, 'Media', '300', '10g', '25g', '18g', '2025-05-10', '/gastrolink/app/img/recetas/croquetas.jpg'),
    (6, 'Flan de Vainilla', 'Postre', 'Un postre suave y dulce.', 'Leche, huevos, azúcar, esencia de vainilla.', '1. Mezclar los ingredientes. 2. Verter en moldes y hornear al baño maría.', 50, 4, 'Fácil', '270', '6g', '35g', '10g', '2025-05-11', '/gastrolink/app/img/recetas/flan.jpg'),
    (7, 'Hamburguesa Vegana', 'Vegetariano', 'Una opción deliciosa y saludable.', 'Pan de hamburguesa, hamburguesa vegetal, lechuga, tomate, cebolla.', '1. Cocinar la hamburguesa. 2. Montar con los ingredientes. 3. Servir con salsa vegana.', 25, 2, 'Fácil', '380', '12g', '30g', '16g', '2025-05-12', '/gastrolink/app/img/recetas/hamburguesa_vegana.jpg'),
    (4, 'Arroz con Pollo', 'Plato Principal', 'Un plato tradicional y completo.', 'Arroz, pollo, pimientos, guisantes, azafrán.', '1. Sofreír el pollo y las verduras. 2. Añadir el arroz y el caldo. 3. Cocinar hasta que el arroz esté en su punto.', 45, 4, 'Media', '450', '28g', '40g', '18g', '2025-05-13', '/gastrolink/app/img/recetas/arroz_con_pollo.jpg'),
    (5, 'Mousse de Chocolate', 'Postre', 'Un postre esponjoso y cremoso.', 'Chocolate, nata, huevos, azúcar.', '1. Derretir el chocolate. 2. Mezclar con nata montada y huevo. 3. Refrigerar hasta que cuaje.', 25, 4, 'Fácil', '410', '7g', '38g', '22g', '2025-05-14', '/gastrolink/app/img/recetas/mousse.jpg'),
    (6, 'Ensalada de Quinoa', 'Vegetariano', 'Una opción ligera y nutritiva.', 'Quinoa, tomate, pepino, cebolla, aceite de oliva, limón.', '1. Cocinar la quinoa. 2. Mezclar con los ingredientes y aliñar.', 20, 2, 'Fácil', '220', '8g', '30g', '6g', '2025-05-15', '/gastrolink/app/img/recetas/ensalada_quinoa.jpeg'),
    (7, 'Empanadas de Carne', 'Plato Principal', 'Un clásico argentino.', 'Masa de empanada, carne picada, cebolla, huevo duro, aceitunas.', '1. Preparar el relleno. 2. Rellenar y cerrar las empanadas. 3. Hornear hasta dorar.', 50, 6, 'Media', '530', '22g', '40g', '28g', '2025-05-16', '/gastrolink/app/img/recetas/empanadas.jpg');";
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
$favorito = "CREATE TABLE IF NOT EXISTS favorito (
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
mysqli_query($connection, $favorito) or die('ERROR: No se puede crear la tabla favorito: ' . mysqli_error($connection));

$favorito_receta = "CREATE TABLE favorito_receta (
    id_favorito INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_receta INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_receta) REFERENCES receta(id_receta)
);";
mysqli_query($connection, $favorito_receta) or die('ERROR: No se puede crear la tabla favorito: ' . mysqli_error($connection));

$insertar_fav_receta = "INSERT INTO favorito_receta (id_usuario, id_receta) VALUES
    (1, 3),  -- Daniel Farias favorito Brownie
    (2, 5),  -- Laura García favorito Pizza Margarita
    (3, 7),  -- Antonio Martínez favorito Gazpacho
    (4, 2),  -- Daniel González favorito Paella
    (5, 6),  -- Candela Martínez favorito Tarta de Queso
    (6, 8),  -- María Fernández favorito Risotto
    (7, 10), -- Pedro Sánchez favorito Croquetas
    (11, 1), -- Alejandro Mendoza favorito Ensalada
    (12, 4), -- Isabel Ortega favorito Sopa
    (13, 9), -- Ricardo Herrera favorito Bacalao
    (14, 12),-- Patricia Vega favorito Hamburguesa Vegana
    (15, 14),-- Fernando Guzmán favorito Mousse
    (16, 11),-- Beatriz Ríos favorito Flan
    (17, 13),-- Hugo Silva favorito Arroz con Pollo
    (18, 15),-- Adriana Castro favorito Ensalada Quinoa
    (19, 16),-- Raúl Navarro favorito Empanadas
    (20, 3), -- Carmen Paredes favorito Brownie
    (26, 5), -- Mónica Ríos favorito Pizza
    (27, 7), -- Sergio Silva favorito Gazpacho
    (28, 2), -- Diana Castro favorito Paella
    (29, 6), -- Arturo Navarro favorito Tarta
    (31, 8), -- Emilio Mendoza favorito Risotto
    (32, 10),-- Rocío Ortega favorito Croquetas
    (33, 1); -- Felipe Herrera favorito Ensalada";
mysqli_query($connection, $insertar_fav_receta) or die('ERROR: No se pueden insertar las recetas: ' . mysqli_error($connection));


$favorito_cocinero = "CREATE TABLE favorito_cocinero (
    id_favorito INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_cocinero INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_cocinero) REFERENCES cocinero(id_cocinero)
);";
mysqli_query($connection, $favorito_cocinero) or die('ERROR: No se puede crear la tabla favorito: ' . mysqli_error($connection));

$insertar_fav_cocinero = "INSERT INTO favorito_cocinero (id_usuario, id_cocinero) VALUES
    (1, 4),   -- Daniel Farias sigue a Daniel González
    (2, 5),   -- Laura García sigue a Candela Martínez
    (3, 6),   -- Antonio Martínez sigue a María Fernández
    (11, 7),  -- Alejandro Mendoza sigue a Pedro Sánchez
    (12, 16), -- Isabel Ortega sigue a Beatriz Ríos
    (13, 17), -- Ricardo Herrera sigue a Hugo Silva
    (14, 18), -- Patricia Vega sigue a Adriana Castro
    (15, 19), -- Fernando Guzmán sigue a Raúl Navarro
    (26, 20), -- Mónica Ríos sigue a Carmen Paredes
    (27, 28), -- Sergio Silva sigue a Diana Castro
    (31, 29), -- Emilio Mendoza sigue a Arturo Navarro
    (4, 32),  -- Daniel González sigue a Rocío Ortega
    (5, 33),  -- Candela Martínez sigue a Felipe Herrera
    (6, 4),   -- María Fernández sigue a Daniel González
    (7, 5),   -- Pedro Sánchez sigue a Candela Martínez
    (16, 6),  -- Beatriz Ríos sigue a María Fernández
    (17, 7),  -- Hugo Silva sigue a Pedro Sánchez
    (18, 16), -- Adriana Castro sigue a Beatriz Ríos
    (19, 17), -- Raúl Navarro sigue a Hugo Silva
    (20, 18); -- Carmen Paredes sigue a Adriana Castro";
mysqli_query($connection, $insertar_fav_cocinero) or die('ERROR: No se pueden insertar las recetas: ' . mysqli_error($connection));

$favorito_restaurante = "CREATE TABLE favorito_restaurante (
    id_favorito INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_restaurante INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_restaurante) REFERENCES restaurante(id_restaurante)
);";
mysqli_query($connection, $favorito_restaurante) or die('ERROR: No se puede crear la tabla favorito: ' . mysqli_error($connection));


$insertar_fav_restaurante = "INSERT INTO favorito_restaurante (id_usuario, id_restaurante) VALUES
    (1, 8),   -- Daniel Farias favorito Madrid Gourmet
    (2, 9),   -- Laura García favorito Calle del Hambre
    (3, 10),  -- Antonio Martínez favorito Tierra Noble
    (4, 21),  -- Daniel González favorito Asador Real
    (5, 22),  -- Candela Martínez favorito Sushi Madrid
    (6, 23),  -- María Fernández favorito Trattoria Málaga
    (7, 24),  -- Pedro Sánchez favorito La Gaviota
    (11, 25), -- Alejandro Mendoza favorito Brasserie Barceloca
    (12, 30), -- Isabel Ortega favorito Café Bilbao
    (13, 34), -- Ricardo Herrera favorito Restaurante Autor
    (14, 35), -- Patricia Vega favorito Taberna Da Galera
    (15, 8),  -- Fernando Guzmán favorito Madrid Gourmet
    (16, 9),  -- Beatriz Ríos favorito Calle del Hambre
    (17, 10), -- Hugo Silva favorito Tierra Noble
    (18, 21), -- Adriana Castro favorito Asador Real
    (19, 22), -- Raúl Navarro favorito Sushi Madrid
    (20, 23), -- Carmen Paredes favorito Trattoria Málaga
    (26, 24), -- Mónica Ríos favorito La Gaviota
    (27, 25), -- Sergio Silva favorito Brasserie Barceloca
    (28, 30), -- Diana Castro favorito Café Bilbao
    (29, 34), -- Arturo Navarro favorito Restaurante Autor
    (31, 35), -- Emilio Mendoza favorito Taberna Da Galera
    (32, 8),  -- Rocío Ortega favorito Madrid Gourmet
    (33, 9);  -- Felipe Herrera favorito Calle del Hambre";
mysqli_query($connection, $insertar_fav_restaurante) or die('ERROR: No se pueden insertar las recetas: ' . mysqli_error($connection));
