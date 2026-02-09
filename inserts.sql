-- ===========================
-- USERS FPC : DIRECTIONS
-- ===========================

INSERT INTO users (name,email,password,entreprise,role,service,created_at,updated_at) VALUES
("DF","direction_financiere@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","chef_direction","Direction Financière",NOW(),NOW()),
("DRHSG","direction_rh@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","chef_direction","Direction Ressources Humaines et Gestion",NOW(),NOW()),
("DCP","direction_coordination@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","chef_direction","Direction Coordination et Planification",NOW(),NOW()),
("DPC","direction_promotion_culturelle@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","chef_direction","Direction Promotion Culturelle",NOW(),NOW()),
("CI","direction_controle@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","chef_direction","Direction Contrôle Interne",NOW(),NOW()),
("DMR","direction_redevance@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","chef_direction","Direction Redevance",NOW(),NOW()),
("DEFP","direction_etudes@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","chef_direction","Direction Études et Formation et Planification",NOW(),NOW()),
("Autres","direction_autres@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","chef_direction","Autres Directions",NOW(),NOW());

-- ===========================
-- USERS FPC : SERVICES
-- ===========================

-- Direction Financière
INSERT INTO users (name,email,password,entreprise,role,service,created_at,updated_at) VALUES
("Comptabilité","comptabilite@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","chef_service","Comptabilité",NOW(),NOW()),
("Trésorerie","tresorerie@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","chef_service","Trésorerie",NOW(),NOW());

-- Ressources Humaines et Services Généraux
INSERT INTO users (name,email,password,entreprise,role,service,created_at,updated_at) VALUES
("Ressources Humaines","ressources_humaines@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","chef_service","Ressources Humaines",NOW(),NOW()),
("Services Généraux","services_generaux@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","chef_service","Services Généraux",NOW(),NOW());

-- Coordination des Provinces
INSERT INTO users (name,email,password,entreprise,role,service,created_at,updated_at) VALUES
("Coordination","coordination@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","chef_service","Coordination",NOW(),NOW());

-- Promotion Culturelle
INSERT INTO users (name,email,password,entreprise,role,service,created_at,updated_at) VALUES
("Services de la Promotion Culturelle","promotion_culturelle@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","chef_service","Services de la Promotion Culturelle",NOW(),NOW()),
("Production et Animation Culturelle","production_culturelle@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","chef_service","Production et Animation Culturelle",NOW(),NOW());

-- Contrôle et Inspection
INSERT INTO users (name,email,password,entreprise,role,service,created_at,updated_at) VALUES
("Audit interne","audit_interne@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","chef_service","Audit interne",NOW(),NOW());

-- Mobilisation de la Redevance
INSERT INTO users (name,email,password,entreprise,role,service,created_at,updated_at) VALUES
("Taxation","taxation@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","chef_service","Taxation",NOW(),NOW());

-- Études, Planification et de la Formation
INSERT INTO users (name,email,password,entreprise,role,service,created_at,updated_at) VALUES
("Études","etudes@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","chef_service","Études",NOW(),NOW()),
("Planification","planification@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","chef_service","Planification",NOW(),NOW()),
("Formation","formation@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","chef_service","Formation",NOW(),NOW());

-- Autres
INSERT INTO users (name,email,password,entreprise,role,service,created_at,updated_at) VALUES
("Informatique","informatique@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","chef_service","Informatique",NOW(),NOW()),
("Juridique et Contentieux","juridique_contentieux@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","chef_service","Juridique et Contentieux",NOW(),NOW());

-- OPS1–4 dans Secrétariat DG
INSERT INTO users (name,email,password,entreprise,role,service,created_at,updated_at) VALUES
("OPS1","ops1@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","chef_service","Secrétariat",NOW(),NOW()),
("OPS2","ops2@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","agent","Secrétariat",NOW(),NOW()),
("OPS3","ops3@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","agent","Secrétariat",NOW(),NOW()),
("OPS4","ops4@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","agent","Secrétariat",NOW(),NOW());

-- Assistant DG et DGA
INSERT INTO users (name,email,password,entreprise,role,service,created_at,updated_at) VALUES
("Assistant DG","assistant.dg@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","chef_service","Assistant DG",NOW(),NOW()),
("Assistant DGA","assistant.dga@fpc.local","$2y$10$OuYFM1IER8nZ2vC5oR9B7uZ7HQLmoJ0W.mUhj7glAKocpGSiUu.0K","FPC","chef_service","Assistant DGA",NOW(),NOW());
