-- final_exam_s3
drop database final_exam_s3;
create database final_exam_s3;
use final_exam_s3;

-- Table bngrc_region
create table bngrc_region (
  r_id int primary key auto_increment,
  r_nom varchar(255) not null
);

-- Table bngrc_ville
create table bngrc_ville (
  v_id int primary key auto_increment,
  v_nom varchar(255) not null
);

-- Table bngrc_regionVille
create table bngrc_regionVille (
  rv_id int primary key auto_increment,
  rv_region int not null,
  rv_ville int not null,
  foreign key (rv_region) references bngrc_region(r_id),
  foreign key (rv_ville) references bngrc_ville(v_id)
);

-- Table bngrc_sinistre
create table bngrc_sinistre (
  s_id int primary key auto_increment,
  s_nom varchar(255) not null,
  s_ville int not null,
  foreign key (s_ville) references bngrc_ville(v_id)
);

-- Table bngrc_categorieBesoin
create table bngrc_categorieBesoin (
  cb_id int primary key auto_increment,
  cb_libelle varchar(255) not null
);

-- Table bngrc_uniteBesoin
create table bngrc_uniteBesoin (
  ub_id int primary key auto_increment,
  ub_libelle varchar(255) not null
);

-- Table bngrc_besoin
create table bngrc_besoin (
  b_id int primary key auto_increment,
  b_libelle varchar(255) not null,
  b_prixUnitraire decimal(10, 2) not null,
  b_categorie int not null,
  b_unite int not null,
  foreign key (b_categorie) references bngrc_categorieBesoin(cb_id),
  foreign key (b_unite) references bngrc_uniteBesoin(ub_id)
);

-- Table bngrc_besoinVille
create table bngrc_besoinVille (
  bv_id int primary key auto_increment,
  bv_besoin int not null,
  bv_quantite int not null,
  bv_ville int not null,
  foreign key (bv_besoin) references bngrc_besoin(b_id),
  foreign key (bv_ville) references bngrc_ville(v_id)
);

-- Table bngrc_collecte
create table bngrc_collecte (
  c_id int primary key auto_increment,
  c_date date not null
);

