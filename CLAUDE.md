# Codepress Extensions

Collection of extensions for the Codepress (Laravel + WordPress) framework. Each extension lives in its own directory
with a Hook class and a Readme.md, and is registered by the application as a Hookable/Service Provider.

## Releasing a change

Every update to the package needs both of these:

1. **Bump the version in `composer.json`** (`"version"`), following [Semantic Versioning](https://semver.org/):
   - patch (`1.1.0` → `1.1.1`) for bug fixes,
   - minor (`1.1.0` → `1.2.0`) for new extensions or features that don't break existing usage,
   - major (`1.1.0` → `2.0.0`) for breaking changes (renamed classes, ACF fields, hooks, etc.).
2. **Add an entry to `CHANGELOG.md`** under a new heading `## [x.y.z] - YYYY-MM-DD` with the same version, grouped
   under `### Added`, `### Changed`, `### Fixed` or `### Removed`. Prefix each line with the extension name.
   Move anything listed under `## [Unreleased]` into that version.

Also update the root `README.md` when an extension is added or removed, and the extension's own `Readme.md` when its
installation or usage changes.
