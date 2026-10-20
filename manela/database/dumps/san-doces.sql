-- MySQL dump 10.13  Distrib 8.0.45, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: san-doces
-- ------------------------------------------------------
-- Server version	8.0.45

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `san-doces`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `san-doces` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `san-doces`;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba','i:1;',1790886684),('laravel-cache-5c785c036466adea360111aa28563bfd556b5fba:timer','i:1790886684;',1790886684);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doces`
--

DROP TABLE IF EXISTS `doces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doces` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `categoria` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `preco` decimal(8,2) NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sobre` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `imagem` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_produto` bigint unsigned NOT NULL,
  `peso` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doces`
--

LOCK TABLES `doces` WRITE;
/*!40000 ALTER TABLE `doces` DISABLE KEYS */;
INSERT INTO `doces` VALUES (1,'Bolos',89.90,'Bolo de Morango','Massa fofinha com creme e morangos frescos.','Bolo artesanal de morango preparado sob encomenda.','assets/images/bolo-morango.jpg',0,0.00,'2026-09-22 20:06:05','2026-09-22 20:06:05'),(3,'Sobremesas',18.00,'Copo Chocolate com Morango','Camadas de creme, chocolate e morango.','Sobremesa individual montada no copo.','assets/images/copos-chocolate-morango.jpg',0,0.00,'2026-09-22 20:06:05','2026-09-22 20:06:05'),(5,'doce',18.00,'Morango cravejado','Brigadeiro de ninho, morango e casquinha de chocolate nobre branco e caramelo crocante','Um doce saboroso e muito delicioso feito exatamente para você!','https://instadelivery-public.nyc3.cdn.digitaloceanspaces.com/itens/17876905906a8dfe5e93028_75_75.jpeg',0,0.00,'2026-09-24 14:06:16','2026-09-24 14:06:16'),(6,'sobremesas',27.00,'kinder bueno','Mousse de ninho, creme de ninho, brownie , creme kinder e creme de chocolate (250ml)','muito gostoso','https://instadelivery-public.nyc3.cdn.digitaloceanspaces.com/itens/176836621469672086593a5_75_75.jpeg',0,0.00,'2026-09-24 14:25:18','2026-09-24 14:25:18'),(7,'sobremesas',27.00,'Copo brownie','Creme de chocolate, creme de ninho , brownie e morangos(250ml)','muito gostoso','https://instadelivery-public.nyc3.cdn.digitaloceanspaces.com/itens/176836609969672013e3506_75_75.jpeg',0,0.00,'2026-09-24 14:26:35','2026-09-24 14:26:35'),(8,'sobremesas',27.00,'copo ferrero','Mousse de chocolate, brownie , amendoim e creme de chocolate (250ml)','gostoso dms','https://instadelivery-public.nyc3.cdn.digitaloceanspaces.com/itens/1768366283696720cb62163_75_75.jpeg',0,0.00,'2026-09-24 14:28:59','2026-09-24 14:28:59'),(10,'bolos',24.00,'Fatia red velvet','Massa red velvet , brigadeiro de cream cheese e geleia de frutas vermelhas','muito bom','storage/produtos/4LrlmxQPlhMMBkoeX1zbqvwe0sVLH3AFxPNQUe3R.png',0,0.00,'2026-09-24 14:32:32','2026-09-24 14:32:32'),(11,'torta',22.00,'torta de limão','Massa artesanal assado , mousse de limão da fruta e chantilly','bom','https://instadelivery-public.nyc3.cdn.digitaloceanspaces.com/itens/17683671836967244fc8f81_75_75.jpeg',0,0.00,'2026-09-24 14:33:36','2026-09-24 14:33:36'),(12,'torta',20.00,'banoffe','Massa artesanal assada, doce de leite (Moça cozido, chantilly e canela','muito boa','https://instadelivery-public.nyc3.cdn.digitaloceanspaces.com/itens/17850185636a6538c3a3dfd_75_75.jpeg',0,0.00,'2026-09-24 15:08:29','2026-09-24 15:08:29'),(13,'torta',26.00,'torta holandesa','creme branco a base de chocolate branco , finalizado com ganache de chocolate','muito bom','storage/produtos/0WZ5w9uGFU9bRG1enLkB8x6TybyQMj2Ho73Aqjyl.png',0,0.00,'2026-09-24 15:10:28','2026-09-24 15:10:28'),(14,'doce',17.50,'caixa de brigadeiro misto','2 unidades de brigadeiro tradicional com granulado ao leite melken e 2 und de ninho com Nutella','muito bom','storage/produtos/8K7Nob0uprbDM1wKrTOGrH2lwYavNKWjcInt5P63.png',0,0.00,'2026-09-24 15:19:27','2026-09-24 15:19:27'),(15,'doce',17.50,'caixa de brigadeiro','4 und de brigadeiro tradicional com granulado ao leite melken','muito bom','storage/produtos/cZGsJsyYxDdiSg3dBetEyoJKkE0pHbaWoEaoL5bu.png',0,0.00,'2026-09-24 15:20:13','2026-09-24 15:20:13'),(16,'sobremesas',14.00,'pudim de leite condensado','Pudim extremamente cremoso com calda de caramelo','muito gostoso','storage/produtos/9oM9YcAKvIeXFJ8vEgmBgGGuTEL9DHONB66KIodb.png',0,0.00,'2026-09-24 15:21:12','2026-09-24 15:21:12'),(17,'doce',10.00,'brownie','Brownie , gotas de chocolate, nozes e doce de leite ( 1 und 80g aprox)','muito bom','storage/produtos/EiRK89WcqrHvIuuqsVvsHfZv45t99gGSAyuIfBVS.png',0,0.00,'2026-09-24 15:25:41','2026-09-24 15:25:41'),(18,'sobremesas',24.00,'Choux morango','Nossa incrível massa choux, recheada com creme lègére,finalizada com o nosso deleite perfeito e morangos','muito bom','storage/produtos/RnLZOCpMqN2lvlpdmSHbs2RFj5zNCP4xJhXEsFI0.png',0,0.00,'2026-09-24 15:26:16','2026-09-24 15:26:16');
/*!40000 ALTER TABLE `doces` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horarios_encomenda`
--

DROP TABLE IF EXISTS `horarios_encomenda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `horarios_encomenda` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dia` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `horario` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horarios_encomenda`
--

LOCK TABLES `horarios_encomenda` WRITE;
/*!40000 ALTER TABLE `horarios_encomenda` DISABLE KEYS */;
INSERT INTO `horarios_encomenda` VALUES (7,'Segunda','14:30 - 20:00',1,'2026-09-22 20:54:06','2026-09-22 20:54:06'),(8,'Terca','14:30 - 20:00',1,'2026-09-22 20:54:06','2026-09-22 20:54:06'),(9,'Quarta','14:30 - 20:00',1,'2026-09-22 20:54:06','2026-09-22 20:54:06'),(10,'Quinta','14:30 - 20:00',1,'2026-09-22 20:54:06','2026-09-22 20:54:06'),(11,'Sexta','14:30 - 21:00',1,'2026-09-22 20:54:06','2026-09-22 20:54:06'),(12,'Sabado','14:30 - 21:00',1,'2026-09-22 20:54:06','2026-09-22 20:54:06');
/*!40000 ALTER TABLE `horarios_encomenda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `horarios_funcionamento`
--

DROP TABLE IF EXISTS `horarios_funcionamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `horarios_funcionamento` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `dias` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `horario` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `observacao` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `horarios_funcionamento`
--

LOCK TABLES `horarios_funcionamento` WRITE;
/*!40000 ALTER TABLE `horarios_funcionamento` DISABLE KEYS */;
INSERT INTO `horarios_funcionamento` VALUES (1,'segunda - sexta','Das 14:30 as 23:30','Nossa loja funciona todos os dias para melhor atender voce!','2026-09-22 19:46:10','2026-09-22 19:47:57');
/*!40000 ALTER TABLE `horarios_funcionamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mensagens_contato`
--

DROP TABLE IF EXISTS `mensagens_contato`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mensagens_contato` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `whatsapp` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `assunto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mensagem` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `lida` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mensagens_contato`
--

LOCK TABLES `mensagens_contato` WRITE;
/*!40000 ALTER TABLE `mensagens_contato` DISABLE KEYS */;
INSERT INTO `mensagens_contato` VALUES (1,'williamberg Conceição Bispo','williamberg.senaidstatui@gmail.com','11994083005','Sugestão','coloque sucos na loja',0,'2026-09-22 19:58:14','2026-09-22 19:58:14'),(2,'williamberg Conceição Bispo','williamberg.senaidstatui@gmail.com','11994083005','Dúvida','onde eu pago?',0,'2026-09-22 20:57:12','2026-09-22 20:57:12');
/*!40000 ALTER TABLE `mensagens_contato` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_08_27_111643_create_usuario',1),(5,'2026_08_27_111658_create_token_usuario',1),(6,'2026_09_03_112421_create_personal_access_tokens_table',1),(7,'2026_09_08_183918_create_produto',2),(8,'2026_09_15_172632_add_is_admin_to_usuario_table',2),(9,'2026_09_22_130000_create_pedidos_table',2),(10,'2026_09_22_130100_add_telefone_to_usuario_table',2),(11,'2026_09_22_131000_create_horarios_funcionamento_table',3),(12,'2026_09_22_131100_create_horarios_encomenda_table',3),(13,'2026_09_22_131200_create_mensagens_contato_table',3),(14,'2026_09_22_131300_add_produto_to_pedidos_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `whatsapp` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_encomenda` date NOT NULL,
  `categoria` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `produto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observacao` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Em preparo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos`
--

LOCK TABLES `pedidos` WRITE;
/*!40000 ALTER TABLE `pedidos` DISABLE KEYS */;
INSERT INTO `pedidos` VALUES (1,'williamberg Conceição Bispo','williamberg.senaidstatui@gmail.com','11994083005','2027-11-25','Bolos',NULL,'gostaria de um bolo do homem aranha para a minha festa de casamento','Cancelado','2026-09-22 19:11:57','2026-09-24 21:35:19'),(2,'williamberg Conceição Bispo','williamberg.senaidstatui@gmail.com','11994083005','2026-11-22','Bolos','Bolo de Morango','bolo','Cancelado','2026-09-22 20:52:53','2026-09-24 21:35:18'),(3,'williamberg Conceição Bispo','williamberg.senaidstatui@gmail.com','11994083005','2026-11-11','Doces','Brigadeiro Gourmet','jhxjhwx','Cancelado','2026-09-22 21:19:10','2026-09-24 15:49:15'),(4,'williamberg Conceição Bispo','williamberg.senaidstatui@gmail.com','11994083005','2026-11-11','Doce','Morango cravejado','QUERO DINHEIRO VAI VAI VAI PROGRAMA RAPIDAO','Cancelado','2026-09-24 17:12:51','2026-09-24 21:35:15'),(5,'williamberg Conceição Bispo','williamberg.senaidstatui@gmail.com','11994083005','2028-12-12','Doce','pudim de leite condensado','dvsdv','Em preparo','2026-09-24 23:39:17','2026-09-24 23:39:17'),(6,'Lucas Rafael Torres de Camargo','williambergconceicao@gmail.com','11994083005','2027-07-12','Sobremesas','copo ferrero','udnund','Cancelado','2026-10-01 23:17:25','2026-10-01 23:23:09'),(7,'Williamberg Conceição Bispo','dashbrasilds@gmail.com','11994083005','2026-12-12','Doce','pudim de leite condensado','nknk','Em preparo','2026-10-01 23:27:43','2026-10-01 23:27:43'),(8,'Williamberg Conceição Bispo','dashbrasilds@gmail.com','11994083005','2026-12-12','Doce','pudim de leite condensado',NULL,'Em preparo','2026-10-01 23:30:24','2026-10-01 23:30:24');
/*!40000 ALTER TABLE `pedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (4,'App\\Models\\Usuario',1,'login','db9822c59dd9fc81ab2abc27a5d08f81d26a62ee19a0b8f3df273a5acda4ddb8','[\"*\"]','2026-10-01 23:23:09','2026-10-03 23:21:47','2026-10-01 23:21:47','2026-10-01 23:23:09');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('A7DNIGSX3s8QtoeFqlamgugGVt8rZ9SutLyjpjVm',NULL,'127.0.0.1','Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1','eyJfdG9rZW4iOiJZZllsYTFWcFluN3ZtblZKdjJxSlJSU2NZRjhqUnJQQ0ZVSUFjZUdOIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wcm9kdXRvcyIsInJvdXRlIjoic2FuLWRvY2VzLnByb2R1dG9zIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1791301986),('cSbtFudqlMzopGtF10OaKeSNJUkcQfi00x9eM8zU',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJTQ0pwWnRHNE9jZHlEMm1vQTJwOGlMT04yMHRGQXc3bUVnaXZJVkdPIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9lbmNvbWVuZGFzIiwicm91dGUiOiJzYW4tZG9jZXMuZW5jb21lbmRhcyJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1790886781),('iUL1Qz20qnmXOELJtWVP9A7CClbyMozKaokPgs5o',NULL,'127.0.0.1','Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1','eyJfdG9rZW4iOiIyZndYbWJtbEFNeUNjd3V5UHdXSHQxTzR3S0NDOG0wNEtubVdiNXozIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOiJzYW4tZG9jZXMuaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1791310956);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `token_usuario`
--

DROP TABLE IF EXISTS `token_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `token_usuario` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint unsigned NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valido_ate` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token_usuario_token_unique` (`token`),
  KEY `token_usuario_usuario_id_foreign` (`usuario_id`),
  CONSTRAINT `token_usuario_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `token_usuario`
--

LOCK TABLES `token_usuario` WRITE;
/*!40000 ALTER TABLE `token_usuario` DISABLE KEYS */;
/*!40000 ALTER TABLE `token_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senha` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_nascimento` date NOT NULL,
  `cpf` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'Administrador','(15) 99144-4740','admin@verify.com','e10adc3949ba59abbe56e057f20f883e','2000-01-01','00000000000','2026-09-22 19:18:23','2026-09-22 20:04:22',1);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'san-doces'
--

--
-- Dumping routines for database 'san-doces'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-10-06 15:44:14
