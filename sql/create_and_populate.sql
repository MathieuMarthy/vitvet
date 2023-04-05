
create OR REPLACE table `User`(id INT AUTO_INCREMENT PRIMARY KEY,
			login VARCHAR(50) UNIQUE NOT NULL,
                    	nom VARCHAR(100) NOT NULL,
                    	prenom VARCHAR(50) NOT NULL,
                        `password` VARCHAR(200) NOT NULL,
                        statut VARCHAR(6) NOT NULL,
                        mail VARCHAR(100) UNIQUE NOT NULL,
                        departement INT NOT NULL,
                        adresse VARCHAR(200) NOT NULL,
                        code_validation CHAR(24) NOT NULL,
                        verifier TINYINT(1) NOT NULL, -- 0 : adresse mail pas verifier, 1 : adresse mail vérifier
                        );

create OR REPLACE table Article(id INT AUTO_INCREMENT PRIMARY KEY,
                        nom VARCHAR(50) NOT NULL,
                        prix FLOAT NOT NULL,
                        quantite INT NOT NULL,
                        description VARCHAR(255) NOT NULL,
                        categorie VARCHAR(50) NOT NULL,
                        taille VARCHAR(5) NOT NULL,
                       image VARCHAR(255) NOT NULL
                        );

create OR REPLACE table Panier(
    			id_user INT, 
    			id_article INT, 
    			quantite_commande INT NOT NULL
			);

CREATE OR REPLACE table Commande(
			id_user INT,
			id_article INT,
			date_commande TIMESTAMP NOT NULL,
			adresse_livraison VARCHAR(200) NOT NULL,
			quantite_commande INT NOT NULL,
			en_cours TINYINT(1) NOT NULL, --0==false, 1==true
			prix FLOAT NOT NULL
			);



ALTER TABLE Panier ADD CONSTRAINT Pk_Panier PRIMARY KEY (id_user, id_article);
ALTER TABLE Panier ADD CONSTRAINT Fk_Panier_User FOREIGN KEY (id_user) REFERENCES `User`(id);
ALTER TABLE Panier ADD CONSTRAINT Fk_Panier_Article FOREIGN KEY (id_article) REFERENCES Article(id);

ALTER TABLE Commande ADD CONSTRAINT Pk_Commande PRIMARY KEY (id_user, id_article, date_commande);
ALTER TABLE Commande ADD CONSTRAINT Fk_Commande_User FOREIGN KEY (id_user) REFERENCES `User`(id);
ALTER TABLE Commande ADD CONSTRAINT Fk_Commande_Article FOREIGN KEY (id_article) REFERENCES Article(id);


DELIMITER |
--PROCEDURE 1 addUser
CREATE OR REPLACE PROCEDURE addUser(
				  IN _login VARCHAR(50),
    				  IN _nom VARCHAR(100),
                                  IN _prenom VARCHAR(50),
                                  IN _password VARCHAR(200),
                                  IN _statut VARCHAR(6),
                                  IN _mail VARCHAR(100),
                                  IN _departement INT,
                                  IN _adresse VARCHAR(200),
                                  IN _code_validation CHAR(24))
BEGIN
INSERT INTO `User`(login, nom, prenom, `password`, statut, mail, departement, adresse, code_validation, verifier) VALUES(_login, _nom, _prenom, _password, _statut, _mail, _departement, _adresse, _code_validation, 0);
END;|

--PROCEDURE 2 updateUser
CREATE OR REPLACE PROCEDURE updateUser(
    				IN _id INT,
    				IN _login VARCHAR(50),
    				IN _nom VARCHAR(100),
                                  IN _prenom VARCHAR(50),
                                  IN _password VARCHAR(200),
                                  IN _statut VARCHAR(6),
                                  IN _mail VARCHAR(100),
                                  IN _departement INT,
                                  IN _adresse VARCHAR(200))
BEGIN
UPDATE `User` SET login=_login, nom=_nom, prenom=_prenom, `password`=_password, statut=_statut, mail=_mail, departement=_departement, adresse=_adresse WHERE id=_id;
END;|

--PROCEDURE 2.5 passe la variable vérifier à 1 pour signifier que le User a une adresse mail valide
CREATE OR REPLACE PROCEDURE verifMailOk(IN _id INT)
BEGIN
UPDATE `User` SET verifier = 1 WHERE id=_id;
END; |

--PROCEDURE 3 deleteUserById. On supprime aussi le Panier et les Commandes du User
CREATE OR REPLACE PROCEDURE deleteUserById(IN _id INT)
BEGIN
DELETE FROM Panier WHERE id_user=_id;
DELETE FROM Commande WHERE id_user=_id;
DELETE FROM `User` WHERE id=_id;
END;|

--Procedure 3.5 deleteUserByLogin. On supprime aussi le Panier et les Commandes du User
CREATE OR REPLACE PROCEDURE deleteUserByLogin(IN _login VARCHAR(50))
BEGIN
DELETE FROM Panier WHERE id_user=_id;
DELETE FROM Commande WHERE id_user=_id;
DELETE FROM `User` WHERE login=_login;
END;|

--PROCEDURE 4 addArticle
CREATE OR REPLACE PROCEDURE addArticle(
                                        IN _nom VARCHAR(50),
                                        IN _prix FLOAT,
                                        IN _quantite INT,
                                        IN _description VARCHAR(255),
                                        IN _categorie VARCHAR(50),
                                        IN _taille VARCHAR(5),
                                       IN _image VARCHAR(255))
BEGIN
INSERT INTO Article(nom, prix, quantite, description, categorie, taille, image) VALUES(_nom, _prix, _quantite, _description, _categorie, _taille, _image);
END;|

--PROCEDURE 5 updateArticle
CREATE OR REPLACE PROCEDURE updateArticle(
    					IN _id INT,
                                        IN _nom VARCHAR(50),
                                        IN _prix FLOAT,
                                        IN _quantite INT,
                                        IN _description VARCHAR(255),
                                        IN _categorie VARCHAR(50),
                                        IN _taille VARCHAR(5),
                                       IN _image VARCHAR(255))
BEGIN
UPDATE Article SET nom=_nom, prix=_prix, quantite=_quantite, description=_description, categorie=_categorie, taille=_taille, image=_image WHERE id=_id;
END;|

--PROCEDURE 6 deleteArticle. On ne peut pas supprimer un article qui a une référence dans un Panier ou une Commande
CREATE OR REPLACE PROCEDURE deleteArticle(IN _id INT)
BEGIN
DELETE FROM Article WHERE id=_id;
END;|

--PROCEDURE 7 addPanier
CREATE OR REPLACE PROCEDURE addPanier(IN _id_user INT, IN _id_article INT, IN _quantite_commande INT)
BEGIN
INSERT INTO Panier VALUES(_id_user, _id_article, _quantite_commande);
END;|

--PROCEDURE 8 updatePanier
CREATE OR REPLACE PROCEDURE updatePanier(IN _id_user INT, IN _id_article INT, IN _quantite_commande INT)
BEGIN
IF _quantite_commande=0 THEN 
CALL deletePanier(_id_user, _id_article);
ELSE
UPDATE Panier SET quantite_commande=_quantite_commande WHERE id_user=_id_user AND id_article=_id_article;
END IF;
END;|

--PROCEDURE 9 deletePanier
CREATE OR REPLACE PROCEDURE deletePanier(IN _id_user INT, IN _id_article INT)
BEGIN
DELETE FROM Panier WHERE id_user=_id_user AND id_article=_id_article;
END;|

--PROCEDURE 9.5 deleteUserPanier
CREATE OR REPLACE PROCEDURE deleteUserPanier(IN _id_user INT)
BEGIN
DELETE FROM Panier WHERE id_user=_id_user;
END;|

--PROCEDURE 10 addCommande
CREATE OR REPLACE PROCEDURE addCommande(IN _id_user INT, IN _id_article INT, IN _date_commande TIMESTAMP, IN _adresse_livraison VARCHAR(200), IN _quantite_commande INT, IN _prix FLOAT, IN _en_cours TINYINT(1))
BEGIN
INSERT INTO Commande VALUES(_id_user, _id_article, _date_commande, _adresse_livraison, _quantite_commande, _en_cours, _prix);
END;|

--PROCEDURE 11 updateCommande
CREATE OR REPLACE PROCEDURE updateCommande(IN _id_user INT, IN _date_commande TIMESTAMP, IN _en_cours TINYINT(1))
BEGIN
UPDATE Commande SET en_cours=_en_cours WHERE id_user=_id_user AND _date_commande=_date_commande;
END;|

--PROCEDURE 12 deleteCommande
CREATE OR REPLACE PROCEDURE deleteCommande(IN _id_user INT, IN _id_article INT)
BEGIN
DELETE FROM Commande WHERE id_user=_id_user AND id_article=_id_article;
END;|

--PROCEDURE 12.5 deleteUserCommande
CREATE OR REPLACE PROCEDURE deleteCommande(IN _id_user INT)
BEGIN
DELETE FROM Commande WHERE id_user=_id_user;
END;|

--PROCEDURE 13 recherche pour la barre de recherche Article
CREATE OR REPLACE PROCEDURE rechercheArticle(IN var VARCHAR(66))
BEGIN
SELECT * FROM Article 
WHERE CONVERT(id,CHAR) = var
OR LOWER(nom) LIKE LOWER(CONCAT("%",var,"%"))
OR CONVERT(quantite,CHAR) = var
OR LOWER(description) LIKE LOWER(CONCAT("%",var,"%"))
OR LOWER(categorie) LIKE LOWER(CONCAT("%",var,"%"))
OR taille = var;
END;|

--PROCEDURE 13.5 recherche pour la barre de recherche Article
CREATE OR REPLACE PROCEDURE rechercheArticleUnParNom(IN var VARCHAR(66))
BEGIN
SELECT * FROM Article 
WHERE CONVERT(id,CHAR) = var
OR LOWER(nom) LIKE LOWER(CONCAT("%",var,"%"))
OR CONVERT(quantite,CHAR) = var
OR LOWER(description) LIKE LOWER(CONCAT("%",var,"%"))
OR LOWER(categorie) LIKE LOWER(CONCAT("%",var,"%"))
OR taille = var;
END;|


--PROCEDURE 13.7 recherche pour la barre de recherche User
CREATE OR REPLACE PROCEDURE rechercheUser(IN var VARCHAR(66))
BEGIN
SELECT * FROM `User` 
WHERE CONVERT(id,CHAR) LIKE CONCAT("%",var,"%")
OR LOWER(login) LIKE LOWER(CONCAT("%",var,"%"))
OR LOWER(nom) LIKE LOWER(CONCAT("%",var,"%"))
OR LOWER(prenom) LIKE LOWER(CONCAT("%",var,"%"))
OR LOWER(statut) LIKE LOWER(CONCAT("%",var,"%"))
OR LOWER(mail) LIKE LOWER(CONCAT("%",var,"%"))
OR LOWER(adresse) LIKE LOWER(CONCAT("%",var,"%"))
OR CONVERT(departement, CHAR) LIKE CONCAT("%",var,"%");
END;|


--PROCEDURE 14 récupère tous les articles
CREATE OR REPLACE PROCEDURE findAllArticle()
BEGIN
SELECT * FROM Article;
END;|

--PROCEDURE 14.5 récupère tous les articles avec un nom différents
CREATE OR REPLACE PROCEDURE findAllArticleUnParNom()
BEGIN
SELECT * FROM Article GROUP BY nom;
END;|

--PROCEDURE 15 récupère les produits pour la page d'accueil
CREATE OR REPLACE PROCEDURE findRecentArticle()
BEGIN
SELECT * FROM Article WHERE quantite>0 GROUP BY nom ORDER BY id DESC LIMIT 4;
END;|

--PROCEDURE 16 récupère l'article avec l'id en param
CREATE OR REPLACE PROCEDURE findArticleById(IN _id INT)
BEGIN
SELECT * FROM Article WHERE id=_id;
END;|

--PROCEDURE 17 récupère les articles avec le nom en param
CREATE OR REPLACE PROCEDURE findArticleByNom(IN _nom VARCHAR(50))
BEGIN
SELECT * FROM Article WHERE nom=_nom;
END;|

--PROCEDURE 18 récupère les articles avec la categorie en param
CREATE OR REPLACE PROCEDURE findArticleByCategorie(IN _categorie VARCHAR(50))
BEGIN
SELECT * FROM Article WHERE categorie=_categorie;
END;|

--PROCEDURE 19 récupère les articles avec la categorie en param
CREATE OR REPLACE PROCEDURE findArticleByCategorieUnParNom(IN _categorie VARCHAR(50))
BEGIN
SELECT * FROM Article WHERE categorie=_categorie GROUP BY nom;
END;|

--PROCEDURE 20 récupère tous les Users
CREATE OR REPLACE PROCEDURE findAllUser()
BEGIN
SELECT * FROM `User`;
END;|

--PROCEDURE 21 récupère un User grâce à son id
CREATE OR REPLACE PROCEDURE findUserById(IN _id INT)
BEGIN
SELECT * FROM `User` WHERE id=_id;
END;|

--PROCEDURE 21.5 récupère un User grâce à son login
CREATE OR REPLACE PROCEDURE findUserByLogin(IN _login VARCHAR(50))
BEGIN
SELECT * FROM `User` WHERE login=_login;
END;|

--PROCEDURE 22 récupère un Panier grâce à son id_user
CREATE OR REPLACE PROCEDURE findPanierByUserId(IN _id_user INT)
BEGIN
SELECT * FROM `Panier` WHERE id_user=_id_user;
END;|

--PROCEDURE 23 récupère une ligne du Panier
CREATE OR REPLACE PROCEDURE findOneLigne(IN _id_user INT, IN _id_article INT)
BEGIN
SELECT * FROM `Panier` WHERE id_user=_id_user AND id_article=_id_article;
END;|

--PROCEDURE 24 compte le nombre d'article dans la panier d'un User
CREATE OR REPLACE PROCEDURE compteNombreArticleDuPanier(IN _id_user INT)
BEGIN
SELECT sum(quantite_commande) as nb FROM `Panier` WHERE id_user=_id_user;
END;|

--PROCEDURE 25 récupère tout les articles de la commande d'un User avec une date donnée
CREATE OR REPLACE PROCEDURE findCommandeParIdUserEtDate(IN _id_user INT, IN _date TIMESTAMP)
BEGIN
SELECT * FROM `Commande` WHERE id_user=_id_user AND date_commande = _date;
END;|

--PROCEDURE 26 récupère une ligne d'une commande
CREATE OR REPLACE PROCEDURE findOneLineCommande(IN _id_user INT, IN _id_article, IN _date TIMESTAMP)
BEGIN
SELECT * FROM `Commande` WHERE id_user=_id_user AND id_article=_id_article AND date_commande = _date;
END;|

--PROCEDURE 27 récupère tous les articles déjà commandé par un User
CREATE OR REPLACE PROCEDURE findCommandeByUserId(IN _id_user INT)
BEGIN
SELECT * FROM `Commande` WHERE id_user=_id_user ORDER BY date_commande DESC;
END;|

--PROCEDURE 28 récupère tous les articles déjà commandé par un User d'une certaine date
CREATE OR REPLACE PROCEDURE findCommandeByUserIdAndDate(IN _id_user INT, IN _date TIMESTAMP)
BEGIN
SELECT * FROM `Commande` WHERE id_user=_id_user AND date_commande = _date;
END;|








/*------------------------------------------------------------------------ POPULATE ------------------------------------------------------------------------*/


--création de l'admin suprême (mot de passe chiffré)
CALL addUser("admin1", "Chusseau", "Nicolas", "$2y$10$du.t4yGtTKnA18luqU4Pje4lfdt9fXhIZIqaFN4tySS9ZIt8nOfnW", "admin", 44000,"15 rue des petites fleurs", "virABGGolhAfmPvnYTr453Db");
CALL addArticle("Chaussures en cuir noir", 119.95, 10, "Chaussures en cuir noir lissé mélangeant confort et élégance", "chaussure","40","chaussure_cuir_noires.webp");
CALL addArticle("Chaussures en cuir de daim marron", 89.99, 7, "Chaussures en cuir de daim marron afin de marcher avec élégance", "chaussure","42","chaussure_cuir_marron.webp");
CALL addArticle("Chaussures bleues type basket", 69.99, 8, "Chaussures bleues, rien de mieux pour se sentir dans ses baskets", "chaussure","41","chaussures_bleues.webp");
CALL addArticle("Chemise à carreaux", 49.99, 16, "Chemise à carreaux pour se sentir bien au travail", "chaussures","M","chemise_a_carreau.webp");
CALL addArticle("T-Shirt quadrillé", 29.99, 14, "T-Shirt quadrillé pour faire bonne impression aux repas en famille","Tshirt","S","tee-shirt_quadrille.webp");
CALL addArticle("Sweat à capuche gris", 59.99, 20, "Sweat à capuche gris, la parfaite combinaison entre style et décontracté", "Tshirt","M","sweat_a_capuche_gris.webp");
CALL addArticle("Sweat à capuche gris", 59.99, 20, "Sweat à capuche gris, la parfaite combinaison entre style et décontracté", "Tshirt","S","sweat_a_capuche_gris.webp");
CALL addArticle("Sweat à capuche gris", 59.99, 20, "Sweat à capuche gris, la parfaite combinaison entre style et décontracté", "Tshirt","L","sweat_a_capuche_gris.webp");
CALL addArticle("Sweat à capuche gris", 59.99, 20, "Sweat à capuche gris, la parfaite combinaison entre style et décontracté", "Tshirt","XXL","sweat_a_capuche_gris.webp");
CALL addArticle("Polo noir tacheté blanc", 39.99, 17, "Polo noir tacheté blanc, la classe avant tout !", "Tshirt","S","polo_noir_point_blanc.jpg");
CALL addArticle("Polo noir tacheté blanc", 39.99, 23, "Polo noir tacheté blanc, la classe avant tout !", "Tshirt","M","polo_noir_point_blanc.jpg");
CALL addArticle("Polo noir tacheté blanc", 39.99, 21, "Polo noir tacheté blanc, la classe avant tout !", "Tshirt","L","polo_noir_point_blanc.jpg");
CALL addArticle("Polo noir tacheté blanc", 39.99, 15, "Polo noir tacheté blanc, la classe avant tout !", "Tshirt","XL","polo_noir_point_blanc.jpg");
CALL addArticle("Polo blanc avec chevron", 34.99, 15, "Polo blanc avec chevron, LE polo pour les développeurs HTML", "Tshirt","S","polo_blanc_chevron_retourné.jpg");
CALL addArticle("Polo blanc avec chevron", 34.99, 15, "Polo blanc avec chevron, LE polo pour les développeurs HTML", "Tshirt","M","polo_blanc_chevron_retourné.jpg");
CALL addArticle("Polo blanc avec chevron", 34.99, 15, "Polo blanc avec chevron, LE polo pour les développeurs HTML", "Tshirt","L","polo_blanc_chevron_retourné.jpg");
CALL addArticle("Polo blanc avec chevron", 34.99, 15, "Polo blanc avec chevron, LE polo pour les développeurs HTML", "Tshirt","XL","polo_blanc_chevron_retourné.jpg");
CALL addArticle("Pantalon chino gris cadrillé", 59.99, 25, "Pantalon chino gris cadrillé, trop la classe pour les repas de famille", "pantalon","S","Pantalon_chino_gris_cadrillé_homme.jpg");
CALL addArticle("Pantalon chino gris cadrillé", 59.99, 31, "Pantalon chino gris cadrillé, trop la classe pour les repas de famille", "pantalon","M","Pantalon_chino_gris_cadrillé_homme.jpg");
CALL addArticle("Pantalon chino gris cadrillé", 59.99, 27, "Pantalon chino gris cadrillé, trop la classe pour les repas de famille", "pantalon","L","Pantalon_chino_gris_cadrillé_homme.jpg");
CALL addArticle("Pantalon chino gris cadrillé", 59.99, 21, "Pantalon chino gris cadrillé, trop la classe pour les repas de famille", "pantalon","XL","Pantalon_chino_gris_cadrillé_homme.jpg");
CALL addArticle("Pantalon cargo beige cordon", 54.99, 25, "Pantalon cargo beige avec cordon, relax tout en ayant du style", "pantalon","S","Pantalon_cargo_beige_a_cordon.jpg");
CALL addArticle("Pantalon cargo beige cordon", 54.99, 33, "Pantalon cargo beige avec cordon, relax tout en ayant du style", "pantalon","M","Pantalon_cargo_beige_a_cordon.jpg");
CALL addArticle("Pantalon cargo beige cordon", 54.99, 31, "Pantalon cargo beige avec cordon, relax tout en ayant du style", "pantalon","L","Pantalon_cargo_beige_a_cordon.jpg");
CALL addArticle("Pantalon cargo beige cordon", 54.99, 23, "Pantalon cargo beige avec cordon, relax tout en ayant du style", "pantalon","XL","Pantalon_cargo_beige_a_cordon.jpg");
CALL addArticle("Pantalon chino marron", 59.99, 35, "Pantalon chino marron, trop de style, trop cool", "pantalon","S","Pantalon_chino_marron_homme.jpg");
CALL addArticle("Pantalon chino marron", 59.99, 40, "Pantalon chino marron, trop de style, trop cool", "pantalon","M","Pantalon_chino_marron_homme.jpg");
CALL addArticle("Pantalon chino marron", 59.99, 39, "Pantalon chino marron, trop de style, trop cool", "pantalon","L","Pantalon_chino_marron_homme.jpg");
CALL addArticle("Pantalon chino marron", 59.99, 31, "Pantalon chino marron, trop de style, trop cool", "pantalon","XL","Pantalon_chino_marron_homme.jpg");
CALL addArticle("Bottines plates noires homme", 79.99, 35, "Bottines plates noires homme, rien de mieux pour faire du bruit", "chaussure","38","bottines_plates_zippees_noir_homme.jpg");
CALL addArticle("Bottines plates noires homme", 79.99, 37, "Bottines plates noires homme, rien de mieux pour faire du bruit", "chaussure","39","bottines_plates_zippees_noir_homme.jpg");
CALL addArticle("Bottines plates noires homme", 79.99, 40, "Bottines plates noires homme, rien de mieux pour faire du bruit", "chaussure","40","bottines_plates_zippees_noir_homme.jpg");
CALL addArticle("Bottines plates noires homme", 79.99, 45, "Bottines plates noires homme, rien de mieux pour faire du bruit", "chaussure","41","bottines_plates_zippees_noir_homme.jpg");
CALL addArticle("Bottines plates noires homme", 79.99, 46, "Bottines plates noires homme, rien de mieux pour faire du bruit", "chaussure","42","bottines_plates_zippees_noir_homme.jpg");
CALL addArticle("Bottines plates noires homme", 79.99, 43, "Bottines plates noires homme, rien de mieux pour faire du bruit", "chaussure","43","bottines_plates_zippees_noir_homme.jpg");
CALL addArticle("Bottines plates noires homme", 79.99, 39, "Bottines plates noires homme, rien de mieux pour faire du bruit", "chaussure","44","bottines_plates_zippees_noir_homme.jpg");
CALL addArticle("Bottines plates noires homme", 79.99, 35, "Bottines plates noires homme, rien de mieux pour faire du bruit", "chaussure","45","bottines_plates_zippees_noir_homme.jpg");
CALL addArticle("Sweat à capuche Star Wars", 59.99, 28, "Sweat à capuche Star Wars, que la chaleur soit avec toi", "Tshirt","S","sweat_star_wars.jpg");
CALL addArticle("Sweat à capuche Star Wars", 59.99, 33, "Sweat à capuche Star Wars, que la chaleur soit avec toi", "Tshirt","M","sweat_star_wars.jpg");
CALL addArticle("Sweat à capuche Star Wars", 59.99, 35, "Sweat à capuche Star Wars, que la chaleur soit avec toi", "Tshirt","L","sweat_star_wars.jpg");
CALL addArticle("Sweat à capuche Star Wars", 59.99, 29, "Sweat à capuche Star Wars, que la chaleur soit avec toi", "Tshirt","XL","sweat_star_wars.jpg");
CALL addArticle("Sweat à capuche Hors Piste", 54.99, 28, "Sweat à capuche Hors Piste, attention au ravin", "Tshirt","S","sweat_hors_piste.jpg");
CALL addArticle("Sweat à capuche Hors Piste", 54.99, 36, "Sweat à capuche Hors Piste, attention au ravin", "Tshirt","M","sweat_hors_piste.jpg");
CALL addArticle("Sweat à capuche Hors Piste", 54.99, 38, "Sweat à capuche Hors Piste, attention au ravin", "Tshirt","L","sweat_hors_piste.jpg");
CALL addArticle("Sweat à capuche Hors Piste", 54.99, 27, "Sweat à capuche Hors Piste, attention au ravin", "Tshirt","Xl","sweat_hors_piste.jpg");
CALL addArticle("Gilet noir zippé AirBag", 54.99, 28, "Gilet noir zippé avec des manches AirBag, veillez à bien monter la fermeture jusqu'en haut en cas d'accident", "Tshirt","S","gilet_noir_manche_airbag.jpg");
CALL addArticle("Gilet noir zippé AirBag", 54.99, 32, "Gilet noir zippé avec des manches AirBag, veillez à bien monter la fermeture jusqu'en haut en cas d'accident", "Tshirt","M","gilet_noir_manche_airbag.jpg");
CALL addArticle("Gilet noir zippé AirBag", 54.99, 33, "Gilet noir zippé avec des manches AirBag, veillez à bien monter la fermeture jusqu'en haut en cas d'accident", "Tshirt","L","gilet_noir_manche_airbag.jpg");
CALL addArticle("Gilet noir zippé AirBag", 54.99, 25, "Gilet noir zippé avec des manches AirBag, veillez à bien monter la fermeture jusqu'en haut en cas d'accident", "Tshirt","XL","gilet_noir_manche_airbag.jpg");
CALL addArticle("Jean bleu abimé", 59.99, 29, "Jean bleu abimé, un jean parfait pour aller chicher", "pantalon","S","jean_bleu_abimé.jpg");
CALL addArticle("Jean bleu abimé", 59.99, 34, "Jean bleu abimé, un jean parfait pour aller chicher", "pantalon","M","jean_bleu_abimé.jpg");
CALL addArticle("Jean bleu abimé", 59.99, 37, "Jean bleu abimé, un jean parfait pour aller chicher", "pantalon","L","jean_bleu_abimé.jpg");
CALL addArticle("Jean bleu abimé", 59.99, 27, "Jean bleu abimé, un jean parfait pour aller chicher", "pantalon","XL","jean_bleu_abimé.jpg");
CALL addArticle("Jean délavé", 54.99, 31, "Jean délavé, très fade et ample", "pantalon","S","jean_délavé.jpg");
CALL addArticle("Jean délavé", 54.99, 36, "Jean délavé, très fade et ample", "pantalon","M","jean_délavé.jpg");
CALL addArticle("Jean délavé", 54.99, 37, "Jean délavé, très fade et ample", "pantalon","L","jean_délavé.jpg");
CALL addArticle("Jean délavé", 54.99, 29, "Jean délavé, très fade et ample", "pantalon","XL","jean_délavé.jpg");
CALL addArticle("Bermuda chino à coulisse bleu", 39.99, 45, "Bermuda chino à coulisse bleu, detiné aux personne connaissant le mot bermuda", "pantalon","S","Bermuda_chino_à_coulisse_bleu.jpg");
CALL addArticle("Bermuda chino à coulisse bleu", 39.99, 50, "Bermuda chino à coulisse bleu, detiné aux personne connaissant le mot bermuda", "pantalon","M","Bermuda_chino_à_coulisse_bleu.jpg");
CALL addArticle("Bermuda chino à coulisse bleu", 39.99, 52, "Bermuda chino à coulisse bleu, detiné aux personne connaissant le mot bermuda", "pantalon","L","Bermuda_chino_à_coulisse_bleu.jpg");
CALL addArticle("Bermuda chino à coulisse bleu", 39.99, 41, "Bermuda chino à coulisse bleu, detiné aux personne connaissant le mot bermuda", "pantalon","XL","Bermuda_chino_à_coulisse_bleu.jpg");
CALL addArticle("Short chino rose", 39.99, 29, "Short chino rose, certaines personnes apprécient cette couleur", "pantalon","S","short_chino_rose.jpg");
CALL addArticle("Short chino rose", 39.99, 32, "Short chino rose, certaines personnes apprécient cette couleur", "pantalon","M","short_chino_rose.jpg");
CALL addArticle("Short chino rose", 39.99, 33, "Short chino rose, certaines personnes apprécient cette couleur", "pantalon","L","short_chino_rose.jpg");
CALL addArticle("Short chino rose", 39.99, 27, "Short chino rose, certaines personnes apprécient cette couleur", "pantalon","XL","short_chino_rose.jpg");
CALL addArticle("Chemise à fleurs fanées", 39.99, 34, "Chemise manche longue à fleurs fanées, il fait chaud !", "Tshirt","S","Chemise_fleurs_fanées.jpg");
CALL addArticle("Chemise à fleurs fanées", 39.99, 40, "Chemise manche longue à fleurs fanées, il fait chaud !", "Tshirt","M","Chemise_fleurs_fanées.jpg");
CALL addArticle("Chemise à fleurs fanées", 39.99, 43, "Chemise manche longue à fleurs fanées, il fait chaud !", "Tshirt","L","Chemise_fleurs_fanées.jpg");
CALL addArticle("Chemise à fleurs fanées", 39.99, 32, "Chemise manche longue à fleurs fanées, il fait chaud !", "Tshirt","XL","Chemise_fleurs_fanées.jpg");
CALL addArticle("Chemise bleue virile", 44.99, 34, "Chemise bleue virile, exclusivement pour les hommes charismatiques (vous l'êtes)", "Tshirt","S","chemise_bleue_virile.jpg");
CALL addArticle("Chemise bleue virile", 44.99, 40, "Chemise bleue virile, exclusivement pour les hommes charismatiques (vous l'êtes)", "Tshirt","M","chemise_bleue_virile.jpg");
CALL addArticle("Chemise bleue virile", 44.99, 42, "Chemise bleue virile, exclusivement pour les hommes charismatiques (vous l'êtes)", "Tshirt","L","chemise_bleue_virile.jpg");
CALL addArticle("Chemise bleue virile", 44.99, 30, "Chemise bleue virile, exclusivement pour les hommes charismatiques (vous l'êtes)", "Tshirt","XL","chemise_bleue_virile.jpg");
CALL addArticle("Chemise éco-responsable", 34.99, 37, "Chemise éco-responsable, fabriquée intégralement avec du coton bio, merci de contribuer à l'effort pour préserver la planète", "Tshirt","S","Chemise_blanche_eco_responsable.jpg");
CALL addArticle("Chemise éco-responsable", 34.99, 40, "Chemise éco-responsable, fabriquée intégralement avec du coton bio, merci de contribuer à l'effort pour préserver la planète", "Tshirt","M","Chemise_blanche_eco_responsable.jpg");
CALL addArticle("Chemise éco-responsable", 34.99, 44, "Chemise éco-responsable, fabriquée intégralement avec du coton bio, merci de contribuer à l'effort pour préserver la planète", "Tshirt","L","Chemise_blanche_eco_responsable.jpg");
CALL addArticle("Chemise éco-responsable", 34.99, 34, "Chemise éco-responsable, fabriquée intégralement avec du coton bio, merci de contribuer à l'effort pour préserver la planète", "Tshirt","XL","Chemise_blanche_eco_responsable.jpg");
CALL addArticle("T-shirt chauve qui peut", 34.99, 38, "T-shirt chauve qui peut, beau et très confortable, exclusivement destiné à l'élite", "Tshirt","S","Tshirt_chauve_qui_peut.jpg");
CALL addArticle("T-shirt chauve qui peut", 34.99, 41, "T-shirt chauve qui peut, beau et très confortable, exclusivement destiné à l'élite", "Tshirt","M","Tshirt_chauve_qui_peut.jpg");
CALL addArticle("T-shirt chauve qui peut", 34.99, 44, "T-shirt chauve qui peut, beau et très confortable, exclusivement destiné à l'élite", "Tshirt","L","Tshirt_chauve_qui_peut.jpg");
CALL addArticle("T-shirt chauve qui peut", 34.99, 35, "T-shirt chauve qui peut, beau et très confortable, exclusivement destiné à l'élite", "Tshirt","XL","Tshirt_chauve_qui_peut.jpg");
CALL addArticle("T-shirt Kotlin 0 ou rien", 34.99, 38, "T-shirt Kotlin à la noix, rien de plus rapide pour corriger les contrôles", "Tshirt","S","t-shirt_kotlin.jpg");
CALL addArticle("T-shirt Kotlin 0 ou rien", 34.99, 45, "T-shirt Kotlin à la noix, rien de plus rapide pour corriger les contrôles", "Tshirt","M","t-shirt_kotlin.jpg");
CALL addArticle("T-shirt Kotlin 0 ou rien", 34.99, 43, "T-shirt Kotlin à la noix, rien de plus rapide pour corriger les contrôles", "Tshirt","L","t-shirt_kotlin.jpg");
CALL addArticle("T-shirt Kotlin 0 ou rien", 34.99, 34, "T-shirt Kotlin à la noix, rien de plus rapide pour corriger les contrôles", "Tshirt","XL","t-shirt_kotlin.jpg");
CALL addArticle("Chemise en Jean bleue", 44.99, 38, "Chemise en jean bleue, se marie bien avec un pantalon en jean", "Tshirt","S","chemise_jean_bleue.jpg");
CALL addArticle("Chemise en Jean bleue", 44.99, 44, "Chemise en jean bleue, se marie bien avec un pantalon en jean", "Tshirt","M","chemise_jean_bleue.jpg");
CALL addArticle("Chemise en Jean bleue", 44.99, 46, "Chemise en jean bleue, se marie bien avec un pantalon en jean", "Tshirt","L","chemise_jean_bleue.jpg");
CALL addArticle("Chemise en Jean bleue", 44.99, 36, "Chemise en jean bleue, se marie bien avec un pantalon en jean", "Tshirt","XL","chemise_jean_bleue.jpg");
CALL addArticle("Pull de Noël Charlie", 54.99, 38, "Pull de Noël où est Charlie ?, mais d'ailleurs, il est où ?", "Tshirt","S","pull_ou_est_charlie.jpg");
CALL addArticle("Pull de Noël Charlie", 54.99, 48, "Pull de Noël où est Charlie ?, mais d'ailleurs, il est où ?", "Tshirt","M","pull_ou_est_charlie.jpg");
CALL addArticle("Pull de Noël Charlie", 54.99, 46, "Pull de Noël où est Charlie ?, mais d'ailleurs, il est où ?", "Tshirt","L","pull_ou_est_charlie.jpg");
CALL addArticle("Pull de Noël Charlie", 54.99, 40, "Pull de Noël où est Charlie ?, mais d'ailleurs, il est où ?", "Tshirt","XL","pull_ou_est_charlie.jpg");
CALL addArticle("Jean noir", 59.99, 34, "Jean stretch noir, la classe", "pantalon","S","jean_noir.jpg");
CALL addArticle("Jean noir", 59.99, 37, "Jean stretch noir, la classe", "pantalon","M","jean_noir.jpg");
CALL addArticle("Jean noir", 59.99, 39, "Jean stretch noir, la classe", "pantalon","L","jean_noir.jpg");
CALL addArticle("Jean noir", 59.99, 35, "Jean stretch noir, la classe", "pantalon","XL","jean_noir.jpg");
CALL addArticle("Pantalon chino bleu", 59.99, 46, "Pantalon chino bleu, le style avant tout", "pantalon","S","pantalon_chino_bleu.jpg");
CALL addArticle("Pantalon chino bleu", 59.99, 39, "Pantalon chino bleu, le style avant tout", "pantalon","M","pantalon_chino_bleu.jpg");
CALL addArticle("Pantalon chino bleu", 59.99, 36, "Pantalon chino bleu, le style avant tout", "pantalon","L","pantalon_chino_bleu.jpg");
CALL addArticle("Pantalon chino bleu", 59.99, 18, "Pantalon chino bleu, le style avant tout", "pantalon","XL","pantalon_chino_bleu.jpg");
CALL addArticle("Pantalon chino prune", 59.99, 35, "Pantalon chino prune, spécialement conçu pour vous", "pantalon","S","Pantalon_chino_prune_homme.jpg");
CALL addArticle("Pantalon chino prune", 59.99, 39, "Pantalon chino prune, spécialement conçu pour vous", "pantalon","M","Pantalon_chino_prune_homme.jpg");
CALL addArticle("Pantalon chino prune", 59.99, 37, "Pantalon chino prune, spécialement conçu pour vous", "pantalon","L","Pantalon_chino_prune_homme.jpg");
CALL addArticle("Pantalon chino prune", 59.99, 33, "Pantalon chino prune, spécialement conçu pour vous", "pantalon","XL","Pantalon_chino_prune_homme.jpg");
CALL addArticle("Pantalon de jogging coloré", 39.99, 35, "Pantalon de jogging gris coloré, rien de mieux pour se sentir à l'aise", "pantalon","S","Pantalon_jogging_gris_coloré.jpg");
CALL addArticle("Pantalon de jogging coloré", 39.99, 39, "Pantalon de jogging gris coloré, rien de mieux pour se sentir à l'aise", "pantalon","M","Pantalon_jogging_gris_coloré.jpg");
CALL addArticle("Pantalon de jogging coloré", 39.99, 48, "Pantalon de jogging gris coloré, rien de mieux pour se sentir à l'aise", "pantalon","L","Pantalon_jogging_gris_coloré.jpg");
CALL addArticle("Pantalon de jogging coloré", 39.99, 25, "Pantalon de jogging gris coloré, rien de mieux pour se sentir à l'aise", "pantalon","XL","Pantalon_jogging_gris_coloré.jpg");
CALL addArticle("Bottines plates marron homme", 79.99, 20, "Bottines plates marron homme, pour avoir chaud aux pieds en hiver", "chaussure","39","bottines_plates_marron_homme.jpg");
CALL addArticle("Bottines plates marron homme", 79.99, 27, "Bottines plates marron homme, pour avoir chaud aux pieds en hiver", "chaussure","40","bottines_plates_marron_homme.jpg");
CALL addArticle("Bottines plates marron homme", 79.99, 29, "Bottines plates marron homme, pour avoir chaud aux pieds en hiver", "chaussure","41","bottines_plates_marron_homme.jpg");
CALL addArticle("Bottines plates marron homme", 79.99, 33, "Bottines plates marron homme, pour avoir chaud aux pieds en hiver", "chaussure","42","bottines_plates_marron_homme.jpg");
CALL addArticle("Bottines plates marron homme", 79.99, 30, "Bottines plates marron homme, pour avoir chaud aux pieds en hiver", "chaussure","43","bottines_plates_marron_homme.jpg");
CALL addArticle("Bottines plates marron homme", 79.99, 28, "Bottines plates marron homme, pour avoir chaud aux pieds en hiver", "chaussure","44","bottines_plates_marron_homme.jpg");
CALL addArticle("Tennis gris foncé", 49.99, 25, "Tennis gris foncé homme, parfait pour faire......du tennis !", "chaussure","39","tennis_gris_foncé.jpg");
CALL addArticle("Tennis gris foncé", 49.99, 27, "Tennis gris foncé homme, parfait pour faire......du tennis !", "chaussure","40","tennis_gris_foncé.jpg");
CALL addArticle("Tennis gris foncé", 49.99, 0, "Tennis gris foncé homme, parfait pour faire......du tennis !", "chaussure","41","tennis_gris_foncé.jpg");
CALL addArticle("Tennis gris foncé", 49.99, 29, "Tennis gris foncé homme, parfait pour faire......du tennis !", "chaussure","42","tennis_gris_foncé.jpg");
CALL addArticle("Tennis gris foncé", 49.99, 31, "Tennis gris foncé homme, parfait pour faire......du tennis !", "chaussure","43","tennis_gris_foncé.jpg");
CALL addArticle("Tennis gris foncé", 49.99, 23, "Tennis gris foncé homme, parfait pour faire......du tennis !", "chaussure","44","tennis_gris_foncé.jpg");










