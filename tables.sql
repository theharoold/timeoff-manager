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
    description VARCHAR(300),
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    create_time DATETIME NOT NULL,
    CONSTRAINT requests_fk_emp FOREIGN KEY (employee_id) REFERENCES employees(id)
);

CREATE TABLE request_statuses (
    request_id INT PRIMARY KEY,
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

CREATE TABLE contact_forms (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    message VARCHAR(500) NOT NULL,
    create_time DATETIME NOT NULL,
    CONSTRAINT contact_forms_fk FOREIGN KEY (employee_id) REFERENCES employees(id)
);

CREATE TABLE surveys (
    id INT PRIMARY KEY AUTO_INCREMENT,
    question VARCHAR(200) NOT NULL,
    answers VARCHAR(200) NOT NULL
);

CREATE TABLE survey_responses (
    employee_id INT,
    survey_id INT,
    answer VARCHAR(200) NOT NULL,
    CONSTRAINT survey_responses_pk PRIMARY KEY (employee_id, survey_id),
    CONSTRAINT survey_responses_fk_emp FOREIGN KEY (employee_id) REFERENCES employees(id),
    CONSTRAINT survey_responses_fk_srv FOREIGN KEY (survey_id) REFERENCES surveys(id)
);

INSERT INTO employees (email, password, fname, lname, job_title, is_manager, create_time)
VALUES ('hr@office.rs', '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', 'Lucia', 'Collins', 'HR Manager', 1, NOW());

INSERT INTO addresses (employee_id) VALUES (1);