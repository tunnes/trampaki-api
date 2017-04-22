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
    ds_login varchar(15),
    cd_token varchar(100)
);

create table usuario(
    cd_usuario int Primary Key auto_increment,
    cd_login int not null,
    cd_endereco int not null,
    nm_usuario varchar(80),
    ds_email varchar(30),
    ds_telefone varchar(20),
    cd_tipo enum('0','1','2') not null,
    cd_imagem int,
--  0 - ANUNCIANTE
--  1 - PRESTADOR
--  2 - HIBRIDO
    cd_tokenFcm varchar(255)
);

create table chat(
    cd_usuario_um int not null,
    cd_usuario_dois int not null
);

create table prestador(
    cd_usuario int Primary Key,
    ds_perfilProfissional varchar(400),
);

create table anunciante(
    cd_usuario int Primary Key,
    cd_codigoAnuncioSelecioado int
);

create table categoria(
    cd_categoria int Primary Key auto_increment,
    nm_categoria varchar(80),
    ds_categoria varchar(200)
);

create table anuncio(
    cd_anuncio int Primary Key auto_increment,
    cd_usuario int not null,
    nm_titulo varchar(80),
    ds_anuncio varchar(600),
    cd_status enum('0','1','2','3'),
    cd_imagem01 int,
    cd_imagem02 int,
    cd_imagem03 int
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
    cd_usuario int not null
);

create table conexao(
    cd_conexao int Primary Key auto_increment,
    cd_anuncio int not null,
    cd_usuario int not null,
    cd_status enum('0','1','2'),
--  0 - PENDENTE
--  1 - ACEITA
--  2 - RECUSADA
    cd_solicitante enum('0','1')
--  0 - ANUNCIANTE
--  1 - PRESTADOR    
);
create table avaliacao(
--  cd_usuario = cd_prestador.
    cd_avaliacao int Primary Key auto_increment,
    cd_usuario int not null,
    cd_conexao int not null,
    qt_nota_servico double,
    qt_nota_avaliacao double,
    ds_avaliacao varchar(600)
);
create table imagem(
    cd_imagem int Primary Key auto_increment,
    ds_imagem blob
);
--  =========================================================================================================================================
ALTER TABLE `usuario` ADD CONSTRAINT `fk_usuario_login` FOREIGN KEY ( `cd_login` ) REFERENCES `login` ( `cd_login` ) ;

ALTER TABLE `usuario` ADD CONSTRAINT `fk_usuario_endereco` FOREIGN KEY ( `cd_endereco` ) REFERENCES `endereco` ( `cd_endereco` ) ;

ALTER TABLE `chat` ADD CONSTRAINT `fk_usuario_um_chat` FOREIGN KEY ( `cd_usuario_um` ) REFERENCES `usuario` ( `cd_usuario` ) ;

ALTER TABLE `chat` ADD CONSTRAINT `fk_usuario_dois_chat` FOREIGN KEY ( `cd_usuario_dois` ) REFERENCES `usuario` ( `cd_usuario` ) ;

ALTER TABLE `chat` ADD CONSTRAINT `pk_chat` PRIMARY KEY (`cd_usuario_um`, `cd_usuario_dois`);

ALTER TABLE `prestador` ADD CONSTRAINT `fk_prestador_usuario` FOREIGN KEY ( `cd_usuario` ) REFERENCES `usuario` ( `cd_usuario` ) ;

ALTER TABLE `anunciante` ADD CONSTRAINT `fk_anunciante_usuario` FOREIGN KEY ( `cd_usuario` ) REFERENCES `usuario` ( `cd_usuario` ) ;

-- Alteração recente:
-- ALTER TABLE `usuario` ADD CONSTRAINT `fk_anunciante_anuncio_selecioado` FOREIGN KEY ( `cd_codigoAnuncioSelecioado` ) REFERENCES `anuncio` ( `cd_anuncio` ) ;

ALTER TABLE `anuncio` ADD CONSTRAINT `fk_anuncio_anunciante` FOREIGN KEY ( `cd_usuario` ) REFERENCES `anunciante` ( `cd_usuario` ) ;

ALTER TABLE `categoriaAnuncio` ADD CONSTRAINT `pk_categoria_anuncio` PRIMARY KEY ( `cd_categoria`, `cd_anuncio` );

ALTER TABLE `categoriaAnuncio` ADD CONSTRAINT `fk_categoriaAnucio_anuncio` FOREIGN KEY ( `cd_anuncio` ) REFERENCES `anuncio` ( `cd_anuncio` ) ;

ALTER TABLE `categoriaAnuncio` ADD CONSTRAINT `fk_categoriaAnucio_categoria` FOREIGN KEY ( `cd_categoria` ) REFERENCES `categoria` ( `cd_categoria` ) ;

ALTER TABLE `categoriaPrestador` ADD CONSTRAINT `pk_categoria_prestador` PRIMARY KEY ( `cd_categoria`, `cd_usuario` );

ALTER TABLE `categoriaPrestador` ADD CONSTRAINT `fk_categoriaPrestador_prestador` FOREIGN KEY ( `cd_usuario` ) REFERENCES `prestador` ( `cd_usuario` ) ;

ALTER TABLE `categoriaPrestador` ADD CONSTRAINT `fk_categoriaPrestador_categoria` FOREIGN KEY ( `cd_categoria` ) REFERENCES `categoria` ( `cd_categoria` ) ;

ALTER TABLE `conexao` ADD CONSTRAINT `fk_conexao_prestador` FOREIGN KEY ( `cd_usuario` ) REFERENCES `prestador` ( `cd_usuario` ) ;

ALTER TABLE `conexao` ADD CONSTRAINT `fk_conexao_anuncio` FOREIGN KEY ( `cd_anuncio` ) REFERENCES `anuncio` ( `cd_anuncio` ) ;

ALTER TABLE `avaliacao` ADD CONSTRAINT `fk_avaliacao_prestador` FOREIGN KEY ( `cd_usuario` ) REFERENCES `prestador` ( `cd_usuario` ) ;

ALTER TABLE `avaliacao` ADD CONSTRAINT `fk_avaliacao_conexao` FOREIGN KEY ( `cd_conexao` ) REFERENCES `conexao` ( `cd_conexao` ) ;

--  =========================================================================================================================================

ALTER TABLE `usuario` ADD CONSTRAINT `fk_usuario_imagem` FOREIGN KEY ( `cd_imagem` ) REFERENCES `imagem` ( `cd_imagem` ) ;

ALTER TABLE `anuncio` ADD CONSTRAINT `fk_anuncio_imagem01` FOREIGN KEY ( `cd_imagem01` ) REFERENCES `imagem` ( `cd_imagem` ) ;

ALTER TABLE `anuncio` ADD CONSTRAINT `fk_anuncio_imagem02` FOREIGN KEY ( `cd_imagem02` ) REFERENCES `imagem` ( `cd_imagem` ) ;

ALTER TABLE `anuncio` ADD CONSTRAINT `fk_anuncio_imagem03` FOREIGN KEY ( `cd_imagem03` ) REFERENCES `imagem` ( `cd_imagem` ) ;
