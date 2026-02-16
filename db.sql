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

