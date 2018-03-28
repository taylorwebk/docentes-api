CREATE DATABASE docentedb DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
use docentedb;
create table docente(
  id integer not null auto_increment,
  nombres varchar(127),
  apellidos varchar(127),
  grado varchar(63),
  urlimg varchar(255),
  primary key(id)
);
create table auxiliar(
  id integer not null auto_increment,
  nombres varchar(127),
  apellidos varchar(127),
  urlimg varchar(255),
  primary key(id)
);
create table materia(
  id integer not null auto_increment,
  sigla varchar(7),
  nombre varchar(255),
  primary key(id)
);
create table ddicta(
  id integer not null auto_increment,
  docente_id integer not null,
  materia_id integer not null,
  estado boolean,
  primary key(id),
  foreign key(docente_id)
  references docente(id)
  on delete cascade,
  foreign key(materia_id)
  references materia(id)
  on delete cascade
);
create table adicta(
  id integer not null auto_increment,
  auxiliar_id integer not null,
  materia_id integer not null,
  estado boolean,
  primary key(id),
  foreign key(auxiliar_id)
  references auxiliar(id)
  on delete cascade,
  foreign key(materia_id)
  references materia(id)
  on delete cascade
);
create table comentario(
  id integer not null auto_increment,
  cont text,
  comentario_id integer default null,
  primary key(id)
);
create table reaccion(
  id integer not null auto_increment,
  nombre varchar(63),
  urlimg varchar(255),
  primary key(id)
);
create table administrador(
  id integer not null auto_increment,
  nombres varchar(127),
  apellidos varchar(127),
  correo varchar(128),
  passw varchar(255),
  primary key(id)
);
create table comentario_ddicta(
  comentario_id integer not null,
  ddicta_id integer not null,
  val tinyint,
  fecha date,
  hora time,
  primary key(comentario_id, ddicta_id),
  foreign key(comentario_id)
  references comentario(id)
  on delete cascade,
  foreign key(ddicta_id)
  references ddicta(id)
  on delete cascade
);
create table adicta_comentario(
  comentario_id integer not null,
  adicta_id integer not null,
  val tinyint,
  fecha date,
  hora time,
  primary key(comentario_id, adicta_id),
  foreign key(comentario_id)
  references comentario(id)
  on delete cascade,
  foreign key(adicta_id)
  references adicta(id)
  on delete cascade
);
create table comentario_reaccion(
  comentario_id integer not null,
  reaccion_id integer not null,
  primary key(comentario_id, reaccion_id),
  foreign key(comentario_id)
  references comentario(id)
  on delete cascade,
  foreign key(reaccion_id)
  references reaccion(id)
  on delete cascade
);
