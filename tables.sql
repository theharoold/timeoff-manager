CREATE TABLE employees (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    fname VARCHAR(50) NOT NULL,
    lname VARCHAR(50) NOT NULL,
    phone VARCHAR(50),
    is_manager INT(1) DEFAULT 0 NOT NULL,
    job_title VARCHAR(50) NOT NULL,
    is_reset_password INT(1) DEFAULT 0 NOT NULL,
    create_time DATETIME NOT NULL
);

CREATE TABLE addresses (
    employee_id INT PRIMARY KEY,
    address VARCHAR(50),
    zip_code VARCHAR(10),
    city VARCHAR(50),
    country VARCHAR(50),
    CONSTRAINT contact_details_fk FOREIGN KEY (employee_id) REFERENCES employees(id)
);

CREATE TABLE requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    create_time DATETIME NOT NULL,
    CONSTRAINT requests_fk_emp FOREIGN KEY (employee_id) REFERENCES employees(id)
);

CREATE TABLE request_statuses (
    request_id INT,
    manager_id INT,
    updated_on DATETIME,
    status VARCHAR(20) DEFAULT 'PROCESSING',
    CONSTRAINT request_statuses_fk_req FOREIGN KEY (request_id) REFERENCES requests(id),
    CONSTRAINT request_statuses_fk_mng FOREIGN KEY (manager_id) REFERENCES employees(id)
);

CREATE TABLE events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    description VARCHAR(100),
    is_workday INT(1) NOT NULL DEFAULT 0,
    event_date DATE NOT NULL
);