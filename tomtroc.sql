-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 22 nov. 2025 à 16:57
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `tomtroc`
--

-- --------------------------------------------------------

--
-- Structure de la table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `available` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `books`
--

INSERT INTO `books` (`id`, `user_id`, `title`, `author`, `image`, `description`, `available`, `created_at`, `updated_at`) VALUES
(9, 1, 'Le Petit Prince', 'Antoine de Saint-Exupéry', '/uploads/books/book_690da96208a8f.jpg', 'Publié en 1943, ce conte poétique et philosophique raconte l\'histoire d\'un jeune prince qui voyage de planète en planète à travers l\'univers. Sur Terre, il rencontre un aviateur échoué dans le désert du Sahara. À travers ses rencontres avec des personnages symboliques (un roi, un vaniteux, un businessman, un allumeur de réverbères), le récit explore des thèmes universels comme l\'amitié, l\'amour, la perte et le sens de la vie. La célèbre citation \"On ne voit bien qu\'avec le cœur. L\'essentiel est invisible pour les yeux\" résume la philosophie de cette œuvre intemporelle qui parle autant aux enfants qu\'aux adultes.', 0, '2025-11-07 09:10:10', '2025-11-07 09:10:16'),
(10, 1, 'Orgueil et Préjugés', 'Jane Austen', '/uploads/books/book_690da9963b6ed.jpg', 'Publié en 1813, ce chef-d\'œuvre de la littérature anglaise suit l\'histoire d\'Elizabeth Bennet, une jeune femme spirituelle et indépendante dans l\'Angleterre du début du XIXe siècle. Le roman explore sa relation tumultueuse avec le riche et hautain Mr. Darcy. À travers les malentendus, les préjugés sociaux et les questions de classe, Jane Austen dépeint brillamment les mœurs de son époque avec humour et ironie. C\'est une histoire intemporelle sur l\'amour, le mariage, la famille et la société, qui reste l\'un des romans romantiques les plus lus et adaptés au monde. Le titre fait référence aux deux défauts principaux des protagonistes : l\'orgueil de Darcy et les préjugés d\'Elizabeth.', 1, '2025-11-07 09:11:02', '2025-11-07 09:11:02'),
(11, 1, 'Cent ans de solitude', 'Gabriel García Márquez', '/uploads/books/book_690da9ed7964a.jpg', 'Publié en 1967, ce chef-d\'œuvre du réalisme magique raconte l\'histoire épique de la famille Buendía sur sept générations dans le village fictif de Macondo en Colombie. Le roman commence avec la fondation du village par José Arcadio Buendía et Úrsula Iguarán et suit les destins tragiques, amoureux et fantastiques de leurs descendants. García Márquez mêle brillamment le réel et le merveilleux, créant un univers où les tapis volants, la pluie qui dure quatre ans et l\'ascension au ciel sont aussi naturels que les naissances et les morts. C\'est une méditation profonde sur la solitude, le temps cyclique, l\'amour, la guerre et l\'histoire de l\'Amérique latine. Ce roman a valu à García Márquez le prix Nobel de littérature en 1982.', 1, '2025-11-07 09:12:29', '2025-11-21 17:18:55');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `book_id`, `content`, `is_read`, `created_at`) VALUES
(3, 5, 1, 11, 'Bonjour,\r\n\r\nJe suis intéressé par votre livre, serait-il possible d\'avoir un échange ?', 1, '2025-11-10 13:57:18'),
(4, 1, 5, NULL, 'Bonjour, \r\n\r\nMerci pour votre message, effectivement il est possible de vous l\'échanger, que livre pouvez vous me proposer ?', 1, '2025-11-10 13:58:10'),
(5, 1, 5, NULL, 'Bonjour, \r\n\r\nJe reviens vers vous pour avoir des nouvelles, le livre vous intéresse-t-il toujours ?', 1, '2025-11-14 09:36:56'),
(6, 5, 1, NULL, 'Bonjour,\r\n\r\nOui je suis toujours très intéressé! Désolé du vent j\'ai eu quelques soucis persos mais tout est bon! Quel livre pourrait vous intéresser ?', 1, '2025-11-21 15:42:24'),
(7, 1, 5, NULL, 'Bonjour,\r\n\r\nPas de soucis je comprends! Actuellement vous n\'avez aucun livre dans votre bibliothèque, lesquelles avez vous a disposition ?', 1, '2025-11-21 15:57:23'),
(8, 5, 1, NULL, 'Bonjour,\r\n\r\nJe vais les publier!', 1, '2025-11-21 17:04:29');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `pseudo` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `pseudo`, `email`, `password`, `photo`, `created_at`, `updated_at`) VALUES
(1, 'Koruno', 'jsampedropro@gmail.com', '$2y$12$Jx9kdaJ5EFI1Rq.ImK6y7e/zvsXTFSbkB/hxZNhZWxccgvzMjvfl6', '/uploads/profiles/profile_69207aac536c7.jpeg', '2025-11-06 11:05:37', '2025-11-21 15:43:56'),
(5, 'Jsampedro', 'jsampedro98@gmail.com', '$2y$12$roVmKqBdbS.ans1LtPO2gOdNbQiqdTihL.eWMk5ikz67mT.M6w1f6', '/uploads/profiles/profile_69208dbdcf619.png', '2025-11-08 14:43:17', '2025-11-21 17:05:17'),
(7, 'Admin', 'admin@tomtroc.com', '$2y$12$Q0.Qs1Twv8bS/VEz8FGH0ukxUpC4M/7HkjrNnivjUTaIPOnQzAX.a', NULL, '2025-11-22 16:53:04', '2025-11-22 16:53:04');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_available` (`available`),
  ADD KEY `idx_title` (`title`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `idx_sender_receiver` (`sender_id`,`receiver_id`),
  ADD KEY `idx_receiver_read` (`receiver_id`,`is_read`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pseudo` (`pseudo`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_pseudo` (`pseudo`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
