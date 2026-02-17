-- final_exam_s3
drop database final_exam_s3;
create database final_exam_s3;
use final_exam_s3;
 drop TABLE IF EXISTS bngrc_regionVille;
 drop TABLE IF EXISTS bngrc_besoinVille;
 drop TABLE IF EXISTS bngrc_sinistre;
 drop TABLE IF EXISTS bngrc_categorieBesoin;
 drop TABLE IF EXISTS bngrc_uniteBesoin;
 drop TABLE IF EXISTS bngrc_besoin;
 drop TABLE IF EXISTS bngrc_statusBesoinVille;
 drop TABLE IF EXISTS bngrc_collecteDetails;
 drop TABLE IF EXISTS bngrc_collecte;
 drop TABLE IF EXISTS bngrc_distributionDetails;
 drop TABLE IF EXISTS bngrc_distribution;
 drop TABLE IF EXISTS bngrc_ville;
 drop TABLE IF EXISTS bngrc_region; 

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

-- Table bngrc_statusBesoinVille: état du besoin pour une ville (ex: attendu, partiel, couvert)
create table bngrc_statusBesoinVille (
  sbv_id int primary key auto_increment,
  sbv_libelle varchar(255) not null
);

-- Table bngrc_besoinVille
create table bngrc_besoinVille (
  bv_id int primary key auto_increment,
  bv_besoin int not null,
  bv_quantite int not null,
  bv_date_demande date,
  bv_ville int not null,
  bv_status int not null,
  foreign key (bv_besoin) references bngrc_besoin(b_id),
  foreign key (bv_ville) references bngrc_ville(v_id)
  , foreign key (bv_status) references bngrc_statusBesoinVille(sbv_id)
);

-- Table bngrc_collecte
create table bngrc_collecte (
  c_id int primary key auto_increment,
  c_date date not null
);

-- Table bngrc_collecteDetails
create table bngrc_collecteDetails (
  cd_id int primary key auto_increment,
  cd_collecte int not null,
  cd_besoin int not null,
  cd_quantite int not null,
  foreign key (cd_collecte) references bngrc_collecte(c_id),
  foreign key (cd_besoin) references bngrc_besoin(b_id)
);

-- Table bngrc_distribution
create table bngrc_distribution (
  d_id int primary key auto_increment,
  d_date date not null
);

-- Table bngrc_distributionDetails
create table bngrc_distributionDetails (
  dd_id int primary key auto_increment,
  dd_distribution int not null,
  dd_besoin int not null,
  dd_quantite int not null,
  dd_ville int not null,
  dd_collecteDetails int not null,
  foreign key (dd_distribution) references bngrc_distribution(d_id),
  foreign key (dd_besoin) references bngrc_besoin(b_id),
  foreign key (dd_ville) references bngrc_ville(v_id),
  foreign key (dd_collecteDetails) references bngrc_collecteDetails(cd_id)
);