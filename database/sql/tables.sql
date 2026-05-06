-- Tabla de clientes
CREATE TABLE IF NOT EXISTS client(
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- Datos
    document_type ENUM('DNI', 'RUC', 'CE', 'PASSPORT', 'OTHER') NOT NULL,
    document_number VARCHAR(12) NOT NULL,
    name VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(9),
    -- Auditoría
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT uq_client UNIQUE(document_type, document_number)
);
-- Tabla de trabajadores
CREATE TABLE IF NOT EXISTS worker(
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- Datos
    name VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    document_type ENUM('DNI', 'RUC', 'CE', 'PASSPORT', 'OTHER') NOT NULL,
    document_number VARCHAR(12) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(9),
    -- Auditoría
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT uq_worker UNIQUE(document_type, document_number)
);
-- Tabla de credenciales
CREATE TABLE IF NOT EXISTS credential(
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- Referencias
    id_worker INT NOT NULL,
    -- Datos
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL,
    -- Auditoría
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    CONSTRAINT uq_username UNIQUE (username),
    CONSTRAINT uq_active_user UNIQUE (id_worker),
    CONSTRAINT fk_credential_worker FOREIGN KEY (id_worker) REFERENCES worker(id) ON DELETE CASCADE
);
-- Tabla de ventas
CREATE TABLE IF NOT EXISTS sale(
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- Referencias
    id_client INT NOT NULL,
    id_worker INT NOT NULL,
    -- Datos
    total_net DECIMAL (12, 2) NOT NULL,
    total_taxes DECIMAL (12, 2) NOT NULL,
    total_amount DECIMAL(12, 2) NOT NULL,
    status ENUM('COMPLETED', 'CANCELLED') NOT NULL DEFAULT 'COMPLETED',
    -- Auditoría
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    CONSTRAINT fk_sale_client FOREIGN KEY (id_client) REFERENCES client(id),
    CONSTRAINT fk_sale_worker FOREIGN KEY (id_worker) REFERENCES worker(id)
);
-- Catálogo con categoria de los productos
CREATE TABLE IF NOT EXISTS product_category(
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- Datos
    name VARCHAR(100) NOT NULL,
    -- Auditoría
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT uq_category_name UNIQUE(name)
);
-- Tabla de productos
CREATE TABLE IF NOT EXISTS product(
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- Referencias
    id_product_category INT NOT NULL,
    -- Datos
    name VARCHAR(100) NOT NULL,
    description TEXT,
    sale_price DECIMAL(12, 2) NOT NULL,
    image_path VARCHAR(255),
    -- Auditoría
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT uq_product_name UNIQUE(id_product_category, name),
    CONSTRAINT fk_product_product_category FOREIGN KEY (id_product_category) REFERENCES product_category(id)
);
-- Tabla de proveedores
CREATE TABLE IF NOT EXISTS supplier(
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- Datos
    name VARCHAR(100),
    email VARCHAR(255),
    phone VARCHAR(9),
    document_type ENUM('DNI', 'RUC', 'CE', 'PASSPORT', 'OTHER') NOT NULL,
    document_number VARCHAR(12) NOT NULL,
    -- Auditoría
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT uq_supplier UNIQUE(document_type, document_number)
);
-- Tabla de lotes
CREATE TABLE IF NOT EXISTS batch(
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- Referencias
    id_product INT NOT NULL,
    id_supplier INT NOT NULL,
    -- Datos
    initial_stock INT NOT NULL,
    current_stock INT NOT NULL,
    purchase_price DECIMAL (12, 2) NOT NULL,
    -- Auditoría
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    CONSTRAINT fk_batch_product FOREIGN KEY (id_product) REFERENCES product(id),
    CONSTRAINT fk_batch_supplier FOREIGN KEY (id_supplier) REFERENCES supplier(id)
);
-- Tabla de descuentos
CREATE TABLE IF NOT EXISTS discount(
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- Datos
    code VARCHAR(50) NOT NULL,
    type_use ENUM('AUTOMATIC', 'MANUAL') NOT NULL,
    type_discount ENUM('AMOUNT', 'PERCENTAGE') NOT NULL,
    -- Cantidad máxima de desscuento (fija o porcentaje)
    amount DECIMAL(12, 2) NOT NULL,
    -- Rango para aplicar descuento
    minimum_amount DECIMAL (12, 2) NOT NULL,
    maximum_amount DECIMAL (12, 2) NOT NULL,
    expiration_date DATETIME NOT NULL,
    use_limit INT NOT NULL,
    -- Cómo se descontará el limite de usos
    type_limit ENUM('FOR_PRODUCT', 'FOR_SALE', 'UNLIMITED') NOT NULL,
    -- Auditoría
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT uq_discount_code UNIQUE(code)
);
-- Tabla de detalles de venta
CREATE TABLE IF NOT EXISTS sale_detail(
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- Referencias
    id_sale INT NOT NULL,
    id_batch INT NOT NULL,
    id_discount INT,
    -- Datos
    quantity INT NOT NULL,
    unit_price DECIMAL (12, 2) NOT NULL,
    discount_amount DECIMAL (12, 2),
    is_active BOOLEAN DEFAULT TRUE,
    -- Auditoría
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    CONSTRAINT fk_sale_detail_sale FOREIGN KEY (id_sale) REFERENCES sale(id),
    CONSTRAINT fk_sale_detail_batch FOREIGN KEY (id_batch) REFERENCES batch(id),
    CONSTRAINT fk_sale_detail_discount FOREIGN KEY (id_discount) REFERENCES discount(id)
);