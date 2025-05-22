-- MySQL dump 10.13  Distrib 8.0.32, for Linux (x86_64)
--
-- Host: localhost    Database: proyectotpv
-- ------------------------------------------------------
-- Server version	8.0.32

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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
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
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
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
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `categoria` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `id_empresa` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categorias_id_empresa_foreign` (`id_empresa`),
  CONSTRAINT `categorias_id_empresa_foreign` FOREIGN KEY (`id_empresa`) REFERENCES `empresas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (1,'Entrantes',1,1,'2025-04-02 15:02:06','2025-05-05 12:32:26',NULL),(2,'Postres',1,1,'2025-04-02 15:02:06','2025-04-02 15:02:06',NULL),(3,'Platos principales',1,1,'2025-04-03 11:07:24','2025-04-03 11:07:24',NULL),(4,'Bebidas',1,1,'2025-04-03 11:07:24','2025-04-03 11:07:24',NULL),(5,'Pizzas',1,1,'2025-04-03 11:07:24','2025-05-02 09:24:32',NULL),(6,'Sushi',1,2,NULL,NULL,NULL),(7,'Vinos',1,1,'2025-05-02 08:50:47','2025-05-02 09:38:30',NULL),(8,'Algo',1,1,'2025-05-02 16:27:17','2025-05-02 16:27:17','2025-05-02 16:27:20');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `razon_social` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cif` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion_fiscal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_empresa` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clientes_id_empresa_foreign` (`id_empresa`),
  CONSTRAINT `clientes_id_empresa_foreign` FOREIGN KEY (`id_empresa`) REFERENCES `empresas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (1,'Cliente Ejemplo','C12345678','Calle del Cliente 456','cliente@email.com',1,'2025-04-02 15:02:14','2025-05-05 15:16:05',NULL),(2,'Cliente Ejemplo 2','C87654321','Calle del Cliente 789','cliente2@email.com',1,'2025-04-03 11:13:50','2025-04-03 11:13:50',NULL),(3,'Cliente Ejemplo 3','C11223344','Calle del Cliente 101','cliente3@email.com',1,'2025-04-03 11:13:50','2025-04-03 11:13:50',NULL),(4,'Cliente Ejemplo 4','C99887766','Calle del Cliente 202','cliente4@email.com',1,'2025-04-03 11:13:50','2025-04-03 11:13:50',NULL),(5,'dsgf','d23432123','gffdg','dfggfd@sdg.com',1,'2025-04-24 12:48:46','2025-04-24 12:48:46',NULL),(6,'Manolo SL','C87321230','c/ fiscal','manolo@gmail.com',1,'2025-04-24 13:04:35','2025-04-24 13:04:35',NULL),(7,'Francisco SL','L98765432','c/ algo','fran@gmail.com',1,'2025-04-24 16:03:09','2025-04-24 16:03:09',NULL);
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empleado_restaurante`
--

DROP TABLE IF EXISTS `empleado_restaurante`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empleado_restaurante` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_empleado` bigint unsigned NOT NULL,
  `id_restaurante` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `emp_rest_id_empleado_foreign` (`id_empleado`),
  KEY `emp_rest_id_restaurante_foreign` (`id_restaurante`),
  CONSTRAINT `emp_rest_id_empleado_foreign` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id`) ON DELETE CASCADE,
  CONSTRAINT `emp_rest_id_restaurante_foreign` FOREIGN KEY (`id_restaurante`) REFERENCES `restaurantes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empleado_restaurante`
--

LOCK TABLES `empleado_restaurante` WRITE;
/*!40000 ALTER TABLE `empleado_restaurante` DISABLE KEYS */;
INSERT INTO `empleado_restaurante` VALUES (1,1,1,'2025-04-02 15:02:01','2025-04-02 15:02:01'),(2,2,2,'2025-04-03 11:11:09','2025-04-03 11:11:09'),(3,3,3,'2025-04-03 11:11:09','2025-04-03 11:11:09'),(4,4,4,'2025-04-03 11:11:09','2025-04-03 11:11:09'),(18,19,1,NULL,NULL),(19,20,1,NULL,NULL),(20,21,1,NULL,NULL),(22,23,1,NULL,NULL),(23,24,1,NULL,NULL),(24,25,1,NULL,NULL),(25,26,1,NULL,NULL),(26,27,1,NULL,NULL),(27,28,1,NULL,NULL),(28,29,1,NULL,NULL),(29,30,1,NULL,NULL),(30,31,1,NULL,NULL),(31,32,1,NULL,NULL),(32,33,1,NULL,NULL),(33,34,1,NULL,NULL),(34,1,5,'2025-05-06 09:34:47','2025-05-06 09:34:47'),(35,1,6,'2025-05-06 09:52:51','2025-05-06 09:52:51');
/*!40000 ALTER TABLE `empleado_restaurante` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empleados`
--

DROP TABLE IF EXISTS `empleados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empleados` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pin` int NOT NULL,
  `id_empresa` bigint unsigned NOT NULL,
  `id_rol` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `empleados_id_empresa_foreign` (`id_empresa`),
  KEY `empleados_id_rol_foreign` (`id_rol`),
  CONSTRAINT `empleados_id_empresa_foreign` FOREIGN KEY (`id_empresa`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `empleados_id_rol_foreign` FOREIGN KEY (`id_rol`) REFERENCES `rols` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empleados`
--

LOCK TABLES `empleados` WRITE;
/*!40000 ALTER TABLE `empleados` DISABLE KEYS */;
INSERT INTO `empleados` VALUES (1,'Juan Pérez',1234,1,1,'2025-04-02 15:01:48','2025-04-02 15:01:48',NULL),(2,'María López',5678,2,2,'2025-04-03 10:55:39','2025-04-03 10:55:39',NULL),(3,'Carlos Rodríguez',9101,3,3,'2025-04-03 10:55:39','2025-04-03 10:55:39',NULL),(4,'Lucía Fernández',1121,4,4,'2025-04-03 10:55:39','2025-04-03 10:55:39',NULL),(5,'Pedro Sánchez',3141,5,5,'2025-04-03 10:55:39','2025-04-03 10:55:39',NULL),(19,'Jose Luis',1111,1,1,'2025-04-09 07:16:58','2025-04-29 14:15:48',NULL),(20,'Vicente',1111,1,4,'2025-04-10 07:29:28','2025-04-10 07:29:28',NULL),(21,'Dorotea',1111,1,4,'2025-04-10 07:29:41','2025-04-10 07:29:41',NULL),(22,'Gorka',1111,1,4,'2025-04-10 07:29:49','2025-04-10 07:29:49',NULL),(23,'Concha',1111,1,4,'2025-04-10 07:29:56','2025-04-10 07:29:56',NULL),(24,'Andrés',1111,1,4,'2025-04-10 07:30:03','2025-04-10 07:30:03',NULL),(25,'Gerard',2222,1,4,'2025-04-10 07:30:17','2025-04-10 07:30:17',NULL),(26,'Ana',2222,1,4,'2025-04-10 07:30:24','2025-04-10 07:30:24',NULL),(27,'Simon',2222,1,4,'2025-04-10 07:30:41','2025-04-10 07:30:41',NULL),(28,'Eva',2222,1,4,'2025-04-10 07:30:47','2025-04-10 07:30:47',NULL),(29,'Hugo',2222,1,4,'2025-04-10 07:30:51','2025-04-29 14:34:53','2025-04-29 14:34:53'),(30,'Ivan',2222,1,4,'2025-04-10 07:30:56','2025-04-10 07:30:56',NULL),(31,'Hermenegildo Martinez Pérez',2222,1,8,'2025-04-10 07:36:14','2025-05-09 10:49:55',NULL),(32,'Jose Manuel',1111,1,1,'2025-04-29 14:01:00','2025-04-29 14:32:48','2025-04-29 14:32:48'),(33,'Pepito',1111,1,6,'2025-04-29 14:35:46','2025-05-09 09:47:28',NULL),(34,'Jose',1111,1,1,'2025-04-29 14:36:16','2025-04-29 14:36:28','2025-04-29 14:36:28');
/*!40000 ALTER TABLE `empleados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empresas`
--

DROP TABLE IF EXISTS `empresas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empresas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion_fiscal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CIF` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `razon_social` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contrasenya` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empresas`
--

LOCK TABLES `empresas` WRITE;
/*!40000 ALTER TABLE `empresas` DISABLE KEYS */;
INSERT INTO `empresas` VALUES (1,'Restaurante El Buen Sabor','Calle Falsa 123','B12345678','El Buen Sabor S.L.','600123456','contacto@buen-sabor.com','password123','2025-04-02 14:58:51','2025-04-02 14:58:51',NULL),(2,'Restaurante La Esquina','Avenida Siempre Viva 742','B98765432','La Esquina S.L.','601234567','contacto@laesquina.com','clave456','2025-04-03 10:55:39','2025-04-03 10:55:39',NULL),(3,'Café Central','Plaza Mayor 5','B11223344','Café Central S.A.','602345678','info@cafecentral.com','clave789','2025-04-03 10:55:39','2025-04-03 10:55:39',NULL),(4,'Pizzería Napoli','Calle Italia 99','B55667788','Napoli Pizzas','603456789','napoli@pizzas.com','clave123','2025-04-03 10:55:39','2025-04-03 10:55:39',NULL),(5,'Hamburguesas Express','Carretera Nacional 22','B22334455','Express Burgers','604567890','contacto@expressburgers.com','clave202','2025-04-03 10:55:39','2025-04-03 10:55:39',NULL);
/*!40000 ALTER TABLE `empresas` ENABLE KEYS */;
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
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
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
  `attempts` tinyint unsigned NOT NULL,
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
-- Table structure for table `lineas_pedido`
--

DROP TABLE IF EXISTS `lineas_pedido`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lineas_pedido` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_pedido` bigint unsigned NOT NULL,
  `id_producto` bigint unsigned NOT NULL,
  `cantidad` int NOT NULL,
  `precio` decimal(8,2) NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `observaciones` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lineas_pedido_id_pedido_foreign` (`id_pedido`),
  KEY `lineas_pedido_id_producto_foreign` (`id_producto`),
  CONSTRAINT `lineas_pedido_id_pedido_foreign` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lineas_pedido_id_producto_foreign` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=217 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lineas_pedido`
--

LOCK TABLES `lineas_pedido` WRITE;
/*!40000 ALTER TABLE `lineas_pedido` DISABLE KEYS */;
INSERT INTO `lineas_pedido` VALUES (3,3,3,2,9.00,'Hamburguesa Clásica','Sin pepinillos','En preparación','2025-04-03 11:07:24','2025-04-03 11:07:24'),(4,4,4,1,4.00,'Refresco Cola','Sin hielo','Listo','2025-04-03 11:07:24','2025-04-03 11:07:24'),(5,5,5,3,13.00,'Pizza Margarita','Extra queso','Pendiente','2025-04-03 11:07:24','2025-04-03 11:07:24'),(49,6,3,3,9.00,'Hamburguesa Clásica',NULL,'Pendiente','2025-04-16 13:51:46','2025-04-17 13:35:13'),(57,7,5,1,13.00,'Pizza Margarita',NULL,'Pendiente','2025-04-17 10:35:58','2025-04-17 13:49:58'),(59,7,15,2,6.50,'Bravas',NULL,'Pendiente','2025-04-17 13:33:58','2025-04-17 13:49:38'),(64,7,4,2,4.00,'Refresco Cola',NULL,'Pendiente','2025-04-17 13:35:34','2025-04-17 13:37:28'),(72,7,3,1,9.00,'Hamburguesa Clásica',NULL,'Pendiente','2025-04-17 14:16:32','2025-04-17 14:16:32'),(78,6,15,3,6.50,'Bravas',NULL,'Pendiente','2025-04-23 11:11:35','2025-04-23 13:54:32'),(79,8,3,2,9.00,'Hamburguesa Clásica',NULL,'Pendiente','2025-04-23 11:11:52','2025-04-23 15:44:05'),(92,6,5,1,6.55,'Pizza Margarita',NULL,'Pendiente','2025-04-23 11:14:18','2025-04-23 11:14:18'),(105,6,4,1,4.00,'Refresco Cola',NULL,'Pendiente','2025-04-23 11:49:17','2025-04-23 11:49:17'),(108,1,3,2,9.00,'Hamburguesa Clásica',NULL,'Pendiente','2025-04-23 12:55:15','2025-04-23 13:54:01'),(109,8,5,3,6.55,'Pizza Margarita',NULL,'Pendiente','2025-04-23 12:56:07','2025-04-23 13:27:36'),(121,1,5,1,6.55,'Pizza Margarita',NULL,'Pendiente','2025-04-23 13:22:23','2025-04-23 13:22:23'),(125,1,15,1,6.50,'Bravas',NULL,'Pendiente','2025-04-23 13:27:02','2025-04-23 13:27:02'),(129,6,2,1,5.99,'Tarta de Queso',NULL,'Pendiente','2025-04-23 13:54:17','2025-04-23 13:54:17'),(130,8,15,1,6.50,'Bravas',NULL,'Pendiente','2025-04-23 13:54:37','2025-04-23 13:54:37'),(132,1,4,1,4.00,'Refresco Cola',NULL,'Pendiente','2025-04-23 15:42:54','2025-04-23 15:42:54'),(133,9,15,3,6.50,'Bravas',NULL,'Pendiente','2025-04-24 08:32:42','2025-04-24 09:19:27'),(134,9,5,5,6.55,'Pizza Margarita',NULL,'Pendiente','2025-04-24 08:32:44','2025-04-24 09:20:06'),(135,9,4,2,4.00,'Refresco Cola',NULL,'Pendiente','2025-04-24 08:32:46','2025-04-24 09:20:40'),(136,10,5,6,6.55,'Pizza Margarita',NULL,'Pendiente','2025-04-24 08:33:00','2025-04-24 17:42:12'),(137,10,4,4,4.00,'Refresco Cola',NULL,'Pendiente','2025-04-24 08:33:01','2025-04-24 09:18:48'),(138,10,3,2,9.00,'Hamburguesa Clásica',NULL,'Pendiente','2025-04-24 08:33:02','2025-04-24 15:47:20'),(139,10,2,2,5.99,'Tarta de Queso',NULL,'Pendiente','2025-04-24 08:33:03','2025-04-24 17:41:09'),(140,11,15,1,6.50,'Bravas',NULL,'Pendiente','2025-04-24 09:19:04','2025-04-25 08:50:55'),(141,11,5,1,6.55,'Pizza Margarita',NULL,'Pendiente','2025-04-24 09:19:08','2025-04-25 08:50:51'),(142,11,3,2,9.00,'Hamburguesa Clásica',NULL,'Pendiente','2025-04-24 17:41:40','2025-04-25 08:51:02'),(143,11,4,4,4.00,'Refresco Cola',NULL,'Pendiente','2025-04-25 08:43:22','2025-04-25 08:51:05'),(145,11,2,1,5.99,'Tarta de Queso',NULL,'Pendiente','2025-04-25 08:44:04','2025-04-25 08:44:04'),(148,13,5,1,6.55,'Pizza Margarita',NULL,'Pendiente','2025-04-25 08:47:52','2025-04-25 08:47:52'),(149,13,15,1,6.50,'Bravas',NULL,'Pendiente','2025-04-25 08:47:53','2025-04-25 08:47:53'),(150,13,3,1,9.00,'Hamburguesa Clásica',NULL,'Pendiente','2025-04-25 08:47:54','2025-04-25 08:47:54'),(151,11,1,1,9.95,'Ensalada César',NULL,'Pendiente','2025-04-25 08:48:04','2025-04-25 08:48:04'),(152,14,5,2,6.55,'Pizza Margarita',NULL,'Pendiente','2025-04-25 08:48:11','2025-04-25 16:34:41'),(153,14,4,1,4.00,'Refresco Cola',NULL,'Pendiente','2025-04-25 08:48:12','2025-04-25 08:48:12'),(155,15,5,1,6.55,'Pizza Margarita',NULL,'Pendiente','2025-04-25 13:07:09','2025-04-25 13:07:09'),(156,16,5,2,6.55,'Pizza Margarita',NULL,'Pendiente','2025-04-25 13:07:27','2025-04-25 13:07:27'),(157,16,2,1,5.99,'Tarta de Queso',NULL,'Pendiente','2025-04-25 17:16:24','2025-04-25 17:16:24'),(158,16,4,2,4.00,'Refresco Cola',NULL,'Pendiente','2025-04-25 17:16:26','2025-04-25 17:16:27'),(159,17,5,2,6.55,'Pizza Margarita',NULL,'Pendiente','2025-04-30 11:45:46','2025-04-30 11:45:47'),(160,17,19,1,3.55,'Croquetas',NULL,'Pendiente','2025-04-30 13:35:34','2025-04-30 13:36:19'),(161,18,3,1,9.50,'Hamburguesa Clásica',NULL,'Pendiente','2025-05-02 13:09:42','2025-05-02 13:09:42'),(162,19,15,2,6.50,'Bravas',NULL,'Pendiente','2025-05-05 16:10:10','2025-05-06 08:43:07'),(163,19,19,2,3.55,'Croquetas',NULL,'Pendiente','2025-05-05 16:10:13','2025-05-05 16:40:58'),(164,19,4,1,2.50,'Refresco Cola',NULL,'Pendiente','2025-05-05 17:32:48','2025-05-05 17:32:48'),(165,20,5,1,6.55,'Pizza Margarita',NULL,'Pendiente','2025-05-06 16:19:23','2025-05-06 16:19:23'),(166,21,3,2,9.55,'Hamburguesa Clásica',NULL,'Pendiente','2025-05-06 16:22:34','2025-05-06 16:22:35'),(167,21,4,2,2.50,'Refresco Cola',NULL,'Pendiente','2025-05-06 16:22:35','2025-05-06 16:24:39'),(168,21,5,1,6.55,'Pizza Margarita',NULL,'Pendiente','2025-05-06 17:07:25','2025-05-06 17:07:25'),(169,22,3,2,9.55,'Hamburguesa Clásica',NULL,'Pendiente','2025-05-07 09:20:16','2025-05-07 09:20:16'),(170,22,4,1,2.50,'Refresco Cola',NULL,'Pendiente','2025-05-07 09:20:18','2025-05-07 09:20:18'),(171,23,3,2,9.55,'Hamburguesa Clásica',NULL,'Pendiente','2025-05-07 09:21:59','2025-05-07 09:22:00'),(172,23,5,2,6.55,'Pizza Margarita',NULL,'Pendiente','2025-05-07 09:22:00','2025-05-07 09:22:01'),(173,23,4,1,2.50,'Refresco Cola',NULL,'Pendiente','2025-05-07 09:22:01','2025-05-07 09:22:01'),(174,24,3,2,9.55,'Hamburguesa Clásica',NULL,'Pendiente','2025-05-07 09:23:48','2025-05-07 09:23:48'),(175,25,3,2,9.55,'Hamburguesa Clásica',NULL,'Pendiente','2025-05-07 09:26:48','2025-05-07 09:26:48'),(176,25,2,2,5.99,'Tarta de Queso',NULL,'Pendiente','2025-05-07 09:26:49','2025-05-07 09:26:49'),(177,25,4,2,2.50,'Refresco Cola',NULL,'Pendiente','2025-05-07 09:26:50','2025-05-07 09:26:50'),(178,26,5,2,6.55,'Pizza Margarita',NULL,'Pendiente','2025-05-07 09:50:34','2025-05-07 09:50:34'),(179,26,4,1,2.50,'Refresco Cola',NULL,'Pendiente','2025-05-07 09:50:35','2025-05-07 09:50:35'),(180,27,5,2,6.55,'Pizza Margarita',NULL,'Pendiente','2025-05-07 09:57:50','2025-05-07 09:57:52'),(181,28,5,2,6.55,'Pizza Margarita',NULL,'Pendiente','2025-05-07 11:04:52','2025-05-07 11:04:52'),(182,28,3,2,9.55,'Hamburguesa Clásica',NULL,'Pendiente','2025-05-07 11:04:53','2025-05-07 11:04:53'),(183,28,2,2,5.99,'Tarta de Queso',NULL,'Pendiente','2025-05-07 11:04:53','2025-05-07 11:04:54'),(184,28,4,2,2.50,'Refresco Cola',NULL,'Pendiente','2025-05-07 11:04:54','2025-05-07 11:04:56'),(185,28,19,1,3.55,'Croquetas',NULL,'Pendiente','2025-05-07 11:04:55','2025-05-07 11:04:55'),(186,29,5,2,6.55,'Pizza Margarita',NULL,'Pendiente','2025-05-07 11:06:36','2025-05-07 11:06:36'),(187,29,4,2,2.50,'Refresco Cola',NULL,'Pendiente','2025-05-07 11:06:37','2025-05-07 11:06:38'),(188,29,15,1,6.50,'Bravas',NULL,'Pendiente','2025-05-07 11:06:40','2025-05-07 11:06:40'),(189,30,4,1,2.50,'Refresco Cola',NULL,'Pendiente','2025-05-07 11:25:15','2025-05-07 11:25:15'),(190,30,3,1,9.55,'Hamburguesa Clásica',NULL,'Pendiente','2025-05-07 11:25:16','2025-05-07 11:25:16'),(191,31,5,2,6.55,'Pizza Margarita',NULL,'Pendiente','2025-05-07 11:29:12','2025-05-07 11:29:12'),(192,31,4,1,2.50,'Refresco Cola',NULL,'Pendiente','2025-05-07 11:29:12','2025-05-07 11:29:12'),(193,31,19,1,3.55,'Croquetas',NULL,'Pendiente','2025-05-07 11:29:14','2025-05-07 11:29:14'),(194,32,5,1,6.55,'Pizza Margarita',NULL,'Pendiente','2025-05-07 11:33:57','2025-05-07 11:33:57'),(195,32,2,1,5.99,'Tarta de Queso',NULL,'Pendiente','2025-05-07 11:33:58','2025-05-07 11:33:58'),(196,32,19,1,3.55,'Croquetas',NULL,'Pendiente','2025-05-07 11:33:59','2025-05-07 11:33:59'),(197,32,4,1,2.50,'Refresco Cola',NULL,'Pendiente','2025-05-07 11:33:59','2025-05-07 11:33:59'),(198,34,4,1,2.50,'Refresco Cola',NULL,'Pendiente','2025-05-07 15:33:40','2025-05-07 15:33:40'),(199,34,5,1,6.55,'Pizza Margarita',NULL,'Pendiente','2025-05-07 15:33:41','2025-05-07 15:33:41'),(200,35,4,1,2.50,'Refresco Cola',NULL,'Pendiente','2025-05-08 09:51:24','2025-05-08 09:51:24'),(201,35,15,1,6.50,'Bravas',NULL,'Pendiente','2025-05-08 10:21:02','2025-05-08 10:21:02'),(202,35,26,4,11.50,'Hamburguesa queso y bacon',NULL,'Pendiente','2025-05-08 10:32:16','2025-05-08 10:45:00'),(203,36,2,1,5.99,'Tarta de Queso',NULL,'Pendiente','2025-05-08 16:57:40','2025-05-08 16:57:40'),(204,37,4,1,2.50,'Refresco Cola',NULL,'Pendiente','2025-05-09 08:36:32','2025-05-09 08:36:32'),(205,38,5,1,6.55,'Pizza Margarita',NULL,'Pendiente','2025-05-09 08:56:40','2025-05-09 08:56:40'),(211,38,15,1,6.50,'Bravas',NULL,'Pendiente','2025-05-09 10:52:57','2025-05-09 10:52:57'),(214,38,1,1,9.90,'Ensalada César',NULL,'Pendiente','2025-05-09 11:19:00','2025-05-09 11:19:00'),(215,39,1,1,9.90,'Ensalada César',NULL,'Pendiente','2025-05-09 11:19:47','2025-05-09 11:19:47'),(216,39,19,1,3.55,'Croquetas',NULL,'Pendiente','2025-05-09 11:21:04','2025-05-09 11:21:04');
/*!40000 ALTER TABLE `lineas_pedido` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mesas`
--

DROP TABLE IF EXISTS `mesas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mesas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mesa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `id_ubicacion` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `pos_x` int unsigned NOT NULL DEFAULT '0',
  `pos_y` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mesas_id_ubicacion_foreign` (`id_ubicacion`),
  CONSTRAINT `mesas_id_ubicacion_foreign` FOREIGN KEY (`id_ubicacion`) REFERENCES `ubicaciones` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mesas`
--

LOCK TABLES `mesas` WRITE;
/*!40000 ALTER TABLE `mesas` DISABLE KEYS */;
INSERT INTO `mesas` VALUES (1,'Mesa 1',1,1,'2025-04-02 15:02:21','2025-05-08 16:37:29',NULL,2,2),(2,'Mesa 2',1,1,'2025-04-02 15:02:21','2025-05-09 11:19:31',NULL,4,2),(3,'Mesa 3',1,2,'2025-04-03 11:02:07','2025-05-09 11:19:25',NULL,6,1),(4,'Mesa 4',1,3,'2025-04-03 11:02:07','2025-04-03 11:02:07',NULL,2,2),(5,'Mesa 5',1,4,'2025-04-03 11:02:07','2025-04-03 11:02:07',NULL,3,1),(6,'Mesa 5',1,2,NULL,'2025-05-07 17:10:27',NULL,2,1),(7,'Mesa 12',1,1,'2025-05-06 09:12:04','2025-05-08 16:37:31',NULL,3,0),(8,'Mesa 13',1,2,'2025-05-07 15:51:52','2025-05-09 11:19:27',NULL,4,3);
/*!40000 ALTER TABLE `mesas` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (4,'2025_04_02_131814_tablas',1),(5,'2025_04_02_143435_create_personal_access_tokens_table',1),(6,'2025_04_07_074746_iva_productos',2),(7,'0001_01_01_000000_create_users_table',3),(8,'0001_01_01_000001_create_cache_table',3),(9,'0001_01_01_000002_create_jobs_table',3),(10,'2025_04_23_141252_soft_deletes',4),(11,'2025_05_07_114307_add_position_to_mesas_table',5);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pagos`
--

DROP TABLE IF EXISTS `pagos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pagos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` double NOT NULL,
  `fecha` date NOT NULL,
  `id_pedido` bigint unsigned NOT NULL,
  `id_cliente` bigint unsigned DEFAULT NULL,
  `razon_social` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `CIF` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `n_factura` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion_fiscal` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pagos_id_pedido_foreign` (`id_pedido`),
  KEY `pagos_id_cliente_foreign` (`id_cliente`),
  CONSTRAINT `pagos_id_cliente_foreign` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id`),
  CONSTRAINT `pagos_id_pedido_foreign` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagos`
--

LOCK TABLES `pagos` WRITE;
/*!40000 ALTER TABLE `pagos` DISABLE KEYS */;
INSERT INTO `pagos` VALUES (1,'Tarjeta',15.98,'2025-04-02',1,1,'Cliente Ejemplo','C12345678','F123456','cliente@email.com','Calle del Cliente 456','2025-04-02 15:02:36','2025-04-02 15:02:36',NULL),(2,'Efectivo',20,'2025-04-03',2,1,'Cliente Ejemplo','C12345678','F789101','cliente@email.com','Calle del Cliente 456','2025-04-03 11:14:39','2025-04-03 11:14:39',NULL),(3,'Tarjeta',15.5,'2025-04-03',3,2,'Empresa X','C87654321','F112131','empresa@email.com','Calle Empresa 789','2025-04-03 11:14:39','2025-04-03 11:14:39',NULL),(37,'Efectivo',10,'2025-04-23',1,1,'Cliente Ejemplo','C12345678','4/25','cliente@email.com','Calle del Cliente 456','2025-04-23 09:10:56','2025-04-23 09:10:56',NULL),(38,'Efectivo',10,'2025-04-23',1,1,'Cliente Ejemplo','C12345678','5/25','cliente@email.com','Calle del Cliente 456','2025-04-23 10:56:17','2025-04-23 10:56:17',NULL),(39,'Efectivo',10,'2025-04-23',1,1,'Cliente Ejemplo','C12345678','6/25','cliente@email.com','Calle del Cliente 456','2025-04-23 10:59:25','2025-04-23 10:59:25',NULL),(40,'Efectivo',10,'2025-04-23',1,1,'Cliente Ejemplo','C12345678','7/25','cliente@email.com','Calle del Cliente 456','2025-04-23 14:54:30','2025-04-23 14:54:30',NULL),(41,'Targeta',10,'2025-04-23',1,1,NULL,'1234','8/25',NULL,NULL,'2025-04-23 15:03:17','2025-04-23 15:03:17','2025-04-24 07:06:31'),(51,'Tarjeta',59.64,'2025-04-24',10,1,'Cliente Ejemplo','C12345678','8/25','cliente@email.com','Calle del Cliente 456','2025-04-24 15:57:58','2025-04-24 15:57:58',NULL),(52,'Tarjeta',59.64,'2025-04-24',10,7,'Francisco SL','L98765432','9/25','fran@gmail.com','c/ algo','2025-04-24 16:03:09','2025-04-24 16:03:09',NULL),(53,'Tarjeta',62.99,'2025-04-25',11,1,'Cliente Ejemplo','C12345678','10/25','cliente@email.com','Calle del Cliente 456','2025-04-25 08:51:47','2025-04-25 08:51:47',NULL),(71,'Efectivo',20,'2025-04-25',13,NULL,NULL,NULL,NULL,NULL,NULL,'2025-04-25 11:48:14','2025-04-25 11:48:14',NULL),(72,'Efectivo',2.05,'2025-04-25',13,NULL,NULL,NULL,NULL,NULL,NULL,'2025-04-25 11:48:14','2025-04-25 11:48:14',NULL),(73,'Efectivo',22.05,'2025-04-25',13,NULL,NULL,NULL,NULL,NULL,NULL,'2025-04-25 11:49:26','2025-04-25 11:49:26',NULL),(74,'Efectivo',22.05,'2025-04-25',13,NULL,NULL,NULL,NULL,NULL,NULL,'2025-04-25 11:50:09','2025-04-25 11:50:09',NULL),(75,'Efectivo',15,'2025-04-25',13,NULL,NULL,NULL,NULL,NULL,NULL,'2025-04-25 11:57:31','2025-04-25 11:57:31',NULL),(76,'Efectivo',7.05,'2025-04-25',13,NULL,NULL,NULL,NULL,NULL,NULL,'2025-04-25 11:57:31','2025-04-25 11:57:31',NULL),(77,'Tarjeta',6.55,'2025-04-25',15,NULL,NULL,NULL,NULL,NULL,NULL,'2025-04-25 13:07:19','2025-04-25 13:07:19',NULL),(78,'Tarjeta',27.09,'2025-04-25',16,NULL,NULL,NULL,NULL,NULL,NULL,'2025-04-25 17:16:40','2025-04-25 17:16:40',NULL),(79,'Tarjeta',13.1,'2025-04-30',17,1,'Cliente Ejemplo','C12345678','11/25','cliente@email.com','Calle del Cliente 456','2025-04-30 11:46:15','2025-04-30 11:46:15',NULL),(80,'Tarjeta',13.1,'2025-04-30',17,1,'Cliente Ejemplo','C12345678','12/25','cliente@email.com','Calle del Cliente 456','2025-04-30 11:46:18','2025-04-30 11:46:18',NULL),(81,'Tarjeta',13.1,'2025-04-30',17,1,'Cliente Ejemplo','C12345678','13/25','cliente@email.com','Calle del Cliente 456','2025-04-30 11:49:30','2025-04-30 11:49:30',NULL),(82,'Tarjeta',6.55,'2025-05-06',20,NULL,NULL,NULL,NULL,NULL,NULL,'2025-05-06 16:22:14','2025-05-06 16:22:14',NULL),(83,'Tarjeta',30.65,'2025-05-07',21,1,'Cliente Ejemplo','C12345678','15/25','cliente@email.com','Calle del Cliente 456','2025-05-07 08:42:28','2025-05-07 09:08:12',NULL),(84,'Tarjeta',21.6,'2025-05-07',22,1,'Cliente Ejemplo','C12345678','15/25','cliente@email.com','Calle del Cliente 456','2025-05-07 09:20:26','2025-05-07 09:20:32',NULL),(85,'Tarjeta',21.6,'2025-05-07',22,1,'Cliente Ejemplo','C12345678','16/25','cliente@email.com','Calle del Cliente 456','2025-05-07 09:20:32','2025-05-07 09:20:32',NULL),(86,'Tarjeta',34.7,'2025-05-07',23,NULL,NULL,NULL,NULL,NULL,NULL,'2025-05-07 09:22:08','2025-05-07 09:22:08',NULL),(87,'Tarjeta',34.7,'2025-05-07',23,2,'Cliente Ejemplo 2','C87654321','17/25','cliente2@email.com','Calle del Cliente 789','2025-05-07 09:22:13','2025-05-07 09:22:13',NULL),(88,'Efectivo',19.1,'2025-05-07',24,2,'Cliente Ejemplo 2','C87654321','19/25','cliente2@email.com','Calle del Cliente 789','2025-05-07 09:23:55','2025-05-07 09:24:28',NULL),(89,'Efectivo',18,'2025-05-07',25,4,'Cliente Ejemplo 4','C99887766','20/25','cliente4@email.com','Calle del Cliente 202','2025-05-07 09:27:04','2025-05-07 09:28:59',NULL),(90,'Efectivo',18.08,'2025-05-07',25,NULL,NULL,NULL,NULL,NULL,NULL,'2025-05-07 09:27:04','2025-05-07 09:27:04',NULL),(91,'Efectivo',14,'2025-05-07',26,1,'Cliente Ejemplo','C12345678','22/25','cliente@email.com','Calle del Cliente 456','2025-05-07 09:50:52','2025-05-07 09:51:09',NULL),(92,'Efectivo',1.6,'2025-05-07',26,1,'Cliente Ejemplo','C12345678','22/25','cliente@email.com','Calle del Cliente 456','2025-05-07 09:50:52','2025-05-07 09:51:09',NULL),(94,'Tarjeta',5,'2025-05-07',27,1,'Cliente Ejemplo','C12345678','24/25','cliente@email.com','Calle del Cliente 456','2025-05-07 10:46:51','2025-05-07 10:57:40',NULL),(95,'Tarjeta',8.1,'2025-05-07',27,1,'Cliente Ejemplo','C12345678','24/25','cliente@email.com','Calle del Cliente 456','2025-05-07 10:46:51','2025-05-07 10:57:40',NULL),(96,'Efectivo',10,'2025-05-07',28,4,'Cliente Ejemplo 4','C99887766','24/25','cliente4@email.com','Calle del Cliente 202','2025-05-07 11:05:23','2025-05-07 11:05:30',NULL),(97,'Tarjeta',30,'2025-05-07',28,4,'Cliente Ejemplo 4','C99887766','24/25','cliente4@email.com','Calle del Cliente 202','2025-05-07 11:05:23','2025-05-07 11:05:30',NULL),(98,'Tarjeta',12.73,'2025-05-07',28,4,'Cliente Ejemplo 4','C99887766','24/25','cliente4@email.com','Calle del Cliente 202','2025-05-07 11:05:23','2025-05-07 11:05:30',NULL),(99,'Tarjeta',15,'2025-05-07',29,7,'Francisco SL','L98765432','29/25','fran@gmail.com','c/ algo','2025-05-07 11:06:51','2025-05-07 11:19:37',NULL),(100,'Efectivo',9.6,'2025-05-07',29,7,'Francisco SL','L98765432','29/25','fran@gmail.com','c/ algo','2025-05-07 11:06:51','2025-05-07 11:19:37',NULL),(101,'Tarjeta',12.05,'2025-05-07',30,4,'Cliente Ejemplo 4','C99887766','29/25','cliente4@email.com','Calle del Cliente 202','2025-05-07 11:25:23','2025-05-07 11:32:34',NULL),(102,'Tarjeta',19.15,'2025-05-07',31,1,'Cliente Ejemplo','C12345678','30/25','cliente@email.com','Calle del Cliente 456','2025-05-07 11:29:20','2025-05-07 11:32:44',NULL),(103,'Efectivo',1,'2025-05-07',32,3,'Cliente Ejemplo 3','C11223344','31/25','cliente3@email.com','Calle del Cliente 101','2025-05-07 11:34:14','2025-05-07 11:39:34',NULL),(104,'Efectivo',2,'2025-05-07',32,3,'Cliente Ejemplo 3','C11223344','31/25','cliente3@email.com','Calle del Cliente 101','2025-05-07 11:34:14','2025-05-07 11:39:34',NULL),(105,'Efectivo',3,'2025-05-07',32,3,'Cliente Ejemplo 3','C11223344','31/25','cliente3@email.com','Calle del Cliente 101','2025-05-07 11:34:15','2025-05-07 11:39:34',NULL),(106,'Tarjeta',12.59,'2025-05-07',32,3,'Cliente Ejemplo 3','C11223344','31/25','cliente3@email.com','Calle del Cliente 101','2025-05-07 11:34:15','2025-05-07 11:39:34',NULL),(107,'Tarjeta',2.5,'2025-05-09',37,NULL,NULL,NULL,NULL,NULL,NULL,'2025-05-09 08:56:33','2025-05-09 08:56:33',NULL),(108,'Tarjeta',35.05,'2025-05-09',1,NULL,NULL,NULL,NULL,NULL,NULL,'2025-05-09 09:10:34','2025-05-09 09:10:34',NULL),(109,'Tarjeta',22.95,'2025-05-09',38,NULL,NULL,NULL,NULL,NULL,NULL,'2025-05-09 11:19:40','2025-05-09 11:19:40',NULL);
/*!40000 ALTER TABLE `pagos` ENABLE KEYS */;
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
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime DEFAULT NULL,
  `comensales` int NOT NULL,
  `id_mesa` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pedidos_id_mesa_foreign` (`id_mesa`),
  CONSTRAINT `pedidos_id_mesa_foreign` FOREIGN KEY (`id_mesa`) REFERENCES `mesas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos`
--

LOCK TABLES `pedidos` WRITE;
/*!40000 ALTER TABLE `pedidos` DISABLE KEYS */;
INSERT INTO `pedidos` VALUES (1,'Cerrado','2025-04-02 15:02:25','2025-05-09 09:10:34',4,1,'2025-04-02 15:02:25','2025-05-09 09:10:34',NULL),(2,'Cerrado','2025-04-03 11:02:13','2025-04-03 11:02:13',3,2,'2025-04-03 11:02:13','2025-04-03 11:02:13',NULL),(3,'Pendiente','2025-04-03 11:02:13','2025-04-03 11:02:13',4,3,'2025-04-03 11:02:13','2025-04-03 11:02:13',NULL),(4,'Cancelado','2025-04-03 11:02:13','2025-04-03 11:02:13',1,4,'2025-04-03 11:02:13','2025-04-03 11:02:13',NULL),(5,'Abierto','2025-04-03 11:02:13','2025-04-03 11:02:13',5,5,'2025-04-03 11:02:13','2025-04-03 11:02:13',NULL),(6,'Cancelado','2025-04-16 09:33:25','2025-05-09 09:10:18',3,3,'2025-04-16 09:33:25','2025-05-09 09:10:18',NULL),(7,'Cancelado','2025-04-16 13:58:42',NULL,2,2,'2025-04-16 13:58:42','2025-04-16 13:58:42',NULL),(8,'Abierto','2025-04-17 14:34:57',NULL,3,2,'2025-04-17 14:34:57','2025-04-17 14:34:57','2025-04-24 07:15:07'),(9,'Cancelado','2025-04-24 08:32:40','2025-05-09 09:10:09',2,1,'2025-04-24 08:32:40','2025-05-09 09:10:09',NULL),(10,'Cancelado','2025-04-24 08:32:59','2025-05-09 09:10:03',3,2,'2025-04-24 08:32:59','2025-05-09 09:10:03',NULL),(11,'Cancelado','2025-04-24 09:18:56','2025-05-08 17:11:30',2,3,'2025-04-24 09:18:56','2025-05-08 17:11:30',NULL),(13,'Cerrado','2025-04-25 08:47:50','2025-04-25 11:57:31',5,2,'2025-04-25 08:47:50','2025-04-25 11:57:31',NULL),(14,'Cancelado','2025-04-25 08:48:10','2025-05-08 17:02:19',2,1,'2025-04-25 08:48:10','2025-05-08 17:02:19',NULL),(15,'Cerrado','2025-04-25 12:55:47','2025-04-25 13:07:19',2,2,'2025-04-25 12:55:47','2025-04-25 13:07:19',NULL),(16,'Cerrado','2025-04-25 13:07:26','2025-04-25 17:16:40',5,2,'2025-04-25 13:07:26','2025-04-25 17:16:40',NULL),(17,'Cancelado','2025-04-30 09:15:36','2025-05-08 17:02:02',2,1,'2025-04-30 09:15:36','2025-05-08 17:02:02',NULL),(18,'Cerrado','2025-05-02 13:09:41',NULL,2,1,'2025-05-02 13:09:41','2025-05-02 13:09:41',NULL),(19,'Cancelado','2025-05-05 16:08:09','2025-05-09 09:09:55',3,1,'2025-05-05 16:08:09','2025-05-09 09:09:55',NULL),(20,'Cerrado','2025-05-06 16:15:43','2025-05-06 16:22:14',2,1,'2025-05-06 16:15:43','2025-05-06 16:22:14',NULL),(21,'Cerrado','2025-05-06 16:22:29','2025-05-07 08:42:28',2,1,'2025-05-06 16:22:29','2025-05-07 08:42:28',NULL),(22,'Cerrado','2025-05-07 09:14:07','2025-05-07 09:20:26',2,1,'2025-05-07 09:14:07','2025-05-07 09:20:26',NULL),(23,'Cerrado','2025-05-07 09:21:58','2025-05-07 09:22:08',2,1,'2025-05-07 09:21:58','2025-05-07 09:22:08',NULL),(24,'Cerrado','2025-05-07 09:23:47','2025-05-07 09:23:55',2,2,'2025-05-07 09:23:47','2025-05-07 09:23:55',NULL),(25,'Cerrado','2025-05-07 09:26:47','2025-05-07 09:27:04',2,2,'2025-05-07 09:26:47','2025-05-07 09:27:04',NULL),(26,'Cerrado','2025-05-07 09:50:33','2025-05-07 09:50:52',2,1,'2025-05-07 09:50:33','2025-05-07 09:50:52',NULL),(27,'Cerrado','2025-05-07 09:57:49','2025-05-07 10:46:51',2,2,'2025-05-07 09:57:49','2025-05-07 10:46:51',NULL),(28,'Cerrado','2025-05-07 11:04:51','2025-05-07 11:05:23',2,1,'2025-05-07 11:04:51','2025-05-07 11:05:23',NULL),(29,'Cerrado','2025-05-07 11:06:35','2025-05-07 11:06:51',2,1,'2025-05-07 11:06:35','2025-05-07 11:06:51',NULL),(30,'Cerrado','2025-05-07 11:25:14','2025-05-07 11:25:23',2,2,'2025-05-07 11:25:14','2025-05-07 11:25:23',NULL),(31,'Cerrado','2025-05-07 11:29:10','2025-05-07 11:29:20',2,2,'2025-05-07 11:29:10','2025-05-07 11:29:20',NULL),(32,'Cerrado','2025-05-07 11:33:56','2025-05-07 11:34:15',10,3,'2025-05-07 11:33:56','2025-05-07 11:34:15',NULL),(33,'Cancelado','2025-05-07 15:28:20','2025-05-09 09:07:43',2,6,'2025-05-07 15:28:20','2025-05-09 09:07:43',NULL),(34,'Cancelado','2025-05-07 15:33:31','2025-05-09 09:09:44',3,2,'2025-05-07 15:33:31','2025-05-09 09:09:44',NULL),(35,'Abierto','2025-05-07 15:40:02',NULL,3,3,'2025-05-07 15:40:02','2025-05-07 15:40:02',NULL),(36,'Abierto','2025-05-08 16:57:39',NULL,2,1,'2025-05-08 16:57:39','2025-05-09 11:33:48',NULL),(37,'Cerrado','2025-05-09 08:36:31','2025-05-09 08:56:33',2,2,'2025-05-09 08:36:31','2025-05-09 08:56:33',NULL),(38,'Cerrado','2025-05-09 08:56:38','2025-05-09 11:19:40',2,2,'2025-05-09 08:56:38','2025-05-09 11:19:40',NULL),(39,'Abierto','2025-05-09 11:19:47',NULL,3,2,'2025-05-09 11:19:47','2025-05-09 11:35:14',NULL);
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
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio` decimal(8,2) NOT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `id_categoria` bigint unsigned NOT NULL,
  `id_empresa` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `iva` double DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `productos_id_categoria_foreign` (`id_categoria`),
  KEY `productos_id_empresa_foreign` (`id_empresa`),
  CONSTRAINT `productos_id_categoria_foreign` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE,
  CONSTRAINT `productos_id_empresa_foreign` FOREIGN KEY (`id_empresa`) REFERENCES `empresas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (1,'Ensalada César',9.90,'ensalada.jpg',1,1,1,'2025-04-02 15:02:10','2025-05-05 13:55:37',10,NULL),(2,'Tarta de Queso',5.99,'tarta.jpg',1,2,1,'2025-04-02 15:02:10','2025-04-02 15:02:10',0.1,NULL),(3,'Hamburguesa Clásica',9.55,'hamburguesa.jpg',1,3,1,'2025-04-03 11:07:24','2025-05-06 13:03:40',10,NULL),(4,'Refresco Cola',2.50,'refresco.jpg',1,4,1,'2025-04-03 11:07:24','2025-05-09 09:11:00',10,NULL),(5,'Pizza Margarita',6.55,'pizza.jpg',1,5,1,'2025-04-03 11:07:24','2025-05-06 16:18:57',10,NULL),(15,'Bravas',6.50,'bravas.jpg',1,1,1,'2025-04-17 09:57:29','2025-05-05 12:32:26',10,NULL),(19,'Croquetas',3.55,'croquetas.jpg',1,1,1,'2025-04-30 09:29:01','2025-05-06 13:03:24',10,NULL),(20,'Croquetas jamon',5.55,'croquetas.jpg',1,1,1,'2025-04-30 11:19:46','2025-05-05 12:32:26',10,'2025-04-30 11:23:42'),(26,'Hamburguesa queso y bacon',11.50,'hamburguesa queso y bacon.jpg',1,3,1,'2025-05-08 10:29:00','2025-05-09 09:12:42',10,NULL),(27,'Entrecot',16.50,'entrecot.jpg',1,3,1,'2025-05-09 09:17:02','2025-05-09 09:17:02',10,NULL),(28,'Lenguado',12.20,'lenguado.jpg',1,3,1,'2025-05-09 09:18:59','2025-05-09 09:18:59',10,NULL),(29,'Brownie',4.50,'brownie.jpg',1,2,1,'2025-05-09 09:20:58','2025-05-09 09:20:58',10,NULL),(30,'Gambas al ajillo',8.50,'gambas al ajillo.jpg',1,1,1,'2025-05-09 09:23:51','2025-05-09 09:23:51',10,NULL),(31,'Vino Juan de Juanes tinto',13.00,'vino juanes blanco.jpg',1,7,1,'2025-05-09 09:25:09','2025-05-09 09:27:18',10,NULL),(32,'Vino juan de juanes blanco',13.00,'vino juanes blanco.jpg',1,7,1,'2025-05-09 09:27:08','2025-05-09 09:27:08',10,NULL),(33,'Pizza 4 quesos',8.50,'pizza 4 quesos.jpg',1,5,1,'2025-05-09 09:29:05','2025-05-09 09:29:05',10,NULL),(34,'Pizza vegetal',7.30,'pizza vegetal.jpg',1,5,1,'2025-05-09 09:30:06','2025-05-09 09:30:06',10,NULL),(35,'Paella valenciana',15.00,'paella valenciana.jpg',1,3,1,'2025-05-09 09:31:41','2025-05-09 09:31:41',10,NULL),(36,'Arròs del senyoret',16.00,'arroz al senyoret.jpg',1,3,1,'2025-05-09 09:33:52','2025-05-09 09:33:52',10,NULL),(37,'Agua',2.00,'agua.jpg',1,4,1,'2025-05-09 09:36:14','2025-05-09 09:36:14',10,NULL),(38,'Turia',3.00,'turia.jpg',1,4,1,'2025-05-09 09:37:24','2025-05-09 09:37:24',10,NULL);
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurante_producto`
--

DROP TABLE IF EXISTS `restaurante_producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `restaurante_producto` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `activo` tinyint(1) NOT NULL,
  `id_producto` bigint unsigned NOT NULL,
  `id_restaurante` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rest_prod_id_producto_foreign` (`id_producto`),
  KEY `rest_prod_id_restaurante_foreign` (`id_restaurante`),
  CONSTRAINT `rest_prod_id_producto_foreign` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rest_prod_id_restaurante_foreign` FOREIGN KEY (`id_restaurante`) REFERENCES `restaurantes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurante_producto`
--

LOCK TABLES `restaurante_producto` WRITE;
/*!40000 ALTER TABLE `restaurante_producto` DISABLE KEYS */;
INSERT INTO `restaurante_producto` VALUES (1,1,1,1,'2025-04-02 15:03:49','2025-05-02 16:30:06'),(2,1,2,1,'2025-04-02 15:03:49','2025-04-02 15:03:49'),(3,1,3,1,'2025-04-03 11:11:25','2025-05-05 10:25:30'),(4,1,4,1,'2025-04-03 11:11:25','2025-05-09 09:11:00'),(5,1,5,1,'2025-04-03 11:11:25','2025-05-06 16:18:57'),(18,1,15,1,'2025-04-17 11:26:05','2025-04-30 15:42:44'),(33,1,19,1,'2025-04-30 09:29:01','2025-05-06 13:03:24'),(34,1,20,1,'2025-04-30 11:19:46','2025-04-30 11:19:46'),(42,1,1,6,'2025-05-06 13:41:08','2025-05-06 13:41:08'),(43,1,2,6,'2025-05-06 13:41:08','2025-05-06 13:41:08'),(44,1,5,6,'2025-05-06 13:41:08','2025-05-06 13:41:08'),(45,1,26,1,'2025-05-08 10:29:01','2025-05-09 09:12:42');
/*!40000 ALTER TABLE `restaurante_producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `restaurantes`
--

DROP TABLE IF EXISTS `restaurantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `restaurantes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(9) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contrasenya` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion_fiscal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CIF` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `razon_social` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_empresa` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `restaurantes_id_empresa_foreign` (`id_empresa`),
  CONSTRAINT `restaurantes_id_empresa_foreign` FOREIGN KEY (`id_empresa`) REFERENCES `empresas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `restaurantes`
--

LOCK TABLES `restaurantes` WRITE;
/*!40000 ALTER TABLE `restaurantes` DISABLE KEYS */;
INSERT INTO `restaurantes` VALUES (1,'Restaurante El Buen Sabor','Calle Falsa 123','620123456','$2y$12$El13IUIDo6fS/6hZ0FS2oOka38CIM2qceGeNEJzCOuVk0EFfTq0PG','Calle Falsa 123','B12345678','El Buen Sabor S.L.',1,'2025-04-02 15:01:57','2025-04-22 15:32:28',NULL),(2,'Café Central','Plaza Mayor 5','602345678','clave789','Plaza Mayor 5','B11223344','Café Central S.A.',3,'2025-04-03 10:55:39','2025-04-03 10:55:39',NULL),(3,'Pizzería Napoli','Calle Italia 99','603456789','clave101','Calle Italia 99','B55667788','Napoli Pizzas',4,'2025-04-03 10:55:39','2025-04-03 10:55:39',NULL),(4,'Hamburguesas Express','Carretera Nacional 22','604567890','clave202','Carretera Nacional 22','B22334455','Express Burgers',5,'2025-04-03 10:55:39','2025-04-03 10:55:39',NULL),(5,'Restaurante el buen sabor 2','c/ falsa 2','625167351','$2y$12$lMKywIq9i6P8Dn7Mk.SeA.5BixE6aWARe6qpGQQqnSaUgByLQQH36','c/ falsa 123','B12345678','El Buen Sabor S.L.',1,'2025-05-06 09:34:30','2025-05-06 15:47:27',NULL),(6,'Samuel','sadsadasdasdassadassda','6213213','$2y$12$CYNx5tyIL179XQWL5MlrduiQHfsNInUDKNnXDdSW0Ko4XM7wm9XBS','c/ sadasd','C12345668','Samuel SL',1,'2025-05-06 09:52:40','2025-05-06 09:52:40',NULL);
/*!40000 ALTER TABLE `restaurantes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rols`
--

DROP TABLE IF EXISTS `rols`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rols` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rol` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `productos` tinyint(1) NOT NULL,
  `categorias` tinyint(1) NOT NULL,
  `tpv` tinyint(1) NOT NULL,
  `usuarios` tinyint(1) NOT NULL,
  `mesas` tinyint(1) NOT NULL,
  `restaurante` tinyint(1) NOT NULL,
  `clientes` tinyint(1) NOT NULL,
  `empresa` tinyint(1) NOT NULL,
  `pago` tinyint(1) NOT NULL,
  `id_empresa` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rols_id_empresa_foreign` (`id_empresa`),
  CONSTRAINT `rols_id_empresa_foreign` FOREIGN KEY (`id_empresa`) REFERENCES `empresas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rols`
--

LOCK TABLES `rols` WRITE;
/*!40000 ALTER TABLE `rols` DISABLE KEYS */;
INSERT INTO `rols` VALUES (1,'Administrador',1,1,1,1,1,1,1,1,1,1,'2025-04-02 15:01:43','2025-04-02 15:01:43',NULL),(2,'Cocinero',1,0,0,0,0,1,0,0,0,2,'2025-04-03 10:55:39','2025-04-03 10:55:39','2025-04-03 10:55:39'),(3,'Cajero',0,0,1,0,0,0,1,0,1,3,'2025-04-03 10:55:39','2025-04-03 10:55:39',NULL),(4,'Mesero',0,0,0,1,1,0,1,0,0,4,'2025-04-03 10:55:39','2025-04-03 10:55:39',NULL),(5,'Gerente',1,1,1,1,1,1,1,1,1,5,'2025-04-03 10:55:39','2025-04-03 10:55:39',NULL),(6,'Camarero',0,0,1,0,0,0,0,0,0,1,'2025-05-09 09:46:41','2025-05-09 09:46:41',NULL),(7,'Cocinero',1,1,1,0,0,0,0,0,0,1,'2025-05-09 09:47:02','2025-05-09 09:47:02',NULL),(8,'Finanzas',0,0,0,0,0,0,1,0,1,1,'2025-05-09 10:23:49','2025-05-09 10:23:49',NULL),(9,'Ayudante de Cocina',1,0,0,0,0,0,0,0,0,1,'2025-05-09 10:45:10','2025-05-09 10:45:10',NULL);
/*!40000 ALTER TABLE `rols` ENABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('OA1nAzl3ELvQ33Kd2ZsqHJzFRn0fsy367RvoSCiL',NULL,'192.168.1.46','Mozilla/5.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiejBhWkNSZ1dIVWJoOFJOTXZLYWNWblVQVTFlTzBySG1TR1JWUFV1YSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MTk6Imh0dHA6Ly8xOTIuMTY4LjEuMzkiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1746011392);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ubicaciones`
--

DROP TABLE IF EXISTS `ubicaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ubicaciones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ubicacion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `id_restaurante` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ubicaciones_id_restaurante_foreign` (`id_restaurante`),
  CONSTRAINT `ubicaciones_id_restaurante_foreign` FOREIGN KEY (`id_restaurante`) REFERENCES `restaurantes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ubicaciones`
--

LOCK TABLES `ubicaciones` WRITE;
/*!40000 ALTER TABLE `ubicaciones` DISABLE KEYS */;
INSERT INTO `ubicaciones` VALUES (1,'Zona Interior',1,1,'2025-04-02 15:02:18','2025-04-02 15:02:18',NULL),(2,'Zona Terraza',1,1,'2025-04-03 11:02:07','2025-04-03 11:02:07',NULL),(3,'Salón VIP',1,2,'2025-04-03 11:02:07','2025-04-03 11:02:07',NULL),(4,'Barra',1,3,'2025-04-03 11:02:07','2025-04-03 11:02:07',NULL),(5,'Zona Infantil',1,4,'2025-04-03 11:02:07','2025-04-03 11:02:07',NULL),(6,'Zona restaurante',1,1,'2025-05-06 08:43:55','2025-05-06 08:43:55','2025-05-06 08:44:24'),(7,'Campo',1,1,'2025-05-06 08:44:02','2025-05-06 08:44:02','2025-05-06 08:44:22'),(8,'Barra',1,1,'2025-05-06 08:44:11','2025-05-06 08:44:11','2025-05-06 08:44:20');
/*!40000 ALTER TABLE `ubicaciones` ENABLE KEYS */;
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
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-05-09 10:14:56
