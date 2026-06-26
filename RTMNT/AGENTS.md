# RTMNT - Project Framework Documentation

## Overview

This project was created using the **tito** framework generator, which provides
a standardized C++ project structure with web interface capabilities.

## Project Structure

The tito framework creates the following directory structure:

```
RTMNT/
├── pix_/          # Image and graphics resources
├── data_/         # Data files and databases
├── docs_/         # Documentation files
├── incl_/         # Include files and headers
├── html_/         # Web interface files
│   └── index.php  # Main web interface
├── RTMNT.cpp    # Main C++ source file
├── Makefile       # Build configuration
└── AGENTS.md      # This file
```

## Building the Project

The project uses a standard Makefile with the following targets:

- `make` or `make all` - Compile the C++ application
- `make clean` - Remove compiled binaries
- `make run` - Compile and run the application
- `make install` - Install the binary to ~/.local/bin

### Build Requirements

- C++23 compatible compiler (g++)
- Standard C++ library

## The C++ Application

The main application (`RTMNT.cpp`) is a template that includes:

- Standard C++ headers for common operations
- Exception handling with try-catch blocks
- Console output functionality

## Web Interface

The `html_/index.php` file provides a web-based interface to interact with
the C++ application:

- Accepts user input through an HTML form
- Executes the C++ application with the provided input
- Displays the output in a formatted display area
- Uses the minos.css stylesheet from omega-prime.pictures

### Web Server Setup

To use the web interface:

1. Ensure PHP is installed with shell_exec enabled
2. The compiled RTMNT binary must be in the project root
3. Set appropriate permissions for the PHP script to execute the binary
4. Serve the html_ directory through a web server (Apache, Nginx, or PHP's built-in server)

Example using PHP's built-in server:
```bash
cd html_
php -S localhost:8000
```

## Tito Framework Commands

The tito tool (built from x0.cpp) provides these commands:

- `tito --create <name>` - Create directory structure only
- `tito --docpp <name>` - Create C++ source and Makefile
- `tito --all <name>` - Create complete project (directories + files)

## For AI Coding Agents

When modifying this project, please note:

1. **Build System**: The project uses Make with C++23 standards
2. **Entry Point**: Main logic is in `RTMNT.cpp`
3. **Web Integration**: PHP interface calls the compiled binary via shell_exec
4. **Directory Convention**: Subdirectories use trailing underscore (e.g., `html_/`, `data_/`)
5. **Error Handling**: Template includes exception handling - maintain this pattern
6. **CSS**: Web pages reference external minos.css - do not inline styles

### Development Workflow

1. Modify the C++ source code as needed
2. Run `make` to compile
3. Test via command line: `./RTMNT`
4. Test via web interface: Access index.php through web server
5. Use `make install` to install to PATH when ready

### Code Style

- Use modern C++ features (C++23)
- Maintain exception handling blocks
- Follow existing namespace conventions
- Keep web interface simple and functional

## License

Please add your license information here.

## Author

Please add author information here.
