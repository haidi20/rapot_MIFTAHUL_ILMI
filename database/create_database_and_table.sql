CREATE TABLE `class_room` (
  `id` varchar(50),
  `name_class_room` varchar(255),
  `description` text,
  `name_speaker` varchar(255),
  `is_deleted` boolean DEFAULT false,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `student` (
  `id` varchar(50),
  `name_student` varchar(255),
  `is_deleted` boolean DEFAULT false,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `absen_type` (
  `id` varchar(50),
  `name_absen_type` varchar(255),
  `description` text,
  `is_deleted` boolean DEFAULT false,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `quiz` (
  `id` varchar(50),
  `name_quiz` varchar(255),
  `description` text,
  `is_deleted` boolean DEFAULT false,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `quiz_student` (
  `id` varchar(50),
  `class_room_id` varchar(50),
  `quiz_id` varchar(50),
  `student_id` varchar(50),
  `value` int,
  `grade` varchar(255),
  `note` text,
  `is_deleted` boolean DEFAULT false,
  `created_at` datetime,
  `updated_at` datetime
);

CREATE TABLE `absen` (
  `id` varchar(50),
  `student_id` varchar(50),
  `quiz_id` varchar(50),
  `absen_type_id` varchar(50),
  `date_absen` datetime,
  `is_deleted` boolean DEFAULT false,
  `created_at` datetime,
  `updated_at` datetime
);
