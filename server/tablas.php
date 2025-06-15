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
    token VARCHAR(255),
    tipo_usuario ENUM('restaurante', 'cocinero', 'camarero') NOT NULL
);";
mysqli_query($connection, $usuario) or die('ERROR: No se puede crear la tabla usuario: ' . mysqli_error($connection));

$insertar_usuario = "INSERT INTO usuario (id_usuario, nombre, correo, clave, img_usuario, tipo_usuario) VALUES
    (1, 'Daniel Farias Morales', 'fariasd99@gmail.com', '" . hashPassword('Daniel123.') . "', '/gastrolink/app/img/usuarios/1.jpg', 'camarero'),
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
    tipo_restaurante ENUM('Mediterráneo', 'Carnes', 'Gourmet', 'Vegetariano', 'Asiático', 'Italiano', 'Mariscos', 'Tapas', 'Francés', 'Cafés'),
    descripcion TEXT,
    direccion VARCHAR(255),
    web VARCHAR(100),
    telefono VARCHAR(20),
    rango_precio VARCHAR(50),
    ubicacion VARCHAR(255),
    historial TEXT,
    FOREIGN KEY (id_restaurante) REFERENCES usuario(id_usuario)
);";
mysqli_query($connection, $restaurante) or die('ERROR: No se puede crear la tabla restaurante: ' . mysqli_error($connection));

$insertar_restaurante = "INSERT INTO restaurante (id_restaurante, tipo_restaurante, descripcion, direccion, web, telefono, rango_precio, ubicacion, historial) VALUES
    (8, 'Mediterráneo', 'Restaurante gourmet especializado en cocina mediterránea moderna.', 'Calle Mayor 15, Madrid', 'www.mediterraneomadrid.com', '+34 911 111 111', '25-50', 'https://maps.google.com/?q=Calle+Mayor+15,+Madrid', 'Fundado en 2008, este restaurante surgió del sueño de dos chefs apasionados por la dieta mediterránea. A lo largo de los años, ha evolucionado en estilo y técnicas, manteniéndose fiel al uso de productos frescos de la región. Galardonado con varios premios gastronómicos nacionales, es un referente por su carta saludable, sus vinos ecológicos y su compromiso con la sostenibilidad. Ha participado en eventos internacionales como Madrid Fusión y es elegido frecuentemente para cenas institucionales y culturales.'),
    (9, 'Carnes', 'Steakhouse premium con carnes maduradas y selección internacional.', 'Avenida Diagonal 123, Barcelona', 'www.steakhousebcn.com', '+34 922 222 222', '30-60', 'https://maps.google.com/?q=Avenida+Diagonal+123,+Barcelona', 'Abierto en 2003, este steakhouse de renombre se ha especializado en técnicas de maduración en seco (dry aged) y en importar cortes selectos de Argentina, Estados Unidos y Japón. Su bodega alberga más de 300 vinos, y el local ha sido renovado recientemente para ofrecer una experiencia gastronómica moderna y elegante. Es habitual encontrarlo recomendado en las guías de viaje internacionales, y muchos chefs famosos han colaborado en su cocina como invitados especiales.'),
    (10, 'Vegetariano', 'Restaurante vegano con propuestas innovadoras y sostenibles.', 'Calle Valencia 45, Valencia', 'www.verdevalencia.com', '+34 933 333 333', '20-35', 'https://maps.google.com/?q=Calle+Valencia+45,+Valencia', 'Nacido en 2015 como una iniciativa de una cooperativa local, este restaurante se convirtió rápidamente en un punto de encuentro para los amantes de la cocina ética, vegetal y de proximidad. Además de su carta 100% vegana, ofrece talleres culinarios, colaboraciones con granjas locales y eventos temáticos de temporada. Ha aparecido en reportajes de TV y revistas especializadas en sostenibilidad, y colabora regularmente con ONGs de protección animal y medio ambiente.'),
    (21, 'Carnes', 'Asador tradicional con horno de leña y productos locales.', 'Plaza del Sol 8, Sevilla', 'www.asadorsevilla.com', '+34 944 444 444', '25-45', 'https://maps.google.com/?q=Plaza+del+Sol+8,+Sevilla', 'Este asador abrió sus puertas en 1998 con la intención de recuperar la tradición culinaria andaluza más auténtica. Con un horno de leña centenario traído de Navarra, prepara carnes a fuego lento con técnicas artesanales. El local conserva la estética de una antigua casa sevillana y ha sido reformado con materiales reciclados. Participa en ferias regionales y es frecuentado por políticos y celebridades locales.'),
    (22, 'Asiático', 'Restaurante japonés con sushi bar y cocina teppanyaki.', 'Gran Vía 67, Madrid', 'www.sushimadrid.com', '+34 955 555 555', '30-55', 'https://maps.google.com/?q=Gran+Vía+67,+Madrid', 'Inaugurado en 2010 por el chef japonés Kenji Nakamura, este restaurante fusiona tradición nipona con innovación europea. Su barra de sushi es atendida en vivo y ofrece cortes de pescado importado directamente desde Tsukiji. Además, cuenta con estaciones teppanyaki para una experiencia interactiva. Ha ganado premios por su decoración minimalista y por su menú de degustación omakase.'),
    (23, 'Italiano', 'Trattoria auténtica con pasta fresca y vinos italianos.', 'Calle Málaga 12, Málaga', 'www.trattoriamalaga.com', '+34 966 666 666', '18-30', 'https://maps.google.com/?q=Calle+Málaga+12,+Málaga', 'Inspirado en las trattorias familiares del norte de Italia, este restaurante abrió en 2012 y es famoso por su pasta fresca elaborada a diario. El chef, originario de Bolonia, trajo consigo recetas heredadas por generaciones. Su carta cambia con las estaciones, y su selección de vinos incluye etiquetas poco conocidas de bodegas artesanales italianas. El local ha sido destacado por su ambiente acogedor y su autenticidad.'),
    (24, 'Mariscos', 'Restaurante de mariscos con pescado fresco diario.', 'Paseo Marítimo 3, Santander', 'www.marisqueriasantander.com', '+34 977 777 777', '30-50', 'https://maps.google.com/?q=Paseo+Marítimo+3,+Santander', 'Con vistas privilegiadas al mar Cantábrico, este restaurante lleva más de 25 años ofreciendo pescados y mariscos del día. Sus proveedores son pequeñas cofradías locales, y muchos platos se cocinan en parrilla de carbón o a la sal. Ha sido escenario de grabaciones gastronómicas y es frecuentado por chefs en búsqueda de inspiración marina.'),
    (25, 'Francés', 'Brasserie francesa con ambiente parisino y cocina clásica.', 'Calle Rosellón 89, Barcelona', 'www.brasseriebarcelona.com', '+34 988 888 888', '25-45', 'https://maps.google.com/?q=Calle+Rosellón+89,+Barcelona', 'Fundado por un matrimonio franco-español en 2006, este restaurante busca trasladar el alma de París a Barcelona. Sus especialidades incluyen confit de pato, ratatouille y crepas flambeadas. La ambientación incluye mobiliario importado de Lyon y Toulouse. Ha sido mencionado en revistas francesas como uno de los mejores lugares para comer auténtica cocina gala fuera de Francia.'),
    (30, 'Cafés', 'Cafetería-restaurante con horario extendido y cocina internacional.', 'Plaza Central 5, Bilbao', 'www.cafebilbao.com', '+34 999 999 999', '8-20', 'https://maps.google.com/?q=Plaza+Central+5,+Bilbao', 'Con un concepto abierto y flexible, esta cafetería moderna sirve desde desayunos internacionales hasta cenas ligeras. Fundada en 2018 por un grupo de baristas premiados, cuenta con café de especialidad, bollería artesanal y menús del día variados. Además, se ha convertido en un espacio cultural con exposiciones temporales y conciertos acústicos.'),
    (34, 'Gourmet', 'Restaurante de autor con menú degustación y cocina creativa.', 'Calle Michelín 1, San Sebastián', 'www.restauranteautor.com', '+34 910 101 010', '80-120', 'https://maps.google.com/?q=Calle+Michelín+1,+San+Sebastián', 'Creado por el chef Esteban Roca, discípulo de Ferran Adrià, este restaurante está orientado exclusivamente al menú degustación. Cada plato es una obra de arte inspirada en elementos de la naturaleza, ciencia y diseño. Solo se atiende con reserva previa y hay una lista de espera de hasta 3 meses. Ha recibido dos estrellas Michelín y es considerado una experiencia multisensorial única en España.'),
    (35, 'Tapas', 'Taberna moderna con tapas reinventadas y ambiente contemporáneo.', 'Calle Nueva 23, Zaragoza', 'www.tabernamoderna.com', '+34 920 202 020', '15-25', 'https://maps.google.com/?q=Calle+Nueva+23,+Zaragoza', 'Una taberna que rinde homenaje a las tapas clásicas españolas con un giro moderno. Abierta en 2016, trabaja con ingredientes de kilómetro cero y técnicas innovadoras como cocina al vacío o esferificación. Es muy popular entre jóvenes y turistas por su ambiente informal y creativo. Ha ganado varios premios en concursos de tapas a nivel nacional.');";
mysqli_query($connection, $insertar_restaurante) or die('ERROR: No se pueden insertar los restaurantes: ' . mysqli_error($connection));

/*---------------------------------------------------------------
HORARIO - RESTAURANTE
---------------------------------------------------------------*/
$horario_restaurante = "CREATE TABLE IF NOT EXISTS horario_restaurante (
    id_horario INT AUTO_INCREMENT PRIMARY KEY,
    id_restaurante INT NOT NULL,
    dia_semana ENUM('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo') NOT NULL,
    hora_apertura TIME NOT NULL,
    hora_cierre TIME NOT NULL,
    FOREIGN KEY (id_restaurante) REFERENCES restaurante(id_restaurante)
);";
mysqli_query($connection, $horario_restaurante) or die('ERROR: No se puede crear la tabla horario_restaurante: ' . mysqli_error($connection));

$insertar_horarios = "INSERT INTO horario_restaurante (id_restaurante, dia_semana, hora_apertura, hora_cierre) VALUES
    (8, 'Martes', '13:00:00', '23:30:00'),
    (8, 'Miércoles', '13:00:00', '23:30:00'),
    (8, 'Jueves', '13:00:00', '23:30:00'),
    (8, 'Viernes', '13:00:00', '00:00:00'),
    (8, 'Sábado', '13:00:00', '00:00:00'),
    (8, 'Domingo', '13:00:00', '16:00:00'),
    (9, 'Lunes', '12:30:00', '23:00:00'),
    (9, 'Martes', '12:30:00', '23:00:00'),
    (9, 'Miércoles', '12:30:00', '23:00:00'),
    (9, 'Jueves', '12:30:00', '23:00:00'),
    (9, 'Viernes', '12:30:00', '00:00:00'),
    (9, 'Sábado', '13:00:00', '00:00:00'),
    (10, 'Martes', '11:00:00', '22:00:00'),
    (10, 'Miércoles', '11:00:00', '22:00:00'),
    (10, 'Jueves', '11:00:00', '22:00:00'),
    (10, 'Viernes', '11:00:00', '23:00:00'),
    (10, 'Sábado', '11:30:00', '23:00:00'),
    (10, 'Domingo', '11:30:00', '16:30:00'),
    (21, 'Martes', '13:00:00', '23:00:00'),
    (21, 'Miércoles', '13:00:00', '23:00:00'),
    (21, 'Jueves', '13:00:00', '23:00:00'),
    (21, 'Viernes', '13:00:00', '00:00:00'),
    (21, 'Sábado', '13:00:00', '00:00:00'),
    (22, 'Lunes', '13:00:00', '23:00:00'),
    (22, 'Martes', '13:00:00', '23:00:00'),
    (22, 'Miércoles', '13:00:00', '23:00:00'),
    (22, 'Jueves', '13:00:00', '23:00:00'),
    (22, 'Viernes', '13:00:00', '00:00:00'),
    (22, 'Sábado', '13:00:00', '00:00:00'),
    (23, 'Martes', '12:30:00', '23:00:00'),
    (23, 'Miércoles', '12:30:00', '23:00:00'),
    (23, 'Jueves', '12:30:00', '23:00:00'),
    (23, 'Viernes', '13:00:00', '00:00:00'),
    (23, 'Sábado', '13:00:00', '00:00:00'),
    (24, 'Martes', '12:00:00', '16:30:00'),
    (24, 'Miércoles', '12:00:00', '16:30:00'),
    (24, 'Jueves', '12:00:00', '16:30:00'),
    (24, 'Viernes', '12:00:00', '17:00:00'),
    (24, 'Sábado', '12:00:00', '17:00:00'),
    (24, 'Domingo', '12:00:00', '17:00:00'),
    (25, 'Martes', '13:00:00', '23:00:00'),
    (25, 'Miércoles', '13:00:00', '23:00:00'),
    (25, 'Jueves', '13:00:00', '23:00:00'),
    (25, 'Viernes', '13:00:00', '00:00:00'),
    (25, 'Sábado', '13:00:00', '00:00:00'),
    (30, 'Lunes', '08:00:00', '22:00:00'),
    (30, 'Martes', '08:00:00', '22:00:00'),
    (30, 'Miércoles', '08:00:00', '22:00:00'),
    (30, 'Jueves', '08:00:00', '22:00:00'),
    (30, 'Viernes', '08:00:00', '23:00:00'),
    (30, 'Sábado', '09:00:00', '23:00:00'),
    (30, 'Domingo', '09:00:00', '21:00:00'),
    (34, 'Miércoles', '20:00:00', '23:30:00'),
    (34, 'Jueves', '20:00:00', '23:30:00'),
    (34, 'Viernes', '20:00:00', '00:00:00'),
    (34, 'Sábado', '13:30:00', '15:30:00'),
    (34, 'Domingo', '13:30:00', '15:30:00'),
    (35, 'Martes', '13:00:00', '23:30:00'),
    (35, 'Miércoles', '13:00:00', '23:30:00'),
    (35, 'Jueves', '13:00:00', '23:30:00'),
    (35, 'Viernes', '13:00:00', '00:00:00'),
    (35, 'Sábado', '13:00:00', '00:00:00');";
mysqli_query($connection, $insertar_horarios) or die('ERROR: No se pueden insertar los horarios: ' . mysqli_error($connection));

/*---------------------------------------------------------------
IMAGENES - RESTAURANTE
---------------------------------------------------------------*/
$imagen_restaurante = "CREATE TABLE IF NOT EXISTS imagen_restaurante (
    id_imagen INT AUTO_INCREMENT PRIMARY KEY,
    id_restaurante INT NOT NULL,
    url_imagen VARCHAR(255) NOT NULL,
    alt VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_restaurante) REFERENCES restaurante(id_restaurante)
);";
mysqli_query($connection, $imagen_restaurante) or die('ERROR: No se puede crear la tabla imagen_restaurante: ' . mysqli_error($connection));

$insertar_imagenes = "INSERT INTO imagen_restaurante (id_restaurante, url_imagen, alt) VALUES
    (8, '/gastrolink/app/img/restaurantes/8.avif', 'Terraza'),
    (8, '/gastrolink/app/img/restaurantes/8.1.jpeg', 'Comida'),
    (8, '/gastrolink/app/img/restaurantes/8.2.webp', 'Entrante'),
    (8, '/gastrolink/app/img/restaurantes/8.3.jpg', 'Restaurante interior'),
    (8, '/gastrolink/app/img/restaurantes/8.4.jpg', 'Restaurante interior'),
    (9, '/gastrolink/app/img/restaurantes/9.jpg', 'Carnes'),
    (9, '/gastrolink/app/img/restaurantes/9.2.jpg', 'Carnes'),
    (9, '/gastrolink/app/img/restaurantes/9.3.jpg', 'Carnes'),
    (9, '/gastrolink/app/img/restaurantes/9.4.webp', 'Carnes'),
    (9, '/gastrolink/app/img/restaurantes/9.5.jpg', 'Carnes'),
    (10, '/gastrolink/app/img/restaurantes/10.jpg', 'Vegetariano'),
    (10, '/gastrolink/app/img/restaurantes/10.1.jpeg', 'Vegetariano'),
    (10, '/gastrolink/app/img/restaurantes/10.2.jpg', 'Vegetariano'),
    (10, '/gastrolink/app/img/restaurantes/10.3.jpg', 'Vegetariano'),
    (10, '/gastrolink/app/img/restaurantes/10.5.jpg', 'Vegetariano'),
    (10, '/gastrolink/app/img/restaurantes/10.6.webp', 'Vegetariano'),
    (21, '/gastrolink/app/img/restaurantes/21.1.jpg', 'Asador'),
    (21, '/gastrolink/app/img/restaurantes/21.2.jpg', 'Asador'),
    (21, '/gastrolink/app/img/restaurantes/21.3.jpg', 'Asador'),
    (21, '/gastrolink/app/img/restaurantes/21.4.jpg', 'Asador'),
    (21, '/gastrolink/app/img/restaurantes/21.5.webp', 'Asador'),
    (21, '/gastrolink/app/img/restaurantes/21.6.jpg', 'Asador'),
    (22, '/gastrolink/app/img/restaurantes/22.jpg', 'Asiático'),
    (22, '/gastrolink/app/img/restaurantes/22.1.webp', 'Asiático'),
    (22, '/gastrolink/app/img/restaurantes/22.3.jpg', 'Asiático'),
    (22, '/gastrolink/app/img/restaurantes/22.4.jpg', 'Asiático'),
    (23, '/gastrolink/app/img/restaurantes/23.1.jpg', 'Italiano'),
    (23, '/gastrolink/app/img/restaurantes/23.2.jpg', 'Italiano'),
    (23, '/gastrolink/app/img/restaurantes/23.3.avif', 'Italiano'),
    (23, '/gastrolink/app/img/restaurantes/23.4.jpg', 'Italiano'),
    (23, '/gastrolink/app/img/restaurantes/23.5.jpg', 'Italiano'),
    (23, '/gastrolink/app/img/restaurantes/23.6.jpg', 'Italiano'),
    (24, '/gastrolink/app/img/restaurantes/24.1.jpeg', 'Mariscos'),
    (24, '/gastrolink/app/img/restaurantes/24.2.jpg', 'Mariscos'),
    (24, '/gastrolink/app/img/restaurantes/24.3.jpg', 'Mariscos'),
    (24, '/gastrolink/app/img/restaurantes/24.4.jpg', 'Mariscos'),
    (25, '/gastrolink/app/img/restaurantes/25.1.jpg', 'Francés'),
    (25, '/gastrolink/app/img/restaurantes/25.2.webp', 'Francés'),
    (25, '/gastrolink/app/img/restaurantes/25.3.webp', 'Francés'),
    (25, '/gastrolink/app/img/restaurantes/25.4.webp', 'Francés'),
    (25, '/gastrolink/app/img/restaurantes/25.5.jpg', 'Francés'),
    (30, '/gastrolink/app/img/restaurantes/30.1.jpg', 'Cafés'),
    (30, '/gastrolink/app/img/restaurantes/30.2.jpg', 'Cafés'),
    (30, '/gastrolink/app/img/restaurantes/30.3.jpg', 'Cafés'),
    (30, '/gastrolink/app/img/restaurantes/30.4.jpg', 'Cafés'),
    (30, '/gastrolink/app/img/restaurantes/30.5.jpg', 'Cafés'),
    (34, '/gastrolink/app/img/restaurantes/34.1.jpg', 'Gourmet'),
    (34, '/gastrolink/app/img/restaurantes/34.2.webp', 'Gourmet'),
    (34, '/gastrolink/app/img/restaurantes/34.3.jpg', 'Gourmet'),
    (34, '/gastrolink/app/img/restaurantes/34.4.webp', 'Gourmet'),
    (34, '/gastrolink/app/img/restaurantes/34.5.webp', 'Gourmet'),
    (34, '/gastrolink/app/img/restaurantes/34.6.jpg', 'Gourmet'),
    (35, '/gastrolink/app/img/restaurantes/35.1.jpg', 'Gourmet'),
    (35, '/gastrolink/app/img/restaurantes/35.2.jpg', 'Gourmet'),
    (35, '/gastrolink/app/img/restaurantes/35.3.jpg', 'Gourmet'),
    (35, '/gastrolink/app/img/restaurantes/35.4.jpg', 'Gourmet'),
    (35, '/gastrolink/app/img/restaurantes/35.5.jpg', 'Gourment');";
mysqli_query($connection, $insertar_imagenes) or die('ERROR: No se pueden insertar las imágenes: ' . mysqli_error($connection));

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
    (4, 'Ensalada Mediterránea', 'Entrante', 'Una ensalada fresca y ligera.', 'Lechuga, tomate, cebolla, aceitunas, queso feta, aceite de oliva, sal.', 'Lavar y cortar los ingredientes.\nMezclar y aliñar con aceite y sal.', 10, 2, 'Fácil', '150', '4g', '10g', '7g', '2025-05-01', '/gastrolink/app/img/recetas/ensalada-mediterranea.jpg'),
    (5, 'Paella Valenciana', 'Plato Principal', 'Un clásico plato español.', 'Arroz, pollo, conejo, judías verdes, garrofón, azafrán.', 'Sofreír la carne.\nAñadir el arroz y el caldo.\nCocinar a fuego medio.', 60, 4, 'Media', '480', '35g', '45g', '20g', '2025-05-02', '/gastrolink/app/img/recetas/paella.jpg'),
    (6, 'Brownie de Chocolate', 'Postre', 'Un postre esponjoso y delicioso.', 'Chocolate, mantequilla, azúcar, huevos, harina, nueces.', 'Derretir el chocolate y la mantequilla.\nMezclar con los demás ingredientes.\nHornear a 180ºC durante 25 minutos.', 40, 6, 'Fácil', '400', '5g', '50g', '25g', '2025-05-03', '/gastrolink/app/img/recetas/brownie.jpg'),
    (7, 'Sopa de Verduras', 'Vegetariano', 'Una sopa nutritiva y saludable.', 'Zanahoria, calabacín, patata, cebolla, caldo de verduras.', 'Trocear las verduras.\nCocinar en caldo hasta ablandar.\nTriturar y servir.', 35, 4, 'Fácil', '120', '3g', '20g', '4g', '2025-05-04', '/gastrolink/app/img/recetas/sopa_verduras.jpg'),
    (4, 'Pizza Margarita', 'Plato Principal', 'La clásica pizza italiana.', 'Masa de pizza, tomate, queso mozzarella, albahaca.', 'Extender la masa.\nAñadir tomate y queso.\nHornear a 200ºC durante 15 minutos.', 30, 2, 'Fácil', '550', '20g', '60g', '25g', '2025-05-05', '/gastrolink/app/img/recetas/pizza_margarita.jpeg'),
    (5, 'Tarta de Queso', 'Postre', 'Un postre cremoso y delicioso.', 'Queso crema, huevos, azúcar, galletas, mantequilla.', 'Triturar las galletas y mezclar con mantequilla.\nHornear la base.\nPreparar la mezcla de queso y hornear.', 50, 8, 'Media', '420', '8g', '35g', '28g', '2025-05-06', '/gastrolink/app/img/recetas/tarta_queso.jpg'),
    (6, 'Gazpacho Andaluz', 'Vegetariano', 'Una sopa fría refrescante.', 'Tomate, pepino, pimiento, ajo, aceite, vinagre.', 'Trocear los ingredientes.\nTriturar hasta obtener una mezcla homogénea.\nRefrigerar y servir frío.', 15, 4, 'Fácil', '95', '2g', '12g', '3g', '2025-05-07', '/gastrolink/app/img/recetas/gazpacho.jpg'),
    (7, 'Risotto de Setas', 'Plato Principal', 'Un plato cremoso y sabroso.', 'Arroz arborio, setas, cebolla, caldo de verduras, parmesano.', 'Sofreír las setas.\nAñadir el arroz y el caldo poco a poco.\nRemover hasta que el arroz esté cremoso.', 45, 4, 'Media', '470', '14g', '50g', '18g', '2025-05-08', '/gastrolink/app/img/recetas/risotto_setas.jpg'),
    (4, 'Bacalao a la Vizcaína', 'Plato Principal', 'Un plato tradicional del norte.', 'Bacalao, cebolla, pimientos, tomate, ajo.', 'Desalar el bacalao.\nSofreír las verduras.\nCocinar el bacalao en la salsa.', 50, 4, 'Media', '360', '30g', '10g', '15g', '2025-05-09', '/gastrolink/app/img/recetas/bacalao_vizcaina.jpg'),
    (5, 'Croquetas de Jamón', 'Entrante', 'Un aperitivo clásico y cremoso.', 'Jamón, harina, leche, mantequilla, huevo, pan rallado.', 'Preparar la bechamel con jamón.\nEnfriar y dar forma.\nEmpanar y freír.', 60, 6, 'Media', '300', '10g', '25g', '18g', '2025-05-10', '/gastrolink/app/img/recetas/croquetas.jpg'),
    (6, 'Flan de Vainilla', 'Postre', 'Un postre suave y dulce.', 'Leche, huevos, azúcar, esencia de vainilla.', 'Mezclar los ingredientes.\nVerter en moldes y hornear al baño maría.', 50, 4, 'Fácil', '270', '6g', '35g', '10g', '2025-05-11', '/gastrolink/app/img/recetas/flan.jpg'),
    (7, 'Hamburguesa Vegana', 'Vegetariano', 'Una opción deliciosa y saludable.', 'Pan de hamburguesa, hamburguesa vegetal, lechuga, tomate, cebolla.', 'Cocinar la hamburguesa.\nMontar con los ingredientes.\nServir con salsa vegana.', 25, 2, 'Fácil', '380', '12g', '30g', '16g', '2025-05-12', '/gastrolink/app/img/recetas/hamburguesa_vegana.jpg'),
    (4, 'Arroz con Pollo', 'Plato Principal', 'Un plato tradicional y completo.', 'Arroz, pollo, pimientos, guisantes, azafrán.', 'Sofreír el pollo y las verduras.\nAñadir el arroz y el caldo.\nCocinar hasta que el arroz esté en su punto.', 45, 4, 'Media', '450', '28g', '40g', '18g', '2025-05-13', '/gastrolink/app/img/recetas/arroz_con_pollo.jpg'),
    (5, 'Mousse de Chocolate', 'Postre', 'Un postre esponjoso y cremoso.', 'Chocolate, nata, huevos, azúcar.', 'Derretir el chocolate.\nMezclar con nata montada y huevo.\nRefrigerar hasta que cuaje.', 25, 4, 'Fácil', '410', '7g', '38g', '22g', '2025-05-14', '/gastrolink/app/img/recetas/mousse.jpg'),
    (6, 'Ensalada de Quinoa', 'Vegetariano', 'Una opción ligera y nutritiva.', 'Quinoa, tomate, pepino, cebolla, aceite de oliva, limón.', 'Cocinar la quinoa.\nMezclar con los ingredientes y aliñar.', 20, 2, 'Fácil', '220', '8g', '30g', '6g', '2025-05-15', '/gastrolink/app/img/recetas/ensalada_quinoa.jpeg'),
    (7, 'Empanadas de Carne', 'Plato Principal', 'Un clásico argentino.', 'Masa de empanada, carne picada, cebolla, huevo duro, aceitunas.', 'Preparar el relleno.\nRellenar y cerrar las empanadas.\nHornear hasta dorar.', 50, 6, 'Media', '530', '22g', '40g', '28g', '2025-05-16', '/gastrolink/app/img/recetas/empanadas.jpg');";
mysqli_query($connection, $insertar_receta) or die('ERROR: No se pueden insertar las recetas: ' . mysqli_error($connection));

/*---------------------------------------------------------------
FAVORITO
---------------------------------------------------------------*/
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
