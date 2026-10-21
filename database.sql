-- ==============================================================
-- BASE DE DATOS: echele_camello
-- Sincronización con XAMPP / MySQL
-- Portal de Empleo, Salarios Transparentes & Talento LATAM
-- ==============================================================

CREATE DATABASE IF NOT EXISTS `echele_camello` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `echele_camello`;

-- --------------------------------------------------------
-- 1. Tabla: companies (Empresas)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `companies` (
  `id` VARCHAR(50) NOT NULL PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `logo` TEXT,
  `banner` TEXT,
  `industry` VARCHAR(100),
  `location` VARCHAR(150),
  `website` VARCHAR(255),
  `employees` VARCHAR(50),
  `verified` TINYINT(1) DEFAULT 1,
  `about` TEXT,
  `culture` TEXT,
  `perks` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 2. Tabla: categories (Categorías de empleo)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id` VARCHAR(50) NOT NULL PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `quota` VARCHAR(50),
  `icon` VARCHAR(50),
  `description` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 3. Tabla: jobs (Vacantes / Empleos)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` VARCHAR(100) NOT NULL PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `company_id` VARCHAR(50),
  `company` VARCHAR(150) NOT NULL,
  `company_logo` TEXT,
  `location` VARCHAR(150) NOT NULL,
  `modality` ENUM('100% Remoto', 'Híbrido', 'Presencial') NOT NULL DEFAULT '100% Remoto',
  `salary_cop` VARCHAR(100),
  `salary_usd` VARCHAR(100),
  `salary_min` BIGINT DEFAULT 0,
  `salary_max` BIGINT DEFAULT 0,
  `currency` ENUM('COP', 'USD') NOT NULL DEFAULT 'COP',
  `salary_period` VARCHAR(50) DEFAULT '/ mes',
  `contract_type` VARCHAR(150) NOT NULL,
  `experience_level` ENUM('Junior', 'Semi-Senior', 'Senior', 'Lead & Staff') NOT NULL DEFAULT 'Senior',
  `category` VARCHAR(100) NOT NULL,
  `tags` TEXT, -- JSON array o texto delimitado por comas
  `perks` TEXT, -- JSON array o texto delimitado por comas
  `posted_time` VARCHAR(50) DEFAULT 'Reciente',
  `urgency` VARCHAR(50) DEFAULT 'Destacada',
  `verified_company` TINYINT(1) DEFAULT 1,
  `response_time` VARCHAR(50) DEFAULT '< 48h',
  `active_candidates` INT DEFAULT 15,
  `description` TEXT NOT NULL,
  `responsibilities` TEXT,
  `requirements` TEXT,
  `hiring_manager_name` VARCHAR(150),
  `hiring_manager_role` VARCHAR(150),
  `hiring_manager_avatar` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 4. Tabla: candidates (Candidatos)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `candidates` (
  `id` VARCHAR(50) NOT NULL PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `role` VARCHAR(150) NOT NULL,
  `experience` VARCHAR(100),
  `location` VARCHAR(150),
  `avatar` TEXT,
  `match_score` INT DEFAULT 90,
  `vacancy_target` VARCHAR(150),
  `salary_expectation` VARCHAR(100),
  `skills` TEXT,
  `english_level` VARCHAR(50),
  `tech_test_score` VARCHAR(50),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 5. Tabla: applications (Postulaciones del usuario)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `applications` (
  `id` VARCHAR(50) NOT NULL PRIMARY KEY,
  `job_id` VARCHAR(100) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `company` VARCHAR(150) NOT NULL,
  `company_logo` TEXT,
  `location` VARCHAR(150),
  `status` ENUM('offer', 'interview', 'tech_test', 'review', 'archived') NOT NULL DEFAULT 'review',
  `status_label` VARCHAR(100) NOT NULL,
  `status_color` VARCHAR(50) DEFAULT 'emerald',
  `salary_display` VARCHAR(100),
  `contract_type` VARCHAR(150),
  `applied_days_ago` INT DEFAULT 1,
  `stage_progress` VARCHAR(100) DEFAULT 'Fase 1 de 4',
  `progress_percent` INT DEFAULT 25,
  `tags` TEXT,
  `action_title` VARCHAR(255),
  `action_description` TEXT,
  `action_deadline` VARCHAR(100),
  `action_btn_text` VARCHAR(100),
  `action_type` VARCHAR(50),
  `interview_topic` VARCHAR(255),
  `interview_datetime` VARCHAR(100),
  `interview_interviewer` VARCHAR(150),
  `interview_platform` VARCHAR(100),
  `test_topic` VARCHAR(255),
  `test_time_remaining` VARCHAR(100),
  `test_progress_percent` INT DEFAULT 0,
  `test_last_saved` VARCHAR(100),
  `applicant_name` VARCHAR(150) DEFAULT 'Camilo Pérez',
  `applicant_email` VARCHAR(150) DEFAULT 'camilo.perez@echelecamello.co',
  `applicant_phone` VARCHAR(50) DEFAULT '+57 312 456 7890',
  `cover_note` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 6. Tabla: testimonials (Testimonios de usuarios)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` VARCHAR(50) NOT NULL PRIMARY KEY,
  `quote` TEXT NOT NULL,
  `author` VARCHAR(150) NOT NULL,
  `role` VARCHAR(150) NOT NULL,
  `hired_in` VARCHAR(100),
  `avatar` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 7. Tabla: saved_jobs (Camellos guardados / favoritos)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `saved_jobs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `job_id` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uk_saved_job` (`job_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================
-- INSERCIÓN DE DATOS INICIALES (SEED DATA)
-- ==============================================================

-- Categorías
INSERT INTO `categories` (`id`, `name`, `quota`, `icon`, `description`) VALUES
('tech', 'Tecnología & Desarrollo', '4,120 cupos', 'terminal', 'Software, Cloud, Datos, Inteligencia Artificial y Ciberseguridad.'),
('sales', 'Ventas & Comercial', '3,450 cupos', 'trending_up', 'Account Executives, B2B, Retail, SDR y Directores de Cuenta.'),
('ops', 'Operaciones & Logística', '2,890 cupos', 'local_shipping', 'Cadena de suministros, centros de distribución y despacho.'),
('design', 'Marketing & Diseño', '1,620 cupos', 'palette', 'UI/UX, Performance, Redacción, Estrategia y Contenido Multimedia.'),
('support', 'Atención & Bilingüe', '3,890 cupos', 'support_agent', 'Soporte técnico, éxito del cliente y agentes bilingües C1/B2.'),
('finance', 'Finanzas & Contabilidad', '1,410 cupos', 'account_balance', 'Revisoría fiscal, auditoría, controller e inversiones corporativas.'),
('health', 'Salud & Bienestar', '980 cupos', 'health_and_safety', 'Enfermería clínica, coordinación asistencial y SG-SST.'),
('tourism', 'Hotelería & Turismo', '1,120 cupos', 'hotel', 'Gestión de hospedaje, eventos masivos y guía especializada.')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- Empresas
INSERT INTO `companies` (`id`, `name`, `logo`, `banner`, `industry`, `location`, `website`, `employees`, `verified`, `about`, `culture`, `perks`) VALUES
('stark-cloud', 'Stark Cloud Corp', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDaLtyWrvTDHSisX7_s1dOIeOQaAbH_fMviGGcxP4PscKyDvneJ1dV7L4ubRpAN3xK2UWcoOp2E0ZQCtCv5mC1CpynAUHM8_aYvTtp5vbR3ED8q2ZOtrqVuGq6bhZj5zDsvkzTKeLXiGq-hxUyEYpVA7PjPAammTB7Yb75KeJTvgMf_MrLlIUVnXYFcK4RJWYp_9UFghIkZyb4mcnku1en1_PMGJ6EVKIb94TAIa-VqEJQHxdDyA-vs', 'https://lh3.googleusercontent.com/aida-public/AB6AXuAzIYswyddhsKFHv9C3OI57DaNAvqr9CvKT_-qEDgUTAZUT0o_0qCZjHKgbagbOH5TFDxxB-JuafFkFUmn5UtjQjFQGKy47vgwkS5BcQXM5188OVtpW8-IlCTht6ssLRafNAc-JllXQZpADUZuvgPTrvUZnJ1xExfihQ0ap0EIqZuBAWxfwO_5l-4VJV168rw0SRL3DBC7hWSjFIM5ramvDHagh1LSMZ1PnvDHcf_GJ9dRaIaqPqM3G', 'Cloud Computing & AI Infrastructure', 'Medellín / San Francisco (100% Remoto)', 'https://starkcloud.io', '250 - 500 colaboradores', 1, 'En Stark Cloud Corp aceleramos la orquestación multi-cloud, el despliegue serverless perimetral y la infraestructura para modelos de Inteligencia Artificial generativa. Creemos en el trabajo asíncrono, los salarios en moneda dura y la autonomía técnica.', 'Cultura remota global con horarios flexibles, enfoque en resultados claros, sin micromanagement y con presupuesto anual para educación y conferencias internacionales.', 'Salario en USD directo vía Deel/Wise, MacBook Pro M3 Max, 25 días PTO libres, Seguro médico internacional 100%, Bono de Home Office de USD $1,000')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- Vacantes / Empleos
INSERT INTO `jobs` (`id`, `title`, `company_id`, `company`, `company_logo`, `location`, `modality`, `salary_cop`, `salary_usd`, `salary_min`, `salary_max`, `currency`, `salary_period`, `contract_type`, `experience_level`, `category`, `tags`, `perks`, `posted_time`, `urgency`, `verified_company`, `response_time`, `active_candidates`, `description`, `responsibilities`, `requirements`, `hiring_manager_name`, `hiring_manager_role`, `hiring_manager_avatar`) VALUES
('stark-frontend-senior', 'Senior Frontend Developer (Next.js / TypeScript)', 'stark-cloud', 'Stark Cloud Corp', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDaLtyWrvTDHSisX7_s1dOIeOQaAbH_fMviGGcxP4PscKyDvneJ1dV7L4ubRpAN3xK2UWcoOp2E0ZQCtCv5mC1CpynAUHM8_aYvTtp5vbR3ED8q2ZOtrqVuGq6bhZj5zDsvkzTKeLXiGq-hxUyEYpVA7PjPAammTB7Yb75KeJTvgMf_MrLlIUVnXYFcK4RJWYp_9UFghIkZyb4mcnku1en1_PMGJ6EVKIb94TAIa-VqEJQHxdDyA-vs', '100% Remoto LATAM (GMT-5)', '100% Remoto', '$14,500,000 - $19,800,000 COP', 'USD $3,500 – $4,800', 3500, 4800, 'USD', '/ mes', 'Contrato Contractor Internacional / Indefinido', 'Senior', 'Tecnología & Software', 'Next.js 14, TypeScript, React Server Components, Tailwind CSS, GraphQL, Inglés C1', 'Salario en Moneda Fuerte, Seguro Médico Internacional, MacBook Pro M3 Max, 25 Días PTO', 'Publicado hace 2 días', 'Urgente', 1, '< 48h', 42, 'En Stark Cloud Corp aceleramos la orquestación multi-cloud y computación perimetral. Buscamos un Senior Frontend Developer apasionado por interfaces de alta frecuencia, diseño modular y rendimiento de milisegundos.', 'Diseñar, estructurar y mantener aplicaciones web complejas en Next.js 14, TypeScript estricto y Tailwind CSS.;Construir componentes reutilizables y altamente documentados para nuestro Design System interno bajo Storybook.;Colaborar activamente con arquitectos Backend para consumir APIs RESTful y endpoints en GraphQL con SSR y cacheo.;Conducir revisiones de código de alto estándar, mentoría técnica y pipeline CI/CD en GitHub Actions.;Optimizar Core Web Vitals y bundle size garantizando métricas de 95+ en Lighthouse.', 'Experiencia comprobable mínima de 4 a 5 años en producción con React y ecosistema moderno.;Dominio profundo de asincronía, gestión del estado global (Zustand/Redux), Server Components y Web APIs.;Comunicación efectiva y fluida para articular trade-offs de arquitectura con producto.;Inglés profesional intermedio-avanzado (B2/C1) para interactuar en syncs con equipo global.', 'Mateo Valencia', 'VP of Engineering en Stark Cloud', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCxhpvIhC62GScpDBqJDz_M-HTOg6puaYp1e93ppGV5mX8gIDuiuXsNBdTwNFnP6TRdojQWpFu2ye9aQRtbhzvhGXBaPFClg0FIfoMIoxbqWChig63FqopL1zedE0zUDxliqmtMaaa3qVv8yJ9tWs9idNRp1KcKRNE9whkvtDGW-zvNZm-Zexs8fxOR5gNKtxuHwQdY8KaaSGms-UXAPEYY9USuYXAOJLt-p6-GyBk00sVoy8ZA8GZx'),

('nubank-fullstack-lead', 'Senior Fullstack Tech Lead (React & Node)', NULL, 'Nubank Colombia / Fintech Hub', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBT9fRJfKpazDGS6VGIeM1WFP_UAoMmmjl3jRzP9NO08NqK7l8YIJBO-yPbHRxMjF66DDHkcsk9_Iy7qoJQMFMdcr4IbdldaykxUFHsHlZrljqExL006AtyddVbou2997KIEufIPAiIzEB0_OmM4hgoXbm4gPWsjldBlbsd2dw1pC69zKQP5qT87QS8bF_PU3gwF4vhEBeO1DonXjyqPCoq5mMIcbVnjSP-fmjAZ6FSce7-aZoj9XI1', '100% Remoto en Colombia', '100% Remoto', '$14,500,000 - $17,000,000 COP', 'USD $3,600 - $4,200', 14500000, 17000000, 'COP', '/ Mes Bruto', 'Contrato Término Indefinido', 'Lead & Staff', 'Tecnología & Software', 'TypeScript, Next.js, Node.js, AWS ECS, GraphQL, Kafka', 'MacBook Pro M3 Incluida, Prepagada Sura, Stock Options, Vacaciones Flexibles', 'Publicada hace 3 horas', 'Destacada', 1, '< 24h', 43, 'Liderarás la arquitectura y migración de nuestra infraestructura de microservicios de pagos masivos. Requerimos experiencia sólida en TypeScript, arquitecturas distribuidas de alta concurrencia y liderazgo de células ágiles de ingeniería.', 'Guiar técnicamente al equipo de ingeniería de pagos.;Asegurar alta disponibilidad del 99.99% en servicios críticos.;Diseñar APIs robustas y escalables con Kafka y microservicios en AWS.', 'Mínimo 6 años de experiencia en desarrollo web y backend.;Experiencia liderando células técnicas ágiles.;Manejo avanzado de arquitecturas cloud en AWS.', 'Tomás Restrepo', 'VP de Ingeniería', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBFszncdPI5S0JGlj2wBJzsI8mmKxq_Hqh6FPQ6V0Ma_nS5D3Yvw0-ofDMPQMRcFYy9z3yTigXmZgaDK9nNgE3lIE1YhO_4k1NnJHz-lGmcHGybjqEnd5qMTqh6DPVrDIiObqz6I1V4vaRIIdnn7C-Ffo2KKxXmMpVHW_JkuL2ufPElwJDZ4jyZXvnUuRCx4pCTmoY604E-EBJxzE4TMmFswH9xedBPZN7ZCP6vSzd7JUaMdJ3TFsgW'),

('datapulse-staff-ml', 'Staff Machine Learning Engineer · LLM Infra', NULL, 'DataPulse Global (San Francisco / LATAM)', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBAB_IdEdrsuIst1GOf6jawRzqiGC2WCUCSnV_aF5SJOIsnI7edZSdDLAtJRG4cZ1kXPm4vRZh06eiq-2qlIEnKKihAWtP-1I7tCnXnhxIuxc_ZjI_ZZAZpKJp0WCxYfGL5LLssIadTy5gz-GQDch1M7S1dAH_h6Kb3jEowUmyNGeXV0Ozi0oWEBvRykOD-ABxudBXR-lMnK0bE5O0olgye80NWjFU6gvxxZ1HQmjtxUMrRGExGIOx6', 'Remoto Total LATAM · Horario Flexible', '100% Remoto', '$18,000,000 - $26,000,000 COP', 'USD $4,500 - $6,500', 4500, 6500, 'USD', '/ Mes Neto', 'Contractor W-8BEN', 'Lead & Staff', 'Tecnología & Software', 'Python, PyTorch, Kubernetes, CUDA, vLLM, Inference Engine', 'Bono Bienvenida USD $2k, Vacaciones Ilimitadas, Presupuesto HW, Home Office Stipend', 'Publicada ayer', 'Destacada', 1, '< 12h', 19, 'Buscamos un Staff ML Engineer para diseñar y optimizar pipelines de inferencia para modelos fundacionales a gran escala. Experiencia demostrada con PyTorch, vLLM, despliegue en Kubernetes y optimización de latencia en GPU clusters.', 'Optimización de throughput y latencia en clusters GPU.;Despliegue de modelos LLM con vLLM y TensorRT-LLM.;Monitoreo continuo de drifting y rendimiento de modelos.', '5+ años implementando modelos de Deep Learning en producción.;Dominio profundo de Python, PyTorch y CUDA.', 'Andrea Rincón', 'Head of AI Research', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDuZhhxS3E99w5O_teRuwCh303uV8B2cqteNE4yiw31Tjhc5GJebldRv5yWB8IkfmyJQM78zG72j0FQYTUbP0H0j6LIBIfD-r6quK3NB2oOg73k7cmEdrTbxcdzSVnT_Sxlxkiqgw7jAzhyJ0C6NUKKWUxsEhypyPaTpG3RobtjBv7ChaumWI2XooKYA2QVuTbulu-uqB_nAZhanwQuIaOITx6IrBnLJ0F5zGQeN4N_kNGZULBzOIxj'),

('coordinadora-lead-designer', 'Lead Product Designer (Design Systems)', NULL, 'Coordinadora Digital Labs', 'https://lh3.googleusercontent.com/aida-public/AB6AXuAHrnbqulc635NeCw7_zczejAHyZDVuNXQWRX2vffZhteISOn6OWtxzYCM8gzgiA0K2ysQfYdUXp3yIPARNuXQlZ8329Ojs-EdCRJDLnLBYBxEtoegMiGrWStR_J3pgVYKA0YOvCaDgJHOsi0G2dVWdb-lBWvzKskOreYinA1i2R3UlyOkbrwpa6zby87ADi6dxKqMv8pe8a6kZ3XKOmvnuEWLksEtn_tUtHueTI2kKZ8v9nOZeUt7n', 'Medellín (El Poblado) / Híbrido', 'Híbrido', '$9,000,000 - $11,500,000 COP', 'USD $2,250 - $2,850', 9000000, 11500000, 'COP', '/ Mes', 'Término Indefinido', 'Senior', 'Marketing & Diseño', 'Figma Config, Design Tokens, User Research, Storybook, Design Ops', 'Parqueadero Gratis, Medicina Prepagada, Bono Anual, Flexitime', 'Publicada hace 2 días', 'Activa', 1, '< 48h', 31, 'Responsable de la evolución y escalabilidad del ecosistema de componentes globales en Figma y código para más de 12 productos B2B de logística y despacho inteligente.', 'Construir y mantener el design system corporativo en Figma.;Colaborar con desarrolladores para alinear tokens y Storybook.;Ejecutar pruebas de usabilidad e investigación con usuarios reales.', '4+ años en diseño de producto y UI/UX complejo.;Portafolio sólido demostrando design systems escalables.', 'David Ospina', 'Design Lead', NULL),

('rappi-growth-lead', 'Growth & Performance Marketing Lead', NULL, 'Rappi Colombia / Turbo Ecosystem', 'https://lh3.googleusercontent.com/aida-public/AB6AXuB7xVY3AryZuITaUPNDqEDdymoBqyn3ewzVsBppdEVpJvz2ZaVBf5vg7zaLyzHrT2aWGnCnQoqOxmag-KF_rdx7s9dqsGkuxG89jSFlyehKKDAAQM9JV7kxkST51mq_ZSd4FX9hIY1bqQcB8CKFVlJ4Y8kNuYz8r9wVuEgkMlDLPxVPCQiAFwGyzSK8CvZlIkLM4RQkoBucE0sPKFVcOOLtQ1EVXZGGRBsd-YfQcR_j713wwISnNEzo', '100% Remoto (Colombia)', '100% Remoto', '$8,500,000 - $11,000,000 COP', 'USD $2,100 - $2,750', 8500000, 11000000, 'COP', '/ Mes', 'Término Indefinido + Stock', 'Senior', 'Ventas & Comercial', 'Growth Loops, Google Ads / Meta, Mixpanel, SQL Avanzado, CAC/LTV', 'Créditos Turbo Mensuales, Stock Options, Seguro Salud, Bono Cumplimiento', 'Publicada hace 3 días', 'Activa', 1, '< 48h', 58, 'Gestión y optimización de presupuestos de adquisición paid de más de $150K USD mensuales. Enfoque riguroso en métricas CAC/LTV, experimentación A/B y atribución multicanal.', 'Optimizar campañas de adquisición digital a gran escala.;Analizar embudos de conversión con SQL y Mixpanel.;Diseñar experimentos de retención de usuarios para Turbo.', '3+ años en marketing de performance para apps o e-commerce de alto volumen.;SQL intermedio a avanzado para analítica de datos.', 'Laura Salazar', 'Head of Growth', NULL),

('bavaria-flota-coord', 'Coordinador de Flota & Logística', NULL, 'Bavaria Logistics & Tech', 'https://lh3.googleusercontent.com/aida-public/AB6AXuABB4Xbiqyx1gNCVLZ5_eS8bCloOk94Pe99lTM7mb1kY6VwfDtMjk4T019qsr6errP6xImCDYn61Zu9fK6JxJzX42jNbd1Hi1yxDf94h9bl-ETrWxQd1opd5YSfiA1R_loynE1JZ_Ijd4v0gVFKHduXfnzAgySVZ-lKdeFcK4i5xidFRs7R2_Bw0ZcuP9FpxJ4XQF8bu8Gz7ZI__0of-HKnokJUJIJA1iEB4rqmnaG2ly0ChgtNbSNG', 'Fontibón, Bogotá (Presencial)', 'Presencial', '$4,500,000 - $5,500,000 COP', 'USD $1,100 - $1,350', 4500000, 5500000, 'COP', '/ Mes', 'Término Indefinido', 'Senior', 'Operaciones & Logística', 'Supply Chain, SAP S/4HANA, Gestión Flota, RUNT Validado, WMS', 'Casino de alimentos, Ruta corporativa, Bono semestral, Seguro de vida', 'Publicado hace 6d', 'Verificado', 1, '< 24h', 89, 'Supervisar operaciones de ruteo y despacho metropolitano con más de 120 vehículos de carga pesada. Enfoque en optimización de combustible e indicadores de entrega OTIF.', 'Coordinar despacho de más de 120 vehículos diarios.;Auditar consumos de combustible y tiempos de entrega OTIF.;Supervisar mantenimiento preventivo y corretaje de fletes.', '3+ años liderando flotas de transporte terrestre en Colombia.;Manejo de SAP y plataformas de telemetría GPS.', 'Carlos Echeverri', 'Director Logístico', NULL),

('novatech-sales-b2b', 'Ejecutivo Comercial Senior B2B (Enterprise SaaS)', NULL, 'NovaTech Fintech', 'https://lh3.googleusercontent.com/aida-public/AB6AXuABB4Xbiqyx1gNCVLZ5_eS8bCloOk94Pe99lTM7mb1kY6VwfDtMjk4T019qsr6errP6xImCDYn61Zu9fK6JxJzX42jNbd1Hi1yxDf94h9bl-ETrWxQd1opd5YSfiA1R_loynE1JZ_Ijd4v0gVFKHduXfnzAgySVZ-lKdeFcK4i5xidFRs7R2_Bw0ZcuP9FpxJ4XQF8bu8Gz7ZI__0of-HKnokJUJIJA1iEB4rqmnaG2ly0ChgtNbSNG', 'Medellín (El Poblado)', 'Híbrido', 'COP $5.5M + Variable sin techo', 'USD $1,400 + Comisiones', 5500000, 9000000, 'COP', '/ Mes', 'Término Indefinido', 'Senior', 'Ventas & Comercial', 'Hubspot, Comisiones altas, Bilingüe, Enterprise Deals, Fintech', 'Comisiones en USD, Medicina Prepagada 100%, Computador Apple', 'Publicado hace 4d', '1 Clic', 1, '< 24h', 24, 'Prospección y cierre de contratos en clientes corporativos para pasarelas de pago y cobro automático. Excelente tabla de comisiones sin techo en dólares.', 'Cerrar cuentas enterprise con tickets anuales mayores a USD $50K.;Gestionar pipeline comercial en HubSpot CRM.;Presentar demos ejecutivas a CFOs y directores de compras.', '3+ años en ventas consultivas de software B2B o fintech.;Excelentes habilidades de negociación y comunicación ejecutiva.', 'Natalia Restrepo', 'VP Commercial', NULL),

('apex-designer-uiux', 'Product Designer UI/UX (Design Systems)', NULL, 'Apex Universal', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDaLtyWrvTDHSisX7_s1dOIeOQaAbH_fMviGGcxP4PscKyDvneJ1dV7L4ubRpAN3xK2UWcoOp2E0ZQCtCv5mC1CpynAUHM8_aYvTtp5vbR3ED8q2ZOtrqVuGq6bhZj5zDsvkzTKeLXiGq-hxUyEYpVA7PjPAammTB7Yb75KeJTvgMf_MrLlIUVnXYFcK4RJWYp_9UFghIkZyb4mcnku1en1_PMGJ6EVKIb94TAIa-VqEJQHxdDyA-vs', '100% Remoto (LATAM)', '100% Remoto', '$11,200,000 - $14,400,000 COP', 'USD $2,800 - $3,600', 2800, 3600, 'USD', '/ mes', 'Contractor Internacional', 'Semi-Senior', 'Marketing & Diseño', 'Figma Variables, Design Tokens, Prototyping, Accessibility', 'USD Directo, Vacaciones flexibles, Horario libre', 'Publicado hace 5d', 'Rápido', 1, '< 48h', 27, 'Creación y documentación de componentes atómicos en Figma para aplicaciones nativas iOS y Android con millones de descargas activas.', 'Diseñar componentes accesibles siguiendo pautas WCAG 2.1.;Crear prototipos interactivos avanzados con Figma Variables.;Documentar lineamientos de interacción para ingenieros móviles.', '3+ años de experiencia como Product Designer en mobile apps.;Manejo avanzado de Figma Tokens y diseño responsive.', 'Daniela Cárdenas', 'Head of Product', NULL),

('clinica-enfermeria-uci', 'Jefe de Enfermería UCI Adultos', NULL, 'Clínica Reina Victoria', 'https://lh3.googleusercontent.com/aida-public/AB6AXuABB4Xbiqyx1gNCVLZ5_eS8bCloOk94Pe99lTM7mb1kY6VwfDtMjk4T019qsr6errP6xImCDYn61Zu9fK6JxJzX42jNbd1Hi1yxDf94h9bl-ETrWxQd1opd5YSfiA1R_loynE1JZ_Ijd4v0gVFKHduXfnzAgySVZ-lKdeFcK4i5xidFRs7R2_Bw0ZcuP9FpxJ4XQF8bu8Gz7ZI__0of-HKnokJUJIJA1iEB4rqmnaG2ly0ChgtNbSNG', 'Cali, Valle (Presencial)', 'Presencial', 'COP $4.8M - $5.5M', 'USD $1,200 - $1,380', 4800000, 5500000, 'COP', '/ Mes', 'Planta Fija Indefinida', 'Senior', 'Salud & Bienestar', 'UCI Certificada, Turnos rotativos, Prestaciones, Acreditación Salud', 'Bono de nocturnos, Alimentación en sede, Plan Exequial', 'Publicado hace 1sem', 'Activa', 1, '< 72h', 18, 'Liderazgo de turnos de cuidados intensivos, administración de medicamentos de alto riesgo y cumplimiento de estándares de acreditación en salud.', 'Coordinar turno asistencial de 16 camas UCI adultos.;Supervisar adherencia a protocolos de seguridad del paciente.;Administrar medicamentos vasoactivos y nutrición parenteral.', 'Profesional en Enfermería con tarjeta profesional vigente y Rethus.;Mínimo 2 años de experiencia en UCI adultos certificada.', 'Dra. Patricia Silva', 'Directora Médica', NULL),

('grupo-ramo-finanzas', 'Analista Financiero Senior & Tesorería', NULL, 'Grupo Ramo', 'https://lh3.googleusercontent.com/aida-public/AB6AXuABB4Xbiqyx1gNCVLZ5_eS8bCloOk94Pe99lTM7mb1kY6VwfDtMjk4T019qsr6errP6xImCDYn61Zu9fK6JxJzX42jNbd1Hi1yxDf94h9bl-ETrWxQd1opd5YSfiA1R_loynE1JZ_Ijd4v0gVFKHduXfnzAgySVZ-lKdeFcK4i5xidFRs7R2_Bw0ZcuP9FpxJ4XQF8bu8Gz7ZI__0of-HKnokJUJIJA1iEB4rqmnaG2ly0ChgtNbSNG', 'Mosquera / Bogotá (Híbrido)', 'Híbrido', 'COP $6.2M - $7.8M', 'USD $1,550 - $1,950', 6200000, 7800000, 'COP', '/ Mes', 'Término Indefinido', 'Senior', 'Finanzas & Contabilidad', 'Modelos Financieros, Power BI, Beneficios corporativos, Tesorería', 'Casino de alimentos, Auxilio transporte adicional, Fondo de empleados', 'Publicado hace 6d', 'Destacada', 1, '< 24h', 36, 'Modelación financiera de flujos de caja operativos, gestión de líneas de crédito bancarias e informes para junta directiva.', 'Elaborar proyecciones financieras y flujo de caja semanal.;Gestionar relaciones bancarias y coberturas cambiarias.;Diseñar tableros de control de rentabilidad en Power BI.', 'Profesional en Finanzas, Economía o Contaduría con especialización.;Mínimo 4 años de experiencia en tesorería corporativa o planeación financiera.', 'Julián Morales', 'Gerente Financiero', NULL)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`);

-- Candidatos
INSERT INTO `candidates` (`id`, `name`, `role`, `experience`, `location`, `avatar`, `match_score`, `vacancy_target`, `salary_expectation`, `skills`, `english_level`, `tech_test_score`) VALUES
('c-1', 'Camilo Restrepo P.', 'Backend Engineer', '6 años exp.', 'Medellín / Remoto', 'https://lh3.googleusercontent.com/aida-public/AB6AXuAgG2b7ZF809Z0Sx5HTRRrly0esUJ04OUM5yKRda56odj__MYi4E7CTCsu7qq0j-5o8OSZyl4kmaPDTLKZ7zMANUT7gREEE1XZkPzUEWvzF41o36fdqn10P1VaPakHyvelKa1TYskX5gq7pKVvCh5Ef79Bj1gmRh8JH0iREc8gd_T-m6K7rOpQ3-PurR7otyxL5Wj4Ey3KcZwFgQVFBESJYXjq7L68qFrJ4_GWGKckpgU0Al-ER5WGG', 96, 'Senior Backend Go', '$13.500.000 COP', 'Golang, Kubernetes, PostgreSQL, Kafka', 'C1 Bilingüe', '9.8 / 10'),
('c-2', 'Valentina Gómez L.', 'Product Designer', '5 años exp.', 'Bogotá / Remoto', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCkUHi2HUjWXpqR9L-UoAIu_YLgpC82SdpzJS4LZIQglargV5Vsd2O2gQEMd3WBjIvNk2YpW8Mo_O_kPhe169rx0DqtaqZadNdi8w_G_2oMacZbffgq26CckwIHMBqGq_ZZ6tRYvE5Y8Bi4rXYGoLVC4DLefkWuSDGJ2ERwfAGjPzwL9bfEnFOt62_yKxYET3jmimBLhpLbAYu5XeBGMSx-x5mWgn-o6pv5eXPjyMPJwXcPGiWyVIvV', 94, 'Product Designer Senior', '$10.000.000 COP', 'Figma Tokens, Design Systems, User Research', 'B2 Profesional', '9.4 / 10'),
('c-3', 'Hernán Montoya S.', 'Jefe de Despacho', '8 años exp.', 'Fontibón, Bogotá', 'https://lh3.googleusercontent.com/aida-public/AB6AXuB-F_U-hhP0TKLskAQgL26DdqazUZIVdr4bS2QPJoHX95YKG_jtpHl4PUPr-usm3oQjlaL-0UxHMyOprgr0LEGECHc1OdonTbIO869abUWKpvW_R4KL5zvx3SbaA34hrLigou7cWIx4RFIhPl38ZuibvA0IS3dxGSkGe_HsnEXZsUPGFfzCc952GXMFdvGe513DJaQGrpBPZMFJYnDRNIiw2DtAZqSPTHqjNMS2aPd_nsiQuYBU9utz', 91, 'Coord. Flota & Logística', '$5.200.000 COP', 'TMS / WMS, Runt Validado, Gestión Flota', 'Español Nativo', '9.1 / 10')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- Postulaciones
INSERT INTO `applications` (`id`, `job_id`, `title`, `company`, `company_logo`, `location`, `status`, `status_label`, `status_color`, `salary_display`, `contract_type`, `applied_days_ago`, `stage_progress`, `progress_percent`, `tags`, `action_title`, `action_description`, `action_deadline`, `action_btn_text`, `action_type`, `interview_topic`, `interview_datetime`, `interview_interviewer`, `interview_platform`, `test_topic`, `test_time_remaining`, `test_progress_percent`, `test_last_saved`) VALUES
('app-1', 'stark-frontend-senior', 'Senior Frontend Developer', 'Stark Cloud Corp', 'https://lh3.googleusercontent.com/aida-public/AB6AXuDaLtyWrvTDHSisX7_s1dOIeOQaAbH_fMviGGcxP4PscKyDvneJ1dV7L4ubRpAN3xK2UWcoOp2E0ZQCtCv5mC1CpynAUHM8_aYvTtp5vbR3ED8q2ZOtrqVuGq6bhZj5zDsvkzTKeLXiGq-hxUyEYpVA7PjPAammTB7Yb75KeJTvgMf_MrLlIUVnXYFcK4RJWYp_9UFghIkZyb4mcnku1en1_PMGJ6EVKIb94TAIa-VqEJQHxdDyA-vs', 'Remoto (Global / LATAM)', 'offer', 'Oferta Recibida 🎉', 'emerald', 'USD $4,200 / mes', 'Contrato Contractor Internacional', 12, '4/4 Fases concluidas', 100, 'Next.js 14, TypeScript, Tailwind CSS, Inglés C1', 'Acción requerida: Carta de oferta disponible', 'Tienes plazo hasta el Viernes 5:00 PM (COT) para revisión y firma legal electrónica.', 'Viernes 5:00 PM (COT)', 'Revisar Carta Oferta', 'review_offer', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL),

('app-2', 'nubank-fullstack-lead', 'Senior Fullstack Tech Lead', 'Nubank Colombia', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBT9fRJfKpazDGS6VGIeM1WFP_UAoMmmjl3jRzP9NO08NqK7l8YIJBO-yPbHRxMjF66DDHkcsk9_Iy7qoJQMFMdcr4IbdldaykxUFHsHlZrljqExL006AtyddVbou2997KIEufIPAiIzEB0_OmM4hgoXbm4gPWsjldBlbsd2dw1pC69zKQP5qT87QS8bF_PU3gwF4vhEBeO1DonXjyqPCoq5mMIcbVnjSP-fmjAZ6FSce7-aZoj9XI1', 'Híbrido (Bogotá, Chapinero)', 'interview', 'Entrevista Agendada', 'blue', '$16,000,000 COP / mes', 'Término Indefinido + Stock Options', 8, 'Fase 3 de 4', 75, 'Kotlin / Java, Kafka, AWS Microservicios', 'Entrevista de Arquitectura & Liderazgo', 'Jueves 24 Oct, 3:00 PM (45 min) con Tomás Restrepo (Engineering VP)', 'Jueves 24 Oct, 3:00 PM', 'Unirme a la Videollamada', 'join_interview', 'Entrevista Arquitectura & Liderazgo', 'Jueves 24 Oct, 3:00 PM (45 min)', 'Tomás Restrepo (Engineering VP)', 'Google Meet', NULL, NULL, 0, NULL),

('app-3', 'rappi-growth-lead', 'Growth & Performance Lead', 'Rappi Turbo LATAM', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBQ17TsVm7SJF3fnoAU_G-27AjXgjy8e8lFaE71Wf6e6-uNG4fKaxRr3QZQwKOMd92aXpI7T4P1oDi2nH-HJJiWoFLjSmi99xsMj6-yWnGFccFfE5RP5tSYj1eUZ4s3jhJMEw6YEjLN2NRcdfAzPym4oXYOnjAGSBBRgf4lum_F9MDF8pJldMPVmLz3m6mMow87W4EUvkDtHKvT2n02X8p1BoebM8oqEfDiF5Nu6Mg997dyfLnCRvbr', 'Remoto (Bogotá / Medellín)', 'tech_test', 'Prueba Técnica en Curso', 'amber', '$10,500,000 COP / mes', 'Término Indefinido + Bonos KPI', 5, 'Fase 2 de 4', 50, 'Growth Loops, Mixpanel, SQL Avanzado', 'Business Case: Algoritmo de Adquisición & LTV', '48 horas restantes para la entrega del entregable de optimización CAC/LTV.', '48 horas restantes', 'Continuar Prueba Técnica', 'submit_test', NULL, NULL, NULL, NULL, 'Business Case: Algoritmo de Adquisición & LTV', '48 horas restantes', 70, 'Hoy, 11:20 AM'),

('app-4', 'coordinadora-lead-designer', 'Lead Product Designer', 'Coordinadora Digital', 'https://lh3.googleusercontent.com/aida-public/AB6AXuBJed_jVLMrE-z8OoIbTHxtgn58MM16Z-QDadkPiNMb47ynbbVb9GoDekUoAoP4Zq9OJO6CM5O8OTvGNHwmWkGfY_bZ9O6acCy5FhiJUtwI67m7ou4xfrZd4eZrljaphDVwecJzQE_6kmRElSIf_1x4JM_g-sCLorANeWZGU9d_ba7OmFGISXIzJNrIb73vBGAQxiguiCj7qf4Bj38RFLoJhzVMyn-N20l0Bt6wiO7Bn9bjbwTnjDSc', 'Medellín (Poblado) / Flexible', 'review', 'En Revisión por Talento', 'gray', '$10,500,000 COP / mes', 'Término Indefinido + Medicina Prepagada', 3, 'Fase 1 de 4', 25, 'Figma Tokens, Design Systems, User Research', NULL, NULL, NULL, NULL, 'view_application', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL)
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`);

-- Testimonios
INSERT INTO `testimonials` (`id`, `quote`, `author`, `role`, `hired_in`, `avatar`) VALUES
('t-1', 'Llevaba 4 meses buscando en plataformas típicas llenas de ofertas fantasma. En Échele Camello apliqué un martes a un rol remoto para una empresa en Florida, el viernes tuve entrevista técnica y el lunes ya tenía mi contrato formal firmado.', 'Juan Pablo Giraldo', 'Fullstack Engineer', 'Contratado en 6 días', 'https://lh3.googleusercontent.com/aida-public/AB6AXuA_pk1Htaes60zApIFYtKzvYxPbUYcdB8rXsOQj1u1hRZqHK5_18CjTH8rBssGxlsXYO7fNnMcbAAjuD2X0bmApUTY-VwE5F2J2GHhEW_re-2KfSG_KSKHG9tzXXxWfrF9xPmefJP6SwbSuahDcHcnVuRciMiWhMeSoPZJRejpiKhXQiMmvjg0VmHO_g-OU_0TMFSZmEo61kMHeOHvA1pGghs_JbkJXHxEJ-SUIcUd1CMa4bjX5Jbtt'),
('t-2', 'La transparencia salarial lo es todo. Uno no pierde el tiempo aplicando a ciegas para enterarse al final de que el sueldo no cubre lo mínimo. Conseguí mi puesto como Directora Comercial con salario claro y prestaciones completas.', 'Camila Morales', 'Líder Comercial B2B', 'Bogotá', 'https://lh3.googleusercontent.com/aida-public/AB6AXuAy7PlTfnnM5fO7PcpQy92Ne2bu_O-Fxkb4H6-awe8C7M__J0tCQo30YrclBlliF04ALMSmzkwbhLKH_iW-fNpjuyd9D5gqLzRhngKlqBEGkKVK0zGj0dIl714x2Xn6J8wOR52uiVKOYKyordPt1Wx9x08c2f20_eeFozpv5SE_qDFg8SqxJnvuj8qcQor5WfVRkAQMqlIj36mwOxIZ1V3OEyXlyNrDWasWsSG3fJtHBeIOBKofZLlp'),
('t-3', 'La función de postulación en 1 Clic con el perfil verificado me ahorró tener que volver a llenar los mismos formularios eternos una y otra vez. ¡Qué chimba de plataforma para quienes de verdad queremos trabajar!', 'Andrés Felipe Vélez', 'Supervisor Operativo', 'Medellín', 'https://lh3.googleusercontent.com/aida-public/AB6AXuCjbxlrrYvJ0ZuHs09lNdqEk7I_ZwigV5bkjeJK7rzk8iQ6l7Ci6U99x4YsGoPo1FulgFBOPy4A0J_KzFULXYeGKloT7N3719Jmz2iezg6l1dOQ_gFNx9yBinZg2hiMAOlWjY2XWlio3LJeDht58bfkZ-e1Pvx_VSRTv40l1kL_IyUJvsAS287FonDCFu0SQsNiIGtw5Kb2lDxy44vSf5m6wys0HeTLUh6cPc1-gCwv8JLboORWb3zX')
ON DUPLICATE KEY UPDATE `author`=VALUES(`author`);

-- Camellos guardados iniciales
INSERT IGNORE INTO `saved_jobs` (`job_id`) VALUES
('stark-frontend-senior'),
('nubank-fullstack-lead'),
('coordinadora-lead-designer');

-- ==============================================================
-- DOMINIO: USUARIOS Y ROLES (05_PATRONES - RF-01 al 08)
-- ==============================================================
CREATE TABLE IF NOT EXISTS `users` (
  `id` VARCHAR(50) NOT NULL PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('CLIENTE', 'TRABAJADOR', 'ADMINISTRADOR') NOT NULL DEFAULT 'CLIENTE',
  `status` ENUM('PENDIENTE', 'ACTIVO', 'SUSPENDIDO') NOT NULL DEFAULT 'ACTIVO',
  `avatar` TEXT,
  `phone` VARCHAR(50),
  `location` VARCHAR(150),
  `profession` VARCHAR(150),
  `rating_avg` DECIMAL(3,2) DEFAULT 5.00,
  `rating_count` INT DEFAULT 0,
  `verification_token` VARCHAR(100),
  `recovery_token` VARCHAR(100),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================
-- DOMINIO: SOLICITUDES Y AGENDAMIENTO (05_PATRONES - State Pattern)
-- Ciclo: PENDIENTE -> ACEPTADA/RECHAZADA -> PROGRAMADA -> EN_PROCESO -> FINALIZADA / CANCELADA
-- ==============================================================
CREATE TABLE IF NOT EXISTS `solicitudes` (
  `id` VARCHAR(50) NOT NULL PRIMARY KEY,
  `cliente_id` VARCHAR(50) NOT NULL,
  `trabajador_id` VARCHAR(50) NOT NULL,
  `servicio_id` VARCHAR(100),
  `titulo` VARCHAR(255) NOT NULL,
  `descripcion` TEXT NOT NULL,
  `estado` ENUM('PENDIENTE', 'ACEPTADA', 'RECHAZADA', 'PROGRAMADA', 'EN_PROCESO', 'FINALIZADA', 'CANCELADA') NOT NULL DEFAULT 'PENDIENTE',
  `fecha_programada` DATETIME NULL,
  `direccion` VARCHAR(255) NULL,
  `valor_propuesto` DECIMAL(12,2) DEFAULT 0,
  `valor_acordado` DECIMAL(12,2) DEFAULT 0,
  `motivo_cancelacion` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================
-- DOMINIO: COMUNICACIÓN Y MENSAJERÍA (05_PATRONES - RF-41 al 46)
-- ==============================================================
CREATE TABLE IF NOT EXISTS `conversaciones` (
  `id` VARCHAR(50) NOT NULL PRIMARY KEY,
  `cliente_id` VARCHAR(50) NOT NULL,
  `trabajador_id` VARCHAR(50) NOT NULL,
  `solicitud_id` VARCHAR(50) NULL,
  `ultimo_mensaje` TEXT NULL,
  `ultimo_mensaje_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `mensajes` (
  `id` VARCHAR(50) NOT NULL PRIMARY KEY,
  `conversacion_id` VARCHAR(50) NOT NULL,
  `remitente_id` VARCHAR(50) NOT NULL,
  `contenido` TEXT NOT NULL,
  `adjunto_url` TEXT NULL,
  `adjunto_tipo` VARCHAR(50) NULL,
  `leido` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================
-- DOMINIO: NOTIFICACIONES (05_PATRONES - Observer / PubSub)
-- ==============================================================
CREATE TABLE IF NOT EXISTS `notificaciones` (
  `id` VARCHAR(50) NOT NULL PRIMARY KEY,
  `usuario_id` VARCHAR(50) NOT NULL,
  `titulo` VARCHAR(200) NOT NULL,
  `mensaje` TEXT NOT NULL,
  `tipo` ENUM('solicitud', 'mensaje', 'calificacion', 'sistema') DEFAULT 'sistema',
  `enlace` VARCHAR(255) NULL,
  `leida` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================
-- DOMINIO: REPUTACIÓN Y VALORACIONES (05_PATRONES - RF-47 al 51)
-- ==============================================================
CREATE TABLE IF NOT EXISTS `calificaciones` (
  `id` VARCHAR(50) NOT NULL PRIMARY KEY,
  `solicitud_id` VARCHAR(50) NOT NULL UNIQUE,
  `cliente_id` VARCHAR(50) NOT NULL,
  `trabajador_id` VARCHAR(50) NOT NULL,
  `puntuacion` TINYINT NOT NULL,
  `comentario` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================
-- DOMINIO: MODERACIÓN Y REPORTES (05_PATRONES - State en Reportes)
-- ==============================================================
CREATE TABLE IF NOT EXISTS `reportes` (
  `id` VARCHAR(50) NOT NULL PRIMARY KEY,
  `reportante_id` VARCHAR(50) NOT NULL,
  `tipo_entidad` ENUM('servicio', 'usuario', 'solicitud') NOT NULL,
  `entidad_id` VARCHAR(100) NOT NULL,
  `motivo` TEXT NOT NULL,
  `estado` ENUM('ABIERTO', 'EN_REVISION', 'RESUELTO', 'DESCARTADO') NOT NULL DEFAULT 'ABIERTO',
  `resolucion` TEXT NULL,
  `moderador_id` VARCHAR(50) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================
-- DATOS SEED PARA USUARIOS DE PRUEBA
-- Password para todos: "Camello2026*" (hash bcrypt)
-- ==============================================================
INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `status`, `avatar`, `phone`, `location`, `profession`, `rating_avg`, `rating_count`) VALUES
('usr-cliente-1', 'Camila Ramos', 'camila.ramos@empresa.com', '$2y$10$eO1vV5lI7vR9sJ9x6kQvU.oKqWqW5Z3F4sR5T6U7V8W9X0Y1Z2a3b', 'CLIENTE', 'ACTIVO', 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=300&q=80', '+57 301 234 5678', 'Bogotá D.C., Colombia', 'Gerente de Proyectos & Contratación', 5.00, 8),
('usr-trabajador-1', 'Camilo Restrepo', 'camilo.restrepo@echelecamello.co', '$2y$10$eO1vV5lI7vR9sJ9x6kQvU.oKqWqW5Z3F4sR5T6U7V8W9X0Y1Z2a3b', 'TRABAJADOR', 'ACTIVO', 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300&q=80', '+57 312 456 7890', 'Medellín, Antioquia', 'Desarrollador Fullstack & Consultor Cloud', 4.95, 24),
('usr-trabajador-2', 'Valentina Gómez', 'valentina.gomez@diseno.co', '$2y$10$eO1vV5lI7vR9sJ9x6kQvU.oKqWqW5Z3F4sR5T6U7V8W9X0Y1Z2a3b', 'TRABAJADOR', 'ACTIVO', 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=300&q=80', '+57 315 987 6543', 'Cali, Valle del Cauca', 'Diseñadora UX/UI & Especialista en Design Systems', 4.90, 19),
('usr-admin-1', 'Luis Yamit Tombe', 'admin@echelecamello.co', '$2y$10$eO1vV5lI7vR9sJ9x6kQvU.oKqWqW5Z3F4sR5T6U7V8W9X0Y1Z2a3b', 'ADMINISTRADOR', 'ACTIVO', 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=300&q=80', '+57 300 000 0000', 'Popayán / Remoto', 'Administrador Principal de la Plataforma', 5.00, 50)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- ==============================================================
-- DATOS SEED PARA SOLICITUDES EN DIVERSOS ESTADOS (Patrón State)
-- ==============================================================
INSERT INTO `solicitudes` (`id`, `cliente_id`, `trabajador_id`, `servicio_id`, `titulo`, `descripcion`, `estado`, `fecha_programada`, `direccion`, `valor_propuesto`, `valor_acordado`, `motivo_cancelacion`) VALUES
('sol-001', 'usr-cliente-1', 'usr-trabajador-1', 'stark-frontend-senior', 'Desarrollo de Módulo de Pagos con Pasarela Wompi', 'Necesitamos integrar la pasarela de pagos Wompi y PSE en nuestra plataforma web con webhook seguro.', 'PROGRAMADA', '2026-10-25 09:30:00', 'Modalidad Remota (Google Meet)', 3500000, 3500000, NULL),
('sol-002', 'usr-cliente-1', 'usr-trabajador-2', 'apex-designer-uiux', 'Diseño de Interfaz Móvil y Prototipo Figma', 'Rediseño completo del flujo de onboarding y dashboard de usuario para app iOS y Android.', 'EN_PROCESO', '2026-10-22 14:00:00', 'Remoto / Figma Workspace', 2800000, 2800000, NULL),
('sol-003', 'usr-cliente-1', 'usr-trabajador-1', 'stark-frontend-senior', 'Auditoría de Rendimiento Web y Core Web Vitals', 'Diagnóstico y optimización para alcanzar puntaje 95+ en Google PageSpeed Insights.', 'PENDIENTE', NULL, 'Remoto (Asíncrono)', 1500000, 0, NULL),
('sol-004', 'usr-cliente-1', 'usr-trabajador-2', 'coordinadora-lead-designer', 'Creación de Design Tokens y Guía de Estilo', 'Documentación estructurada de tokens de color, tipografía y componentes atómicos.', 'FINALIZADA', '2026-10-18 10:00:00', 'Entregado en Storybook', 2200000, 2200000, NULL),
('sol-005', 'usr-cliente-1', 'usr-trabajador-1', 'nubank-fullstack-lead', 'Migración de Monolito a Microservicios en Docker', 'Análisis previo y contenedorización de 3 APIs críticas en ambiente de pruebas.', 'RECHAZADA', NULL, 'Remoto', 4000000, 0, 'No dispongo de disponibilidad horaria para el plazo solicitado de 48 horas.')
ON DUPLICATE KEY UPDATE `titulo`=VALUES(`titulo`);

-- ==============================================================
-- DATOS SEED PARA CONVERSACIONES Y MENSAJES
-- ==============================================================
INSERT INTO `conversaciones` (`id`, `cliente_id`, `trabajador_id`, `solicitud_id`, `ultimo_mensaje`, `ultimo_mensaje_at`) VALUES
('conv-001', 'usr-cliente-1', 'usr-trabajador-1', 'sol-001', '¡Excelente! Quedamos confirmados para el kickoff el sábado a las 9:30 AM.', '2026-10-21 11:45:00'),
('conv-002', 'usr-cliente-1', 'usr-trabajador-2', 'sol-002', 'Te he compartido el enlace del prototipo en Figma con los comentarios resueltos.', '2026-10-21 12:10:00')
ON DUPLICATE KEY UPDATE `ultimo_mensaje`=VALUES(`ultimo_mensaje`);

INSERT INTO `mensajes` (`id`, `conversacion_id`, `remitente_id`, `contenido`, `adjunto_url`, `adjunto_tipo`, `leido`, `created_at`) VALUES
('msg-101', 'conv-001', 'usr-cliente-1', 'Hola Camilo, un gusto saludarte. Vimos tu portafolio y nos interesa avanzar con el módulo de pagos.', NULL, NULL, 1, '2026-10-21 10:00:00'),
('msg-102', 'conv-001', 'usr-trabajador-1', '¡Hola Camila! Muchas gracias por escribirme. Con gusto podemos agendar una sesión inicial para revisar los endpoints de Wompi.', NULL, NULL, 1, '2026-10-21 10:15:00'),
('msg-103', 'conv-001', 'usr-cliente-1', 'Perfecto. Acabo de aprobar la propuesta económica por $3.500.000 COP y programé la fecha.', NULL, NULL, 1, '2026-10-21 11:30:00'),
('msg-104', 'conv-001', 'usr-trabajador-1', '¡Excelente! Quedamos confirmados para el kickoff el sábado a las 9:30 AM.', NULL, NULL, 1, '2026-10-21 11:45:00'),
('msg-201', 'conv-002', 'usr-trabajador-2', 'Hola Camila, acabo de subir los wireframes de alta fidelidad para el flujo de checkout.', 'https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?auto=format&fit=crop&w=400&q=80', 'image', 1, '2026-10-21 11:00:00'),
('msg-202', 'conv-002', 'usr-cliente-1', '¡Quedaron preciosos! Me encanta la suavidad de los tonos pastel y la claridad de los botones.', NULL, NULL, 1, '2026-10-21 11:50:00'),
('msg-203', 'conv-002', 'usr-trabajador-2', 'Te he compartido el enlace del prototipo en Figma con los comentarios resueltos.', NULL, NULL, 0, '2026-10-21 12:10:00')
ON DUPLICATE KEY UPDATE `contenido`=VALUES(`contenido`);

-- ==============================================================
-- DATOS SEED PARA NOTIFICACIONES (Observer/PubSub)
-- ==============================================================
INSERT INTO `notificaciones` (`id`, `usuario_id`, `titulo`, `mensaje`, `tipo`, `enlace`, `leida`, `created_at`) VALUES
('notif-001', 'usr-trabajador-1', 'Nueva Solicitud Recibida', 'Camila Ramos te ha enviado una solicitud para "Auditoría de Rendimiento Web".', 'solicitud', 'requests.php?id=sol-003', 0, '2026-10-21 09:00:00'),
('notif-002', 'usr-cliente-1', 'Solicitud Programada con Éxito', 'Tu solicitud "Módulo de Pagos con Pasarela Wompi" ha sido agendada para el 25 de Octubre.', 'solicitud', 'requests.php?id=sol-001', 0, '2026-10-21 11:30:00'),
('notif-003', 'usr-cliente-1', 'Nuevo Mensaje en Chat', 'Valentina Gómez: "Te he compartido el enlace del prototipo en Figma..."', 'mensaje', 'messages.php?conv=conv-002', 0, '2026-10-21 12:10:00'),
('notif-004', 'usr-trabajador-2', 'Calificación 5 Estrellas Recibida ⭐', 'Camila Ramos valoró tu servicio "Design Tokens y Guía de Estilo" con 5 estrellas.', 'calificacion', 'requests.php?id=sol-004', 1, '2026-10-18 11:30:00')
ON DUPLICATE KEY UPDATE `titulo`=VALUES(`titulo`);

-- ==============================================================
-- DATOS SEED PARA CALIFICACIONES (1 a 5 Estrellas)
-- ==============================================================
INSERT INTO `calificaciones` (`id`, `solicitud_id`, `cliente_id`, `trabajador_id`, `puntuacion`, `comentario`, `created_at`) VALUES
('cal-001', 'sol-004', 'usr-cliente-1', 'usr-trabajador-2', 5, '¡Trabajo impecable! La guía de tokens quedó clara, modular y lista para sincronizarse con código. Recomendadísima al 100%.', '2026-10-18 11:20:00')
ON DUPLICATE KEY UPDATE `comentario`=VALUES(`comentario`);

-- ==============================================================
-- DATOS SEED PARA REPORTES DE MODERACIÓN (Patrón State)
-- ==============================================================
INSERT INTO `reportes` (`id`, `reportante_id`, `tipo_entidad`, `entidad_id`, `motivo`, `estado`, `resolucion`, `moderador_id`, `created_at`) VALUES
('rep-001', 'usr-cliente-1', 'servicio', 'stark-frontend-senior', 'Consulta sobre requisitos de prueba técnica desactualizados.', 'RESUELTO', 'Se coordinó con el líder técnico para actualizar la descripción con la versión correcta.', 'usr-admin-1', '2026-10-15 14:00:00')
ON DUPLICATE KEY UPDATE `motivo`=VALUES(`motivo`);
