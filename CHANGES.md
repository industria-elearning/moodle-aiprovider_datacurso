## 1.1.6

**Released on:** 2026-04-29

**Compatibility note:** This version is compatible **with Moodle 4.5 only**.

## Fixed
- **Removed legacy `aiprovider_datacurso_rl` table on upgrade**  
  Added an upgrade step to drop the deprecated table left behind after the ratelimit table rename, preventing schema checker errors such as `aiprovider_datacurso_rl table is not expected`.

## Added
- **Upgrade regression test for legacy table cleanup**  
  Added a PHPUnit test to cover the upgrade path that removes the old ratelimit table.

## 1.1.5

**Released on:** 2026-04-29

**Compatibility note:** This version is compatible **with Moodle 4.5 only**.

## Changed
- **Optimized ratelimit settings class lookup in admin settings**  
  Replaced dynamic class discovery per service with an explicit service-to-class map in the provider, reducing unnecessary autoload checks when loading plugin settings.
- **Added tests for ratelimit settings mapping**  
  Added PHPUnit coverage for known and unknown service ids to ensure class resolution remains predictable and maintainable.

## 1.0.10

**Released on:** 2026-02-10

**Compatibility note:** This version is compatible **with Moodle 4.5 only**.

## Fixed
- **Abstract ratelimit_settings caused fatal error**  
  Resolved a fatal error triggered when `ratelimit_settings` was treated as
  an abstract class while service-specific rate limit classes no longer
  extended it.

## Changed
- **Relaxed service binding for allowlist resolution**  
  Updated `ratelimit_settings::get_allowed_users_for_service()` to call the
  static `get_allowed_service_user_ids()` method only when it exists on the
  target service class, removing the hard requirement for inheritance and
  keeping services decoupled from the base helper.
- **Version bump**  
  Release version bumped to **1.0.10**.

## 1.0.9

**Released on:** 2026-02-10

**Compatibility note:** This version is compatible **with Moodle 4.5 only**.

## Fixed
- **Fatal error when only course generation allowlist was considered**  
  Corrected the rate limit user check that previously only evaluated the
  `local_coursegen` course creator list, which could cause incorrect
  access validation or fatal errors when other services or actions were
  configured.

## Added
- **Service/action-specific allowlist handling**  
  Extended the rate limiter so each AI-enabled service can declare its own
  user allowlist per HTTP action path (for example, `/course/v2/start` vs
  `/resources/create-mod`), keeping the access rules for different actions
  completely independent.

## Changed
- **Centralised helpers and internal clean-up**  
  Introduced small internal helpers to map paths to configuration keys and
  to extract user ids from configuration, reducing duplication and making
  future changes easier to maintain.
- **Version bump**  
  Release version bumped to **1.0.9**.

## 1.0.8

**Released on:** 2026-01-29

**Compatibility note:** This version is compatible **with Moodle 4.5 only**.

## Fixed
- **Suppress developer debug warning when listing rate-limited users**  
  Updated the rate-limit user selector query to load all required name fields (`firstnamephonetic`, `lastnamephonetic`, `middlename`, `alternatename`) so that `fullname()` no longer triggers the developer `debugging()` warning when building the allowed users lists.


## 1.0.7

**Released on:** 2026-01-26

**Compatibility note:** This version is compatible **from Moodle 4.5 to Moodle 5.1**.

## Added
- **Configurable base URLs for DataCurso AI services**  
  Added support for configurable base URLs for both the **standard** and **EU-hosted** DataCurso AI services, allowing greater flexibility across environments.
- **Optional base URL parameters in constructors**  
  Updated service constructors to accept optional base URL parameters, enabling explicit overrides when needed.

## Changed
- **Centralized base URL resolution via instance method**  
  Refactored base URL access to ensure the correct instance method is used when resolving the active base URL, improving consistency and maintainability.
- **Service initialization flow updated**  
  Adjusted internal initialization logic so all API requests correctly respect the configured base URL (standard or EU-hosted).
- **Version bump**  
  Release version bumped to **1.0.7**.


## 1.0.6

**Released on:** 2026-01-19

## Added

- **Enhanced webservice setup error logging.**  
Improved error reporting during webservice registration by including the original exception message, providing clearer diagnostics when the setup process fails.

## Changed

- **Improved boolean evaluation logic.**  
Adjusted the `is_for_ue` method to ensure proper and safe boolean comparison, preventing unintended conditional behavior.

## Fixed

- **Webservice setup debugging limitations.**  
Resolved an issue where webservice registration failures did not expose sufficient context, making troubleshooting difficult.

## Changed

- **Release bump to 1.0.6**  
Updated the plugin version and release metadata to **1.0.6** to reflect the included improvements and fixes.

## 1.0.5

**Released on:** 2025-12-04

 **Compatibility note:** This version is compatible **only with Moodle 4.5**.

## Fixed
- **Upgrade savepoint order corrected**  
  Reordered the upgrade savepoint to prevent upgrade failures related to the `aiprovider_datacurso_userlimit`

## 1.0.4

**Released on:** 2025-12-02

Fixed
- add missing capabilities and web service functions

## 1.0.3

**Released on:** 2025-12-02

 **Compatibility note:** This version is compatible **only with Moodle 4.5**.

## Added
- **Automated release workflow for the plugin.**  
  A new GitHub Actions workflow was added to streamline/automate Moodle plugin releases.
- **Support only for Moodle 4.5.**  
  Added `$plugin->supported` in `version.php` to declare Moodle 4.5 as the only supported version.

## Changed
- **Release bump to 1.0.3**  
  The plugin release number was updated to **1.0.3**.

