# Technify University Portal

A comprehensive university management system built with PHP, MySQL, and Docker.

## Getting Started

### Prerequisites
- Docker and Docker Compose
- Web browser

### Running the Application

1. Start the Docker containers
   ```Terminal:
   docker-compose up
   ```

2. Access the website
   ```
   http://localhost
   ```

3. Access PHPMyAdmin (database management)
   ```
   http://localhost:8081
   ```

## Login Credentials

### Website Users

| Role         | Username    | Password    | Access Level                                |
|--------------|-------------|-------------|---------------------------------------------|
| Admin        | admin       | admin       | Full administrative access                  |
| Lecturer     | lecturer    | lecturer    | Course management and grading               |
| Student      | student     | student     | Course enrollment and assignment submission |

### Database Access (PHPMyAdmin)

| Username | Password | Access Level   |
|----------|----------|----------------|
| student  | student  | Full access    |

## Design Elements

### Color Theme (Ocean)

| Element         | Color Value            | Description                                      |
|-----------------|------------------------|--------------------------------------------------|
| Background      | #0f2027 → #2c5364     | Dark ocean gradient, deep and moody blues        |
| Card Background | rgba(255,255,255,0.08) | Translucent white with 8% opacity (very subtle)  |
| Text            | #e8f1f2               | Very light teal/white for high contrast text     |
| Button BG       | #4dd0e1               | Bright cyan (teal) for strong call-to-action     |
| Button Hover    | #33c5d9               | Slightly darker cyan                             |
| Footer Text     | #cccccc               | Muted light gray                                 |

### Images Used

| Page    | Source                                                                              |
|---------|------------------------------------------------------------------------------------|
| Portal  | [Unsplash - Bookshelves](https://unsplash.com/photos/shallow-focus-photography-of-bookshelfs-ggeZ9oyI-PE) |
| Login   | [Unsplash - Assorted Books](https://unsplash.com/photos/assorted-books-PBNbMX6jtBM) |
| Home    | [Unsplash - Open Book](https://unsplash.com/photos/photo-of-open-book-XCwsOj_RUjE) |

### Libraries Used

- **Chart.js**: JavaScript charting library
  - CDN: https://cdn.jsdelivr.net/npm/chart.js
  - Used for administrative dashboards and analytics


