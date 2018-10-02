# Changes in Appnexus Client 0.x

All notable changes of the Appnexus Client 0.x release series are documented in this file using the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## [0.6.0] 20-09-2018
### Added
 - ext-json requirement is now enforced
 - static analysis via PhpStan
 - more environment variables to run functional testing
 - strict typing for many entities

### Changed
 - moved the support from php 5.6 to PHP 7.1
 - travis .ci build will now use 7.1 as environment

### Removed
 - some properties from the segment entity: (description, code, provider and price)
 - category repository is no longer supported

## [0.4.1]
### Added
 - Added exception if add-segment-billing-category  return Ok without segment-billing-category data

## [0.4]
### Added
 - Segment Billing repository service, will allow to query the Appnexus segment billing category list

## [0.3.3]
### Added
 - Category repository service, will allow to query the Appnexus category list

## [0.3.2]
### Fixed
 - Fixed a bug in the report service that was caused by the breaking changes introduced in Appnexus API v 1.17
 - Fixed a missing dependency that was causing the hydration procedure to fail
