-- IT Help Desk v2.2.0 - Database Initialization Script
-- This script will run automatically when the MySQL container starts for the first time

-- Create additional database users (optional)
-- CREATE USER 'backup_user'@'%' IDENTIFIED BY 'backup_pass_2024';
-- GRANT SELECT, LOCK TABLES, SHOW VIEW, EVENT, TRIGGER ON it_helpdesk.* TO 'backup_user'@'%';

-- Optimize database for first run
SET GLOBAL innodb_fast_shutdown = 0;

-- Create indexes for better performance (will be created by Laravel migrations)
-- These are examples of what Laravel will create automatically:

-- Example: Index for tickets table
-- CREATE INDEX idx_tickets_status ON tickets(status);
-- CREATE INDEX idx_tickets_priority ON tickets(priority);
-- CREATE INDEX idx_tickets_user_id ON tickets(user_id);
-- CREATE INDEX idx_tickets_created_at ON tickets(created_at);

-- Example: Index for users table
-- CREATE INDEX idx_users_email ON users(email);
-- CREATE INDEX idx_users_role ON users(role);

-- Example: Index for ticket_responses table
-- CREATE INDEX idx_ticket_responses_ticket_id ON ticket_responses(ticket_id);
-- CREATE INDEX idx_ticket_responses_user_id ON ticket_responses(user_id);

-- Insert default data (optional)
-- INSERT INTO categories (name, description, created_at, updated_at) VALUES
-- ('General', 'General IT support requests', NOW(), NOW()),
-- ('Hardware', 'Hardware related issues', NOW(), NOW()),
-- ('Software', 'Software installation and troubleshooting', NOW(), NOW()),
-- ('Network', 'Network connectivity issues', NOW(), NOW());

FLUSH PRIVILEGES;