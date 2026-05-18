-- MySQL schema export for Dashboard Skrining CKG
-- Sesuaikan nama database di Laragon dengan `dashboard_skrining` atau ubah di file ini.

CREATE TABLE `respondents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `age` tinyint unsigned DEFAULT NULL,
  `marital_status` varchar(100) DEFAULT NULL,
  `has_marriage_plan` tinyint(1) DEFAULT NULL,
  `is_disabled` tinyint(1) DEFAULT NULL,
  `is_pregnant` tinyint(1) DEFAULT NULL,
  `work_unit` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `screening_sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `respondent_id` bigint unsigned NOT NULL,
  `screened_at` datetime DEFAULT NULL,
  `risk_score` int unsigned NOT NULL DEFAULT 0,
  `risk_category` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `screening_sessions_respondent_id_foreign` (`respondent_id`),
  CONSTRAINT `screening_sessions_respondent_id_foreign` FOREIGN KEY (`respondent_id`) REFERENCES `respondents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `screening_questions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `text` varchar(500) NOT NULL,
  `category` varchar(100) NOT NULL,
  `dimension` varchar(100) DEFAULT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'single_choice',
  `order` int unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `screening_questions_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `screening_options` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `screening_question_id` bigint unsigned NOT NULL,
  `label` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `score` int unsigned NOT NULL DEFAULT 0,
  `order` int unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `screening_options_question_id_foreign` (`screening_question_id`),
  CONSTRAINT `screening_options_question_id_foreign` FOREIGN KEY (`screening_question_id`) REFERENCES `screening_questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `screening_answers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `screening_session_id` bigint unsigned NOT NULL,
  `screening_question_id` bigint unsigned NOT NULL,
  `screening_option_id` bigint unsigned DEFAULT NULL,
  `text_answer` varchar(500) DEFAULT NULL,
  `score` int unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `screening_answers_session_id_foreign` (`screening_session_id`),
  KEY `screening_answers_question_id_foreign` (`screening_question_id`),
  KEY `screening_answers_option_id_foreign` (`screening_option_id`),
  CONSTRAINT `screening_answers_option_id_foreign` FOREIGN KEY (`screening_option_id`) REFERENCES `screening_options` (`id`) ON DELETE SET NULL,
  CONSTRAINT `screening_answers_question_id_foreign` FOREIGN KEY (`screening_question_id`) REFERENCES `screening_questions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `screening_answers_session_id_foreign` FOREIGN KEY (`screening_session_id`) REFERENCES `screening_sessions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Jika Anda sudah mengimpor skema lama tanpa kolom peserta, jalankan ALTER berikut (atau gunakan migrasi Laravel).
ALTER TABLE `respondents`
  ADD COLUMN `nik` varchar(32) NULL AFTER `name`,
  ADD COLUMN `birth_date` date NULL AFTER `nik`,
  ADD COLUMN `participant_category` enum('pjlp','non_asn','asn') NULL AFTER `gender`,
  ADD COLUMN `skpd` varchar(255) NULL AFTER `participant_category`,
  ADD COLUMN `ukpd` varchar(255) NULL AFTER `skpd`,
  ADD COLUMN `phone` varchar(32) NULL AFTER `ukpd`,
  ADD COLUMN `province` varchar(255) NULL AFTER `phone`,
  ADD COLUMN `regency` varchar(255) NULL AFTER `province`,
  ADD COLUMN `district` varchar(255) NULL AFTER `regency`,
  ADD COLUMN `village` varchar(255) NULL AFTER `district`,
  ADD COLUMN `address` text NULL AFTER `village`,
  ADD COLUMN `clinic_name` varchar(255) NULL AFTER `address`,
  ADD COLUMN `ckg_location` varchar(255) NULL AFTER `clinic_name`,
  ADD COLUMN `ckg_date` date NULL AFTER `ckg_location`;

-- Kode wilayah (BPS/Kemendagri) untuk dropdown berjenjang
ALTER TABLE `respondents`
  ADD COLUMN `province_code` varchar(20) NULL AFTER `province`,
  ADD COLUMN `regency_code` varchar(20) NULL AFTER `regency`,
  ADD COLUMN `district_code` varchar(20) NULL AFTER `district`,
  ADD COLUMN `village_code` varchar(20) NULL AFTER `village`;

