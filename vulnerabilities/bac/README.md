# Broken Access Control Module - DVWA

This module demonstrates OWASP Top 10 2021's #1 vulnerability: Broken Access Control (BAC). It provides a practical learning environment showing how access control vulnerabilities can be exploited and properly mitigated.

## Module Overview

The module simulates a user profile viewing system with progressive security levels:

### Security Levels

#### 1. Low Security
- Basic input validation for user ID format
- No access control checks
- Direct SQL queries without sanitization
- Vulnerable to SQL injection and unauthorized access

#### 2. Medium Security
- Input validation for user ID format
- Basic cookie-based authentication
- Integer type casting for basic SQL injection protection
- Vulnerable to cookie manipulation

#### 3. High Security
- Strict input validation
- Role-based access control (RBAC)
- Prepared statements for SQL injection prevention
- Output encoding to prevent XSS
- Basic security logging
- Still vulnerable to certain timing attacks

#### 4. Impossible Security
- Comprehensive security implementation:
  - Multi-layer input validation
  - Rate limiting
  - Proper RBAC implementation
  - Prepared statements
  - Comprehensive logging
  - Security monitoring
  - Suspicious activity detection

## Common Security Features

1. Input Validation
   - All levels validate user ID format using regex: `/^\d+$/`
   - Clear error messages for invalid input
   - Additional validation layers in higher security levels

2. Access Control
   - Progressive implementation from none to comprehensive RBAC
   - Session validation
   - Role-based permissions

3. Database Security
   - Progression from raw queries to prepared statements
   - User role management
   - Audit logging capabilities

## Database Schema

```sql
-- Users table modifications
ALTER TABLE users ADD COLUMN role VARCHAR(20) DEFAULT 'user';
ALTER TABLE users ADD COLUMN account_enabled TINYINT(1) DEFAULT 1;

-- Access logging
CREATE TABLE access_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    target_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    timestamp DATETIME NOT NULL
);

-- Security monitoring
CREATE TABLE security_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    target_id VARCHAR(50),
    timestamp DATETIME NOT NULL,
    ip_address VARCHAR(45)
);
```

## Security Testing Guide

1. Input Validation Testing
   - Try non-numeric user IDs
   - Attempt SQL injection
   - Test with special characters

2. Access Control Testing
   - Attempt to view other users' profiles
   - Test admin role functionality
   - Try cookie manipulation

3. Rate Limiting Testing
   - Make multiple rapid requests
   - Test blocking mechanism
   - Verify logging of attempts

## References

- [OWASP Top 10 2021 - A01:2021 Broken Access Control](https://owasp.org/Top10/A01_2021-Broken_Access_Control/)
- [OWASP Testing Guide - Authorization Testing](https://owasp.org/www-project-web-security-testing-guide/latest/4-Web_Application_Security_Testing/05-Authorization_Testing/02-Testing_for_Bypassing_Authorization_Schema)
