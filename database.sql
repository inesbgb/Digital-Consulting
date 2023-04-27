DROP DATABASE IF EXISTS digitalconsulting;
CREATE DATABASE digitalconsulting;
USE digitalconsulting;

DROP TABLE IF EXISTS user;
CREATE TABLE user (
    id INT PRIMARY KEY AUTO_INCREMENT,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    adress VARCHAR(100) NOT NULL,
    mail VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    sex VARCHAR(20) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    photo BLOB,
    role VARCHAR(50) NOT NULL
);


DROP TABLE IF EXISTS service;
CREATE TABLE service (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL
);
INSERT INTO service (name) VALUES ('DEVELOPPEMENT & IT');
INSERT INTO service (name) VALUES ('DESIGN & CREATION');
INSERT INTO service (name) VALUES ('MARKETING DIGITAL');
INSERT INTO service (name) VALUES ('COMMUNITY MANAGEMENT');

DROP TABLE IF EXISTS annonce;
CREATE TABLE annonce (
    id INT PRIMARY KEY AUTO_INCREMENT,
    service_id INT NOT NULL,
    CONSTRAINT fk_service_annonce
        FOREIGN KEY (service_id)
        REFERENCES service(id),
        user_freelance_id INT NOT NULL,
    CONSTRAINT fk_user_annonce
        FOREIGN KEY (user_freelance_id)
        REFERENCES user(id),
    photo BLOB,
    title VARCHAR(100) NOT NULL,
    price FLOAT NOT NULL,
    description TEXT NOT NULL,
    is_delete TINYINT(1) NOT NULL DEFAULT 0
);

DROP TABLE IF EXISTS facture;
CREATE TABLE facture (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_client_id INT NOT NULL,
    CONSTRAINT fk_facture_user
        FOREIGN KEY (user_client_id)
        REFERENCES user(id),
            annonce_id INT NOT NULL,
    CONSTRAINT fk_facture_annonce
        FOREIGN KEY (annonce_id)
        REFERENCES annonce(id)
);

DROP TABLE IF EXISTS tchat;
CREATE TABLE tchat (
    id INT PRIMARY KEY AUTO_INCREMENT,
    annonce_id INT NOT NULL,
        CONSTRAINT fk_tchat_annonce
        FOREIGN KEY ( annonce_id)
        REFERENCES annonce(id),
    user_tchat_id INT NOT NULL,
        CONSTRAINT fk_tchat_user
        FOREIGN KEY (user_tchat_id)
        REFERENCES user(id),
    created_at VARCHAR(20) NOT NULL,
    object VARCHAR(100) NOT NULL
);

DROP TABLE IF EXISTS message;
CREATE TABLE message (
    id INT PRIMARY KEY AUTO_INCREMENT,
    message
        VARCHAR(255) NOT NULL,
    user_id INT NOT NULL,
        CONSTRAINT fk_user_tchat
        FOREIGN KEY (user_id)
        REFERENCES user(id),
    tchat_id INT NOT NULL,
        CONSTRAINT fk_message_tchat
        FOREIGN KEY (tchat_id)
        REFERENCES tchat(id)
);
