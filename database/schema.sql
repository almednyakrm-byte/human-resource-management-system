CREATE TABLE users (
  id INT AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('guest', 'user', 'admin') NOT NULL DEFAULT 'guest',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY (email)
);

CREATE TABLE employees (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  job_title VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE budgets (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  amount DECIMAL(10, 2) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE reports (
  id INT AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

CREATE TABLE user_permissions (
  id INT AUTO_INCREMENT,
  user_id INT NOT NULL,
  permission VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE employee_permissions (
  id INT AUTO_INCREMENT,
  employee_id INT NOT NULL,
  permission VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (employee_id) REFERENCES employees(id)
);

CREATE TABLE budget_permissions (
  id INT AUTO_INCREMENT,
  budget_id INT NOT NULL,
  permission VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (budget_id) REFERENCES budgets(id)
);

CREATE TABLE report_permissions (
  id INT AUTO_INCREMENT,
  report_id INT NOT NULL,
  permission VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  FOREIGN KEY (report_id) REFERENCES reports(id)
);

INSERT INTO users (username, email, password, role)
VALUES ('admin', 'admin@example.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'admin');

INSERT INTO employees (name, job_title)
VALUES ('John Doe', 'Software Engineer');

INSERT INTO budgets (name, amount)
VALUES ('Marketing Budget', 10000.00);

INSERT INTO reports (name, description)
VALUES ('Quarterly Report', 'This is a quarterly report');

INSERT INTO user_permissions (user_id, permission)
VALUES (1, 'الرئيسية'), (1, 'قائمة الموظفين'), (1, 'قائمة الميزانيات'), (1, 'قائمة التقارير');

INSERT INTO employee_permissions (employee_id, permission)
VALUES (1, 'قائمة الموظفين');

INSERT INTO budget_permissions (budget_id, permission)
VALUES (1, 'قائمة الميزانيات');

INSERT INTO report_permissions (report_id, permission)
VALUES (1, 'قائمة التقارير');