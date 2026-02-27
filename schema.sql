-- CyborX Database Schema for PostgreSQL

CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    telegram_id VARCHAR(64) UNIQUE NOT NULL,
    username VARCHAR(255),
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    profile_picture TEXT,
    email VARCHAR(255),
    password_hash VARCHAR(255),
    otp_secret VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    status VARCHAR(20) DEFAULT 'free',
    plan_name VARCHAR(50),
    credits INTEGER DEFAULT 100,
    xcoin INTEGER DEFAULT 0,
    kcoin INTEGER DEFAULT 0,
    lives INTEGER DEFAULT 0,
    charges INTEGER DEFAULT 0,
    expiry_date TIMESTAMP,
    theme_preference VARCHAR(20) DEFAULT 'dark',
    online_status VARCHAR(20) DEFAULT 'offline',
    last_login TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    proxy_host VARCHAR(255),
    proxy_port INTEGER,
    proxy_username VARCHAR(255),
    proxy_password VARCHAR(255),
    ptype VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS redeem_codes (
    id SERIAL PRIMARY KEY,
    code VARCHAR(64) UNIQUE NOT NULL,
    type VARCHAR(20) NOT NULL,
    credits INTEGER DEFAULT 0,
    plan_name VARCHAR(50),
    plan_days INTEGER DEFAULT 0,
    max_uses INTEGER DEFAULT 1,
    used_count INTEGER DEFAULT 0,
    created_by INTEGER REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP
);

CREATE TABLE IF NOT EXISTS redeem_history (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id),
    code VARCHAR(64) NOT NULL,
    redeemed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS settings (
    key VARCHAR(64) PRIMARY KEY,
    val TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_users_telegram_id ON users(telegram_id);
CREATE INDEX IF NOT EXISTS idx_users_status ON users(status);
CREATE INDEX IF NOT EXISTS idx_redeem_codes_code ON redeem_codes(code);
CREATE INDEX IF NOT EXISTS idx_redeem_history_user_id ON redeem_history(user_id);
