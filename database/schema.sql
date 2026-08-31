-- =============================================
-- ELITE ESTATES — LUXURY REAL ESTATE DATABASE
-- =============================================

CREATE DATABASE IF NOT EXISTS real_estate CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE real_estate;

-- USERS
CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100)  NOT NULL,
    email      VARCHAR(150)  NOT NULL UNIQUE,
    password   VARCHAR(255)  NOT NULL,
    role       ENUM('user','admin') DEFAULT 'user',
    phone      VARCHAR(30)   DEFAULT NULL,
    created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

-- Admin account (email: officialabdulrehman310@gmail.com / password: admin123)
-- Hash generated with PHP password_hash('admin123', PASSWORD_BCRYPT) — verified with password_verify().
INSERT IGNORE INTO users (name, email, password, role) VALUES
('Administrator', 'officialabdulrehman310@gmail.com', '$2y$10$P/3EfXcCe7HQ2u/kJLhE.OSDJicKrdz9Eon2Sv8eWikvSlfW8Atda', 'admin');

-- PROPERTIES
CREATE TABLE IF NOT EXISTS properties (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    title        VARCHAR(255)   NOT NULL,
    location     VARCHAR(255)   DEFAULT NULL,
    price        DECIMAL(15,2)  NOT NULL DEFAULT 0,
    type         VARCHAR(50)    DEFAULT 'villa',
    beds         TINYINT        DEFAULT 0,
    baths        TINYINT        DEFAULT 0,
    sqft         INT            DEFAULT 0,
    garage       TINYINT        DEFAULT 0,
    year_built   SMALLINT       DEFAULT NULL,
    description  TEXT           DEFAULT NULL,
    img          VARCHAR(500)   DEFAULT NULL,
    status       VARCHAR(20)    DEFAULT 'active',
    featured     TINYINT(1)     DEFAULT 0,
    created_at   TIMESTAMP      DEFAULT CURRENT_TIMESTAMP
);

-- Sample properties
INSERT INTO properties (title,location,price,type,beds,baths,sqft,garage,year_built,description,img,status,featured) VALUES
('Villa Serenita','Amalfi Coast, Italy',12500000,'villa',6,7,8400,3,2019,'An extraordinary clifftop villa perched above the crystalline waters of the Amalfi Coast. Panoramic sea views from every room, a 25-meter infinity pool, private helipad, and a wine cellar carved directly into the ancient rock face.','https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=1200&q=80','active',1),
('Sky Penthouse 88','Manhattan, New York',28000000,'penthouse',5,6,6200,4,2022,'Occupying the entire 88th floor of One Billionaires Row, this extraordinary penthouse redefines urban luxury. Floor-to-ceiling glass panels offer 360-degree views of Manhattan iconic skyline.','https://images.unsplash.com/photo-1567767292278-a4f21aa2d36e?w=1200&q=80','active',1),
('Palm Crest Mansion','Palm Beach, Florida',9750000,'mansion',8,10,11200,6,2016,'Set behind private gates on one of Palm Beach most coveted addresses. Features a main residence, two guest cottages, tennis court, resort-style pool, and 250 feet of direct intracoastal waterfront.','https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=1200&q=80','active',1),
('The Riviera Estate','Nice, France',18200000,'estate',7,8,9600,5,2018,'A grand Belle Epoque estate on the Cote dAzur, meticulously restored. Set within 2.5 acres of manicured gardens with breathtaking Mediterranean views.','https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?w=1200&q=80','active',0),
('Malibu Bluffs Villa','Malibu, California',14500000,'villa',5,6,7100,3,2021,'Perched dramatically on the Malibu bluffs with unobstructed Pacific Ocean views. Open-plan living spaces flow to expansive terraces and a glass-edged infinity pool.','https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1200&q=80','active',0),
('Kensington Townhouse','London, United Kingdom',8900000,'townhouse',4,5,4800,2,2015,'An immaculately presented five-storey Georgian townhouse on one of Kensingtons most prestigious garden squares. Period features throughout with every modern luxury.','https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=1200&q=80','active',0),
('Desert Rose Compound','Dubai, UAE',22000000,'villa',9,11,15000,8,2023,'A palatial contemporary compound in Emirates Hills. Spans over one acre featuring seven en-suite bedrooms, a 50-metre lap pool, private gym, home cinema, and temperature-controlled garage.','https://images.unsplash.com/photo-1600566753376-12c8ab7fb75b?w=1200&q=80','active',0),
('Aspen Mountain Estate','Aspen, Colorado',31000000,'estate',10,12,18200,8,2020,'A truly once-in-a-generation ski estate with ski-in/ski-out access. The 18,200 sq ft residence features a private spa, 1,500-bottle wine cellar, indoor sports court, and 360-degree Rocky Mountain panoramas.','https://images.unsplash.com/photo-1510798831971-661eb04b3739?w=1200&q=80','active',0),
('Cote dAzur Retreat','Cannes, France',16500000,'villa',6,7,7800,4,2017,'A sun-drenched Provencal villa in the hills above Cannes. Centuries-old olive trees, a 20-metre pool, outdoor kitchen, and postcard views to the Bay of Cannes.','https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=1200&q=80','active',0);

-- INQUIRIES
CREATE TABLE IF NOT EXISTS inquiries (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT            NOT NULL,
    name        VARCHAR(100)   NOT NULL,
    email       VARCHAR(150)   NOT NULL,
    phone       VARCHAR(30)    DEFAULT NULL,
    message     TEXT           NOT NULL,
    status      VARCHAR(20)    DEFAULT 'new',
    created_at  TIMESTAMP      DEFAULT CURRENT_TIMESTAMP
);

-- FAVORITES
CREATE TABLE IF NOT EXISTS favorites (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    property_id INT NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_fav (user_id, property_id)
);

-- =============================================
-- ADMIN LOGIN: officialabdulrehman310@gmail.com / rehman123
-- =============================================
