# AGENTS.md - Tycho Station Development Guide

## Build/Test/Lint Commands
- `make deploy` - Main deployment command (checks file sizes, commits, pushes, copies to /var/www/html)
- `make check-size` - Check for files larger than 10MB before deployment
- `make git-push MSG="message"` - Add, commit, and push changes
- `php -l filename.php` - Syntax check individual PHP files
- `php -S localhost:8000` - Start local development server
-  `docker run --rm -v $(pwd):/data phpdoc/phpdoc`  - Generate PHP Documentation

## Code Style Guidelines
- **Language**: PHP 8.1+ with HTML/CSS frontend
- **File Structure**: 
  - Modular directories (OLMA/, XSRB/, PLLE/, etc.) with index.php entry points
  - **Modular Architecture**: Separate calculation logic from presentation logic
    - `calculations.php`: Pure calculation functions (no side effects)
    - `calculate.php`: Web interface logic and form handling
    - `index.php`: Main form interface
    - `retirement_projections.php`: Interactive form for customizable retirement projections
- **PHP Style**: 
  - Use `<?php` opening tags, '?>' closing tag.
  - Snake_case for variables: `$current_pos`, `$the_height`
  - Functions use snake_case: `style_option()`, `nicebuttonlink()`
  - Include files with `include 'filename.php'`
  - Prefer functional programming (array_map) over foreach 
  - **Separation of Concerns**: Keep pure functions separate from presentation logic
- **HTML/CSS**:  css classes should be facoreed into DIRECTORY_NAME.css and included in the html file
- **Error Handling**: Use `exec()` return codes, file operations with basic error checking
- **Security**: Basic input validation with `$_POST` checks and `file_put_contents()`
- **Comments**: Add key features of changes made as comments in the file
- **Function Description**:  Add comments for function descriptions in PHPDoc.


## Testing
- Manual testing via web interface at localhost or deployment server
- Check Apache error logs: `make apacheerrors` (copies /var/log/apache2/error.log)
- **TDD Directory**: `TDD/` contains test files for Test-Driven Development
  - `php TDD/test_refactor.php` - Comprehensive test suite for modular calculations.php functions
  - `php TDD/test_projections.php` - Test suite for retirement_projections.php functionality
  - Tests validate modular architecture with functional programming using array_map()
  - Verifies backward compatibility and data structure integrity
  - Tests pure calculation functions in isolation from presentation logic
  - Tests custom projection calculations with user-specified parameters
- No automated test framework - verify functionality through web UI

## Deployment
- Files deployed to `/var/www/html/` via rsync
- Apache/PHP web server environment
- Git-based version control with automatic deployment pipeline
