# Changelog

All notable changes to this project will be documented in this file.

## 15.02.2024
### Changed
- Removed outdated information from README. 
- Added note about env variables
 
## 01.01.2024
### Added
- Added Docker compose watch for dev environment. This allows to update the code without requiring a local set-up or reloading constantly via --build: [More info](https://docs.docker.com/compose/file-watch/)
### Changed
- Bumped php version to 8.4
- Refactored all of the PHP code to follow the new requirements. More noteable "Dynamic Properties". [More info](https://www.php.net/manual/en/migration82.deprecated.php)
- Fixed `favicon.ico` missing
