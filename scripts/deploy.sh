#!/bin/bash

# IT Help Desk v2.2.0 - Deployment Script
# This script handles the complete deployment process

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
PROJECT_NAME="IT Help Desk v2.2.0"
DOCKER_COMPOSE_FILE="docker-compose.yml"
ENV_FILE=".env.docker"
BACKUP_DIR="backups"
LOG_FILE="deployment.log"

# Functions
log() {
    echo -e "${BLUE}[$(date '+%Y-%m-%d %H:%M:%S')]${NC} $1" | tee -a "$LOG_FILE"
}

error() {
    echo -e "${RED}[ERROR]${NC} $1" | tee -a "$LOG_FILE"
    exit 1
}

success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1" | tee -a "$LOG_FILE"
}

warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1" | tee -a "$LOG_FILE"
}

# Check if Docker is installed
check_docker() {
    log "Checking Docker installation..."
    if ! command -v docker &> /dev/null; then
        error "Docker is not installed. Please install Docker first."
    fi
    
    if ! command -v docker-compose &> /dev/null; then
        error "Docker Compose is not installed. Please install Docker Compose first."
    fi
    
    success "Docker and Docker Compose are installed"
}

# Check if required files exist
check_files() {
    log "Checking required files..."
    
    if [ ! -f "$DOCKER_COMPOSE_FILE" ]; then
        error "Docker Compose file $DOCKER_COMPOSE_FILE not found"
    fi
    
    if [ ! -f "$ENV_FILE" ]; then
        warning "Environment file $ENV_FILE not found. Creating from template..."
        cp .env.example "$ENV_FILE"
        warning "Please update $ENV_FILE with your configuration"
    fi
    
    success "All required files are present"
}

# Create backup
create_backup() {
    log "Creating backup..."
    
    mkdir -p "$BACKUP_DIR"
    BACKUP_NAME="backup_$(date +%Y%m%d_%H%M%S).tar.gz"
    
    # Backup database if exists
    if docker-compose -f "$DOCKER_COMPOSE_FILE" ps | grep -q mysql; then
        log "Backing up database..."
        docker-compose -f "$DOCKER_COMPOSE_FILE" exec -T mysql mysqldump -u root -proot_pass_2024_secure it_helpdesk > "$BACKUP_DIR/database_backup.sql" 2>/dev/null || warning "Database backup failed"
    fi
    
    # Backup storage
    if [ -d "storage" ]; then
        log "Backing up storage..."
        tar -czf "$BACKUP_DIR/storage_$BACKUP_NAME" storage/ 2>/dev/null || warning "Storage backup failed"
    fi
    
    success "Backup created in $BACKUP_DIR"
}

# Build and start services
deploy() {
    log "Starting deployment of $PROJECT_NAME..."
    
    # Pull latest images
    log "Pulling latest Docker images..."
    docker-compose -f "$DOCKER_COMPOSE_FILE" pull
    
    # Build application
    log "Building application image..."
    docker-compose -f "$DOCKER_COMPOSE_FILE" build --no-cache app
    
    # Start services
    log "Starting services..."
    docker-compose -f "$DOCKER_COMPOSE_FILE" up -d
    
    # Wait for services to be ready
    log "Waiting for services to be ready..."
    sleep 30
    
    # Check service health
    check_health
    
    success "Deployment completed successfully!"
}

# Check service health
check_health() {
    log "Checking service health..."
    
    # Check if containers are running
    if ! docker-compose -f "$DOCKER_COMPOSE_FILE" ps | grep -q "Up"; then
        error "Some services are not running"
    fi
    
    # Check application
    if curl -f http://localhost:8000 >/dev/null 2>&1; then
        success "Application is accessible at http://localhost:8000"
    else
        warning "Application might not be ready yet"
    fi
    
    # Check phpMyAdmin
    if curl -f http://localhost:8080 >/dev/null 2>&1; then
        success "phpMyAdmin is accessible at http://localhost:8080"
    else
        warning "phpMyAdmin might not be ready yet"
    fi
}

# Show status
show_status() {
    log "Service Status:"
    docker-compose -f "$DOCKER_COMPOSE_FILE" ps
    
    echo ""
    log "Access Information:"
    echo "🌐 Application: http://localhost:8000"
    echo "🗄️ phpMyAdmin: http://localhost:8080"
    echo "📊 MySQL: localhost:3306"
    echo ""
    echo "Default Credentials:"
    echo "👤 MySQL Root: root / root_pass_2024_secure"
    echo "👤 MySQL User: helpdesk_user / helpdesk_pass_2024"
}

# Stop services
stop() {
    log "Stopping services..."
    docker-compose -f "$DOCKER_COMPOSE_FILE" down
    success "Services stopped"
}

# Restart services
restart() {
    log "Restarting services..."
    docker-compose -f "$DOCKER_COMPOSE_FILE" restart
    success "Services restarted"
}

# Show logs
show_logs() {
    log "Showing logs..."
    docker-compose -f "$DOCKER_COMPOSE_FILE" logs -f
}

# Main menu
main() {
    echo -e "${BLUE}"
    echo "╔══════════════════════════════════════════════════════════════╗"
    echo "║              IT Help Desk v2.2.0 Deployment                ║"
    echo "╚══════════════════════════════════════════════════════════════╝"
    echo -e "${NC}"
    
    case "${1:-menu}" in
        "deploy")
            check_docker
            check_files
            create_backup
            deploy
            show_status
            ;;
        "status")
            show_status
            ;;
        "stop")
            stop
            ;;
        "restart")
            restart
            ;;
        "logs")
            show_logs
            ;;
        "backup")
            create_backup
            ;;
        "menu"|*)
            echo "Usage: $0 {deploy|status|stop|restart|logs|backup}"
            echo ""
            echo "Commands:"
            echo "  deploy   - Full deployment with backup"
            echo "  status   - Show service status"
            echo "  stop     - Stop all services"
            echo "  restart  - Restart all services"
            echo "  logs     - Show service logs"
            echo "  backup   - Create backup"
            ;;
    esac
}

# Run main function
main "$@"