create database trampaki;
use trampaki;
create table endereco(
    cd_endereco int Primary Key auto_increment,
    cd_numeroResidencia varchar(8),
    cd_cep varchar(15),
    nm_cidade varchar(30),
    sg_estado char(2),
    cd_longitude float,
    cd_latitude float
);

create table login(
    cd_login int Primary Key auto_increment,
    ds_senha varchar(15),
    ds_login varchar(15)
);

create table usuario(
    cd_usuario int Primary Key auto_increment,
    cd_login int not null,
    cd_endereco int not null,
    nm_usuario varchar(80),
    ds_email varchar(30),
    ds_telefone varchar(20)
);

create table prestador(
    cd_prestador int Primary Key auto_increment,
    cd_usuario int not null,
    ds_perfilProfissional varchar(400),
    qt_areaAlcance double
);

create table anunciante(
    cd_anunciante int Primary Key auto_increment,
    cd_usuario int not null
);

create table categoria(
    cd_categoria int Primary Key auto_increment,
    nm_categoria varchar(80),
    ds_categoria varchar(200)
);

create table anuncio(
    cd_anuncio int Primary Key auto_increment,
    cd_anunciante int not null,
    nm_titulo varchar(80),
    ds_anuncio varchar(600),
    qt_areaAlcance double,
    cd_status enum('0','1','2','3')
--  0 - ABERTO
--  1 - ENCERRADO
--  2 - CANCELADO
--  3 - SUSPENSO
);

create table categoriaAnuncio(
    cd_categoria int not null,
    cd_anuncio int not null
);

create table categoriaPrestador(
    cd_categoria int not null,
    cd_prestador int not null
);

create table conexao(
    cd_conexao int Primary Key auto_increment,
    cd_anuncio int not null,
    cd_prestador int not null,
    cd_status enum('0','1')
--  0 - FALSO
--  1 - VERDADE
);
create table avaliacao(
    cd_prestador int not null,
    cd_conexao int not null,
    qt_avaliacao double

);
--  ============================================================================
ALTER TABLE `usuario` ADD CONSTRAINT `fk_usuario_login` FOREIGN KEY ( `cd_login` ) REFERENCES `login` ( `cd_login` ) ;

ALTER TABLE `usuario` ADD CONSTRAINT `fk_usuario_endereco` FOREIGN KEY ( `cd_endereco` ) REFERENCES `endereco` ( `cd_endereco` ) ;

ALTER TABLE `prestador` ADD CONSTRAINT `fk_prestador_usuario` FOREIGN KEY ( `cd_usuario` ) REFERENCES `usuario` ( `cd_usuario` ) ;

ALTER TABLE `anunciante` ADD CONSTRAINT `fk_anunciante_usuario` FOREIGN KEY ( `cd_usuario` ) REFERENCES `usuario` ( `cd_usuario` ) ;

ALTER TABLE `anuncio` ADD CONSTRAINT `fk_anuncio_anunciante` FOREIGN KEY ( `cd_anunciante` ) REFERENCES `anunciante` ( `cd_anunciante` ) ;

ALTER TABLE `categoriaAnuncio` ADD CONSTRAINT `pk_categoria_anuncio` PRIMARY KEY ( `cd_categoria`, `cd_anuncio` );

ALTER TABLE `categoriaAnuncio` ADD CONSTRAINT `fk_categoriaAnucio_anuncio` FOREIGN KEY ( `cd_anuncio` ) REFERENCES `anuncio` ( `cd_anuncio` ) ;

ALTER TABLE `categoriaPrestador` ADD CONSTRAINT `pk_categoria_prestador` PRIMARY KEY ( `cd_categoria`, `cd_prestador` );

ALTER TABLE `categoriaPrestador` ADD CONSTRAINT `fk_categoriaPrestador_prestador` FOREIGN KEY ( `cd_prestador` ) REFERENCES `prestador` ( `cd_prestador` ) ;

ALTER TABLE `conexao` ADD CONSTRAINT `fk_conexao_prestador` FOREIGN KEY ( `cd_prestador` ) REFERENCES `prestador` ( `cd_prestador` ) ;

ALTER TABLE `conexao` ADD CONSTRAINT `fk_conexao_anuncio` FOREIGN KEY ( `cd_anuncio` ) REFERENCES `anuncio` ( `cd_anuncio` ) ;

ALTER TABLE `avaliacao` ADD CONSTRAINT `fk_avaliacao_prestador` FOREIGN KEY ( `cd_prestador` ) REFERENCES `prestador` ( `cd_prestador` ) ;

ALTER TABLE `avaliacao` ADD CONSTRAINT `fk_avaliacao_conexao` FOREIGN KEY ( `cd_conexao` ) REFERENCES `conexao` ( `cd_conexao` ) ;
