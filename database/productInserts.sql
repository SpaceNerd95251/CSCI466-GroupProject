-- resets products in all tables because of product foreign keys
-- and resets the AUTO_INCREMENT
DELETE FROM orderItems; 
DELETE FROM shoppingCart;
DELETE FROM orders; 

DELETE FROM products;
ALTER TABLE products AUTO_INCREMENT = 1;

-- All credits to amazon.com for the images, and the book descriptions are paraphrased from product listings 
-- This data is used exclusively for a course project and not for commercial use

-- since this is books we could add an author row if there is time
INSERT INTO products (name, description, price, stock, imageUrl) 
VALUES
('London is Falling', 'A Mysterious Death in a Gilded City', 14.99, 12, 'https://m.media-amazon.com/images/I/51oy7hf6odL.AC_SX250.jpg'),
('Yesteryear', 'A traditional American woman, suddenly wakes up in 1855', 14.99, 8, 'https://m.media-amazon.com/images/I/41Ta5l9chEL.AC_SX250.jpg'),
('American Men', 'A story about four men, that shows what it means to be a man in America', 30.00, 20, 'https://m.media-amazon.com/images/G/01/Books/ABR/BOTM2026/april26/Amen.jpeg'),
('The Keeper', 'A New York Times Bestseller by who some call the greatest crime novelist of our era', 13.99, 30, 'https://m.media-amazon.com/images/G/01/Books/ABR/BOTM2026/april26/kkepr.jpeg'),
('Mad Mabel', "A twisting tale of justice, redemption, and a woman who's not done breaking the rules", 29.00, 10, 'https://m.media-amazon.com/images/G/01/Books/ABR/BOTM2026/april26/mad.jpeg'),
('This Land is Your Land', 'A story about Gage as she travels the country', 14.99, 31, "https://m.media-amazon.com/images/I/51XOddhlptL.AC_SX250.jpg"),
('The Vast Enterprise', 'The history of Lewis and Clark', 16.99, 3, "https://m.media-amazon.com/images/I/416u3r1PpSL.AC_SX250.jpg"),
('Ghost of Sicily', "A true story about how the US government convinced the mafia to help stop the Nazis", 29.99, 41, "https://m.media-amazon.com/images/I/41XDPFi27ZL.AC_SX250.jpg"), 
('Apple: The First 50 Years', 'A historical account about the first 50 years of business at Apple, including full-color photographs.', 19.99, 11, "https://m.media-amazon.com/images/I/31UJihUB2+L.AC_SX250.jpg"), 
("Lost: Amelia Earhart's Mysterious Death", "A story of the tragic disappearance of Amelia Earhart", 13.99, 1, "https://m.media-amazon.com/images/I/41ipM0D8E1L.AC_SX250.jpg"), 
("Reproductive Wrongs", "A collection of seven short stories, telling how women were wrong through history", 9.00, 55, "https://m.media-amazon.com/images/I/41orbqK45vL.AC_SX250.jpg"), 
("The Coming Storm", "A look at the threat of the next great war and how to stop it", 27.99, 6, "https://m.media-amazon.com/images/I/31B2zroUg2L.AC_SX250.jpg"), 
("The Story of Stories", "Tells the story of storytelling throughout all of history", 31.00, 22, "https://m.media-amazon.com/images/I/41gruqVWo1L.AC_SX250.jpg"), 
("Truth and Consequence", "The memoir and story of whistleblower Daniel Ellsberg", 20.99, 4, "https://m.media-amazon.com/images/I/41klWoXhUZL.AC_SX250.jpg"), 
("The Feather Wars", "A history of bird conservation in the United States", 16.99, 19, "https://m.media-amazon.com/images/I/41DpCW4YJHL.AC_SX250.jpg"), 
("We Are the World (Cup)", "A history of the World Cup told through personal anecdotes", 14.99, 16, "https://m.media-amazon.com/images/I/51qmxoHk88L.AC_SX250.jpg"), 
("Neptune's Fortune", "The story of a billion dollar shipwreck off the coast of Colombia", 22.00, 13, "https://m.media-amazon.com/images/I/51vlvX3ipQL.AC_SX250.jpg"), 
("Fear and Fury", "A Historical analysis of the 1984 Bernhard Goetz subway shooting", 21.99, 61, "https://m.media-amazon.com/images/I/51PB5XZaSyL.AC_SX250.jpg"), 
("The Culting of America", "What makes a cult and why people are so fascinated by them", 35.00, 52, "https://m.media-amazon.com/images/I/81sB5Og3g9L._AC_UL320_.jpg"), 
("Powers and Thrones", "A telling of the history of the middle ages and how they relate to today", 21.99, 32, "https://m.media-amazon.com/images/I/91Eja+h3NxL._AC_UL320_.jpg"), 
("Duty, Honor, Country and Life", "A motivational piece on the values of the American People", 18.00, 0, "https://m.media-amazon.com/images/I/81diDR0CirL._AC_UL320_.jpg");

