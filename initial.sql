SELECT @grotte_id := id FROM poi WHERE name = 'Grotte de Han Le Domaine des Grottes de Han';
SELECT @reserve_id := id FROM poi WHERE name = 'Réserve d\'Animaux Sauvages  Le Domaine des Grottes de Han';

INSERT INTO `question` (`poi_id`, `label`, `right_answer`, `wrong_answer1`, `wrong_answer2`, `wrong_answer3`)
VALUES
    (@reserve_id,"Depuis juillet, il vous est possible de visiter la totalité de la Réserve à pied. Quelle est la longeur du parcours ?","5 km.","3 km","2 km","6 km"),
    (@reserve_id,"Le massif qui surplombe la Grotte et dans la vallée abandonnée par la Lesse s'étend un parc de pure nature qui abrite la Réserve d'Animaux Sauvages. Quelle est sa superficie ?","250 hectares.","120 hectares","349 hectares","150 km²"),
    (@reserve_id,"Découvrez le massif calcaire de Boine, sa faune et sa flore si particulière. Partez à la rencontre d’animaux de nos contrées évoluant dans leur environnement naturel. Parmis ces animaux, lequel vous ne rencontrerez jamais ?comme le bison d'Europe ou le cheval de Przewalski.","le chat de votre voisin.","le cheval de Przewalski","le loup","l'ours"),
    (@reserve_id,"La visite en safari-car, est un grand véhicule ouvert qui permet d'apprécier la visite sans fatigue. D'où démarre la visite en Safari-car ?","du centre du village.","du bureau d’accueil",NULL,NULL),
    (@grotte_id,"Comment accède t-on aux grottes?","Tram.","Voiture","Tgv","Avion"),
    (@grotte_id,"De combien de marches est composé le pracours?","400.","200","300","100"),
    (@grotte_id,"Combien mesure la stalagmite dénommée \"Le Minaret\"?","5m80.","3m10","1m20","4m50"),
    (@grotte_id,"Qu'entend t-on à la sortie des Grottes?","Un coup de canon.","Un chat qui miaule","Un coup de fusil","Un lion qui rugit"),
    (@grotte_id,"Quand a eu lieu le premier itinéraire touristique?","1857.","1914","1865","1928");

INSERT INTO `user` (`email`, `password`, `lastname`, `firstname`) VALUES ("john@doe.tld", SHA1("secret"), "Doe", "John");

INSERT INTO `coupon` (`name`, `gain`)
VALUES
    ("La Calèche", "10% sur un menu au restaurant La Calèche à Rochefort"),
    ("Grottes de Lorettes", "5% sur l'entrée aux grottes de Lorettes à Rochefort"),
    ("Brasserie Rochefort", "Dégustation offerte à l'issu d'une visite achetée"),
    ("Brasserie Rochefort", "15% de remise sur la visite en semaine"),
    ("La Calèche", "Un dessert offert pour un menu acheté"),
    ("Dinan aventure", "10% sur le laser game");

INSERT INTO `poi_has_coupon`
VALUES
    (@grotte_id, 1),
    (@reserve_id, 2),
    (@grotte_id, 3),
    (@reserve_id, 4),
    (@grotte_id, 5),
    (@reserve_id, 5),
    (@reserve_id, 6);