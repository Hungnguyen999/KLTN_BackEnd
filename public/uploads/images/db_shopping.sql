-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 03, 2020 lúc 05:41 AM
-- Phiên bản máy phục vụ: 10.4.11-MariaDB
-- Phiên bản PHP: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `db_shopping`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `attribute`
--

CREATE TABLE `attribute` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `attribute`
--

INSERT INTO `attribute` (`id`, `name`) VALUES
(1, 'size');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `attribute_value`
--

CREATE TABLE `attribute_value` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_attribute` bigint(20) UNSIGNED NOT NULL,
  `id_product` bigint(20) UNSIGNED NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `attribute_value`
--

INSERT INTO `attribute_value` (`id`, `value`, `id_attribute`, `id_product`, `amount`) VALUES
(2, '34', 1, 5, 100);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bills`
--

CREATE TABLE `bills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date_order` datetime NOT NULL,
  `total` double NOT NULL,
  `payment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `isFinish` int(11) NOT NULL,
  `cus_infor` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`cus_infor`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bill_detail`
--

CREATE TABLE `bill_detail` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `amount` int(11) NOT NULL,
  `unit_price` double NOT NULL,
  `status` int(11) NOT NULL,
  `id_bill` bigint(20) UNSIGNED NOT NULL,
  `id_product` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `goods_receipt`
--

CREATE TABLE `goods_receipt` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `amount` int(11) NOT NULL,
  `unit_price` double(8,2) NOT NULL,
  `total` double(8,2) NOT NULL,
  `date` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `id_product` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hack`
--

CREATE TABLE `hack` (
  `TEXT` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `hack`
--

INSERT INTO `hack` (`TEXT`, `created_at`, `updated_at`) VALUES
('PHPSESSID=r81hmvbn9rj251gmmebpi83pa7; XSRF-TOKEN=eyJpdiI6InczMEJzTzRsVmwzT0hickdSUUNcL01nPT0iLCJ2YWx1ZSI6IkpMK1Znak5cL0pkZDNtcTNmRDVqMnorNkJuSkVzZXZIR0ZZUGRtTnJkV3FYRjdlbHpcL3JCekUxNXBRXC9cL3F0NHIwIiwibWFjIjoiNmYyNGM4ZGU2YzIxMzQ4Y2I5ZWY4OTliNDNhYzQyZDIxNDE2OWUyZjdmNGZlZWY2ODBiZGQ2MTg2OGZkZDE4MyJ9', '2020-03-31 09:04:10', '2020-03-31 09:04:10'),
('111', '2020-04-01 07:21:53', '2020-04-01 07:21:53'),
('PHPSESSID=93dfnn6u604enu6thsdcth4580; XSRF-TOKEN=eyJpdiI6InZQYzZlSXdRUnlcL2FNenBHNnpqcmhRPT0iLCJ2YWx1ZSI6IlpUWHFaRnAxb3RPRVo4RFoxS1RtcVFqMnkzZW5WY210dTV0MWx5NnpjaTZZNTRKTUJoVEs5R055aDk0cE1ZYWoiLCJtYWMiOiI1OTZmZDZkYzM2OWZkMDY4NmE1ZTAzNDJiN2UxOTk4NjRmMTllMTExOTU3MjU5ZmI2YzMyMTU0Yjc0YjA5MGNlIn0=', '2020-04-01 07:24:47', '2020-04-01 07:24:47'),
('PHPSESSID=93dfnn6u604enu6thsdcth4580; XSRF-TOKEN=eyJpdiI6ImhUYkZvdG9xdXNOaXBXTmNRXC9cLzNFUT09IiwidmFsdWUiOiJBSkdNVFwvbnBwY2o5czQrNStSdklKV0VmVFJNelYyNTNXSncyeklmc2JLMTQ5aE54SG1nZ3JBTVZhclJvRE5LTSIsIm1hYyI6ImI0Y2Y1MDIxMjcyYTVlYTM1NGNiOTUxMTRiNzdiNGQ4MzVjYjY1NDBiOWUxYTM0Y2M2ZTI2NWZhMzc5ZmIyNzgifQ==', '2020-04-01 08:50:40', '2020-04-01 08:50:40');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_10_29_115831_create_type_products_table', 1),
(4, '2019_10_29_154741_create_slides_table', 1),
(5, '2019_10_29_155252_create_customers_table', 1),
(6, '2019_10_29_160433_add_more_atribute_to_users_table', 1),
(7, '2019_10_29_161803_add_atribute_username_to_users_table', 1),
(8, '2019_10_29_171140_create_products_table', 1),
(9, '2019_10_29_191444_create_goods_receipt_table', 1),
(10, '2019_10_29_195135_create_bills_table', 1),
(11, '2019_10_29_205029_create_bill_detail_table', 1),
(12, '2019_11_01_170342_add_more_atribute_to_table_product', 1),
(13, '2019_11_19_162558_change_data_type_of_column_gender_in_table_customers', 1),
(14, '2019_11_27_083952_add_gender_to_table_products', 1),
(15, '2019_11_29_131250_delete_customer_from_bill_table', 1),
(16, '2019_11_29_140400_delete_table_customer', 1),
(17, '2019_11_29_141154_add_attribute_gender_to_users_table', 1),
(18, '2019_11_29_144725_add_foreign_key_user_bill', 1),
(19, '2019_11_29_200518_add_atribute_image_des_to_table_products', 1),
(20, '2019_11_29_201237_add_atribute_size_to_table_bill_detail', 1),
(21, '2019_12_04_003759_allow_description_null_in_table_products', 1),
(22, '2019_12_05_083255_add_size_to_products_table', 1),
(23, '2019_12_05_155553_change_amount_table_products', 1),
(24, '2019_12_05_162535_change_total_column_table_bills', 1),
(25, '2019_12_05_163555_change_unit_price_table_bill_detail', 1),
(26, '2019_12_06_145642_add_is_finish_to_table_bills', 1),
(27, '2019_12_18_212516_add_attribute_to_table_users', 1),
(28, '2019_12_20_101659_add_google_id_column', 1),
(29, '2019_12_20_104433_set_username_nullable', 1),
(30, '2020_03_07_115430_create_table_attribute', 1),
(31, '2020_03_07_115553_create_table_attribute_value', 1),
(32, '2020_03_10_101021_add_attribute_user_infor_to_table_bill', 1),
(33, '2020_03_25_133000_create_table_product_attribute', 1),
(34, '2020_03_25_133202_delete_amount_coloumn_in_table_product', 1),
(35, '2020_03_25_133938_delete_size_coloumn_in_table_product', 1),
(36, '2020_03_25_134643_delete_table_product_attribute', 1),
(37, '2020_03_25_142534_add_idproduct', 1),
(38, '2020_03_31_142924_add_attribute_to_table_user', 2),
(39, '2020_03_31_143952_change_role_attribute_of_table_users', 3),
(41, '2020_03_31_145544_edit_role_in_table_user', 4),
(43, '2020_04_02_095722_change_data_type_coloumn_description_of_table_product', 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_price` double NOT NULL,
  `promotion_price` double NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `id_type` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `isNew` tinyint(1) NOT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `unit_price`, `promotion_price`, `image`, `status`, `id_type`, `created_at`, `updated_at`, `isNew`, `gender`, `img1`, `img2`, `img3`) VALUES
(5, 'Giày thể thao nữ', '<p>❌&nbsp;Chỉ c&oacute; thể n&oacute;i l&agrave; &ldquo; si&ecirc;u phẩm &ldquo; th&ocirc;i C&aacute;c N&agrave;ng ạ&nbsp;❗️<br />\r\n[<a href=\"https://www.facebook.com/hashtag/m%C3%A3_sp?source=feed_text&amp;epa=HASHTAG&amp;__xts__%5B0%5D=68.ARAKNnFCc6d2GDDe3PKR3wG3RNZJ77cVT5vs5Ze3clprG-Lf-j3mH6eKJRKTeP6Jy-eoryKXbKWeK3DMWo5ZX2nAhZ7pxNLH-cR7y_YmX3FulN8qmhQYgIefhoBgugAifWsEz2IwH_mUgp0dimN1kIayX_ctpK-VG8NW0zMbe27aYeOzyD56ADClb8IwX-uG1gigJTcDVoMv8Odvbx10JRvEjCK9_r7dNbznEnCBqYvMz0IJ5fqiokwXnF3p29zYQIx34W46DW9GuFNTG2mBgaIzJ75UJ_voUh9rPXbH069a6ZMF-jPwZ4oWkVizaIHpTHOi06iBPpzhwt1c6VmtHfcZNiVnhgrh6m9uIA&amp;__tn__=%2ANK-R\">#M&atilde;_sp</a>: 0402] gi&aacute;&nbsp;<a href=\"https://www.facebook.com/hashtag/270k?source=feed_text&amp;epa=HASHTAG&amp;__xts__%5B0%5D=68.ARAKNnFCc6d2GDDe3PKR3wG3RNZJ77cVT5vs5Ze3clprG-Lf-j3mH6eKJRKTeP6Jy-eoryKXbKWeK3DMWo5ZX2nAhZ7pxNLH-cR7y_YmX3FulN8qmhQYgIefhoBgugAifWsEz2IwH_mUgp0dimN1kIayX_ctpK-VG8NW0zMbe27aYeOzyD56ADClb8IwX-uG1gigJTcDVoMv8Odvbx10JRvEjCK9_r7dNbznEnCBqYvMz0IJ5fqiokwXnF3p29zYQIx34W46DW9GuFNTG2mBgaIzJ75UJ_voUh9rPXbH069a6ZMF-jPwZ4oWkVizaIHpTHOi06iBPpzhwt1c6VmtHfcZNiVnhgrh6m9uIA&amp;__tn__=%2ANK-R\">#295K</a><br />\r\n✌️&nbsp;<a href=\"https://www.facebook.com/hashtag/sneaker_tr%E1%BA%AFng?source=feed_text&amp;epa=HASHTAG&amp;__xts__%5B0%5D=68.ARAKNnFCc6d2GDDe3PKR3wG3RNZJ77cVT5vs5Ze3clprG-Lf-j3mH6eKJRKTeP6Jy-eoryKXbKWeK3DMWo5ZX2nAhZ7pxNLH-cR7y_YmX3FulN8qmhQYgIefhoBgugAifWsEz2IwH_mUgp0dimN1kIayX_ctpK-VG8NW0zMbe27aYeOzyD56ADClb8IwX-uG1gigJTcDVoMv8Odvbx10JRvEjCK9_r7dNbznEnCBqYvMz0IJ5fqiokwXnF3p29zYQIx34W46DW9GuFNTG2mBgaIzJ75UJ_voUh9rPXbH069a6ZMF-jPwZ4oWkVizaIHpTHOi06iBPpzhwt1c6VmtHfcZNiVnhgrh6m9uIA&amp;__tn__=%2ANK-R\">#Sneaker_trắng</a>&nbsp;mới về cực phong c&aacute;ch với phom d&aacute;ng chắc chắn, cứng c&aacute;p lại c&ograve;n si&ecirc;u bền.<br />\r\n🍭&nbsp;Đế độn 5cm t&ocirc;n d&aacute;ng đỉnh cao, mix đồ l&agrave; SỐ 1 lu&ocirc;n nh&eacute;!<br />\r\nChần chừ g&igrave; nữa m&agrave; kh&ocirc;ng sắm ngay một em th&ocirc;i c&aacute;c n&agrave;ng ơiiii&nbsp;😝</p>', 295000, 270000, '2020-04-02_19h_17m_45s_mwc1.jpg', 1, 1, NULL, NULL, 1, 'Nữ', '2020-04-02_10h_59m_43s_mwc.jpg', '2020-04-02_10h_59m_43s_mwc2.jpg', '2020-04-02_10h_59m_43s_mwc3.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `slides`
--

CREATE TABLE `slides` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `type_products`
--

CREATE TABLE `type_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `type_products`
--

INSERT INTO `type_products` (`id`, `name`, `description`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Giày thể thao', '', '', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `phone`, `address`, `status`, `username`, `gender`, `google_id`, `role`) VALUES
(1, 'Minh Luân Trương', 'minhluan260144@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '111415741757531001755', 1);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `attribute`
--
ALTER TABLE `attribute`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `attribute_value`
--
ALTER TABLE `attribute_value`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attribute_value_id_attribute_foreign` (`id_attribute`),
  ADD KEY `attribute_value_id_product_foreign` (`id_product`);

--
-- Chỉ mục cho bảng `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bills_id_user_foreign` (`id_user`);

--
-- Chỉ mục cho bảng `bill_detail`
--
ALTER TABLE `bill_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bill_detail_id_bill_foreign` (`id_bill`),
  ADD KEY `bill_detail_id_product_foreign` (`id_product`);

--
-- Chỉ mục cho bảng `goods_receipt`
--
ALTER TABLE `goods_receipt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_receipt_id_product_foreign` (`id_product`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_id_type_foreign` (`id_type`);

--
-- Chỉ mục cho bảng `slides`
--
ALTER TABLE `slides`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `type_products`
--
ALTER TABLE `type_products`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `attribute`
--
ALTER TABLE `attribute`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `attribute_value`
--
ALTER TABLE `attribute_value`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `bills`
--
ALTER TABLE `bills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `bill_detail`
--
ALTER TABLE `bill_detail`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `goods_receipt`
--
ALTER TABLE `goods_receipt`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `slides`
--
ALTER TABLE `slides`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `type_products`
--
ALTER TABLE `type_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `attribute_value`
--
ALTER TABLE `attribute_value`
  ADD CONSTRAINT `attribute_value_id_attribute_foreign` FOREIGN KEY (`id_attribute`) REFERENCES `attribute` (`id`),
  ADD CONSTRAINT `attribute_value_id_product_foreign` FOREIGN KEY (`id_product`) REFERENCES `products` (`id`);

--
-- Các ràng buộc cho bảng `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `bill_detail`
--
ALTER TABLE `bill_detail`
  ADD CONSTRAINT `bill_detail_id_bill_foreign` FOREIGN KEY (`id_bill`) REFERENCES `bills` (`id`),
  ADD CONSTRAINT `bill_detail_id_product_foreign` FOREIGN KEY (`id_product`) REFERENCES `products` (`id`);

--
-- Các ràng buộc cho bảng `goods_receipt`
--
ALTER TABLE `goods_receipt`
  ADD CONSTRAINT `goods_receipt_id_product_foreign` FOREIGN KEY (`id_product`) REFERENCES `products` (`id`);

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_id_type_foreign` FOREIGN KEY (`id_type`) REFERENCES `type_products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
